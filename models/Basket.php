<?php
namespace Models;

class Basket {
  private $db;

  public function __construct() {
      $this->db = \Config\Database::getInstance()->getConnection();
  }

  public function findOne($options) {
      $conditions = $options['conditions'] ?? [];
      $includes = $options['include'] ?? [];
      
      $sql = "SELECT b.* FROM baskets b WHERE 1=1 ";
      $params = [];
      
      foreach ($conditions as $key => $value) {
          $sql .= "AND b.$key = ? ";
          $params[] = $value;
      }
      
      $sql .= "LIMIT 1";
      
      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);
      $basket = $stmt->fetch(\PDO::FETCH_ASSOC);
      
      if ($basket && in_array('basketItems', $includes)) {
          $itemsSql = "SELECT * FROM basket_items WHERE basket_id = ?";
          $itemsStmt = $this->db->prepare($itemsSql);
          $itemsStmt->execute([$basket['basket_id']]);
          $basket['basketItems'] = $itemsStmt->fetchAll(\PDO::FETCH_ASSOC);
      }
      
      return $basket;
  }

  public function create($data) {
      $sql = "INSERT INTO baskets (basket_id, user_id, items_count, total, status, last_modified) 
              VALUES (?, ?, ?, ?, ?, ?)";
              
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([
          $data['basket_id'] ?? null,
          $data['user_id'],
          $data['items_count'] ?? 0,
          $data['total'] ?? 0,
          $data['status'] ?? 'active',
          $data['last_modified'] ?? date('Y-m-d H:i:s')
      ]);
  }

  public function update($id, $data) {
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
  }

  public function delete($id) {
      $sql = "DELETE FROM baskets WHERE basket_id = ?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$id]);
  }
}