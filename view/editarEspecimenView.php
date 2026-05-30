<?php include 'public/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Modificar Datos de Espécimen Entomológico</h2>

        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>
        <?php if($status == 'error'): ?>
            <div class="alert alert-danger">Error técnico: No se pudieron salvar las modificaciones. Verifique duplicación de Vial o Código.</div>
        <?php elseif($status == 'invalido'): ?>
            <div class="alert alert-warning">El campo Código ID es requerido de forma obligatoria por el laboratorio.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Especimen&accion=editar" onsubmit="return validarFormularioEspecimen();">
            
            <input type="hidden" name="id_especimen" id="id_especimen" value="<?php echo $especimen['id_especimen']; ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Código ID (Etiqueta Física)</label>
                    <input type="text" name="codigo_id" id="codigo_id" class="form-control" value="<?php echo $especimen['codigo_id']; ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha de Recolección en Campo</label>
                    <input type="date" name="fecha_recoleccion" class="form-control" value="<?php echo $especimen['fecha_recoleccion']; ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Localidad de Recolección (Geografía)</label>
                <input type="text" name="localizacion_recoleccion" class="form-control" value="<?php echo $especimen['localizacion_recoleccion']; ?>">
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado de Identificación</label>
                    <select name="estado" class="form-control" required>
                        <option value="disponible" <?php echo ($especimen['estado'] == 'disponible') ? 'selected' : ''; ?>>Disponible</option>
                        <option value="pendiente_identificacion" <?php echo ($especimen['estado'] == 'pendiente_identificacion') ? 'selected' : ''; ?>>Pendiente de Identificación</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Clasificación Taxonómica (Especie)</label>
                    <select name="id_especie" class="form-control">
                        <option value="">-- Sin Clasificar / Datos Parciales --</option>
                        <option value="1" <?php echo ($especimen['id_especie'] == 1) ? 'selected' : ''; ?>>Apis mellifera</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Ubicación Asignada (Vial)</label>
                    <select name="id_vial" class="form-control">
                        <option value="">-- Sin Ubicación Física Asignada --</option>
                        <option value="1" <?php echo ($especimen['id_vial'] == 1) ? 'selected' : ''; ?>>VIAL-01 (Caja 01)</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="?controlador=Index&accion=mostrar" class="btn btn-secondary">Regresar</a>
            </div>
        </form>
    </div>
</div>

<script>
function validarFormularioEspecimen(){
    let codigo = document.getElementById('codigo_id').value;
    if(codigo.trim() === ''){
        alert('El código identificador de la etiqueta no puede guardarse vacío.');
        return false;
    }
    return confirm('¿Está seguro de que desea sobreescribir los datos de este ejemplar?');
}
</script>

<?php include 'public/footer.php'; ?>