<?php

namespace Models;

class Address
{
  private $db;

  public function __construct()
  {
    $this->db = \Config\Database::getInstance()->getConnection();
  }

  public function getAll($userId)
  {
    $query = $this->db->prepare('SELECT address_id, user_id, full_name, phone_number, address_line1, address_line2, city, county, postcode, country, is_default, is_billing, delivery_instructions, address_type FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC');
    $query->execute([$userId]);
    $result = $query->fetchAll(\PDO::FETCH_ASSOC);
    error_log("Database result: " . json_encode($result)); // Debug log
    return $result;
  }

  public function getById($id)
  {
    $query = $this->db->prepare('SELECT address_id, user_id, address_line1, address_line2, city, county, postcode, country, is_default, is_billing, delivery_instructions, address_type FROM addresses WHERE address_id = ?');
    $query->execute([$id]);
    return $query->fetch();
  }

  public function create($data)
  {
    $inTransaction = $this->db->inTransaction();
    if (!$inTransaction) {
      $this->db->beginTransaction();
    }

    $isDefault = $data['is_default'] ? 1 : 0;
    $isBilling = $data['is_billing'] ? 1 : 0;

    $query = $this->db->prepare('INSERT INTO addresses (user_id, full_name, phone_number, address_line1, address_line2, city, county, postcode, country, is_default, is_billing, delivery_instructions, address_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $success = $query->execute([$data['user_id'], $data['full_name'], $data['phone_number'], $data['address_line1'], $data['address_line2'], $data['city'], $data['county'], $data['postcode'], $data['country'], $isDefault, $isBilling, $data['delivery_instructions'], $data['address_type']]);

    if (!$success) {
      if (!$inTransaction) {
        $this->db->rollBack();
      }
      throw new \Exception("Failed to insert address");
    }

    $newAddressId = $this->db->lastInsertId();

    $fetchQuery = $this->db->prepare('SELECT address_id, user_id, full_name, phone_number, address_line1, address_line2, city, county, postcode, country, is_default, is_billing, delivery_instructions, address_type FROM addresses WHERE address_id = ?');
    $fetchQuery->execute([$newAddressId]);
    $newAddress = $fetchQuery->fetch(\PDO::FETCH_ASSOC);

    if (!$inTransaction) {
      $this->db->commit();
    }
    return $newAddress;
  }

  public function update($addressId, $data)
  {
    $inTransaction = $this->db->inTransaction();
    if (!$inTransaction) {
      $this->db->beginTransaction();
    }

    $isDefault = $data['is_default'] ? 1 : 0;
    $isBilling = $data['is_billing'] ? 1 : 0;

    $query = $this->db->prepare('
      UPDATE addresses 
      SET full_name = ?, 
        phone_number = ?,
        address_line1 = ?, 
        address_line2 = ?, 
        city = ?, 
        county = ?, 
        postcode = ?, 
        country = ?, 
        is_default = ?, 
        is_billing = ?, 
        delivery_instructions = ?, 
        address_type = ? 
      WHERE address_id = ?
    ');

    $success = $query->execute([
      $data['full_name'],
      $data['phone_number'],
      $data['address_line1'],
      $data['address_line2'],
      $data['city'],
      $data['county'],
      $data['postcode'],
      $data['country'],
      $isDefault,
      $isBilling,
      $data['delivery_instructions'],
      $data['address_type'],
      $addressId
    ]);

    if (!$success) {
      if (!$inTransaction) {
        $this->db->rollBack();
      }
      throw new \Exception("Failed to update address");
    }

    // Fetch the updated address
    $fetchQuery = $this->db->prepare('
      SELECT address_id, user_id, full_name, phone_number, address_line1, 
        address_line2, city, county, postcode, country, is_default, 
        is_billing, delivery_instructions, address_type 
      FROM addresses 
      WHERE address_id = ?
    ');
    $fetchQuery->execute([$addressId]);
    $updatedAddress = $fetchQuery->fetch(\PDO::FETCH_ASSOC);

    if (!$inTransaction) {
      $this->db->commit();
    }
    return $updatedAddress;
  }

  public function setDefault($userId, $addressId)
  {
    $this->db->beginTransaction();
    try {
      // Reset all addresses to not default
      $query = $this->db->prepare('UPDATE addresses SET is_default = 0 WHERE user_id = ?');
      $query->execute([$userId]);
      // Set the selected address to default
      $query = $this->db->prepare('UPDATE addresses SET is_default = 1 WHERE address_id = ? AND user_id = ?');
      $query->execute([$addressId, $userId]);
      $this->db->commit();
      return true;
    } catch (\Exception $e) {
      $this->db->rollBack();
      return false;
    }
  }

  public function delete($addressId)
  {
    $query = $this->db->prepare('DELETE FROM addresses WHERE address_id = ?');
    return $query->execute([$addressId]);
  }
}
