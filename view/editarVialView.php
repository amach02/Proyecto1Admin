<?php 
/** @var array $vial */
/** @var array $cajas */
include 'public/header.php'; 
?>

<div class="container mt-5">
    <div class="card shadow p-4 mb-5">
        <h2 class="mb-4">Infraestructura: Editar Vial</h2>
        
        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

        <?php if($status == 'ocupado'): ?>
            <div class="alert alert-danger">Acción Bloqueada: Este vial contiene actualmente un espécimen físico activo.</div>
        <?php elseif($status == 'error'): ?>
            <div class="alert alert-danger">Error al actualizar el registro. Verifique que el código no esté duplicado.</div>
        <?php elseif($status == 'invalido'): ?>
            <div class="alert alert-warning">Por favor, complete todos los campos obligatorios.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Vial&accion=editar" onsubmit="return confirm('¿Desea guardar los cambios en este vial?');">
            
            <input type="hidden" name="id_vial" value="<?php echo htmlspecialchars($vial['id_vial']); ?>">
            
            <div class="mb-3">
                <label class="form-label">Código de Vial <span class="text-danger">*</span></label>
                <input type="text" name="codigo" class="form-control" value="<?php echo htmlspecialchars($vial['codigo']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Caja a la que pertenece <span class="text-danger">*</span></label>
                <select name="id_caja" class="form-select" required>
                    <option value="">— Seleccione una caja —</option>
                    <?php if(isset($cajas) && !empty($cajas)): ?>
                        <?php foreach ($cajas as $caj): ?>
                            <option value="<?php echo $caj['id_caja']; ?>" <?php echo ($vial['id_caja'] == $caj['id_caja']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(isset($caj['codigo']) ? $caj['codigo'] : $caj['codigo_caja']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-primary">Actualizar Vial</button>
                    <a href="?controlador=Infraestructura&accion=mostrar&tab=viales" class="btn btn-secondary">Volver al Panel</a>
                </div>
                
                <?php if(isset($vial['estado']) && $vial['estado'] == 'activo'): ?>
                    <a href="?controlador=Vial&accion=inhabilitar&id=<?php echo htmlspecialchars($vial['id_vial']); ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('¿Seguro que desea deshabilitar este vial? Se validará que esté vacío.');">
                       Inhabilitar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php include 'public/footer.php'; ?>