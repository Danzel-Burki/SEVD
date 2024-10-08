<header class="header">
    <div class="container">

        <div class="btn-menu">
            <?php
            if (isset($_SESSION['idrol'])) {
                echo '<label for="btn-menu">☰</label>';
            }
            ?>
        </div>
        <div class="logo">
            <a href="index.php">
                <h1>SEVD</h1>
                <img src="img/Logo_ISVD.png" alt="Logo del Instituto" width="40">
            </a>
        </div>
        <div class="menu">
        <?php
            if (isset($_SESSION['idusuario'])) {
                $idusuario = $_SESSION['idusuario'];
                
                // Consulta inicial para obtener el nombre de usuario y rol
                $query = "
                    SELECT u.nombreusuario, u.idrol, r.tipo 
                    FROM usuarios u 
                    JOIN roles r ON u.idrol = r.idrol 
                    WHERE u.idusuario = '$idusuario'";
                
                $resultado = mysqli_query($con, $query);

                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    $row = mysqli_fetch_assoc($resultado);
                    $nombreusuario = $row['nombreusuario'];
                    $rol = $row['tipo'];

                    echo '
                    <input type="checkbox" id="dropdown-toggle" hidden>
                    <label for="dropdown-toggle" class="perfil-icon" title="Perfil del usuario">
                        <i class="fas fa-user-cog"></i>
                    </label>
                    <div class="dropdown-content">
                        <p><b>Usuario:</b> ' . $nombreusuario . '</p>
                        <p><b>Rol:</b> ' . $rol . '</p>';

                    // Verificar si el usuario es un Estudiante (idrol = 1)
                    if ($row['idrol'] == 1) {
                        // Realizar la consulta para obtener el nombre de la carrera
                        $queryCarrera = "
                            SELECT c.nombre 
                            FROM carreras c
                            JOIN estudiantes e ON c.idcarrera = e.idcarrera
                            WHERE e.idusuario = '$idusuario'";
                        
                        $resultadoCarrera = mysqli_query($con, $queryCarrera);
                        
                        if ($resultadoCarrera && mysqli_num_rows($resultadoCarrera) > 0) {
                            $rowCarrera = mysqli_fetch_assoc($resultadoCarrera);
                            $carrera = $rowCarrera['nombre'];

                            echo '<p><b>Carrera:</b> ' . $carrera . '</p>';
                        } else {
                            echo '<p><b>Carrera:</b> No asignada</p>';
                        }
                    }

                    // Añadir botón de cerrar sesión
                    echo '<a href="index.php?salir=ok" class="logout-btn">Cerrar sesión</a>';

                    echo '</div>';
                } else {
                    echo '
                    <input type="checkbox" id="dropdown-toggle" hidden>
                    <label for="dropdown-toggle" class="perfil-icon" title="Perfil del usuario">
                        <i class="fas fa-user-cog"></i>
                    </label>
                    <div class="dropdown-content">
                        <p>Usuario: Usuario</p>
                        <p>Rol: Desconocido</p>
                        <a href="index.php?salir=ok" class="logout-btn">Cerrar sesión</a>
                    </div>';
                }
            }

            // Lógica para cerrar sesión
            if (isset($_GET['salir']) && $_GET['salir'] == 'ok') {   
                session_destroy();
                echo "<script> alert('Sesión cerrada exitosamente.');</script>";
                echo "<script> window.location='index.php';</script>";
            }
        ?>
        </div>
    </div>
</header>
