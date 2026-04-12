<?php require __DIR__ . "/../header.php"; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar sesión
$trainerId = $_SESSION['trainer_id'] ?? null;
$trainerName = $_SESSION['trainer_name'] ?? 'Entrenador';

if (!$trainerId) {
    header("Location: ../index.php");
    exit;
}
?>

<div class="container" style="max-width: 500px; margin-top: 50px;">
    
    <h4 class="center-align red-text text-darken-2">
        Eliminar Cuenta
    </h4>

    <p class="center-align">
        ¿Estás seguro de que quieres eliminar tu cuenta, 
        <strong><?php echo htmlspecialchars($trainerName); ?></strong>?
    </p>

    <p class="center-align grey-text">
        ⚠️ Esta acción eliminará tu equipo Pokémon y estadísticas de combate.
    </p>

    <!-- Formulario seguro -->
    <form method="POST" action="./deleteUsuario.php">

        <!-- Mandamos el ID oculto -->
        <input type="hidden" name="id_usuario" value="<?php echo $trainerId; ?>">

        <div class="center-align" style="margin-top: 30px;">
            <button type="submit" class="btn waves-effect waves-light red lighten-1">
                Sí, eliminar mi cuenta
            </button>
        </div>
    </form>

    <!-- Cancelar -->
    <div class="center-align" style="margin-top: 20px;">
        <a href="./principal.php" class="btn waves-effect waves-light teal lighten-1">
            Cancelar
        </a>
    </div>

</div>

<?php require __DIR__ . "/../footer.php"; ?>