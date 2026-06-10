<?php

/**
 * detalleEspecimenView.php  — Compatible con PHP 5.6
 * Guardar y eliminar usan form POST + redirect (sin fetch/JSON)
 */
$rol_actual = isset($_SESSION['nombre_rol']) ? $_SESSION['nombre_rol'] : '';
$puede_gestionar_fotos = ($rol_actual === 'Administrador' || $rol_actual === 'Curador');

$especimen = isset($especimen) ? $especimen : array();
$fotos     = isset($fotos)     ? $fotos     : array();

$codigo_id         = isset($especimen['codigo_id'])                ? $especimen['codigo_id']                : '';
$estado            = isset($especimen['estado'])                   ? $especimen['estado']                   : '';
$localizacion      = isset($especimen['localizacion_recoleccion']) ? $especimen['localizacion_recoleccion'] : '—';
$fecha_recoleccion = isset($especimen['fecha_recoleccion'])        ? $especimen['fecha_recoleccion']        : '—';
$id_especimen_js   = isset($especimen['id_especimen'])             ? intval($especimen['id_especimen'])     : 0;

$badgeMap = array(
    'disponible'               => 'bg-success',
    'prestado'                 => 'bg-warning text-dark',
    'pendiente_identificacion' => 'bg-secondary'
);
$badgeClass  = isset($badgeMap[$estado]) ? $badgeMap[$estado] : 'bg-light text-dark';
$estadoLabel = str_replace('_', ' ', ucfirst($estado));

$status = isset($_GET['status']) ? $_GET['status'] : '';

include 'public/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="container mt-4" style="max-width: 900px;">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">
            Detalle del Espécimen
            <span class="text-muted">— <?php echo htmlspecialchars($codigo_id); ?></span>
        </h2>
        <a href="?controlador=Especimen&accion=mostrarListar" class="btn btn-secondary btn-sm">← Volver</a>
    </div>

    <!-- Alertas de status -->
    <?php if ($status === 'foto_guardada'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Fotografía guardada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($status === 'foto_eliminada'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Fotografía eliminada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($status === 'foto_formato_error'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error: Solo se permiten imágenes PNG o JPG. El archivo seleccionado no es válido.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($status === 'foto_error'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error al procesar la fotografía. Intente nuevamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Datos generales -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Código ID:</strong>
                    <?php echo htmlspecialchars($codigo_id); ?>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Estado:</strong>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $estadoLabel; ?></span>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Localización:</strong>
                    <?php echo htmlspecialchars($localizacion); ?>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Fecha de recolección:</strong>
                    <?php echo htmlspecialchars($fecha_recoleccion); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección fotografías -->
    <div class="card shadow mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Fotografías del Espécimen</h5>
            <?php if ($puede_gestionar_fotos): ?>
                <button type="button" class="btn btn-success btn-sm" id="btnSubirFoto">
                    + Agregar Foto
                </button>
            <?php endif; ?>
        </div>

        <div class="card-body">

            <?php if (!empty($fotos)): ?>
                <div class="swiper especimenSwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($fotos as $foto): ?>
                            <div class="swiper-slide d-flex justify-content-center align-items-center"
                                style="background:#f8f9fa; border-radius:8px; min-height:340px; position:relative;">

                                <img src="<?php echo htmlspecialchars($foto['ruta']); ?>"
                                    alt="Fotografia del especimen"
                                    style="max-height:300px; max-width:100%; object-fit:contain;
                                            border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,.15);">

                                <!-- Botón eliminar con form POST normal -->
                                <?php if ($puede_gestionar_fotos): ?>
                                    <form method="POST"
                                        action="?controlador=Fotografia&accion=eliminarFoto"
                                        style="position:absolute; top:8px; right:8px;"
                                        onsubmit="return confirm('¿Desea eliminar esta fotografía?');">
                                        <input type="hidden" name="id_fotografia" value="<?php echo intval($foto['id_fotografia']); ?>">
                                        <input type="hidden" name="id_especimen" value="<?php echo $id_especimen_js; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">🗑 Eliminar</button>
                                    </form>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>
                <p class="text-muted text-center mt-2 small">
                    <?php echo count($fotos); ?> foto(s) registrada(s). Use las flechas para navegar.
                </p>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <p>Este espécimen aún no tiene fotografías.</p>
                    <p class="small">Haga clic en <strong>"+ Agregar Foto"</strong> para subir la primera imagen.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>

<script>
    var CLOUD_NAME = 'drnsa0qtf';
    var UPLOAD_PRESET = 'especimenes_ucr';
    var ID_ESPECIMEN = <?php echo $id_especimen_js; ?>;

    <?php if (!empty($fotos)): ?>
        var swiper = new Swiper('.especimenSwiper', {
            loop: <?php echo count($fotos) > 1 ? 'true' : 'false'; ?>,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            keyboard: {
                enabled: true
            }
        });
    <?php endif; ?>

    var widget = cloudinary.createUploadWidget({
            cloudName: CLOUD_NAME,
            uploadPreset: UPLOAD_PRESET,
            sources: ['local', 'url'],
            multiple: false,
            clientAllowedFormats: ['png', 'jpg', 'jpeg'],
            maxFileSize: 5000000,
            styles: {
                palette: {
                    window: '#FFFFFF',
                    windowBorder: '#90A0B3',
                    tabIcon: '#198754',
                    menuIcons: '#5A616A',
                    textDark: '#000000',
                    textLight: '#FFFFFF',
                    link: '#198754',
                    action: '#198754',
                    inactiveTabIcon: '#0E2F5A',
                    error: '#dc3545',
                    inProgress: '#198754',
                    complete: '#20B832',
                    sourceBg: '#E4EBF1'
                }
            }
        },
        function(error, result) {
            if (error) {
                alert('Error: El archivo seleccionado no es válido. Solo se permiten imágenes PNG o JPG.');
                return;
            }

            if (result.event === 'success') {
                var info = result.info;
                var url = info.secure_url;
                var formato = info.format;

                // Crear form oculto y enviarlo — sin fetch, sin JSON
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '?controlador=Fotografia&accion=guardarFoto';

                var f1 = document.createElement('input');
                f1.type = 'hidden';
                f1.name = 'ruta';
                f1.value = url;

                var f2 = document.createElement('input');
                f2.type = 'hidden';
                f2.name = 'id_especimen';
                f2.value = ID_ESPECIMEN;

                var f3 = document.createElement('input');
                f3.type = 'hidden';
                f3.name = 'formato';
                f3.value = formato;

                form.appendChild(f1);
                form.appendChild(f2);
                form.appendChild(f3);
                document.body.appendChild(form);
                form.submit();
            }
        }
    );

    document.getElementById('btnSubirFoto').addEventListener('click', function() {
        widget.open();
    });
</script>

<style>
    .especimenSwiper {
        width: 100%;
        padding-bottom: 40px;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #198754;
    }

    .swiper-pagination-bullet-active {
        background: #198754;
    }
</style>

<?php include 'public/footer.php'; ?>