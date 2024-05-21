<?php
class Database {
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "sklep";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        $this->conn->close();
    }
}
?>

