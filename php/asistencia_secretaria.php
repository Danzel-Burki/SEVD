<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$mensaje = "";
$usuarios = [];

// 📦 Procesamiento de formularios (búsqueda y registro de asistencia)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buscar'])) {
        $buscar = trim($_POST['buscar']);

        // 🔍 Consulta: traer usuarios de rol docente (2) o administración (3)
        $stmt = $con->prepare("SELECT idusuario, nombre, apellido, dni, idrol, uid_rfid 
                               FROM usuarios 
                               WHERE (dni LIKE ? OR nombre LIKE ? OR apellido LIKE ?) 
                               AND idrol IN (2,3) 
                               LIMIT 10");
        $likeBuscar = "%$buscar%";
        $stmt->bind_param("sss", $likeBuscar, $likeBuscar, $likeBuscar);
        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }

        $stmt->close();
    } elseif (isset($_POST['idusuario_asistencia'])) {
        // 🕒 Registro manual de asistencia
        $idusuario = $_POST['idusuario_asistencia'];
        $estado = $_POST['estado'] ?? 'Presente';
        $observacion = $_POST['observacion'] ?? '';

        $fechahora = $_POST['fechahora'] ?? date('Y-m-d H:i:s');
        $fechahora = date('Y-m-d H:i:s', strtotime($fechahora));

        // 🔁 Verificar si ya tiene 2 asistencias en el día
        $fechaHoy = date('Y-m-d', strtotime($fechahora ?? 'now'));
        $sqlVerificacion = "SELECT COUNT(*) AS cantidad FROM asistencias WHERE idusuario = ? AND DATE(fechahora) = ?";
        $stmtVerificacion = $con->prepare($sqlVerificacion);
        $stmtVerificacion->bind_param('is', $idusuario, $fechaHoy);
        $stmtVerificacion->execute();
        $resultado = $stmtVerificacion->get_result();
        $fila = $resultado->fetch_assoc();
        $stmtVerificacion->close();

        if ($fila['cantidad'] >= 2) {
            $mensaje = "⚠️ Este usuario ya tiene 2 asistencias registradas hoy.";
        } else {
            // 💾 Insertar asistencia manual
            $stmt = $con->prepare("INSERT INTO asistencias (fechahora, estado, observacion, idusuario) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $fechahora, $estado, $observacion, $idusuario);

            if ($stmt->execute()) {
                $mensaje = "✅ Asistencia registrada correctamente.";
            } else {
                $mensaje = "❌ Error al registrar asistencia.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Asistencia - Secretaría</title>
    <link rel="stylesheet" href="css/Styles_inscripcion_mesas.css">
    <link rel="stylesheet" href="css/estilo_general.css">
</head>
<body>
    <section class="main-content">
        <h2>Registrar Asistencia de Docentes y Personal Administrativo</h2>

        <!-- 🔍 Formulario de búsqueda -->
        <form  class="inscription-form" method="POST" action="" class="historial_academico_form">
            <div class="ancho-completo">
                <input type="text" name="buscar" placeholder="Nombre, Apellido o DNI" required class="input-buscar" />
            </div>
            <div class="ancho-completo">
                <button type="submit" name="buscarBtn">Buscar</button>
            </div>
        </form>

        <!-- 💬 Mensaje de confirmación o error -->
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje-confirmacion"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <!-- 📋 Resultados de búsqueda -->
        <?php if (!empty($usuarios)): ?>
            <section class="course-list">
                <h3>Resultados de la búsqueda:</h3>
                <ul>
                    <?php foreach ($usuarios as $usuario): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($usuario['nombre'] . " " . $usuario['apellido']); ?></strong> 
                            - DNI: <?php echo htmlspecialchars($usuario['dni']); ?>

                            <!-- 🖊️ Botón para cargar asistencia manual -->
                            <button type="button" class="btn-small btn-cargar-asistencia" data-id="<?php echo $usuario['idusuario']; ?>">
                                Cargar asistencia manual
                            </button>

                            <!-- 💳 Mostrar botón de "Cargar asistencia RFID" solo si el usuario YA tiene un UID asignado -->
                            <?php if (!empty($usuario['uid_rfid'])): ?>
                                <button type="button" class="btn-small btn-asistencia-rfid" data-id="<?php echo $usuario['idusuario']; ?>">
                                    Cargar asistencia RFID
                                </button>
                            <?php endif; ?>

                            <!-- 🔐 Mostrar botón "Vincular RFID" solo si el usuario NO tiene tarjeta -->
                            <?php if (empty($usuario['uid_rfid'])): ?>
                                <button type="button" class="btn-small vincular-rfid-btn" data-id="<?php echo $usuario['idusuario']; ?>">
                                    Vincular RFID
                                </button>
                            <?php else: ?>
                                <span class="rfid-asignado">🔒 RFID asignado</span>
                            <?php endif; ?>

                            <!-- 🧾 Formulario de asistencia manual -->
                            <div class="form-asistencia-container" id="form-<?php echo $usuario['idusuario']; ?>" style="display:none;">
                                <form class="inscription-form" method="POST" action="" class="form-asistencia">
                                    <input type="hidden" name="idusuario_asistencia" value="<?php echo $usuario['idusuario']; ?>">
                                    <br>
                                    <label for="fechahora-<?php echo $usuario['idusuario']; ?>">Fecha y hora:</label>
                                    <br><br>
                                    <input type="datetime-local" id="fechahora-<?php echo $usuario['idusuario']; ?>" 
                                           name="fechahora" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                                    <br><br>
                                    <label for="estado-<?php echo $usuario['idusuario']; ?>">Estado:</label>
                                    <br><br>
                                    <select id="estado-<?php echo $usuario['idusuario']; ?>" name="estado" required>
                                        <option value="Presente" selected>Presente</option>
                                        <option value="Tardanza">Tardanza</option>
                                        <option value="Ausente">Ausente</option>
                                    </select>
                                    <br><br>
                                    <label for="observacion-<?php echo $usuario['idusuario']; ?>">Observación:</label>
                                    <br><br>
                                    <textarea id="observacion-<?php echo $usuario['idusuario']; ?>" name="observacion" rows="3" placeholder="Opcional"></textarea>
                                    <br><br>
                                    <button type="submit">Registrar asistencia</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php elseif (isset($_POST['buscar'])): ?>
            <div class="mensaje-no-usuarios">No se encontraron usuarios.</div>
        <?php endif; ?>
    </section>

    <!-- 🪪 Modal para mostrar estado del lector RFID -->
    <div id="rfid-modal" class="rfid-modal" style="display:none;">
        <div class="rfid-modal-content">
            <div id="rfid-spinner" class="spinner"></div>
            <div id="rfid-status" class="status-text">Acerque la tarjeta al lector...</div>
        </div>
    </div>

    <script src="js/asistencia.js"></script>
    <script src="js/vincular_rfid.js"></script>
    <script src="js/asistencia_rfid.js"></script>
</body>
</html>
