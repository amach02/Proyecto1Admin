<?php 
$gavetas  = isset($gavetas)  ? $gavetas  : array();
$viales   = isset($viales)   ? $viales   : array();
$especies = isset($especies) ? $especies : array();
$status   = isset($_GET['status']) ? $_GET['status'] : '';

include 'public/header.php'; 
?>

<div class="container mt-4" style="max-width:750px;">
    <div class="card shadow p-4 mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Registrar Espécimen</h2>
            <a href="?controlador=Especimen&accion=mostrarListar" class="btn btn-secondary btn-sm">← Volver</a>
        </div>

        <?php if ($status === 'registrado_success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                El espécimen ha sido registrado con éxito en el inventario.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($status === 'invalido'): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Atención:</strong> El campo Código ID y los datos de almacenamiento son obligatorios.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error al registrar:</strong> El código puede estar duplicado o el contenedor final ya se encuentra ocupado.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Especimen&accion=registrar" onsubmit="return validarFormRegistro();">

            <!-- Código e ID -->
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

            <!-- Localización -->
            <div class="mb-3">
                <label class="form-label">Localización de Recolección</label>
                <input type="text" name="localizacion_recoleccion" class="form-control"
                       placeholder="Ej: Turrialba, Sector Norte">
            </div>

            <!-- Estado y Especie -->
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

            <!-- Ubicación física -->
            <div class="mb-4 p-4 bg-light border rounded shadow-sm">
                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">
                        Ubicación del Espécimen <span class="text-danger">*</span>
                    </label>
                    <select name="tipo_contenedor" id="selectorContenedor"
                            class="form-select form-select-lg border-primary"
                            onchange="cambiarContenedor()" required>
                        <option value="">— Seleccione el contenedor principal —</option>
                        <option value="gabinete">Gabinete</option>
                        <option value="caja">Caja</option>
                    </select>
                </div>

                <div id="bloqueGaveta" class="mb-2 d-none">
                    <label class="form-label fw-bold text-success">
                        Seleccione la Gaveta Destino <span class="text-danger">*</span>
                    </label>
                    <select name="id_gaveta" id="inputGaveta" class="form-select border-success">
                        <option value="">— Elija una gaveta —</option>
                        <?php foreach ($gavetas as $gav): ?>
                            <option value="<?php echo htmlspecialchars($gav['id_gaveta']); ?>">
                                <?php echo htmlspecialchars($gav['codigo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="bloqueVial" class="mb-2 d-none">
                    <label class="form-label fw-bold text-dark">
                        Seleccione el Vial Destino <span class="text-danger">*</span>
                    </label>
                    <select name="id_vial" id="inputVial" class="form-select border-info">
                        <option value="">— Elija un vial —</option>
                        <?php foreach ($viales as $v):
                            $codVial = isset($v['vial'])       ? $v['vial']
                                     : (isset($v['codigo_vial']) ? $v['codigo_vial']
                                     : (isset($v['codigo'])      ? $v['codigo'] : ''));
                        ?>
                            <option value="<?php echo htmlspecialchars($v['id_vial']); ?>">
                                <?php echo htmlspecialchars($codVial); ?>
                                <?php echo isset($v['caja']) ? ' (Caja: ' . htmlspecialchars($v['caja']) . ')' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text text-muted small">Solo se muestran viales disponibles.</div>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════
                 SECCIÓN DE FOTOGRAFÍAS
            ════════════════════════════════════════════ -->
            <div class="mb-4 p-4 bg-light border rounded shadow-sm">
                <label class="form-label fw-bold text-primary">Fotografías del Espécimen</label>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <button type="button" class="btn btn-outline-success btn-sm" id="btnSubirFotoRegistro">
                        + Agregar Foto
                    </button>
                    <span class="text-muted small" id="contadorFotos">Sin fotos agregadas</span>
                </div>

                <!-- inputs ocultos con las URLs de Cloudinary -->
                <div id="contenedorUrlsFotos"></div>

                <!-- miniaturas de preview -->
                <div id="previstaFotos" class="d-flex flex-wrap gap-2 mt-2"></div>

                <div class="form-text text-muted small mt-1">
                    Formatos permitidos: PNG, JPG. Las fotos se guardarán junto con el espécimen.
                </div>
            </div>

            <div class="d-grid mt-2">
                <button type="submit" class="btn btn-success btn-lg fw-bold">Guardar Espécimen</button>
            </div>

        </form>
    </div>
</div>

<script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>

<script>
// ── Validación del formulario ─────────────────────────────────
function validarFormRegistro() {
    var codigo = document.getElementById('codigo_id').value.trim();
    if (!codigo) {
        alert('El Código ID es obligatorio.');
        return false;
    }
    return true;
}

// ── Lógica del selector de contenedor ────────────────────────
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
        inputVial.value = '';
    } else if (contenedorSel === 'caja') {
        bloqueVial.classList.remove('d-none');
        inputVial.setAttribute('required', 'required');
        bloqueGaveta.classList.add('d-none');
        inputGaveta.removeAttribute('required');
        inputGaveta.value = '';
    } else {
        bloqueGaveta.classList.add('d-none');
        inputGaveta.removeAttribute('required');
        bloqueVial.classList.add('d-none');
        inputVial.removeAttribute('required');
    }
}

// ── Widget Cloudinary para el formulario de registro ─────────
var urlsFotos = [];

var widgetRegistro = cloudinary.createUploadWidget(
    {
        cloudName           : 'drnsa0qtf',
        uploadPreset        : 'especimenes_ucr',
        sources             : ['local', 'url'],
        multiple            : true,
        clientAllowedFormats: ['png', 'jpg', 'jpeg'],
        maxFileSize         : 5000000,
        styles: {
            palette: {
                window          : '#FFFFFF',
                windowBorder    : '#90A0B3',
                tabIcon         : '#198754',
                menuIcons       : '#5A616A',
                textDark        : '#000000',
                textLight       : '#FFFFFF',
                link            : '#198754',
                action          : '#198754',
                inactiveTabIcon : '#0E2F5A',
                error           : '#dc3545',
                inProgress      : '#198754',
                complete        : '#20B832',
                sourceBg        : '#E4EBF1'
            }
        }
    },
    function (error, result) {
        if (error) {
            alert('Error: El archivo no es válido. Solo se permiten PNG o JPG.');
            return;
        }

        if (result.event === 'success') {
            var url    = result.info.secure_url;
            var indice = urlsFotos.length;
            urlsFotos.push(url);

            // Input oculto para que llegue en $_POST['fotos']
            var input   = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'fotos[]';
            input.value = url;
            input.id    = 'fotoInput_' + indice;
            document.getElementById('contenedorUrlsFotos').appendChild(input);

            // Miniatura con botón de quitar
            var div = document.createElement('div');
            div.style.cssText = 'position:relative; display:inline-block;';
            div.id = 'preview_' + indice;
            div.innerHTML =
                '<img src="' + url + '" style="height:80px; width:80px; object-fit:cover; ' +
                'border-radius:6px; border:2px solid #198754;">' +
                '<button type="button" onclick="quitarFoto(' + indice + ')" ' +
                'style="position:absolute; top:-6px; right:-6px; background:#dc3545; color:#fff; ' +
                'border:none; border-radius:50%; width:20px; height:20px; font-size:12px; ' +
                'cursor:pointer; line-height:1; padding:0;">✕</button>';
            document.getElementById('previstaFotos').appendChild(div);

            actualizarContador();
        }
    }
);

document.getElementById('btnSubirFotoRegistro').addEventListener('click', function() {
    widgetRegistro.open();
});

function quitarFoto(indice) {
    // Quitar miniatura
    var preview = document.getElementById('preview_' + indice);
    if (preview) { preview.parentNode.removeChild(preview); }

    // Quitar input oculto
    var input = document.getElementById('fotoInput_' + indice);
    if (input) { input.parentNode.removeChild(input); }

    // Marcar como eliminada
    urlsFotos[indice] = null;
    actualizarContador();
}

function actualizarContador() {
    var activas  = urlsFotos.filter(function(u) { return u !== null; }).length;
    var contador = document.getElementById('contadorFotos');
    contador.textContent = activas > 0
        ? activas + ' foto(s) lista(s) para guardar'
        : 'Sin fotos agregadas';
}
</script>

<?php include 'public/footer.php'; ?>