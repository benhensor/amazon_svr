<?php

namespace Controllers;

use Middleware\AuthMiddleware;
use Services\GenerateResponse;
use Models\Address;
use GuzzleHttp\Client;
use Exception;

class AddressController
{
  private $address;
  private $generateResponse;
  private $httpClient;
  private $executionStartTime;

  public function __construct()
  {
    $this->address = new Address();
    $this->generateResponse = new GenerateResponse();
    $this->httpClient = new Client();
    $this->executionStartTime = microtime(true);
  }

  public function getAllAddresses()
  {
    $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    try {
      // Your get all addresses logic here
      $addresses = $this->address->getAll($user['user_id']);

      // Transform the data before sending
      $transformedAddresses = array_map(function ($address) {
        return array_merge($address, [
          'is_default' => (bool)$address['is_default'],
          'is_billing' => (bool)$address['is_billing']
        ]);
      }, $addresses);

      return $this->generateResponse->send(
        'Success',
        200,
        'Addresses retrieved successfully',
        ['data' => $transformedAddresses]
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function createAddress()
  {
    $this->executionStartTime = microtime(true);
    error_log("Starting createAddress");

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    error_log("User authenticated: " . $user['user_id']);

    // Get and decode request body
    $data = json_decode(file_get_contents('php://input'), true);
    error_log("Received address data: " . print_r($data, true));
    if (!$data) {
      return $this->generateResponse->send(
        'Failure',
        400,
        'No address data provided'
      );
    }

    try {
      // Validate required fields
      if (
        !isset($user['user_id']) ||
        !isset($data['address_type']) ||
        !isset($data['full_name']) ||
        !isset($data['phone_number']) ||
        !isset($data['address_line1']) ||
        !isset($data['city']) ||
        !isset($data['postcode']) ||
        !isset($data['country']) ||
        !isset($data['delivery_instructions']) ||
        !isset($data['is_default']) ||
        !isset($data['is_billing'])
      ) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Missing required fields'
        );
      }

      error_log("Missing fields: " . print_r(array_filter([
        'user_id' => !isset($user['user_id']),
        'address_type' => !isset($data['address_type']),
        'full_name' => !isset($data['full_name']),
        'phone_number' => !isset($data['phone_number']),
        'address_line1' => !isset($data['address_line1']),
        'city' => !isset($data['city']),
        'postcode' => !isset($data['postcode']),
        'country' => !isset($data['country']),
        'delivery_instructions' => !isset($data['delivery_instructions']),
        'is_default' => !isset($data['is_default']),
        'is_billing' => !isset($data['is_billing'])
      ], function ($v) {
        return $v;
      }), true));

      // Sanitize inputs
      $full_name = trim(htmlspecialchars($data['full_name']));
      $phone_number = trim(htmlspecialchars($data['phone_number']));
      $address_line1 = trim(htmlspecialchars($data['address_line1']));
      $address_line2 = isset($data['address_line2']) ? trim(htmlspecialchars($data['address_line2'])) : null;
      $city = trim(htmlspecialchars($data['city']));
      $county = isset($data['county']) ? trim(htmlspecialchars($data['county'])) : null;
      $postcode = trim(htmlspecialchars($data['postcode']));
      $country = trim(htmlspecialchars($data['country']));
      $is_default = filter_var($data['is_default'], FILTER_VALIDATE_BOOLEAN);
      $is_billing = filter_var($data['is_billing'], FILTER_VALIDATE_BOOLEAN);
      $delivery_instructions = trim(htmlspecialchars($data['delivery_instructions']));
      $address_type = trim(htmlspecialchars($data['address_type']));

      // Validate data formats
      if (
        strlen($full_name) < 2 ||
        strlen($full_name) > 100 ||
        !preg_match('/^[0-9+\-\s()]*$/', $phone_number) ||
        strlen($phone_number) < 10 ||
        strlen($address_line1) < 5 ||
        strlen($city) < 2 ||
        strlen($postcode) < 3 ||
        strlen($country) < 2 ||
        !in_array(strtolower($address_type), ['home', 'work', 'other'])
      ) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid address data'
        );
      }

      // Your add address logic here
      $address = $this->address->create([
        'user_id' => $user['user_id'],
        'full_name' => $full_name,
        'phone_number' => $phone_number,
        'address_line1' => $address_line1,
        'address_line2' => $address_line2,
        'city' => $city,
        'county' => $county,
        'postcode' => $postcode,
        'country' => $country,
        'is_default' => $is_default,
        'is_billing' => $is_billing,
        'delivery_instructions' => $delivery_instructions,
        'address_type' => $address_type
      ]);

      error_log("Address creation result: " . print_r($address, true));

      if ($is_default && $address) {
        $this->address->setDefault($user['user_id'], $address['address_id']);
      }

      if (!$address) {
        throw new \Exception("Failed to create address");
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'Address added successfully',
        ['data' => $address]
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function updateAddress($addressId)
  {
    $this->executionStartTime = microtime(true);

    // Verify token
    $token = $_COOKIE['authToken'] ?? null;
    if (!$token) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'No authentication token provided'
      );
    }

    // Verify user
    $user = AuthMiddleware::authenticate();
    if (!$user) {
      return $this->generateResponse->send(
        'Failure',
        401,
        'Invalid authentication token'
      );
    }

    // Get and decode request body
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
      return $this->generateResponse->send(
        'Failure',
        400,
        'No address data provided'
      );
    }

