<?php include 'public/header.php'; ?>

<?php
if (!isset($especimen)) {
    $especimen = array();
}

if (!isset($especies)) {
    $especies = array();
}

if (!isset($gavetas)) {
    $gavetas = array();
}

if (!isset($viales)) {
    $viales = array();
}

$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<div class="container mt-5">
    <div class="card shadow p-4">

        <h2 class="mb-4">Modificar Datos de Espécimen Entomológico</h2>

        <?php if ($status == 'error'): ?>
            <div class="alert alert-danger">
                Error técnico: No se pudieron salvar las modificaciones.
                Verifique duplicación de vial o código.
            </div>
        <?php elseif ($status == 'invalido'): ?>
            <div class="alert alert-warning">
                El campo Código ID es requerido de forma obligatoria por el laboratorio.
            </div>
        <?php elseif ($status == 'ok'): ?>
            <div class="alert alert-success">
                Los datos del espécimen fueron modificados correctamente.
            </div>
        <?php endif; ?>

        <div id="mensaje_validacion" class="alert alert-warning" style="display:none;"></div>

        <form method="POST" action="?controlador=Especimen&accion=editar" onsubmit="return validarFormularioEspecimen();">

            <input
                type="hidden"
                name="id_especimen"
                id="id_especimen"
                value="<?php echo isset($especimen['id_especimen']) ? htmlspecialchars($especimen['id_especimen']) : ''; ?>"
            >

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Código ID (Etiqueta Física)</label>

                    <input
                        type="text"
                        name="codigo_id"
                        id="codigo_id"
                        class="form-control"
                        value="<?php echo isset($especimen['codigo_id']) ? htmlspecialchars($especimen['codigo_id']) : ''; ?>"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fecha de Recolección en Campo</label>

                    <input
                        type="date"
                        name="fecha_recoleccion"
                        class="form-control"
                        value="<?php echo isset($especimen['fecha_recoleccion']) ? htmlspecialchars($especimen['fecha_recoleccion']) : ''; ?>"
                    >
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Localidad de Recolección (Geografía)</label>

                <input
                    type="text"
                    name="localizacion_recoleccion"
                    class="form-control"
                    value="<?php echo isset($especimen['localizacion_recoleccion']) ? htmlspecialchars($especimen['localizacion_recoleccion']) : ''; ?>"
                >
            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado de Identificación</label>

                    <select name="estado" class="form-control" required>
                        <option value="disponible"
                            <?php echo (isset($especimen['estado']) && $especimen['estado'] == 'disponible') ? 'selected' : ''; ?>>
                            Disponible
                        </option>

                        <option value="pendiente_identificacion"
                            <?php echo (isset($especimen['estado']) && $especimen['estado'] == 'pendiente_identificacion') ? 'selected' : ''; ?>>
                            Pendiente de Identificación
                        </option>

                        <option value="prestado"
                            <?php echo (isset($especimen['estado']) && $especimen['estado'] == 'prestado') ? 'selected' : ''; ?>>
                            Prestado
                        </option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Clasificación Taxonómica (Especie)</label>

                    <select name="id_especie" class="form-control">
                        <option value="">-- Sin Clasificar / Datos Parciales --</option>

                        <?php foreach ($especies as $esp): ?>
                            <option value="<?php echo $esp['id_especie']; ?>"
                                <?php echo (isset($especimen['id_especie']) && $especimen['id_especie'] == $esp['id_especie']) ? 'selected' : ''; ?>>

                                <?php
                                if (isset($esp['especie'])) {
                                    echo htmlspecialchars($esp['especie']);
                                }

                                if (isset($esp['genero'])) {
                                    echo ' (' . htmlspecialchars($esp['genero']) . ')';
                                }
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <hr>

            <h5 class="mb-3">Ubicación física del espécimen</h5>

            <p class="text-muted">
                Seleccione solo una ubicación: gaveta para almacenamiento seco o vial para almacenamiento en líquido.
            </p>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Ubicación Asignada (Gaveta)</label>

                    <select name="id_gaveta" id="id_gaveta" class="form-control" onchange="controlarUbicacion();">
                        <option value="">-- Sin Ubicación en Gaveta --</option>

                        <?php if (!empty($especimen['id_gaveta'])): ?>
                            <option value="<?php echo $especimen['id_gaveta']; ?>" selected>
                                Gaveta actual (ID <?php echo $especimen['id_gaveta']; ?>)
                            </option>
                        <?php endif; ?>

                        <?php foreach ($gavetas as $g): ?>

                            <?php
                            if (!empty($especimen['id_gaveta']) && $g['id_gaveta'] == $especimen['id_gaveta']) {
                                continue;
                            }
                            ?>

                            <option value="<?php echo $g['id_gaveta']; ?>">
                                <?php
                                if (isset($g['ruta_completa'])) {
                                    echo htmlspecialchars($g['ruta_completa']);
                                } else {
                                    echo htmlspecialchars($g['codigo']);
                                }
                                ?>
                            </option>

                        <?php endforeach; ?>
                    </select>

                    <div class="form-text">
                        Formato esperado: Gabinete &gt; Gaveta.
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Ubicación Asignada (Vial)</label>

                    <select name="id_vial" id="id_vial" class="form-control" onchange="controlarUbicacion();">
                        <option value="">-- Sin Ubicación en Vial --</option>

                        <?php if (!empty($especimen['id_vial'])): ?>
                            <option value="<?php echo $especimen['id_vial']; ?>" selected>
                                Vial actual (ID <?php echo $especimen['id_vial']; ?>)
                            </option>
                        <?php endif; ?>

                        <?php foreach ($viales as $v): ?>

                            <?php
                            if (!empty($especimen['id_vial']) && $v['id_vial'] == $especimen['id_vial']) {
                                continue;
                            }
                            ?>

                            <option value="<?php echo $v['id_vial']; ?>">
                                <?php
                                if (isset($v['ruta_completa'])) {
                                    echo htmlspecialchars($v['ruta_completa']);
                                } else {
                                    echo htmlspecialchars($v['codigo']);
                                }
                                ?>
                            </option>

                        <?php endforeach; ?>
                    </select>

                    <div class="form-text">
                        Formato esperado: Caja &gt; Vial. Los viales listados están disponibles para reasignación.
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    Guardar Cambios
                </button>

                <a href="?controlador=Index&accion=mostrar" class="btn btn-secondary">
                    Regresar
                </a>
            </div>

        </form>
    </div>
