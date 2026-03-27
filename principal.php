<?php
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: index.php");
    exit;
}

require "./header.php";
require "./Controlador/conexion.php";

$id_usuario = $_SESSION['trainer_id'];
$nombre = $_SESSION['trainer_name'];
?>

<style>
body {
    background: #f5f5f5;
}

.poke-card {
    background: linear-gradient(145deg, #ffffff, #e6e6e6);
    border-radius: 15px;
    padding: 15px;
    box-shadow: 4px 4px 10px rgba(0,0,0,0.2);
    transition: transform 0.2s, box-shadow 0.2s;
}

.poke-card:hover {
    transform: scale(1.05);
    box-shadow: 6px 6px 15px rgba(0,0,0,0.3);
}

.poke-img {
    width: 96px;
    animation: float 2s infinite;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
    100% { transform: translateY(0px); }
}

#sugerencias {
    background: white;
    border-radius: 10px;
    max-width: 300px;
    margin: auto;
    list-style: none;
    padding: 0;
}
</style>

<div class="container center-align">
    <h4>Bienvenido entrenador</h4>
    <h5><?php echo $nombre; ?></h5>
    <a href="Controlador/salir.php" class="btn red">Salir</a>
</div>
<div class="container center-align">
    <h4>Bienvenido entrenador</h4>
    <h5><?php echo $nombre; ?></h5>

    <a href="Controlador/salir.php" class="btn red">Salir</a>

    <!-- 🔥 NUEVO BOTÓN -->
    <form method="POST" action="Controlador/eliminarUsuario.php" style="margin-top:10px;">
        <button class="btn black" onclick="return confirm('¿Seguro que quieres eliminar tu cuenta?');">
            Eliminar cuenta
        </button>
    </form>
</div>

<!-- ========================= -->
<!-- 🧑 TU EQUIPO -->
<!-- ========================= -->

<h5 class="center-align">Tu equipo</h5>

<div class="row">
<?php
$sql = "SELECT id_pokemon FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()):
?>
    <div class="col s12 m3 center-align">
        <div class="poke-card">

            <img class="poke-img"
            src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $row['id_pokemon']; ?>.png">

            <p>#<?php echo $row['id_pokemon']; ?></p>

            <form method="POST" action="Controlador/eliminarPokemon.php">
                <input type="hidden" name="pokemon" value="<?php echo $row['id_pokemon']; ?>">
                <button class="btn red">Eliminar</button>
            </form>

        </div>
    </div>
<?php endwhile; ?>
</div>

<!-- ========================= -->
<!-- ➕ AGREGAR POKÉMON -->
<!-- ========================= -->

<div class="container center-align">

<?php
$sql = "SELECT COUNT(*) total FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['total'];

if ($count >= 6) {
    echo "<p class='red-text'>Equipo completo (6 Pokémon)</p>";
}
?>

<h5>Agregar Pokémon</h5>

<input type="text" id="pokemonInput" placeholder="Escribe un Pokémon">
<ul id="sugerencias"></ul>

<form method="POST" action="Controlador/agregarPokemon.php">
    <input type="hidden" name="pokemon" id="pokemonID">
    <br>
    <button class="btn green">Agregar</button>
</form>

</div>

<!-- ========================= -->
<!-- 👥 ENTRENADORES -->
<!-- ========================= -->

<h5 class="center-align">Entrenadores</h5>

<?php
$sql = "SELECT u.id_usuario, u.nombre, e.id_pokemon 
        FROM usuarios u
        LEFT JOIN equipo e ON u.id_usuario = e.id_usuario
        ORDER BY u.id_usuario";

$result = $conexion->query($sql);

$entrenadores = [];

while ($row = $result->fetch_assoc()) {
    $entrenadores[$row['id_usuario']]['nombre'] = $row['nombre'];
    if ($row['id_pokemon']) {
        $entrenadores[$row['id_usuario']]['pokemons'][] = $row['id_pokemon'];
    }
}
?>

<div class="row">
<?php foreach ($entrenadores as $id => $data): ?>
    <div class="col s12 m4">
        <div class="poke-card center-align">

            <h6><?php echo $data['nombre']; ?></h6>

            <?php if (!empty($data['pokemons'])): ?>
                <?php foreach ($data['pokemons'] as $poke): ?>
                    <img class="poke-img"
                    src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $poke; ?>.png">
                <?php endforeach; ?>
            <?php else: ?>
                <p>Sin Pokémon</p>
            <?php endif; ?>

            <?php if ($id == $id_usuario): ?>
                <p class="green-text">Tu equipo</p>
            <?php else: ?>
                <p class="grey-text">Solo ver</p>
            <?php endif; ?>

        </div>
    </div>
<?php endforeach; ?>
</div>

<!-- ========================= -->
<!-- 🔎 AUTOCOMPLETE -->
<!-- ========================= -->

<script>
document.addEventListener("DOMContentLoaded", function() {

    let listaPokemon = [];

    fetch("https://pokeapi.co/api/v2/pokemon?limit=151")
    .then(res => res.json())
    .then(data => listaPokemon = data.results);

    const input = document.getElementById("pokemonInput");
    const sugerencias = document.getElementById("sugerencias");
    const hidden = document.getElementById("pokemonID");

    input.addEventListener("input", function() {

        let valor = this.value.toLowerCase();
        sugerencias.innerHTML = "";

        if (valor.length === 0) return;

        let filtrados = listaPokemon
            .filter(p => p.name.includes(valor))
            .slice(0,5);

        filtrados.forEach(p => {
            let id = p.url.split("/")[6];

            let li = document.createElement("li");

            li.innerHTML = `
                <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${id}.png">
                <span>${p.name}</span>
            `;

            li.style.cursor = "pointer";
            li.style.padding = "10px";
            li.style.display = "flex";
            li.style.alignItems = "center";
            li.style.gap = "10px";

            li.onclick = () => {
                input.value = p.name;
                hidden.value = id;
                sugerencias.innerHTML = "";
            };

            sugerencias.appendChild(li);
        });
    });

});
</script>

<?php require "./footer.php"; ?>