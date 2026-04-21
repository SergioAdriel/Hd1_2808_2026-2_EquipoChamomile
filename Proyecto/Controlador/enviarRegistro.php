<?php
require __DIR__ . '/conexion.php';

$nombre = trim($_POST['nombre']);
$telefono = trim($_POST['telefono']);
$clave = trim($_POST['clave']);

$sql = "SELECT id_usuario FROM usuarios WHERE telefono = ? OR nombre = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $telefono, $nombre);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: ./registroVista.php?error=1");
    exit;
}

$sql = "INSERT INTO usuarios (nombre, telefono, contrasena)
        VALUES (?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $telefono, $clave);

if ($stmt->execute()) {
    header("Location: ./registroVista.php?ok=1");
} else {
    header("Location: ./registroVista.php?error=1");
}

$stmt->close();
$conexion->close();
?>
