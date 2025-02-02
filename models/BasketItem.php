<?php

namespace Models;

class BasketItem
{
  private $db;

  public function __construct()
  {
    $this->db = \Config\Database::getInstance()->getConnection();
  }

  public function findAll($options)
  {
    $conditions = $options['conditions'] ?? [];

    $sql = "SELECT * FROM basket_items WHERE 1=1 ";
    $params = [];

    foreach ($conditions as $key => $value) {
      $sql .= "AND $key = ? ";
      $params[] = $value;
    }

    try {
      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);
      $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      // Decode product_data JSON for all results
      return array_map(function ($item) {
        if (isset($item['product_data'])) {
          $item['product_data'] = json_decode($item['product_data'], true);
        }
        return $item;
      }, $results);
    } catch (\PDOException $e) {
      error_log('Database error in findAll: ' . $e->getMessage());
      throw $e;
    }
  }

  public function createMany($items)
  {
    if (empty($items)) {
      return true;
    }

    try {
      // Process in batches of 100 to avoid query length/parameter limits
      $batchSize = 100;
      $batches = array_chunk($items, $batchSize);

      foreach ($batches as $batch) {
        $sql = "INSERT INTO basket_items 
                        (basket_item_id, basket_id, product_data, quantity, is_selected) 
                        VALUES ";

        $params = [];
        foreach ($batch as $item) {
          $sql .= "(?, ?, ?, ?, ?), ";
          $params[] = $item['basket_item_id'];
          $params[] = $item['basket_id'];
          $params[] = $item['product_data'];
          $params[] = $item['quantity'];
          $params[] = $item['is_selected'];
        }

        $sql = rtrim($sql, ", ");

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
      }

      return true;
    } catch (\PDOException $e) {
      error_log('Database error in createMany: ' . $e->getMessage());
      throw $e;
    }
  }

  public function deleteMany($options)
  {
    $conditions = $options['conditions'] ?? [];

    $sql = "DELETE FROM basket_items WHERE 1=1 ";
    $params = [];

    foreach ($conditions as $key => $value) {
      $sql .= "AND $key = ? ";
      $params[] = $value;
    }

    try {
      $stmt = $this->db->prepare($sql);
      return $stmt->execute($params);
    } catch (\PDOException $e) {
      error_log('Database error in deleteMany: ' . $e->getMessage());
      throw $e;
    }
  }
}