</div>

<script>
function mostrarMensajeValidacion(mensaje) {
    var contenedor = document.getElementById('mensaje_validacion');
    contenedor.innerHTML = mensaje;
    contenedor.style.display = 'block';
}

function ocultarMensajeValidacion() {
    var contenedor = document.getElementById('mensaje_validacion');
    contenedor.innerHTML = '';
    contenedor.style.display = 'none';
}

function controlarUbicacion() {
    var gaveta = document.getElementById('id_gaveta');
    var vial = document.getElementById('id_vial');

    ocultarMensajeValidacion();

    if (gaveta.value !== '') {
        vial.value = '';
        vial.disabled = true;
    } else {
        vial.disabled = false;
    }

    if (vial.value !== '') {
        gaveta.value = '';
        gaveta.disabled = true;
    } else {
        gaveta.disabled = false;
    }
}

function validarFormularioEspecimen() {
    var codigo = document.getElementById('codigo_id').value;
    var gaveta = document.getElementById('id_gaveta');
    var vial = document.getElementById('id_vial');

    ocultarMensajeValidacion();

    if (codigo.trim() === '') {
        mostrarMensajeValidacion('El código identificador de la etiqueta no puede guardarse vacío.');
        return false;
    }

    if (gaveta.value !== '' && vial.value !== '') {
        mostrarMensajeValidacion('Debe seleccionar solo una ubicación física: gaveta o vial, no ambas.');
        return false;
    }

    gaveta.disabled = false;
    vial.disabled = false;

    return true;
}

controlarUbicacion();
</script>

<?php include 'public/footer.php'; ?>