
    <link rel="stylesheet" href="../css/Styles_inscripcion_mesas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="img/Logo_ISVD.png" type="image/png">
</head>

<body>
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
</body>

</html>