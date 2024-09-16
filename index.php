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
    
    <?php include('php/header.php')?>    
    <?php include('php/menu.php')?>

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
        //Controlo si viene algun módulo para cargar
        if(!empty($_GET['modulo']))
            {
                include('php/'. addslashes($_GET['modulo']).'.php');
            }
            else
            {
                //controlo si tiene una sesión iniciada
                if(empty($_SESSION['idusuario']))
                    //cargo el login
                    include('php/inicio_sesion.php');
                else
                    //muestro las opciones
                    include('php/opciones.php');
                    //echo $_SESSION['idusuario'];
            }
        ?>
    </main>

    <?php include('php/footer.php')?>
    
</body>

</html>