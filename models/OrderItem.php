<?php

namespace Models;

class OrderItem
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

    try {
      $sql = "SELECT order_item_id, order_id, product_id, quantity, price, total, created_at, updated_at FROM order_items WHERE 1=1 ";
      $params = [];

      foreach ($conditions as $key => $value) {
        $sql .= "AND $key = ? ";
        $params[] = $value;
      }

      $sql .= "LIMIT 1";

      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      error_log('Database error in findOne: ' . $e->getMessage());
      throw $e;
    }
  }

  public function createMany($items)
  {
    try {
      $sql = "INSERT INTO order_items 
                    (order_item_id, order_id, product_id, quantity, price, total) 
                    VALUES (?, ?, ?, ?, ?, ?)";

      $stmt = $this->db->prepare($sql);

      foreach ($items as $item) {
        $success = $stmt->execute([
          $item['order_item_id'],
          $item['order_id'],
          $item['product_id'],
          $item['quantity'],
          $item['price'],
          $item['total'],
        ]);

        if (!$success) {
          throw new \Exception('Failed to create order item');
        }
      }

      return $this->getByOrderId($items[0]['order_id']);
      
    } catch (\PDOException $e) {
      error_log('Database error in createMany: ' . $e->getMessage());
      throw $e;
    }
  }

  public function getByOrderId($orderId)
  {
    try {
      $sql = "SELECT order_item_id, order_id, product_id, quantity, price, total, created_at, updated_at FROM order_items WHERE order_id = ?";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([$orderId]);
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      error_log('Database error in getByOrderId: ' . $e->getMessage());
      throw $e;
    }
  }

  public function update($conditions, $data)
  {
    try {
      $sql = "UPDATE order_items SET ";
      $params = [];

      foreach ($data as $key => $value) {
        $sql .= "$key = ?, ";
        $params[] = $value;
      }

      $sql = rtrim($sql, ", ");
      $sql .= " WHERE order_item_id = ?";
      $params[] = $conditions['order_item_id'];

      $stmt = $this->db->prepare($sql);
      return $stmt->execute($params);
    } catch (\PDOException $e) {
      error_log('Database error in update: ' . $e->getMessage());
      throw $e;
    }
  }

  public function deleteMany($conditions)
  {
    try {
      $sql = "DELETE FROM order_items WHERE order_id = ?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$conditions['order_id']]);
    } catch (\PDOException $e) {
      error_log('Database error in deleteMany: ' . $e->getMessage());
      throw $e;
    }
  }

  public function delete($conditions)
  {
    try {
      $sql = "DELETE FROM order_items WHERE order_item_id = ?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$conditions['order_item_id']]);
    } catch (\PDOException $e) {
      error_log('Database error in delete: ' . $e->getMessage());
      throw $e;
    }
  }
}
