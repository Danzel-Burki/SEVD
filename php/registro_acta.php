<?php

// Consultar condiciones
$queryCondiciones = "SELECT DISTINCT condicion FROM inscripciones";
$resultCondiciones = $con->query($queryCondiciones);

// Consultar carreras
$queryCarreras = "SELECT * FROM carreras WHERE nombre != 'Pendiente'";
$resultCarreras = $con->query($queryCarreras);


?>
<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">

<script src="js/script.js"></script>
<section class="main-content">
    <div class="acta-volante">
        <h2 class="titulo">ACTA VOLANTE DE EXAMENES</h2>
        <form method="get" action="php/acta_volante.php" target="_blank"> 
            <header>
                <div class="left-section">
                    <div class="condicion">
                        <label for="condicion">Exámenes de Alumnos:</label>
                        <select name="condicion" id="condicion" required>
                            <option value="">Seleccione una condición</option>
                            <?php
                            if ($resultCondiciones->num_rows > 0) {
                                while ($row = $resultCondiciones->fetch_assoc()) {
                                    echo "<option value='" . $row['condicion'] . "'>hola" . $row['condicion'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay condiciones disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <br>
                    <div class="carrera">
                        <label for="carrera">Carrera:</label>
                        <select name="carrera" id="carrera" required>
                            <option value="">Seleccione una carrera</option>
                            <?php
                            if ($resultCarreras->num_rows > 0) {
                                while ($row = $resultCarreras->fetch_assoc()) {
                                    echo "<option value='" . $row['idcarrera'] . "'>" . $row['nombre'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay carreras disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="materia">
                        <label for="materia">Asignatura:</label>
                        <select name="materia" id="materia" required>
                            <option value="">Seleccione una carrera primero</option>
                        </select>
                    </div>
                    <br>
                    
                    <!-- Botón para buscar inscripciones -->
                    <div>
                        <input type="submit" class="buscar-inscripciones" value="Buscar inscripciones">
                        <br><br>
                    </div>
                </div>
            </header>
        </form>
    </div>
</section>
