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
                                <?php echo htmlspecialchars($esp['especie']); ?>
                                (<?php echo htmlspecialchars($esp['genero']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

           <div class="mb-4 p-4 bg-light border rounded shadow-sm">

    <div class="mb-3">
        <label class="form-label fw-bold text-primary">
            Ubicación del Espécimen <span class="text-danger">*</span>
        </label>

        <select
            name="tipo_contenedor"
            id="selectorContenedor"
            class="form-select form-select-lg border-primary"
            onchange="cambiarContenedor()"
            required
        >
            <option value="">— Seleccione el tipo de almacenamiento —</option>
            <option value="gabinete">Gabinete</option>
            <option value="caja">Caja</option>
        </select>
    </div>

    <!-- BLOQUE GABINETE / GAVETA -->
    <div id="bloqueGaveta" class="mb-2 d-none">

        <div class="mb-3">
            <label class="form-label fw-bold text-success">
                Seleccione el Gabinete <span class="text-danger">*</span>
            </label>

            <select
                name="id_gabinete"
                id="inputGabinete"
                class="form-select border-success"
                onchange="cargarGavetasPorGabinete()"
            >
                <option value="">— Elija un gabinete —</option>

                <?php if (!empty($gabinetes)): ?>
                    <?php foreach ($gabinetes as $gab): ?>
                        <option value="<?php echo htmlspecialchars($gab['id_gabinete']); ?>">
                            <?php echo htmlspecialchars($gab['codigo']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-success">
                Seleccione la Gaveta del Gabinete <span class="text-danger">*</span>
            </label>

            <select
                name="id_gaveta"
                id="inputGaveta"
                class="form-select border-success"
            >
                <option value="">— Primero seleccione un gabinete —</option>
            </select>

            <div class="form-text text-muted small">
                Formato: Gabinete &gt; Gaveta.
            </div>
        </div>

    </div>

    <!-- BLOQUE CAJA / VIAL -->
    <div id="bloqueVial" class="mb-2 d-none">

        <div class="mb-3">
            <label class="form-label fw-bold text-info text-dark">
                Seleccione la Caja <span class="text-danger">*</span>
            </label>

            <select
                name="id_caja"
                id="inputCaja"
                class="form-select border-info"
                onchange="cargarVialesPorCaja()"
            >
                <option value="">— Elija una caja —</option>

                <?php if (!empty($cajas)): ?>
                    <?php foreach ($cajas as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['id_caja']); ?>">
                            <?php echo htmlspecialchars($c['codigo']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold text-info text-dark">
                Seleccione el Vial de la Caja <span class="text-danger">*</span>
            </label>

            <select
                name="id_vial"
                id="inputVial"
                class="form-select border-info"
            >
                <option value="">— Primero seleccione una caja —</option>
            </select>

            <div class="form-text text-muted small">
                Solo se muestran viales disponibles. Formato: Caja &gt; Vial.
            </div>
        </div>

    </div>

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
    var tipo = document.getElementById('selectorContenedor').value;

    var bloqueGaveta = document.getElementById('bloqueGaveta');
    var bloqueVial = document.getElementById('bloqueVial');

    var inputGabinete = document.getElementById('inputGabinete');
    var inputGaveta = document.getElementById('inputGaveta');
    var inputCaja = document.getElementById('inputCaja');
    var inputVial = document.getElementById('inputVial');

    if (tipo === 'gabinete') {
        bloqueGaveta.classList.remove('d-none');
        bloqueVial.classList.add('d-none');

        inputGabinete.required = true;
        inputGaveta.required = true;

        inputCaja.required = false;
        inputVial.required = false;

        inputCaja.value = '';
        inputVial.innerHTML = '<option value="">— Primero seleccione una caja —</option>';

    } else if (tipo === 'caja') {
        bloqueGaveta.classList.add('d-none');
        bloqueVial.classList.remove('d-none');

        inputCaja.required = true;
        inputVial.required = true;

        inputGabinete.required = false;
        inputGaveta.required = false;

        inputGabinete.value = '';
        inputGaveta.innerHTML = '<option value="">— Primero seleccione un gabinete —</option>';

    } else {
        bloqueGaveta.classList.add('d-none');
        bloqueVial.classList.add('d-none');

        inputGabinete.required = false;
        inputGaveta.required = false;
        inputCaja.required = false;
        inputVial.required = false;
    }
}

function cargarGavetasPorGabinete() {
    var idGabinete = document.getElementById('inputGabinete').value;
    var comboGaveta = document.getElementById('inputGaveta');

    if (idGabinete === '') {
        comboGaveta.innerHTML = '<option value="">— Primero seleccione un gabinete —</option>';
        return;
    }

    comboGaveta.innerHTML = '<option value="">Cargando gavetas...</option>';

    fetch('?controlador=Especimen&accion=cargarGavetas&id_gabinete=' + idGabinete)
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (datos) {
            comboGaveta.innerHTML = '';

            if (datos.length > 0) {
                comboGaveta.innerHTML = '<option value="">— Seleccione una gaveta —</option>';

                datos.forEach(function (gaveta) {
                    comboGaveta.innerHTML +=
                        '<option value="' + gaveta.id_gaveta + '">' +
                        gaveta.ruta_completa +
                        '</option>';
                });

            } else {
                comboGaveta.innerHTML =
                    '<option value="">No hay gavetas disponibles en este gabinete</option>';
            }
        })
        .catch(function () {
            comboGaveta.innerHTML =
                '<option value="">No hay gavetas disponibles en este gabinete</option>';
        });
}

function cargarVialesPorCaja() {
    var idCaja = document.getElementById('inputCaja').value;
    var comboVial = document.getElementById('inputVial');

    if (idCaja === '') {
        comboVial.innerHTML = '<option value="">— Primero seleccione una caja —</option>';
        return;
    }

    comboVial.innerHTML = '<option value="">Cargando viales...</option>';

    fetch('?controlador=Especimen&accion=cargarViales&id_caja=' + idCaja)
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (datos) {
            comboVial.innerHTML = '';

            if (datos.length > 0) {
                comboVial.innerHTML = '<option value="">— Seleccione un vial disponible —</option>';

                datos.forEach(function (vial) {
                    comboVial.innerHTML +=
                        '<option value="' + vial.id_vial + '">' +
                        vial.ruta_completa +
                        '</option>';
                });

            } else {
                comboVial.innerHTML =
                    '<option value="">No hay viales disponibles en esta caja</option>';
            }
        })
        .catch(function () {
            comboVial.innerHTML =
                '<option value="">No hay viales disponibles en esta caja</option>';
        });
}
</script>