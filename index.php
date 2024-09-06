<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEVD</title>
    <link rel="stylesheet" href="css/Styles_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="img/Logo_ISVD.png" type="image/png">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="btn-menu">
                <label for="btn-menu">☰</label>
            </div>
            <div class="logo">
                <a href="index.php">
                    <h1>SEVD</h1>
                    <img src="img/Logo_ISVD.png" alt="Logo del Instituto" width="40">
                </a>
            </div>
            <div class="menu">
                <a href="#" title="Notificaciones"><i class="fas fa-bell"></i></a>
                <a href="#" title="Perfil del usuario"><i class="fas fa-user-cog"></i></a>
            </div>
        </div>
    </header>

    <input type="checkbox" id="btn-menu">
    <div class="container-menu">
        <div class="cont-menu">
            <nav>
                <a href="php/inscripcion_mesas.php"><i class="fas fa-clipboard-list"></i> Inscripción a mesas</a>
                <a href="php/estado_academico.php"><i class="fas fa-book-open"></i>Estado Académico</a>
            </nav>
            <label for="btn-menu">✖️</label>
        </div>
    </div>

    <main>
        <section class="welcome-section">
            <div class="container">
                <div class="welcome-content">
                    <h2>Bienvenido a nuestro Sistema Educativo Verbo Divino</h2>
                    <p>Podrás estar al tanto del estado de tus materias actuales, incluyendo tus notas y condiciones.
                        Acceder al apartado de inscripción a materias para matricularte en las materias del ciclo.</p>
                </div>
            </div>
        </section>

        <section class="info-section">
            <div class="container">
                <a href="php/inscripcion_mesas.php" class="info-box-link">
                    <div class="info-box">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Inscripción a mesas</h3>
                        <p>Accede al portal para inscribirte en las materias del próximo semestre.</p>
                    </div>
                </a>

                <a href="php/estado_academico.php" class="info-box-link">
                    <div class="info-box">
                        <i class="fas fa-book-open"></i>
                        <h3>Estado Académico</h3>
                        <p>Accede a tus notas de cada materia cursada y condición a materias.</p>
                    </div>
                </a>

            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="contact-info">
                <h2>Comunidad</h2>
                <p>© 2024 Diseño y Desarrollo | Instituto Verbo Divino</p>
                <p><i class="fas fa-globe"></i> <a
                        href="https://isvd.com.ar/verbo-solidario/">https://isvd.com.ar/verbo-solidario/</a> | Todos los
                    derechos reSEVDados</p>
            </div>

            <div class="contact-info">
                <h2>Contactos</h2>
                <p><i class="fas fa-phone-alt"></i> Tel: 4465962</p>
                <p><i class="fas fa-envelope"></i> INSTITUTOVERBODIVINO@HOTMAIL.COM</p>
                <p><i class="fas fa-map-marker-alt"></i> AVDA. TAMBOR de TACUARI VILLA CABELLO AVDA. TAMBOR de TACUARI Y
                    PADRE KOLPING</p>
            </div>
        </div>
    </footer>
</body>

</html>