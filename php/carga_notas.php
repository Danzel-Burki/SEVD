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

// --- Editar nota ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_nota'])) {
    $idnota = intval($_POST['idnota']);
    $valor = floatval($_POST['valor']);
    $idtiponota = intval($_POST['idtiponota']);
    $sql_upd = "UPDATE notas SET valor=$valor, idtiponota=$idtiponota WHERE idnota=$idnota";
    mysqli_query($con, $sql_upd);
    echo "<script>alert('Nota editada correctamente'); window.location.href='index.php?modulo=carga_notas';</script>";
}
?>

<link rel="stylesheet" href="css/estilo_general.css">
<link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="contenedor_contenido">
    <section class="main-content">
        <h2>Carga de Notas</h2>
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
                    <td>
                        <!-- Editar -->
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="idnota" value="<?php echo $r['idnota']; ?>">
                            <input type="number" step="0.01" min="0" max="10" name="valor" value="<?php echo $r['valor']; ?>" required style="width:60px;">
                            <select name="idtiponota" required>
                                <?php
                                $res_tipos = mysqli_query($con, "SELECT * FROM tiponotas ORDER BY idtiponota");
                                while ($t = mysqli_fetch_assoc($res_tipos)):
                                    $sel = ($r['idtiponota'] == $t['idtiponota']) ? 'selected' : '';
                                    echo "<option value='{$t['idtiponota']}' $sel>{$t['descripcion']}</option>";
                                endwhile;
                                ?>
                            </select>
                            <button type="submit" name="editar_nota" style="color:blue;"><i class="fas fa-pencil-alt"></i></button>
                        </form>
                        <!-- Eliminar -->
                        <a href="index.php?modulo=carga_notas&delete_nota=<?php echo $r['idnota']; ?>" 
                           onclick="return confirm('¿Eliminar nota?');" 
                           style="color:red; margin-left:5px;">
                           <i class="fas fa-trash"></i>
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
