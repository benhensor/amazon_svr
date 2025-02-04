<?php

use Controllers\PaymentMethodController;

$controller = new PaymentMethodController();

// Get additional path segments for nested routes
$subResource = $pathParts[3] ?? '';

error_log("Method: $requestMethod, Resource: $resource, SubResource: $subResource");

switch ($requestMethod) {
  case 'GET':
    // GET /api/payment-method - Fetch user's payment methods
    $controller->fetchPaymentMethods();
    break;

  case 'POST':
    // POST /api/payment-method - Add payment method
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller->addPaymentMethod($data);
    break;

  case 'PUT':
    // PUT /api/payment-method - Set default payment method
    $paymentMethodId = $pathParts[3] ?? null;
    $controller->setDefaultPaymentMethod($paymentMethodId);
    break;

  case 'DELETE':
    // DELETE /api/payment-method - Delete payment method
    $paymentMethodId = $pathParts[3] ?? null;
    $controller->deletePaymentMethod($paymentMethodId);
    break;


  default:
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    break;
}
