<?php
require('libs/fpdf.php');
session_start();

$conexion = new mysqli("webll.mysql.database.azure.com", "cuestionarios", "Jano123.", "soporte");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!isset($_GET['idCuestionario']) || !is_numeric($_GET['idCuestionario'])) {
    die("Error: ID del cuestionario no proporcionado o inválido.");
}

$idCuestionario = intval($_GET['idCuestionario']);
$query = "SELECT * FROM cuestionarios WHERE idCuestionario = ?";
$stmt = $conexion->prepare($query);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $idCuestionario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Error: No se encontró el cuestionario.");
}

$cuestionario = $resultado->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(0, 10, 'Cuestionario: ' . $cuestionario['NombreCuestionario'], 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, utf8_decode($cuestionario['cuestionarioTexto']));

$pdf->Output('D', 'Cuestionario_' . $idCuestionario . '.pdf'); // Descarga directa
exit;
?>
