<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - Carreras</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@400;700&display=swap">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="#">
                    <h1>Admin Panel</h1>
                </a>
            </div>
            <nav class="menu">
                <a href="#">Inicio</a>
                <a href="#">Opciones</a>
                <a href="#">Cerrar Sesión</a>
            </nav>
        </div>
    </header>

    <!-- Menú lateral -->
    <input type="checkbox" id="btn-menu">
    <div class="container-menu">
        <div class="cont-menu">
            <nav>
                <a href="#"><i class="icon-dashboard"></i> Dashboard</a>
                <a href="#"><i class="icon-users"></i> Usuarios</a>
                <a href="#"><i class="icon-studies"></i> Plan de Estudio</a>
            </nav>
        </div>
    </div>

    <!-- Contenido principal -->
    <main>
        <section class="welcome-section">
            <div class="container">
                <h2>Bienvenido al Panel Administrativo</h2>
                <p>Elige una carrera para gestionar su plan de estudios.</p>
            </div>
        </section>

        <!-- Sección para las carreras -->
        <section class="info-section">
            <div class="container">
                <!-- Módulo 1: Tec. Sup. en Análisis de Sistemas -->
                <a href="cargar_sistemas.html">
                    <div class="info-box">
                        <i class="icon-upload"></i>
                        <h3>TEC. SUP. EN ANÁLISIS DE SISTEMAS</h3>
                        <p>Gestionar el plan de estudio de Análisis de Sistemas.</p>
                    </div>
                </a>

                <!-- Módulo 2: Tec. Sup. en Administración y Gestión de Empresas -->
                <a href="cargar_empresas.html">
                    <div class="info-box">
                        <i class="icon-upload"></i>
                        <h3>TEC. SUP. EN ADM. Y GESTIÓN DE EMPRESAS</h3>
                        <p>Gestionar el plan de estudio de Administración y Gestión de Empresas.</p>
                    </div>
                </a>

                <!-- Módulo 3: Tec. Sup. en Bioseguridad, Higiene y Seguridad -->
                <a href="cargar_bioseguridad.html">
                    <div class="info-box">
                        <i class="icon-upload"></i>
                        <h3>TEC. SUP. EN BIOSEGURIDAD, HIG. Y SEG.</h3>
                        <p>Gestionar el plan de estudio de Bioseguridad, Higiene y Seguridad.</p>
                    </div>
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>Administración © 2024</p>
        </div>
    </footer>
</body>
</html>




