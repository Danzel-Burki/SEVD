<?php
// Verificar si se ha solicitado la eliminación de un docente
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // En lugar de eliminar, podemos marcar como no verificado o cambiar el rol
    $sql_delete = "UPDATE usuarios SET verificacion = 'pendiente', idrol = 1 WHERE idusuario = ?";
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
    header("Location: index.php?modulo=abm_docentes");
    exit();
}

// Guardar o actualizar docente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $correo = $_POST['correo'];
    $nombreusuario = $_POST['nombreusuario'];
    $clave = $_POST['clave'];
    $idcarrera = $_POST['idcarrera'];
    
    // Verificar si es una actualización o una inserción
    if (isset($_GET['id'])) {
        // Actualizar docente existente
        if (!empty($clave)) {
            $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, dni = ?, correo = ?, nombreusuario = ?, clave = ? WHERE idusuario = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssisssi", $nombre, $apellido, $dni, $correo, $nombreusuario, $clave_hash, $_GET['id']);
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, dni = ?, correo = ?, nombreusuario = ? WHERE idusuario = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssissi", $nombre, $apellido, $dni, $correo, $nombreusuario, $_GET['id']);
        }
        
        if ($stmt->execute()) {
            // Actualizar también en la tabla docentes
            $sql_docente = "UPDATE docentes SET nombre = ?, apellido = ?, dni = ?, correo = ?, idcarrera = ? WHERE idusuario = ?";
            $stmt_docente = $con->prepare($sql_docente);
            $stmt_docente->bind_param("sssiii", $nombre, $apellido, $dni, $correo, $idcarrera, $_GET['id']);
            $stmt_docente->execute();
            $stmt_docente->close();
            
            $_SESSION['mensaje'] = "Docente actualizado correctamente.";
        }
        $stmt->close();
        
    } else {
        // Insertar nuevo docente
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
        $idrol = 2; // Rol de docente
        $verificacion = 'verificado';
        
        // Verificar si el usuario ya existe en la tabla usuarios por DNI
        $sql_check = "SELECT idusuario FROM usuarios WHERE dni = ?";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->bind_param("s", $dni);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            // El usuario ya existe, obtener su idusuario
            $usuario_existente = $result_check->fetch_assoc();
            $idusuario = $usuario_existente['idusuario'];
            
            // Actualizar el usuario existente a rol docente
            $sql_update_usuario = "UPDATE usuarios SET idrol = ?, verificacion = ? WHERE idusuario = ?";
            $stmt_update = $con->prepare($sql_update_usuario);
            $stmt_update->bind_param("isi", $idrol, $verificacion, $idusuario);
            $stmt_update->execute();
            $stmt_update->close();
            
        } else {
            // El usuario no existe, crear nuevo usuario
            $sql_usuario = "INSERT INTO usuarios (nombre, apellido, dni, correo, nombreusuario, clave, idrol, verificacion) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_usuario = $con->prepare($sql_usuario);
            $stmt_usuario->bind_param("ssisssis", $nombre, $apellido, $dni, $correo, $nombreusuario, $clave_hash, $idrol, $verificacion);
            
            if ($stmt_usuario->execute()) {
                $idusuario = $stmt_usuario->insert_id;
            } else {
                $_SESSION['mensaje'] = "Error al crear usuario: " . $stmt_usuario->error;
                $stmt_usuario->close();
                header("Location: index.php?modulo=abm_docentes");
                exit();
            }
            $stmt_usuario->close();
        }
        
        // Insertar en la tabla docentes
        $sql_docente = "INSERT INTO docentes (nombre, apellido, dni, correo, idusuario, idcarrera) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_docente = $con->prepare($sql_docente);
        $stmt_docente->bind_param("ssssii", $nombre, $apellido, $dni, $correo, $idusuario, $idcarrera);
        
        if ($stmt_docente->execute()) {
            $_SESSION['mensaje'] = "Docente insertado correctamente.";
        } else {
            $_SESSION['mensaje'] = "Error al insertar docente: " . $stmt_docente->error;
        }
        $stmt_docente->close();
    }

    // Redirigir para evitar reenvío del formulario
    header("Location: index.php?modulo=abm_docentes");
    exit();
}

