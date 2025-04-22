<link rel="stylesheet" href="css/Styles_index.css">
<div class="contenedor_padre">
    <div class="contenedor-consultas">
        <div class="titulo_consulta">
            <h3>Seleccione una Consulta</h3>
        </div>
        <div class="">
            <ul>
                <li>
                    <a href="#historial-academico">Consultar Historial Académico de Estudiantes</a>
                </li>
                <li>
                    <a href="#consulta_plan">Consultar Planes de Estudio de una Carrera</a>
                </li>
                <li>
                    <a href="#acta_volante">Solicitar Acta Volante</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="contenedor_contenido">
        <link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">

        <!-- secciones de y formularios-->
        <section class="main-content" id="consulta_plan">
            <!--formulario para mostrar el plan de la materia seleccionada-->
            <h2>Consultar Plan de Estudio</h2>
            <div class="form-group">
                <form action="index.php?modulo=consultas" method="POST" class="inscription-form">
                    <input type="hidden" name="accion" value="mostrar_plan">
                    <label for="carrera">Carreras</label>
                    <select name="carrera" id="carrera" required>
                        <option value="">Seleccione una carrera</option>
                        <?php  // Consulta para listar las carreras
                        $sql_carreras = "SELECT idcarrera, nombre FROM carreras";
                        $resultado_carreras = mysqli_query($con, $sql_carreras);
                        while ($fila = mysqli_fetch_array($resultado_carreras)) {
                            echo "<option value='" . $fila['idcarrera'] . "'>" . $fila['nombre'] . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Cargar plan de estudio</button><br>
                </form>
            </div>
            <!--formulario para mostrar el plan de la materia seleccionada-->
            <?php
            // logica para mostrar el plan de estudio 
            if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["accion"] == "mostrar_plan") {
                $archivo = consultar_plan_estudio($_POST['carrera']);

                if ($archivo) {
                    // Verificar si el archivo existe en la carpeta 'documentos'
                    $ruta_archivo = "documentos/" . htmlspecialchars($archivo);

                    if (file_exists($ruta_archivo)) { //hola tengo hambre 
                        ?>
                        <div class="open">
                            <?php
                            echo "<a  href='" . $ruta_archivo . "' target='_blank'>Abrir en una nueva ventana</a>";

                            ?>

                        </div>
                        <?php
                        
                        echo "<embed src='" . $ruta_archivo . "' width='1270' height='700' type='application/pdf'>";
                    } else {
                        echo "<p>El archivo no se encuentra disponible.</p>";
                    }
                } else {
                    echo "<h2>No se encontró el plan de estudio.</h2>";
                }
            }
            ?>
        </section>
        <!-- seccion historial academico-->
        <section class="main-content" id="historial-academico">
            <?php
            // Inicializar variables de filtros  
            $carreraFiltro = isset($_GET['carrera']) ? $_GET['carrera'] : '';
            $materiaFiltro = isset($_GET['materia']) ? $_GET['materia'] : '';
            $estudianteFiltro = isset($_GET['estudiante']) ? $_GET['estudiante'] : '';
            $result = (consultar_historial_academico($carreraFiltro, $materiaFiltro, $estudianteFiltro));
            ?>
            <h1>Historial Académico</h1>
            <form method="GET" action="index.php#historial-academico">
                <input type="hidden" name="accion" value="mostrar_historial">
                <input type="hidden" name="modulo" value="consultas">
                <input type="hidden" name="filtro" value="filtrar_historial">

                <label for="carrera">Carrera:</label>
                <select name="carrera" id="carrera">
                    <option value="">Seleccione una carrera</option>
                    <?php
                    // Consulta para obtener las carreras
                    $sql_carreras = "SELECT idcarrera, nombre FROM carreras";
                    $resultado_carreras = mysqli_query($con, $sql_carreras);
                    while ($fila = mysqli_fetch_array($resultado_carreras)) {
                        $selected = ($fila['idcarrera'] == $carreraFiltro) ? 'selected' : '';
                        echo "<option value='" . $fila['idcarrera'] . "' $selected>" . htmlspecialchars($fila['nombre']) . "</option>";
                    }
                    ?>
                </select>

                <label for="materia">Materia:</label>
                <input type="text" name="materia" id="materia" value="<?php echo htmlspecialchars($materiaFiltro); ?>">

                <label for="estudiante">Estudiante:</label>
                <input type="text" name="estudiante" id="estudiante"
                    value="<?php echo htmlspecialchars($estudianteFiltro); ?>">

                <input type="submit" value="Filtrar">
                <button class="filtro" type="button" onclick="limpiarFiltros()">
                    <i class="fa-solid fa-filter-circle-xmark"></i> Limpiar filtros
                </button>


            </form>

            <script>
                function limpiarFiltros() {
                    // Redirigir a la misma página sin parámetros de consulta
                    window.location.href = 'index.php?modulo=consultas#historial-academico';
                }
            </script>

            <link rel="stylesheet" href="css/estilo_general.css">
            <section class="academic-status">
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
            </section>
        </section>
        <!-- seccion acta volante -->
        <section class="main-content" id="acta_volante">
            <h2>Acta volante</h2>
         

        </section>

        <?php
        // logica para manejar que formulario manda datos 
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            switch ($_POST["accion"]) {
                case 'mostrar_plan':
                    consultar_plan_estudio($_POST['carrera']);
                    break;
                case 'mostrar_historial':
                    consultar_historial_academico($_POST['carrera'], $_POST['materias'], $_POST['estudiantes']);
                    break;
            }
        }
        //funciones //
        function consultar_plan_estudio($datos)
        {
            $con = mysqli_connect('localhost', 'root', '', 'sevd');
            // Consulta para obtener el nombre del archivo del plan de estudio
            $sql = "SELECT planestudiocarrera 
        FROM carreras 
        WHERE idcarrera = $datos";

            $resultado = $con->query($sql);

            if ($resultado && $fila = $resultado->fetch_assoc()) {
                $plan_estudio = $fila['planestudiocarrera']; // Nombre del archivo con extensión PDF
        
                // Devolver el nombre del archivo del plan de estudio
                return $plan_estudio;
            } else {
                return null;
            }
        }
        function consultar_historial_academico($carreraFiltro, $materiaFiltro, $estudianteFiltro)
        {
            $con = mysqli_connect('localhost', 'root', '', 'sevd');
            // Consulta SQL
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

            $resultado = $con->query($sql);
            return $resultado;
        }
        ?>

    </div>

</div>