<section class="info-section">
    <div class="container">
    <?php 
        // Verificar si el usuario está logueado 
        if (isset($_SESSION['idusuario'])) { 
            // Obtener el ID del usuario logueado desde la sesión 
            $idusuario = $_SESSION['idusuario']; 
 
            // Consulta para obtener el rol del usuario 
            $sql = "SELECT p.nombre, p.modulo, p.icono, p.descripcion
                    FROM usuarios u
                    INNER JOIN roles r ON u.idrol = r.idrol
                    INNER JOIN roles_permisos rp on r.idrol = rp.idrol
                    INNER JOIN permisos p on rp.idpermiso = p.idpermiso
                    WHERE u.idusuario = $idusuario
                    UNION ALL
                    SELECT p.nombre, p.modulo, p.icono, p.descripcion
                    FROM usuarios u
                    INNER JOIN usuarios_permisos up on u.idusuario = up.idusuario
                    INNER JOIN permisos p on up.idpermiso = p.idpermiso
                    WHERE u.idusuario = $idusuario";
 
            $resultado = mysqli_query($con, $sql);  // Se usa $con que ya está en la conexión previamente establecida  
            if (mysqli_num_rows($resultado) > 0) { 
                while ($r=mysqli_fetch_array($resultado)) 
                { 
                    ?> 
                    <a href="index.php?modulo=<?php echo $r['modulo'];?>" class="info-box-link">
                        <div class="info-box">
                            <i class="<?php echo $r['icono'];?>"></i>
                            <h3><?php echo $r['nombre'];?></h3>
                            <p><?php echo $r['descripcion'];?></p>
                        </div>
                    </a> 
                    <?php 
                } 
            }  
        }  
        ?> 
    </div>
</section>