<?php
session_start();

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

if (isset($_POST['idCuestionario']) && isset($_POST['nombreCuestionario']) && isset($_POST['cuestionarioTexto'])) {
    $idCuestionario = $_POST['idCuestionario'];
    $nombreCuestionario = $_POST['nombreCuestionario'];
    $cuestionarioTexto = $_POST['cuestionarioTexto'];
    $idUsuario = $_SESSION['idUsuario'];  
    
    $queryVerificar = "SELECT 1 FROM cuestionarios c 
                       JOIN materia m ON c.idCuestionario = m.idCuestionario 
                       WHERE c.idCuestionario = ? AND m.idUsuario = ?";
    
    if ($stmtVerificar = $conexion->prepare($queryVerificar)) {
        $stmtVerificar->bind_param("ii", $idCuestionario, $idUsuario);
        $stmtVerificar->execute();
        $stmtVerificar->store_result();

        if ($stmtVerificar->num_rows > 0) {
            
            $queryActualizar = "UPDATE cuestionarios SET NombreCuestionario = ?, cuestionarioTexto = ? WHERE idCuestionario = ?";
            
            if ($stmt = $conexion->prepare($queryActualizar)) {
                $stmt->bind_param("ssi", $nombreCuestionario, $cuestionarioTexto, $idCuestionario);
                
                if ($stmt->execute()) {
                    header("Location: PaginaPrincipal.php?mensaje=El cuestionario fue actualizado correctamente.");
                    exit;
                } else {
                    echo "Error al actualizar el cuestionario.";
                }
                $stmt->close();
            } else {
                echo "Error al preparar la consulta de actualización.";
            }
        } else {
            echo "No tienes permisos para editar este cuestionario.";
        }

        $stmtVerificar->close();
    } else {
        echo "Error al verificar el cuestionario.";
    }
} else {
    echo "No se recibieron todos los datos necesarios para actualizar el cuestionario.";
}
?>
