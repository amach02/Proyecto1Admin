<?php 
// Aseguramos que las variables existan para que no dé error en pantalla si vienen vacías
$gavetas  = isset($gavetas) ? $gavetas : array();
$viales   = isset($viales) ? $viales : array();
$especies = isset($especies) ? $especies : array();

include 'public/header.php'; 
?>
<?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

<div class="container mt-4" style="max-width:750px;">
    <div class="card shadow p-4 mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Registrar Espécimen</h2>
            <a href="?controlador=Especimen&accion=mostrarListar" class="btn btn-secondary btn-sm">← Volver</a>
        </div>

        <?php if ($status === 'registrado_success'): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <div>
                    El espécimen ha sido registrado con éxito en el inventario.
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($status === 'invalido'): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Atención:</strong> El campo Código ID y los datos de almacenamiento son obligatorios.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error al registrar:</strong> El código puede estar duplicado o el contenedor final ya se encuentra ocupado.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
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
                <div class="col-md-6 mb-3">
                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                    <select name="estado" class="form-select" required>
                        <option value="pendiente_identificacion" selected>Pendiente de identificación</option>
                        <option value="disponible">Disponible</option>
                        <option value="prestado">Prestado</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Especie Taxonómica</label>
                    <select name="id_especie" class="form-select">
                        <option value="">— Sin clasificar —</option>
                        <?php foreach ($especies as $esp): ?>
                            <option value="<?php echo $esp['id_especie']; ?>">
                                <?php echo htmlspecialchars($esp['orden']); ?> &gt;
                                <?php echo htmlspecialchars($esp['familia']); ?> &gt;
                                <?php echo htmlspecialchars($esp['genero']); ?> &gt;
                                <?php echo htmlspecialchars($esp['especie']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-4 p-4 bg-light border rounded shadow-sm">
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Ubicación del Espécimen <span class="text-danger">*</span></label>
                    <select name="tipo_contenedor" id="selectorContenedor" class="form-select form-select-lg border-primary" onchange="cambiarContenedor()" required>
                        <option value="">— Seleccione el contenedor principal —</option>
                        <option value="gabinete">Gabinete</option>
                        <option value="caja">Caja</option>
                    </select>
                </div>

                <div id="bloqueGaveta" class="mb-2 d-none">
                    <label class="form-label fw-bold text-success">Seleccione la Gaveta Destino <span class="text-danger">*</span></label>
                    <select name="id_gaveta" id="inputGaveta" class="form-select border-success">
                        <option value="">— Elija una gaveta —</option>
                        <?php if (!empty($gavetas)): ?>
                            <?php foreach ($gavetas as $gav): ?>
                                <option value="<?php echo htmlspecialchars($gav['id_gaveta']); ?>">
                                    <?php echo htmlspecialchars($gav['codigo']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div id="bloqueVial" class="mb-2 d-none">
                    <label class="form-label fw-bold text-info text-dark">Seleccione el Vial Destino <span class="text-danger">*</span></label>
                    <select name="id_vial" id="inputVial" class="form-select border-info">
                        <option value="">— Elija un vial —</option>
                        <?php if (!empty($viales)): ?>
                            <?php foreach ($viales as $v): 
                                $codVial = isset($v['vial']) ? $v['vial'] : (isset($v['codigo_vial']) ? $v['codigo_vial'] : (isset($v['codigo']) ? $v['codigo'] : ''));
                            ?>
                                <option value="<?php echo htmlspecialchars($v['id_vial']); ?>">
                                    <?php echo htmlspecialchars($codVial); ?> 
                                    <?php echo isset($v['caja']) ? '(Caja: ' . htmlspecialchars($v['caja']) . ')' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="form-text text-muted small">Solo se muestran viales disponibles.</div>
                </div>

            </div>

            <div class="d-grid mt-2">
                <button type="submit" class="btn btn-success btn-lg fw-bold">Guardar Espécimen</button>
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

function cambiarContenedor() {
    var contenedorSel = document.getElementById('selectorContenedor').value;
    var bloqueGaveta  = document.getElementById('bloqueGaveta');
    var bloqueVial    = document.getElementById('bloqueVial');
    var inputGaveta   = document.getElementById('inputGaveta');
    var inputVial     = document.getElementById('inputVial');

    if (contenedorSel === 'gabinete') {
        bloqueGaveta.classList.remove('d-none');
        inputGaveta.setAttribute('required', 'required');
        
        bloqueVial.classList.add('d-none');
        inputVial.removeAttribute('required');
        inputVial.value = ""; 
        
    } else if (contenedorSel === 'caja') {
        bloqueVial.classList.remove('d-none');
        inputVial.setAttribute('required', 'required');
        
        bloqueGaveta.classList.add('d-none');
        inputGaveta.removeAttribute('required');
        inputGaveta.value = ""; 
        
    } else {
        bloqueGaveta.classList.add('d-none');
        inputGaveta.removeAttribute('required');
        bloqueVial.classList.add('d-none');
        inputVial.removeAttribute('required');
    }
}
</script>

<?php include 'public/footer.php'; ?>