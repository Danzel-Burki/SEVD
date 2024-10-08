<?php
// Lógica para gestionar permisos (asignar o eliminar)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $idusuario = $_POST['idusuario'];
    $idpermiso = $_POST['idpermiso'];

    if ($accion == 'asignar') {
        // Verificar si el permiso ya está asignado
        $sql_verificar = "SELECT * FROM usuarios_permisos WHERE idusuario = '$idusuario' AND idpermiso = '$idpermiso'";
        $result_verificar = mysqli_query($con, $sql_verificar);
        
        if (mysqli_num_rows($result_verificar) > 0) {
            $_SESSION['mensaje'] = "Este permiso ya está asignado a este usuario.";
            $_SESSION['mensaje_tipo'] = "error";
        } else {
            // Asignar permiso
            $sql_asignar = "INSERT INTO usuarios_permisos (idusuario, idpermiso) VALUES ('$idusuario', '$idpermiso')";
            if (mysqli_query($con, $sql_asignar)) {
                $_SESSION['mensaje'] = "Permiso asignado correctamente.";
                $_SESSION['mensaje_tipo'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al asignar permiso: " . mysqli_error($con);
                $_SESSION['mensaje_tipo'] = "error";
            }
        }
    } elseif ($accion == 'eliminar') {
        // Eliminar permiso
        $sql_eliminar = "DELETE FROM usuarios_permisos WHERE idusuario = '$idusuario' AND idpermiso = '$idpermiso'";
        if (mysqli_query($con, $sql_eliminar)) {
            $_SESSION['mensaje'] = "Permiso eliminado correctamente.";
            $_SESSION['mensaje_tipo'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar permiso: " . mysqli_error($con);
            $_SESSION['mensaje_tipo'] = "error";
        }
    }
    header("Location:index.php?modulo=permisos_usuarios");
    exit();
}
?>

<!-- Mostrar mensaje de confirmación arriba de todo -->
<?php if (isset($_SESSION['mensaje'])): ?>
    <script>
        alert('<?php echo $_SESSION['mensaje']; ?>');
    </script>
    <?php 
        unset($_SESSION['mensaje']); 
        unset($_SESSION['mensaje_tipo']);
    ?>
<?php endif; ?>

<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">

<section class="main-content">
    <h2>Gestión de Permisos para Usuarios</h2>

    <!-- Formulario para asignar permisos -->
    <form action="" method="post">
        <input type="hidden" name="accion" value="asignar">
        
        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <select name="idusuario" id="usuario" required>
                <option value="">Seleccione un usuario</option>
                <?php
                // Consulta para listar usuarios
                $sql_usuarios = "SELECT idusuario, nombre FROM usuarios";
                $result_usuarios = mysqli_query($con, $sql_usuarios);
                while ($fila = mysqli_fetch_array($result_usuarios)) {
                    echo "<option value='" . $fila['idusuario'] . "'>" . $fila['nombre'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="permiso">Permiso:</label>
            <select name="idpermiso" id="permiso" required>
                <option value="">Seleccione un permiso</option>
                <?php
                // Consulta para listar permisos
                $sql_permisos = "SELECT idpermiso, nombre FROM permisos";
                $result_permisos = mysqli_query($con, $sql_permisos);
                while ($fila = mysqli_fetch_array($result_permisos)) {
                    echo "<option value='" . $fila['idpermiso'] . "'>" . $fila['nombre'] . "</option>";
                }
                ?>
            </select>
        </div>

        <input type="submit" value="Asignar Permiso">
    </form>
</section>

<link rel="stylesheet" href="css/Styles_estado_academico.css">
<section class="main-content">
    <section class="academic-status">
        <!-- Tabla de permisos asignados con opción para eliminarlos -->
        <h3>Permisos Asignados a Usuarios</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Permiso</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener los permisos asignados a todos los usuarios
                $sql_asignados = "SELECT usuarios.nombre AS usuario, permisos.nombre AS permiso, 
                                usuarios_permisos.idusuario, usuarios_permisos.idpermiso 
                                FROM usuarios_permisos
                                JOIN usuarios ON usuarios.idusuario = usuarios_permisos.idusuario
                                JOIN permisos ON permisos.idpermiso = usuarios_permisos.idpermiso";
                $result_asignados = mysqli_query($con, $sql_asignados);
                
                if (mysqli_num_rows($result_asignados) > 0) {
                    while ($fila = mysqli_fetch_array($result_asignados)) {
                        echo "<tr>
                                <td>{$fila['usuario']}</td>
                                <td>{$fila['permiso']}</td>
                                <td>
                                    <form action='' method='post'>
                                        <input type='hidden' name='accion' value='eliminar'>
                                        <input type='hidden' name='idusuario' value='{$fila['idusuario']}'>
                                        <input type='hidden' name='idpermiso' value='{$fila['idpermiso']}'>
                                        <input type='submit' value='Eliminar' class='btn-eliminar' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este permiso?');\">
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay permisos asignados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>    
</section>