// Mostrar formulario para editar docente existente
if (isset($_GET['id'])) {
    // Obtener datos del docente uniendo ambas tablas
    $sql = "SELECT u.*, d.idcarrera 
            FROM usuarios u 
            LEFT JOIN docentes d ON u.idusuario = d.idusuario 
            WHERE u.idusuario = ".$_GET['id']." AND u.idrol = 2";
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
    <h2><?php echo isset($_GET['id']) ? 'Editar Docente' : 'Agregar Nuevo Docente'; ?></h2>
    
    <form method="POST" class="inscription-form" action="index.php?modulo=abm_docentes<?php echo isset($_GET['id']) ? '&id=' . $_GET['id'] : ''; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo isset($r['nombre']) ? $r['nombre'] : ''; ?>" required><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo isset($r['apellido']) ? $r['apellido'] : ''; ?>" required><br><br>

        <label for="dni">D.N.I:</label>
        <input type="text" id="dni" name="dni" value="<?php echo isset($r['dni']) ? $r['dni'] : ''; ?>" required><br><br>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" value="<?php echo isset($r['correo']) ? $r['correo'] : ''; ?>" required><br><br>

        <label for="nombreusuario">Nombre de Usuario:</label>
        <input type="text" id="nombreusuario" name="nombreusuario" value="<?php echo isset($r['nombreusuario']) ? $r['nombreusuario'] : ''; ?>" required><br><br>

        <?php if (!isset($_GET['id'])): ?>
        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required><br><br>
        <?php else: ?>
        <label for="clave">Contraseña (dejar en blanco para no cambiar):</label>
        <input type="password" id="clave" name="clave"><br><br>
        <?php endif; ?>

        <label for="idcarrera">Carrera:</label><br>
        <select id="idcarrera" name="idcarrera" required>
            <option value="">Seleccione una carrera</option>
            <?php
            $sql_carreras = "SELECT idcarrera, nombre FROM carreras";
            $resultado_carreras = mysqli_query($con, $sql_carreras);
            while ($carrera = mysqli_fetch_array($resultado_carreras)) {
                $selected = (isset($r['idcarrera']) && $r['idcarrera'] == $carrera['idcarrera']) ? 'selected' : '';
                echo "<option value='" . $carrera['idcarrera'] . "' $selected>" . $carrera['nombre'] . "</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="<?php echo isset($_GET['id']) ? 'Actualizar' : 'Insertar'; ?> Docente">
    </form>
</section>

<!-- Listado de docentes -->
<?php
$sql = "SELECT u.*, d.iddocente, r.tipo as rol, c.nombre as carrera_nombre
        FROM usuarios u 
        JOIN docentes d ON u.idusuario = d.idusuario
        JOIN roles r ON u.idrol = r.idrol 
        JOIN carreras c ON d.idcarrera = c.idcarrera
        WHERE u.idrol = 2 AND u.verificacion = 'verificado'
        ORDER BY u.apellido, u.nombre";
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
                    <th>D.N.I</th>
                    <th>Correo</th>
                    <th>Usuario</th>
                    <th>Carrera</th>
                    <th>Rol</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($resultado) > 0) {
                    while ($r = mysqli_fetch_array($resultado)) {
                        ?>
                        <tr>
                            <td><?php echo $r['idusuario']; ?></td>
                            <td><?php echo $r['apellido']; ?></td>
                            <td><?php echo $r['nombre']; ?></td>
                            <td><?php echo $r['dni']; ?></td>
                            <td><?php echo $r['correo']; ?></td>
                            <td><?php echo $r['nombreusuario']; ?></td>
                            <td><?php echo $r['carrera_nombre']; ?></td>
                            <td><?php echo $r['rol']; ?></td>
                            <td>
                                <a href="index.php?modulo=abm_docentes&id=<?php echo $r['idusuario']; ?>"><i class='fas fa-pencil-alt'></i></a>
                                <a href="index.php?modulo=abm_docentes&delete_id=<?php echo $r['idusuario']; ?>"
                                class="eliminar"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este docente?');"><i class='fa-solid fa-trash'></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='9'>No hay docentes registrados en el sistema.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>   
</section>