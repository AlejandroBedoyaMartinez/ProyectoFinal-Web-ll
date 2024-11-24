<?php
session_start();
$user = $_POST['user'];
$pass = $_POST['pass'];

$servidor = "webll.mysql.database.azure.com";
$usuario = "cuestionarios";
$password = "Jano123.";
$baseDatos = "soporte";

// Inicializar conexión segura con SSL
$conexion = mysqli_init();
mysqli_ssl_set($conexion, null, null, __DIR__ . "/certs/ca-cert.pem", null, null); // Ajusta la ruta si es necesario
mysqli_real_connect($conexion, $servidor, $usuario, $password, $baseDatos, 3306, null, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}

$query = "SELECT * FROM usuarios WHERE user = ? AND pass = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ss", $user, $pass); // 'ss' indica que ambas variables son cadenas de texto
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $_SESSION['user'] = $user;
    header("Location: PaginaPrincipal.php");
    exit();
} else {
    echo "<h3>El usuario o la contraseña son incorrectos</h3>";
}

$stmt->close();
$conexion->close();
?>
