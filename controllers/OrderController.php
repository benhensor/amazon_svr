<?php

namespace Controllers;

use Middleware\AuthMiddleware;
use Services\GenerateResponse;
use Models\Order;
use Models\OrderItem;
use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;
use Exception;

class OrderController
{
  private $order;
  private $orderItem;
  private $generateResponse;
  private $httpClient;
  private $executionStartTime;

  public function __construct()
  {
    $this->order = new Order();
    $this->orderItem = new OrderItem();
    $this->generateResponse = new GenerateResponse();
    $this->httpClient = new Client();
    $this->executionStartTime = microtime(true);
  }

  public function createOrder()
  {
    $this->executionStartTime = microtime(true);

    try {

      // Verify user
      $user = AuthMiddleware::authenticate();

      // Verify order data
      $data = json_decode(file_get_contents('php://input'), true);
      if (!$data) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'No order data provided'
        );
      }

      // Validate order data
      if (!isset($user['user_id']) || !isset($data['order_placed']) || !isset($data['delivery_address']) || !isset($data['payment_method']) || !isset($data['shipping']) || !isset($data['order_items']) || !isset($data['total'])) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid order data provided'
        );
      }

      try {

        $this->order->beginTransaction();

        // Create order
        $order = $this->order->create([
          'order_id' => Uuid::uuid4()->toString(),
          'user_id' => $user['user_id'],
          'order_placed' => $data['order_placed'],
          'delivery_address' => $data['delivery_address'],  
          'payment_method' => $data['payment_method'],
          'shipping' => $data['shipping'],
          'total' => $data['total'],
          'status' => 'pending',
        ]);

        // Create order items
        $orderItems = array_map(function ($item) use ($order) {
          return [
            'order_item_id' => Uuid::uuid4()->toString(),
            'order_id' => $order['order_id'],
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'total' => $item['total'],
          ];
        }, $data['order_items']);

        $createdOrderItems = $this->orderItem->createMany($orderItems);

        $this->order->commit();

        $responseData = [
          'order' => array_merge($order, ['order_items' => $createdOrderItems])
        ];

        return $this->generateResponse->send(
          'Success',
          201,
          'Order created successfully',
          $responseData
        );
      } catch (Exception $e) {
        $this->order->rollBack();
        return $this->generateResponse->send(
          'Error',
          500,
          'Internal server error: ' . $e->getMessage()
        );
      }
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function getOrder($orderId)
  {
    $this->executionStartTime = microtime(true);

    try {
      // Verify user
      $user = AuthMiddleware::authenticate();

      // Get order
      $order = $this->order->getById([
        'conditions' => ['order_id' => $orderId],
        'include' => [
          [
            'model' => $this->orderItem,
            'as' => 'order_items'
          ]
        ]
      ]);

      if (!$order) {
        return $this->generateResponse->send(

          'Failure',
          404,
          'Order not found'
        );
      }

      // Verify order belongs to user
      if ($order['user_id'] !== $user['user_id']) {
        return $this->generateResponse->send(
          'Failure',
          403,
          'Unauthorized access to order'
        );
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'Order fetched successfully',
        ['order' => $order]
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function getAllOrders()
  {
    $this->executionStartTime = microtime(true);

    try {
      // Verify user
      $user = AuthMiddleware::authenticate();

      // Get all orders for user
      $orders = $this->order->getAll([
        'conditions' => ['user_id' => $user['user_id']],
        'include' => [
          [
            'model' => $this->orderItem,
            'as' => 'order_items'
          ]
        ]
      ]);

      return $this->generateResponse->send(
        'Success',
        200,
        'Orders fetched successfully',
        ['orders' => $orders]
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function updateOrderStatus($orderId, $data)
  {
    $this->executionStartTime = microtime(true);

    try {
      // Verify user
      $user = AuthMiddleware::authenticate();

      // Start transaction
      $this->order->beginTransaction();

      // Check if order exists
      $order = $this->order->getById($orderId);
      if (!$order) {
        $this->order->rollBack();
        return $this->generateResponse->send(
          'Failure',
          400,
          'Order does not exist'
        );
      }

      // Verify order belongs to user
      if ($order['user_id'] !== $user['user_id']) {
        $this->order->rollBack();
        return $this->generateResponse->send(
          'Failure',
          403,
          'Unauthorized access to order'
        );
      }

      // Update order status
      $updated = $this->order->updateStatus(
        ['order_id' => $orderId],
        ['status' => $data['status']]
      );

      if (!$updated) {
        $this->order->rollBack();
        return $this->generateResponse->send(
          'Failure',
          400,
          'Failed to update order status'
        );
      }

      $this->order->commit();

      return $this->generateResponse->send(
        'Success',
        200,
        'Order status updated successfully',
        ['order' => $updated]
      );
    } catch (Exception $e) {
      $this->order->rollBack();
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function deleteOrder($orderId)
  {
    $this->executionStartTime = microtime(true);

    try {
      // Verify user
      $user = AuthMiddleware::authenticate();

      // Start transaction
      $this->order->beginTransaction();

      // Check if order exists
      $order = $this->order->getById($orderId);
      if (!$order) {
        $this->order->rollBack();
        return $this->generateResponse->send(
          'Failure',
          400,
          'Order does not exist'
        );
      }

      // Verify order belongs to user
      if ($order['user_id'] !== $user['user_id']) {
        $this->order->rollBack();
        return $this->generateResponse->send(
          'Failure',
          403,
          'Unauthorized access to order'
        );
      }

      // Delete order items first
      $this->orderItem->deleteMany([
        'order_id' => $orderId
      ]);

      // Delete order
      $deleted = $this->order->delete([
        'order_id' => $orderId
      ]);

      if (!$deleted) {
        $this->order->rollBack();
        return $this->generateResponse->send(
          'Failure',
          400,
          'Failed to delete order'
        );
      }

      $this->order->commit();

      return $this->generateResponse->send(
        'Success',
        200,
        'Order deleted successfully',
        $orderId
      );
    } catch (Exception $e) {
      $this->order->rollBack();
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }
}
