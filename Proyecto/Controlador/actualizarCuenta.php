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

// 🔍 Validar que ningún campo esté vacío
if (empty($nombre) || empty($telefono) || empty($clave) || empty($confirmar_clave)) {
    header("Location: $locacionConf?accion=editar&error=campos_vacios");
    exit;
}

// 🔍 Validar que las contraseñas coincidan
if ($clave !== $confirmar_clave) {
    header("Location: $locacionConf?accion=editar&error=password_no_coinciden");
    exit;
}

// 🔍 Validar formato de teléfono (solo números, mínimo 10 dígitos)
if (!preg_match('/^[0-9]{10,}$/', $telefono)) {
    header("Location: $locacionConf?accion=editar&error=telefono_invalido");
    exit;
}

// 🔍 Verificar si el teléfono o nombre ya existen en OTRO usuario (diferente al actual)
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

// 🔄 Actualizar usuario (incluyendo contraseña)
$sql = "UPDATE usuarios SET nombre = ?, telefono = ?, contrasena = ? WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssi", $nombre, $telefono, $clave, $trainer_id);

if ($stmt->execute()) {
    // ✅ Actualizar también los datos en la sesión
    $_SESSION['nombre'] = $nombre;
    $_SESSION['telefono'] = $telefono;
    
    header("Location: $locacionConf?msg=actualizado");
} else {
    header("Location: $locacionConf?accion=editar&error=db_error");
}

$stmt->close();
$conexion->close();
?>