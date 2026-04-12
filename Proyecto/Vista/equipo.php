<?php
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: ../Controlador/login.php");
    exit;
}

require __DIR__ . "/../header.php";
require __DIR__ . "/../Controlador/conexion.php";

$id_usuario = $_SESSION['trainer_id'];
$nombre = $_SESSION['trainer_name'];

// 🔢 contar Pokémon
$sql = "SELECT COUNT(*) total FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['total'];

$error = $_GET['error'] ?? '';
$ok = $_GET['ok'] ?? '';
?>

<link rel="stylesheet" href="./css/PokeCSS.css">

<div class="container center-align">

    <h4>Editar equipo</h4>
    <a href="../Controlador/principal.php" class="btn blue">Volver</a>
    <a href="./verTodosPokemon.php" class="btn red">Consultar Pokedex</a>
    <br><br>
    <h5>Equipo de <?php echo $nombre; ?>.</h5>

    <!-- MENSAJES -->

<?php if ($count >= 6): ?>
    <div class="card red lighten-4" style="padding:10px; margin-bottom:20px;">
        <span class="red-text text-darken-2">
            Equipo completo (6 Pokémon).
        </span>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="card red lighten-4" style="padding:10px; margin-bottom:20px;">
        <span class="red-text text-darken-2">
            <?php
            if ($error === 'repetido') {
                echo "Ya tienes ese Pokémon en tu equipo.";
            } elseif ($error === 'vacio') {
                echo "Debes seleccionar un Pokémon válido.";
            } elseif ($error === 'noexiste') {
                echo "Ese Pokémon no existe en tu equipo.";
            } elseif ($error === 'limite') {
                echo "No puedes tener más de 6 Pokémon.";
            }
            ?>
        </span>
    </div>
<?php endif; ?>

<?php if ($ok): ?>
    <div class="card green lighten-4" style="padding:10px; margin-bottom:20px;">
        <span class="green-text text-darken-2">
            <?php
            if ($ok === '1') {
                echo "Pokémon agregado correctamente.";
            } elseif ($ok === 'eliminado') {
                echo "Pokémon eliminado correctamente.";
            }
            ?>
        </span>
    </div>
<?php endif; ?>

<br>

</div>
<!-- ========================= -->
<!-- 🧬 TU EQUIPO -->
<!-- ========================= -->
<div class="row">
<?php
$sql = "SELECT id_pokemon FROM equipo WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Obtener nombres de Pokémon desde la PokeAPI
while ($row = $result->fetch_assoc()):
    $pokemon_id = $row['id_pokemon'];
    
    // Obtener el nombre del Pokémon desde la API
    $pokemon_name = "";
    $api_url = "https://pokeapi.co/api/v2/pokemon/" . $pokemon_id;
    $response = @file_get_contents($api_url);
    
    if ($response !== false) {
        $pokemon_data = json_decode($response, true);
        $pokemon_name = $pokemon_data['name'] ?? "";
    }
    
    // Si no se pudo obtener de la API, usar un nombre por defecto
    if (empty($pokemon_name)) {
        $pokemon_name = "Pokémon #" . $pokemon_id;
    }
?>
    <div class="col s12 m3 center-align">
        <div class="poke-card">

            <img class="poke-img"
            src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?php echo $pokemon_id; ?>.png">

            <p class="pokemon-name"><?php echo ucfirst($pokemon_name); ?></p>
            <p>#<?php echo $pokemon_id; ?></p>

            <!-- 🔥 ELIMINAR -->
            <form method="POST" action="../Controlador/eliminarPokemon.php">
                <input type="hidden" name="pokemon" value="<?php echo $pokemon_id; ?>">
                <button class="btn red"
                    onclick="return confirm('¿Eliminar <?php echo ucfirst($pokemon_name); ?> de tu equipo?');">
                    Eliminar
                </button>
            </form>

        </div>
    </div>
<?php endwhile; ?>
</div>

<!-- ========================= -->
<!-- ➕ AGREGAR POKÉMON -->
<!-- ========================= -->

<div class="container center-align">

<!-- SOLO SI HAY ESPACIO -->
<?php if ($count < 6): ?>

    <h5>Agregar Pokémon</h5>
    
    <!-- Selector de tipo de búsqueda -->
    <div class="search-type-selector">
        <button type="button" class="search-type-btn active" id="searchByNameBtn">Buscar por Nombre</button>
        <button type="button" class="search-type-btn inactive" id="searchByNumberBtn">Buscar por Número</button>
    </div>
    
    <!-- Búsqueda por nombre -->
    <div id="searchByName" class="search-container">
        <input type="text" id="pokemonInput" placeholder="Escribe un Pokémon">
        <ul id="sugerencias"></ul>
    </div>
    
    <!-- Búsqueda por número -->
    <div id="searchByNumber" style="display: none;" class="search-container">
        <input type="number" id="pokemonNumberInput" placeholder="Número del Pokémon (1-151)" min="1" max="151">
        <ul id="sugerenciasNumber"></ul>
    </div>
    
    <form method="POST" action="../Controlador/agregarPokemon.php" id="addPokemonForm">
        <input type="hidden" name="pokemon" id="pokemonID">
        <br>
        <button class="btn green">Agregar</button>
    </form>

