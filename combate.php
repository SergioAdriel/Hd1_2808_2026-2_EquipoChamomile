<?php
session_start();
require "./header.php";
require "./Controlador/conexion.php";

$id_usuario = $_SESSION['trainer_id'];

// 🔍 verificar si tienes 6 Pokémon
$sql = "SELECT COUNT(*) total FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
?>

<div class="container center-align">
    <h4>⚔️ Combate Pokémon</h4>

    <?php if ($total < 6): ?>
        <p class="red-text">⚠ Necesitas 6 Pokémon para combatir</p>
    <?php else: ?>
        <p class="green-text">✔ Listo para combatir</p>
    <?php endif; ?>
</div>

<?php
$sql = "SELECT u.id_usuario, u.nombre, c.victorias, c.derrotas
        FROM usuarios u
        JOIN combates c ON u.id_usuario = c.id_usuario";

$result = $conexion->query($sql);
?>

<div class="row">

<?php while ($row = $result->fetch_assoc()): ?>

<?php
// contar pokemons del rival
$sql2 = "SELECT COUNT(*) total FROM equipo WHERE id_usuario = ?";
$stmt2 = $conexion->prepare($sql2);
$stmt2->bind_param("i", $row['id_usuario']);
$stmt2->execute();
$totalRival = $stmt2->get_result()->fetch_assoc()['total'];
?>

<div class="col s12 m4">
    <div class="card center-align">

        <h6><?php echo $row['nombre']; ?></h6>

        <p>🏆 <?php echo $row['victorias']; ?> | 💀 <?php echo $row['derrotas']; ?></p>

        <?php if ($row['id_usuario'] == $id_usuario): ?>
            <p class="grey-text">Eres tú</p>

        <?php elseif ($total < 6): ?>
            <p class="red-text">Completa tu equipo</p>

        <?php elseif ($totalRival < 6): ?>
            <p class="orange-text">Rival incompleto</p>

        <?php else: ?>
            <a href="batalla.php?rival=<?php echo $row['id_usuario']; ?>" class="btn red">
                ⚔️ Retar
            </a>
        <?php endif; ?>

    </div>
</div>

<?php endwhile; ?>

</div>

<?php require "./footer.php"; ?>