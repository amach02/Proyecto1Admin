<?php include 'public/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Taxonomía: Editar Especie</h2>
        <form method="POST" action="?controlador=Especie&accion=editar">
            <input type="hidden" name="id_especie" value="<?php echo $especie['id_especie']; ?>">
            <div class="mb-3">
                <label class="form-label">Nombre de la Especie (Epíteto específico)</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $especie['nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Género Jerárquico (Padre)</label>
                <select name="id_genero" class="form-control" required>
                    <?php foreach ($generos as $gen): ?>
                        <option value="<?php echo $gen['id_genero']; ?>" <?php echo ($especie['id_genero'] == $gen['id_genero']) ? 'selected' : ''; ?>>
                            <?php echo $gen['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Guardar Corrección</button>
            <a href="?controlador=Taxonomia&accion=mostrar&tab=especies" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
<?php include 'public/footer.php'; ?>