<?php

namespace Controllers;

use Middleware\AuthMiddleware;
use Services\GenerateResponse;
use Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class UserController {

  private $user;
  private $generateResponse;
  private $executionStartTime;

  public function __construct() {
    $this->user = new User();
    $this->generateResponse = new GenerateResponse();
    $this->executionStartTime = microtime(true);
  }

  private function generateTokens($userId, $email) {
    if (!isset($_ENV['JWT_SECRET']) || !isset($_ENV['REFRESH_TOKEN_SECRET'])) {
      throw new Exception('JWT secrets not configured');
    }

    // Generate access token (short-lived, 15 minutes)
    $accessToken = JWT::encode([
      'user_id' => $userId,
      'email' => $email,
      'exp' => time() + (15 * 60), // 15 minutes
      'iat' => time(),
      'type' => 'access'
    ], $_ENV['JWT_SECRET'], 'HS256');

    // Generate refresh token (long-lived, 7 days)
    $refreshToken = JWT::encode([
      'user_id' => $userId,
      'exp' => time() + (7 * 24 * 60 * 60), // 7 days
      'iat' => time(),
      'type' => 'refresh'
    ], $_ENV['REFRESH_TOKEN_SECRET'], 'HS256');

    return ['accessToken' => $accessToken, 'refreshToken' => $refreshToken];
  }

  private function validateEmail($email) {
    $email = trim($email);

    // Check for dangerous characters 
    if (preg_match('/[\r\n\t,;<>&]/', $email)) {
      return false;
    }

    // Single validation check is sufficient
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
    }

    return $email;
  }

  public function login() {
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      
      if (!isset($data['email']) || !isset($data['password'])) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Missing required fields'
        );
      }

      $validatedEmail = $this->validateEmail($data['email']);
      if (!$validatedEmail) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid email format'
        );
      }

      $user = $this->user->findByEmail($validatedEmail);
      if (!$user || !password_verify(trim($data['password']), $user['password'])) {
        return $this->generateResponse->send(
          'Failure',
          401,
          'Invalid email or password'
        );
      }

      // Generate tokens
      $tokens = $this->generateTokens($user['user_id'], $user['email']);
      
      // Store refresh token hash in database
      $this->user->storeRefreshToken($user['user_id'], hash('sha256', $tokens['refreshToken']));

      unset($user['password']);

      return $this->generateResponse->send(
        'Success',
        200,
        'Login successful',
        [
          'user' => $user,
          'accessToken' => $tokens['accessToken'],
          'refreshToken' => $tokens['refreshToken']
        ]
      );
    } catch (Exception $e) {
      error_log("Login error: " . $e->getMessage());
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error'
      );
    }
  }

  public function refreshToken() {
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      
      if (!isset($data['refreshToken'])) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Refresh token required'
        );
      }

      try {
        $decoded = JWT::decode($data['refreshToken'], new Key($_ENV['REFRESH_TOKEN_SECRET'], 'HS256'));
        
        if ($decoded->type !== 'refresh') {
          throw new Exception('Invalid token type');
        }

        // Verify refresh token exists in database
        $tokenHash = hash('sha256', $data['refreshToken']);
        $isValid = $this->user->verifyRefreshToken($decoded->user_id, $tokenHash);
        
        if (!$isValid) {
          throw new Exception('Invalid refresh token');
        }

        // Get user details
        $user = $this->user->findById($decoded->user_id);
        if (!$user) {
          throw new Exception('User not found');
        }

        // Generate new tokens
        $tokens = $this->generateTokens($user['user_id'], $user['email']);
        
        // Update refresh token in database
        $this->user->storeRefreshToken($user['user_id'], hash('sha256', $tokens['refreshToken']));

        return $this->generateResponse->send(
          'Success',
          200,
          'Tokens refreshed successfully',
          [
            'accessToken' => $tokens['accessToken'],
            'refreshToken' => $tokens['refreshToken']
          ]
        );
      } catch (Exception $e) {
        return $this->generateResponse->send(
          'Failure',
          401,
          'Invalid refresh token'
        );
      }
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error'
      );
    }
  }

  public function logout() {
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      
      if (!isset($data['refreshToken'])) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Refresh token required'
        );
      }

      try {
        $decoded = JWT::decode($data['refreshToken'], new Key($_ENV['REFRESH_TOKEN_SECRET'], 'HS256'));
        
        // Remove refresh token from database
        $this->user->removeRefreshToken($decoded->user_id, hash('sha256', $data['refreshToken']));

        return $this->generateResponse->send(
          'Success',
          200,
          'Logout successful'
        );
      } catch (Exception $e) {
        // If token is invalid, still return success as the frontend will clear tokens
        return $this->generateResponse->send(
          'Success',
          200,
          'Logout successful'
        );
      }
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error'
      );
    }
  }

  public function register() {
    $this->executionStartTime = microtime(true);

    try {
      // Check JSON decode errors
      $data = json_decode(file_get_contents('php://input'), true);
      error_log("Data: " . json_encode($data));
      if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid JSON format'
        );
      }

      // Validate required fields
      $requiredFields = ['email', 'password', 'fullname'];
      foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
          return $this->generateResponse->send(
            'Failure',
            400,
            "Missing required field: {$field}"
          );
        }
      }

      // Validate email and check for existing user
      $validatedEmail = $this->validateEmail($data['email']);
      if (!$validatedEmail) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid email format'
        );
      }

      if ($this->user->findByEmail($validatedEmail)) {
        return $this->generateResponse->send(
          'Failure',
          409, // Conflict
          'Email already in use'
        );
      }

      $password = trim($data['password']);
      if (strlen($password) < 8) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Password must be at least 8 characters'
        );
      }

      if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Password must contain at least one uppercase letter'
        );
      }

      $fullname = trim(htmlspecialchars($data['fullname']));
      if (empty($fullname)) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Name cannot be empty'
        );
      }

      $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

      $userData = [
        'email' => $validatedEmail,
        'password' => $hashedPassword,
        'full_name' => $fullname
      ];

      // Create new user
      $newUser = $this->user->create($userData);

      if (!$newUser) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'User registration failed'
        );
      }

      $tokens = $this->generateTokens($newUser['user_id'], $newUser['email']);

      // Store refresh token hash in database
      $this->user->storeRefreshToken($newUser['user_id'], hash('sha256', $tokens['refreshToken']));

      unset($newUser['password']); // Remove password from response

      return $this->generateResponse->send(
        'Success',
        201,
        'User registered successfully',
        [
          'user' => $newUser,
          'accessToken' => $tokens['accessToken'],
          'refreshToken' => $tokens['refreshToken']
        ] 
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function updateUser() {
    $this->executionStartTime = microtime(true);
    
    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Check JSON decode errors
      $data = json_decode(file_get_contents('php://input'), true);
      if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid JSON format'
        );
      }

      // Validate update data
      if (empty($data)) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'No update data provided'
        );
      }

      // Validate specific fields if they exist in update data
      if (isset($data['email'])) {
        $validatedEmail = $this->validateEmail($data['email']);
        if (!$validatedEmail) {
          return $this->generateResponse->send(
            'Failure',
            400,
            'Invalid email format'
          );
        }
        // Check if email is already taken by another user
        $existingUser = $this->user->findByEmail($validatedEmail);
        if ($existingUser && $existingUser['user_id'] !== $user['user_id']) {
          return $this->generateResponse->send(
            'Failure',
            409,
            'Email already in use'
          );
        }
        $data['email'] = $validatedEmail;
      }

      if (isset($data['name'])) {
        $data['name'] = trim(htmlspecialchars($data['name']));
        if (empty($data['name'])) {
          return $this->generateResponse->send(
            'Failure',
            400,
            'Name cannot be empty'
          );
        }
      }

      // Update user
      $updatedUser = $this->user->update($user['user_id'], $data);
      if (!$updatedUser) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'User update failed'
        );
      }

      unset($updatedUser['password']); // Remove password from response

      return $this->generateResponse->send(
        'Success',
        200,
        'User updated successfully',
        ['user' => $updatedUser]
      );

    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function getUserProfile() {
    $this->executionStartTime = microtime(true);

    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Your get profile logic here
      $userProfile = $this->user->getProfileById($user['user_id']);
      if (!$userProfile) {
        return $this->generateResponse->send(
          'Failure',
          404,
          'Profile not found'
        );
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'Profile retrieved successfully',
        ['profile' => $userProfile] 
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function getCurrentUser() {
    $this->executionStartTime = microtime(true);

    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Get current user
      $currentUser = $this->user->findById($user['user_id']);
      if (!$currentUser) {
        error_log("User not found in database");

        return $this->generateResponse->send(
          'Failure',
          404,
          'User not found'
        );
      }

      unset($currentUser['password']); // Remove password from response

      return $this->generateResponse->send(
        'Success',
        200,
        'Current user retrieved successfully',
        ['user' => $currentUser]
      );

    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function deleteUser() {
    $this->executionStartTime = microtime(true);

    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Your delete logic here
      $deletedUser = $this->user->delete($user['user_id']);
      if (!$deletedUser) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'User deletion failed'
        );
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'User deleted successfully'
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }
}
