<?php
session_start();

$conexion = new mysqli("localhost", "root", "root", "soporte");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!isset($_GET['idMateria']) || !is_numeric($_GET['idMateria'])) {
    die("Error: idMateria no proporcionado o inválido.");
}

$idMateria = intval($_GET['idMateria']); 

$queryCuestionarios = "
    SELECT cuestionarios.idCuestionario, cuestionarios.NombreCuestionario
    FROM cuestionarios
    INNER JOIN materia ON materia.idCuestionario = cuestionarios.idCuestionario
    WHERE materia.idMateria = ?";

$stmt = $conexion->prepare($queryCuestionarios);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $idMateria);
$stmt->execute();

$resultadoCuestionarios = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionarios</title>
    <link rel="stylesheet" href="styles/verCuestionario.css">
</head>
<body>
    <h1>Cuestionarios</h1>
    <form action="CrearCuestionario.php" method="GET">
        <button type="submit">Crear Nuevo Cuestionario</button>
        <br><br>
    </form>
    <table id="tablaCuestionarios">
        <thead>
            <tr>
                <th>Nombre del Cuestionario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultadoCuestionarios && $resultadoCuestionarios->num_rows > 0): ?>
                <?php while ($fila = $resultadoCuestionarios->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila['NombreCuestionario']); ?></td>
                        <td>
                            <form action="EditarCuestionario.php" method="GET" style="display:inline;">
                                <input type="hidden" name="idCuestionario" value="<?php echo $fila['idCuestionario']; ?>">
                                <button type="submit">Editar</button>
                            </form>
                            <form action="EliminarCuestionario.php" method="POST" style="display:inline;">
                                <input type="hidden" name="idCuestionario" value="<?php echo $fila['idCuestionario']; ?>">
                                <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar este cuestionario?');">Eliminar</button>
                            </form>
                            <form action="GenerarPDF.php" method="GET" style="display:inline;">
                                <input type="hidden" name="idCuestionario" value="<?php echo $fila['idCuestionario']; ?>">
                                <button type="submit">Descargar PDF</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No hay cuestionarios registrados para esta materia.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>