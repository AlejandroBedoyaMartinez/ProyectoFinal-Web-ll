<?php
session_start();

$conexion = new mysqli("localhost", "root", "root", "soporte");
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
    <title>Crear Cuestionario</title>
    <link rel="stylesheet" href="styles/crearCuestionario.css">
</head>
<body>
    <h1>Crear Cuestionario</h1>
    
    <div class="controles">
        <button onclick="agregarPregunta()">Agregar Pregunta</button>
    </div>
    <form action="guardar_cuestionario.php" method="POST" id="formularioCuestionario">
        <label for="materia">Seleccionar Materia:</label>
        <select name="idMateria" id="materia" required>
            <option value="">Seleccione una materia</option>
            <?php foreach ($resultadoMaterias as $materia): ?>
                <option value="<?php echo $materia['idMateria']; ?>">
                    <?php echo $materia['NombreMateria']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="nombreCuestionario">Nombre del Cuestionario:</label>
        <input type="text" name="nombreCuestionario" id="nombreCuestionario" required>

        <input type="hidden" name="cuestionarioTexto" id="cuestionarioTexto">

        <div id="contenedorPreguntas"></div>
        <button type="submit">Guardar Cuestionario</button>
    </form>

    <script src="JS/crearCuestionario.js"></script>
</body>
</html>
