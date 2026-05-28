<?php
include_once 'public/header.php';
?>

<section class="form-section">

    <div class="form-container-card">

        <form id="productoForm" method="POST" action="?controlador=Inventario&accion=registrarProductos">

            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre del Producto</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Ej: Arroz"
                        required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="categoria">Categoría</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">-- Seleccione una categoría --</option>
                        <option value="Granos">Granos</option>
                        <option value="Lacteos">Lacteos</option>
                        <option value="Enlatados">Enlatados</option>
                        <option value="Basicos">Basicos</option>
                        <option value="Bebidas">Bebidas</option>
                        <option value="Limpieza">Limpieza</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ruta_imagen">URL de la Imagen</label>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input
                            type="url"
                            id="ruta_imagen"
                            name="ruta_imagen"
                            placeholder="https://ejemplo.com/imagen.png"
                            required
                            oninput="previsualizarImagen(this.value)"
                            style="flex: 1;">
                        <img
                            id="preview-imagen"
                            src=""
                            alt="Vista previa"
                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; display: none; border: 1px solid #ddd;">
                    </div>
                </div>
            </div>

            <hr class="form-divider">

            <div class="form-actions-right">
                <button type="submit" class="btn-save">
                    Registrar Producto
                </button>
            </div>

            <?php
            $status = isset($_GET['status']) ? $_GET['status'] : '';
            if ($status === 'success'): ?>
                <div class="alert alert-success">
                    Producto registrado correctamente.
                </div>
            <?php elseif ($status === 'error'): ?>
                <div class="alert alert-error">
                    No se pudo registrar el producto.
                </div>
            <?php elseif ($status === 'invalido'): ?>
                <div class="alert alert-error">
                    Datos inválidos.
                </div>
            <?php endif; ?>

        </form>

    </div>

</section>

<script>
    function previsualizarImagen(url) {
        const img = document.getElementById('preview-imagen');
        if (url.trim() !== '') {
            img.src = url;
            img.style.display = 'inline-block';
            // Si la URL no carga, ocultar la imagen
            img.onerror = function () {
                img.style.display = 'none';
            };
        } else {
            img.src = '';
            img.style.display = 'none';
        }
    }
</script>

<?php
include_once 'public/footer.php';
?>