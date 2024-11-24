<?php
session_start(); // Asegúrate de iniciar la sesión aquí.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMateria = $_POST['idMateria'];

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

    $idUsuario = $_SESSION['idUsuario'];

    $queryVerificar = "SELECT * FROM materia WHERE idMateria = ? AND idUsuario = ?";
    $stmtVerificar = $conexion->prepare($queryVerificar);
    $stmtVerificar->bind_param("ii", $idMateria, $idUsuario);
    $stmtVerificar->execute();
    $resultado = $stmtVerificar->get_result();

    if ($resultado->num_rows === 0) {
        echo "No se encontró una materia con idMateria: $idMateria y idUsuario: $idUsuario.";
        exit;
    }

    $query = "DELETE FROM materia WHERE idMateria = ? AND idUsuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $idMateria, $idUsuario); 

    if ($stmt->execute()) {
        header("Location: PaginaPrincipal.php");
        exit;
    } else {
        echo "Error al eliminar la materia: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
