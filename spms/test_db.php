<?php
header('Content-Type: text/plain');

echo "=== Database Connection Diagnostic Tool ===\n\n";

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
$name = getenv('DB_NAME') ?: 'spms_db';
$port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;
$ssl  = (getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1' || stripos($host, 'aivencloud.com') !== false);

echo "Connecting with parameters:\n";
echo "Host: " . $host . "\n";
echo "Port: " . $port . "\n";
echo "User: " . $user . "\n";
echo "Database: " . $name . "\n";
echo "SSL Enabled: " . ($ssl ? "YES" : "NO") . "\n\n";

$db = mysqli_init();
if(!$db){
    die("FATAL: mysqli_init failed.\n");
}

if($ssl){
    $db->ssl_set(NULL, NULL, NULL, NULL, NULL);
    if(defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')){
        $db->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
    }
    $conn = @$db->real_connect($host, $user, $pass, $name, $port, NULL, MYSQLI_CLIENT_SSL);
} else {
    $conn = @$db->real_connect($host, $user, $pass, $name, $port);
}

if(!$conn || $db->connect_error){
    echo "FAILED: Connection Error (" . $db->connect_errno . "): " . $db->connect_error . "\n";
    exit(1);
}

echo "SUCCESS: Connected successfully to MySQL server!\n";
echo "Server Version: " . $db->server_info . "\n\n";

// Check tables
$result = $db->query("SHOW TABLES");
if($result){
    echo "Tables found in database '$name':\n";
    $count = 0;
    while($row = $result->fetch_row()){
        echo " - " . $row[0] . "\n";
        $count++;
    }
    if($count === 0){
        echo " (No tables found yet. Auto-import will run on startup)\n";
    }
} else {
    echo "Could not fetch tables: " . $db->error . "\n";
}

$db->close();
echo "\n=== Diagnostic Complete ===\n";
