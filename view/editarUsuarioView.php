<?php include 'public/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Editar Usuario</h2>

        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
        <?php if($status == 'error'): ?>
            <div class="alert alert-danger">Error al actualizar el usuario.</div>
        <?php elseif($status == 'invalido'): ?>
            <div class="alert alert-warning">Todos los campos son obligatorios.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Usuario&accion=editar" id="formEditarUsuario" onsubmit="return confirmarEdicion()">
            
            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" value="<?php echo $usuario['correo']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol del Sistema</label>
                <select name="id_rol" id="id_rol" class="form-control" required>
                    <option value="1" <?php echo ($usuario['id_rol'] == 1) ? 'selected' : ''; ?>>Administrador</option>
                    <option value="2" <?php echo ($usuario['id_rol'] == 2) ? 'selected' : ''; ?>>Curador</option>
                    <option value="3" <?php echo ($usuario['id_rol'] == 3) ? 'selected' : ''; ?>>Estudiante</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="?controlador=Index&accion=mostrar" class="btn btn-secondary">Volver</a>
                </div>
                
                <?php if($usuario['estado'] == 'activo'): ?>
                    <a href="?controlador=Usuario&accion=inhabilitar&id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea inhabilitar este usuario? Perderá el acceso al sistema.');">
                        Inhabilitar Usuario
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
function confirmarEdicion(){
    return confirm('¿Desea guardar los cambios realizados en este usuario?');
}
</script>

<?php include 'public/footer.php'; ?>