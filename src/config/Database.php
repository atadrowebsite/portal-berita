<?php
/**
 * Database Configuration
 * Konfigurasi koneksi database MySQL
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'portal_berita';
    private $user = 'root';
    private $password = '';
    private $connection;

    public function connect() {
        $this->connection = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->db_name
        );

        // Check connection
        if ($this->connection->connect_error) {
            die('Connection Error: ' . $this->connection->connect_error);
        }

        // Set charset
        $this->connection->set_charset('utf8mb4');

        return $this->connection;
    }

    public static function getInstance() {
        static $instance = null;
        if ($instance === null) {
            $db = new Database();
            $instance = $db->connect();
        }
        return $instance;
    }
}
?>