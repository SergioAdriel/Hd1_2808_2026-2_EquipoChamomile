<?php
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: ./login.php");
    exit;
}

// ✅ DEFINIR $accion AQUÍ (antes de usarla)
$accion = $_GET['accion'] ?? null;

require __DIR__ . "/../header.php";

// Mostrar mensajes de éxito o error
$mensaje = '';
$tipo_mensaje = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'actualizado') {
        $mensaje = '¡Datos actualizados correctamente!';
        $tipo_mensaje = 'green';
    }
}

if (isset($_GET['error'])) {
    switch($_GET['error']) {
        case 'campos_vacios':
            $mensaje = 'Todos los campos son obligatorios';
            $tipo_mensaje = 'red';
            break;
        case 'password_no_coinciden':
            $mensaje = 'Las contraseñas no coinciden';
            $tipo_mensaje = 'red';
            break;
        case 'telefono_invalido':
            $mensaje = 'El teléfono debe tener al menos 10 dígitos y solo números';
            $tipo_mensaje = 'red';
            break;
        case 'duplicado':
            $mensaje = 'El nombre o teléfono ya está registrado por otro usuario';
            $tipo_mensaje = 'red';
            break;
        case 'db_error':
            $mensaje = 'Error en la base de datos, intente nuevamente';
            $tipo_mensaje = 'red';
            break;
        case 'usuario_no_encontrado':
            $mensaje = 'Usuario no encontrado';
            $tipo_mensaje = 'red';
            break;
    }
}
?>

<div class="container center-align">

    <!-- 🔥 MOSTRAR MENSAJE (si existe) -->
    <?php if ($mensaje): ?>
        <div class="card-panel <?php echo $tipo_mensaje; ?> white-text" style="margin-top: 20px;">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <!-- 🔹 MENÚ PRINCIPAL (SIEMPRE SE MUESTRA, HAYA O NO ERROR) -->
    <h4>Configuración de cuenta</h4>

        <br><br>
        
        <div class="card" style="padding: 30px; max-width: 500px; margin: 0 auto;">
            <a href="./editarCuenta.php" class="btn green waves-effect waves-light" style="width: 80%; margin: 10px auto;">
                Editar datos
            </a>
            <br><br>
            <a href="./eliminarUsuario.php" class="btn red waves-effect waves-light" style="width: 80%; margin: 10px auto;">
                Eliminar cuenta
            </a>
            <br><br>
            <a href="./principal.php" class="btn blue waves-effect waves-light" style="width: 80%; margin: 10px auto;">
                Volver al panel
            </a>
        </div>
    

</div>

<?php require __DIR__ . "/../footer.php"; ?>