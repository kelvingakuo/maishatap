<?php
//object oriented with PDO.
class Database{
    private $host = "localhost";
    private $db_name = "veivecok_maishatap";
    private $username = "veivecok_admin";
    private $password = "#1Qwertyuiop";
    public $conn;

    public function dbConnection(){
     $this->conn = null;
     try{
      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     } catch(PDOException $exception) {
      echo "Connection error: " . $exception->getMessage();
     }
     return $this->conn;
    }
}

//structured db connection
$conn = mysqli_connect("localhost", "veivecok_admin", "#1Qwertyuiop", "veivecok_maishatap");
?>