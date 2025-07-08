<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idusuario'])) {
    header("Location: index.php");
    exit;
}



$idusuario = $_SESSION['idusuario'];

$stmt = $con->prepare("SELECT idasistencia, DATE(fechahora) AS fecha, TIME(fechahora) AS hora, estado, observacion FROM asistencias WHERE idusuario = ? ORDER BY fechahora ASC");
$stmt->bind_param("i", $idusuario);
$stmt->execute();
$resultado = $stmt->get_result();

$asistencias = [];

while ($fila = $resultado->fetch_assoc()) {
    $fecha = $fila['fecha'];
    if (!isset($asistencias[$fecha])) {
        $asistencias[$fecha] = [];
    }
    $asistencias[$fecha][] = [
        'hora' => $fila['hora'],
        'estado' => $fila['estado'],
        'observacion' => $fila['observacion']
    ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario de Asistencias</title>
    <link rel="stylesheet" href="css/estilo_general.css">
    <link rel="stylesheet" href="css/estilo_calendario.css">
    <script>
        const asistencias = <?php echo json_encode($asistencias); ?>;
    </script>
    <script src="js/calendario.js" defer></script>
</head>
<body>
    <section class="main-content calendario-section">
        <h2 class="titulo-historial">Historial de Asistencias</h2>
        <div class="contenedor-calendario">
            <div id="calendario">
                <div class="encabezado-mes">
                    <button id="mes-anterior">&#10094;</button>
                    <h3 id="mes-anio"></h3>
                    <button id="mes-siguiente">&#10095;</button>
                </div>
                <div class="fila-dias">
                    <div class="dia-cabecera">D</div>
                    <div class="dia-cabecera">L</div>
                    <div class="dia-cabecera">M</div>
                    <div class="dia-cabecera">M</div>
                    <div class="dia-cabecera">J</div>
                    <div class="dia-cabecera">V</div>
                    <div class="dia-cabecera">S</div>
                </div>
                <div id="dias-calendario"></div>
            </div>

            <div id="detalles-dia">
                <h4>Detalles del día</h4>
                <div id="detalle-resumen">
                    <p><strong>Hora de entrada:</strong> ---</p>
                    <p><strong>Hora de salida:</strong> ---</p>
                    <p><strong>Estado:</strong> ---</p>
                    <p><strong>Observación:</strong> ---</p>
                    <button id="btn-ver-mas" class="btn-ver-mas">Más</button>
                </div>
                <div id="detalle-extendido" style="display: none;">
                    <h4>Detalles extendidos</h4>
                    <div id="info-extendida"></div>
                    <button id="btn-ver-menos" class="btn-ver-menos">Menos</button>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
