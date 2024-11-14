<?php
// Inicializar variables
$carreraSeleccionada = isset($_POST['carrera']) ? $_POST['carrera'] : '';
$estudiantes = [];
$estudianteEdicion = null; // Para almacenar el estudiante a editar

// Obtener las carreras para mostrarlas en el formulario
$queryCarreras = "SELECT * FROM carreras WHERE nombre != 'Pendiente'";
$resultCarreras = $con->query($queryCarreras);

// Consultar estudiantes si se ha seleccionado una carrera
if (!empty($carreraSeleccionada)) {
    $queryEstudiantes = "
        SELECT idestudiante, nombre, apellido, fechanacimiento, dni, direccion, telefono, correo, idcarrera 
        FROM estudiantes 
        WHERE idcarrera = ?";

    // Preparar la consulta
    $stmtEstudiantes = $con->prepare($queryEstudiantes);
    $stmtEstudiantes->bind_param("i", $carreraSeleccionada);
    $stmtEstudiantes->execute();
    $resultEstudiantes = $stmtEstudiantes->get_result();

    // Almacenar los estudiantes en un array
    if ($resultEstudiantes->num_rows > 0) {
        while ($row = $resultEstudiantes->fetch_assoc()) {
            $estudiantes[] = $row;
        }
    }
}

// Procesar edición de estudiante
if (isset($_POST['editar']) && isset($_POST['idestudiante'])) {
    $idEstudiante = $_POST['idestudiante'];
    // Consultar datos del estudiante
    $queryEdicion = "SELECT * FROM estudiantes WHERE idestudiante = ?";
    $stmtEdicion = $con->prepare($queryEdicion);
    $stmtEdicion->bind_param("i", $idEstudiante);
    $stmtEdicion->execute();
    $resultEdicion = $stmtEdicion->get_result();
    $estudianteEdicion = $resultEdicion->fetch_assoc();
}

