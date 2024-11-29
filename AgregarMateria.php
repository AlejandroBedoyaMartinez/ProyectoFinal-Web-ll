<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }

    $nombreMateria = $_POST['nombreMateria'];

    $servidor = "webll.mysql.database.azure.com";
    $usuario = "cuestionarios";
    $password = "Jano123.";
    $baseDatos = "soporte";
    
    $conexion = mysqli_init();
    mysqli_ssl_set($conexion, null, null, __DIR__ . "/certs/ca-cert.pem", null, null); 
    mysqli_real_connect($conexion, $servidor, $usuario, $password, $baseDatos, 3306, null, MYSQLI_CLIENT_SSL);
    
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $queryUsuario = "SELECT idUsuario FROM usuarios WHERE user = ?";
    $stmtUsuario = $conexion->prepare($queryUsuario);
    $stmtUsuario->bind_param("s", $_SESSION['user']);
    $stmtUsuario->execute();
    $resultUsuario = $stmtUsuario->get_result();

    if ($resultUsuario && $resultUsuario->num_rows > 0) {
        $row = $resultUsuario->fetch_assoc();
        $idUsuario = $row['idUsuario'];
    } else {
        die("Usuario no encontrado.");
    }

    $query = "INSERT INTO materia (NombreMateria, idUsuario) VALUES (?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("si", $nombreMateria, $idUsuario);

    if ($stmt->execute()) {
        header("Location: PaginaPrincipal.php");
    } else {
        echo "Error al agregar la materia: " . $conexion->error;
    }

    $stmt->close();
    $stmtUsuario->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Materia</title>
    <link rel="stylesheet" href="styles/AgregarEditarMateria.css">
</head>
<body>
<a href="PaginaPrincipal.php" class="regreso">Inicio</a>
    <h1>Agregar Nueva Materia</h1>
    <form action="AgregarMateria.php" method="POST">
        <label for="nombreMateria">Nombre de la Materia:</label>
        <input type="text" name="nombreMateria" id="nombreMateria" required>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
