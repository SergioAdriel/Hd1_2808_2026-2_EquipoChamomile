<?php
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: login.php");
    exit;
}

require "./header.php";
?>

<!-- Enlazar el archivo CSS externo -->
<link rel="stylesheet" href="css/modalsPokedex.css">

<div class="container">
    <div class="center-align">
        <h4>Pokedex</h4>
        <a href="equipo.php" class="btn blue">Volver al equipo</a>
        <br><br>
    </div>
    
    <div class="row" id="pokemonList">
        <div class="loading">
            <div class="preloader-wrapper active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Cargando Pokémon...</p>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="pokemonModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div class="center-align">
            <img id="modalPokemonImg" class="modal-pokemon-img" src="" alt="">
            <h3 id="modalPokemonName"></h3>
            <p id="modalPokemonNumber" class="pokemon-number"></p>
        </div>
        
        <div class="pokemon-detail">
            <h5>Información Básica</h5>
            <div class="detail-row">
                <span class="detail-label">Altura:</span>
                <span id="modalHeight"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Peso:</span>
                <span id="modalWeight"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Experiencia Base:</span>
                <span id="modalBaseExp"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tipos:</span>
                <span id="modalTypes"></span>
            </div>
        </div>
        
        <div class="pokemon-detail">
            <h5>Estadísticas Base</h5>
            <div id="modalStats"></div>
        </div>
    </div>
</div>

<script>
let currentModal = null;

// Diccionario de traducción de tipos de Pokémon
const typeTranslations = {
    'normal': 'Normal',
    'fire': 'Fuego',
    'water': 'Agua',
    'electric': 'Eléctrico',
    'grass': 'Planta',
    'ice': 'Hielo',
    'fighting': 'Lucha',
    'poison': 'Veneno',
    'ground': 'Tierra',
    'flying': 'Volador',
    'psychic': 'Psíquico',
    'bug': 'Bicho',
    'rock': 'Roca',
    'ghost': 'Fantasma',
    'dragon': 'Dragón',
    'dark': 'Siniestro',
    'steel': 'Acero',
    'fairy': 'Hada'
};

// Función para traducir tipos
function translateType(type) {
    return typeTranslations[type] || type;
}

// Valores máximos generales para cada estadística
const maxStats = {
    'hp': 255,
    'attack': 190,
    'defense': 230,
    'special-attack': 194,
    'special-defense': 230,
    'speed': 200
};

async function cargarTodosPokemon() {
    try {
        const response = await fetch("https://pokeapi.co/api/v2/pokemon?limit=151");
        const data = await response.json();
        
        const pokemonList = document.getElementById("pokemonList");
        pokemonList.innerHTML = "";
        
        for (const pokemon of data.results) {
            const pokemonId = pokemon.url.split("/")[6];
            
            const col = document.createElement("div");
            col.className = "col s12 m3 center-align";
            
            col.innerHTML = `
                <div class="poke-card" data-pokemon-id="${pokemonId}" data-pokemon-url="${pokemon.url}">
                    <img class="poke-img"
                         src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${pokemonId}.png"
                         alt="${pokemon.name}">
                    <p class="pokemon-name">${pokemon.name}</p>
                    <p class="pokemon-number">#${pokemonId}</p>
                </div>
            `;
            
            // Agregar evento click a la tarjeta
            const card = col.querySelector('.poke-card');
            card.addEventListener('click', () => abrirModal(pokemon.url, pokemonId, pokemon.name));
            
            pokemonList.appendChild(col);
        }
        
        // Inicializar modal
        const modalElement = document.getElementById('pokemonModal');
        currentModal = M.Modal.init(modalElement, {
            dismissible: true,
            opacity: 0.5,
            inDuration: 300,
            outDuration: 200
        });
        
        // Cerrar modal con el botón
        document.querySelector('.modal-close').addEventListener('click', () => {
            currentModal.close();
        });
        
    } catch (error) {
        console.error("Error cargando Pokémon:", error);
        document.getElementById("pokemonList").innerHTML = `
            <div class="center-align red-text">
                <h5>Error al cargar los Pokémon</h5>
                <p>Por favor, intenta de nuevo más tarde.</p>
            </div>
        `;
    }
}

async function abrirModal(url, id, name) {
    try {
        const response = await fetch(url);
        const pokemon = await response.json();
        
        // Actualizar contenido del modal
        document.getElementById('modalPokemonName').textContent = name.charAt(0).toUpperCase() + name.slice(1);
        document.getElementById('modalPokemonNumber').textContent = `#${id}`;
        document.getElementById('modalPokemonImg').src = pokemon.sprites.front_default;
        
        // Información básica
        document.getElementById('modalHeight').textContent = `${pokemon.height / 10} m`;
        document.getElementById('modalWeight').textContent = `${pokemon.weight / 10} kg`;
        document.getElementById('modalBaseExp').textContent = pokemon.base_experience;
        
        // Tipos - Ahora traducidos al español
        const typesContainer = document.getElementById('modalTypes');
        typesContainer.innerHTML = '';
        pokemon.types.forEach(type => {
            const typeSpan = document.createElement('span');
            const originalType = type.type.name;
            const translatedType = translateType(originalType);
            typeSpan.className = `type-badge type-${originalType}`;
            typeSpan.textContent = translatedType;
            typesContainer.appendChild(typeSpan);
        });
        
        // Estadísticas con formato PS: X/Y
        const statsContainer = document.getElementById('modalStats');
        statsContainer.innerHTML = '';
        
        const statNames = {
            'hp': 'PS',
            'attack': 'Ataque',
            'defense': 'Defensa',
            'special-attack': 'Ataque Especial',
            'special-defense': 'Defensa Especial',
            'speed': 'Velocidad'
        };
        
        pokemon.stats.forEach(stat => {
            const statName = statNames[stat.stat.name] || stat.stat.name;
            const currentValue = stat.base_stat;
            const maxValue = maxStats[stat.stat.name] || 255;
            
            const statDiv = document.createElement('div');
            statDiv.className = 'stat-item';
            statDiv.innerHTML = `
                <div class="detail-row">
                    <span class="detail-label">${statName}:</span>
                    <span class="stat-value">${currentValue}/${maxValue}</span>
                </div>
            `;
            statsContainer.appendChild(statDiv);
        });
        
        // Abrir modal
        currentModal.open();
        
    } catch (error) {
        console.error("Error cargando detalles del Pokémon:", error);
        M.toast({html: 'Error al cargar los detalles del Pokémon', classes: 'red'});
    }
}

document.addEventListener("DOMContentLoaded", cargarTodosPokemon);
</script>

<?php require "./footer.php"; ?>