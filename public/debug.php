<?php
/**
 * Debug file untuk cek environment di production
 * Akses: https://siapkak.my.id/siapkak/debug.php
 * HAPUS FILE INI setelah debugging selesai!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>SIAPKAK Debug Info</h1>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4;}h2{color:#4ec9b0;}pre{background:#2d2d2d;padding:15px;border-radius:5px;overflow-x:auto;}.ok{color:#4ec9b0;}.error{color:#f48771;}</style>";

echo "<h2>1. PHP Environment</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Path: " . __DIR__ . "\n";
echo "</pre>";

echo "<h2>2. Required Extensions</h2>";
echo "<pre>";
$required = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'openssl', 'curl'];
foreach ($required as $ext) {
    $status = extension_loaded($ext) ? '<span class="ok">✓ OK</span>' : '<span class="error">✗ MISSING</span>';
    echo "$ext: $status\n";
}
echo "</pre>";

echo "<h2>3. File Paths</h2>";
echo "<pre>";
$paths = [
    'Vendor Autoload' => __DIR__ . '/../vendor/autoload.php',
    '.env File' => __DIR__ . '/../.env',
    'Database Config' => __DIR__ . '/../src/config/Database.php',
    'PermissionHelper' => __DIR__ . '/../src/config/PermissionHelper.php',
];
foreach ($paths as $name => $path) {
    $exists = file_exists($path) ? '<span class="ok">✓ EXISTS</span>' : '<span class="error">✗ NOT FOUND</span>';
    echo "$name: $exists\n  → $path\n";
}
echo "</pre>";

echo "<h2>4. Session Test</h2>";
echo "<pre>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "Session started: <span class='ok'>✓ OK</span>\n";
} else {
    echo "Session already active: <span class='ok'>✓ OK</span>\n";
}
echo "Session ID: " . session_id() . "\n";
echo "Session save path: " . session_save_path() . "\n";
echo "</pre>";

echo "<h2>5. Database Connection</h2>";
echo "<pre>";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../src/config/Env.php';
    
    App\Config\Env::load();
    
    $host = $_ENV['DB_HOST'] ?? 'not set';
    $name = $_ENV['DB_NAME'] ?? 'not set';
    $user = $_ENV['DB_USER'] ?? 'not set';
    
    echo "DB Host: $host\n";
    echo "DB Name: $name\n";
    echo "DB User: $user\n";
    
    $db = App\Config\Database::getInstance()->getConnection();
    echo "Connection: <span class='ok'>✓ CONNECTED</span>\n";
    
    // Test query
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "Users count: " . $result['count'] . "\n";
    
    // Check menus table
    $stmt = $db->query("SHOW TABLES LIKE 'menus'");
    if ($stmt->rowCount() > 0) {
        echo "Menus table: <span class='ok'>✓ EXISTS</span>\n";
        $stmt = $db->query("SELECT COUNT(*) as count FROM menus");
        $result = $stmt->fetch();
        echo "Menus count: " . $result['count'] . "\n";
    } else {
        echo "Menus table: <span class='error'>✗ NOT FOUND</span>\n";
    }
    
} catch (\Exception $e) {
    echo "<span class='error'>✗ ERROR: " . $e->getMessage() . "</span>\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
echo "</pre>";

echo "<h2>6. PermissionHelper Test</h2>";
echo "<pre>";
try {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = 1;
    $_SESSION['user_role'] = 'admin';
    
    App\Config\PermissionHelper::init();
    echo "PermissionHelper: <span class='ok'>✓ INITIALIZED</span>\n";
    
} catch (\Exception $e) {
    echo "<span class='error'>✗ ERROR: " . $e->getMessage() . "</span>\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
echo "</pre>";

echo "<h2>7. Error Log (Last 20 lines)</h2>";
echo "<pre>";
$errorLog = ini_get('error_log');
if ($errorLog && file_exists($errorLog)) {
    $lines = file($errorLog);
    $last20 = array_slice($lines, -20);
    echo htmlspecialchars(implode('', $last20));
} else {
    echo "Error log path: $errorLog\n";
    echo "Error log not accessible or not configured\n";
}
echo "</pre>";

echo "<hr><p><strong>⚠️ HAPUS FILE INI (debug.php) setelah selesai debugging!</strong></p>";
?>
