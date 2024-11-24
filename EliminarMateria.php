<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMateria = $_POST['idMateria'];

    $conexion = new mysqli("localhost", "root", "root", "soporte");
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $query = "DELETE FROM materia WHERE idMateria = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idMateria);

    if ($stmt->execute()) {
        header("Location: PaginaPrincipal.php");
    } else {
        echo "Error al eliminar la materia: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
