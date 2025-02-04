<?php

namespace Models;

class Order
{
  private $db;

  public function __construct()
  {
    $this->db = \Config\Database::getInstance()->getConnection();
  }

  public function beginTransaction()
  {
    return $this->db->beginTransaction();
  }

  public function commit()
  {
    return $this->db->commit();
  }

  public function rollBack()
  {
    return $this->db->rollBack();
  }

  public function findOne($options)
  {
    $conditions = $options['conditions'] ?? [];
    $includes = $options['include'] ?? [];

    try {
      // First get the order
      $sql = "SELECT order_id, user_id, order_placed, delivery_address, payment_method, shipping, total, status, created_at, updated_at FROM orders WHERE 1=1 ";
      $params = [];

      foreach ($conditions as $key => $value) {
        $sql .= "AND $key = ? ";
        $params[] = $value;
      }

      $sql .= "LIMIT 1";

      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);
      $order = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!$order) {
        return null;
      }

      // If order items are requested, get them
      if (in_array('order_items', $includes)) {
        $itemsSql = "SELECT order_item_id, order_id, product_id, quantity, price, total, created_at, updated_at FROM order_items WHERE order_id = ?";
        $itemsStmt = $this->db->prepare($itemsSql);
        $itemsStmt->execute([$order['order_id']]);
        $items = $itemsStmt->fetchAll(\PDO::FETCH_ASSOC);

        $order['order_items'] = $items;
      }

      // Decode delivery address JSON
      if (isset($order['delivery_address'])) {
        $order['delivery_address'] = json_decode($order['delivery_address'], true);
      }

      // Decode payment method JSON
      if (isset($order['payment_method'])) {
        $order['payment_method'] = json_decode($order['payment_method'], true);
      }

      // Decode shipping JSON
      if (isset($order['shipping'])) {
        $order['shipping'] = json_decode($order['shipping'], true);
      }

      return $order;
    } catch (\PDOException $e) {
      error_log('Database error in findOne: ' . $e->getMessage());
      throw $e;
    }
  }

  public function getAll($options)
  {
    $conditions = $options['conditions'] ?? [];

    try {
      // First get all orders
      $sql = "SELECT order_id, user_id, order_placed, delivery_address, payment_method, shipping, total, status, created_at, updated_at 
                FROM orders WHERE 1=1 ";
      $params = [];

      foreach ($conditions as $key => $value) {
        $sql .= "AND $key = ? ";
        $params[] = $value;
      }

      $sql .= "ORDER BY created_at DESC"; // Optional: order by creation date

      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);
      $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      if (empty($orders)) {
        return [];
      }

      // Get all order items for these orders in a single query
      $orderIds = array_column($orders, 'order_id');
      $placeholders = str_repeat('?,', count($orderIds) - 1) . '?';

      $itemsSql = "SELECT order_item_id, order_id, product_id, quantity, price, total, created_at, updated_at 
                     FROM order_items 
                     WHERE order_id IN ($placeholders)
                     ORDER BY created_at ASC";

      $itemsStmt = $this->db->prepare($itemsSql);
      $itemsStmt->execute($orderIds);
      $items = $itemsStmt->fetchAll(\PDO::FETCH_ASSOC);

      // Group items by order_id
      $itemsByOrder = [];
      foreach ($items as $item) {
        $itemsByOrder[$item['order_id']][] = $item;
      }

      // Build final order objects with their items
      $result = array_map(function ($order) use ($itemsByOrder) {
        // Decode delivery address JSON if it exists
        if (isset($order['delivery_address']) && !is_null($order['delivery_address'])) {
          $order['delivery_address'] = json_decode($order['delivery_address'], true);
        }
        // Decode payment method JSON if it exists
        if (isset($order['payment_method']) && !is_null($order['payment_method'])) {
          $order['payment_method'] = json_decode($order['payment_method'], true);
        }
        // Decode shipping JSON if it exists
        if (isset($order['shipping']) && !is_null($order['shipping'])) {
          $order['shipping'] = json_decode($order['shipping'], true);
        }

        // Add order items array (empty array if no items exist)
        $order['order_items'] = $itemsByOrder[$order['order_id']] ?? [];

        return $order;
      }, $orders);

      return $result;
    } catch (\PDOException $e) {
      error_log('Database error in getAll: ' . $e->getMessage());
      throw $e;
    }
  }

  public function create($data)
  {
    try {
      $orderPlaced = new \DateTime($data['order_placed']);
      $formattedDate = $orderPlaced->format('Y-m-d H:i:s');
      $sql = "INSERT INTO orders 
                    (order_id, user_id, order_placed, delivery_address, payment_method, shipping, total, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

      $stmt = $this->db->prepare($sql);
      $success = $stmt->execute([
        $data['order_id'],
        $data['user_id'],
        $formattedDate,
        json_encode($data['delivery_address']),
        json_encode($data['payment_method']),
        json_encode($data['shipping']),
        $data['total'],
        $data['status']
      ]);

      if (!$success) {
        return false;
      }

      return $this->getById($data['order_id']);
    } catch (\PDOException $e) {
      error_log('Database error in create: ' . $e->getMessage());
      throw $e;
    }
  }

  public function getById($orderId)
  {
    return $this->findOne([
      'conditions' => ['order_id' => $orderId],
      'include' => ['order_items']
    ]);
  }

  public function updateStatus($conditions, $data)
  {
    try {
      $sql = "UPDATE orders SET status = ? WHERE order_id = ?";

      $stmt = $this->db->prepare($sql);
      $success = $stmt->execute([
        $data['status'],
        $conditions['order_id']
      ]);

      if (!$success) {
        return false;
      }

      return $this->getById($conditions['order_id']);
    } catch (\PDOException $e) {
      error_log('Database error in updateStatus: ' . $e->getMessage());
      throw $e;
    }
  }

  public function delete($conditions)
  {
    try {
      $sql = "DELETE FROM orders WHERE order_id = ?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$conditions['order_id']]);
    } catch (\PDOException $e) {
      error_log('Database error in delete: ' . $e->getMessage());
      throw $e;
    }
  }
}
