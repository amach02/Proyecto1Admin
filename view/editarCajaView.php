<?php include 'public/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Infraestructura: Editar Caja</h2>
        <?php if(isset($_GET['status']) && $_GET['status'] == 'con_especimenes'): ?>
            <div class="alert alert-danger">Acción Bloqueada: Esta caja contiene viales con especímenes activos.</div>
        <?php endif; ?>
        <form method="POST" action="?controlador=Caja&accion=editar">
            <input type="hidden" name="id_caja" value="<?php echo $caja['id_caja']; ?>">
            <div class="mb-3">
                <label class="form-label">Código de Caja</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo $caja['codigo']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gaveta a la que pertenece</label>
                <select name="id_gaveta" class="form-control" required>
                    <?php foreach ($gavetas as $gav): ?>
                        <option value="<?php echo $gav['id_gaveta']; ?>" <?php echo ($caja['id_gaveta'] == $gav['id_gaveta']) ? 'selected' : ''; ?>>
                            <?php echo $gav['codigo']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="?controlador=Infraestructura&accion=mostrar&tab=cajas" class="btn btn-secondary">Volver</a>
                </div>
                <?php if($caja['estado'] == 'activo'): ?>
                    <a href="?controlador=Caja&accion=inhabilitar&id=<?php echo $caja['id_caja']; ?>" class="btn btn-danger" onclick="return confirm('¿Deshabilitar caja?');">Inhabilitar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?php include 'public/footer.php'; ?>