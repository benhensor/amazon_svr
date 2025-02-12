<?php

use Controllers\UserController;
use Services\GenerateResponse;

$controller = new UserController();
$response = new GenerateResponse();

// Debug logging
error_log("UserRoutes.php loaded");
error_log("Request Method: " . $requestMethod);
error_log("Action: " . $action);

switch ($requestMethod) {
  case 'POST':
    switch ($action) {
      case 'login':
        $controller->login();
        break;
      case 'logout':
        $controller->logout();
        break;
      case 'register':
        $controller->register();
        break;
      case 'refresh-token':
        $controller->refreshToken();
        break;
      default:
        $response->send('Error', 404, 'Not Found');
        break;
    }
    break;

  case 'PUT':
    if ($action === 'update') {
      $controller->updateUser();
    } else {
      $response->send('Error', 404, 'Not Found');
    }
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
        $response->send('Error', 404, 'Not Found');
        break;
    }
    break;

  case 'DELETE':
    if ($action === 'delete') {
      $controller->deleteUser();
    } else {
      $response->send('Error', 404, 'Not Found');
    }
    break;

  default:
    $response->send('Error', 404, 'Not Found');
    break;
}
