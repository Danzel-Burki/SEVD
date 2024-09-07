<?php
session_start();
include("../includes/conexion.php");
conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilos_archivo.css" />
    <title>Subir Archivo</title>
</head>
<body>
  <div class="container">
    <h1>Subir Archivo</h1>
    
    <?php
      if (isset($_FILES['archivo'])) 
      {
        // detalles del archivo subido
        $fileTmpPath = $_FILES['archivo']['tmp_name'];
        $fileName = $_FILES['archivo']['name'];
        $fileSize = $_FILES['archivo']['size'];
        $fileType = $_FILES['archivo']['type'];

        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('pdf');

        if (in_array($fileExtension, $allowedfileExtensions)) 
        {
          // Si es válido el archivo, lo subo al servidor
          $uploadFileDir = '../documentos/';
          $dest_path = $uploadFileDir . $newFileName;

          if (move_uploaded_file($fileTmpPath, $dest_path)) 
          {
            $sql = "INSERT INTO carreras (nombre, planestudiocarrera) values('ejemplo','".$dest_path."')";
            $sql = mysqli_query($con, $sql);
              if(mysqli_error($con))
              $message = 'Error, no se pudo copiar el archivo al servidor. Revisa los permisos de la carpeta o la ruta.';
              else 
              $message = 'Sesubio corectamente';
          } 

          else 
          {
            $message = '<p class="error">Error, no se pudo copiar el archivo al servidor. Revisa los permisos de la carpeta o la ruta.</p>';
          }
          
        } 
        else 
        {
          $message = '<p class="error">Error, el formato del archivo no es válido.</p>';
        }

        echo '<div class="message">' . $message . '</div>';
      }
    ?>
    <form method="POST" action="archivo.php" enctype="multipart/form-data">
      <div class="form-group">
        <label for="archivo">Selecciona un archivo:</label>
        <input type="file" name="archivo" id="archivo" required />
      </div>

      <input type="submit" value="Subir" />
    </form>
  </div>
</body>
</html>