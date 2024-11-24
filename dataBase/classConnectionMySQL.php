<?php
class ConnectionMySQL{
    private $host;
    private $user;
    private $password;
    private $database;
    private $conn;

   public function __construct(){  // Cambié _construct por __construct
        require_once "config_db.php";
        $this->host = HOST;
        $this->user = USER;
        $this->password = PASSWORD;
        $this->database = DATABASE;
    }

    public function CreateConnection(){
        $this->conn = new mysqli($this->host,$this->user,$this->password,$this->database);
        if($this->conn->connect_error){
            die("Error al conectarse a MYSQL: (".$this->conn->connect_error.") ".$this->conn->connect_error);
        }
    }
    public function CloseConnection(){
        $this->conn->close();
    }

    public function ExecuteQuery($sql){
        $result = $this->conn->query($sql);
        return $result;
    }

    public function GetCountAffectedRows(){
        return $this->conn->affected_rows;
    }

    public function GetRows($result){
        return $result->fetch_row();
    }

    public function SetFreeResult($result){
        $result->free_result();
    }
}
?>