<?php
require __DIR__ . '/conexion.php';
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../index.php");
    exit;
}

$id = $_SESSION['trainer_id'];
$pokemon = $_POST['pokemon'] ?? '';

if (empty($pokemon)) {
    header("Location: ../Vista/equipo.php?error=vacio");
    exit;
}

$sql_check = "SELECT * FROM equipo WHERE id_usuario = ? AND id_pokemon = ?";
$stmt = $conexion->prepare($sql_check);
$stmt->bind_param("ii", $id, $pokemon);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../Vista/equipo.php?error=noexiste");
    exit;
}

$sql = "DELETE FROM equipo WHERE id_usuario = ? AND id_pokemon = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id, $pokemon);

if (!$stmt->execute()) {
    die("Error al eliminar: " . $stmt->error);
}

// 🎉 éxito
header("Location: ../Vista/equipo.php?ok=eliminado");
exit;
?>
