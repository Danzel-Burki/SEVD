<?php

require_once '../librerias/mpdf/vendor/autoload.php';

// Define el tamaño de página 
$mpdf = new \Mpdf\Mpdf([
    'format' => 'Legal',
    'margin_left' => 0.5,
    'margin_right' => 0.5,
    'margin_top' => 0.5,
    'margin_bottom' => 0.5,
    'margin_header' => 0.5,
    'margin_footer' => 0.5,
]);


$mpdf->WriteHTML('
<link rel="stylesheet" href="../css/acta_volante.css">

<div class="acta-volante">
    <h1 class="titulo">ACTA VOLANTE DE EXAMENES</h1>
    <header>
        <div class="left-section">
            <div>
                <label>Establecimiento: Instituto Superior Verbo Divino</label> <span class="establecimiento"></span><br><br>
            </div>
            <div>
                <label>Exámenes de Alumnos:</label><br><br>
            </div>
            <div>
                <label>Asignatura:</label> <span class="asignatura"></span><br><br>
            </div>
        </div>
        <div class="right-section">
            <div class="header-row">
                <div>
                    <span>Día: <input type="text" class="fecha" size="2"> Mes: <input type="text" class="fecha" size="2"> Año: <input type="text" class="fecha" size="4"></span>
                </div>
            </div>
            <div class="header-row">
                <div>
                    <span>Año: <input type="text" size="2"> Div: <input type="text" size="2"> Turno: <input type="text" size="6"></span>
                    
                </div>
            </div>
        </div>
    </header>

    <table class="tabla-examen">
        <thead>
            <tr>
                <th rowspan="2">N° de Orden</th>
                <th rowspan="2">N° del Permiso</th>
                <th rowspan="2">Apellido y Nombres</th>
                <th colspan="3">Calificaciones</th>
                <th colspan="2">N° de las Bolillas</th>
                <th rowspan="2">Documento de Identidad</th>
            </tr>
            <tr>
                <th>Esc.</th>
                <th>Oral</th>
                <th>Prom.</th>
                <th>Esc.</th>
                <th>Oral</th>
            </tr>
        </thead>
        <tbody>
            <!-- Filas de estudiantes -->
            <tr>
                <td>1</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <footer>
        <div class="footer-section">
            <div class="footer-left">
                <div class="left-group">
                    <div class="signature">
                        <label>Presidente:______________________</label> 
                        <br><br>
                    </div>
                    <div class="signature">
                        <label>Vocal:______________________</label> 
                    </div>
                </div>
                
                <div class="footer-date">
                    <span>______ de ______________ de 20____</span>
                </div>
            </div>

            <div class="footer-right">
                <div class="summary-item">
                    <label>Vocal:</label> <input type="text" name="vocal" />
                </div>
                <div class="summary-item">
                    <label>Total de alumnos:</label> <input type="text" name="total_alumnos" />
                </div>
                <div class="summary-item">
                    <label>Aprobados:</label> <input type="text" name="aprobados" />
                </div>
                <div class="summary-item">
                    <label>Aplazados:</label> <input type="text" name="aplazados" />
                </div>
                <div class="summary-item">
                    <label>Ausentes:</label> <input type="text" name="ausentes" />
                </div>
            </div>
        </div>
    </footer>

                
</div>
');

$mpdf->Output("acta_volante", "I");
