<?php include 'public/header.php'; ?>
<?php
    $tab    = isset($_GET['tab'])    ? $_GET['tab']    : 'ordenes';
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // Construir mapas para selects en cascada
    $ordenIdByNombre = [];
    foreach ($ordenes as $o) {
        $ordenIdByNombre[$o['nombre']] = $o['id_orden'];
    }

    $familiasPorOrden = [];
    foreach ($familias as $f) {
        $id_o = $ordenIdByNombre[$f['orden']] ?? null;
        if ($id_o !== null) {
            $familiasPorOrden[$id_o][] = ['id' => $f['id_familia'], 'nombre' => $f['nombre']];
        }
    }

    $familiaIdByNombre = [];
    foreach ($familias as $f) {
        $familiaIdByNombre[$f['nombre']] = $f['id_familia'];
    }

    $generosPorFamilia = [];
    foreach ($generos as $g) {
        $id_f = $familiaIdByNombre[$g['familia']] ?? null;
        if ($id_f !== null) {
            $generosPorFamilia[$id_f][] = ['id' => $g['id_genero'], 'nombre' => $g['nombre']];
        }
    }
?>

<div class="container mt-4">

    <h2 class="mb-3">Gestión Taxonómica</h2>

    <?php if ($status === 'invalido'): ?>
        <div class="alert alert-warning alert-dismissible fade show">Todos los campos marcados con * son obligatorios. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($status === 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show">Error al guardar. El registro puede existir ya en la base de datos. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Tabs de navegación -->
    <ul class="nav nav-tabs mb-0" id="taxTabs">
        <li class="nav-item">
            <a class="nav-link <?php echo $tab === 'ordenes'  ? 'active' : ''; ?>"
               href="?controlador=Taxonomia&accion=mostrar&tab=ordenes">Órdenes (<?php echo count($ordenes); ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab === 'familias' ? 'active' : ''; ?>"
               href="?controlador=Taxonomia&accion=mostrar&tab=familias">Familias (<?php echo count($familias); ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab === 'generos'  ? 'active' : ''; ?>"
               href="?controlador=Taxonomia&accion=mostrar&tab=generos">Géneros (<?php echo count($generos); ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab === 'especies' ? 'active' : ''; ?>"
               href="?controlador=Taxonomia&accion=mostrar&tab=especies">Especies (<?php echo count($especies); ?>)</a>
        </li>
    </ul>

    <div class="card shadow border-top-0 rounded-top-0 p-3">

        <!-- ===== ÓRDENES ===== -->
        <div class="<?php echo $tab === 'ordenes' ? '' : 'd-none'; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Listado de Órdenes Taxonómicos</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalOrden">+ Nuevo Orden</button>
            </div>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr><th>#</th><th>Nombre del Orden</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($ordenes as $o): ?>
                    <tr>
                        <td><?php echo $o['id_orden']; ?></td>
                        <td><?php echo htmlspecialchars($o['nombre']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($ordenes)): ?>
                    <tr><td colspan="2" class="text-center text-muted">Sin registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ===== FAMILIAS ===== -->
        <div class="<?php echo $tab === 'familias' ? '' : 'd-none'; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Listado de Familias</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalFamilia">+ Nueva Familia</button>
            </div>
            <div class="mb-2">
                <select class="form-select form-select-sm w-auto d-inline-block"
                        onchange="filtrarTabla('tablaFamilias', 2, this.value)">
                    <option value="">— Filtrar por Orden —</option>
                    <?php foreach ($ordenes as $o): ?>
                    <option value="<?php echo htmlspecialchars($o['nombre']); ?>"><?php echo htmlspecialchars($o['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <table class="table table-bordered table-hover" id="tablaFamilias">
                <thead class="table-dark">
                    <tr><th>#</th><th>Familia</th><th>Orden</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($familias as $f): ?>
                    <tr>
                        <td><?php echo $f['id_familia']; ?></td>
                        <td><?php echo htmlspecialchars($f['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($f['orden']); ?></td>
                        <td>
                            <a href="?controlador=Familia&accion=mostrarEditar&id=<?php echo $f['id_familia']; ?>"
                               class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($familias)): ?>
                    <tr><td colspan="4" class="text-center text-muted">Sin registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ===== GÉNEROS ===== -->
        <div class="<?php echo $tab === 'generos' ? '' : 'd-none'; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Listado de Géneros</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalGenero">+ Nuevo Género</button>
            </div>
            <div class="mb-2">
                <select class="form-select form-select-sm w-auto d-inline-block"
                        onchange="filtrarTabla('tablaGeneros', 2, this.value)">
                    <option value="">— Filtrar por Familia —</option>
                    <?php foreach ($familias as $f): ?>
                    <option value="<?php echo htmlspecialchars($f['nombre']); ?>"><?php echo htmlspecialchars($f['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <table class="table table-bordered table-hover" id="tablaGeneros">
                <thead class="table-dark">
                    <tr><th>#</th><th>Género</th><th>Familia</th><th>Orden</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($generos as $g): ?>
                    <tr>
                        <td><?php echo $g['id_genero']; ?></td>
                        <td><em><?php echo htmlspecialchars($g['nombre']); ?></em></td>
                        <td><?php echo htmlspecialchars($g['familia']); ?></td>
                        <td><?php echo htmlspecialchars($g['orden']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($generos)): ?>
                    <tr><td colspan="4" class="text-center text-muted">Sin registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ===== ESPECIES ===== -->
        <div class="<?php echo $tab === 'especies' ? '' : 'd-none'; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Listado de Especies</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalEspecie">+ Nueva Especie</button>
            </div>
            <div class="mb-2">
                <select class="form-select form-select-sm w-auto d-inline-block"
                        onchange="filtrarTabla('tablaEspecies', 2, this.value)">
                    <option value="">— Filtrar por Género —</option>
                    <?php foreach ($generos as $g): ?>
                    <option value="<?php echo htmlspecialchars($g['nombre']); ?>"><?php echo htmlspecialchars($g['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <table class="table table-bordered table-hover" id="tablaEspecies">
                <thead class="table-dark">
                    <tr><th>#</th><th>Especie</th><th>Género</th><th>Familia</th><th>Orden</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($especies as $e): ?>
                    <tr>
                        <td><?php echo $e['id_especie']; ?></td>
                        <td><em><?php echo htmlspecialchars($e['especie']); ?></em></td>
                        <td><?php echo htmlspecialchars($e['genero']); ?></td>
                        <td><?php echo htmlspecialchars($e['familia']); ?></td>
                        <td><?php echo htmlspecialchars($e['orden']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($especies)): ?>
                    <tr><td colspan="5" class="text-center text-muted">Sin registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div><!-- /card -->
</div><!-- /container -->

<!-- ===== MODALS ===== -->

<!-- Modal Orden -->
<div class="modal fade" id="modalOrden" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Nuevo Orden</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?controlador=Taxonomia&accion=registrarOrden">
                <div class="modal-body">
                    <label class="form-label">Nombre del Orden <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Hymenoptera" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Familia -->
<div class="modal fade" id="modalFamilia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Nueva Familia</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?controlador=Taxonomia&accion=registrarFamilia">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Orden al que pertenece <span class="text-danger">*</span></label>
                        <select name="id_orden" class="form-select" required>
                            <option value="">— Seleccione un orden —</option>
                            <?php foreach ($ordenes as $o): ?>
                            <option value="<?php echo $o['id_orden']; ?>"><?php echo htmlspecialchars($o['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Familia <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Apidae" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Género (cascada: Orden → Familia) -->
<div class="modal fade" id="modalGenero" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Nuevo Género</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?controlador=Taxonomia&accion=registrarGenero">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Orden (guía de selección)</label>
                        <select id="genOrden" class="form-select"
                                onchange="cascadeFamilias(this.value, 'genFamilia')">
                            <option value="">— Seleccione un orden —</option>
                            <?php foreach ($ordenes as $o): ?>
                            <option value="<?php echo $o['id_orden']; ?>"><?php echo htmlspecialchars($o['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Familia <span class="text-danger">*</span></label>
                        <select name="id_familia" id="genFamilia" class="form-select" required>
                            <option value="">— Seleccione primero un orden —</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre del Género <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Apis" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Especie (cascada: Orden → Familia → Género) -->
<div class="modal fade" id="modalEspecie" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Nueva Especie</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?controlador=Taxonomia&accion=registrarEspecie">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Orden (guía de selección)</label>
                        <select id="espOrden" class="form-select"
                                onchange="cascadeFamilias(this.value, 'espFamilia'); limpiarSelect('espGenero', '— Seleccione primero una familia —');">
                            <option value="">— Seleccione un orden —</option>
                            <?php foreach ($ordenes as $o): ?>
                            <option value="<?php echo $o['id_orden']; ?>"><?php echo htmlspecialchars($o['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Familia (guía de selección)</label>
                        <select id="espFamilia" class="form-select"
                                onchange="cascadeGeneros(this.value, 'espGenero')">
                            <option value="">— Seleccione primero un orden —</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Género <span class="text-danger">*</span></label>
                        <select name="id_genero" id="espGenero" class="form-select" required>
                            <option value="">— Seleccione primero una familia —</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Especie <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Apis mellifera" required>
                        <div class="form-text">Use nomenclatura binomial (Género especie).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Datos para cascada (generados desde PHP)
const familiasPorOrden  = <?php echo json_encode($familiasPorOrden);  ?>;
const generosPorFamilia = <?php echo json_encode($generosPorFamilia); ?>;

function cascadeFamilias(id_orden, targetId) {
    const sel = document.getElementById(targetId);
    sel.innerHTML = '<option value="">— Seleccione una familia —</option>';
    const lista = familiasPorOrden[id_orden] || [];
    lista.forEach(f => {
        sel.innerHTML += `<option value="${f.id}">${f.nombre}</option>`;
    });
}

function cascadeGeneros(id_familia, targetId) {
    const sel = document.getElementById(targetId);
    sel.innerHTML = '<option value="">— Seleccione un género —</option>';
    const lista = generosPorFamilia[id_familia] || [];
    lista.forEach(g => {
        sel.innerHTML += `<option value="${g.id}">${g.nombre}</option>`;
    });
}

function limpiarSelect(id, placeholder) {
    const sel = document.getElementById(id);
    sel.innerHTML = `<option value="">${placeholder}</option>`;
}

// Filtro client-side para tablas
function filtrarTabla(tablaId, colIdx, valor) {
    document.querySelectorAll(`#${tablaId} tbody tr`).forEach(fila => {
        const celda = fila.cells[colIdx];
        fila.style.display = (!valor || (celda && celda.textContent.trim() === valor)) ? '' : 'none';
    });
}

// Toast de éxito
<?php if ($status === 'registrado_success'): ?>
document.addEventListener('DOMContentLoaded', () => mostrarToast('Registro guardado exitosamente.', 'success'));
<?php endif; ?>
</script>

<?php include 'public/footer.php'; ?>
