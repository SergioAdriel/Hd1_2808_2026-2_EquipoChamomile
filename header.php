<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pokédex</title>
  <link rel="icon" href="/Controlador/Media/favicon/favicon.png" type="image/png"/>

  <!-- Materialize CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet"/>
  <link href="css/style.css" type="text/css" rel="stylesheet"/>

  <!-- NES.css (retro) -->
  <link href="https://unpkg.com/nes.css@2.3.0/css/nes.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet" />

  <style>
    body {
      font-family: 'Press Start 2P', cursive;
    }
  </style>
</head>
<body>
<header class="header-section" style="margin-bottom: 20px;">
  <!-- Barra de navegación -->
  <nav class="red darken-2" role="navigation">
    <div class="nav-wrapper container">
      <!-- Logo / Link principal -->
<a id="logo-container" href="index.php" class="brand-logo white-text" style="font-weight: bold; font-family: 'Roboto', sans-serif; display: flex; align-items: center;">
  <i class="nes-pokeball" style="font-size: 2rem;"></i>
</a>
      <!-- Menú de navegación para pantallas grandes -->
      <ul class="right hide-on-med-and-down">
        <li><a href="index.php" class="white-text" style="font-weight: bold;">Registro</a></li>
        <li><a href="combate.php" class="white-text" style="font-weight: bold;">Combate</a></li>
      </ul>

      <!-- Menú lateral para dispositivos pequeños -->
      <ul id="nav-mobile" class="sidenav">
        <li><a href="index.php">Registro</a></li>
        <li><a href="combate.php">Combate</a></li>
      </ul>
      <!-- Botón que activa el sidenav -->
      <a href="#" data-target="nav-mobile" class="sidenav-trigger white-text"><i class="material-icons">menu</i></a>
    </div>
  </nav>
</header>