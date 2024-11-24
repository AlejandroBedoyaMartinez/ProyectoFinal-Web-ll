<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreMateria = $_POST['nombreMateria'];

    $conexion = new mysqli("webll.mysql.database.azure.com", "cuestionarios", "Jano123.", "soporte");
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
