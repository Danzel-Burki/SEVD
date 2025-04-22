<?php
// Verificar si se ha solicitado la eliminación de un estudiante
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "UPDATE estudiantes SET eliminado = 1 WHERE idestudiante = ?";
    $stmt_delete = $con->prepare($sql_delete);

    if ($stmt_delete === false) {
        die("Error en la preparación de la consulta: " . $con->error);
    }

    $stmt_delete->bind_param("i", $delete_id);

    if ($stmt_delete->execute()) {
        $_SESSION['mensaje'] = "Estudiante eliminado correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el estudiante: " . $stmt_delete->error;
    }

    $stmt_delete->close();
    header("Location: index.php?modulo=amb_estudiantes");
    exit();
}

// Guardar o actualizar estudiante
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fechanacimiento = $_POST['fechanacimiento'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $dni = $_POST['dni'];
    $idcarrera = $_POST['idcarrera'];

    // Verificar si es una actualización o una inserción
    if (isset($_GET['id'])) {
        // Actualizar estudiante existente
        $sql = "UPDATE estudiantes SET nombre = ?, apellido = ?, fechanacimiento = ?, direccion = ?, telefono = ?, dni = ?, idcarrera = ? WHERE idestudiante = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssii", $nombre, $apellido, $fechanacimiento, $direccion, $telefono, $dni, $idcarrera, $_GET['id']);
        $_SESSION['mensaje'] = "Estudiante actualizado correctamente.";
    } else {
        // Insertar nuevo estudiante
        $sql = "INSERT INTO estudiantes (nombre, apellido, fechanacimiento, direccion, telefono, dni, idcarrera) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssi", $nombre, $apellido, $fechanacimiento, $direccion, $telefono, $dni, $idcarrera);
        $_SESSION['mensaje'] = "Estudiante insertado correctamente.";
    }

    if ($stmt->execute()) {
        // Redirigir para evitar reenvío del formulario
        header("Location: index.php?modulo=amb_estudiantes");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
}

// Mostrar formulario para editar estudiante existente
if (isset($_GET['id'])) {
    $sql = "SELECT * FROM estudiantes WHERE idestudiante = ".$_GET['id'];
    $sql = mysqli_query($con, $sql);
    $r = mysqli_fetch_array($sql);
}
?>

<!-- Mostrar mensaje de confirmación si existe -->
<?php
if (isset($_SESSION['mensaje'])) {
    echo "<script> alert('" . $_SESSION['mensaje'] . "');</script>";
    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
}
?>

<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">

<section class="main-content">
    <h2>Formulario de Inserción de Estudiantes</h2>
    
    <form method="POST" action="index.php?modulo=amb_estudiantes<?php echo isset($_GET['id']) ? '&id=' . $_GET['id'] : ''; ?>">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?php echo isset($r['nombre']) ? $r['nombre'] : ''; ?>" required><br><br>

        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" value="<?php echo isset($r['apellido']) ? $r['apellido'] : ''; ?>" required><br><br>

        <label for="fechanacimiento">Fecha de Nacimiento:</label><br>
        <input type="date" id="fechanacimiento" name="fechanacimiento" value="<?php echo isset($r['fechanacimiento']) ? $r['fechanacimiento'] : ''; ?>" required><br><br>

        <label for="direccion">Dirección:</label><br>
        <input type="text" id="direccion" name="direccion" value="<?php echo isset($r['direccion']) ? $r['direccion'] : ''; ?>" required><br><br>

        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" value="<?php echo isset($r['telefono']) ? $r['telefono'] : ''; ?>" required><br><br>

        <label for="dni">D.N.I:</label><br>
        <input type="text" id="dni" name="dni" value="<?php echo isset($r['dni']) ? $r['dni'] : ''; ?>" required><br><br>

        <label for="idcarrera">Carrera:</label><br>
        <select id="idcarrera" name="idcarrera" required>
            <!-- Aquí debes agregar un bucle para obtener las carreras disponibles -->
            <option value="">Seleccione una carrera</option>
            <?php
            $sql_carreras = "SELECT idcarrera, nombre FROM carreras";
            $resultado_carreras = mysqli_query($con, $sql_carreras);
            while ($carrera = mysqli_fetch_array($resultado_carreras)) {
                echo "<option value='" . $carrera['idcarrera'] . "' " . (isset($r['idcarrera']) && $r['idcarrera'] == $carrera['idcarrera'] ? 'selected' : '') . ">" . $carrera['nombre'] . "</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="<?php echo isset($_GET['id']) ? 'Actualizar' : 'Insertar'; ?> Estudiante">
    </form>
</section>

<!-- Listado de estudiantes -->
<?php
$sql = "SELECT e.*, c.nombre AS carrera FROM estudiantes e JOIN carreras c ON c.idcarrera = e.idcarrera WHERE e.eliminado = 0 ORDER BY e.apellido, e.nombre";
$resultado = mysqli_query($con, $sql);
?>

<link rel="stylesheet" href="css/estilo_general.css">
<section class="main-content">
    <section class="academic-status">
    <h2>Lista de Estudiantes</h2>
    <table border="1">
        <thead>
        <tr>
            <th>ID Estudiante</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Carrera</th>
            <th>D.N.I</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($resultado) > 0) {
            while ($r = mysqli_fetch_array($resultado)) {
                ?>
                <tr>
                    <td><?php echo $r['idestudiante']; ?></td>
                    <td><?php echo $r['apellido']; ?></td>
                    <td><?php echo $r['nombre']; ?></td>
                    <td><?php echo $r['carrera']; ?></td>
                    <td><?php echo $r['dni']; ?></td>
                    <td>
                        <a href="index.php?modulo=amb_estudiantes&id=<?php echo $r['idestudiante']; ?>"><i class='fas fa-pencil-alt ancho_boton'></i></a>
                        <a href="index.php?modulo=amb_estudiantes&delete_id=<?php echo $r['idestudiante']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este estudiante?');"><i class='fas fa-times-circle ancho_boton'></i></a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
    </section>
</section>
