<?php
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../Controlador/login.php");
    exit;
}
require __DIR__ . '/../musica.php';
require __DIR__ . "/../header.php";
require __DIR__ . "/../Controlador/conexion.php";

$id_usuario = $_SESSION['trainer_id'];

// 🔍 verificar si tienes 6 Pokémon
$sql = "SELECT COUNT(*) total FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
?>

<style>
.poke-img {
    width: 70px;
}
</style>

<div class="container center-align">
    <h4>Oponentes registrados.</h4>
    <a href="../Controlador/principal.php" class="btn blue">Volver</a>
    <br><br>

    <?php if ($total < 6): ?>
        <div class="card red lighten-4" style="padding:10px; margin-bottom:20px; max-width: 500px; margin: 0 auto 20px auto;">
            <span class="red-text text-darken-2">
                Necesitas 6 Pokémon para combatir
            </span>
        </div>
    <?php else: ?>
        <div class="card green lighten-4" style="padding:10px; margin-bottom:20px; max-width: 500px; margin: 0 auto 20px auto;">
            <span class="green-text text-darken-2">
                Listo para combatir
            </span>
        </div>
    <?php endif; ?>
</div>

<?php
// 🔥 traer entrenadores + stats
$sql = "SELECT u.id_usuario, u.nombre, c.victorias, c.derrotas
        FROM usuarios u
        JOIN combates c ON u.id_usuario = c.id_usuario";

$result = $conexion->query($sql);
?>

<div class="row">

<?php while ($row = $result->fetch_assoc()): ?>

<?php
if ($row['id_usuario'] == $id_usuario) {
    continue; // No mostrar al usuario
}

// 🔍 obtener equipo del rival
$sql2 = "SELECT id_pokemon FROM equipo WHERE id_usuario = ?";
$stmt2 = $conexion->prepare($sql2);
$stmt2->bind_param("i", $row['id_usuario']);
$stmt2->execute();
$resPokes = $stmt2->get_result();

$pokemons = [];
while ($p = $resPokes->fetch_assoc()) {
    $pokemons[] = $p['id_pokemon'];
}

$totalRival = count($pokemons);
?>

<div class="col s12 m4">
    <div class="card center-align" style="padding:15px;">

        <h6><?php echo $row['nombre']; ?></h6>

        <p>🏆 <?php echo $row['victorias']; ?> | 💀 <?php echo $row['derrotas']; ?></p>

        <!-- 🧬 EQUIPO DEL RIVAL -->
        <div class="poke-card center-align" style="margin-bottom:10px;">
            <?php if (!empty($pokemons)): ?>
                <?php foreach ($pokemons as $poke): ?>
                    <img class="poke-img"
                    src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png">
                <?php endforeach; ?>
            <?php else: ?>
                <p>Sin Pokémon</p>
            <?php endif; ?>
        </div>

        <!-- ⚔️ LÓGICA DE COMBATE -->
        <?php if ($row['id_usuario'] == $id_usuario): ?>
            <p class="grey-text">Eres tú</p>

        <?php elseif ($total < 6): ?>
            <p class="red-text">Completa tu equipo</p>

        <?php elseif ($totalRival < 6): ?>
            <p class="orange-text">Rival incompleto</p>

        <?php else: ?>
            <a href="./retar.php?rival=<?php echo $row['id_usuario']; ?>" class="btn red">
                Retar
            </a>
        <?php endif; ?>

    </div>
</div>

<?php endwhile; ?>

</div>

<?php require __DIR__ . "/../footer.php"; ?>