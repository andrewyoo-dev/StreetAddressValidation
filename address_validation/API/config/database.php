<?php
class Database{
    private $server = "andrewy3_address";
    private $user = "andrewy3_address";
    private $host = "localhost";
    private $password = "password";
    public $conn;

    public function dbConnection(){
        $this->conn = null;
        
        try
        {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->server, $this->user, $this->password);
            $this->conn->exec("set names utf8");
        } 
        catch (PDOException $e)
        {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}