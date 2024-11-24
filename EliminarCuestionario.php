<?php
session_start();

$conexion = new mysqli("localhost", "root", "root", "soporte");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_POST['idCuestionario'])) {
    $idCuestionario = $_POST['idCuestionario'];

    $queryEliminar = "DELETE FROM cuestionarios WHERE idCuestionario = ?";

    if ($stmt = $conexion->prepare($queryEliminar)) {
        $stmt->bind_param("i", $idCuestionario);
        
        if ($stmt->execute()) {
            header("Location: PaginaPrincipal.php?mensaje=El cuestionario fue eliminado correctamente.");
            exit;
        } else {
            echo "Error al eliminar el cuestionario.";
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta.";
    }
} else {
    echo "No se proporcionó el ID del cuestionario.";
}
?>
