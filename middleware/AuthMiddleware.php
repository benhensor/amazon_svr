<?php

namespace Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Exception;
use Models\User;

class AuthMiddleware {
    public static function authenticate() {
        try {
            $headers = getallheaders();
            $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

            if (empty($authHeader) || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                throw new Exception('No token provided');
            }

            $token = $matches[1];

            try {
                $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));

                if (!isset($decoded->type) || $decoded->type !== 'access') {
                    throw new Exception('Invalid token type');
                }

                $user = new User();
                $userData = $user->findById($decoded->user_id);

                if (!$userData) {
                    throw new Exception('User not found');
                }

                return $userData;
            } catch (ExpiredException $e) {
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode([
                    'status' => [
                        'code' => 401,
                        'description' => 'Token expired'
                    ]
                ]);
                exit;
            } catch (Exception $e) {
                throw new Exception('Invalid token');
            }
        } catch (Exception $e) {
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode([
                'status' => [
                    'code' => 401,
                    'description' => $e->getMessage()
                ]
            ]);
            exit;
        }
    }
}