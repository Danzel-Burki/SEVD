<link rel="stylesheet" href="css/inicio_sesion.css">
<script src="js/editar_usuario.js" defer></script>

<div class="form-container">
    <input type="checkbox" id="reg-log" hidden>
    <div class="card-3d-wrap">
        <div class="card-3d-wrapper">
            <!-- INICIO DE SESIÓN -->
            <div class="card-front">
                <h2>Inicio de Sesión</h2>
                <form action="index.php" method="post">
                    <label for="username">Nombre de usuario:</label>
                    <input type="text" id="username" name="username" required>

                    <div class="password-container">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" id="contrasena" name="password" required>
                        <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="contrasena" alt="Mostrar/Ocultar contraseña">
                    </div>

                    <input type="submit" name="iniciar_sesion" value="Iniciar sesión">
                </form>

                <?php
                if (isset($_POST['iniciar_sesion'])) {
                    $username = trim($_POST['username']);
                    $password = trim($_POST['password']);
                    $sql = "SELECT * FROM usuarios WHERE nombreusuario = '$username'";
                    $res = mysqli_query($con, $sql);

                    if ($res && mysqli_num_rows($res) > 0) {
                        $r = mysqli_fetch_assoc($res);
                        if (password_verify($password, $r['clave'])) {
                            if ($r['verificacion'] === 'verificado') {
                                session_start();
                                $_SESSION['idusuario'] = $r['idusuario'];
                                $_SESSION['idrol'] = $r['idrol'];
                                $_SESSION['nombrecarrera'] = $r['nombre'];
                                echo "<script>alert('Bienvenido: " . $r['nombre'] . "');</script>";
                                echo "<script>window.location='index.php';</script>";
                            } else {
                                echo "<p class='mensaje'>Usuario no verificado.</p>";
                            }
                        } else {
                            echo "<p class='mensaje'>Contraseña incorrecta.</p>";
                        }
                    } else {
                        echo "<p class='mensaje'>Usuario no encontrado.</p>";
                    }
                }
                ?>

                <label for="reg-log" class="btn">¿No tienes cuenta? <br> Regístrate</label>
            </div>

            <!-- REGISTRO -->
            <div class="card-back">
                <h2>Crear Usuario</h2>
                <form action="index.php" method="post">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>
                    
                    <label for="dni">D.N.I:</label>
                    <input type="text" 
                        id="dni" 
                        name="dni" 
                        value="<?php echo isset($r['dni']) ? htmlspecialchars($r['dni']) : ''; ?>" 
                        pattern="^\d{8}$" 
                        maxlength="8" 
                        inputmode="numeric" 
                        title="Porfavor ingrese exactamente 8 números sin . ni ," 
                        required>

                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required>

                    <label for="nombreusuario">Nombre de Usuario:</label>
                    <input type="text" id="nombreusuario" name="nombreusuario" required>

                    <div class="password-container">
                        <label for="contrasena1">Contraseña:</label>
                        <input type="password" id="contrasena1" name="password" required>
                        <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="contrasena1" alt="Mostrar/Ocultar contraseña">
                    </div>

                    <label for="idrol">Rol:</label>
                    <select id="idrol" name="idrol" required>
                        <option value="">Seleccione un rol</option>
                        <?php
                        include("conexion.php");
                        $sql_roles = "SELECT idrol, tipo FROM roles";
                        $resultado_roles = mysqli_query($con, $sql_roles);
                        while ($r = mysqli_fetch_assoc($resultado_roles)) {
                            echo "<option value='" . $r['idrol'] . "'>" . $r['tipo'] . "</option>";
                        }
                        ?>
                    </select>

                    <input type="submit" name="crear_usuario" value="Crear Usuario">
                </form>

                <?php
                if (isset($_POST['crear_usuario'])) {
                    include("conexion.php");
                    $nombre = $_POST['nombre'];
                    $apellido = $_POST['apellido'];
                    $dni = $_POST['dni'];
                    $correo = $_POST['correo'];
                    $nombreusuario = $_POST['nombreusuario'];
                    $clave = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $idrol = $_POST['idrol'];

                    $existe = mysqli_query($con, "SELECT * FROM usuarios WHERE nombreusuario = '$nombreusuario' OR correo = '$correo' OR dni = '$dni'");
                    if (mysqli_num_rows($existe) > 0) {
                        echo "<script>alert('El usuario, correo o DNI ya existen.');</script>";
                    } else {
                        $sql = "INSERT INTO usuarios (nombre, apellido, dni, correo, nombreusuario, clave, idrol) 
                                VALUES ('$nombre', '$apellido', '$dni', '$correo', '$nombreusuario', '$clave', '$idrol')";
                        if (mysqli_query($con, $sql)) {
                            echo "<script>alert('Usuario creado. Pendiente de verificación.');</script>";
                        } else {
                            echo "<script>alert('Error al registrar usuario.');</script>";
                        }
                    }
                }
                ?>

                <label for="reg-log" class="btn">¿Ya tienes cuenta? <br> Inicia sesión</label>
            </div>
        </div>
    </div>
</div> 