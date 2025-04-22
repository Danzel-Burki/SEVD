<?php
// Evitar iniciar sesión si ya está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="css/estilo_general.css">
<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">

<div class="contenedor_contenido">
    <section class="main-content" id="consulta_plan">
        <h2>Consultar Plan de Estudio</h2>
        <div class="form-group">
            <form action="index.php?modulo=consulta_plan_estudio" method="POST" class="inscription-form">
                <input type="hidden" name="accion" value="mostrar_plan">
                <select name="carrera" id="carrera" required>
                    <option value="">Seleccione una carrera</option>
                    <?php
                    $sql_carreras = "SELECT idcarrera, nombre FROM carreras WHERE nombre != 'Pendiente'";
                    $resultado_carreras = mysqli_query($con, $sql_carreras);
                    while ($fila = mysqli_fetch_assoc($resultado_carreras)) {
                        echo "<option value='" . $fila['idcarrera'] . "'>" . $fila['nombre'] . "</option>";
                    }
                    ?>
                </select>
                <button type="submit">Cargar plan de estudio</button><br>
            </form>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["accion"] == "mostrar_plan") {
            if (!empty($_POST['carrera'])) {
                $archivo = consultar_plan_estudio($_POST['carrera']);

                if ($archivo) {
                    $ruta_archivo = "documentos/planes-estudio/" . htmlspecialchars($archivo);

                    if (file_exists($ruta_archivo)) {
                        ?>

                        <div id="pdf-container" style="display: block;">
                            <button class="btn-small" onclick="abrirEnNuevaVentana('<?php echo $ruta_archivo; ?>'); return false;">
                                <i class="fas fa-external-link-alt"></i> Abrir en nueva ventana
                            </button>
                            <button class="btn-small" onclick="cerrarPdf(); return false;">
                                <i class="fas fa-times"></i> Cerrar PDF
                            </button>
                            <embed src="<?php echo $ruta_archivo; ?>" width="1270" height="700" type="application/pdf">
                        </div>

                        <?php
                    } else {
                        echo "<p>El archivo no se encuentra disponible.</p>";
                    }
                } else {
                    echo "<h2>No se encontró el plan de estudio.</h2>";
                }
            } else {
                echo "<h2>Por favor, seleccione una carrera.</h2>";
            }
        }
        ?>

    </section>

    <script>
        function abrirEnNuevaVentana(url) {
            window.open(url, '_blank');
        }

        function cerrarPdf() {
            document.getElementById('pdf-container').style.display = 'none';
        }
    </script>

</div>

<?php
// Función para consultar el plan de estudio
function consultar_plan_estudio($carreraId)
{
    global $con;  // Usar la conexión ya abierta
    $sql = "SELECT planestudiocarrera 
            FROM carreras 
            WHERE idcarrera = $carreraId";

    $resultado = $con->query($sql);

    if ($resultado && $fila = $resultado->fetch_assoc()) {
        return $fila['planestudiocarrera'];
    } else {
        return null;
    }
}
?>
