<?php
session_start();
include("../includes/conexion.php");
conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/inicio_sesion.css">
    <title>Iniciar Sesión y Registro</title>
</head>
<body>
<div class="form-container">
    <div class="formulario-sesion">
        <h2>Inicio de Sesión</h2>
            <form action="inicio_sesion.php" method="post">
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
            if  (isset($_POST['username'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                //consulta a la bd
                $sql="SELECT * FROM usuarios WHERE nombreusuario ='".$username."' AND clave = '".$password."'";
                $sql = mysqli_query($con, $sql);//ejecuto la consulta
                if(mysqli_num_rows($sql) != 0)//pregunto si tiene datos
                {
                    while ($r = mysqli_fetch_array($sql))//recorro todos los registros
                    {
                        echo "bienvenido:  ".$r['nombre'];//mostrar un registro
                        $_SESSION['idusuario']=$r['idusuario'];
                        echo $_SESSION['idusuario'];

                    }
                }
                else //sin datos, aviso que no hay registros
                {
                echo "SIN DATOS";
                }
            }
            //cerrar sesion 
            if(isset($_GET['salir']) && $_GET['salir']=='ok')
                session_destroy();
            // controlar si hay sesion iniciada
            if (!empty($_SESSION['idusuario']))
            {
                echo '<a href="inicio_sesion.php?salir=ok">SALIR</a>';
            }
            
        ?> 
    </div>
    <div class="formulario-registro">
        <h2>Crear Usuario</h2>
        <form method="post" action="inicio_sesion.php">
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
                        echo "Usuario creado exitosamente.";
                    } else {
                        echo "Error al crear el usuario: " . mysqli_error($con);
                    }
                } else {
                    echo "El nombre de usuario ya existe. Por favor, elija otro.";
                }
            }
        ?>
    </div>
</div>
    <!-- Tabla dinámica de usuarios -->
    <h2>Lista de Usuarios</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Nombre de Usuario</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_usuarios = "SELECT u.idusuario, u.nombre, u.apellido, u.dni, u.correo, u.nombreusuario, r.tipo as rol 
                                    FROM usuarios u
                                    JOIN roles r ON u.idrol = r.idrol";
                    $resultado_usuarios = mysqli_query($con, $sql_usuarios);

                    if ($resultado_usuarios && mysqli_num_rows($resultado_usuarios) > 0) {
                        while ($row = mysqli_fetch_assoc($resultado_usuarios)) {
                            echo "<tr>";
                            echo "<td>" . $row['idusuario'] . "</td>";
                            echo "<td>" . $row['nombre'] . "</td>";
                            echo "<td>" . $row['apellido'] . "</td>";
                            echo "<td>" . $row['dni'] . "</td>";
                            echo "<td>" . $row['correo'] . "</td>";
                            echo "<td>" . $row['nombreusuario'] . "</td>";
                            echo "<td>" . $row['rol'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay usuarios registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
</body>
</html>
     