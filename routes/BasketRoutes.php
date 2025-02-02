<?php
use Controllers\BasketController;

$controller = new BasketController();

// Get additional path segments for nested routes
$subResource = $pathParts[3] ?? '';

error_log("Method: $requestMethod, Resource: $resource, SubResource: $subResource");

switch ($requestMethod) {
    case 'GET':
        // GET /api/basket - Fetch user's basket
        $controller->fetchBasket();
        break;

    case 'PUT':
        // PUT /api/basket - Update entire basket
        $controller->updateBasket();
        break;

    case 'POST':
        if ($subResource === 'sync') {
            // POST /api/basket/sync - Sync guest basket
            $controller->syncGuestBasket();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}