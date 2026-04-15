<?php

// ✅ Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Verificar que exista la sesión
if (!isset($_SESSION['trainer_id'])) {
    header("Location: ./login.php");
    exit;
}

require __DIR__ . "/conexion.php";
require __DIR__ . "/../header.php";
require __DIR__ . '/../musica.php';

// Obtener datos actuales del usuario
$trainer_id = $_SESSION['trainer_id'];

$sql = "SELECT nombre, telefono FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $trainer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ./configuracion.php?error=usuario_no_encontrado");
    exit;
}

$usuario = $result->fetch_assoc();
$stmt->close();
$conexion->close();
?>

<div class="container center-align" style="margin-top: 40px;">

    <h4>Editar mis datos</h4>

    <!-- 🔥 MENSAJES DE ERROR/ÉXITO (formato del segundo script) -->
    <?php if (isset($_GET['error'])): ?>
        <div class="card red lighten-4" style="padding:10px; margin-bottom:20px;">
            <span class="red-text text-darken-2">
                <?php
                if ($_GET['error'] == 'campos_vacios') {
                    echo "Todos los campos son obligatorios";
                } elseif ($_GET['error'] == 'password_no_coinciden') {
                    echo "Las contraseñas no coinciden";
                } elseif ($_GET['error'] == 'telefono_invalido') {
                    echo "El teléfono debe tener al menos 10 dígitos y solo números";
                } elseif ($_GET['error'] == 'duplicado') {
                    echo "El nombre o teléfono ya está registrado por otro usuario";
                } elseif ($_GET['error'] == 'db_error') {
                    echo "Error en la base de datos, intente nuevamente";
                } elseif ($_GET['error'] == 'usuario_no_encontrado') {
                    echo "Usuario no encontrado";
                } else {
                    echo "Error al actualizar los datos";
                }
                ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'actualizado'): ?>
        <div class="card green lighten-4" style="padding:10px; margin-bottom:20px;">
            <span class="green-text text-darken-2">
                Datos actualizados correctamente
            </span>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <div class="card" style="padding: 30px; max-width: 500px; margin: 20px auto;">
        
        <form action="./actualizarCuenta.php" method="POST" id="formEditar">
            
            <!-- 👤 NOMBRE -->
            <div class="input-field">
                <input type="text" name="nombre" id="nombre" 
                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                <label for="nombre">Nombre completo</label>
            </div>

            <!-- 📱 TELÉFONO -->
            <div class="input-field">
                <input type="tel" name="telefono" id="telefono" 
                       value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required
                       pattern="[0-9]{10,}"
                       title="Debe contener al menos 10 dígitos, solo números">
                <label for="telefono">Número de teléfono</label>
            </div>

            <!-- 🔑 CONTRASEÑA -->
            <div class="input-field">
                <input type="password" name="clave" id="clave" required>
                <label for="clave">Nueva contraseña</label>
                <span class="helper-text">Debe escribir su contraseña (nueva o actual)</span>
            </div>

            <!-- 🔑 CONFIRMAR CONTRASEÑA -->
            <div class="input-field">
                <input type="password" name="confirmar_clave" id="confirmar_clave" required>
                <label for="confirmar_clave">Confirmar contraseña</label>
            </div>

            <br>

            <div class="row">
                <div class="col s6">
                    <button type="submit" class="btn green waves-effect waves-light" style="width: 100%;">
                        Actualizar datos
                    </button>
                </div>
                <div class="col s6">
                    <a href="./configuracion.php" class="btn red waves-effect waves-light" style="width: 100%;">
                        Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <br><br>

    <!-- 🔥 BOTÓN REGRESAR -->
    <a href="./principal.php" class="btn blue">
        Volver al panel principal
    </a>

</div>

<script>
// Validación en tiempo real
document.getElementById('formEditar').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const clave = document.getElementById('clave').value;
    const confirmar = document.getElementById('confirmar_clave').value;
    
    // Limpiar mensajes de error anteriores
    const existingErrors = document.querySelectorAll('.error-message');
    existingErrors.forEach(error => error.remove());
    
    let errorMessage = '';
    
    // Validar que ningún campo esté vacío
    if (nombre === '' || telefono === '' || clave === '' || confirmar === '') {
        errorMessage = 'Todos los campos son obligatorios';
    }
    // Validar que las contraseñas coincidan
    else if (clave !== confirmar) {
        errorMessage = 'Las contraseñas no coinciden';
    }
    // Validar teléfono (solo números, al menos 10 dígitos)
    else if (!/^[0-9]{10,}$/.test(telefono)) {
        errorMessage = 'El teléfono debe tener al menos 10 dígitos y solo números';
    }
    
    if (errorMessage) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'card red lighten-4 error-message';
        errorDiv.style.padding = '10px';
        errorDiv.style.marginBottom = '20px';
        errorDiv.innerHTML = `<span class="red-text text-darken-2">${errorMessage}</span>`;
        
        const form = document.getElementById('formEditar');
        form.parentNode.insertBefore(errorDiv, form);
        
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        e.preventDefault();
        return false;
    }
    
    return true;
});
</script>

<?php require __DIR__ . "/../footer.php"; ?>