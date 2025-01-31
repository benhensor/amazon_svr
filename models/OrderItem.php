<?php
namespace Models;

class OrderItem {
  private $db;

  public function __construct() {
    $this->db = \Config\Database::getInstance()->getConnection();
  }

  public function create() {

  }
  public function delete() {

  }
}