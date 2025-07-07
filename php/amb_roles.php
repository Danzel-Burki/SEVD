<?php
// Verificar si se ha solicitado la eliminación de un permiso
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "UPDATE roles SET eliminado = 1 WHERE idrol = ?";
    $stmt_delete = $con->prepare($sql_delete);

    if ($stmt_delete === false) {
        die("Error en la preparación de la consulta: " . $con->error);
    }

    $stmt_delete->bind_param("i", $delete_id);

    if ($stmt_delete->execute()) {
        $_SESSION['mensaje'] = "Rol eliminado correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el rol: " . $stmt_delete->error;
    }

    $stmt_delete->close();
    header("Location: index.php?modulo=amb_roles");
    exit();
}

// Guardar o actualizar rol
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];

    // Verificar si es una actualización o una inserción
    if (isset($_GET['id'])) {
        // Actualizar rol existente
        $sql = "UPDATE roles SET tipo = ?, descripcion = ? WHERE idrol = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $tipo, $descripcion, $_GET['id']);
        $_SESSION['mensaje'] = "Rol actualizado correctamente.";
    } else {
        // Insertar nuevo rol
        $sql = "INSERT INTO roles (tipo, descripcion) VALUES (?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $tipo, $descripcion);
        $_SESSION['mensaje'] = "Rol insertado correctamente.";
    }

    if ($stmt->execute()) {
        // Redirigir para evitar reenvío del formulario
        header("Location: index.php?modulo=amb_roles");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
}

// Mostrar formulario para editar roles existente
if (isset($_GET['id'])) {
    $sql = "SELECT * FROM roles WHERE idrol = ".$_GET['id'];
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
    <h2>Formulario de Inserción de Roles</h2>
    
        <form method="POST" action="index.php?modulo=amb_roles<?php echo isset($_GET['id']) ? '&id=' . $_GET['id'] : ''; ?>">
            <label for="tipo">Tipo:</label><br>
            <input type="text" id="tipo" name="tipo" value="<?php echo isset($r['tipo']) ? $r['tipo'] : ''; ?>" required><br><br>

            <label for="descripcion">Descripción:</label><br>
            <input type="text" id="descripcion" name="descripcion" value="<?php echo isset($r['descripcion']) ? $r['descripcion'] : ''; ?>" required><br><br>

            <input type="submit" value="<?php echo isset($_GET['id']) ? 'Actualizar' : 'Insertar'; ?> Rol">
        </form>
    </section>

    <!-- Listado de roles -->
    <?php
    $sql = "SELECT * FROM roles WHERE eliminado = 0  ORDER BY tipo";
    $sql = mysqli_query($con, $sql);
    ?>

<link rel="stylesheet" href="css/estilo_general.css">
<section class="main-content">
    <section class="academic-status">
    <h2>Lista de Roles</h2>
    <table border="1">
        <thead>
        <tr>
            <th>ID Rol</th>
            <th>Tipo</th>
            <th>Descripcion</th>
            <th>Opciones</th>
        </tr>
        </thead>

        <?php
        if (mysqli_num_rows($sql) > 0) {
            while($r = mysqli_fetch_array($sql)) {
                ?>
                <tr>
                    <td><?php echo $r['idrol']; ?></td>
                    <td><?php echo $r['tipo']; ?></td>
                    <td><?php echo $r['descripcion']; ?></td>
                    <td> 
                        <a href="index.php?modulo=amb_roles&id=<?php echo $r['idrol']; ?>"><i class='fas fa-pencil-alt ancho_boton'></i></a> 
                        <a href="index.php?modulo=amb_roles&delete_id=<?php echo $r['idrol']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este permiso?');"><i class='fas fa-times-circle ancho_boton'></i> </a> 
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>      
    </section>   
</section>