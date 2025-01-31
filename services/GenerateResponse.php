<?php
namespace Services;

class GenerateResponse {
  private $executionStartTime;

  public function __construct() {
    $this->executionStartTime = microtime(true);
  }

  public function send($status, $code, $message, $data = null) {

    http_response_code($code);

    $response = [
      'status' => [
        'code' => $code,
        'name' => $status,
        'description' => $message,
        'seconds' => number_format((microtime(true) - $this->executionStartTime), 3)
      ],
      'data' => $data
    ];

    echo json_encode($response);
    exit(); 
  }
}