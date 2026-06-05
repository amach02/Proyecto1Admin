<?php 
/** @var array $caja */
/** @var array $viales */
include 'public/header.php'; 
?>

<div class="container mt-5">
    <div class="card shadow p-4 mb-4">
        <h2 class="mb-4">Infraestructura: Editar Caja</h2>
        
        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

        <?php if($status == 'con_especimenes'): ?>
            <div class="alert alert-danger">Acción Bloqueada: Esta caja contiene viales con especímenes activos.</div>
        <?php elseif($status == 'invalido'): ?>
            <div class="alert alert-warning">El código de la caja es obligatorio.</div>
        <?php elseif($status == 'error'): ?>
            <div class="alert alert-danger">Error al actualizar el registro en la base de datos.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Caja&accion=editar">
            <input type="hidden" name="id_caja" value="<?php echo htmlspecialchars($caja['id_caja']); ?>">
            
            <div class="mb-3">
                <label class="form-label">Código de Caja</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($caja['codigo']); ?>" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-primary">Actualizar Caja</button>
                    <a href="?controlador=Infraestructura&accion=mostrar&tab=cajas" class="btn btn-secondary">Volver al Panel</a>
                </div>
                <?php if(isset($caja['estado']) && $caja['estado'] == 'activo'): ?>
                    <a href="?controlador=Caja&accion=inhabilitar&id=<?php echo htmlspecialchars($caja['id_caja']); ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('¿Deshabilitar caja? Se validará que no tenga viales.');">
                       Inhabilitar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card shadow p-4">
        <h4 class="mb-3">Viales dentro de esta Caja</h4>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-header bg-info text-white text-dark">Añadir Vial</div>
                    <div class="card-body">
                        <form method="POST" action="?controlador=Infraestructura&accion=registrarVial">
                            <input type="hidden" name="id_caja" value="<?php echo htmlspecialchars($caja['id_caja']); ?>">
                            <div class="mb-3">
                                <label class="form-label">Código del Vial <span class="text-danger">*</span></label>
                                <input type="text" name="codigo" class="form-control" placeholder="Ej: VIAL-01" required>
                            </div>
                            <button type="submit" class="btn btn-info text-dark w-100">Guardar Vial</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Código Vial</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($viales) && !empty($viales)): ?>
                                <?php foreach ($viales as $v): 
                                    $codVial = isset($v['vial']) ? $v['vial'] : (isset($v['codigo_vial']) ? $v['codigo_vial'] : '');
                                ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($codVial); ?></strong></td>
                                        <td class="text-end">
                                            <a href="?controlador=Vial&accion=mostrarEditar&id=<?php echo htmlspecialchars($v['id_vial']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Esta caja no tiene viales registrados.</td>
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