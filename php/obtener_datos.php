<?php
session_start();
include("../includes/conexion.php");
conectar();

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$response = [];

// Consulta para obtener condiciones
if ($type === 'condiciones') {
    $query = "SELECT DISTINCT condicion FROM inscripciones";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        $response[] = $row['condicion'];
    }
}

// Consulta para obtener carreras excluyendo "Pendiente"
elseif ($type === 'carreras') {
    $query = "SELECT idcarrera, nombre FROM carreras WHERE nombre != 'Pendiente'";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

// Consulta para obtener materias basadas en la carrera seleccionada
elseif ($type === 'materias' && isset($_GET['idcarrera'])) {
    $idcarrera = intval($_GET['idcarrera']);
    $query = "SELECT idmateria, nombre FROM materias WHERE idcarrera = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $idcarrera);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

echo json_encode($response);
?>
