<?php include 'public/header.php'; ?>
<?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

<div class="container mt-4" style="max-width:600px;">
    <div class="card shadow p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Registrar Usuario</h2>
            <a href="?controlador=Usuario&accion=mostrarListar" class="btn btn-secondary btn-sm">← Volver</a>
        </div>

        <?php if ($status === 'invalido'): ?>
            <div class="alert alert-warning">Todos los campos son obligatorios.</div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger">Error al registrar. El correo puede ya estar en uso o el rol no existe.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Usuario&accion=registrar" onsubmit="return validarRegistro();">

            <div class="mb-3">
                <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: María Rodríguez" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                <input type="email" name="correo" class="form-control" placeholder="usuario@ucr.ac.cr" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                <input type="password" id="confirmar" class="form-control" required>
                <div id="msgConfirmar" class="form-text text-danger d-none">Las contraseñas no coinciden.</div>
            </div>

            <div class="mb-4">
                <label class="form-label">Rol del sistema <span class="text-danger">*</span></label>
                <select name="id_rol" class="form-select" required>
                    <option value="">— Seleccione un rol —</option>
                    <option value="1">Administrador</option>
                    <option value="2">Curador</option>
                    <option value="3">Estudiante</option>
                </select>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">Crear Usuario</button>
            </div>

        </form>
    </div>
</div>

<script>
function validarRegistro() {
    const pass    = document.getElementById('contrasena').value;
    const confirm = document.getElementById('confirmar').value;
    if (pass !== confirm) {
        document.getElementById('msgConfirmar').classList.remove('d-none');
        return false;
    }
    return true;
}
document.getElementById('confirmar').addEventListener('input', function () {
    const match = this.value === document.getElementById('contrasena').value;
    document.getElementById('msgConfirmar').classList.toggle('d-none', match);
});
</script>

<?php include 'public/footer.php'; ?>
