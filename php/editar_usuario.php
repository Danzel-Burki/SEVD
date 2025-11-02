<?php
// Protección CSRF: genera un token si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_GET['idusuario'])) {
    $idusuario = $_GET['idusuario'];

    // Obtener el nombre de usuario y la clave actual
    $query = "SELECT nombreusuario, clave FROM usuarios WHERE idusuario = ?";
    if ($stmt = mysqli_prepare($con, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $idusuario);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $nombreusuarioActual, $claveActualHasheada);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

$mensajeError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificación del token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Acceso no autorizado.");
    }

    $nuevoNombre = trim($_POST['nombreusuario'] ?? '');
    $nuevaClave = $_POST['nueva_clave'] ?? '';
    $repetirClave = $_POST['repetir_clave'] ?? '';

    $actualizarNombre = !empty($nuevoNombre) && $nuevoNombre !== $nombreusuarioActual;
    $actualizarClave = !empty($nuevaClave) || !empty($repetirClave);

    // Validar combinaciones posibles
    if (!$actualizarNombre && !$actualizarClave) {
        $mensajeError = "No hay cambios para actualizar.";
    }

    // Si quiere cambiar el nombre de usuario, verificar que no esté repetido
    if (empty($mensajeError) && $actualizarNombre) {
        $queryVerificar = "SELECT COUNT(*) FROM usuarios WHERE nombreusuario = ?";
        if ($stmtVerificar = mysqli_prepare($con, $queryVerificar)) {
            mysqli_stmt_bind_param($stmtVerificar, "s", $nuevoNombre);
            mysqli_stmt_execute($stmtVerificar);
            mysqli_stmt_bind_result($stmtVerificar, $cantidad);
            mysqli_stmt_fetch($stmtVerificar);
            mysqli_stmt_close($stmtVerificar);

            if ($cantidad > 0) {
                $mensajeError = "El nombre de usuario ya está en uso.";
            }
        }
    }

    // Si quiere cambiar la clave, validar coherencia
    if (empty($mensajeError) && $actualizarClave) {
        if (empty($nuevaClave) || empty($repetirClave)) {
            $mensajeError = "Debe completar ambos campos de contraseña.";
        } elseif ($nuevaClave !== $repetirClave) {
            $mensajeError = "Las contraseñas no coinciden.";
        } elseif (password_verify($nuevaClave, $claveActualHasheada)) {
            $mensajeError = "La nueva contraseña no puede ser igual a la anterior.";
        } else {
            $claveHasheadaNueva = password_hash($nuevaClave, PASSWORD_DEFAULT);
        }
    }

    // Si no hay errores, actualizar según corresponda
    if (empty($mensajeError)) {
        if ($actualizarNombre && $actualizarClave) {
            $queryUpdate = "UPDATE usuarios SET nombreusuario = ?, clave = ? WHERE idusuario = ?";
            $params = [$nuevoNombre, $claveHasheadaNueva, $idusuario];
            $types = "ssi";
        } elseif ($actualizarNombre) {
            $queryUpdate = "UPDATE usuarios SET nombreusuario = ? WHERE idusuario = ?";
            $params = [$nuevoNombre, $idusuario];
            $types = "si";
        } elseif ($actualizarClave) {
            $queryUpdate = "UPDATE usuarios SET clave = ? WHERE idusuario = ?";
            $params = [$claveHasheadaNueva, $idusuario];
            $types = "si";
        }

        if ($stmtUpdate = mysqli_prepare($con, $queryUpdate)) {
            mysqli_stmt_bind_param($stmtUpdate, $types, ...$params);
            mysqli_stmt_execute($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);

            echo "<script>
                    alert('Datos actualizados correctamente');
                    window.location.href = 'index.php?mensaje=actualizado';
                </script>";
            exit;
        }
    }
}
?>

<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<script src="js/editar_usuario.js"></script>

<section class="main-content">
    <section class="academic-status">
        <form method="post" action="index.php?modulo=editar_usuario&idusuario=<?php echo $idusuario; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <?php if (!empty($mensajeError)): ?>
                <p style="color:red;"><?php echo $mensajeError; ?></p>
            <?php endif; ?>

            <div>
                <label for="nombreusuario">Nombre de usuario:</label>
                <input type="text" id="nombreusuario" name="nombreusuario" 
                       value="<?php echo htmlspecialchars($nuevoNombre ?? $nombreusuarioActual); ?>">
            </div>
            <br>

            <div class="password-container">
                <label for="nueva_clave">Nueva contraseña:</label>
                <div class="input-wrapper">
                    <input type="password" id="nueva_clave" name="nueva_clave">
                    <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="nueva_clave" alt="Mostrar/Ocultar contraseña">
                </div>
            </div>

            <br>

            <div class="password-container">
                <label for="repetir_clave">Repita la nueva contraseña:</label>
                <div class="input-wrapper">
                    <input type="password" id="repetir_clave" name="repetir_clave">
                    <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="repetir_clave" alt="Mostrar/Ocultar contraseña">
                </div>
            </div>

            <button type="submit">Actualizar</button>
        </form>
    </section>
</section>
