<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Capturar los valores de los filtros, si están presentes
$carreraFiltro = isset($_POST['carrera']) ? $_POST['carrera'] : '';
$materiaFiltro = isset($_POST['materia']) ? $_POST['materia'] : '';
$estudianteFiltro = isset($_POST['estudiante']) ? $_POST['estudiante'] : '';

// Definir la consulta para obtener los datos
$sql = "SELECT 
            e.nombre AS nombre_estudiante,
            e.apellido AS apellido_estudiante,
            m.nombre AS nombre_materia,
            c.nombre AS nombre_carrera,
            i.fechainscripcion AS fecha_inscripcion,
            i.estado AS estado_inscripcion,
            IFNULL(n.valor, 'No registrada') AS nota_curso,
            IFNULL(em.notaexamen, 'No rendido') AS nota_examen,
            IFNULL(em.fechainscripcion, 'Sin fecha de examen') AS fecha_examen
        FROM estudiantes e
        JOIN inscripciones i ON e.idestudiante = i.idestudiante
        JOIN carreras c ON e.idcarrera = c.idcarrera
        JOIN materias m ON i.idmateria = m.idmateria
        LEFT JOIN notas n ON e.idestudiante = n.idestudiante AND m.idmateria = n.idmateria
        LEFT JOIN estudiantes_mesas em ON e.idestudiante = em.idestudiante AND m.idmateria = em.idmesa
        WHERE 
            (c.idcarrera = '$carreraFiltro' OR '$carreraFiltro' = '') AND
            (m.nombre LIKE '%$materiaFiltro%' OR '$materiaFiltro' = '') AND
            ((e.nombre LIKE '%$estudianteFiltro%' OR e.apellido LIKE '%$estudianteFiltro%') OR '$estudianteFiltro' = '')
        ORDER BY 
            e.nombre, c.nombre, m.nombre, i.fechainscripcion";

// Ejecutar la consulta
$result = $con->query($sql);

// Verificar si se deben aplicar filtros
$filtrosActivos = !empty($carreraFiltro) || !empty($materiaFiltro) || !empty($estudianteFiltro);

?>

<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">

<section class="main-content" id="historial-academico">
    <h2>Consultar Historial Académico</h2>

    <div class="botones-filtros">
        <button class="btn-small" type="button" onclick="toggleFiltros()">
            <i class="fas fa-filter"></i> Filtros
        </button>
        <?php if ($filtrosActivos): ?>
            <button class="btn-small" type="button" onclick="limpiarFiltros()">
                <i class="fas fa-times"></i> Limpiar Filtros
            </button>
        <?php endif; ?>
    </div><br>

    <div class="filtros" style="display: block;">
        <form id="form-filtros" method="POST" action="index.php?modulo=consulta_historial_academico"
            class="historial_academico_form">
            <div>
                <label for="carrera">Carrera</label>
                <select name="carrera" id="carrera">
                    <option value="">Seleccione una carrera</option>
                    <?php
                    $sql_carreras = "SELECT idcarrera, nombre FROM carreras WHERE nombre != 'Pendiente'";
                    $resultado_carreras = mysqli_query($con, $sql_carreras);
                    while ($fila = mysqli_fetch_array($resultado_carreras)) {
                        $selected = ($fila['idcarrera'] == $carreraFiltro) ? 'selected' : '';
                        echo "<option value='" . $fila['idcarrera'] . "' $selected>" . htmlspecialchars($fila['nombre']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="materia">Materia</label>
                <input type="text" name="materia" id="materia" value="<?php echo htmlspecialchars($materiaFiltro); ?>">
            </div>
            <div>
                <label for="estudiante">Estudiante</label>
                <input type="text" name="estudiante" id="estudiante"
                    value="<?php echo htmlspecialchars($estudianteFiltro); ?>">
            </div>
            <div class="ancho-completo">
                <button type="submit" value="Filtrar">Aplicar Filtros</button>
            </div>
        </form>
        <br>
    </div>

    <section class="academic-status">
        <div class="table-container">
            <table border="1">
                <thead>
                    <tr>
                        <th>Nombre Estudiante</th>
                        <th>Apellido Estudiante</th>
                        <th>Carrera</th>
                        <th>Materia</th>
                        <th>Fecha Inscripción</th>
                        <th>Estado Inscripción</th>
                        <th>Nota Curso</th>
                        <th>Nota Examen</th>
                        <th>Fecha Examen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nombre_estudiante']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['apellido_estudiante']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nombre_carrera']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nombre_materia']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fecha_inscripcion']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['estado_inscripcion']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nota_curso']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nota_examen']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fecha_examen']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No se encontraron registros</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>Error en la consulta: " . htmlspecialchars($con->error) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</section>

<script>
    function limpiarFiltros() {
        window.location.href = 'index.php?modulo=consulta_historial_academico';
    }

    function toggleFiltros() {
        var filtros = document.querySelector('.filtros');
        if (filtros.style.display === "none") {
            filtros.style.display = "block";
        } else {
            filtros.style.display = "none";
        }
    }
</script>

<?php
$con->close();
?>