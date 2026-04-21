<?php
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: ./login.php");
    exit;
}

require './conexion.php';

// Obtener y limpiar datos
$trainer_id = $_SESSION['trainer_id'];
$nombre = trim($_POST['nombre']);
$telefono = trim($_POST['telefono']);
$clave = trim($_POST['clave']);
$confirmar_clave = trim($_POST['confirmar_clave']);
$locacionConf = "./configuracion.php";

if (empty($nombre) || empty($telefono) || empty($clave) || empty($confirmar_clave)) {
    header("Location: $locacionConf?accion=editar&error=campos_vacios");
    exit;
}

if ($clave !== $confirmar_clave) {
    header("Location: $locacionConf?accion=editar&error=password_no_coinciden");
    exit;
}

if (!preg_match('/^[0-9]{10,}$/', $telefono)) {
    header("Location: $locacionConf?accion=editar&error=telefono_invalido");
    exit;
}

$sql = "SELECT id_usuario FROM usuarios WHERE (telefono = ? OR nombre = ?) AND id_usuario != ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssi", $telefono, $nombre, $trainer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: $locacionConf?accion=editar&error=duplicado");
    exit;
}
$stmt->close();

$sql = "UPDATE usuarios SET nombre = ?, telefono = ?, contrasena = ? WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssi", $nombre, $telefono, $clave, $trainer_id);

if ($stmt->execute()) {
    $_SESSION['nombre'] = $nombre;
    $_SESSION['telefono'] = $telefono;
    
    header("Location: $locacionConf?msg=actualizado");
} else {
    header("Location: $locacionConf?accion=editar&error=db_error");
}

$stmt->close();
$conexion->close();
?>
