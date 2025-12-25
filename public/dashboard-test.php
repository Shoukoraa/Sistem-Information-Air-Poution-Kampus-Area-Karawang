<?php
/**
 * Simple Dashboard Test - No PermissionHelper
 * Test file untuk verify routing works
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /siapkak/login.php');
    exit();
}

$userName = $_SESSION['user_name'] ?? 'User';
$userRole = $_SESSION['user_role'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Test - SIAPKAK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-green-600 mb-4">✅ Dashboard Working!</h1>
            
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <p class="font-semibold">Good news! Basic routing is working.</p>
                <p class="text-sm text-gray-600 mt-2">This means your folder restructure was successful.</p>
            </div>

            <div class="space-y-4">
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="font-semibold text-blue-600">Session Info:</h3>
                    <ul class="text-sm space-y-1 mt-2">
                        <li>👤 Name: <?php echo htmlspecialchars($userName); ?></li>
                        <li>🔑 Role: <?php echo htmlspecialchars($userRole); ?></li>
                        <li>🆔 User ID: <?php echo $_SESSION['user_id'] ?? 'N/A'; ?></li>
                        <li>📧 Email: <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'N/A'); ?></li>
                    </ul>
                </div>

                <div class="border-l-4 border-yellow-500 pl-4">
                    <h3 class="font-semibold text-yellow-600">File Paths:</h3>
                    <ul class="text-sm space-y-1 mt-2 font-mono">
                        <li>📁 __DIR__: <?php echo __DIR__; ?></li>
                        <li>📄 __FILE__: <?php echo __FILE__; ?></li>
                        <li>🌐 DOCUMENT_ROOT: <?php echo $_SERVER['DOCUMENT_ROOT']; ?></li>
                    </ul>
                </div>

                <div class="border-l-4 border-purple-500 pl-4">
                    <h3 class="font-semibold text-purple-600">Next Steps:</h3>
                    <ol class="text-sm space-y-2 mt-2 list-decimal list-inside">
                        <li>Akses <a href="/siapkak/debug.php" class="text-blue-600 underline">/siapkak/debug.php</a> untuk full diagnostic</li>
                        <li>Pastikan database sudah di-import (siapkak.sql)</li>
                        <li>Check .env file sudah di-setup dengan benar</li>
                        <li>Verify table 'menus' sudah ada di database</li>
                        <li>Setelah fix, akses dashboard asli: <a href="/siapkak/dashboard" class="text-blue-600 underline">/siapkak/dashboard</a></li>
                    </ol>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <a href="/siapkak/debug.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    🔍 Run Full Diagnostic
                </a>
                <a href="/siapkak/dashboard" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    ➡️ Try Real Dashboard
                </a>
                <a href="/siapkak/auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    🚪 Logout
                </a>
            </div>
        </div>
    </div>
</body>
</html>
