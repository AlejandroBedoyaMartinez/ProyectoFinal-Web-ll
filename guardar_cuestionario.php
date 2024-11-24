<?php
session_start();

$conexion = new mysqli("webll.mysql.database.azure.com", "cuestionarios", "Jano123.", "soporte");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idMateria = $_POST['idMateria'];
    $nombreCuestionario = $_POST['nombreCuestionario'];
    $cuestionarioTexto = $_POST['cuestionarioTexto'];  

    $queryInsertar = "INSERT INTO cuestionarios (NombreCuestionario, cuestionarioTexto)
                      VALUES (?, ?)";

    if ($stmt = $conexion->prepare($queryInsertar)) {
        $stmt->bind_param("ss", $nombreCuestionario, $cuestionarioTexto);
        if ($stmt->execute()) {
            $idCuestionario = $stmt->insert_id;

            $queryMateria = "UPDATE materia SET idCuestionario = ? WHERE idMateria = ?";
            if ($stmtMateria = $conexion->prepare($queryMateria)) {
                $stmtMateria->bind_param("ii", $idCuestionario, $idMateria);
                if ($stmtMateria->execute()) {
                    header("Location: PaginaPrincipal.php");
                } else {
                    echo "Error al asignar el cuestionario a la materia.";
                }
            }
        } else {
            echo "Error al guardar el cuestionario.";
        }
        $stmt->close();
    }
}
?>
