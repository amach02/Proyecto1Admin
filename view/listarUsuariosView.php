<?php include 'public/header.php'; ?>
<?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Usuarios</h2>
        <a href="?controlador=Usuario&accion=mostrarRegistrar
        " class="btn btn-success">+ Registrar Usuario</a>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?php echo $u['id_usuario']; ?></td>
                        <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($u['correo']); ?></td>
                        <td><?php echo htmlspecialchars($u['rol']); ?></td>
                        <td>
                            <?php if ($u['estado'] === 'activo'): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inhabilitado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?controlador=Usuario&accion=mostrarEditar&id=<?php echo $u['id_usuario']; ?>"
                               class="btn btn-warning btn-sm">Editar</a>
                            <?php if ($u['estado'] === 'activo'): ?>
                            <a href="?controlador=Usuario&accion=inhabilitar&id=<?php echo $u['id_usuario']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Inhabilitar a <?php echo htmlspecialchars($u['nombre']); ?>? Perderá acceso al sistema.');">
                               Inhabilitar
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($usuarios)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">No hay usuarios registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include 'public/footer.php'; ?>
<script>
<?php if ($status === 'registrado_success'): ?>
document.addEventListener('DOMContentLoaded', () => mostrarToast('Usuario registrado exitosamente.', 'success'));
<?php elseif ($status === 'inhabilitado_success'): ?>
document.addEventListener('DOMContentLoaded', () => mostrarToast('Usuario inhabilitado correctamente.', 'success'));
<?php elseif ($status === 'editado_success'): ?>
document.addEventListener('DOMContentLoaded', () => mostrarToast('Usuario actualizado exitosamente.', 'success'));
<?php elseif ($status === 'error'): ?>
document.addEventListener('DOMContentLoaded', () => mostrarToast('Ocurrió un error. Intente nuevamente.', 'danger'));
<?php endif; ?>
</script>
