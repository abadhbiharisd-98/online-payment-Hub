<?php

Class DBConnection{
    protected $db;
    function __construct(){
        $host = getenv('DB_HOST') ?: 'localhost';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
        $name = getenv('DB_NAME') ?: 'spms_db';
        $port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

        // Aiven MySQL requires SSL. Auto-detect Aiven or DB_SSL flag.
        $use_ssl = (getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1' || stripos($host, 'aivencloud.com') !== false);

        $this->db = mysqli_init();

        if(!$this->db){
            die("Database Initialization Error: mysqli_init failed.");
        }

        if($use_ssl){
            // Enable SSL for cloud MySQL providers like Aiven
            $this->db->ssl_set(NULL, NULL, NULL, NULL, NULL);
            if(defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')){
                $this->db->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
            }
            @$connected = $this->db->real_connect($host, $user, $pass, $name, $port, NULL, MYSQLI_CLIENT_SSL);
        } else {
            @$connected = $this->db->real_connect($host, $user, $pass, $name, $port);
        }

        if(!$connected || $this->db->connect_error){
            die("Database Connection Error (" . $this->db->connect_errno . "): " . $this->db->connect_error);
        }

        // Auto-initialize all database tables if employee_list or department_list is missing
        $table_check = @$this->db->query("SHOW TABLES LIKE 'employee_list'");
        if($table_check && $table_check->num_rows === 0){
            $sql_file = __DIR__ . '/db/spms_db.sql';
            if(file_exists($sql_file)){
                $sql_content = file_get_contents($sql_file);
                if(!empty($sql_content)){
                    $queries = preg_split('/;\s*[\r\n]+/', $sql_content);
                    foreach($queries as $query){
                        $query = trim($query);
                        if(empty($query)) continue;
                        try {
                            $this->db->query($query);
                        } catch(\Throwable $e){
                            // Continue on any notice/duplicate
                        }
                    }
                }
            }
        }
    }
    function connection(){
        return $this->db;
    }
    function __destruct(){
        if($this->db && !$this->db->connect_error){
            $this->db->close();
        }
    }
}

$db_connection = new DBConnection();
$conn = $db_connection->connection();
