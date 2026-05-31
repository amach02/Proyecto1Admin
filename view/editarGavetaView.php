<?php include 'public/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Infraestructura: Editar Gaveta</h2>
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'con_especimenes'): ?>
            <div class="alert alert-danger">Acción Bloqueada: Esta gaveta contiene cajas con especímenes activos.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Gaveta&accion=editar">
            <input type="hidden" name="id_gaveta" value="<?php echo $gaveta['id_gaveta']; ?>">
            
            <div class="mb-3">
                <label class="form-label">Código de Gaveta</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo $gaveta['codigo']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Gabinete al que pertenece</label>
                <select name="id_gabinete" class="form-control" required>
                    <?php foreach ($gabinetes as $gab): ?>
                        <option value="<?php echo $gab['id_gabinete']; ?>" <?php echo ($gaveta['id_gabinete'] == $gab['id_gabinete']) ? 'selected' : ''; ?>>
                            <?php echo $gab['codigo']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="?controlador=Infraestructura&accion=mostrar&tab=gavetas" class="btn btn-secondary">Volver</a>
                </div>
                <?php if($gaveta['estado'] == 'activo'): ?>
                    <a href="?controlador=Gaveta&accion=inhabilitar&id=<?php echo $gaveta['id_gaveta']; ?>" class="btn btn-danger" onclick="return confirm('¿Deshabilitar gaveta?');">Inhabilitar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?php include 'public/footer.php'; ?>