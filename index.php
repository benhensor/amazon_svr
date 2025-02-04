<?php
require_once __DIR__ . '/vendor/autoload.php';

use Config\Database;

// env vars
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// error reporting
if ($_ENV['PHP_ENV'] === 'development') {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

// cors
$allowedOrigin = $_ENV['FRONTEND_URL'] ?? 'http://localhost:3000';

header('Access-Control-Allow-Origin: ' . $allowedOrigin);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('HTTP/1.1 200 OK');
  exit();
}

// parse JSON body
$rawInput = file_get_contents('php://input');
if (!empty($rawInput)) {
  $_POST = json_decode($rawInput, true) ?? [];
}

// test connection
try {
  $db = Database::getInstance()->getConnection();
  error_log('Database connection established');
} catch (Exception $e) {
  error_log('Unable to connect to teh database: ' . $e->getMessage());
  error_log('Full error details: ' . json_encode($e->getMessage(), JSON_PRETTY_PRINT));
  exit('Database connection failed');
}

// route handling
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// parse route
$path = parse_url($requestUri, PHP_URL_PATH);
$pathParts = array_values(array_filter(explode('/', trim($path, '/'))));

// Debug logging
error_log("Path parts: " . json_encode($pathParts));

// Parse the route
$path = parse_url($requestUri, PHP_URL_PATH);
$baseRoute = $pathParts[1] ?? ''; // 'api'
$resource = $pathParts[2] ?? ''; // 'auth', 'users', 'books', 'messages'
$action = $pathParts[3] ?? ''; // specific endpoint

// response type
header('Content-Type: application/json');


try {
  switch ("$baseRoute/$resource") {
    case 'api/test':
      echo json_encode(['message' => 'Router is working']);
      exit;
    case 'api/user':
      require_once __DIR__ . '/routes/UserRoutes.php';
      break;
    case 'api/addresses':
      require_once __DIR__ . '/routes/AddressRoutes.php';
      break;
    case 'api/basket':
      require_once __DIR__ . '/routes/BasketRoutes.php';
      break;
    case 'api/order':
      require_once __DIR__ . '/routes/OrderRoutes.php';
      break;
    case 'api/payment-methods':
      require_once __DIR__ . '/routes/PaymentMethodRoutes.php';
      break;
    default:
      http_response_code(404);
      echo json_encode(['error' => 'Route not found', 'resource' => $resource]);
      break;
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
