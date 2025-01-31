<?php
namespace Models;

class BasketItem {
  private $db;

  public function __construct() {
      $this->db = \Config\Database::getInstance()->getConnection();
  }

  public function findOne($options) {
      $conditions = $options['conditions'] ?? [];
      $includes = $options['include'] ?? [];
      
      $sql = "SELECT bi.* ";
      if (in_array('basket', $includes)) {
          $sql .= ", b.* ";
      }
      $sql .= "FROM basket_items bi ";
      
      if (in_array('basket', $includes)) {
          $sql .= "JOIN baskets b ON bi.basket_id = b.basket_id ";
      }
      
      $sql .= "WHERE 1=1 ";
      $params = [];
      
      foreach ($conditions as $key => $value) {
          $sql .= "AND $key = ? ";
          $params[] = $value;
      }
      
      $sql .= "LIMIT 1";
      
      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);
      
      return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function findAll($options) {
      $conditions = $options['conditions'] ?? [];
      
      $sql = "SELECT * FROM basket_items WHERE 1=1 ";
      $params = [];
      
      foreach ($conditions as $key => $value) {
          $sql .= "AND $key = ? ";
          $params[] = $value;
      }
      
      $stmt = $this->db->prepare($sql);
      $stmt->execute($params);
      
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function create($data) {
      $sql = "INSERT INTO basket_items (basket_item_id, basket_id, product_data, quantity, is_selected) 
              VALUES (?, ?, ?, ?, ?)";
              
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([
          $data['basket_item_id'],
          $data['basket_id'],
          $data['product_data'],
          $data['quantity'],
          $data['is_selected']
      ]);
  }

  public function update($id, $data) {
      $sql = "UPDATE basket_items SET ";
      $params = [];
      
      foreach ($data as $key => $value) {
          $sql .= "$key = ?, ";
          $params[] = $value;
      }
      
      $sql = rtrim($sql, ", ");
      $sql .= " WHERE basket_item_id = ?";
      $params[] = $id;
      
      $stmt = $this->db->prepare($sql);
      return $stmt->execute($params);
  }

  public function updateMany($options) {
      $conditions = $options['conditions'] ?? [];
      $data = $options['data'] ?? [];
      
      $sql = "UPDATE basket_items SET ";
      $params = [];
      
      foreach ($data as $key => $value) {
          $sql .= "$key = ?, ";
          $params[] = $value;
      }
      
      $sql = rtrim($sql, ", ");
      $sql .= " WHERE 1=1 ";
      
      foreach ($conditions as $key => $value) {
          $sql .= "AND $key = ? ";
          $params[] = $value;
      }
      
      $stmt = $this->db->prepare($sql);
      return $stmt->execute($params);
  }

  public function updateQuantity($id, $data) {
    // Ensure quantity is within bounds (1-5)
    $quantity = max(1, min(5, intval($data['quantity'])));
    
    $sql = "UPDATE basket_items 
            SET quantity = ?, 
                updated_at = CURRENT_TIMESTAMP 
            WHERE basket_item_id = ?";
            
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$quantity, $id]);
  }

  public function delete($id) {
      $sql = "DELETE FROM basket_items WHERE basket_item_id = ?";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute([$id]);
  }

  public function deleteMany($options) {
      $conditions = $options['conditions'] ?? [];
      
      $sql = "DELETE FROM basket_items WHERE 1=1 ";
      $params = [];
      
      foreach ($conditions as $key => $value) {
          $sql .= "AND $key = ? ";
          $params[] = $value;
      }
      
      $stmt = $this->db->prepare($sql);
      return $stmt->execute($params);
  }
}