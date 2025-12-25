<?php
/**
 * Health Check Endpoint
 * Quick check if system is running
 */

header('Content-Type: application/json');

$health = [
    'status' => 'ok',
    'timestamp' => date('Y-m-d H:i:s'),
    'checks' => []
];

// Check PHP
$health['checks']['php'] = [
    'status' => 'ok',
    'version' => phpversion()
];

// Check autoload
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    $health['checks']['autoload'] = ['status' => 'ok'];
} catch (\Exception $e) {
    $health['checks']['autoload'] = ['status' => 'error', 'message' => $e->getMessage()];
    $health['status'] = 'error';
}

// Check database
try {
    require_once __DIR__ . '/../src/config/Env.php';
    App\Config\Env::load();
    
    $db = App\Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT 1");
    
    $health['checks']['database'] = ['status' => 'ok'];
} catch (\Exception $e) {
    $health['checks']['database'] = ['status' => 'error', 'message' => $e->getMessage()];
    $health['status'] = 'error';
}

// Check session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$health['checks']['session'] = ['status' => 'ok', 'id' => session_id()];

// Overall status
http_response_code($health['status'] === 'ok' ? 200 : 500);
echo json_encode($health, JSON_PRETTY_PRINT);
?>
