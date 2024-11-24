<?php
session_start();
require("dataBase/classConnectionMySQL.php");
$user = $_POST['user'];
$pass = $_POST['pass'];

echo "<h1> EL usuario es: ".$user." EL password es ".$pass."</h1>";

$NewConn = new ConnectionMySQL();

$NewConn->CreateConnection();
echo "<h1> EL usuario es: ".$user." EL password es ".$pass."</h1>";

$query = "Select * from usuarios where user= '$user' and pass = '$pass'";
$result = $NewConn->ExecuteQuery($query);
if($result){
    $RowCount = $NewConn->GetCountAffectedRows();
    if($RowCount > 0){
        echo "Query ejecutado exitosamente <br/>";
        header("Location: PaginaPrincipal.php");
        $_SESSION['user'] = $user;
    }else{
        echo "<h3> El usuario o la contrasena son incorrectos </h3>";
    }
}   
?>