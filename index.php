<?php
session_start();
include("includes/conexion.php");
conectar();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEVD</title>
    <link rel="stylesheet" href="css/Styles_index.css">
    <link rel="stylesheet" href="css/inicio_sesion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="img/Logo_ISVD.png" type="image/png">
</head>

<body>
    
    <?php 
    include('php/header.php');   
    include('php/menu.php'); 
    ?>

    <main>
        <section class="welcome-section">
            <div class="container">
                <div class="welcome-content">
                    <h2>Bienvenido al Sistema Educativo Verbo Divino</h2>
                    <p>Inicia sesión o crea una cuenta para acceder al contenido de la página. Podrás estar al tanto del estado de tus materias actuales, incluyendo tus notas y condiciones.
                        Acceder al apartado de inscripción a materias para matricularte en las materias del ciclo.</p>
                </div>
            </div>
        </section>
        <?php 
            // Controlo si viene algún módulo para cargar
            if (!empty($_GET['modulo'])) {
                include('php/' . addslashes($_GET['modulo']) . '.php');
            } else {
                // Controlo si tiene una sesión iniciada
                if (empty($_SESSION['idusuario'])) {
                    // Cargo el login si no está autenticado
                    include('php/inicio_sesion.php');
                } else {
                    // Dependiendo del rol, mostrar opciones personalizadas
                    if (isset($_SESSION['idrol'])) {
                        include('php/menu_inicio.php');
                    } else {
                        echo "Tipo de usuario no definido en la sesión.";
                    }
                }
            }
        ?>
    </main>

    <?php include('php/footer.php')?>
    
</body>

</html>