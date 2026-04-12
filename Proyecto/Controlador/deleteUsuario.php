<?php
require __DIR__ . "/conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Validar sesión
if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../index.php");
    exit;
}

$id_usuario = $_SESSION['trainer_id'];

// 🔥 Preparar eliminación
$sql = "DELETE FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error en prepare: " . $conexion->error);
}

$stmt->bind_param("i", $id_usuario);

// 🚀 Ejecutar
if ($stmt->execute()) {

    // 🔥 limpiar sesión COMPLETA
    $_SESSION = [];
    session_destroy();

    // 👉 redirigir con mensaje
    header("Location: ../index.php?msg=cuenta_eliminada");
    exit;

} else {
    // ❌ error controlado
    header("Location: ../Vista/principal.php?error=eliminar");
    exit;
}

// cerrar conexiones
$stmt->close();
$conexion->close();
?>