<?php

use Controllers\UserController;

$controller = new UserController();

// Debug logging
error_log("UserRoutes.php loaded");
error_log("Request Method: " . $requestMethod);
error_log("Action: " . $action);

switch ($requestMethod) {  // Changed from $method to $requestMethod to match index.php
  case 'POST':
    switch ($action) {
      case 'login':
        $controller->login($_POST);  // Using $_POST instead of $data
        break;
      case 'logout':
        $controller->logout();
        break;
      case 'register':
        $controller->register($_POST);
        break;
      default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
    }
    break;  // Added missing break

  case 'PUT':
    $controller->updateUser($id, $_POST);
    break;

  case 'GET':
    switch ($action) {
      case 'profile':
        $controller->getUserProfile();
        break;
      case 'current':
        $controller->getCurrentUser();
        break;
      default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
    }
    break;  // Added missing break

  case 'DELETE':
    $controller->deleteUser($id);
    break;

  default:
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    break;
}