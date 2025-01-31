<?php

use Controllers\AddressController;

$controller = new AddressController();

// Debug logging
error_log("UserRoutes.php loaded");
error_log("Request Method: " . $requestMethod);
error_log("Action: " . $action);

// Get PUT data if needed
$putData = null;
if ($requestMethod === 'PUT') {
  $putData = json_decode(file_get_contents('php://input'), true);
}

// Get ID if it exists in the URL
$id = $pathParts[4] ?? null;
error_log("ID from URL: " . ($id ?? 'not provided'));

switch ($requestMethod) {
  case 'POST':
    switch ($action) {
      case 'add':
        error_log("POST /add route hit");
        $controller->createAddress();
        break;
      default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
    }
    break;

  case 'PUT':
    switch ($action) {
      case 'update':
        error_log("PUT /update/$id route hit");
        $controller->updateAddress($id);
        break;
      case 'default':
        $controller->setDefaultAddress($id);
        break;
      default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
    }
    break;

  case 'GET':
    $controller->getAllAddresses();
    break;

  case 'DELETE':
    switch ($action) {
      case 'delete':
        $controller->deleteAddress($id);
        break;
      default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
    }
    break;

  default:
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    break;
}
