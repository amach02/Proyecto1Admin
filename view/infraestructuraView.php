<?php include 'public/header.php'; ?>
<?php
    $tab    = isset($_GET['tab'])    ? $_GET['tab']    : 'gabinetes';
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // Mapas para selects en cascada
    $gabineteIdByCodigo = [];
    foreach ($gabinetes as $g) {
        $gabineteIdByCodigo[$g['codigo']] = $g['id_gabinete'];
    }

    $gavetasPorGabinete = [];
    foreach ($gavetas as $gav) {
        $id_gab = isset($gabineteIdByCodigo[$gav['gabinete']]) ? $gabineteIdByCodigo[$gav['gabinete']] : null;
        if ($id_gab !== null) {
            $gavetasPorGabinete[$id_gab][] = array('id' => $gav['id_gaveta'], 'codigo' => $gav['codigo']);
        }
    }

    $gavetaIdByCodigo = array();
    foreach ($gavetas as $gav) {
        $gavetaIdByCodigo[$gav['codigo']] = $gav['id_gaveta'];
    }

    $cajasPorGaveta = array();
    foreach ($cajas as $c) {
        $id_gav = isset($gavetaIdByCodigo[$c['gaveta']]) ? $gavetaIdByCodigo[$c['gaveta']] : null;
        if ($id_gav !== null) {
            $cajasPorGaveta[$id_gav][] = array('id' => $c['id_caja'], 'codigo' => $c['codigo']);
        }
    }
?>

