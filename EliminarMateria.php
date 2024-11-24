<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMateria = $_POST['idMateria'];

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
