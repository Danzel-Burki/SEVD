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

                // Usar una sentencia preparada para obtener el nombre de usuario y rol
                $query = "
                    SELECT u.nombreusuario, u.idrol, r.tipo 
                    FROM usuarios u 
                    JOIN roles r ON u.idrol = r.idrol 
                    WHERE u.idusuario = ?";
                
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt, "i", $idusuario);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $nombreusuario, $idrol, $rol);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);

                    echo '
                    <input type="checkbox" id="dropdown-toggle" hidden>
                    <label for="dropdown-toggle" class="perfil-icon" title="Perfil del usuario">
                        <i class="fas fa-user-cog"></i>
                    </label>
                    <div class="dropdown-content">
                        <p><b>Usuario:</b> ' . htmlspecialchars($nombreusuario) . '</p>
                        <p><b>Rol:</b> ' . htmlspecialchars($rol) . '</p>';

                    // Verificar si el usuario es un Estudiante (idrol = 1)
                    if ($idrol == 1) {
                        // Consulta para obtener la carrera del estudiante
                        $queryCarrera = "
                            SELECT c.nombre 
                            FROM carreras c
                            JOIN estudiantes e ON c.idcarrera = e.idcarrera
                            WHERE e.idusuario = ?";
                        
                        if ($stmtCarrera = mysqli_prepare($con, $queryCarrera)) {
                            mysqli_stmt_bind_param($stmtCarrera, "i", $idusuario);
                            mysqli_stmt_execute($stmtCarrera);
                            mysqli_stmt_bind_result($stmtCarrera, $carrera);
                            if (mysqli_stmt_fetch($stmtCarrera)) {
                                echo '<p><b>Carrera:</b> ' . htmlspecialchars($carrera) . '</p>';
                            } else {
                                echo '<p><b>Carrera:</b> No asignada</p>';
                            }
                            mysqli_stmt_close($stmtCarrera);
                        }
                    }

                    
                    echo '<a href="index.php?modulo=editar_usuario&idusuario=' . $idusuario . '" class="editar-btn">Editar usuario</a>';
                    

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
