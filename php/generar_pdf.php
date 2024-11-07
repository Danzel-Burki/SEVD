<?php
// Incluir la librería mPDF
require_once('../librerias/mpdf/vendor/autoload.php'); // Asegúrate de que la ruta es correcta

use Mpdf\Mpdf;

try {
    // Crear una instancia de mPDF con tamaño A4 y márgenes ajustados
    $mpdf = new Mpdf([
        'format' => 'A4', // Tamaño de página A4
    ]);

    // Establecer márgenes (opcional)
    $mpdf->SetMargins(5, 5, 5); // Márgenes de 15mm en todos los lados

    // Definir los estilos que se aplicarán al PDF
    $css = "
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding: 5px;
        text-align: center;
    }

    /* Asegurar que los elementos no se deformen */
    .container {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }
    footer {
    margin-top: 20px; /* Espaciado superior del footer */
    border-top: 1px solid #000; /* Línea superior del footer */
    padding: 1% 0 10% 0; /* Espaciado interno superior */
}

    .footer-section {
        display: flex; /* Usar flexbox para las secciones del footer */
        justify-content: space-between; /* Espaciado entre secciones */
}

    .footer-left {
        float: left;
        width: 60%; 
        text-align: left;
        padding: 2% 0 0 1%;
}
    .signature{
        padding-top: 2%;
}

    .footer-right {
        float: right; 
        width: 35%; 
        text-align: left;
        padding: 2% 0 0 0;
}

    .summary-item {
        margin-bottom: 4%; /* Espaciado entre elementos del resumen */
}

    .footer-date {
        margin-top: 5%;
}
    /* Estilo para ocultar el botón de generar PDF en el PDF */
    #boton-pdf, .btn-generar-pdf {
        display: none;
}
";

    // Incluir el archivo que deseas convertir en PDF (acta_volante.php)
    ob_start(); // Iniciar el almacenamiento en búfer de salida
    include('acta_volante.php'); // Asegúrate de que la ruta al archivo es correcta
    $html = ob_get_clean(); // Obtener el contenido de la página como HTML

    // Agregar los estilos al HTML
    $html = "<style>{$css}</style>" . $html;

    // Escribir el HTML en el PDF
    $mpdf->WriteHTML($html);

    // Output del PDF (mostrar en navegador)
    $mpdf->Output('acta_volante.pdf', 'I'); // 'I' para visualizar en el navegador
} catch (\Mpdf\MpdfException $e) {
    echo "Error al generar el PDF: " . $e->getMessage();
}
?>
