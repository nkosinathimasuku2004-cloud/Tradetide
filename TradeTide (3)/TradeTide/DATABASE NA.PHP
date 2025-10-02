<?php
/**
 * Database Configuration for TradeTide
 * InfinityFree Database Settings
 */

class Database {
    private $host = "sql111.infinityfree.com";
    private $db_name = "if0_40070886_tradetide_db";
    private $username = "if0_40070886";
    private $password = "your_vpanel_password_here"; // REPLACE WITH YOUR ACTUAL PASSWORD
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// For CodeIgniter compatibility (if needed)
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'sql111.infinityfree.com',
    'username' => 'if0_40070886',
    'password' => 'your_vpanel_password_here', // REPLACE WITH YOUR ACTUAL PASSWORD
    'database' => 'if0_40070886_tradetide_db',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
?>