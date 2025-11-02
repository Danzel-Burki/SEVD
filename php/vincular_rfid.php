<?php
require_once "../includes/conexion.php";
conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['uid']) && isset($_POST['idusuario'])) {
    $uid = $_POST['uid'];
    $idusuario = $_POST['idusuario'];

    // Verificar si UID ya existe
    $stmtCheck = $con->prepare("SELECT idusuario FROM usuarios WHERE uid_rfid = ?");
    $stmtCheck->bind_param("s", $uid);
    $stmtCheck->execute();
    $resultado = $stmtCheck->get_result();

    if ($resultado->num_rows > 0) {
        echo "❌ UID ya está asignado a otro usuario.";
    } else {
        $stmt = $con->prepare("UPDATE usuarios SET uid_rfid = ? WHERE idusuario = ?");
        $stmt->bind_param("si", $uid, $idusuario);

        if ($stmt->execute()) {
            echo "✅ RFID asignado correctamente al usuario ID $idusuario.";
        } else {
            echo "❌ Error al asignar RFID.";
        }
        $stmt->close();
    }

    $stmtCheck->close();
} else {
    echo "❌ Datos incompletos.";
}
?>
