<?php
// Consulta para obtener el id del estudiante a partir del idusuario
$sql_estudiantes = "SELECT e.idestudiante
                    FROM estudiantes e
                    WHERE e.idusuario = " . $_SESSION['idusuario'];
$sql_estudiantes = mysqli_query($con, $sql_estudiantes);
$sql_estudiantes = mysqli_fetch_array($sql_estudiantes);
$idestudiante = $sql_estudiantes['idestudiante'];

// Consulta para obtener las mesas de exámenes disponibles (excluyendo las ya pre-inscritas)
$sql_mesas = "SELECT m.idmesa, m.fechahora, ma.nombre AS materia
              FROM mesas m
              JOIN materias ma ON m.idmateria = ma.idmateria
              JOIN estudiantes e ON e.idestudiante = ?  -- Relacionamos con el estudiante
              WHERE NOW() >= m.fechahora
              AND ma.idcarrera = e.idcarrera          -- Coinciden las carreras del estudiante y la materia
              AND m.idmesa NOT IN (SELECT idmesa FROM inscripciones WHERE idestudiante = ?)
              ORDER BY m.fechahora;";

$stmt_mesas = $con->prepare($sql_mesas);
$stmt_mesas->bind_param("ii", $idestudiante, $idestudiante); // Pasa el idestudiante dos veces
$stmt_mesas->execute();
$resultado_mesas = $stmt_mesas->get_result();

// Manejar la pre-inscripción
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idmesa'])) {
    $idmesa = (int)$_POST['idmesa'];

    // Consulta para obtener el id de la materia a partir de la mesa seleccionada
    $sql_materia = "SELECT idmateria FROM mesas WHERE idmesa = ?";
    $stmt_materia = $con->prepare($sql_materia);
    $stmt_materia->bind_param("i", $idmesa);
    $stmt_materia->execute();
    $resultado_materia = $stmt_materia->get_result();

    if ($resultado_materia->num_rows > 0) {
        $fila_materia = $resultado_materia->fetch_assoc();
        $idmateria = $fila_materia['idmateria'];

        // Verificar que el estudiante no se haya pre-inscripto a la misma mesa
        $sql_verificar = "SELECT * FROM inscripciones WHERE idestudiante = ? AND idmesa = ?";
        $stmt_verificar = $con->prepare($sql_verificar);
        $stmt_verificar->bind_param("ii", $idestudiante, $idmesa);
        $stmt_verificar->execute();
        $resultado_verificar = $stmt_verificar->get_result();

        if ($resultado_verificar->num_rows == 0) {
            // Insertar pre-inscripción en la tabla 'inscripciones'
            $sql_inscripcion = "INSERT INTO inscripciones (idestudiante, idmesa, idmateria, fechainscripcion, estado) VALUES (?, ?, ?, NOW(), 'Pendinte')";
            $stmt_inscripcion = $con->prepare($sql_inscripcion);
            $stmt_inscripcion->bind_param("iii", $idestudiante, $idmesa, $idmateria);

            if ($stmt_inscripcion->execute()) {
                $_SESSION['mensaje'] = "Pre-inscripción realizada correctamente.";
            } else {
                $_SESSION['mensaje'] = "Error al realizar la pre-inscripción: " . $stmt_inscripcion->error;
            }
            $stmt_inscripcion->close();
        } else {
            $_SESSION['mensaje'] = "Ya estás pre-inscripto en esta mesa.";
        }
        $stmt_verificar->close();
    } else {
        $_SESSION['mensaje'] = "No se encontró la materia para la mesa seleccionada.";
    }
    $stmt_materia->close();

    header("Location: index.php?modulo=inscripcion_mesas");
    exit();
}

// Mostrar mensaje de confirmación si existe
if (isset($_SESSION['mensaje'])) {
    echo "<script>alert('" . htmlspecialchars($_SESSION['mensaje']) . "');</script>";
    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
}

// Eliminar la inscripción si se recibe un ID de mesa para eliminar
if (isset($_GET['idmesa'])) {
    $idmesa = (int)$_GET['idmesa'];

    // Eliminar la pre-inscripción de la base de datos
    $sql_eliminar = "DELETE FROM inscripciones WHERE idestudiante = ? AND idmesa = ?";
    $stmt_eliminar = $con->prepare($sql_eliminar);
    $stmt_eliminar->bind_param("ii", $idestudiante, $idmesa);

    if ($stmt_eliminar->execute()) {
        $_SESSION['mensaje'] = "Inscripción eliminada correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la inscripción.";
    }
    $stmt_eliminar->close();

    // Redirigir de vuelta a la página de inscripciones
    header("Location: index.php?modulo=inscripcion_mesas");
    exit();
}

// Consulta para obtener las materias a las que se ha pre-inscripto el estudiante, incluyendo el estado
$sql_inscripciones = "SELECT ma.nombre AS materia, m.fechahora, em.idmesa, em.estado 
                      FROM inscripciones em 
                      JOIN mesas m ON em.idmesa = m.idmesa 
                      JOIN materias ma ON em.idmateria = ma.idmateria 
                      WHERE em.idestudiante = ?";
$stmt_inscripciones = $con->prepare($sql_inscripciones);
$stmt_inscripciones->bind_param("i", $idestudiante);
$stmt_inscripciones->execute();
$resultado_inscripciones = $stmt_inscripciones->get_result();
?>

<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<section class="main-content">
    <h2>Pre-inscripción a Mesas</h2>
    <form action="index.php?modulo=inscripcion_mesas" method="POST" class="inscription-form">
        <label for="Selecciona las mesas a rendir">Selecciona las mesas a rendir:</label>
        <select name="idmesa" required>
            <option value="">Selecciona una mesa</option>
            <?php
            if (mysqli_num_rows($resultado_mesas) > 0) {
                while ($mesa = mysqli_fetch_array($resultado_mesas)) {
                    echo "<option value='" . htmlspecialchars($mesa['idmesa']) . "'>" . htmlspecialchars($mesa['materia']) . " - " . date("d/m/Y H:i", strtotime($mesa['fechahora'])) . "</option>";
                }
            } else {
                echo "<option value=''>No hay mesas disponibles</option>";
            }
            ?>
        </select>
        <button type="submit">Pre-inscribirse</button>
    </form>
</section>

<link rel="stylesheet" href="css/Styles_estado_academico.css">
<section class="main-content">
    <section class="academic-status">
        <?php if (mysqli_num_rows($resultado_inscripciones) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($inscripcion = mysqli_fetch_array($resultado_inscripciones)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($inscripcion['materia']); ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($inscripcion['fechahora'])); ?></td>
                            <td>
                                <?php
                                // Mostrar el estado de la inscripción o "Pendiente" si está vacío
                                $estado = !empty($inscripcion['estado']) ? htmlspecialchars($inscripcion['estado']) : 'Pendiente';
                                echo $estado;
                                ?>
                            </td>
                            <td>
                                <!-- Enlace para eliminar la inscripción -->
                                <a href="index.php?modulo=inscripcion_mesas&idmesa=<?php echo $inscripcion['idmesa']; ?>" 
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta inscripción?');">
                                   <i class='fas fa-times-circle ancho_boton'></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No te has pre-inscripto a ninguna mesa.</p>
        <?php endif; ?>        
    </section>
</section>
