<?php include 'public/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Taxonomía: Editar Género</h2>
        <form method="POST" action="?controlador=Genero&accion=editar">
            <input type="hidden" name="id_genero" value="<?php echo $genero['id_genero']; ?>">
            
            <div class="mb-3">
                <label class="form-label">Nombre del Género</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $genero['nombre']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Familia Jerárquica (Padre)</label>
                <select name="id_familia" class="form-control" required>
                    <?php foreach ($familias as $fam): ?>
                        <option value="<?php echo $fam['id_familia']; ?>" <?php echo ($genero['id_familia'] == $fam['id_familia']) ? 'selected' : ''; ?>>
                            <?php echo $fam['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Guardar Corrección</button>
            <a href="?controlador=Taxonomia&accion=mostrar&tab=generos" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
<?php include 'public/footer.php'; ?>