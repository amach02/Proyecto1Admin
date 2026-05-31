<?php include 'public/header.php'; ?>
<?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

<div class="container mt-4" style="max-width:750px;">
    <div class="card shadow p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Registrar Espécimen</h2>
            <a href="?controlador=Especimen&accion=mostrarListar" class="btn btn-secondary btn-sm">← Volver</a>
        </div>

        <?php if ($status === 'invalido'): ?>
            <div class="alert alert-warning">El campo Código ID es obligatorio.</div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger">Error al registrar. El código puede estar duplicado o el vial ya está ocupado.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Especimen&accion=registrar" onsubmit="return validarFormRegistro();">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Código ID (etiqueta física) <span class="text-danger">*</span></label>
                    <input type="text" name="codigo_id" id="codigo_id" class="form-control"
                           placeholder="Ej: UCR-ENT-003" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha de Recolección</label>
                    <input type="date" name="fecha_recoleccion" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Localización de Recolección</label>
                <input type="text" name="localizacion_recoleccion" class="form-control"
                       placeholder="Ej: Turrialba, Sector Norte">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                    <select name="estado" class="form-select" required>
                        <option value="pendiente_identificacion" selected>Pendiente de identificación</option>
                        <option value="disponible">Disponible</option>
                        <option value="prestado">Prestado</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Especie Taxonómica</label>
                    <select name="id_especie" class="form-select">
                        <option value="">— Sin clasificar —</option>
                        <?php foreach ($especies as $esp): ?>
                        <option value="<?php echo $esp['id_especie']; ?>">
                            <?php echo htmlspecialchars($esp['especie']); ?>
                            (<?php echo htmlspecialchars($esp['genero']); ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Vial Asignado</label>
                    <select name="id_vial" class="form-select">
                        <option value="">— Sin ubicación —</option>
                        <?php foreach ($viales as $v): ?>
                        <option value="<?php echo $v['id_vial']; ?>">
                            <?php echo htmlspecialchars($v['ruta_completa']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Solo se muestran viales disponibles.</div>
                </div>
            </div>

            <div class="d-grid mt-2">
                <button type="submit" class="btn btn-success">Registrar Espécimen</button>
            </div>

        </form>
    </div>
</div>

<script>
function validarFormRegistro() {
    const codigo = document.getElementById('codigo_id').value.trim();
    if (!codigo) {
        alert('El Código ID es obligatorio.');
        return false;
    }
    return true;
}
</script>

<?php include 'public/footer.php'; ?>
