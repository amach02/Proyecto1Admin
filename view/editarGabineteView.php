<?php include 'public/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Mantenimiento de Infraestructura: Editar Gabinete</h2>

        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

        <?php if ($status == 'error'): ?>
            <div class="alert alert-danger">Error al actualizar el registro en la base de datos.</div>
        <?php elseif ($status == 'invalido'): ?>
            <div class="alert alert-warning">El código del gabinete es obligatorio.</div>
        <?php elseif ($status == 'con_especimenes'): ?>
            <div class="alert alert-danger d-flex align-items-center">
                <strong>¡Acción Bloqueada!</strong> &nbsp; No se puede inhabilitar este gabinete porque contiene viales con especímenes entomológicos activos asociados.
            </div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Gabinete&accion=editar" onsubmit="return confirm('¿Desea modificar este contenedor?');">

            <input type="hidden" name="id_gabinete" value="<?php echo $gabinete['id_gabinete']; ?>">

            <div class="mb-3">
                <label class="form-label">Código de Gabinete</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo $gabinete['codigo']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción / Notas de Ubicación</label>
                <textarea name="descripcion" class="form-control" rows="3"><?php echo $gabinete['descripcion']; ?></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-primary">Actualizar Gabinete</button>
                    <a href="?controlador=Index&accion=mostrar" class="btn btn-secondary">Volver</a>
                </div>

                <?php if ($gabinete['estado'] == 'activo'): ?>
                    <a href="?controlador=Gabinete&accion=inhabilitar&id=<?php echo $gabinete['id_gabinete']; ?>"
                        class="btn btn-danger"
                        onclick="return confirm('¿Seguro que desea deshabilitar este gabinete? Se validará que esté completamente vacío.');">
                        Inhabilitar Estructura
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php include 'public/footer.php'; ?>