<?php
// Lógica para gestionar permisos (asignar o eliminar)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $idrol = $_POST['idrol'];
    $idpermiso = $_POST['idpermiso'];

    if ($accion == 'asignar') {
        // Verificar si el permiso ya está asignado
        $sql_verificar = "SELECT * FROM roles_permisos WHERE idrol = '$idrol' AND idpermiso = '$idpermiso'";
        $result_verificar = mysqli_query($con, $sql_verificar);
        
        if (mysqli_num_rows($result_verificar) > 0) {
            $_SESSION['mensaje'] = "Este permiso ya está asignado a este rol.";
            $_SESSION['mensaje_tipo'] = "error";
        } else {
            // Asignar permiso
            $sql_asignar = "INSERT INTO roles_permisos (idrol, idpermiso) VALUES ('$idrol', '$idpermiso')";
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
        $sql_eliminar = "DELETE FROM roles_permisos WHERE idrol = '$idrol' AND idpermiso = '$idpermiso'";
        if (mysqli_query($con, $sql_eliminar)) {
            $_SESSION['mensaje'] = "Permiso eliminado correctamente.";
            $_SESSION['mensaje_tipo'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar permiso: " . mysqli_error($con);
            $_SESSION['mensaje_tipo'] = "error";
        }
    }
    header("Location:index.php?modulo=permisos_roles");
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
    <h2>Gestión de Permisos para los Roles</h2>

    <!-- Formulario para asignar permisos -->
    <form action="" method="post">
        <input type="hidden" name="accion" value="asignar">
        
        <div class="form-group">
            <label for="roles">Rol:</label>
            <select name="idrol" id="rol" required>
                <option value="">Seleccione un rol</option>
                <?php
                // Consulta para listar roles
                $sql_roles = "SELECT idrol, tipo FROM roles";
                $result_roles = mysqli_query($con, $sql_roles);
                while ($fila = mysqli_fetch_array($result_roles)) {
                    echo "<option value='" . $fila['idrol'] . "'>" . $fila['tipo'] . "</option>";
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
        <h3>Permisos Asignados a Roles</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Roles</th>
                    <th>Permiso</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener los permisos asignados a todos los roles
                $sql_asignados = "SELECT roles.tipo AS rol, permisos.nombre AS permiso, 
                                roles_permisos.idrol, roles_permisos.idpermiso 
                                FROM roles_permisos
                                JOIN roles ON roles.idrol = roles_permisos.idrol
                                JOIN permisos ON permisos.idpermiso = roles_permisos.idpermiso";
                $result_asignados = mysqli_query($con, $sql_asignados);
                
                if (mysqli_num_rows($result_asignados) > 0) {
                    while ($fila = mysqli_fetch_array($result_asignados)) {
                        echo "<tr>
                                <td>{$fila['rol']}</td>
                                <td>{$fila['permiso']}</td>
                                <td>
                                    <form action='' method='post'>
                                        <input type='hidden' name='accion' value='eliminar'>
                                        <input type='hidden' name='idrol' value='{$fila['idrol']}'>
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