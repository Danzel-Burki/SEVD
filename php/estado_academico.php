<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEVD</title>
    <link rel="stylesheet" href="../css/Styles_estado_academico.css">
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
                <a href="../index.php">
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
                <a href="../index.php"><i class="fas fa-home"></i> Inicio</a>
                <a href="estado_academico.php"><i class="fas fa-book-open"></i> Estado académico</a>
                <a href="inscripcion_mesas.php"><i class="fas fa-clipboard-list"></i> Inscripción a mesas</a>
            </nav>
            <label for="btn-menu">✖️</label>
        </div>
    </div>

    <main>
        <section class="academic-status">
            <h2 class="title-page">Estado Académico</h2>
            <div class="year-selection">
                <hr>
                <label for="year1">🔽Primer Año </label>
                <input type="checkbox" id="year1">
                <div class="year-content">
                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2">Materia</th>
                                <th colspan="3" class="cuatrimestre">1er Cuatrimestre</th>
                                <th colspan="2" class="cuatrimestre">2do Cuatrimestre</th>
                                <th rowspan="2">Condición</th>
                                <th rowspan="2">Estado</th>
                            </tr>
                            <tr>
                                <th>Nota 1</th>
                                <th>Nota 2</th>
                                <th>Nota 3</th>
                                <th>Nota 1</th>
                                <th>Nota 2</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="promocionado">
                                <td>Administración de las Organizaciones</td>
                                <td>10</td>
                                <td>10</td>
                                <td>9</td>
                                <td>7</td>
                                <td>7</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Introducción a los Sistemas de información</td>
                                <td>10</td>
                                <td>10</td>
                                <td>9</td>
                                <td>7</td>
                                <td>7</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="regular">
                                <td>Arquitectura de Computadoras</td>
                                <td>6</td>
                                <td>7</td>
                                <td>6</td>
                                <td>5</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="regular">
                                <td>Inglés Técnico I</td>
                                <td>4</td>
                                <td>7</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Encuadre Profesional</td>
                                <td>8</td>
                                <td>8</td>
                                <td>9</td>
                                <td>7</td>
                                <td>8</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Teología Fundamental</td>
                                <td>8</td>
                                <td>8</td>
                                <td>8</td>
                                <td>8</td>
                                <td>8</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="regular">
                                <td>Análisis Matemático I</td>
                                <td>6</td>
                                <td>7</td>
                                <td>6</td>
                                <td>5</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Metodología de la Investigación</td>
                                <td>8</td>
                                <td>8</td>
                                <td>9</td>
                                <td>7</td>
                                <td>8</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Programación I</td>
                                <td>8</td>
                                <td>9</td>
                                <td>7</td>
                                <td>8</td>
                                <td>8</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        <hr>

            <div class="year-selection">
                <label for="year2">🔽Segundo Año</label>
                <input type="checkbox" id="year2">
                <div class="year-content">
                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2">Materia</th>
                                <th colspan="3" class="cuatrimestre">1er Cuatrimestre</th>
                                <th colspan="2" class="cuatrimestre">2do Cuatrimestre</th>
                                <th rowspan="2">Condición</th>
                                <th rowspan="2">Estado</th>
                            </tr>
                            <tr>
                                <th>Nota 1</th>
                                <th>Nota 2</th>
                                <th>Nota 3</th>
                                <th>Nota 1</th>
                                <th>Nota 2</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="libre">
                                <td>Encuadre Profesional</td>
                                <td>6</td>
                                <td>6</td>
                                <td>5</td>
                                <td>7</td>
                                <td>7</td>
                                <td>libre</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Probabilidad y estadística</td>
                                <td>10</td>
                                <td>10</td>
                                <td>9</td>
                                <td>7</td>
                                <td>7</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="regular">
                                <td>Inglés Técnico II</td>
                                <td>6</td>
                                <td>7</td>
                                <td>6</td>
                                <td>5</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="libre">
                                <td>Sistemas de Información I</td>
                                <td>6</td>
                                <td>7</td>
                                <td>6</td>
                                <td>5</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>---</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Base de Datos</td>
                                <td>8</td>
                                <td>8</td>
                                <td>9</td>
                                <td>7</td>
                                <td>8</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Sistemas Operativos</td>
                                <td>9</td>
                                <td>9</td>
                                <td>9</td>
                                <td>10</td>
                                <td>9</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="regular">
                                <td>Análisis Matemático II</td>
                                <td>6</td>
                                <td>7</td>
                                <td>6</td>
                                <td>5</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Programación II</td>
                                <td>8</td>
                                <td>9</td>
                                <td>7</td>
                                <td>8</td>
                                <td>8</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <div class="year-selection">
                <label for="year3">🔽Tercer Año</label>
                <input type="checkbox" id="year3">
                <div class="year-content">
                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2">Materia</th>
                                <th colspan="3" class="cuatrimestre">1er Cuatrimestre</th>
                                <th colspan="2" class="cuatrimestre">2do Cuatrimestre</th>
                                <th rowspan="2">Condición</th>
                                <th rowspan="2">Estado</th>
                            </tr>
                            <tr>
                                <th>Nota 1</th>
                                <th>Nota 2</th>
                                <th>Nota 3</th>
                                <th>Nota 1</th>
                                <th>Nota 2</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="promocionado">
                                <td>Telenformática</td>
                                <td>7</td>
                                <td>7</td>
                                <td>9</td>
                                <td>9</td>
                                <td>8</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="regular">
                                <td>Encuadre Institucional</td>
                                <td>6</td>
                                <td>6</td>
                                <td>9</td>
                                <td>7</td>
                                <td>7</td>
                                <td>Regular</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="libre">
                                <td>Investigación Operativa</td>
                                <td>6</td>
                                <td>7</td>
                                <td>6</td>
                                <td>5</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="regular">
                                <td>Sistemas de Información II</td>
                                <td>6</td>
                                <td>6</td>
                                <td>6</td>
                                <td>6</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>---</td>
                            </tr>

                            <tr class="promocionado">
                                <td>Economía Empresarial</td>
                                <td>9</td>
                                <td>9</td>
                                <td>9</td>
                                <td>8</td>
                                <td>8</td>
                                <td>Promocionado</td>
                                <td>Aprobado</td>
                            </tr>

                            <tr class="regular">
                                <td>Práctica Profesional</td>
                                <td>6</td>
                                <td>7</td>
                                <td>6</td>
                                <td>5</td>
                                <td>6</td>
                                <td>Regular</td>
                                <td>Aprobado</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="contact-info">
                <h2>Comunidad</h2>
                <p>© 2024 Diseño y Desarrollo | Instituto Verbo Divino</p>
                <p><i class="fas fa-globe"></i> <a
                        href="https://isvd.com.ar/verbo-solidario/">https://isvd.com.ar/verbo-solidario/</a> | Todos los
                    derechos reservados</p>
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