<input type="checkbox" id="btn-menu">
<label for="btn-menu" class="btn-menu"><span>☰</span></label>
<link rel="stylesheet" href="css/styles_index.css">

<!-- Contenedor principal del menú lateral -->
<div class="container-menu">
    <div class="cont-menu">
        <!-- Botón para cerrar el menú -->
        <label for="btn-menu" class="boton-cerrar">✖</label>

        <?php
        // Verificar si el usuario está logueado
        if (isset($_SESSION['idusuario'])) {
            $idusuario = $_SESSION['idusuario'];

            // Consulta para obtener el rol del usuario
            $sql = "SELECT p.nombre, p.modulo, p.icono
                    FROM usuarios u
                    INNER JOIN roles r ON u.idrol = r.idrol
                    INNER JOIN roles_permisos rp on r.idrol = rp.idrol
                    INNER JOIN permisos p on rp.idpermiso = p.idpermiso
                    WHERE u.idusuario = $idusuario
                    UNION ALL
                    SELECT p.nombre, p.modulo, p.icono
                    FROM usuarios u
                    INNER JOIN usuarios_permisos up on u.idusuario = up.idusuario
                    INNER JOIN permisos p on up.idpermiso = p.idpermiso
                    WHERE u.idusuario = $idusuario";

            $resultado = mysqli_query($con, $sql);
            echo "<nav><ul>";
            echo "<li><a href='index.php'><i class='fas fa-house-user'></i> Inicio</a></li>";
            if (mysqli_num_rows($resultado) > 0) {
                while ($r=mysqli_fetch_array($resultado)) {
                    ?>
                    <li><a href="index.php?modulo=<?php echo $r['modulo'];?>"><i class="<?php echo $r['icono'];?>"></i><?php echo $r['nombre'];?></a></li>
                    <?php
                }
            }
            echo "</ul></nav>";
        } 
        ?>
    </div>
</div>
