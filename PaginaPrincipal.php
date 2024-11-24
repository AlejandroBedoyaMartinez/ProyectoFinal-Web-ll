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
mysqli_ssl_set($conexion, null, null, __DIR__ . "/certs/ca-cert.pem", null, null); // Ajusta la ruta si es necesario
mysqli_real_connect($conexion, $servidor, $usuario, $password, $baseDatos, 3306, null, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}

$queryUsuario = "SELECT idUsuario FROM usuarios WHERE user = ?";
$stmtUsuario = $conexion->prepare($queryUsuario);
$stmtUsuario->bind_param("s", $_SESSION['user']); // Pasar el nombre de usuario almacenado en la sesión
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();

if ($resultUsuario && $resultUsuario->num_rows > 0) {
    $row = $resultUsuario->fetch_assoc();
    $idUsuario = $row['idUsuario']; // ID del usuario logueado
} else {
    die("No se encontró el usuario en la base de datos.");
}

$queryMaterias = "SELECT idMateria, NombreMateria FROM Materia WHERE idUsuario = ?";
$stmtMaterias = $conexion->prepare($queryMaterias);
$stmtMaterias->bind_param("i", $idUsuario); // 'i' indica que el parámetro es un entero
$stmtMaterias->execute();
$resultadoMaterias = $stmtMaterias->get_result();


$stmtUsuario->close();
$stmtMaterias->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link rel="stylesheet" href="styles/principal.css">
</head>
<body>
    <div id="principal">
        <h1>Crear Cuestionario</h1>
        <form action="CrearCuestionario.php" method="GET">
            <button type="submit">Crear Nuevo Cuestionario</button>
        </form>

        <h1>Materias</h1>
        <form action="AgregarMateria.php" method="GET">
            <button type="submit">Agregar Nueva Materia</button>
        </form>
        <br>
        <br>
        <table id="tablaMaterias">
            <thead>
                <tr>
                    <th>Nombre de la Materia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultadoMaterias && $resultadoMaterias->num_rows > 0): ?>
                    <?php while ($fila = $resultadoMaterias->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $fila['NombreMateria']; ?></td>
                            <td>
                                <form action="EditarMateria.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="idMateria" value="<?php echo $fila['idMateria']; ?>">
                                    <button type="submit">Editar</button>
                                </form>
                                <form action="EliminarMateria.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="idMateria" value="<?php echo $fila['idMateria']; ?>">
                                    <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar esta materia?');">Eliminar</button>
                                </form>
                                <form action="VerCuestionarios.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="idMateria" value="<?php echo $fila['idMateria']; ?>">
                                    <button type="submit">Cuestionarios</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay materias registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
