<?php
session_start();

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

if (isset($_POST['idCuestionario'])) {
    $idCuestionario = $_POST['idCuestionario'];
    $idUsuario = $_SESSION['idUsuario'];  

    $queryVerificar = "SELECT 1 FROM materia m 
                       WHERE m.idCuestionario = ? AND m.idUsuario = ?";
    
    if ($stmtVerificar = $conexion->prepare($queryVerificar)) {
        $stmtVerificar->bind_param("ii", $idCuestionario, $idUsuario);
        $stmtVerificar->execute();
        $stmtVerificar->store_result();

        if ($stmtVerificar->num_rows > 0) {
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
                echo "Error al preparar la consulta de eliminación.";
            }
        } else {
            echo "No tienes permisos para eliminar este cuestionario.";
        }

        $stmtVerificar->close();
    } else {
        echo "Error al verificar el cuestionario.";
    }
} else {
    echo "No se proporcionó el ID del cuestionario.";
}
?>
