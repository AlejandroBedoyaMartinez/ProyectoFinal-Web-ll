<?php
session_start();
$user = $_POST['user'];
$pass = $_POST['pass'];

$servidor = "webll.mysql.database.azure.com";
$usuario = "cuestionarios";
$password = "Jano123.";
$baseDatos = "soporte";

$conexion = mysqli_init();
mysqli_ssl_set($conexion, null, null, __DIR__ . "/certs/ca-cert.pem", null, null); 
mysqli_real_connect($conexion, $servidor, $usuario, $password, $baseDatos, 3306, null, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}

$query = "SELECT idUsuario, user, pass FROM usuarios WHERE user = ? AND pass = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ss", $user, $pass); 
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user'] = $user; 
    $_SESSION['idUsuario'] = $row['idUsuario']; 

    header("Location: PaginaPrincipal.php");
    exit();
} else {
    echo "<h3>El usuario o la contraseña son incorrectos</h3>";
}

$stmt->close();
$conexion->close();
?>
