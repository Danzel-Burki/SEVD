<?php
require_once "../includes/conexion.php";
conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['uid_rfid'])) {
    $uid_rfid = $_POST['uid_rfid'];

    // Buscar el usuario con ese UID
    $stmt = $con->prepare("SELECT idusuario FROM usuarios WHERE uid_rfid = ?");
    $stmt->bind_param('s', $uid_rfid);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo "❌ UID no vinculado a ningún usuario.";
        exit;
    }

    $usuario = $resultado->fetch_assoc();
    $idusuario = $usuario['idusuario'];

    // Insertar asistencia
    $stmt = $con->prepare("INSERT INTO asistencias (fechahora, estado, observacion, idusuario) VALUES (NOW(), 'Presente', NULL, ?)");
    $stmt->bind_param('i', $idusuario);

    if ($stmt->execute()) {
        echo "✅ Asistencia registrada correctamente.";
    } else {
        echo "❌ Error al registrar asistencia.";
    }

    $stmt->close();
    $con->close();
} else {
    echo "❌ Datos incompletos.";
}
?>
