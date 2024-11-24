<?php
$conexion = new mysqli("localhost", "root", "root", "soporte");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idMateria'])) {
    $idMateria = $_GET['idMateria'];

    $query = "SELECT NombreMateria FROM materia WHERE idMateria = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idMateria);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $materia = $resultado->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMateria = $_POST['idMateria'];
    $nombreMateria = $_POST['nombreMateria'];

    $query = "UPDATE materia SET NombreMateria = ? WHERE idMateria = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("si", $nombreMateria, $idMateria);

    if ($stmt->execute()) {
        header("Location: PaginaPrincipal.php");
    } else {
        echo "Error al actualizar la materia: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Materia</title>
</head>
<body>
    <h1>Editar Materia</h1>
    <form action="" method="POST">
        <input type="hidden" name="idMateria" value="<?php echo $idMateria; ?>">
        <label for="nombreMateria">Nombre de la Materia:</label>
        <input type="text" name="nombreMateria" id="nombreMateria" value="<?php echo $materia['NombreMateria']; ?>" required>
        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
