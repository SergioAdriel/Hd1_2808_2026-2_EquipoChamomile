<!-- Encabeezado de página con enlaces útiles y datos de contacto -->
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Metaetiquetas que especifican el tipo de documento y el lenguaje de la página -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Condominio Citisio</title>
  <!-- Favicon -->
  <link rel="icon" href="./Media/favicon/favicon.png" type="image/png"/>

  <!-- Inclusión de fuentes y estilos para Materialize (biblioteca de CSS y JS para interfaces modernas) -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
<header class="header-section" style="margin-bottom: 20px;">
  <!-- Barra de navegación con Materialize -->
  <nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <!-- Logo del sitio web -->
      <a id="logo-container" href="index.php" class="brand-logo">Condominio Citisio</a>
      
      <!-- Menú de navegación para pantallas grandes (no se muestra en dispositivos medianos o pequeños) -->
      <ul class="right hide-on-med-and-down">
        <li><a href="#">Servicios</a></li>
        <li><a href="mailto:contacto@citisio.com">Ayuda</a></li>
      </ul>

      <!-- Menú de navegación para dispositivos pequeños (sidenav que se muestra al hacer clic en el ícono de menú) -->
      <ul id="nav-mobile" class="sidenav">
        <li><a href="#">Servicios</a></li>
        <li><a href="mailto:contacto@citisio.com">Ayuda</a></li>
      </ul>
      <!-- Botón que activa el menú lateral (sidenav) en dispositivos pequeños -->
      <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
  </nav>
</header>