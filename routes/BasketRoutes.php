<?php

use Controllers\BasketController;

$controller = new BasketController();

// Get additional path segments for nested routes
$subResource = $pathParts[3] ?? ''; // 'items'
$subAction = $pathParts[4] ?? ''; // 'updateItemQuantity', etc.

error_log("Method: " . $requestMethod);
error_log("Resource: " . $resource);
error_log("SubResource: " . $subResource);
error_log("SubAction: " . $subAction);

switch ($requestMethod) {
  case 'POST':
    switch ($action) {
      case 'create':
        $controller->createBasket($_POST);
        break;
      // case 'add':
      //   $controller->addItemToBasket($_POST);
      //   break;
      default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
    }
    break;

  case 'PUT':
    if ($subResource === 'items') {
      switch ($subAction) {
        case 'updateItemQuantity':
          $controller->updateBasket();
          break;
        case 'updateItemQuantity':
          $controller->updateItemQuantity($id, $_POST);
          break;
        case 'toggleItemSelected':
          $controller->toggleItemSelected($id, $_POST);
          break;
        case 'selectAllItems':
          $controller->selectAllItems($_POST);
          break;
        case 'deselectAllItems':
          $controller->deselectAllItems($_POST);
          break;
        case 'removeItem':
          $controller->removeItem($id);
          break;
        case 'clearAllItems':
          $controller->clearAllItems($_POST);
          break;
        default:
          http_response_code(404);
          echo json_encode(['error' => 'Not Found']);
          break;
      }
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Invalid sub-resource']);
    }
    break;

  case 'GET':
    $controller->fetchBasket();
    break;

  case 'DELETE':
    switch ($action) {
      case 'delete':
        $controller->deleteBasket();
        break;
      default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
    }
    break;

  default:
    http_response_code(404);
    echo json_encode(['error' => 'Method not allowed']);
    break;
}
