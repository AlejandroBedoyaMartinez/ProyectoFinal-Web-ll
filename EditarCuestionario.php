<?php
session_start();

$conexion = new mysqli("webll.mysql.database.azure.com", "cuestionarios", "Jano123.", "soporte");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_GET['idCuestionario'])) {
    $idCuestionario = $_GET['idCuestionario'];

    $queryCuestionario = "SELECT * FROM cuestionarios WHERE idCuestionario = ?";
    
    if ($stmt = $conexion->prepare($queryCuestionario)) {
        $stmt->bind_param("i", $idCuestionario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $cuestionario = $resultado->fetch_assoc();
        } else {
            echo "No se encontró el cuestionario.";
            exit;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta.";
        exit;
    }
} else {
    echo "No se proporcionó el ID del cuestionario.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cuestionario</title>
    <link rel="stylesheet" href="styles/editarCuestionario.css">
</head>
<body>
    <h1>Editar Cuestionario</h1>
    <div id="Principal">
        <form action="ActualizarCuestionario.php" method="POST">
            <input type="hidden" name="idCuestionario" value="<?php echo $cuestionario['idCuestionario']; ?>">
            <label for="nombreCuestionario">Nombre del Cuestionario:</label>
            <br>
            <br>
            <input type="text" id="nombreCuestionario" name="nombreCuestionario" value="<?php echo $cuestionario['NombreCuestionario']; ?>" required>
            <br>
            <br>
            <label for="cuestionarioTexto">Texto del Cuestionario:</label>
            <br>
            <br>
            <textarea id="cuestionarioTexto" name="cuestionarioTexto" required><?php echo $cuestionario['cuestionarioTexto']; ?></textarea>
            <br>
            <br>
            <button type="submit">Actualizar Cuestionario</button>
        </form>
    </div>
    
</body>
</html>
