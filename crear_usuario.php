<?php
$servidor = "webll.mysql.database.azure.com";
$usuario = "cuestionarios";
$password = "Jano123.";
$baseDatos = "soporte";

$conexion = mysqli_init();
mysqli_ssl_set($conexion, null, null, __DIR__ . "/certs/ca-cert.pem", null, null); // Ruta al certificado
mysqli_real_connect($conexion, $servidor, $usuario, $password, $baseDatos, 3306, null, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['user'];
    $password = $_POST['pass'];

    $query = "INSERT INTO usuarios (User, Pass) VALUES (?, ?)";

    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("ss", $usuario, $password); 
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: login.html");
        } else {
            echo "Error al crear el usuario.";
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta.";
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <form method="POST" action="crear_usuario.php">
    <h1>Crear Nuevo Usuario</h1>
        <div>
            <label for="user">Usuario:</label>
            <br>
            <input type="text" name="user" id="user" required>
        </div>
        <br>
        <div>
            <label for="pass">Contraseña:</label>
            <br>
            <input type="password" name="pass" id="pass" required>
        </div>
        <br>
        <div>
            <input type="submit" value="Crear Cuenta">
        </div>
        <br>
    </form>
</body>
</html>
