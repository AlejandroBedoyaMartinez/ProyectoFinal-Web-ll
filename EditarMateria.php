<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

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

// Obtén el idUsuario desde la sesión
$idUsuario = $_SESSION['idUsuario'];

// Procesar solicitud GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idMateria'])) {
    $idMateria = $_GET['idMateria'];

    $query = "SELECT NombreMateria FROM materia WHERE idMateria = ? AND idUsuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $idMateria, $idUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $materia = $resultado->fetch_assoc();
    } else {
        die("No se encontró la materia con el ID especificado o no pertenece al usuario actual.");
    }
}

// Procesar solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMateria = $_POST['idMateria'];
    $nombreMateria = $_POST['nombreMateria'];

    $query = "UPDATE materia SET NombreMateria = ? WHERE idMateria = ? AND idUsuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sii", $nombreMateria, $idMateria, $idUsuario);

    if ($stmt->execute()) {
        header("Location: PaginaPrincipal.php");
        exit();
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
    <link rel="stylesheet" href="styles/AgregarEditarMateria.css">
</head>
<body>
    <h1>Editar Materia</h1>
    <form action="" method="POST">
        <input type="hidden" name="idMateria" value="<?php echo isset($idMateria) ? $idMateria : ''; ?>">
        <label for="nombreMateria">Nombre de la Materia:</label>
        <input type="text" name="nombreMateria" id="nombreMateria" value="<?php echo isset($materia['NombreMateria']) ? htmlspecialchars($materia['NombreMateria']) : ''; ?>" required>
        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
