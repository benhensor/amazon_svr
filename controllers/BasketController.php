<?php

namespace Controllers;

use Middleware\AuthMiddleware;
use Exception;

class BasketController
{
  private $basket;
  private $basketItem;
  private $generateResponse;
  private $executionStartTime;

  public function __construct()
  {
    $this->basket = new \Models\Basket();
    $this->basketItem = new \Models\BasketItem();
    $this->generateResponse = new \Services\GenerateResponse();
    $this->executionStartTime = microtime(true);
  }

  public function fetchBasket()
  {
    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Find active basket
      $basket = $this->basket->findOne([
        'conditions' => [
          'user_id' => $user['user_id'],
          'status' => 'active'
        ],
        'include' => ['basketItems']
      ]);

      // If no basket exists, return empty basket structure
      if (!$basket) {
        return $this->generateResponse->send('Success', 200, null, [
          'items' => [],
          'count' => 0,
          'total' => 0
        ]);
      }

      // Transform basket items to match frontend expectations
      $transformedItems = array_map(function ($item) {
        return [
          'basket_item_id' => $item['basket_item_id'],
          'basket_id' => (int)$item['basket_id'],
          'product_data' => json_decode($item['product_data'], true),
          'quantity' => (int)$item['quantity'],
          'is_selected' => (bool)$item['is_selected']
        ];
      }, $basket['basketItems'] ?? []);

      return $this->generateResponse->send('Success', 200, null, [
        'items' => $transformedItems,
        'count' => (int)$basket['items_count'],
        'total' => (float)$basket['total']
      ]);
    } catch (Exception $e) {
      error_log('Error fetching basket: ' . $e->getMessage());
      return $this->generateResponse->send(
        'Error',
        500,
        'Failed to fetch basket',
        getenv('APP_ENV') === 'development' ? ['stack' => $e->getTraceAsString()] : []
      );
    }
  }

  public function updateBasket()
  {
    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Get basket data from body
      $data = json_decode(file_get_contents('php://input'), true) ?? null;
      if (!$data) {
        return $this->generateResponse->send('Failure', 400, 'Missing basket data');
      }

      // Validate required fields
      if (!isset($data['items']) || !isset($data['count']) || !isset($data['total'])) {
        return $this->generateResponse->send('Failure', 400, 'Missing required fields');
      }

      // Begin transaction
      $this->basket->beginTransaction();

      try {
        // Find or create basket
        $basket = $this->basket->findOne([
          'conditions' => [
            'user_id' => $user['user_id'],
            'status' => 'active'
          ]
        ]);

        if (!$basket) {
          $basket = $this->basket->create([
            'basket_id' => $data['basket_id'] ?? null,
            'user_id' => $user['user_id'],
            'items_count' => $data['count'],
            'total' => $data['total'],
            'status' => 'active'
          ]);
        } else {
          // Update existing basket
          $this->basket->update($basket['basket_id'], [
            'items_count' => $data['count'],
            'total' => $data['total'],
            'last_modified' => date('Y-m-d H:i:s')
          ]);
        }

        // Remove existing items
        $this->basketItem->deleteMany([
          'conditions' => ['basket_id' => $basket['basket_id']]
        ]);

        // Create new items
        if (!empty($data['items'])) {
          $items = array_map(function ($item) use ($basket) {
            return [
              'basket_item_id' => $item['basket_item_id'],
              'basket_id' => $basket['basket_id'],
              'product_data' => json_encode($item['product_data']),
              'quantity' => (int)$item['quantity'],
              'is_selected' => (bool)$item['is_selected']
            ];
          }, $data['items']);

          $this->basketItem->createMany($items);
        }

        $this->basket->commit();

        return $this->generateResponse->send('Success', 200, null, [
          'success' => true
        ]);
      } catch (Exception $e) {
        $this->basket->rollBack();
        throw $e;
      }
    } catch (Exception $e) {
      error_log('Error updating basket: ' . $e->getMessage());
      return $this->generateResponse->send(
        'Error',
        500,
        'Failed to update basket',
        getenv('APP_ENV') === 'development' ? ['stack' => $e->getTraceAsString()] : []
      );
    }
  }

  public function syncGuestBasket()
  {
    try {

      // Auth check
      $user = \Middleware\AuthMiddleware::authenticate();

      // Get items from body
      $data = json_decode(file_get_contents('php://input'), true) ?? null;
      if (!$data || !isset($data['items'])) {
        return $this->generateResponse->send('Failure', 400, 'Missing items data');
      }

      // Calculate totals
      $count = array_reduce($data['items'], function ($sum, $item) {
        return $sum + ($item['quantity'] ?? 0);
      }, 0);

      $total = array_reduce($data['items'], function ($sum, $item) {
        return $sum + ($item['product_data']['price'] * ($item['quantity'] ?? 0));
      }, 0);

      // Update or create basket with guest items
      $basketData = [
        'items' => $data['items'],
        'count' => $count,
        'total' => $total
      ];

      // Reuse updateBasket logic
      return $this->updateBasket();
    } catch (Exception $e) {
      error_log('Error syncing guest basket: ' . $e->getMessage());
      return $this->generateResponse->send(
        'Error',
        500,
        'Failed to sync guest basket',
        getenv('APP_ENV') === 'development' ? ['stack' => $e->getTraceAsString()] : []
      );
    }
  }
}
