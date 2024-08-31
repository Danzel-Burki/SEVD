<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEVD</title>
    <link rel="stylesheet" href="CSS/Styles_inscripcion_mesas.css">
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
                <a href="index.php"><i class="fas fa-home"></i> Inicio</a>
                <a href="estado_academico.php"><i class="fas fa-book-open"></i> Estado académico</a>
                <a href="inscripcion_mesas.php"><i class="fas fa-clipboard-list"></i> Inscripción a mesas</a>
            </nav>
            <label for="btn-menu">✖️</label>
        </div>
    </div>

    <main>
        <section class="main-content">
            <h2>Inscripción a Mesas</h2>
            <form action="#" method="POST" class="inscription-form">
                <label for="Nombre">Nombre:</label>
                <input type="text" id="student-name" name="student-name" required>

                <label for="DNI">DNI:</label>
                <input type="text" id="student-dni" name="student-dni" required>

                <label for="Tecnicatura">Selecciona tu tecnicatura:</label>
                <select>
                    <option value="TEC. SUP. EN BIOSEGURIDAD, HIG. Y SEG.">TEC. SUP. EN BIOSEGURIDAD, HIG. Y SEG.
                    </option>
                    <option value="TEC. SUP. EN ADM. Y GESTION DE LAS EMPRESAS">TEC. SUP. EN ADM. Y GESTION DE LAS
                        EMPRESAS</option>
                    <option value="TEC. SUP. EN ANALISIS DE SISTEMAS">TEC. SUP. EN ANALISIS DE SISTEMAS</option>
                </select>

                <label for="Gmail">Gmail:</label>
                <input type="email" placeholder="example@gmail.com" required>

                <label for="Selecciona las measas a rendir">Selecciona las measas a rendir:</label>
                <select>
                    <option value="Probabilidad y estadística">Probabilidad y estadística</option>
                    <option value="Inglés técnico">Inglés técnico</option>
                    <option value="Álgebra">Álgebra</option>
                    <option value="Base de datos">Base de datos</option>
                    <option value="Análisis matemático">Análisis matemático</option>
                    <option value="ProgramaciónI">ProgramaciónI</option>
                    <option value="ProgramaciónII">ProgramaciónII</option>
                </select>

                <button type="submit">Enviar</button>
            </form>

            <div class="course-list">
                <h3>Materias disponibles:</h3>
                <ul>
                    <li>Probabilidad y estadística - 10/07/2024</li>
                    <li>Inglés técnico - 12/07/2024</li>
                    <li>Álgebra - 16/07/2024</li>
                    <li>Base de datos - 18/07/2024</li>
                    <li>Análisis matemático - 20/07/2024</li>
                    <li>ProgramaciónI - 22/07/2024</li>
                    <li>ProgramaciónII - 24/07/2024</li>
                </ul>
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