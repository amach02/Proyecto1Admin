<?php 
/** @var array $gabinete */
/** @var array $gavetas */
include 'public/header.php'; 
?>

<div class="container mt-5">
    <div class="card shadow p-4 mb-4">
        <h2 class="mb-4">Mantenimiento de Infraestructura: Editar Gabinete</h2>

        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

        <?php if ($status == 'error'): ?>
            <div class="alert alert-danger">Error al actualizar el registro en la base de datos.</div>
        <?php elseif ($status == 'invalido'): ?>
            <div class="alert alert-warning">El código del gabinete es obligatorio.</div>
        <?php elseif ($status == 'con_especimenes'): ?>
            <div class="alert alert-danger d-flex align-items-center">
                <strong>¡Acción Bloqueada!</strong> &nbsp; No se puede inhabilitar este gabinete porque contiene gavetas o especímenes activos asociados.
            </div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Gabinete&accion=editar" onsubmit="return confirm('¿Desea modificar este contenedor?');">

            <input type="hidden" name="id_gabinete" value="<?php echo htmlspecialchars($gabinete['id_gabinete']); ?>">

            <div class="mb-3">
                <label class="form-label">Código de Gabinete</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($gabinete['codigo']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción / Notas de Ubicación</label>
                <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars(isset($gabinete['descripcion']) ? $gabinete['descripcion'] : ''); ?></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-primary">Actualizar Gabinete</button>
                    <a href="?controlador=Infraestructura&accion=mostrar&tab=gabinetes" class="btn btn-secondary">Volver</a>
                </div>

                <?php if (isset($gabinete['estado']) && $gabinete['estado'] == 'activo'): ?>
                    <a href="?controlador=Gabinete&accion=inhabilitar&id=<?php echo htmlspecialchars($gabinete['id_gabinete']); ?>"
                        class="btn btn-danger"
                        onclick="return confirm('¿Seguro que desea deshabilitar este gabinete? Se validará que esté completamente vacío.');">
                        Inhabilitar Estructura
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card shadow p-4">
        <h4 class="mb-3">Gavetas dentro de este Gabinete</h4>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">Añadir Gaveta</div>
                    <div class="card-body">
                        <form method="POST" action="?controlador=Infraestructura&accion=registrarGaveta">
                            <input type="hidden" name="id_gabinete" value="<?php echo htmlspecialchars($gabinete['id_gabinete']); ?>">
                            <div class="mb-3">
                                <label class="form-label">Código de la Gaveta <span class="text-danger">*</span></label>
                                <input type="text" name="codigo" class="form-control" placeholder="Ej: GAV-01" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Guardar Gaveta</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Código Gaveta</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($gavetas) && !empty($gavetas)): ?>
                                <?php foreach ($gavetas as $gav): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($gav['codigo']); ?></strong></td>
                                        <td class="text-end">
                                            <a href="?controlador=Gaveta&accion=mostrarEditar&id=<?php echo htmlspecialchars($gav['id_gaveta']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Este gabinete no tiene gavetas registradas.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'public/footer.php'; ?>