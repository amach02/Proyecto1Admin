<?php include 'public/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Gestión Taxonómica: Editar Familia</h2>

        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
        
        <?php if($status == 'error'): ?>
            <div class="alert alert-danger">Error al actualizar la base de datos taxonómica.</div>
        <?php elseif($status == 'invalido'): ?>
            <div class="alert alert-warning">Todos los campos son estrictamente obligatorios.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Familia&accion=editar" onsubmit="return confirm('¿Desea modificar esta clasificación científica?');">
            
            <input type="hidden" name="id_familia" value="<?php echo $familia['id_familia']; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre de la Familia</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $familia['nombre']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Orden Jerárquico al que pertenece (Padre)</label>
                <select name="id_orden" class="form-control" required>
                    <?php foreach ($ordenes as $o): ?>
                    <option value="<?php echo $o['id_orden']; ?>"
                        <?php echo ($familia['id_orden'] == $o['id_orden']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($o['nombre']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">La familia debe pertenecer obligatoriamente a un orden existente.</div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-success">Guardar Corrección</button>
                    <a href="?controlador=Taxonomia&accion=mostrar&tab=familias" class="btn btn-secondary">Volver al Catálogo</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'public/footer.php'; ?>