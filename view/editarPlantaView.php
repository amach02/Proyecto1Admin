<?php 
/** @var array $planta */
include 'public/header.php'; 
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Planta Hospedadora: Editar Registro</h2>
        <?php if ($status === 'con_especimenes'): ?>
            <div class="alert alert-danger"><strong>Acción bloqueada.</strong> Tiene especímenes vinculados.</div>
        <?php elseif ($status === 'invalido'): ?>
            <div class="alert alert-warning">El nombre científico es obligatorio.</div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger">Error al actualizar. El nombre científico podría estar duplicado.</div>
        <?php endif; ?>
        <form method="POST" action="?controlador=Planta&accion=editar" onsubmit="return confirm('¿Desea modificar esta planta hospedadora?');">
            <input type="hidden" name="id_planta" value="<?php echo htmlspecialchars($planta['id_planta']); ?>">
            <div class="mb-3">
                <label class="form-label fw-bold">Nombre Científico <span class="text-danger">*</span></label>
                <input type="text" name="nombre_cientifico" class="form-control form-control-lg" value="<?php echo htmlspecialchars($planta['nombre_cientifico']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nombre Común (Opcional)</label>
                <input type="text" name="nombre_comun" class="form-control" value="<?php echo htmlspecialchars(isset($planta['nombre_comun']) ? $planta['nombre_comun'] : '-'); ?>">
            </div>
            <div class="d-flex justify-content-between mt-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Guardar Cambios</button>
                    <a href="?controlador=Planta&accion=mostrarListar" class="btn btn-secondary">Volver al Panel</a>
                </div>
                <a href="?controlador=Planta&accion=inhabilitar&id=<?php echo htmlspecialchars($planta['id_planta']); ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que desea inhabilitar esta planta?');">Inhabilitar Planta</a>
            </div>
        </form>
    </div>
</div>
<?php include 'public/footer.php'; ?>