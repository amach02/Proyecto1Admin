<?php 
/** @var array $plantas */
include 'public/header.php'; 
?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Plantas Hospedadoras</h2>
            <a href="?controlador=Planta&accion=mostrarRegistrar" class="btn btn-warning text-dark fw-bold">+ Nueva Planta</a>
        </div>
        <?php if (!empty($plantas)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre Científico</th>
                            <th>Nombre Común</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plantas as $p): ?>
                            <tr>
                                <td class="text-muted"><?php echo htmlspecialchars($p['id_planta']); ?></td>
                                <td><strong><?php echo htmlspecialchars($p['nombre_cientifico']); ?></strong></td>
                                <td><?php echo htmlspecialchars($p['nombre_comun'] ?? '-'); ?></td>
                                <td class="text-end">
                                    <a href="?controlador=Planta&accion=mostrarEditar&id=<?php echo $p['id_planta']; ?>" class="btn btn-warning btn-sm text-dark fw-bold">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-secondary text-center py-4">No hay plantas hospedadoras registradas.</div>
        <?php endif; ?>
        <div class="mt-3">
            <a href="?controlador=Infraestructura&accion=mostrar&tab=plantas" class="btn btn-secondary">Volver al Panel</a>
        </div>
    </div>
</div>
<?php include 'public/footer.php'; ?>