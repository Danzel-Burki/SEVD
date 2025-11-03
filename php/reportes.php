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
    global $con;

    if (!$con) {
        die("Error en la conexión: " . mysqli_connect_error());
    }

    $sql = "SELECT planestudiocarrera FROM carreras WHERE idcarrera = ?";
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $con->error);
    }

    $stmt->bind_param('i', $idcarrera);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();

    if ($fila) {
        return $fila['planestudiocarrera'];
    } else {
        return null;
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
        WHERE n.idestudiante = ? AND m.aniocursado = ?
        ORDER BY m.idmateria");

    if ($stmt_notas === false) {
        die("Error en la preparación de la consulta de notas: " . $con->error);
    }

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
        default: return "Año " . $anio;
    }
}

?>

<!-- Código HTML -->
 <link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<link rel="stylesheet" href="css/estilo_general.css">
<section class="main-content">
    <section class="academic-status">
        <h2>Historial Académico</h2>
        
        <!-- Formulario para seleccionar el año -->
        <form method="POST" class="inscription-form" action="">
            <label for="anio">Selecciona el Año:</label>
            <select name="anio" id="anio" required>
                <option value="">Seleccione un año</option>
                <option value="1" <?php echo ($anio_seleccionado == 1) ? 'selected' : ''; ?>>Primer Año</option>
                <option value="2" <?php echo ($anio_seleccionado == 2) ? 'selected' : ''; ?>>Segundo Año</option>
                <option value="3" <?php echo ($anio_seleccionado == 3) ? 'selected' : ''; ?>>Tercer Año</option>
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
                    <table border="1" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <thead>
                            <tr>
                                <th>ID Materia</th>
                                <th>Nombre</th>
                                <th>Nota</th>
                                <th>Tipo de Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php endif; ?>

                <!-- Imprimir la fila de la nota -->
                <tr>
                    <td style="padding: 8px;"><?php echo $fila_nota['idmateria']; ?></td>
                    <td style="padding: 8px;"><?php echo $fila_nota['materia']; ?></td>
                    <td style="padding: 8px;"><?php echo $fila_nota['nota']; ?></td>
                    <td style="padding: 8px;"><?php echo $fila_nota['tiponota']; ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody></table> <!-- Cerrar la última tabla -->
            
        <?php elseif ($anio_seleccionado): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin-top: 15px;">
                <strong>No se encontraron notas para el año <?php echo convertirAnio($anio_seleccionado); ?>.</strong>
            </div>
        <?php endif; ?>

        <br><br>
        <h2>Plan de Estudio de la Carrera</h2>
        <?php
        if ($idcarrera) {
            $archivo_plan = consultar_plan_estudio($idcarrera);

            if ($archivo_plan) {
                $ruta_archivo_plan = "documentos/planes-estudio/" . htmlspecialchars($archivo_plan);

                if (file_exists($ruta_archivo_plan)) {
                    echo "<div id='pdf-container' style='display: block;'>";
                    echo "<button class='btn-small' onclick='abrirEnNuevaVentana(\"" . $ruta_archivo_plan . "\"); return false;'>
                            <i class='fas fa-external-link-alt'></i> Abrir en nueva ventana
                          </button>";

                    echo "<button class='btn-small' onclick='cerrarPdf(); return false;'>
                            <i class='fas fa-times'></i> Cerrar PDF
                          </button>";

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

<script>
function abrirEnNuevaVentana(url) {
    window.open(url, '_blank');
}

function cerrarPdf() {
    document.getElementById('pdf-container').style.display = 'none';
}
</script>