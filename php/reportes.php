<?php
// Obtener el ID de usuario desde la sesión
$idusuario = $_SESSION['idusuario'] ?? null;

// Verificar que el ID del usuario esté disponible
if (!$idusuario) {
    echo "Error: El usuario no está registrado.";
    echo "<script>window.location='login.php';</script>";
    exit();
}

// Obtener el ID del estudiante basado en el ID del usuario
$sql_estudiante = "
    SELECT idestudiante, idcarrera
    FROM estudiantes
    WHERE idusuario = ? LIMIT 1";

$stmt_estudiante = $con->prepare($sql_estudiante);
if ($stmt_estudiante === false) {
    die("Error en la preparación de la consulta para obtener el ID del estudiante: " . $con->error);
}

$stmt_estudiante->bind_param("i", $idusuario);
$stmt_estudiante->execute();
$result_estudiante = $stmt_estudiante->get_result();

$datos_estudiante = $result_estudiante->fetch_assoc();
$idestudiante = $datos_estudiante['idestudiante'] ?? null;
$idcarrera = $datos_estudiante['idcarrera'] ?? null;

if (!$idestudiante) {
    die("Error: No se encontró el estudiante asociado al usuario.");
}

// Función para consultar el plan de estudio basado en el ID de la carrera
function consultar_plan_estudio($idcarrera) {
    global $con;  // Usar la conexión global

    // Verificar la conexión a la base de datos
    if (!$con) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    // Consulta para obtener el plan de estudio de la carrera seleccionada
    $sql = "SELECT planestudiocarrera FROM carreras WHERE idcarrera = ?";

    // Preparar la consulta
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $con->error);
    }

    // Asignar el parámetro y ejecutar
    $stmt->bind_param('i', $idcarrera);
    $stmt->execute();

    // Obtener el resultado
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();

    // Verificar si se encontró el plan de estudio
    if ($fila) {
        return $fila['planestudiocarrera']; // Retornar el nombre del archivo del plan
    } else {
        return null; // Si no hay plan de estudio
    }
}

// Procesar el año seleccionado si se ha enviado el formulario
$anio_seleccionado = $_POST['anio'] ?? null;
$result_notas = null;

// Preparar la consulta para obtener las notas del estudiante por año seleccionado
if ($anio_seleccionado) {
    $stmt_notas = $con->prepare("
        SELECT m.idmateria, m.nombre AS materia, n.valor AS nota, tn.descripcion AS tiponota, m.aniocursado
        FROM materias m 
        JOIN notas n ON m.idmateria = n.idmateria
        JOIN tiponotas tn ON n.idtiponota = tn.idtiponota
        JOIN estudiantes e ON n.idestudiante = e.idestudiante
        WHERE e.idestudiante = ? AND m.aniocursado = ? AND n.valor BETWEEN 1 AND 10
        ORDER BY m.idmateria");

    $stmt_notas->bind_param("ii", $idestudiante, $anio_seleccionado);
    $stmt_notas->execute();
    $result_notas = $stmt_notas->get_result();
}

// Función para convertir el año a su forma textual
function convertirAnio($anio) {
    switch ($anio) {
        case 1: return "Primer Año";
        case 2: return "Segundo Año";
        case 3: return "Tercer Año";
        default: return "Año " . $anio; // Para años que no están especificados
    }
}

?>

<!-- Código HTML -->
<link rel="stylesheet" href="css/Styles_estado_academico.css">
<section class="main-content">
    <section class="academic-status">
        <h2>Historial Académico</h2>
        <!-- Formulario para seleccionar el año -->
        <form method="POST" action="">
            <label for="anio">Selecciona el Año:</label>
            <select name="anio" id="anio">
                <option value="">Seleccione un año</option>
                <option value="1">Primer Año</option>
                <option value="2">Segundo Año</option>
                <option value="3">Tercer Año</option>
                <!-- Agregar más años si es necesario -->
            </select>
            <input type="submit" value="Ver Notas">
        </form>

        <?php if ($result_notas && $result_notas->num_rows > 0): ?>
            <?php $anio_actual = null; ?>
            <?php while ($fila_nota = $result_notas->fetch_assoc()): ?>
                <!-- Si cambia el año cursado (aniocursado), cerramos la tabla anterior y abrimos una nueva -->
                <?php if ($anio_actual !== $fila_nota['aniocursado']): ?>
                    <?php if ($anio_actual !== null): ?>
                        </tbody></table> <!-- Cierra la tabla del año anterior -->
                    <?php endif; ?>
                    <?php $anio_actual = $fila_nota['aniocursado']; ?>

                    <!-- Título para el año y encabezado de la tabla -->
                    <h3><?php echo convertirAnio($anio_actual); ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Nota</th>
                                <th>Tipo de Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php endif; ?>

                <!-- Imprimir la fila de la nota -->
                <tr>
                    <td><?php echo $fila_nota['idmateria']; ?></td>
                    <td><?php echo $fila_nota['materia']; ?></td>
                    <td><?php echo $fila_nota['nota']; ?></td>
                    <td><?php echo $fila_nota['tiponota']; ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody></table> <!-- Cerrar la última tabla -->
        <?php elseif ($anio_seleccionado): ?>
            <!-- Mostrar una alerta usando JavaScript si no hay resultados -->
            <script>
                alert("No están disponibles las notas de este año.");
            </script>
        <?php endif; ?>

        <br><br> <!-- Doble salto de línea antes del Plan de Estudio -->
        <h2>Plan de Estudio de la Carrera</h2>
        <?php
        if ($idcarrera) {
            $archivo_plan = consultar_plan_estudio($idcarrera);

            if ($archivo_plan) {
                // Verificar si el archivo existe en la carpeta 'documentos'
                $ruta_archivo_plan = "documentos/planes-estudio/" . htmlspecialchars($archivo_plan);

                if (file_exists($ruta_archivo_plan)) {
                    echo "<div id='pdf-container' style='display: block;'>";
                    // Botón para abrir el PDF en una nueva ventana
                    echo "<button class='btn-small' onclick='abrirEnNuevaVentana(\"" . $ruta_archivo_plan . "\"); return false;'>
                            <i class='fas fa-external-link-alt'></i> Abrir en nueva ventana
                          </button>";

                    // Botón para cerrar el PDF
                    echo "<button class='btn-small' onclick='cerrarPdf(); return false;'>
                            <i class='fas fa-times'></i> Cerrar PDF
                          </button>";

                    // Mostrar el PDF embebido
                    echo "<embed src='" . $ruta_archivo_plan . "' width='1270' height='700' type='application/pdf' class='pdf-viewer'>";
                    echo "</div>";
                } else {
                    echo "<p>El plan de estudio no está disponible en este momento.</p>";
                }
            } else {
                echo "<p>No se encontró un plan de estudio para la carrera seleccionada.</p>";
            }
        }
        ?>
    </section>     
</section>

<!-- JavaScript para cerrar el visor de PDF -->
<script>
// Función para abrir el PDF en una nueva ventana
function abrirEnNuevaVentana(url) {
    window.open(url, '_blank');
}

// Función para cerrar el visor de PDF
function cerrarPdf() {
    document.getElementById('pdf-container').style.display = 'none'; // Ocultar el visor del PDF
}
</script>