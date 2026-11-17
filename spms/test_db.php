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

// Run full importer statement by statement
$sql_file = __DIR__ . '/db/spms_db.sql';
if(file_exists($sql_file)){
    $sql_content = file_get_contents($sql_file);
    // Split into individual SQL statements
    $queries = preg_split('/;\s*[\r\n]+/', $sql_content);
    echo "Executing " . count($queries) . " statements from spms_db.sql...\n";
    foreach($queries as $query){
        $query = trim($query);
        if(empty($query)) continue;
        try {
            $db->query($query);
        } catch(\Throwable $e){
            echo "Notice on query (" . substr($query, 0, 40) . "...): " . $e->getMessage() . "\n";
        }
    }
    echo "Import execution finished.\n\n";
}

// Ensure status column exists in employee_list
$col_check = @$db->query("SHOW COLUMNS FROM `employee_list` LIKE 'status'");
if($col_check && $col_check->num_rows === 0){
    echo "Adding missing 'status' column to employee_list...\n";
    try {
        $db->query("ALTER TABLE `employee_list` ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1 AFTER `monthly_salary`");
        $db->query("UPDATE `employee_list` SET `status` = 1 WHERE `status` IS NULL");
        echo "Successfully added 'status' column to employee_list!\n\n";
    } catch(\Throwable $e){
        echo "Notice on adding status column: " . $e->getMessage() . "\n\n";
    }
}

// Ensure payroll_type column exists in payroll_list
$col_check2 = @$db->query("SHOW COLUMNS FROM `payroll_list` LIKE 'payroll_type'");
if($col_check2 && $col_check2->num_rows === 0){
    echo "Adding missing 'payroll_type' column to payroll_list...\n";
    try {
        $db->query("ALTER TABLE `payroll_list` ADD COLUMN `payroll_type` tinyint(1) NOT NULL DEFAULT 1 AFTER `employee_id`");
        $db->query("UPDATE `payroll_list` SET `payroll_type` = 1 WHERE `payroll_type` IS NULL");
        echo "Successfully added 'payroll_type' column to payroll_list!\n\n";
    } catch(\Throwable $e){
        echo "Notice on adding payroll_type column: " . $e->getMessage() . "\n\n";
    }
}

// Fetch tables
$result2 = $db->query("SHOW TABLES");
if($result2){
    echo "Tables present in database '$name':\n";
    while($row = $result2->fetch_row()){
        echo " - " . $row[0] . "\n";
    }
}

$db->close();
echo "\n=== Diagnostic Complete ===\n";
