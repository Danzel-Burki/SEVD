<?php
// Verificar si el estudiante está logueado
if (!isset($_SESSION['idusuario'])) {
    die("Debes iniciar sesión para ver las mesas.");
}

$idusuario = $_SESSION['idusuario'];

// Obtener el ID del estudiante a partir del ID de usuario
$sql_estudiantes = "SELECT e.idestudiante FROM estudiantes e WHERE e.idusuario = ?";
$stmt_estudiantes = $con->prepare($sql_estudiantes);
$stmt_estudiantes->bind_param("i", $idusuario); // Suponiendo que $idusuario es el valor que tienes disponible
$stmt_estudiantes->execute();
$resultado_estudiantes = $stmt_estudiantes->get_result();
$datos_estudiante = $resultado_estudiantes->fetch_assoc();
$idestudiante = $datos_estudiante['idestudiante'];
$stmt_estudiantes->close();

// Consultar mesas de examen disponibles
$sql_mesas = "SELECT m.idmesa, m.fechahora, ma.nombre AS materia 
              FROM mesas m 
              JOIN materias ma ON m.idmateria = ma.idmateria 
              WHERE m.fechahora >= NOW()
              AND ma.idcarrera = (SELECT idcarrera FROM estudiantes WHERE idestudiante = ?) 
              AND m.idmesa NOT IN (SELECT idmesa FROM inscripciones WHERE idestudiante = ?) 
              ORDER BY m.fechahora";

$stmt_mesas = $con->prepare($sql_mesas);
$stmt_mesas->bind_param("ii", $idestudiante, $idestudiante);
$stmt_mesas->execute();
$resultado_mesas = $stmt_mesas->get_result();

// Gestión de la pre-inscripción
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idmesa'])) {
    $idmesa = (int)$_POST['idmesa'];

    // Obtener la materia para la mesa seleccionada
    $sql_materia = "SELECT idmateria FROM mesas WHERE idmesa = ?";
    $stmt_materia = $con->prepare($sql_materia);
    $stmt_materia->bind_param("i", $idmesa);
    $stmt_materia->execute();
    $resultado_materia = $stmt_materia->get_result();

    if ($resultado_materia->num_rows > 0) {
        $fila_materia = $resultado_materia->fetch_assoc();
        $idmateria = $fila_materia['idmateria'];

        // Verificar si el estudiante ya está inscrito
        $sql_verificar = "SELECT * FROM inscripciones WHERE idestudiante = ? AND idmesa = ?";
        $stmt_verificar = $con->prepare($sql_verificar);
        $stmt_verificar->bind_param("ii", $idestudiante, $idmesa);
        $stmt_verificar->execute();
        $resultado_verificar = $stmt_verificar->get_result();

        if ($resultado_verificar->num_rows == 0) {
            // Realizar la inscripción
            $sql_inscripcion = "INSERT INTO inscripciones (idestudiante, idmesa, idmateria, fechainscripcion, estado) 
                                VALUES (?, ?, ?, NOW(), 'Pendiente')";
            $stmt_inscripcion = $con->prepare($sql_inscripcion);
            $stmt_inscripcion->bind_param("iii", $idestudiante, $idmesa, $idmateria);

            if ($stmt_inscripcion->execute()) {
                echo "<script>alert('Pre-inscripción realizada correctamente.');</script>";
            } else {
                echo "<script>alert('Error al realizar la pre-inscripción.');</script>";
            }
            $stmt_inscripcion->close();
        } else {
            echo "<script>alert('Ya estás pre-inscripto en esta mesa.');</script>";
        }
        $stmt_verificar->close();
    } else {
        echo "<script>alert('No se encontró la materia para la mesa seleccionada.');</script>";
    }
    $stmt_materia->close();
}

// Eliminar inscripción si se recibe un ID
if (isset($_GET['idmesa'])) {
    $idmesa = (int)$_GET['idmesa'];

    $sql_eliminar = "DELETE FROM inscripciones WHERE idestudiante = ? AND idmesa = ?";
    $stmt_eliminar = $con->prepare($sql_eliminar);
    $stmt_eliminar->bind_param("ii", $idestudiante, $idmesa);

    if ($stmt_eliminar->execute()) {
        echo "<script>alert('Inscripción eliminada correctamente.');</script>";
    } else {
        echo "<script>alert('Error al eliminar la inscripción.');</script>";
    }
    $stmt_eliminar->close();
}

// Consultar inscripciones del estudiante
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
            if ($resultado_mesas->num_rows > 0) {
                while ($mesa = $resultado_mesas->fetch_assoc()) {
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

<link rel="stylesheet" href="css/estilo_general.css">
<section class="main-content">
    <section class="academic-status">
        <?php if ($resultado_inscripciones->num_rows > 0): ?>
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
                    <?php while ($inscripcion = $resultado_inscripciones->fetch_assoc()): ?>
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