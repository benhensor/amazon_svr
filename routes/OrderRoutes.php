<?php

use Controllers\OrderController;

$controller = new OrderController();

switch($method) {

  case 'POST':
    switch ($action) {
      case 'create':
        $controller->createOrder($data);
        break;
      case 'createItem':
        $controller->createOrderItem($data);
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

  case 'PUT':
    switch ($action) {
      case 'updateStatus':
        $controller->updateOrderStatus($id, $data);
        break;
      case 'updateItemQuantity':
        $controller->updateItemQuantity($id, $data);
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

  case 'GET':
    switch ($action) {
      case 'fetch':
        $controller->getAllOrders();
        break;
      case 'fetchById':
        $controller->getOrderById($id);
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

  case 'DELETE':
    switch ($action) {
      case 'delete':
        $controller->deleteOrder($id);
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