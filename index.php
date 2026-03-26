<?php
require "./header.php";
?>

  <!-- Sección principal con una imagen de fondo (parallax) y un título con un botón de inicio de sesión -->
  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <h1 class="header center teal-text text-lighten-2 text-">Pokédex Interactiva</h1>
        <div class="row center">
          <h5 class="header col s12 light text-grey text-lighten-2">Explora el mundo Pokémon y registra tus entrenadores</h5>
        </div>
        <div class="row center">
          <!-- Botón que redirige al login -->
          <a href="login.php" id="login-button" class="btn-large waves-effect waves-light teal lighten-1">Entrar como Entrenador</a>
        </div>
        <div class="row center" style="margin-top: 8px;">
          <p>Usa credenciales predefinidas: ID=1 / contraseña=pikachu123 o ID=2 / contraseña=staryu456</p>
        </div>
      </div>
    </div>
    <!-- Imagen de fondo con efecto parallax -->
    <div class="parallax"><img src="./Media/img/palmera.jpg" alt="Fondo de Palmera"></div>
  </div>

  <!-- Sección de contenido con tres bloques de servicios -->
  <div class="container">
    <div class="section">
      <div class="row">
        <!-- Primer bloque de servicio: Seguridad -->
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text text-darken-4"><i class="material-icons">security</i></h2>
            <h5 class="center brown-text text-darken-2">Registro de Entrenadores</h5>
            <p class="light">Controla el acceso al sistema para asegurar que solo entrenadores registrados puedan acceder a la Pokédex.</p>
          </div>
        </div>
  
        <!-- Segundo bloque de servicio: Gestión de Propiedades -->
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text text-darken-4"><i class="material-icons">pets</i></h2>
            <h5 class="center brown-text text-darken-2">Gestión de Pokémon</h5>
            <p class="light">Lleva un registro de los Pokémon y sus características, facilitando la exploración y el aprendizaje.</p>
          </div>
        </div>
  
        <!-- Tercer bloque de servicio: Notificaciones -->
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text text-darken-4"><i class="material-icons">explore</i></h2>
            <h5 class="center brown-text text-darken-2">Exploración</h5>
            <p class="light">Descubre nuevas especies Pokémon y mantén actualizada tu colección con la información más reciente.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
  require "./footer.php"
?>