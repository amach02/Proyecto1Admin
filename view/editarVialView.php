<?php include 'public/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Infraestructura: Editar Vial</h2>
        <?php if(isset($_GET['status']) && $_GET['status'] == 'ocupado'): ?>
            <div class="alert alert-danger">Acción Bloqueada: Este vial contiene actualmente un espécimen físico.</div>
        <?php endif; ?>
        <form method="POST" action="?controlador=Vial&accion=editar">
            <input type="hidden" name="id_vial" value="<?php echo $vial['id_vial']; ?>">
            <div class="mb-3">
                <label class="form-label">Código de Vial</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo $vial['codigo']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Caja a la que pertenece</label>
                <select name="id_caja" class="form-control" required>
                    <?php foreach ($cajas as $caj): ?>
                        <option value="<?php echo $caj['id_caja']; ?>" <?php echo ($vial['id_caja'] == $caj['id_caja']) ? 'selected' : ''; ?>>
                            <?php echo $caj['codigo']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="?controlador=Infraestructura&accion=mostrar&tab=viales" class="btn btn-secondary">Volver</a>
                </div>
                <?php if($vial['estado'] == 'activo'): ?>
                    <a href="?controlador=Vial&accion=inhabilitar&id=<?php echo $vial['id_vial']; ?>" class="btn btn-danger" onclick="return confirm('¿Deshabilitar vial?');">Inhabilitar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?php include 'public/footer.php'; ?>