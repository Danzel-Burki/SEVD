<?php
// Verificación si se ha hecho una solicitud de "aprobar"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idinscripcion'])) {
    $idinscripcion = $_POST['idinscripcion'];

    // Consulta para actualizar el estado de la inscripción a "Activo"
    $query = "UPDATE inscripciones SET estado = 'Activo' WHERE idinscripcion = ?";
    
    // Preparar y ejecutar la consulta
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param('i', $idinscripcion);
        $stmt->execute();
        $stmt->close();
        
        // Redireccionar para evitar reenvíos al actualizar
        header("Location: index.php?modulo=administracion_mesas");
        exit();
    }
}

// Consulta para obtener los datos necesarios de las inscripciones pendientes
$query = "
    SELECT 
        inscripciones.idinscripcion,
        inscripciones.estado,
        inscripciones.fechainscripcion,
        CONCAT(estudiantes.nombre, ' ', estudiantes.apellido) AS Estudiante,
        materias.nombre AS Materia,
        inscripciones.condicion
    FROM 
        inscripciones
    INNER JOIN 
        estudiantes ON inscripciones.idestudiante = estudiantes.idestudiante
    INNER JOIN 
        materias ON inscripciones.idmateria = materias.idmateria
    WHERE 
        inscripciones.estado = 'Pendiente'
";
?>

<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
    <section class="main-content">
        <section class="academic-status">
            <h2>Administrar Mesas</h2>
            <?php
            $result = $con->query($query);
                    // Verificación de resultados
                    if ($result->num_rows > 0) {
                        echo "<table border='1'>
                                <tr>
                                    <th>Estado</th>
                                    <th>Fecha Inscripción</th>
                                    <th>Estudiante</th>
                                    <th>Materia</th>
                                    <th>Condición</th>
                                    <th>Acciones</th>
                                </tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['estado'] . "</td>
                                    <td>" . $row['fechainscripcion'] . "</td>
                                    <td>" . $row['Estudiante'] . "</td>
                                    <td>" . $row['Materia'] . "</td>
                                    <td>" . $row['condicion'] . "</td>
                                    <td>
                                        <form method='post' action='index.php?modulo=administracion_mesas' style='display:inline;'>
                                            <input type='hidden' name='idinscripcion' value='" . $row['idinscripcion'] . "'>
                                            <button type='submit'>Aprobar</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No hay inscripciones pendientes.";
                    }

                    // Cierre de la conexión
                    $con->close();
            ?>
        </section>
    </section>    