<?php endif; ?>

</div>

<!-- ========================= -->
<!-- 🔎 AUTOCOMPLETE Y FUNCIONES -->
<!-- ========================= -->

<script>
document.addEventListener("DOMContentLoaded", function() {

    let listaPokemon = [];
    let searchType = 'name'; // 'name' o 'number'

    // Cargar lista de Pokémon
    fetch("https://pokeapi.co/api/v2/pokemon?limit=151")
    .then(res => res.json())
    .then(data => {
        listaPokemon = data.results;
        // Crear un mapa de número a nombre para búsqueda por número
        window.pokemonMap = {};
        listaPokemon.forEach((pokemon, index) => {
            const id = parseInt(pokemon.url.split("/")[6]);
            window.pokemonMap[id] = pokemon.name;
        });
    });

    // Elementos del DOM
    const searchByNameDiv = document.getElementById("searchByName");
    const searchByNumberDiv = document.getElementById("searchByNumber");
    const searchByNameBtn = document.getElementById("searchByNameBtn");
    const searchByNumberBtn = document.getElementById("searchByNumberBtn");
    const inputName = document.getElementById("pokemonInput");
    const inputNumber = document.getElementById("pokemonNumberInput");
    const sugerenciasName = document.getElementById("sugerencias");
    const sugerenciasNumber = document.getElementById("sugerenciasNumber");
    const hidden = document.getElementById("pokemonID");
    const addForm = document.getElementById("addPokemonForm");

    // Función para cambiar tipo de búsqueda
    function setSearchType(type) {
        searchType = type;
        
        if (type === 'name') {
            searchByNameDiv.style.display = 'block';
            searchByNumberDiv.style.display = 'none';
            searchByNameBtn.className = 'search-type-btn active';
            searchByNumberBtn.className = 'search-type-btn inactive';
            inputName.value = '';
            sugerenciasName.innerHTML = '';
        } else {
            searchByNameDiv.style.display = 'none';
            searchByNumberDiv.style.display = 'block';
            searchByNameBtn.className = 'search-type-btn inactive';
            searchByNumberBtn.className = 'search-type-btn active';
            inputNumber.value = '';
            sugerenciasNumber.innerHTML = '';
        }
        hidden.value = '';
    }
    // Event listeners para los botones de tipo de búsqueda
    searchByNameBtn.addEventListener('click', () => setSearchType('name'));
    searchByNumberBtn.addEventListener('click', () => setSearchType('number'));

    // Búsqueda por nombre con autocompletado
    if (inputName) {
        inputName.addEventListener("input", function() {
            let valor = this.value.toLowerCase();
            sugerenciasName.innerHTML = "";

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
                    inputName.value = p.name;
                    hidden.value = id;
                    sugerenciasName.innerHTML = "";
                };

                sugerenciasName.appendChild(li);
            });
        });
    }

    // Búsqueda por número
    if (inputNumber) {
        inputNumber.addEventListener("input", function() {
            let valor = parseInt(this.value);
            sugerenciasNumber.innerHTML = "";

            if (isNaN(valor) || valor < 1 || valor > 151) {
                if (this.value !== "") {
                    let li = document.createElement("li");
                    li.innerHTML = `<span style="color: red;">Número inválido (1-151)</span>`;
                    li.style.padding = "10px";
                    li.style.textAlign = "center";
                    sugerenciasNumber.appendChild(li);
                }
                hidden.value = '';
                return;
            }

            // Buscar el Pokémon por número
            let pokemonName = window.pokemonMap[valor];
            if (pokemonName) {
                let li = document.createElement("li");
                li.innerHTML = `
                    <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${valor}.png">
                    <span>${pokemonName} (#${valor})</span>
                `;
                li.style.cursor = "pointer";
                li.style.padding = "10px";
                li.style.display = "flex";
                li.style.alignItems = "center";
                li.style.gap = "10px";

                li.onclick = () => {
                    inputNumber.value = valor;
                    hidden.value = valor;
                    sugerenciasNumber.innerHTML = "";
                };

                sugerenciasNumber.appendChild(li);
            }
        });
    }

    // Validar que se haya seleccionado un Pokémon antes de enviar el formulario
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            if (!hidden.value) {
                e.preventDefault();
                let message = '';
                if (searchType === 'name') {
                    message = 'Por favor, selecciona un Pokémon de la lista de sugerencias';
                } else {
                    message = 'Por favor, selecciona un número válido de Pokémon';
                }
                M.toast({html: message, classes: 'red'});
            }
        });
    }

});
</script>

<?php require __DIR__ . "/../footer.php"; ?>