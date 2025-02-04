<?php

namespace Models;

class PaymentMethod
{
  private $db;
  private const STATUS_VALID = 'valid';
  private const STATUS_DEFAULT = 'default';
  private const STATUS_EXPIRED = 'expired';

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

  private function isExpired($endDate) 
  {
    // For MM/YY format
    if (preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $endDate, $matches)) {
      $month = $matches[1];
      $year = '20' . $matches[2];
      
      $cardDate = mktime(0, 0, 0, $month + 1, 0, $year); // Last day of the month
      return $cardDate < time();
    }
    return false;
  }

  private function updateExpiredCards($userId = null)
  {
    // Get all cards
    $query = $userId ? 
      'SELECT payment_method_id, end_date, status FROM payment_methods WHERE user_id = ?' :
      'SELECT payment_method_id, end_date, status FROM payment_methods';
    
    $stmt = $this->db->prepare($query);
    $stmt->execute($userId ? [$userId] : []);
    $cards = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($cards as $card) {
      if ($this->isExpired($card['end_date']) && $card['status'] !== self::STATUS_EXPIRED) {
        $updateStmt = $this->db->prepare('
          UPDATE payment_methods 
          SET status = ? 
          WHERE payment_method_id = ?
        ');
        $updateStmt->execute([self::STATUS_EXPIRED, $card['payment_method_id']]);
      }
    }
  }

  public function getAll($userId)
  {
    // First update any expired cards
    $this->updateExpiredCards($userId);

    $query = $this->db->prepare('SELECT payment_method_id, user_id, bank, card_type, card_account, card_number, cardholder_name, start_date, end_date, cvv, status, created_at, updated_at FROM payment_methods WHERE user_id = ?');
    $query->execute([$userId]);
    return $query->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $inTransaction = $this->db->inTransaction();
    if (!$inTransaction) {
      $this->db->beginTransaction();
    }

    try {
      error_log("Debug: Starting payment method creation");
      error_log("Debug: Input data: " . json_encode($data));

      // Set initial status based on expiry
      $initialStatus = $this->isExpired($data['end_date']) ? 
        self::STATUS_EXPIRED : self::STATUS_VALID;

      $query = $this->db->prepare('
            INSERT INTO payment_methods (
                user_id, 
                bank, 
                card_type, 
                card_account, 
                card_number, 
                cardholder_name,
                start_date, 
                end_date, 
                cvv, 
                status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');

      error_log("Debug: Prepared query");

      $params = [
        $data['user_id'],
        $data['bank'],
        $data['card_type'],
        $data['card_account'],
        $data['card_number'],
        $data['cardholder_name'],
        $data['start_date'],
        $data['end_date'],
        $data['cvv'],
        $initialStatus
      ];

      error_log("Debug: Query params: " . json_encode($params));

      $success = $query->execute($params);

      if (!$success) {
        $errorInfo = $query->errorInfo();
        error_log("Debug: SQL Error: " . json_encode($errorInfo));
        throw new \Exception("Database error: " . implode(" ", $errorInfo));
      }

      $newId = $this->db->lastInsertId();
      error_log("Debug: New ID: " . $newId);

      if (!$inTransaction) {
        $this->db->commit();
      }

      $result = $this->getById($newId);
      error_log("Debug: Final result: " . json_encode($result));

      return $result;
    } catch (\Exception $e) {
      error_log("Debug: Exception in create: " . $e->getMessage());
      if (!$inTransaction) {
        $this->db->rollBack();
      }
      throw $e;
    }
  }

  public function getById($id)
  {
    try {
      error_log("Debug: Getting payment method by ID: " . $id);
      $query = $this->db->prepare('
            SELECT 
                payment_method_id, 
                user_id, 
                bank, 
                card_type, 
                card_account, 
                card_number, 
                cardholder_name,
                start_date, 
                end_date, 
                cvv, 
                status, 
                created_at, 
                updated_at 
            FROM payment_methods 
            WHERE payment_method_id = ?
        ');

      $success = $query->execute([$id]);

      if (!$success) {
        $errorInfo = $query->errorInfo();
        error_log("Debug: getById SQL Error: " . json_encode($errorInfo));
        return false;
      }

      $result = $query->fetch(\PDO::FETCH_ASSOC);
      error_log("Debug: getById result: " . json_encode($result));

      return $result;
    } catch (\Exception $e) {
      error_log("Debug: Exception in getById: " . $e->getMessage());
      return false;
    }
  }

  public function setDefaultStatus($userId, $paymentMethodId)
  {
    $inTransaction = $this->db->inTransaction();
    if (!$inTransaction) {
      $this->db->beginTransaction();
    }

    try {
      // Update expired statuses first
      $this->updateExpiredCards($userId);

      // Check if the card is valid
      $checkQuery = $this->db->prepare('
        SELECT status, end_date 
        FROM payment_methods 
        WHERE payment_method_id = ? 
        AND user_id = ?
      ');

      $checkQuery->execute([$paymentMethodId, $userId]);
      $card = $checkQuery->fetch(\PDO::FETCH_ASSOC);

      if (!$card || $card['status'] === self::STATUS_EXPIRED || $this->isExpired($card['end_date'])) {
        throw new \Exception("Cannot set expired card as default");
      }

      // Reset any existing default card
      $resetQuery = $this->db->prepare('
        UPDATE payment_methods 
        SET status = ?
        WHERE user_id = ?
        AND status = ?
      ');

      $resetSuccess = $resetQuery->execute([self::STATUS_VALID, $userId, self::STATUS_DEFAULT]);

      // Set the new default
      $query = $this->db->prepare('
        UPDATE payment_methods 
        SET status = ?
        WHERE payment_method_id = ?
        AND user_id = ?
      ');

      $success = $query->execute([self::STATUS_DEFAULT, $paymentMethodId, $userId]);

      if (!$success || !$resetSuccess) {
        throw new \Exception("Failed to update payment method");
      }

      if (!$inTransaction) {
        $this->db->commit();
      }

      return $this->getById($paymentMethodId);
    } catch (\Exception $e) {
      if (!$inTransaction) {
        $this->db->rollBack();
      }
      throw $e;
    }
  }

  public function delete($paymentMethodId)
  {
    $query = $this->db->prepare('DELETE FROM payment_methods WHERE payment_method_id = ?');
    return $query->execute([$paymentMethodId]);
  }
}