<?php require "./header.php"; ?>

<?php
session_start();
$trainerId = $_SESSION['trainer_id'] ?? null;
$trainerName = $_SESSION['trainer_name'] ?? 'Entrenador';

if (!$trainerId) {
    header("location: ./index.php");
    exit;
}
?>

<!-- Formulario de registro de Pokémon -->
<div class="container" style="max-width: 600px; margin-top: 20px;">
    <h4 class="center-align blue-text text-darken-2">Registro de Pokémon (Pokédex)</h4>
    <p class="center-align">Entrenador: <strong><?php echo htmlspecialchars($trainerName); ?></strong> (ID <?php echo htmlspecialchars($trainerId); ?>)</p>

    <form action="./Controlador/enviarRegistro.php" method="post" style="margin-top: 20px;">
        <div class="input-field">
            <input type="text" id="nombre" name="nombre" required maxlength="50" placeholder="Nombre del Pokémon">
            <label for="nombre">Nombre del Pokémon</label>
        </div>
        
        <div class="input-field">
            <textarea id="descripcion" name="descripcion" class="materialize-textarea" required maxlength="255" placeholder="Descripción del Pokémon"></textarea>
            <label for="descripcion">Descripción</label>
        </div>

        <div class="center-align" style="margin-top: 20px;">
            <button type="submit" name="submit" class="btn waves-effect waves-light blue lighten-1">Guardar Pokémon</button>
        </div>
    </form>

    <div class="center-align" style="margin-top: 20px;">
        <a href="principal.php" class="btn waves-effect waves-light teal lighten-1">Volver</a>
    </div>
</div>

<?php require "./footer.php"; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        M.AutoInit();
    });
</script>
