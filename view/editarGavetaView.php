<?php 
/** @var array $gaveta */
/** @var array $gabinetes */
include 'public/header.php'; 
?>

<div class="container mt-5">
    <div class="card shadow p-4 mb-5">
        <h2 class="mb-4">Infraestructura: Editar Gaveta</h2>
        
        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

        <?php if($status == 'con_especimenes'): ?>
            <div class="alert alert-danger">Acción Bloqueada: Esta gaveta contiene especímenes montados en seco activos.</div>
        <?php elseif($status == 'error'): ?>
            <div class="alert alert-danger">Error al actualizar el registro en la base de datos. Verifique si el código ya existe.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Gaveta&accion=editar">
            <input type="hidden" name="id_gaveta" value="<?php echo htmlspecialchars($gaveta['id_gaveta']); ?>">
            
            <div class="mb-3">
                <label class="form-label">Código de Gaveta</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($gaveta['codigo']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gabinete al que pertenece</label>
                <select name="id_gabinete" class="form-select" required>
                    <?php if (isset($gabinetes) && !empty($gabinetes)): ?>
                        <?php foreach ($gabinetes as $gab): ?>
                            <option value="<?php echo $gab['id_gabinete']; ?>" <?php echo ($gaveta['id_gabinete'] == $gab['id_gabinete']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($gab['codigo']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay gabinetes disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-primary">Actualizar Gaveta</button>
                    <a href="?controlador=Infraestructura&accion=mostrar&tab=gavetas" class="btn btn-secondary">Volver al Panel</a>
                </div>
                <?php if(isset($gaveta['estado']) && $gaveta['estado'] == 'activo'): ?>
                    <a href="?controlador=Gaveta&accion=inhabilitar&id=<?php echo htmlspecialchars($gaveta['id_gaveta']); ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('¿Deshabilitar esta gaveta?');">
                       Inhabilitar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php include 'public/footer.php'; ?>