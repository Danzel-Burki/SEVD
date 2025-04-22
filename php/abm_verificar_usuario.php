<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idusuario = $_POST['idusuario'];
    $idrol = $_POST['idrol'];

    // Actualizar el usuario con el nuevo rol y verificación
    $actualizar = "UPDATE usuarios SET idrol = $idrol, verificacion = 'verificado' WHERE idusuario = $idusuario";
    if (mysqli_query($con, $actualizar)) {
        // Obtener datos del usuario para enviar el correo
        $consulta_info = "SELECT nombre, correo FROM usuarios WHERE idusuario = $idusuario";
        $resultado_info = mysqli_query($con, $consulta_info);
        $usuario_info = mysqli_fetch_assoc($resultado_info);

        $para = $usuario_info['correo'];
        $asunto = "Verificación de cuenta SEVD";
        $mensaje = "Hola " . $usuario_info['nombre'] . ",\n\nTu cuenta ha sido verificada exitosamente. Ya podés acceder al Sistema Educativo Verbo Divino (SEVD).\n\nSaludos cordiales,\nEquipo SEVD";
        $cabeceras = "From: no-responder@sevd.com";

        mail($para, $asunto, $mensaje, $cabeceras);
    }

    // Recargar la página automáticamente
    header("Location: index.php?modulo=abm_verificar_usuario");
    exit;
}

// Obtener usuarios pendientes de verificación
$consulta = "SELECT u.idusuario, u.nombre, u.apellido, u.dni, u.correo, u.idrol, r.tipo 
             FROM usuarios u 
             JOIN roles r ON u.idrol = r.idrol 
             WHERE u.verificacion = 'pendiente'";
$resultado = mysqli_query($con, $consulta);

// Obtener todos los roles disponibles
$consulta_roles = "SELECT * FROM roles";
$resultado_roles = mysqli_query($con, $consulta_roles);
$roles = [];
while ($fila = mysqli_fetch_assoc($resultado_roles)) {
    $roles[] = $fila;
}
?>

<link rel="stylesheet" href="css/estilo_general.css">
    <section class="main-content">
        <section class="academic-status">
            <h2>Usuarios pendientes de verificación</h2>
            <?php if (mysqli_num_rows($resultado) === 0): ?>
                <p>No hay usuarios pendientes de verificación.</p>
            <?php else: ?>
                <table border="1">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Verificación</th>
                    </tr>
                    <?php while ($usuario = mysqli_fetch_assoc($resultado)) : ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="idusuario" value="<?= $usuario['idusuario'] ?>">
                                <td><?= $usuario['nombre'] ?></td>
                                <td><?= $usuario['apellido'] ?></td>
                                <td><?= $usuario['dni'] ?></td>
                                <td><?= $usuario['correo'] ?></td>
                                <td>
                                    <select name="idrol">
                                        <?php foreach ($roles as $rol) : ?>
                                            <option value="<?= $rol['idrol'] ?>" <?= $rol['idrol'] == $usuario['idrol'] ? 'selected' : '' ?>>
                                                <?= $rol['tipo'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="submit" value="Verificar"></td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php endif; ?>

