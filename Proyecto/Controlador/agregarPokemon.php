<?php
require './conexion.php';
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

$sql_count = "SELECT COUNT(*) total FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql_count);
$stmt->bind_param("i", $id);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['total'];

if ($count >= 6) {
    header("Location: ../Vista/equipo.php?error=limite");
    exit;
}

// 🔍 verificar duplicado
$sql_check = "SELECT * FROM equipo WHERE id_usuario = ? AND id_pokemon = ?";
$stmt = $conexion->prepare($sql_check);
$stmt->bind_param("ii", $id, $pokemon);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: ../Vista/equipo.php?error=repetido");
    exit;
}

$sql = "INSERT INTO equipo (id_usuario, id_pokemon) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id, $pokemon);

if (!$stmt->execute()) {
    die("Error al insertar: " . $stmt->error);
}

header("Location: ../Vista/equipo.php?ok=1");
exit;
?>
