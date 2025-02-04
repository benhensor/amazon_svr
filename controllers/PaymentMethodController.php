<?php

namespace Controllers;

use Middleware\AuthMiddleware;
use Services\GenerateResponse;
use Models\PaymentMethod;
use GuzzleHttp\Client;
use Exception;

class PaymentMethodController
{
  private $paymentMethod;
  private $generateResponse;
  private $httpClient;
  private $executionStartTime;

  public function __construct()
  {
    $this->paymentMethod = new PaymentMethod();
    $this->generateResponse = new GenerateResponse();
    $this->httpClient = new Client();
    $this->executionStartTime = microtime(true);
  }

  public function fetchPaymentMethods()
  {
    $this->executionStartTime = microtime(true);
    
    try {

      // Verify user
      $user = AuthMiddleware::authenticate();
      // Your get all addresses logic here

      if ($user) $paymentMethods = $this->paymentMethod->getAll($user['user_id']);

      return $this->generateResponse->send(
        'Success',
        200,
        'Payment methods retrieved successfully',
        $paymentMethods
      );
    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function addPaymentMethod($data)
  {
    $this->executionStartTime = microtime(true);
    
    try {
  
      // Verify user
      $user = AuthMiddleware::authenticate();
  
      error_log("Debug: User: " . json_encode($user));
      error_log("Debug: Data: " . json_encode($data));
      // Get and decode request body
      if (!$data) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'No payment method data provided'
        );
      }
      // Validate required fields
      if (
        !isset($user['user_id']) ||
        !isset($data['bank']) ||
        !isset($data['card_type']) ||
        !isset($data['cardholder_name']) ||
        !isset($data['card_account']) ||
        !isset($data['card_number']) ||
        !isset($data['start_date']) ||
        !isset($data['end_date']) ||
        !isset($data['cvv']) ||
        !isset($data['status'])
      ) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Missing required fields'
        );
      }

      // Sanitize inputs
      $bank = trim($data['bank']);
      $card_type = trim($data['card_type']);
      $cardholder_name = trim($data['cardholder_name']);
      $card_account = trim($data['card_account']);
      $card_number = trim($data['card_number']);
      $start_date = trim($data['start_date']);
      $end_date = trim($data['end_date']);
      $cvv = trim($data['cvv']);
      $status = trim($data['status']);

      // Validate data formats
      if (
        strlen($bank) < 2 ||
        strlen($card_type) < 2 ||
        strlen($cardholder_name) < 2 ||
        strlen($card_account) < 2 ||
        strlen($card_number) < 2 ||
        strlen($start_date) < 2 ||
        strlen($end_date) < 2 ||
        strlen($cvv) < 2 ||
        strlen($status) < 2
      ) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Invalid payment method data'
        );
      }

      // Add payment method
      $paymentMethod = $this->paymentMethod->create([
        'user_id' => $user['user_id'],
        'bank' => $bank,
        'card_type' => $card_type,
        'cardholder_name' => $cardholder_name,
        'card_account' => $card_account,
        'card_number' => $card_number,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'cvv' => $cvv,
        'status' => $status
      ]);

      error_log("Debug: Payment method: " . json_encode($paymentMethod));

      if (!$paymentMethod) {
        throw new \Exception("Failed to create payment method");
      }

      return $this->generateResponse->send(
        'Success',
        201,
        'Payment method added successfully',
        $paymentMethod
      );

    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function setDefaultPaymentMethod($paymentMethodId)
  {
    $this->executionStartTime = microtime(true);
    
    try {
  
      // Verify user
      $user = AuthMiddleware::authenticate();

      // Get address ID from URL
      if (!$paymentMethodId) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Payment method ID is required'
        );
      }
  
      // Verify payment method belongs to user
      $paymentMethod = $this->paymentMethod->getById($paymentMethodId);
      if (!$paymentMethod || $paymentMethod['user_id'] !== $user['user_id']) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Payment method does not belong to user'
        );
      }

      // Set default payment method
      $defaultPaymentMethod = $this->paymentMethod->setDefaultStatus($user['user_id'], $paymentMethodId);
      if (!$defaultPaymentMethod) {
        return $this->generateResponse->send(
          'Failure',
          500,
          'Failed to set default payment method'
        );
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'Default payment method set successfully',
        $defaultPaymentMethod
      );

    } catch (Exception $e) {
      return $this->generateResponse->send(
        'Error',
        500,
        'Internal server error: ' . $e->getMessage()
      );
    }
  }

  public function deletePaymentMethod($paymentMethodId)
  {
    $this->executionStartTime = microtime(true);

    try {

      // Auth check
      $user = AuthMiddleware::authenticate();

      // Get address ID from URL
      if (!$paymentMethodId) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Payment method ID is required'
        );
      }

      // Verify address belongs to user
      $paymentMethod = $this->paymentMethod->getById($paymentMethodId);
      if (!$paymentMethod || $paymentMethod['user_id'] !== $user['user_id']) {
        return $this->generateResponse->send(
          'Failure',
          400,
          'Payment method does not belong to user'
        );
      }

      // Delete payment method
      $deleted = $this->paymentMethod->delete($paymentMethodId);
      if (!$deleted) {
        return $this->generateResponse->send(
          'Failure',
          500,
          'Failed to delete payment method'
        );
      }

      return $this->generateResponse->send(
        'Success',
        200,
        'Payment method deleted successfully'
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
