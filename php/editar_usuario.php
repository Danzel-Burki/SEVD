<?php
//Protección con CSRF: Genera un token si no existe
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
    //Verificación del token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Acceso no autorizado.");
    }

    $nuevoNombre = $_POST['nombreusuario'] ?? '';
    $claveActual = $_POST['clave_actual'] ?? '';
    $nuevaClave = $_POST['nueva_clave'] ?? '';
    $repetirClave = $_POST['repetir_clave'] ?? '';

    //Verificación de campos vacíos (para nombre)
    if (empty($nuevoNombre)) {
        $mensajeError = "El nombre de usuario no puede estar vacío.";
    } elseif (
        ($claveActual || $nuevaClave || $repetirClave) &&
        (empty($claveActual) || empty($nuevaClave) || empty($repetirClave))
    ) {
        //Verificación de campos vacíos para contraseña si intenta cambiarla
        $mensajeError = "Si desea cambiar la contraseña, debe completar todos los campos de contraseña.";
    }

    //Verificar si se cambió el nombre y si ya está en uso
    if (empty($mensajeError) && $nuevoNombre !== $nombreusuarioActual) {
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

    //Si no hay errores, procesar cambios
    if (empty($mensajeError)) {
        $claveAUsar = $claveActualHasheada; // Por defecto mantiene la actual

        if ($claveActual || $nuevaClave || $repetirClave) {
            // Validar la contraseña actual
            if (!password_verify($claveActual, $claveActualHasheada)) {
                $mensajeError = "Contraseña actual incorrecta.";
            } elseif ($nuevaClave !== $repetirClave) {
                $mensajeError = "Las nuevas contraseñas no coinciden.";
            } elseif (password_verify($nuevaClave, $claveActualHasheada)) {
                $mensajeError = "La nueva contraseña no puede ser igual a la contraseña actual.";
            } else {
                $claveAUsar = password_hash($nuevaClave, PASSWORD_DEFAULT);
            }
        }
    }

    //Si sigue sin errores, actualizar en la base de datos
    if (empty($mensajeError)) {
        $queryUpdate = "
            UPDATE usuarios 
            SET nombreusuario = ?, clave = ? 
            WHERE idusuario = ?";
        
        if ($stmtUpdate = mysqli_prepare($con, $queryUpdate)) {
            mysqli_stmt_bind_param($stmtUpdate, "ssi", $nuevoNombre, $claveAUsar, $idusuario);
            mysqli_stmt_execute($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);

            // Mostrar alerta y luego redirigir
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
                <input type="text" id="nombreusuario" name="nombreusuario" value="<?php echo htmlspecialchars($nuevoNombre ?? $nombreusuarioActual); ?>" required>
            </div>
            <br>

            <div class="password-container">
                <label for="clave_actual">Contraseña actual:</label>
                <input type="password" id="clave_actual" name="clave_actual">
                <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="clave_actual" alt="Mostrar/Ocultar contraseña">
            </div>
            <br>

            <div class="password-container">
                <label for="nueva_clave">Nueva contraseña:</label>
                <input type="password" id="nueva_clave" name="nueva_clave">
                <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="nueva_clave" alt="Mostrar/Ocultar contraseña">
            </div>
            <br>

            <div class="password-container">
                <label for="repetir_clave">Repita la nueva contraseña:</label>
                <input type="password" id="repetir_clave" name="repetir_clave">
                <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="repetir_clave" alt="Mostrar/Ocultar contraseña">
            </div>
            <br>

            <button type="submit">Actualizar</button>
        </form>
    </section>
</section>