// Procesar actualización de datos
if (isset($_POST['guardar_cambios']) && isset($_POST['idestudiante'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fechanacimiento = $_POST['fechanacimiento'];
    $dni = $_POST['dni'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $idEstudiante = $_POST['idestudiante'];

    $queryActualizar = "UPDATE estudiantes SET nombre=?, apellido=?, fechanacimiento=?, dni=?, direccion=?, telefono=?, correo=? WHERE idestudiante=?";
    $stmtActualizar = $con->prepare($queryActualizar);
    $stmtActualizar->bind_param("sssssssi", $nombre, $apellido, $fechanacimiento, $dni, $direccion, $telefono, $correo, $idEstudiante);
    $stmtActualizar->execute();

    echo "<script>alert('Se han guardado los cambios');</script>";
    $estudianteEdicion = null; // Reiniciar el estudiante en edición
}

// Procesar borrado de estudiante
if (isset($_POST['borrar']) && isset($_POST['idestudiante'])) {
    $idEstudiante = $_POST['idestudiante'];

    // Primero, eliminar las referencias en estudiantes_mesas
    $queryEliminarMesas = "DELETE FROM estudiantes_mesas WHERE idestudiante = ?";
    $stmtEliminarMesas = $con->prepare($queryEliminarMesas);
    $stmtEliminarMesas->bind_param("i", $idEstudiante);
    $stmtEliminarMesas->execute();

    // Luego, eliminar las referencias en inscripciones
    $queryEliminarInscripciones = "DELETE FROM inscripciones WHERE idestudiante = ?";
    $stmtEliminarInscripciones = $con->prepare($queryEliminarInscripciones);
    $stmtEliminarInscripciones->bind_param("i", $idEstudiante);
    $stmtEliminarInscripciones->execute();

    // Finalmente, eliminar el estudiante
    $queryBorrar = "DELETE FROM estudiantes WHERE idestudiante = ?";
    $stmtBorrar = $con->prepare($queryBorrar);
    $stmtBorrar->bind_param("i", $idEstudiante);
    $stmtBorrar->execute();

    echo "<script>alert('Estudiante eliminado de la base de datos');</script>";
}
// Lógica para subir archivo
if (isset($_FILES['archivo'])) {
    // Detalles del archivo subido
    $fileTmpPath = $_FILES['archivo']['tmp_name'];
    $fileName = $_FILES['archivo']['name'];
    $fileSize = $_FILES['archivo']['size'];
    $fileType = $_FILES['archivo']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Obtener el nombre personalizado del archivo
    $customFileName = isset($_POST['custom_name']) ? trim($_POST['custom_name']) : '';

    // Verificar si se proporcionó un nombre personalizado
    if (!empty($customFileName)) {
        // Reemplazar espacios con guiones bajos y asegurarse de que no haya caracteres no válidos
        $customFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $customFileName);
        $newFileName = $customFileName . '.' . $fileExtension; // Crear el nuevo nombre de archivo
    } else {
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension; // Nombre por defecto si no hay nombre personalizado
    }

    $allowedfileExtensions = array('pdf');

    if (in_array($fileExtension, $allowedfileExtensions)) {
        // Si es válido el archivo, lo subo al servidor
        $uploadFileDir = 'documentos/planes-estudio/';
        $dest_path = $uploadFileDir . $newFileName; // Usar el nuevo nombre
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Guardar la ruta del archivo en la base de datos
            $sql = "UPDATE carreras SET planestudiocarrera = '" . mysqli_real_escape_string($con, $newFileName) . "' 
            WHERE idcarrera = '" . mysqli_real_escape_string($con, $carreraSeleccionada) . "'";
            $result = mysqli_query($con, $sql);

            if ($result) {
                echo "<script> alert('Se subió correctamente el archivo.');</script>";
            } else {
                $message = 'Error: ' . mysqli_error($con); // Mostrar error de la base de datos
                echo "<script> alert('Error al actualizar la base de datos.');</script>";
            }
        } else {
            echo "<script> alert('Error, no se pudo copiar el archivo al servidor. Revisa los permisos de la carpeta o la ruta.');</script>";
        }
    } else {
        echo "<script> alert('Error, el formato del archivo no es válido.');</script>";
    }
}
?>

<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<section class="main-content">
    <div class="estudiantes-lista">
        <h2 class="titulo">Listado de Estudiantes</h2>
        <form method="post">
            <div class="carrera">
                <label for="carrera">Carrera:</label>
                <select name="carrera" id="carrera" onchange="this.form.submit()">
                    <option value="">Seleccione una carrera</option>
                    <?php
                    // Mostrar opciones de carreras
                    if ($resultCarreras->num_rows > 0) {
                        while ($row = $resultCarreras->fetch_assoc()) {
                            $selected = ($row['idcarrera'] == $carreraSeleccionada) ? 'selected' : '';
                            echo "<option value='" . $row['idcarrera'] . "' $selected>" . $row['nombre'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No hay carreras disponibles</option>";
                    }
                    ?>
                </select>
            </div>
        </form>
        <section class="academic-status">
            <table class="tabla-estudiantes">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($estudiantes)) {
                        foreach ($estudiantes as $estudiante) {
                            echo "<tr>";
                            echo "<td>{$estudiante['nombre']}</td>";
                            echo "<td>{$estudiante['apellido']}</td>";
                            echo "<td>{$estudiante['dni']}</td>";
                            echo "<td>{$estudiante['correo']}</td>";
                            echo "<td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='idestudiante' value='{$estudiante['idestudiante']}'>
                                <button type='submit' name='editar'>Editar</button>
                                <button type='submit' name='borrar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este estudiante?\");'>Borrar</button>
                            </form>
                        </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay estudiantes disponibles para la carrera seleccionada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <br><br>

            <?php if ($estudianteEdicion): ?>
                <h2>Editar Estudiante</h2>
                <form method="post">
                    <input type="hidden" name="idestudiante" value="<?php echo $estudianteEdicion['idestudiante']; ?>">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" value="<?php echo $estudianteEdicion['nombre']; ?>" required>
                    <label for="apellido">Apellido:</label>
                    <input type="text" name="apellido" value="<?php echo $estudianteEdicion['apellido']; ?>" required>
                    <label for="fechanacimiento">Fecha de Nacimiento:</label>
                    <input type="date" name="fechanacimiento" value="<?php echo $estudianteEdicion['fechanacimiento']; ?>"
                        required>
                    <label for="dni">DNI:</label>
                    <input type="text" name="dni" value="<?php echo $estudianteEdicion['dni']; ?>" required>
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" value="<?php echo $estudianteEdicion['direccion']; ?>" required>
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" value="<?php echo $estudianteEdicion['telefono']; ?>" required>
                    <label for="correo">Correo:</label>
                    <input type="email" name="correo" value="<?php echo $estudianteEdicion['correo']; ?>" required>
                    <button type="submit" name="guardar_cambios">Guardar Cambios</button>
                </form>
            <?php endif; ?>
        </section>
        <br><br>

    </div>

    <div class="upload-file">
        <h2>Carga Plan de Estudio</h2>
        <form action="index.php?modulo=gestion_carreras" method="POST" enctype="multipart/form-data">
            <label for="archivo">Selecciona un archivo:</label><br>
            <input type="file" id="archivo" name="archivo" accept=".pdf" required><br><br>

            <label for="custom_name">Nombre personalizado:</label><br>
            <input type="text" id="custom_name" name="custom_name" class="nombre-archivo" required><br><br>
            <input type="hidden" name="carrera" value="<?php echo htmlspecialchars($carreraSeleccionada); ?>">
            <input type="submit" class="cargar-archivo" value="Subir archivo">
        </form>
    </div>

</section>
