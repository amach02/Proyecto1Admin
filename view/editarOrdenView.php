<?php include 'public/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Gestión Taxonómica: Editar Orden</h2>
        
        <form method="POST" action="?controlador=Orden&accion=editar" onsubmit="return confirm('¿Desea modificar el nombre de este Orden?');">
            
            <input type="hidden" name="id_orden" value="<?php echo $orden['id_orden']; ?>">

            <div class="mb-3">
                <label class="form-label">Nombre del Orden <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($orden['nombre']); ?>" required>
                <div class="form-text">El orden es el nivel taxonómico principal en este sistema (Ej: Hymenoptera, Lepidoptera).</div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button type="submit" class="btn btn-success">Guardar Corrección</button>
                    <a href="?controlador=Taxonomia&accion=mostrar&tab=ordenes" class="btn btn-secondary">Volver al Catálogo</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include 'public/footer.php'; ?>