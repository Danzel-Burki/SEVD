<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
global $con;

// --- Guardar notas ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_notas'])) {
    $id_materia = intval($_POST['id_materia']);
    foreach ($_POST['notas'] as $id_estudiante => $datos) {
        $nota = floatval($datos['nota']);
        $idtiponota = intval($datos['idtiponota']);

        $sql_check = "SELECT * FROM notas WHERE idestudiante=$id_estudiante AND idmateria=$id_materia";
        $res_check = mysqli_query($con, $sql_check);

        if (mysqli_num_rows($res_check) > 0) {
            $sql_update = "UPDATE notas SET valor=$nota, idtiponota=$idtiponota 
                           WHERE idestudiante=$id_estudiante AND idmateria=$id_materia";
            mysqli_query($con, $sql_update);
        } else {
            $sql_insert = "INSERT INTO notas (idestudiante, idmateria, valor, idtiponota) 
                           VALUES ($id_estudiante, $id_materia, $nota, $idtiponota)";
            mysqli_query($con, $sql_insert);
        }
    }
    echo "<script>alert('Notas guardadas correctamente');</script>";
}

// --- Eliminar nota ---
if (isset($_GET['delete_nota'])) {
    $idnota = intval($_GET['delete_nota']);
    mysqli_query($con, "DELETE FROM notas WHERE idnota=$idnota");
    echo "<script>alert('Nota eliminada correctamente'); window.location.href='index.php?modulo=carga_notas';</script>";
}

// --- Limpiar filtros ---
if (isset($_GET['limpiar'])) {
    echo "<script>window.location.href='index.php?modulo=carga_notas';</script>";
}
?>

<link rel="stylesheet" href="css/estilo_general.css">
<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="contenedor_contenido">
    <section class="main-content">
        <h2>Carga de Notas</h2>
        
        <!-- Botón Limpiar -->
        <div style="margin-bottom: 15px;">
            <a href="index.php?modulo=carga_notas&limpiar=1" class="btn-limpiar">
                <i class="fas fa-broom"></i> Limpiar
            </a>
        </div>
        
        <form method="post" action="index.php?modulo=carga_notas" class="inscription-form">
            <!-- Paso 1: Carrera -->
            <label for="carrera">Carrera:</label>
            <select name="carrera" required onchange="this.form.submit()">
                <option value="">Seleccionar carrera</option>
                <?php
                $sql_carr = "SELECT idcarrera, nombre FROM carreras ORDER BY nombre";
                $res_carr = mysqli_query($con, $sql_carr);
                while ($c = mysqli_fetch_assoc($res_carr)) {
                    $sel = (isset($_POST['carrera']) && $_POST['carrera']==$c['idcarrera']) ? "selected" : "";
                    echo "<option value='{$c['idcarrera']}' $sel>{$c['nombre']}</option>";
                }
                ?>
            </select>

            <?php if (!empty($_POST['carrera'])): ?>
                <label for="anio">Año:</label>
                <select name="anio" required onchange="this.form.submit()">
                    <option value="">Seleccionar año</option>
                    <option value="1" <?php if (isset($_POST['anio']) && $_POST['anio']==1) echo "selected"; ?>>1</option>
                    <option value="2" <?php if (isset($_POST['anio']) && $_POST['anio']==2) echo "selected"; ?>>2</option>
                    <option value="3" <?php if (isset($_POST['anio']) && $_POST['anio']==3) echo "selected"; ?>>3</option>
                </select>
            <?php endif; ?>

            <?php if (!empty($_POST['anio'])):
                $anio = intval($_POST['anio']);
                $id_carrera = intval($_POST['carrera']);
                $sql_mat = "SELECT idmateria, nombre FROM materias 
                            WHERE idcarrera=$id_carrera AND aniocursado=$anio ORDER BY nombre";
                $res_mat = mysqli_query($con, $sql_mat); ?>
                <label for="materia">Materia:</label>
                <select name="id_materia" required onchange="this.form.submit()">
                    <option value="">Seleccionar materia</option>
                    <?php while ($m = mysqli_fetch_assoc($res_mat)):
                        $sel = (isset($_POST['id_materia']) && $_POST['id_materia']==$m['idmateria']) ? "selected" : "";
                        echo "<option value='{$m['idmateria']}' $sel>{$m['nombre']}</option>";
                    endwhile; ?>
                </select>
            <?php endif; ?>

            <?php if (!empty($_POST['id_materia'])):
                $id_materia = intval($_POST['id_materia']);
                $id_carrera = intval($_POST['carrera']);
                $sql_alumnos = "SELECT idestudiante, nombre, apellido FROM estudiantes WHERE idcarrera=$id_carrera ORDER BY apellido";
                $res_alumnos = mysqli_query($con, $sql_alumnos);
                if (mysqli_num_rows($res_alumnos) > 0): ?>
                    <table border="1" style="margin-top:15px;">
                        <tr><th>Alumno</th><th>Nota</th><th>Tipo</th></tr>
                        <?php while ($al = mysqli_fetch_assoc($res_alumnos)):
                            $sql_nota = "SELECT valor, idtiponota FROM notas 
                                         WHERE idestudiante={$al['idestudiante']} AND idmateria=$id_materia";
                            $res_nota = mysqli_query($con, $sql_nota);
                            $nota = $idtiponota = '';
                            if ($fila = mysqli_fetch_assoc($res_nota)) {
                                $nota = $fila['valor']; $idtiponota = $fila['idtiponota'];
                            } ?>
                            <tr>
                                <td><?php echo "{$al['apellido']}, {$al['nombre']}"; ?></td>
                                <td><input type="number" step="0.01" min="0" max="10" name="notas[<?php echo $al['idestudiante']; ?>][nota]" value="<?php echo $nota; ?>" required></td>
                                <td>
                                    <select name="notas[<?php echo $al['idestudiante']; ?>][idtiponota]" required>
                                        <option value="">Seleccionar</option>
                                        <?php
                                        $sql_tipos = "SELECT idtiponota, descripcion FROM tiponotas ORDER BY idtiponota";
                                        $res_tipos = mysqli_query($con, $sql_tipos);
                                        while ($t = mysqli_fetch_assoc($res_tipos)):
                                            $sel = ($idtiponota==$t['idtiponota'])?'selected':'';
                                            echo "<option value='{$t['idtiponota']}' $sel>{$t['descripcion']}</option>";
                                        endwhile;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                    <button type="submit" name="guardar_notas" style="margin-top:10px;">Guardar Notas</button>
                <?php else: echo "<p>No hay alumnos en esta materia.</p>"; endif; ?>
            <?php endif; ?>
        </form>
    </section>
