<?php
require "conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar sesión
$id_usuario = $_SESSION['trainer_id'] ?? null;

if (!$id_usuario) {
    header("Location: ../index.php");
    exit;
}

// Preparar consulta segura
$sql = "DELETE FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error en prepare: " . $conexion->error);
}

$stmt->bind_param("i", $id_usuario);

// Ejecutar eliminación
if ($stmt->execute()) {

    // Destruir sesión
    session_destroy();

    // Redirigir a inicio
    header("Location: ../index.php?msg=eliminado");
    exit;

} else {
    echo "Error al eliminar usuario";
}

// Cerrar
$stmt->close();
$conexion->close();
?>