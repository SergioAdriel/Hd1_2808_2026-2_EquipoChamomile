<?php
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../Controlador/login.php");
    exit;
}

require __DIR__ . "/../header.php";
require __DIR__ . "/../Controlador/conexion.php";
require __DIR__ . '/../musica.php';

$id_usuario = $_SESSION['trainer_id'];
$id_rival = $_GET['rival'] ?? null;

// validar rival
if (!$id_rival || $id_rival == $id_usuario) {
    header("Location: ./combate.php");
    exit;
}

// obtener nombre del rival
$sql = "SELECT nombre FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_rival);
$stmt->execute();
$rival = $stmt->get_result()->fetch_assoc();

if (!$rival) {
    header("Location: ./combate.php");
    exit;
}

$nombre_rival = $rival['nombre'];

// obtener equipo del usuario
$sql = "SELECT id_pokemon FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resUser = $stmt->get_result();

$equipo_user = [];
while ($row = $resUser->fetch_assoc()) {
    $equipo_user[] = $row['id_pokemon'];
}

// obtener equipo del rival
$sql = "SELECT id_pokemon FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_rival);
$stmt->execute();
$resRival = $stmt->get_result();

$equipo_rival = [];
while ($row = $resRival->fetch_assoc()) {
    $equipo_rival[] = $row['id_pokemon'];
}

// validar equipos completos
if (count($equipo_user) < 6 || count($equipo_rival) < 6) {
    header("Location: ./combate.php");
    exit;
}
?>

<style>
.poke-img {
    width: 80px;
}
.vs-text {
    font-size: 24px;
    font-weight: bold;
    margin: 20px 0;
}
</style>

<div class="container center-align">

    <h5>Confirmar combate</h5>
    <br>
    <a href="./combate.php" class="btn blue">Volver</a>
    <br>
    <br>

    <div class="row">

        <!-- TU EQUIPO -->
        <div class="col s12 m6">
            <div class="card poke-card center-align" style="padding:15px;">
                <h6><?php echo $_SESSION['trainer_name']; ?></h6>

                <?php foreach ($equipo_user as $poke): ?>
                    <img class="poke-img"
                    src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png">
                <?php endforeach; ?>
            </div>
        </div>

        <!-- RIVAL -->
        <div class="col s12 m6">
            <div class="card poke-card center-align" style="padding:15px;">
                <h6><?php echo $nombre_rival; ?></h6>

                <?php foreach ($equipo_rival as $poke): ?>
                    <img class="poke-img"
                    src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png">
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <br>

    <!-- BOTÓN PELEAR -->
    <form method="POST" action="./batalla.php">
        <input type="hidden" name="rival" value="<?php echo $id_rival; ?>">
        <button class="btn red">Pelear</button>
    </form>

    <br>

</div>

<?php require __DIR__ . "/../footer.php"; ?>
