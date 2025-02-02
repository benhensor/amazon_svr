<?php

namespace Controllers;

use Middleware\AuthMiddleware;
use Services\GenerateResponse;
use Models\User;
use Firebase\JWT\JWT;
use Exception;


class UserController
{
  private $user;
  private $generateResponse;
  private $executionStartTime;

  public function __construct() {
    $this->user = new User();
    $this->generateResponse = new GenerateResponse();
    $this->executionStartTime = microtime(true);
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
    $this->executionStartTime = microtime(true);

    header('Content-Type: application/json');

    try {
      // Validate required fields
      $data = json_decode(file_get_contents('php://input'), true);
      error_log("Login attempt data: " . json_encode($data));

      // Check JSON decode errors
      if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid JSON format'
        );
      }

      // Check for missing fields
      if (!isset($data['email']) || !isset($data['password'])) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Missing required fields: email and password'
        );
      }

      // Validate email
      $validatedEmail = $this->validateEmail($data['email']);
      if (!$validatedEmail) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid email format'
        );
      }

      // Trim password
      $password = trim($data['password']);

      // Your login logic here
      $user = $this->user->findByEmail($validatedEmail);
      error_log("User lookup result: " . ($user ? "User found" : "User not found"));
      if (!$user) {
        return $this->generateResponse->send(
          'Failure',
          401,
          'Invalid email or password'
        );
      }

      // Verify password
      $passwordMatch = password_verify($password, $user['password']);
      error_log("Password verification result: " . ($passwordMatch ? "Match" : "No match"));

      if (!$passwordMatch) {
        return $this->generateResponse->send(
          'Failure',
          401,
          'Invalid email or password'
        );
      }

      // Check for JWT secret
      if (!isset($_ENV['JWT_SECRET'])) {
        return $this->generateResponse->send(
          'Failure',
          500,
          'JWT secret not set'
        );
      }

      // Generate JWT
      $token = JWT::encode([
        'user_id' => $user['user_id'],
        'email' => $user['email'],
        'exp' => time() + (24 * 60 * 60), // 24 hours
        'iat' => time(),
        'nbf' => time()
      ], $_ENV['JWT_SECRET'], 'HS256');


      setCookie('authToken', $token, [
        'expires' => time() + (24 * 60 * 60),
        'path' => '/', // replace with '/scamazon/' for deployment
        'domain' => $_ENV['COOKIE_DOMAIN'] ?? 'benhensor.co.uk',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None'
      ]);

      unset($user['password']); // Remove password from response

      return $this->generateResponse->send(
        'Success',
        200,
        'Login successful',
        ['user' => $user]
      );
    } catch (Exception $e) {
      error_log("Login error: " . $e->getMessage());
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function logout() {
    $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    try {

      // Your logout logic here
      setCookie('authToken', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => $_ENV['COOKIE_DOMAIN'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None'
      ]);

      return $this->generateResponse->send(
        'Success',
        200,
        'Logout successful'
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
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

      // Your registration logic here
      $newUser = $this->user->create($userData);

      if ($newUser) {
        return $this->generateResponse->send(
          'Success',
          201,
          'User registered successfully',
          ['user' => $newUser] // Return user data
        );
      } else {
        throw new Exception('User registration failed');
      }
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
        setcookie('authToken', '', [
          'expires' => time() - 3600,
          'path' => '/',
          'domain' => $_ENV['COOKIE_DOMAIN'],
          'secure' => true,
          'httponly' => true,
          'samesite' => 'None'
        ]);

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

  public function deleteUser()
  {
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
