<?php
namespace Models;

class Order {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::getInstance()->getConnection();
  }

  public function create($data) {
    $query = $this->db->prepare('INSERT INTO orders (user_id, order_placed, shipping, total, status, last_modified, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    return $query->execute([$data['user_id'], $data['order_placed'], $data['shipping'], $data['total'], $data['status'], $data['last_modified'], $data['created_at'], $data['updated_at']]);
  }

  public function all() {
    $query = $this->db->query('SELECT order_id, user_id, order_placed, shipping, total, status, last_modified, created_at, updated_at FROM orders');
    return $query->fetchAll();
  }

  public function getById($id) {
    $query = $this->db->prepare('SELECT order_id, user_id, order_placed, shipping, total, status, last_modified, created_at, updated_at FROM orders WHERE order_id = ?');
    $query->execute([$id]);
    return $query->fetch();
  }

  public function updateStatus($id, $status) {
    $query = $this->db->prepare('UPDATE orders SET status = ?, WHERE order_id = ?');
    return $query->execute([$status, $id]);
  }

  public function delete($id) {
    $query = $this->db->prepare('DELETE FROM orders WHERE order_id = ?');
    return $query->execute([$id]);
  }
}
