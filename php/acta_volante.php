<?php
session_start();
include("../includes/conexion.php");
conectar();



// Consulta para obtener las condiciones de la tabla mesas
$query = "SELECT DISTINCT condicion FROM inscripciones";
$result = $con->query($query);

// Inicializar variables
$carreraSeleccionada = isset($_GET['carrera']) ? $_GET['carrera'] : '';
$materiaSeleccionada = isset($_GET['materia']) ? $_GET['materia'] : '';
$condicionSeleccionada = isset($_GET['condicion']) ? $_GET['condicion'] : '';

// Almacenar las materias relacionadas con la carrera seleccionada
$materias = [];

// Obtener las carreras, excluyendo "Pendiente"
$queryCarreras = "SELECT * FROM carreras WHERE nombre != 'Pendiente'";
$resultCarreras = $con->query($queryCarreras);

// Si se seleccionó una carrera, obtener las materias correspondientes
if (!empty($carreraSeleccionada)) {
    // Consulta para obtener las materias relacionadas con la carrera seleccionada
    $queryMaterias = "SELECT idmateria, nombre FROM materias WHERE idcarrera = ?";
    $stmt = $con->prepare($queryMaterias);
    $stmt->bind_param("i", $carreraSeleccionada);
    $stmt->execute();
    $resultMaterias = $stmt->get_result();

    // Llenar el array de materias
    if ($resultMaterias->num_rows > 0) {
        while ($row = $resultMaterias->fetch_assoc()) {
            $materias[$row['idmateria']] = $row['nombre']; // Almacenar el id y nombre
        }
        $materias_mostrar = $materias;
    }
}

    // Llamar a la consulta con los valores seleccionados
    $queryExamenes = "
    SELECT e.apellido, e.nombre, e.dni
    FROM inscripciones i
    INNER JOIN estudiantes e ON i.idestudiante = e.idestudiante
    INNER JOIN materias ma ON i.idmateria = ma.idmateria
    INNER JOIN carreras c ON ma.idcarrera = c.idcarrera
    WHERE c.idcarrera = ? AND ma.idmateria = ? AND i.estado = 'Activo'";

    // Preparar la consulta
    $stmtExamenes = $con->prepare($queryExamenes);
    $stmtExamenes->bind_param("ii", $carreraSeleccionada, $materiaSeleccionada);
    $stmtExamenes->execute();
    $resultExamenes = $stmtExamenes->get_result();

    // Almacenar los estudiantes en un array
    $estudiantesExamen = [];
    if ($resultExamenes->num_rows > 0) {
    while ($row = $resultExamenes->fetch_assoc()) {
        $estudiantesExamen[] = $row;
    }
}

?>

<link rel="stylesheet" href="../css/acta_volante.css">

<div class="acta-volante">
    <h1 class="titulo">ACTA VOLANTE DE EXAMENES</h1>
    <form method="post"> 
        <header>
            <div class="left-section">
                <div>
                    <label>Establecimiento: Instituto Superior Verbo Divino</label><br><br>
                </div>
                <div class="condicion">
                    <label for="condicion">Exámenes de Alumnos:</label>
                        <?php
                        // Verificamos si hay resultados de la consulta
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($row['condicion']==$_GET['condicion'])
                                    echo $row['condicion'];
                            }
                        } else {
                            echo "No hay condiciones disponibles";
                        }
                        ?> 
                </div>
                <br>
                <div class="carrera">
                    <label for="carrera">Carrera:</label>
                        <?php
                        // Mostrar opciones de carreras
                        if ($resultCarreras->num_rows > 0) {
                            while ($row = $resultCarreras->fetch_assoc()) {
                                if ($row['idcarrera'] == $carreraSeleccionada) 
                                    echo $row['nombre'];   
                            }
                        } else {
                            echo "No hay carreras disponibles";
                        }
                        ?>
                </div>
                <br>

                <div class="materia">
                    <label for="materia">Asignatura:</label>
                        <?php
                        // Mostrar materias relacionadas con la carrera seleccionada
                        
                        if (!empty($materias_mostrar)) {
                            foreach ($materias_mostrar as $idMateria => $nombreMateria) {
                                if ($idMateria == $materiaSeleccionada) 
                                echo $nombreMateria; // Cambié $nombre a $materia
                            }
                        } else {
                            echo "No hay asignaturas disponibles para la carrera seleccionada";
                        }
                        ?>

                </div>
                <br>
            </div>
            <div class="right-section">
                <div class="header-row">
                    <div class="fecha-superior">
                        <label>Día:______</label> 
                        <label>Mes:______</label> 
                        <label>Año:______</label> 
                    </div>
                </div>
                <div class="header-row">
                    <div class="opciones-inferiores">
                        <label>Año:______</label> 
                        <label>Div:______</label>
                        <label>Turno:______</label>  
                    </div>
                </div>
            </div>
        </header>

        <table class="tabla-examen">
            <thead>
                <tr>
                    <th rowspan="2">N° de Orden</th>
                    <th rowspan="2">N° del Permiso</th>
                    <th rowspan="2">Apellido y Nombres</th>
                    <th colspan="3">Calificaciones</th>
                    <th colspan="2">N° de las Bolillas</th>
                    <th rowspan="2">Documento de Identidad</th>
                </tr>
                <tr>
                    <th>Esc.</th>
                    <th>Oral</th>
                    <th>Prom.</th>
                    <th>Esc.</th>
                    <th>Oral</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($estudiantesExamen)) {
                    $numOrden = 1; // Inicializamos el número de orden
                    foreach ($estudiantesExamen as $estudiante) {
                        echo "<tr>";
                        echo "<td>{$numOrden}</td>"; // Número de Orden
                        echo "<td></td>"; // Número del Permiso (puedes completar si lo necesitas)
                        echo "<td>{$estudiante['apellido']} {$estudiante['nombre']}</td>"; // Apellido y Nombre
                        echo "<td></td><td></td><td></td>"; // Calificaciones vacías por ahora
                        echo "<td></td><td></td>"; // Números de bolillas vacíos por ahora
                        echo "<td>{$estudiante['dni']}</td>"; // Documento de Identidad
                        echo "</tr>";
                        $numOrden++;
                    }
                } else {
                    echo "<tr><td colspan='9'>No hay estudiantes inscriptos para mostrar.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    <footer>
        <div class="footer-section">
            <div class="footer-left">
                <div class="left-group">
                    <div class="signature">
                        <label>Presidente:________________________________</label>
                        <br><br>
                    </div>
                    <div class="signature">
                        <label>Vocal:____________________________________</label>
                    </div>
                </div>
                
                <div class="footer-date">
                    <label>______</label>
                    <label> de: ___________________</label>
                    <label> del 20_________</label>
                </div>
            </div>

            <div class="footer-right">
                <div class="summary-item">
                    <label>Vocal:_____________________________</label>
                </div>
                <div class="summary-item">
                    <label>Total de alumnos:_____________________________</label>
                </div>
                <div class="summary-item">
                    <label>Aprobados:____________________________</label>
                </div>
                <div class="summary-item">
                    <label>Aplazados:____________________________</label>
                </div>
                <div class="summary-item">
                    <label>Ausentes:_____________________________</label>
                </div>
            </div>
        </div>
    </footer>
    <!-- Botón para generar el PDF -->
    <div class="generar-pdf">
        <a href="generar_pdf.php?carrera=<?= $carreraSeleccionada ?>&materia=<?= $materiaSeleccionada ?>&condicion=<?= $condicionSeleccionada ?>" target="_blank" class="btn-generar-pdf">Generar PDF</a>
    </div>
</div>
