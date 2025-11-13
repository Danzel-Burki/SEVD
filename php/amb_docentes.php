<?php
// Eliminar docente si se solicita
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM docentes WHERE iddocente = ?";
    $stmt_delete = $con->prepare($sql_delete);
    if ($stmt_delete === false) {
        die("Error en la preparación de la consulta: " . $con->error);
    }
    $stmt_delete->bind_param("i", $delete_id);
    if ($stmt_delete->execute()) {
        $_SESSION['mensaje'] = "Docente eliminado correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el docente: " . $stmt_delete->error;
    }
    $stmt_delete->close();
    header("Location: index.php?modulo=amb_docentes");
    exit();
}

// Guardar o actualizar docente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $correo = $_POST['email'];
    $telefono = $_POST['telefono'];
    $idcarrera = 1; // Cambia esto si tenés un select de carrera

    // 1️⃣ Verificamos si ya existe el usuario por correo o DNI
    $sql_check = "SELECT idusuario FROM usuarios WHERE correo = ? OR dni = ?";
    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param("si", $correo, $dni);
    $stmt_check->execute();
    $stmt_check->bind_result($idusuario_existente);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($idusuario_existente) {
        // Usuario ya existe
        $idusuario = $idusuario_existente;
    } else {
        // 2️⃣ Crear nuevo usuario con rol DOCENTE (idrol = 2)
        $nombreusuario = strtolower(explode(" ", $nombre)[0]) . rand(100, 999);
        $clave_hash = password_hash("docente123", PASSWORD_BCRYPT);
        $idrol = 2; // rol docente

        $sql_usuario = "INSERT INTO usuarios (nombre, apellido, clave, idrol, dni, correo, nombreusuario, verificacion)
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'verificado')";
        $stmt_usuario = $con->prepare($sql_usuario);
        $stmt_usuario->bind_param("sssisss", $nombre, $apellido, $clave_hash, $idrol, $dni, $correo, $nombreusuario);
        $stmt_usuario->execute();
        $idusuario = $stmt_usuario->insert_id;
        $stmt_usuario->close();
    }

    // 3️⃣ Insertar o actualizar en docentes
    if (isset($_GET['id'])) {
        $sql = "UPDATE docentes 
                SET nombre=?, apellido=?, dni=?, correo=?, telefono=?, idusuario=?, idcarrera=?
                WHERE iddocente=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssiiii", $nombre, $apellido, $dni, $correo, $telefono, $idusuario, $idcarrera, $_GET['id']);
        $_SESSION['mensaje'] = "Docente actualizado correctamente.";
    } else {
        $sql = "INSERT INTO docentes (nombre, apellido, dni, correo, telefono, idusuario, idcarrera)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssis", $nombre, $apellido, $dni, $correo, $telefono, $idusuario, $idcarrera);
        $_SESSION['mensaje'] = "Docente insertado correctamente.";
    }

    if ($stmt->execute()) {
        header("Location: index.php?modulo=amb_docentes");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener datos para editar
if (isset($_GET['id'])) {
    $sql = "SELECT * FROM docentes WHERE iddocente = " . intval($_GET['id']);
    $result = mysqli_query($con, $sql);
    $r = mysqli_fetch_array($result);
}

// Mostrar mensaje
if (isset($_SESSION['mensaje'])) {
    echo "<script>alert('" . $_SESSION['mensaje'] . "');</script>";
    unset($_SESSION['mensaje']);
}
?>

<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<section class="main-content">
    <h2>Formulario de Docentes</h2>

    <form class="inscription-form" method="POST" action="index.php?modulo=amb_docentes<?php echo isset($_GET['id']) ? '&id=' . $_GET['id'] : ''; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required value="<?php echo isset($r['nombre']) ? $r['nombre'] : ''; ?>">

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" id="apellido" required value="<?php echo isset($r['apellido']) ? $r['apellido'] : ''; ?>">

        <label for="dni">DNI:</label>
        <input type="text" name="dni" id="dni" required value="<?php echo isset($r['dni']) ? $r['dni'] : ''; ?>">

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required value="<?php echo isset($r['correo']) ? $r['correo'] : ''; ?>">

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" value="<?php echo isset($r['telefono']) ? $r['telefono'] : ''; ?>">

        <input type="submit" value="<?php echo isset($_GET['id']) ? 'Actualizar' : 'Insertar'; ?> Docente">
    </form>
</section>

<!-- Listado de docentes -->
<?php
$sql = "SELECT * FROM docentes ORDER BY apellido, nombre";
$resultado = mysqli_query($con, $sql);
?>
<link rel="stylesheet" href="css/estilo_general.css">
<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<section class="main-content">
    <section class="academic-status">

        <h2>Lista de Docentes</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($resultado) > 0) {
                while ($r = mysqli_fetch_array($resultado)) {
                    ?>
                    <tr>
                        <td><?php echo $r['iddocente']; ?></td>
                        <td><?php echo $r['apellido']; ?></td>
                        <td><?php echo $r['nombre']; ?></td>
                        <td><?php echo $r['dni']; ?></td>
                        <td><?php echo $r['correo']; ?></td>
                        <td><?php echo $r['telefono']; ?></td>
                        <td>
                            <a href="index.php?modulo=amb_docentes&id=<?php echo $r['iddocente']; ?>"><i class="fas fa-pencil-alt"></i></a>
                            <a href="index.php?modulo=amb_docentes&delete_id=<?php echo $r['iddocente']; ?>" onclick="return confirm('¿Eliminar docente?');" style="color:red;"><i class="fas fa-trash"></i></a>
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
