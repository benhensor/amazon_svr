<?php

namespace Controllers;

use Middleware\AuthMiddleware;
use Services\GenerateResponse;
use Models\Basket;
use Models\BasketItem;
use GuzzleHttp\Client;
use Exception;

class BasketController
{
  private $basket;
  private $basketItem;
  private $generateResponse;
  private $httpClient;
  private $executionStartTime;

  public function __construct()
  {
    $this->basket = new Basket();
    $this->basketItem = new BasketItem();
    $this->generateResponse = new GenerateResponse();
    $this->httpClient = new Client();
    $this->executionStartTime = microtime(true);
  }

  public function createBasket() {
    $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {
      // We only need to set user_id since everything else has defaults
      $basket = $this->basket->create([
        'user_id' => $user['user_id']
      ]);

      if (!$basket) {
        return $this->generateResponse->send(
          'Failure',
          500,
          'Failed to create basket'
        );
      }

      return $this->generateResponse->send(
        'Success',
        201,
        'Basket created successfully',
        ['basket' => $basket]
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function fetchBasket() {
    $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {

      $basket = $this->basket->findOne([
        'conditions' => [
          'user_id' => $user['user_id'],
          'status' => 'active'
        ],
        'include' => ['basketItems'],
        'attributes' => ['basket_item_id', 'basket_id', 'product_data', 'quantity', 'is_selected']
      ]);

      if (!$basket) {
        return $this->generateResponse->send(
            'Success',  // Changed to Success to match Node behavior
            200,       // Changed to 200 to match Node behavior
            'Basket fetched successfully',
            [
                'basket' => [
                    'items' => [],
                    'count' => 0,
                    'total' => 0
                ]
            ]
        );
      }

      $transformedItems = array_map(function ($item) {
        return [
          'basket_item_id' => $item['basket_item_id'],
          'basket_id' => (int)$item['basket_id'], // Cast to integer to match Node
          'product_data' => is_string($item['product_data']) 
              ? json_decode($item['product_data'], true) 
              : $item['product_data'],
          'quantity' => $item['quantity'],
          'is_selected' => $item['is_selected']
        ];
      }, $basket['basketItems'] ?? []);

      return $this->generateResponse->send(
        'Success',
        200,
        'Basket fetched successfully',
        [
          'basket' => [
            'items' => $transformedItems,
            'count' => $basket['items_count'],
            'total' => $basket['total']
          ]
        ]
      );
      
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function updateBasket() {
     $this->executionStartTime = microtime(true);
     
     try {

      // Verify token
      $token = $_COOKIE['authToken'] ?? null;
      if (!$token) {
        return $this->generateResponse->send(
          'Failure',
          401,
          'No authentication token provided'
        );
      }
  
      // Verify user
      $user = AuthMiddleware::authenticate();
      if (!$user) {
        return $this->generateResponse->send(
          'Failure',
          401,
          'Invalid authentication token'
        );
      }
  
      // Get basket data from body
      $data = json_decode(file_get_contents('php://input'), true) ?? null;
      if (!$data) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Missing required parameters'
        );
      }

      // Validate data
      if (!isset($data['basket_id']) || !isset($data['items_count']) || !isset($data['total']) || !isset($data['status'])) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Missing required parameters'
        );
      }

      if (isset($data['items']) && !is_array($data['items'])) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Items must be an array'
        );
      }

      // Find or create basket
      $basket = $this->basket->findOne([
        'conditions' => [
            'user_id' => $user['user_id'],
            'status' => 'active'
        ]
    ]);

    if (!$basket) {
        $basket = $this->basket->create([
            'basket_id' => $data['basket_id'],
            'user_id' => $user['user_id'],
            'items_count' => $data['items_count'],
            'total' => $data['total'],
            'status' => $data['status'],
            'last_modified' => date('Y-m-d H:i:s')
        ]);
    }

    // Remove existing items
    $this->basketItem->deleteMany([
        'conditions' => ['basket_id' => $data['basket_id']]
    ]);

    // Create new items
    if (!empty($data['items'])) {
        $items = array_map(function ($item) use ($data) {
            return [
                'basket_item_id' => $item['basket_item_id'],
                'basket_id' => $data['basket_id'],
                'product_data' => json_encode($item['product_data']),
                'quantity' => $item['quantity'],
                'is_selected' => $item['is_selected']
            ];
        }, $data['items']);

        $this->basketItem->createMany($items);
    }

    // Update basket
    $updated = $this->basket->update($data['basket_id'], [
        'items_count' => $data['items_count'],
        'total' => $data['total'],
        'status' => $data['status'],
        'last_modified' => date('Y-m-d H:i:s')
    ]);

    if (!$updated) {
        return $this->generateResponse->send(
            'Failure',
            500,
            'Failed to update basket'
        );
    }

    // Fetch updated basket with items
    $updatedBasket = $this->basket->findOne([
        'conditions' => ['basket_id' => $data['basket_id']],
        'with' => ['basketItems']
    ]);

    return $this->generateResponse->send(
        'Success',
        200,
        'Basket updated successfully',
        ['basket' => $updatedBasket]
    );
      
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function updateItemQuantity() {
    $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
        return $this->generateResponse->send(
            'Failure',
            401,
            'No authentication token provided'
        );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
        return $this->generateResponse->send(
            'Failure',
            401,
            'Invalid authentication token'
        );
    }

    // Get basket item ID from params and quantity from body
    $itemId = $_GET['basket_item_id'] ?? null;
    $quantity = json_decode(file_get_contents('php://input'), true)['quantity'] ?? null;
    
    if (!$itemId || !$quantity) {
        return $this->generateResponse->send(
            'Failure',
            400,
            'Missing required parameters'
        );
    }

    try {
        // Find basket item with active basket check
        $basketItem = $this->basketItem->findOne([
            'conditions' => [
                'basket_item_id' => $itemId,
                'basket.user_id' => $user['user_id'],
                'basket.status' => 'active'
            ],
            'include' => ['basket']
        ]);

        if (!$basketItem) {
            return $this->generateResponse->send(
                'Failure',
                404,
                'Basket item not found'
            );
        }

        // Update basket item
        $updated = $this->basketItem->updateQuantity($itemId, [
            'quantity' => $quantity,
            'last_modified' => date('Y-m-d H:i:s')
        ]);

        if (!$updated) {
            return $this->generateResponse->send(
                'Failure',
                500,
                'Failed to update item quantity'
            );
        }

        // Update basket totals
        $updatedBasket = $this->basket->findOne([
            'conditions' => ['basket_id' => $basketItem['basket_id']],
            'include' => [
                'basketItems' => [
                    'include' => ['product']
                ]
            ]
        ]);

        if ($updatedBasket) {
            $count = 0;
            $total = 0;
            
            foreach ($updatedBasket['basketItems'] as $item) {
                $count += $item['quantity'];
                $total += $item['product']['price'] * $item['quantity'];
            }

            $this->basket->update($basketItem['basket_id'], [
                'items_count' => $count,
                'total' => $total,
                'last_modified' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->generateResponse->send(
            'Success',
            200,
            'Item quantity updated successfully'
        );
        
    } catch (Exception $e) {
        return $this->generateResponse->send(
            'Error',
            500,
            'Internal server error: ' . $e->getMessage()
        );
    }
}

  public function toggleItemSelected() {
     $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {
      
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function selectAllItems() {
     $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {
      
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function deselectAllItems() {
     $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {
      
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function removeItem() {
     $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {
      
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function clearAllItems() {
     $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {
      
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function deleteBasket() {
     $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {
      
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }
}
