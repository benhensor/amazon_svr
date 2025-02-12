<?php

namespace Models;

class User
{
  private $db;

  public function __construct() {
    $this->db = \Config\Database::getInstance()->getConnection();
  }

  public function all() {
    $query = $this->db->query('SELECT u.user_id, u.full_name, u.email, a.address_id AS address_id, a.address_line1, a.address_line2, a.city, a.county, a.postcode, a.country, a.is_default, a.is_billing, a.delivery_instructions, a.address_type, p.profile_id AS profile_id, p.profile_picture, p.theme, p.browsing_history FROM users u LEFT JOIN addresses a ON u.user_id = a.user_id LEFT JOIN profiles p ON u.user_id = p.user_id');
    return $query->fetchAll();
  }

  public function create($data) {
    try {
      $query = $this->db->prepare('INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)');
      $success = $query->execute([
        $data['full_name'],
        $data['email'],
        $data['password']
      ]);

      if ($success) {
        $user = $this->findByEmail($data['email']);
        return [
          'user_id' => $user['user_id'],
          'full_name' => $user['full_name'],
          'email' => $user['email']
        ];
      }
      return false;
    } catch (\PDOException $e) {
      error_log("Database error in create user: " . $e->getMessage());
      return false;
    }
  }

  public function findByEmail($email) {
    try {
      $query = $this->db->prepare('SELECT user_id, full_name, email, password FROM users WHERE email = ?');
      $query->execute([$email]);
      $result = $query->fetch(\PDO::FETCH_ASSOC); 
      error_log("Database result: " . json_encode($result)); // Debug log
      return $result;
    } catch (\PDOException $e) {
      error_log("Database error in findByEmail: " . $e->getMessage());
      return false;
    }
  }

  public function findById($id) {
    $query = $this->db->prepare('SELECT user_id, full_name, email, password FROM users WHERE user_id = ?');
    $query->execute([$id]);
    $result = $query->fetch(\PDO::FETCH_ASSOC);
    error_log("Database result: " . json_encode($result)); // Debug log
    return $result;
  }

  public function update($id, $data) {
    $query = $this->db->prepare('UPDATE users SET full_name = ?, email = ?, password = ? WHERE user_id = ?');
    return $query->execute([$data['full_name'], $data['email'], $data['password'], $id]);
  }

  public function getProfileById($id) {
    $query = $this->db->prepare('SELECT profile_id, profile_picture, browsing_history FROM profiles WHERE user_id = ?');
    $query->execute([$id]);
    return $query->fetch();
  }

  public function delete($id) {
    $query = $this->db->prepare('DELETE FROM users WHERE user_id = ?');
    return $query->execute([$id]);
  }

  public function storeRefreshToken($userId, $tokenHash) {
    try {
      // First, clean up any expired tokens for this user
      $cleanupQuery = $this->db->prepare('DELETE FROM refresh_tokens WHERE user_id = ? AND expires_at < NOW()');
      $cleanupQuery->execute([$userId]);

      // Insert new refresh token
      $query = $this->db->prepare('INSERT INTO refresh_tokens (user_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY))');
      return $query->execute([$userId, $tokenHash]);
    } catch (\PDOException $e) {
      error_log("Database error in storeRefreshToken: " . $e->getMessage());
      return false;
    }
  }

  public function verifyRefreshToken($userId, $tokenHash) {
    try {
      $query = $this->db->prepare('SELECT token_hash FROM refresh_tokens WHERE user_id = ? AND token_hash = ? AND expires_at > NOW()');
      $query->execute([$userId, $tokenHash]);
      return $query->fetch() !== false;
    } catch (\PDOException $e) {
      error_log("Database error in verifyRefreshToken: " . $e->getMessage());
      return false;
    }
  }

  public function removeRefreshToken($userId, $tokenHash) {
    try {
      $query = $this->db->prepare('DELETE FROM refresh_tokens WHERE user_id = ? AND token_hash = ?');
      return $query->execute([$userId, $tokenHash]);
    } catch (\PDOException $e) {
      error_log("Database error in removeRefreshToken: " . $e->getMessage());
      return false;
    }
  }

  public function removeAllRefreshTokens($userId) {
    try {
      $query = $this->db->prepare('DELETE FROM refresh_tokens WHERE user_id = ?');
      return $query->execute([$userId]);
    } catch (\PDOException $e) {
      error_log("Database error in removeAllRefreshTokens: " . $e->getMessage());
      return false;
    }
  }
}
