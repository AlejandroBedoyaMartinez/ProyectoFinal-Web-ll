<?php
class ConnectionMySQL {
    private $host;
    private $user;
    private $password;
    private $database;
    private $conn;

    public function __construct() {
        require_once "config_db.php";
        $this->host = HOST;
        $this->user = USER;
        $this->password = PASSWORD;
        $this->database = DATABASE;
    }

    public function CreateConnection() {
        $this->conn = mysqli_init();
        mysqli_ssl_set($this->conn, null, null, __DIR__ . "/../certs/ca-cert.pem", null, null); // Ruta al certificado

        if (!mysqli_real_connect($this->conn, $this->host, $this->user, $this->password, $this->database, 3306, null, MYSQLI_CLIENT_SSL)) {
            die("Error al conectarse a MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
        }
    }

    public function CloseConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    public function ExecuteQuery($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error al ejecutar la consulta: (" . $this->conn->errno . ") " . $this->conn->error);
        }
        return $result;
    }

    public function GetCountAffectedRows() {
        return $this->conn->affected_rows;
    }

    public function GetRows($result) {
        return $result->fetch_row();
    }

    public function SetFreeResult($result) {
        $result->free();
    }
}
?>
