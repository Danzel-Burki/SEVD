<?php
// Verificar si se ha solicitado la eliminación de un permiso
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "UPDATE permisos SET eliminado = 1 WHERE idpermiso = ?";
    $stmt_delete = $con->prepare($sql_delete);

    if ($stmt_delete === false) {
        die("Error en la preparación de la consulta: " . $con->error);
    }

    $stmt_delete->bind_param("i", $delete_id);

    if ($stmt_delete->execute()) {
        $_SESSION['mensaje'] = "Permiso eliminado correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el permiso: " . $stmt_delete->error;
    }

    $stmt_delete->close();
    header("Location: index.php?modulo=amb_permisos");
    exit();
}

// Guardar o actualizar permiso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $modulo = $_POST['modulo'];
    $icono = $_POST['icono'];

    // Verificar si es una actualización o una inserción
    if (isset($_GET['id'])) {
        // Actualizar permiso existente
        $sql = "UPDATE permisos SET nombre = ?, descripcion = ?, modulo = ?, icono = ? WHERE idpermiso = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssi", $nombre, $descripcion, $modulo, $icono, $_GET['id']);
        $_SESSION['mensaje'] = "Permiso actualizado correctamente.";
    } else {
        // Insertar nuevo permiso
        $sql = "INSERT INTO permisos (nombre, descripcion, modulo, icono) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $descripcion, $modulo, $icono);
        $_SESSION['mensaje'] = "Permiso insertado correctamente.";
    }

    if ($stmt->execute()) {
        // Redirigir para evitar reenvío del formulario
        header("Location: index.php?modulo=amb_permisos");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
}

// Mostrar formulario para editar permiso existente
if (isset($_GET['id'])) {
    $sql = "SELECT * FROM permisos WHERE idpermiso = ".$_GET['id'];
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
    <h2>Formulario de Inserción de Permisos</h2>
    
        <form method="POST" action="index.php?modulo=amb_permisos<?php echo isset($_GET['id']) ? '&id=' . $_GET['id'] : ''; ?>">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo isset($r['nombre']) ? $r['nombre'] : ''; ?>" required><br><br>

            <label for="descripcion">Descripción:</label><br>
            <input type="text" id="descripcion" name="descripcion" value="<?php echo isset($r['descripcion']) ? $r['descripcion'] : ''; ?>" required><br><br>

            <label for="modulo">Módulo:</label><br>
            <input type="text" id="modulo" name="modulo" value="<?php echo isset($r['modulo']) ? $r['modulo'] : ''; ?>" required><br><br>

            <label for="icono">Ícono:</label><br>
            <input type="text" id="icono" name="icono" value="<?php echo isset($r['icono']) ? $r['icono'] : ''; ?>" required><br><br>

            <input type="submit" value="<?php echo isset($_GET['id']) ? 'Actualizar' : 'Insertar'; ?> Permiso">
        </form>
    </section>

    <!-- Listado de permisos -->
    <?php
    $sql = "SELECT * FROM permisos WHERE eliminado = 0 ORDER BY nombre";
    $sql = mysqli_query($con, $sql);
    ?>

<link rel="stylesheet" href="css/estilo_general.css">
<section class="main-content">
    <section class="academic-status">
    <h2>Lista de Permisos</h2>
    <table border="1">
        <thead>
        <tr>
            <th>ID Permiso</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Modulo</th>
            <th>Icono</th>
            <th>Opciones</th>
        </tr>
        </thead>

        <?php
        if (mysqli_num_rows($sql) > 0) {
            while($r = mysqli_fetch_array($sql)) {
                ?>
                <tr>
                    <td><?php echo $r['idpermiso']; ?></td>
                    <td><?php echo $r['nombre']; ?></td>
                    <td><?php echo $r['descripcion']; ?></td>
                    <td><?php echo $r['modulo']; ?></td>
                    <td><?php echo $r['icono']; ?></td>
                    <td> 
                        <a href="index.php?modulo=amb_permisos&id=<?php echo $r['idpermiso']; ?>"><i class='fas fa-pencil-alt ancho_boton'></i></a> 
                        <a href="index.php?modulo=amb_permisos&delete_id=<?php echo $r['idpermiso']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este permiso?');"><i class='fas fa-times-circle ancho_boton'></i> </a> 
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>      
    </section>    
</section>