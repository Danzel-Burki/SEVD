<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tec. Sup. en Análisis de Sistemas - Plan de Estudios</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@400;700&display=swap">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="#">
                    <h1>Plan de Estudios</h1>
                </a>
            </div>
            <nav class="menu">
                <a href="#">Inicio</a>
                <a href="#">Opciones</a>
                <a href="index.html">Volver al Panel</a>
            </nav>
        </div>
    </header>

    <!-- Contenido principal -->
    <main>
        <section class="welcome-section">
            <div class="container">
                <h2>Tec. Sup. en Análisis de Sistemas</h2>
                <p>Aquí puedes gestionar el plan de estudio para la carrera.</p>
            </div>
        </section>

        <!-- Formulario para cargar plan de estudios -->
        <section class="info-section">
            <div class="container">
                <form action="procesar_plan_sistemas.php" method="POST">
                    <div class="info-box">
                        <h3>Cargar nuevo plan de estudio</h3>
                        <label for="nombre-plan">Nombre del Plan:</label>
                        <input type="text" id="nombre-plan" name="nombre-plan" required>
                        
                        <label for="descripcion-plan">Descripción:</label>
                        <textarea id="descripcion-plan" name="descripcion-plan" rows="4" required></textarea>
                        
                        <button type="submit">Cargar Plan</button>
                    </div>
                </form>
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
