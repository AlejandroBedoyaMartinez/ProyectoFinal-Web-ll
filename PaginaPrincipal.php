<?php
session_start();

$conexion = new mysqli("webll.mysql.database.azure.com", "cuestionarios", "Jano123.", "soporte");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$queryMaterias = "SELECT idMateria, NombreMateria FROM materia";
$resultadoMaterias = $conexion->query($queryMaterias);

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