</div>

<?php
// --- Tabla con todas las notas ---
$sql = "SELECT n.idnota, e.apellido, e.nombre, m.nombre AS materia, 
               n.valor, n.idtiponota, t.descripcion AS tiponota
        FROM notas n
        JOIN estudiantes e ON n.idestudiante = e.idestudiante
        JOIN materias m ON n.idmateria = m.idmateria
        JOIN tiponotas t ON n.idtiponota = t.idtiponota
        ORDER BY e.apellido, e.nombre, m.nombre";
$resultado = mysqli_query($con, $sql);
?>

<section class="main-content">
    <h2>Lista de Notas</h2>
    <table border="1">
        <thead>
            <tr><th>ID</th><th>Alumno</th><th>Materia</th><th>Nota</th><th>Tipo</th><th>Opciones</th></tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($resultado) > 0):
            while ($r = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $r['idnota']; ?></td>
                    <td><?php echo $r['apellido'] . ", " . $r['nombre']; ?></td>
                    <td><?php echo $r['materia']; ?></td>
                    <td><?php echo $r['valor']; ?></td>
                    <td><?php echo $r['tiponota']; ?></td>
                    <td style="text-align: center;">
                        <!-- Editar - Lápiz azul -->
                        <a href="index.php?modulo=carga_notas&editar=<?php echo $r['idnota']; ?>">
                            <i class='fas fa-pencil-alt'></i>
                        </a>
                        
                        <!-- Eliminar - Basurero rojo -->
                        <a href="index.php?modulo=carga_notas&delete_nota=<?php echo $r['idnota']; ?>"
                           class="eliminar"
                           onclick="return confirm('¿Está seguro que desea eliminar esta nota?');">
                           <i class='fa-solid fa-trash'></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile;
        else: ?>
            <tr><td colspan="6">No hay notas registradas.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</section>

<style>
/* Estilos para los botones */
.btn-limpiar {
    display: inline-block;
    background-color: #6c757d;
    color: white;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    transition: background-color 0.3s;
}

.btn-limpiar:hover {
    background-color: #5a6268;
    color: white;
    text-decoration: none;
}

/* Estilos para los íconos de la tabla */
table td a {
    margin: 0 5px;
    text-decoration: none;
}

table td a i.fas.fa-pencil-alt {
    color: #007bff;
    transition: color 0.3s;
}

table td a i.fas.fa-pencil-alt:hover {
    color: #0056b3;
}

table td a.eliminar i.fa-solid.fa-trash {
    color: #dc3545;
    transition: color 0.3s;
}

table td a.eliminar:hover i.fa-solid.fa-trash {
    color: #c82333;
}

/* Estilos para la tabla */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Centrar la columna de opciones */
table th:nth-child(6), 
table td:nth-child(6) {
    text-align: center;
}
</style>

<?php
// --- Funcionalidad para editar nota ---
if (isset($_GET['editar'])) {
    $idnota = intval($_GET['editar']);
    
    // Obtener datos de la nota a editar
    $sql_editar = "SELECT n.*, e.idcarrera, m.aniocursado 
                   FROM notas n
                   JOIN estudiantes e ON n.idestudiante = e.idestudiante
                   JOIN materias m ON n.idmateria = m.idmateria
                   WHERE n.idnota = $idnota";
    $res_editar = mysqli_query($con, $sql_editar);
    
    if ($nota_editar = mysqli_fetch_assoc($res_editar)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                // Seleccionar la carrera
                document.querySelector('select[name=\"carrera\"]').value = '{$nota_editar['idcarrera']}';
                
                // Enviar el formulario para cargar los años
                setTimeout(function() {
                    document.querySelector('select[name=\"carrera\"]').form.submit();
                    
                    // En el siguiente ciclo, seleccionar el año y materia
                    setTimeout(function() {
                        document.querySelector('select[name=\"anio\"]').value = '{$nota_editar['aniocursado']}';
                        document.querySelector('select[name=\"anio\"]').form.submit();
                        
                        // Finalmente seleccionar la materia
                        setTimeout(function() {
                            document.querySelector('select[name=\"id_materia\"]').value = '{$nota_editar['idmateria']}';
                            document.querySelector('select[name=\"id_materia\"]').form.submit();
                        }, 500);
                    }, 500);
                }, 500);
            });
        </script>";
    }
}
?>