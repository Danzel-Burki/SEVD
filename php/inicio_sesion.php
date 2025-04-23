<link rel="stylesheet" href="css/inicio_sesion.css">
<script src="js/editar_usuario.js"></script>

<div class="form-container">
    <input type="checkbox" id="reg-log" hidden>

    <div class="card-3d-wrap">
        <div class="card-3d-wrapper">
            <!-- Formulario de Inicio de Sesión -->
            <div class="card-front">
                <h2>Inicio de Sesión</h2>
                <form action="index.php" method="post">
                    <label for="username">Nombre de usuario:</label>
                    <input type="text" id="username" name="username" required>
                    <br>
                    <div class="password-container">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="contrasena" name="password" required>
                    <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="contrasena" alt="Mostrar/Ocultar contraseña">
                    </div>
                    <br>
                    <!-- Botón con name para que funcione el isset -->
                    <input type="submit" name="iniciar_sesion" value="Iniciar sesión">
                </form>

                <?php
                    if (isset($_POST['iniciar_sesion'])) {
                        $username = trim($_POST['username']);
                        $password = trim($_POST['password']);
                    
                        $sql = "SELECT * FROM usuarios WHERE nombreusuario = '$username'";
                        $res = mysqli_query($con, $sql);
                    
                        if ($res && mysqli_num_rows($res) != 0) {
                            $r = mysqli_fetch_assoc($res);
                    
                            if (password_verify($password, $r['clave'])) {
                                if ($r['verificacion'] === 'verificado') {
                                    session_start();
                                    $_SESSION['idusuario'] = $r['idusuario'];
                                    $_SESSION['idrol'] = $r['idrol'];
                                    $_SESSION['nombrecarrera'] = $r['nombre'];
                                    echo "<script>alert('Bienvenido: " . $r['nombre'] . "');</script>";
                                    echo "<script> window.location='index.php';</script>";
                                } else {
                                    echo "<p>Su usuario sigue en verificación y no puede iniciar sesión. Intente nuevamente más tarde.</p>";
                                }
                            } else {
                                echo "<p>Contraseña incorrecta.</p>";
                            }
                        } else {
                            echo "<p>Usuario no encontrado.</p>";
                        }
                    }                    
                ?>

                <label for="reg-log" class="btn">¿No tienes cuenta? Regístrate</label>
            </div>

            <!-- Formulario de Registro -->
            <div class="card-back">
                <h2>Crear Usuario</h2>
                <form action="index.php" method="post">
                    <label for="nombre">Nombre:</label><br>
                    <input type="text" id="nombre" name="nombre" required><br>

                    <label for="apellido">Apellido:</label><br>
                    <input type="text" id="apellido" name="apellido" required><br>

                    <label for="dni">DNI:</label><br>
                    <input type="text" id="dni" name="dni" required><br>

                    <label for="correo">Correo Electrónico:</label><br>
                    <input type="email" id="correo" name="correo" required><br>

                    <label for="nombreusuario">Nombre de Usuario:</label><br>
                    <input type="text" id="nombreusuario" name="nombreusuario" required><br>

                    <div class="password-container">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="contrasena1" name="password" required>
                    <img src="img/ojo_cerrado1.png" class="eye-icon" data-target="contrasena1" alt="Mostrar/Ocultar contraseña">
                    </div>

                    <label for="idrol">Rol:</label><br>
                    <select id="idrol" name="idrol" required>
                        <option value="">Seleccione un rol</option>
                        <?php
                        if (isset($_POST['nombreusuario'])) {
                            $nombre = $_POST['nombre'];
                            $apellido = $_POST['apellido'];
                            $dni = $_POST['dni'];
                            $correo = $_POST['correo'];
                            $nombreusuario = $_POST['nombreusuario'];
                            $clave = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hasheado
                            $idrol = $_POST['idrol'];

                            $sql_usuario = "SELECT * FROM usuarios WHERE nombreusuario = '$nombreusuario'";
                            $resultado_usuario = mysqli_query($con, $sql_usuario);

                            $sql_correo = "SELECT * FROM usuarios WHERE correo = '$correo'";
                            $resultado_correo = mysqli_query($con, $sql_correo);

                            $sql_dni = "SELECT * FROM usuarios WHERE dni = '$dni'";
                            $resultado_dni = mysqli_query($con, $sql_dni);

                            if (mysqli_num_rows($resultado_usuario) > 0) {
                                echo "<script> alert('El nombre de usuario ya existe. Por favor, elija otro.');</script>";
                            } elseif (mysqli_num_rows($resultado_correo) > 0) {
                                echo "<script> alert('Ya existe un usuario con este correo.');</script>";
                            } elseif (mysqli_num_rows($resultado_dni) > 0) {
                                echo "<script> alert('Ya existe un usuario con este DNI.');</script>";
                            } else {
                                $sql = "INSERT INTO usuarios (nombre, apellido, dni, correo, nombreusuario, clave, idrol) 
                                        VALUES ('$nombre', '$apellido', '$dni', '$correo', '$nombreusuario', '$clave', $idrol)";
                                if (mysqli_query($con, $sql)) {
                                    echo "<script> alert('Usuario creado exitosamente.');</script>";
                                    echo "<script> window.location='index.php';</script>";
                                } else {
                                    echo "<script> alert('Error al crear el usuario: " . mysqli_error($con) . "');</script>";
                                }
                            }
                        }

                        $sql_roles = "SELECT idrol, tipo FROM roles";
                        $resultado_roles = mysqli_query($con, $sql_roles);

                        if (mysqli_num_rows($resultado_roles) > 0) {
                            while ($r = mysqli_fetch_assoc($resultado_roles)) {
                                echo "<option value='" . $r['idrol'] . "'>" . $r['tipo'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No hay roles disponibles</option>";
                        }
                        ?>
                    </select><br><br>
                    <input type="submit" value="Crear Usuario">
                </form>
                <label for="reg-log" class="btn">¿Ya tienes cuenta? Inicia Sesión</label>
            </div>
        </div>
    </div>
</div>