    try {
      // Verify address belongs to user
      $existingAddress = $this->address->getById($addressId);
      if (!$existingAddress || $existingAddress['user_id'] !== $user['user_id']) {
        return $this->generateResponse->send(
          'Failure',
          403,
          'Address not found or access denied'
        );
      }

      // Validate required fields
      if (
        !isset($data['address_type']) ||
        !isset($data['full_name']) ||
        !isset($data['phone_number']) ||
        !isset($data['address_line1']) ||
        !isset($data['city']) ||
        !isset($data['postcode']) ||
        !isset($data['country']) ||
        !isset($data['delivery_instructions']) ||
        !isset($data['is_default']) ||
        !isset($data['is_billing'])
      ) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Missing required fields'
        );
      }

      // Sanitize inputs
      $full_name = trim(htmlspecialchars($data['full_name']));
      $phone_number = trim(htmlspecialchars($data['phone_number']));
      $address_line1 = trim(htmlspecialchars($data['address_line1']));
      $address_line2 = isset($data['address_line2']) ? trim(htmlspecialchars($data['address_line2'])) : null;
      $city = trim(htmlspecialchars($data['city']));
      $county = isset($data['county']) ? trim(htmlspecialchars($data['county'])) : null;
      $postcode = trim(htmlspecialchars($data['postcode']));
      $country = trim(htmlspecialchars($data['country']));
      $is_default = filter_var($data['is_default'], FILTER_VALIDATE_BOOLEAN);
      $is_billing = filter_var($data['is_billing'], FILTER_VALIDATE_BOOLEAN);
      $delivery_instructions = trim(htmlspecialchars($data['delivery_instructions']));
      $address_type = trim(htmlspecialchars($data['address_type']));

      // Validate data formats
      if (
        strlen($full_name) < 2 ||
        strlen($full_name) > 100 ||
        !preg_match('/^[0-9+\-\s()]*$/', $phone_number) ||
        strlen($phone_number) < 10 ||
        strlen($address_line1) < 5 ||
        strlen($city) < 2 ||
        strlen($postcode) < 3 ||
        strlen($country) < 2 ||
        !in_array(strtolower($address_type), ['home', 'work', 'other'])
      ) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid address data'
        );
      }

      // Update address
      $address = $this->address->update($addressId, [
        'user_id' => $user['user_id'],
        'full_name' => $full_name,
        'phone_number' => $phone_number,
        'address_line1' => $address_line1,
        'address_line2' => $address_line2,
        'city' => $city,
        'county' => $county,
        'postcode' => $postcode,
        'country' => $country,
        'is_default' => $is_default,
        'is_billing' => $is_billing,
        'delivery_instructions' => $delivery_instructions,
        'address_type' => $address_type
      ]);

      if (!$address) {
        throw new \Exception("Failed to update address");
      }

      // Handle default address setting if needed
      if ($is_default) {
        $this->address->setDefault($user['user_id'], $addressId);
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'Address updated successfully',
        ['data' => $address]
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function setDefaultAddress($addressId)
  {
    $this->executionStartTime = microtime(true);

    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Get address ID from URL
      if (!$addressId) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Address ID is required'
        );
      }

      // Verify address belongs to user
      $address = $this->address->getById($addressId);
      if (!$address || $address['user_id'] !== $user['user_id']) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Address does not belong to user'
        );
      }

      // Set default address
      $defaultAddress = $this->address->setDefault($user['user_id'], $addressId);
      if (!$defaultAddress) {
        return $this->generateResponse->send(
          'Failure',
          500,
          'Failed to set default address'
        );
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'Default address set successfully',
        ['address' => $defaultAddress]
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function deleteAddress($addressId)
  {
    $this->executionStartTime = microtime(true);

    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Verify address id
      if (!$addressId) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Address ID is required'
        );
      }

      // Verify address belongs to user
      $address = $this->address->getById($addressId);
      if (!$address || $address['user_id'] !== $user['user_id']) {
        return $this->generateResponse->send(
          'Failure',
          403,
          'Address not found or access denied'
        );
      }

      // Delete address
      $deleted = $this->address->delete($addressId);
      if (!$deleted) {
        return $this->generateResponse->send(
          'Failure',
          500,
          'Failed to delete address'
        );
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'Address deleted successfully',
        ['address_id' => $addressId]
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }
}
