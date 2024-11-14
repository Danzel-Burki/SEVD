<?php


if (isset($_GET['idusuario'])) {
    $idusuario = $_GET['idusuario'];

    // Usar una sentencia preparada para obtener los datos actuales del usuario
    $query = "
        SELECT nombreusuario, clave 
        FROM usuarios 
        WHERE idusuario = ?";
    
    if ($stmt = mysqli_prepare($con, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $idusuario);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $nombreusuario, $clave);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Procesar el formulario para actualizar los datos del usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevoNombre = $_POST['nombreusuario'];
    $nuevaClave = $_POST['clave'];

    // Actualizar los datos del usuario
    $queryUpdate = "
        UPDATE usuarios 
        SET nombreusuario = ?, clave = ? 
        WHERE idusuario = ?";
    
    if ($stmtUpdate = mysqli_prepare($con, $queryUpdate)) {
        mysqli_stmt_bind_param($stmtUpdate, "ssi", $nuevoNombre, $nuevaClave, $idusuario);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);

        // Mensaje de éxito y redirigir
        echo "<script> alert('Datos actualizados exitosamente.'); window.location='index.php'; </script>";
    }
}

?>
<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<section class="main-content">
    <section class="academic-status">
    <form method="post" action="index.php?modulo=editar_usuario&idusuario=<?php echo $idusuario; ?>">
    <div>
        <label for="nombreusuario">Nombre de usuario:</label>
        <input type="text" id="nombreusuario" name="nombreusuario" value="<?php echo htmlspecialchars($nombreusuario); ?>" required>
    </div>
    <br>
    <div>
        <label for="clave">Clave:</label>
        <input type="password" id="clave" name="clave" value="<?php echo htmlspecialchars($clave); ?>" required>
    </div>
    <br>
    <button type="submit">Actualizar</button>
</form>
    </section>
</section>

