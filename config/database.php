<?php
class Database {
    private $host = "localhost";
    private $db_name = "attendance_db";
    private $username = "root";
    private $password = "S3curity@300";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "";
        } catch(PDOException $exception) {
            echo "<div class='alert alert-danger'>Connection error: " . $exception->getMessage() . "</div>";
        }

        return $this->conn;
    }
}
