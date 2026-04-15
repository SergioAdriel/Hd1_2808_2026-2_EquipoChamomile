<?php
/**
 * musica.php — Reproductor de música de fondo para la Pokédex
 * Incluir en footer.php justo antes de </body>:
 *   <?php require __DIR__ . '/musica.php'; ?>
 *
 * Archivos de música en: Proyecto/Controlador/Media/musica/
 */

if (!isset($pagina_actual)) {
    $pagina_actual = basename($_SERVER['PHP_SELF'], '.php');
}

$doc_root     = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
$musica_dir   = rtrim(__DIR__, '/\\');
$proyecto_url = str_replace('\\', '/', substr($musica_dir, strlen($doc_root)));
$base_musica  = $proyecto_url . '/Controlador/Media/musica/';

$mapa_musica = [
    'index'               => 'tema_principal.mp3',
    'batalla'             => 'tema_batalla.mp3',
    'combate'             => 'tema_combate.mp3',
    'equipo'              => 'tema_equipo.mp3',
    'retar'               => 'tema_combate.mp3',
    'verTodosPokemon'     => 'tema_pokedex.mp3',
    'login'               => 'tema_principal.mp3',
    'registroVista'       => 'tema_registro.mp3',
    'registro'            => 'tema_registro.mp3',
    'principal'           => 'tema_principal.mp3',
    'configuracion'       => 'tema_configuracion.mp3',
    'editarCuenta'        => 'tema_configuracion.mp3',
    'eliminarUsuario'     => 'tema_login.mp3',
    'eliminacionExitosa'  => 'tema_principal.mp3',
    'errorLoguin'         => 'tema_login.mp3',
    'politica_privacidad' => 'tema_principal.mp3',
];

$archivo     = $mapa_musica[$pagina_actual] ?? 'tema_principal.mp3';
$ruta_musica = $base_musica . $archivo;
?>

<!-- REPRODUCTOR DE MÚSICA — POKÉBALL FLOTANTE -->
<style>
  #poke-music-btn {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    width: 58px;
    height: 58px;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.5));
    transition: transform 0.15s ease;
  }
  #poke-music-btn:hover  { transform: scale(1.12); }
  #poke-music-btn:active { transform: scale(0.95); }
  #poke-music-btn.playing { animation: pokespin 3s linear infinite; }
  @keyframes pokespin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

  #poke-music-panel {
    position: fixed;
    bottom: 94px;
    right: 24px;
    z-index: 9998;
    width: 200px;
    background: #cc0000;
    border: 3px solid #7a0000;
    border-radius: 14px;
    padding: 12px 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.5);
    font-family: 'Press Start 2P', cursive;
    font-size: 7px;
    color: #fff;
    opacity: 0;
    transform: translateY(10px) scale(0.95);
    pointer-events: none;
    transition: opacity 0.2s ease, transform 0.2s ease;
  }
  #poke-music-panel.open {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: all;
  }
  #poke-music-panel .poke-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  #poke-close-btn {
    background: none;
    border: none;
    color: #ffaaaa;
    cursor: pointer;
    font-size: 11px;
    line-height: 1;
    padding: 0;
  }
  #poke-close-btn:hover { color: #fff; }
  .poke-divider {
    width: 100%;
    height: 3px;
    background: linear-gradient(to right, #fff 0%, #fff 46%, #333 46%, #333 54%, #fff 54%);
    margin-bottom: 10px;
    border-radius: 2px;
  }
  .poke-vol-row { display: flex; align-items: center; gap: 8px; }
  .poke-vol-row label { font-size: 6px; color: #ffd0d0; white-space: nowrap; }
  #poke-volume { flex: 1; accent-color: #fff; cursor: pointer; height: 4px; }
</style>

<button id="poke-music-btn" title="Volumen" aria-label="Abrir control de volumen">
  <svg viewBox="0 0 58 58" xmlns="http://www.w3.org/2000/svg" width="58" height="58">
    <path d="M4 29 A25 25 0 0 1 54 29 Z" fill="#e60000"/>
    <path d="M4 29 A25 25 0 0 0 54 29 Z" fill="#ffffff"/>
    <circle cx="29" cy="29" r="25" fill="none" stroke="#222" stroke-width="3"/>
    <line x1="4" y1="29" x2="54" y2="29" stroke="#222" stroke-width="3"/>
    <circle cx="29" cy="29" r="8" fill="#ffffff" stroke="#222" stroke-width="3"/>
    <circle cx="29" cy="29" r="4" fill="#eeeeee"/>
  </svg>
</button>

<div id="poke-music-panel" role="region" aria-label="Control de volumen">
  <div class="poke-title">
    <span>&#9834; MÚSICA</span>
    <button id="poke-close-btn" title="Cerrar">&#x2715;</button>
  </div>
  <div class="poke-divider"></div>
  <div class="poke-vol-row">
    <label for="poke-volume">VOL</label>
    <input type="range" id="poke-volume" min="0" max="1" step="0.05" value="0.4">
  </div>
</div>

<audio id="poke-audio" loop preload="auto">
  <source src="<?php echo htmlspecialchars($ruta_musica); ?>" type="audio/mpeg">
</audio>

<script>
(function () {
  var audio     = document.getElementById('poke-audio');
  var btn       = document.getElementById('poke-music-btn');
  var panel     = document.getElementById('poke-music-panel');
  var closeBtn  = document.getElementById('poke-close-btn');
  var volSlider = document.getElementById('poke-volume');

  // Restaurar volumen previo
  var savedVol = sessionStorage.getItem('poke_volume');
  if (savedVol !== null) {
    audio.volume    = parseFloat(savedVol);
    volSlider.value = savedVol;
  } else {
    audio.volume = 0.4;
  }

  // Intentar autoplay; si el navegador lo bloquea,
  // arranca en la primera interaccion del usuario
  function doPlay() {
    audio.play()
      .then(function () { btn.classList.add('playing'); })
      .catch(function () {});
  }

  audio.play()
    .then(function () { btn.classList.add('playing'); })
    .catch(function () {
      var eventos = ['click', 'keydown', 'touchstart'];
      function onInteraccion() {
        doPlay();
        eventos.forEach(function (ev) { document.removeEventListener(ev, onInteraccion); });
      }
      eventos.forEach(function (ev) { document.addEventListener(ev, onInteraccion, { once: true }); });
    });

  // Abrir / cerrar panel y play pausa
  btn.addEventListener('click', function (e) {
      e.stopPropagation();

      if (audio.paused) {
          audio.play().then(function () {
              btn.classList.add('playing');
          }).catch(function () {});
      } else {
          audio.pause();
          btn.classList.remove('playing');
      }
      
      panel.classList.toggle('open');
  });
  closeBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    panel.classList.remove('open');
  });
  document.addEventListener('click', function (e) {
    if (!panel.contains(e.target) && e.target !== btn) {
      panel.classList.remove('open');
    }
  });

  // Volumen
  volSlider.addEventListener('input', function () {
    audio.volume = parseFloat(this.value);
    sessionStorage.setItem('poke_volume', this.value);
  });

})();
</script>
<!-- FIN REPRODUCTOR DE MÚSICA -->