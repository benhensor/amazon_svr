<?php

use Controllers\OrderController;

$method = $_SERVER['REQUEST_METHOD'];
$data = [];
if ($method === 'POST' || $method === 'PUT') {
  $data = json_decode(file_get_contents('php://input'), true) ?? [];
}
$id = $pathParts[4] ?? null;
$controller = new OrderController();

switch ($method) {
  case 'POST':
    if ($action === 'add') {
      $controller->createOrder();
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Not Found']);
    }
    break;

  case 'GET':
    if ($action === 'fetch' && isset($id)) {
      $controller->getOrder($id);
    } elseif ($action === 'fetch') {
      $controller->getAllOrders();
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Not Found']);
    }
    break;

  case 'PUT':
    if ($action === 'update' && isset($id)) {
      $controller->updateOrderStatus($id, $data);
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Not Found']);
    }
    break;

  case 'DELETE':
    if ($action === 'delete' && isset($id)) {
      $controller->deleteOrder($id);
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Not Found']);
    }
    break;

  default:
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    break;
}
