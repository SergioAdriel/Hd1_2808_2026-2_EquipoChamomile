<?php 
require "./header.php";
?>

<!-- Banner principal con imagen de Lapras y título dentro de cajita -->
<div id="index-banner" class="parallax-container">
  <div class="section no-pad-bot">
    <div class="container center" style="padding-top: 80px;">
      <!-- Cajita blanca con sombra -->
      <div class="white-text-box">
        <h1 class="header black-text" style="font-weight: bold; font-size: 4rem;">Pokédex</h1>
        <h5 class="header black-text" style="font-weight: bold; font-size: 1.5rem;">Registra y gestiona tus Pokémon favoritos</h5>
      </div>

      <!-- Botón de registro -->
      <div class="row center" style="margin-top: 30px;">
        <a href="login.php" id="login-button" class="btn-large waves-effect waves-light blue darken-2 hoverable">
          Iniciar Registro
        </a>
      </div>
    </div>
  </div>
  <div class="parallax"><img src="./Controlador/Media/img/test3.png" alt="Fondo de Lapras"></div>
</div>

<!-- Sección de características con estilo Pokédex y NES.css -->
<div class="nes-container is-rounded" style="padding: 20px; margin: 20px 0; background-color: #f0f0f0;">
  <div class="row" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">

    <!-- 1. Bulbasaur (#001) -->
    <div class="col" style="flex: 1 1 250px;">
      <div class="nes-container with-title is-rounded" style="background-color: #E1FFE6; padding: 20px;">
        <p class="title">Gestión de Pokémon</p>
        <div class="center" style="font-size: 60px; margin: 10px 0; color: #4CAF50;">
          <i class="nes-bulbasaur"></i>
        </div>
        <p class="center" style="color: #333;">
          Añade, edita y organiza tus Pokémon para mantener tu Pokédex completa y ordenada.
        </p>
      </div>
    </div>

    <!-- 2. Charmander (#004) -->
    <div class="col" style="flex: 1 1 250px;">
      <div class="nes-container with-title is-rounded" style="background-color: #FFF0E1; padding: 20px;">
        <p class="title">Registro Seguro</p>
        <div class="center" style="font-size: 60px; margin: 10px 0; color: #FF5722;"> 
          <i class="nes-charmander"></i>
        </div>
        <p class="center" style="color: #333;">
          Tus Pokémon y datos personales estarán protegidos en nuestra Pokédex.
        </p>
      </div>
    </div>

    <!-- 3. Squirtle (#007) -->
    <div class="col" style="flex: 1 1 250px;">
      <div class="nes-container with-title is-rounded" style="background-color: #E1F0FF; padding: 20px;">
        <p class="title">Alertas</p>
        <div class="center" style="font-size: 60px; margin: 10px 0; color: #2196F3;">
          <i class="nes-squirtle"></i>
        </div>
        <p class="center" style="color: #333;">
          Recibe notificaciones de nuevos Pokémon o eventos especiales en tu Pokédex.
        </p>
      </div>
    </div>

  </div>
</div>

  </div>
</div>
  </div>
</div>
<?php
require "./footer.php";
?>