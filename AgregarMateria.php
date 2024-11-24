<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreMateria = $_POST['nombreMateria'];

    $servidor = "webll.mysql.database.azure.com";
    $usuario = "cuestionarios";
    $password = "Jano123.";
    $baseDatos = "soporte";
    
    $conexion = mysqli_init();
    mysqli_ssl_set($conexion, null, null, __DIR__ . "/certs/ca-cert.pem", null, null); // Ruta al certificado
    mysqli_real_connect($conexion, $servidor, $usuario, $password, $baseDatos, 3306, null, MYSQLI_CLIENT_SSL);
        if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $query = "INSERT INTO materia (NombreMateria) VALUES (?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $nombreMateria);

    if ($stmt->execute()) {
        header("Location: PaginaPrincipal.php");
    } else {
        echo "Error al agregar la materia: " . $conexion->error;
    }

    $stmt->close();
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
    <h1>Agregar Nueva Materia</h1>
    <form action="" method="POST">
        <label for="nombreMateria">Nombre de la Materia:</label>
        <input type="text" name="nombreMateria" id="nombreMateria" required>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
