
<link rel="stylesheet" href="../css/inicio_sesion.css">


<div class="form-container">
    <!-- Checkbox oculto para cambiar entre formularios -->
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
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                    <br>
                    <input type="submit" value="Iniciar sesión">
                </form>
                <?php
                    // Lógica para iniciar sesión
                    if (isset($_POST['username'])) {
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                        // Consulta a la BD
                        $sql = "SELECT * FROM usuarios WHERE nombreusuario = '$username' AND clave = '$password'";
                        $sql = mysqli_query($con, $sql); // Ejecuto la consulta
                        if (mysqli_num_rows($sql) != 0) { // Pregunto si tiene datos
                            while ($r = mysqli_fetch_array($sql)) { // Recorro todos los registros
                                echo "Bienvenido: " . $r['nombre']; // Mostrar un registro
                                $_SESSION['idusuario'] = $r['idusuario'];
                                $_SESSION['idrol'] = $r['idrol']; // Guardar el rol en la sesión
                                $_SESSION['nombrecarrera'] = $r['nombre']; // Guardar el rol en la sesión
                                echo "<script> window.location='index.php';</script>";
                            }
                        } else { // Sin datos, aviso que no hay registros
                            echo "Nombre de usuario o contraseña incorrectos.";
                        }
                    }

                ?> 
                <!-- Botón para cambiar al formulario de registro -->
                <label for="reg-log" class="btn">¿No tienes cuenta? Regístrate</label>
            </div>

            <!-- Formulario de Registro de Usuario -->
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

                    <label for="clave">Contraseña:</label><br>
                    <input type="password" id="clave" name="clave" required><br>

                    <label for="idrol">Rol:</label><br>
                    <select id="idrol" name="idrol" required>
                        <option value="">Seleccione un rol</option>
                        <?php
                        // Lógica para crear un nuevo usuario
                                if (isset($_POST['nombreusuario'])) {
                                    $nombre = $_POST['nombre'];
                                    $apellido = $_POST['apellido'];
                                    $dni = $_POST['dni'];
                                    $correo = $_POST['correo'];
                                    $nombreusuario = $_POST['nombreusuario'];
                                    $clave = $_POST['clave'];
                                    $idrol = $_POST['idrol'];

                                    // Verificar si el nombre de usuario ya existe en la base de datos
                                    $sql = "SELECT * FROM usuarios WHERE nombreusuario = '".$nombreusuario."'";
                                    $resultado = mysqli_query($con, $sql); // Se asume que $con es la conexión establecida previamente

                                    if (mysqli_num_rows($resultado) == 0) {
                                        // Si no existe, insertar el nuevo usuario en la base de datos
                                        $sql = "INSERT INTO usuarios (nombre, apellido, dni, correo, nombreusuario, clave, idrol) 
                                                VALUES ('$nombre', '$apellido', '$dni', '$correo', '$nombreusuario', '$clave', $idrol)";

                                        if (mysqli_query($con, $sql)) {
                                            
                                            echo "<script> alert('Usuario creado exitosamente.');</script>";
                                            echo "<script> window.location='index.php';</script>";
                                        } else {
                                            echo "<script> alert('Error al crear el usuario: ".mysqli_error($con)."');</script>";  
                                        }
                                    } else {
                                        echo "<script> alert('El nombre de usuario ya existe. Por favor, elija otro.');</script>";
                                    }
                                }
                            ?>
                        <?php
                            // Consulta SQL para obtener los roles desde la base de datos
                            $sql_roles = "SELECT idrol, tipo FROM roles";
                            $resultado_roles = mysqli_query($con, $sql_roles); // $con es la conexión establecida previamente

                            if (mysqli_num_rows($resultado_roles) > 0) {
                                // Si hay roles en la base de datos, los mostramos en el select
                                while ($r = mysqli_fetch_assoc($resultado_roles)) {
                                    echo "<option value='" . $r['idrol'] . "'>" . $r['tipo'] . "</option>";
                                }
                            } else {
                                // Si no hay roles en la base de datos, mostramos un mensaje
                                echo "<option value=''>No hay roles disponibles</option>";
                            }
                        ?>
                    </select><br><br>
                    <input type="submit" value="Crear Usuario">
                </form>
                <!-- Botón para cambiar al formulario de inicio de sesión -->
                <label for="reg-log" class="btn">¿Ya tienes cuenta? Inicia Sesión</label>
            </div>
        </div>
    </div>
</div>