<div class="container mt-4">

    <h2 class="mb-3">Gestión de Infraestructura</h2>

    <?php if ($status === 'invalido'): ?>
        <div class="alert alert-warning alert-dismissible fade show">Todos los campos marcados con * son obligatorios. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($status === 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show">Error al guardar. El código puede estar duplicado. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($status === 'con_especimenes'): ?>
        <div class="alert alert-danger alert-dismissible fade show"><strong>Acción bloqueada:</strong> El gabinete contiene especímenes activos y no puede ser inhabilitado. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-0" id="infraTabs">
        <li class="nav-item">
            <a class="nav-link <?php echo $tab === 'gabinetes' ? 'active' : ''; ?>"
               href="?controlador=Infraestructura&accion=mostrar&tab=gabinetes">
               Gabinetes (<?php echo count($gabinetes); ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab === 'gavetas' ? 'active' : ''; ?>"
               href="?controlador=Infraestructura&accion=mostrar&tab=gavetas">
               Gavetas (<?php echo count($gavetas); ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab === 'cajas' ? 'active' : ''; ?>"
               href="?controlador=Infraestructura&accion=mostrar&tab=cajas">
               Cajas (<?php echo count($cajas); ?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab === 'viales' ? 'active' : ''; ?>"
               href="?controlador=Infraestructura&accion=mostrar&tab=viales">
               Viales disponibles (<?php echo count($viales); ?>)</a>
        </li>
    </ul>

    <div class="card shadow border-top-0 rounded-top-0 p-3">

        <!-- ===== GABINETES ===== -->
        <div class="<?php echo $tab === 'gabinetes' ? '' : 'd-none'; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Gabinetes del Laboratorio</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalGabinete">+ Nuevo Gabinete</button>
            </div>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr><th>Código</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($gabinetes as $g): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($g['codigo']); ?></strong></td>
                        <td><?php echo htmlspecialchars(isset($g['descripcion']) ? $g['descripcion'] : '—'); ?></td>
                        <td>
                            <?php if ($g['estado'] === 'activo'): ?>
                            <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?controlador=Gabinete&accion=mostrarEditar&id=<?php echo $g['id_gabinete']; ?>"
                               class="btn btn-warning btn-sm">Editar</a>
                            <?php if ($g['estado'] === 'activo'): ?>
                            <a href="?controlador=Infraestructura&accion=inhabilitarGabinete&id=<?php echo $g['id_gabinete']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Inhabilitar el gabinete <?php echo htmlspecialchars($g['codigo']); ?>? Se validará que esté vacío.');">
                               Inhabilitar
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($gabinetes)): ?>
                    <tr><td colspan="4" class="text-center text-muted">Sin registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ===== GAVETAS ===== -->
        <div class="<?php echo $tab === 'gavetas' ? '' : 'd-none'; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Gavetas</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalGaveta">+ Nueva Gaveta</button>
            </div>
            <div class="mb-2">
                <select class="form-select form-select-sm w-auto d-inline-block"
                        onchange="filtrarTabla('tablaGavetas', 1, this.value)">
                    <option value="">— Filtrar por Gabinete —</option>
                    <?php foreach ($gabinetes as $g): ?>
                    <option value="<?php echo htmlspecialchars($g['codigo']); ?>"><?php echo htmlspecialchars($g['codigo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <table class="table table-bordered table-hover" id="tablaGavetas">
                <thead class="table-dark">
                    <tr><th>Código Gaveta</th><th>Gabinete</th><th>Estado Gabinete</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($gavetas as $gav): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($gav['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($gav['gabinete']); ?></td>
                        <td>
                            <span class="badge <?php echo $gav['estado_gabinete'] === 'activo' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo ucfirst($gav['estado_gabinete']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="?controlador=Gaveta&accion=mostrarEditar&id=<?php echo $gav['id_gaveta']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?controlador=Gaveta&accion=inhabilitar&id=<?php echo $gav['id_gaveta']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Deshabilitar gaveta? Se validará que no tenga cajas con insectos.');">Inhabilitar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($gavetas)): ?>
                    <tr><td colspan="4" class="text-center text-muted">Sin registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ===== CAJAS ===== -->
        <div class="<?php echo $tab === 'cajas' ? '' : 'd-none'; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Cajas</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCaja">+ Nueva Caja</button>
            </div>
            <div class="mb-2">
                <select class="form-select form-select-sm w-auto d-inline-block"
                        onchange="filtrarTabla('tablaCajas', 2, this.value)">
                    <option value="">— Filtrar por Gaveta —</option>
                    <?php foreach ($gavetas as $gav): ?>
                    <option value="<?php echo htmlspecialchars($gav['codigo']); ?>"><?php echo htmlspecialchars($gav['codigo']); ?> (<?php echo htmlspecialchars($gav['gabinete']); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <table class="table table-bordered table-hover" id="tablaCajas">
                <thead class="table-dark">
                    <tr><th>Código Caja</th><th>Gabinete</th><th>Gaveta</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($cajas as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($c['gabinete']); ?></td>
                        <td><?php echo htmlspecialchars($c['gaveta']); ?></td>
                        <td>
                            <a href="?controlador=Caja&accion=mostrarEditar&id=<?php echo $c['id_caja']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?controlador=Caja&accion=inhabilitar&id=<?php echo $c['id_caja']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Deshabilitar caja? Se validará que no tenga viales con insectos.');">Inhabilitar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($cajas)): ?>
                    <tr><td colspan="4" class="text-center text-muted">Sin registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ===== VIALES ===== -->
        <div class="<?php echo $tab === 'viales' ? '' : 'd-none'; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Viales Disponibles</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalVial">+ Nuevo Vial</button>
            </div>
            <p class="text-muted small">Solo se muestran viales sin espécimen asignado y en gabinetes activos.</p>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr><th>Ruta Completa</th><th>Vial</th><th>Caja</th><th>Gaveta</th><th>Gabinete</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($viales as $v): ?>
                    <tr>
                        <td><small><?php echo htmlspecialchars($v['ruta_completa']); ?></small></td>
                        <td><?php echo htmlspecialchars($v['vial']); ?></td>
                        <td><?php echo htmlspecialchars($v['caja']); ?></td>
                        <td><?php echo htmlspecialchars($v['gaveta']); ?></td>
                        <td><?php echo htmlspecialchars($v['gabinete']); ?></td>
                        <td>
                            <a href="?controlador=Vial&accion=mostrarEditar&id=<?php echo $v['id_vial']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?controlador=Vial&accion=inhabilitar&id=<?php echo $v['id_vial']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Deshabilitar vial?');">Inhabilitar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($viales)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No hay viales disponibles.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div><!-- /card -->
</div><!-- /container -->

<!-- ===== MODALS ===== -->

<!-- Modal Gabinete -->
<div class="modal fade" id="modalGabinete" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Nuevo Gabinete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?controlador=Infraestructura&accion=registrarGabinete">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Código del Gabinete <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" class="form-control" placeholder="Ej: GAB-03" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción / Ubicación</label>
                        <textarea name="descripcion" class="form-control" rows="2"
                                  placeholder="Ej: Gabinete sección Norte, estante 2"></textarea>
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

<!-- Modal Gaveta (selecciona Gabinete activo) -->
<div class="modal fade" id="modalGaveta" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Nueva Gaveta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?controlador=Infraestructura&accion=registrarGaveta">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Gabinete padre <span class="text-danger">*</span></label>
                        <select name="id_gabinete" class="form-select" required>
                            <option value="">— Seleccione un gabinete —</option>
                            <?php foreach ($gabinetes as $g): if ($g['estado'] === 'activo'): ?>
                            <option value="<?php echo $g['id_gabinete']; ?>"><?php echo htmlspecialchars($g['codigo']); ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                        <div class="form-text">Solo gabinetes activos pueden recibir gavetas.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Código de la Gaveta <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" class="form-control" placeholder="Ej: GAV-03" required>
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

<!-- Modal Caja (cascada: Gabinete → Gaveta) -->
<div class="modal fade" id="modalCaja" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Nueva Caja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?controlador=Infraestructura&accion=registrarCaja">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Gabinete (guía)</label>
                        <select id="cajaGabinete" class="form-select"
                                onchange="cascadeGavetas(this.value, 'cajaGaveta')">
                            <option value="">— Seleccione un gabinete —</option>
                            <?php foreach ($gabinetes as $g): ?>
                            <option value="<?php echo $g['id_gabinete']; ?>"><?php echo htmlspecialchars($g['codigo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gaveta padre <span class="text-danger">*</span></label>
                        <select name="id_gaveta" id="cajaGaveta" class="form-select" required>
                            <option value="">— Seleccione primero un gabinete —</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Código de la Caja <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" class="form-control" placeholder="Ej: CAJ-03" required>
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

<!-- Modal Vial (cascada: Gabinete → Gaveta → Caja) -->
<div class="modal fade" id="modalVial" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Nuevo Vial</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="?controlador=Infraestructura&accion=registrarVial">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Gabinete (guía)</label>
                        <select id="vialGabinete" class="form-select"
                                onchange="cascadeGavetas(this.value, 'vialGaveta'); limpiarSelect('vialCaja', '— Seleccione primero una gaveta —');">
                            <option value="">— Seleccione un gabinete —</option>
                            <?php foreach ($gabinetes as $g): ?>
                            <option value="<?php echo $g['id_gabinete']; ?>"><?php echo htmlspecialchars($g['codigo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gaveta (guía)</label>
                        <select id="vialGaveta" class="form-select"
                                onchange="cascadeCajas(this.value, 'vialCaja')">
                            <option value="">— Seleccione primero un gabinete —</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Caja padre <span class="text-danger">*</span></label>
                        <select name="id_caja" id="vialCaja" class="form-select" required>
                            <option value="">— Seleccione primero una gaveta —</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Código del Vial <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" class="form-control" placeholder="Ej: VIAL-03" required>
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
// Datos para cascada
const gavetasPorGabinete = <?php echo json_encode($gavetasPorGabinete); ?>;
const cajasPorGaveta     = <?php echo json_encode($cajasPorGaveta);     ?>;

function cascadeGavetas(id_gabinete, targetId) {
    const sel = document.getElementById(targetId);
    sel.innerHTML = '<option value="">— Seleccione una gaveta —</option>';
    (gavetasPorGabinete[id_gabinete] || []).forEach(g => {
        sel.innerHTML += `<option value="${g.id}">${g.codigo}</option>`;
    });
}

function cascadeCajas(id_gaveta, targetId) {
    const sel = document.getElementById(targetId);
    sel.innerHTML = '<option value="">— Seleccione una caja —</option>';
    (cajasPorGaveta[id_gaveta] || []).forEach(c => {
        sel.innerHTML += `<option value="${c.id}">${c.codigo}</option>`;
    });
}

function limpiarSelect(id, placeholder) {
    const sel = document.getElementById(id);
    if (sel) sel.innerHTML = `<option value="">${placeholder}</option>`;
}

function filtrarTabla(tablaId, colIdx, valor) {
    document.querySelectorAll(`#${tablaId} tbody tr`).forEach(fila => {
        const celda = fila.cells[colIdx];
        fila.style.display = (!valor || (celda && celda.textContent.trim() === valor)) ? '' : 'none';
    });
}

<?php if ($status === 'registrado_success'): ?>
document.addEventListener('DOMContentLoaded', () => mostrarToast('Registro guardado exitosamente.', 'success'));
<?php elseif ($status === 'inhabilitado_success'): ?>
document.addEventListener('DOMContentLoaded', () => mostrarToast('Gabinete inhabilitado correctamente.', 'success'));
<?php endif; ?>
</script>

<?php include 'public/footer.php'; ?>
