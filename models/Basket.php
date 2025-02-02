<?php

namespace Models;

class Basket
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
      // First get the basket
      $sql = "SELECT * FROM baskets WHERE 1=1 ";
      $params = [];

      foreach ($conditions as $key => $value) {
        $sql .= "AND $key = ? ";
        $params[] = $value;
      }

      $sql .= "LIMIT 1";

      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);
      $basket = $stmt->fetch(\PDO::FETCH_ASSOC);

      if (!$basket) {
        return null;
      }

      // If basket items are requested, get them
      if (in_array('basketItems', $includes)) {
        $itemsSql = "SELECT * FROM basket_items WHERE basket_id = ?";
        $itemsStmt = $this->db->prepare($itemsSql);
        $itemsStmt->execute([$basket['basket_id']]);
        $items = $itemsStmt->fetchAll(\PDO::FETCH_ASSOC);

        // Decode product_data JSON for all items
        $basket['basketItems'] = array_map(function ($item) {
          if (isset($item['product_data'])) {
            $item['product_data'] = json_decode($item['product_data'], true);
          }
          return $item;
        }, $items);
      }

      return $basket;
    } catch (\PDOException $e) {
      error_log('Database error in findOne: ' . $e->getMessage());
      throw $e;
    }
  }

  public function create($data)
  {
    try {
      $sql = "INSERT INTO baskets 
                    (basket_id, user_id, items_count, total, status, last_modified) 
                    VALUES (?, ?, ?, ?, ?, ?)";

      $stmt = $this->db->prepare($sql);
      $success = $stmt->execute([
        $data['basket_id'],
        $data['user_id'],
        $data['items_count'] ?? 0,
        $data['total'] ?? 0,
        $data['status'] ?? 'active',
        $data['last_modified'] ?? date('Y-m-d H:i:s')
      ]);

      if (!$success) {
        return false;
      }

      // Return the created basket
      return $this->findOne([
        'conditions' => ['basket_id' => $data['basket_id']]
      ]);
    } catch (\PDOException $e) {
      error_log('Database error in create: ' . $e->getMessage());
      throw $e;
    }
  }

  public function update($id, $data)
  {
    try {
      $sql = "UPDATE baskets SET ";
      $params = [];

      foreach ($data as $key => $value) {
        $sql .= "$key = ?, ";
        $params[] = $value;
      }

      $sql = rtrim($sql, ", ");
      $sql .= " WHERE basket_id = ?";
      $params[] = $id;

      $stmt = $this->db->prepare($sql);
      return $stmt->execute($params);
    } catch (\PDOException $e) {
      error_log('Database error in update: ' . $e->getMessage());
      throw $e;
    }
  }
}
