<?php

Class DBConnection{
    protected $db;
    function __construct(){
        $host = getenv('DB_HOST') ?: 'localhost';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
        $name = getenv('DB_NAME') ?: 'spms_db';
        $port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

        $this->db = new mysqli($host, $user, $pass, $name, $port);
        if($this->db->connect_error){
            die("Database Connection Error: " . $this->db->connect_error);
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
