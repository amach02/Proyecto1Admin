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
                    <option value="1" <?php echo ($familia['id_orden'] == 1) ? 'selected' : ''; ?>>Hymenoptera</option>
                    <option value="2" <?php echo ($familia['id_orden'] == 2) ? 'selected' : ''; ?>>Lepidoptera</option>
                </select>
                <div class="form-text">La familia debe pertenecer obligatoriamente a un orden existente.</div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-success">Guardar Corrección</button>
                    <a href="?controlador=Index&accion=mostrar" class="btn btn-secondary">Volver al Catálogo</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'public/footer.php'; ?>