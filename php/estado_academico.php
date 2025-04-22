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
    SELECT idestudiante
    FROM estudiantes
    WHERE idusuario = ? LIMIT 1";

$stmt_estudiante = $con->prepare($sql_estudiante);
if ($stmt_estudiante === false) {
    die("Error en la preparación de la consulta para obtener el ID del estudiante: " . $con->error);
}

$stmt_estudiante->bind_param("i", $idusuario);
$stmt_estudiante->execute();
$result_estudiante = $stmt_estudiante->get_result();

$idestudiante = $result_estudiante->fetch_assoc()['idestudiante'] ?? null;

if (!$idestudiante) {
    die("Error: No se encontró el estudiante asociado al usuario.");
}

// Preparar la consulta para obtener las materias aprobadas (nota >= 6) agrupadas por año (aniocursado)
// Solo incluimos los años 1, 2 y 3 y filtramos por tipo de nota "Final"
$sql_materias_notas = "
    SELECT m.idmateria, m.nombre AS materia, n.valor AS nota, tn.descripcion AS tiponota, m.aniocursado
    FROM materias m 
    JOIN notas n ON m.idmateria = n.idmateria
    JOIN tiponotas tn ON n.idtiponota = tn.idtiponota
    JOIN estudiantes e ON n.idestudiante = e.idestudiante
    WHERE e.idestudiante = ? AND n.valor >= 6 AND m.aniocursado IN (1, 2, 3) AND tn.descripcion = 'Final'
    ORDER BY m.aniocursado, m.idmateria";

$stmt_materias = $con->prepare($sql_materias_notas);
if ($stmt_materias === false) {
    die("Error en la preparación de la consulta de materias y notas: " . $con->error);
}

$stmt_materias->bind_param("i", $idestudiante);
$stmt_materias->execute();
$result_materias_notas = $stmt_materias->get_result();

if (!$result_materias_notas) {
    die("Error en la consulta SQL de materias y notas: " . $con->error);
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
<link rel="stylesheet" href="css/estilo_general.css">
<section class="main-content">
    <section class="academic-status">
        <h2>Estado Académico</h2>

        <?php
        $anio_actual = null;

        if ($result_materias_notas->num_rows > 0) {
            while ($fila_materia = $result_materias_notas->fetch_assoc()) {
                // Si cambia el año cursado (aniocursado), cerramos la tabla anterior y abrimos una nueva
                if ($anio_actual !== $fila_materia['aniocursado']) {
                    if ($anio_actual !== null) {
                        echo "</tbody></table>"; // Cierra la tabla del año anterior
                    }
                    $anio_actual = $fila_materia['aniocursado'];

                    // Título para el año y encabezado de la tabla
                    $titulo_anio = convertirAnio($anio_actual); // Convertir el año a su forma textual
                    echo "<h3>{$titulo_anio}</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Nota</th>
                                <th>Tipo de Nota</th>
                            </tr>
                        </thead>
                        <tbody>";
                }

                // Imprimir la fila de la materia
                echo "<tr>
                        <td>{$fila_materia['idmateria']}</td>
                        <td>{$fila_materia['materia']}</td>
                        <td>{$fila_materia['nota']}</td>
                        <td>{$fila_materia['tiponota']}</td>
                    </tr>";
            }
            echo "</tbody></table>"; // Cerrar la última tabla
        } else {
            echo "<p>No hay materias aprobadas.</p>";
        }
        ?>
    </section>     
</section>
