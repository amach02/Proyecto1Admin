<?php
// Declaraciones base para evitar alertas de variables en tu editor de código
$gabinetes = isset($gabinetes) ? $gabinetes : array();
$cajas     = isset($cajas) ? $cajas : array();
$plantas   = isset($plantas) ? $plantas : array();
$status    = isset($_GET['status']) ? $_GET['status'] : '';

$tab       = isset($_GET['tab']) ? $_GET['tab'] : 'gabinetes';

include 'public/header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Gestión de Infraestructura de Almacenamiento</h2>

    <?php if ($status === 'invalido'): ?>
        <div class="alert alert-warning alert-dismissible fade show">Verifique los campos obligatorios antes de guardar. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($status === 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show">Error al procesar la solicitud. El código podría estar duplicado. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($status === 'registrado_success' || $status === 'registrada_success'): ?>
        <div class="alert alert-success alert-dismissible fade show">Registro completado exitosamente. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($status === 'editada_success'): ?>
        <div class="alert alert-success alert-dismissible fade show">Planta hospedadora actualizada correctamente. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($status === 'inhabilitada_success'): ?>
        <div class="alert alert-warning alert-dismissible fade show">Planta hospedadora inhabilitada correctamente. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif ($status === 'no_encontrado'): ?>
        <div class="alert alert-danger alert-dismissible fade show">No se encontró el registro solicitado. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row g-4 mb-5">
        
        <?php if ($tab === 'gabinetes'): ?>
            <div class="col-12">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0">Ruta Seca: Almacenamiento en Gabinetes</h4>
                        <button class="btn btn-light text-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalGabinete">+ Nuevo Gabinete</button>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">A continuación se muestran los gabinetes del laboratorio. Haz clic sobre cualquiera de ellos para desplegar la lista de gavetas que contiene.</p>
                        
                        <div class="accordion" id="acordeonGabinetes">
                            <?php if (!empty($gabinetes)): ?>
                                <?php foreach ($gabinetes as $index => $g): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingGab<?php echo $index; ?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGab<?php echo $index; ?>">
                                                <span class="fs-5 me-3">📁</span>
                                                <strong class="fs-5"><?php echo htmlspecialchars($g['codigo']); ?></strong>
                                                <span class="badge bg-<?php echo (isset($g['estado']) && $g['estado'] === 'activo') ? 'success' : 'secondary'; ?> ms-3">
                                                    <?php echo (isset($g['estado']) && $g['estado'] === 'activo') ? 'Activo' : 'Inactivo'; ?>
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="collapseGab<?php echo $index; ?>" class="accordion-collapse collapse" data-bs-parent="#acordeonGabinetes">
                                            <div class="accordion-body bg-light p-4">
                                                
                                                <span class="d-block fw-bold text-secondary mb-3 small uppercase tracking-wider">Gavetas Internas Asignadas:</span>
                                                
                                                <?php if (!empty($g['gavetas'])): ?>
                                                    <ul class="list-group list-group-flush shadow-sm mb-4 rounded border">
                                                        <?php foreach ($g['gavetas'] as $gav): ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                                                <span class="fw-bold text-dark fs-6">🔹 <?php echo htmlspecialchars($gav['codigo']); ?></span>
                                                                <a href="?controlador=Gaveta&accion=mostrarEditar&id=<?php echo $gav['id_gaveta']; ?>" class="text-primary small text-decoration-none fw-bold">Editar Gaveta</a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php else: ?>
                                                    <div class="alert alert-light border text-muted small py-3 mb-4">No hay gavetas registradas dentro de este gabinete técnico.</div>
                                                <?php endif; ?>

                                                <div class="text-end border-top pt-3 mt-2">
                                                    <a href="?controlador=Gabinete&accion=mostrarEditar&id=<?php echo $g['id_gabinete']; ?>" class="btn btn-sm btn-success px-3">
                                                        + Añadir Gaveta / Editar Datos del Gabinete
                                                    </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-secondary text-center py-4 fs-6">No se encontraron gabinetes registrados en la base de datos.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($tab === 'plantas'): ?>
            <div class="col-12">
                <div class="card shadow-sm border-warning">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0">Plantas Hospedadoras Registradas</h4>
                        <button class="btn btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalPlanta">+ Nueva Planta</button>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Catálogo de plantas hospedadoras asociables a especímenes del laboratorio. Solo se pueden inhabilitar plantas sin especímenes vinculados.</p>

                        <?php if (!empty($plantas)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre de la Planta</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($plantas as $p): ?>
                                            <tr>
                                                <td class="text-muted"><?php echo htmlspecialchars($p['id_planta']); ?></td>
                                                <td><strong><?php echo htmlspecialchars($p['nombre']); ?></strong></td>
                                                <td class="text-end">
                                                    <a href="?controlador=Planta&accion=mostrarEditar&id=<?php echo $p['id_planta']; ?>"
                                                       class="btn btn-warning btn-sm text-dark fw-bold">Editar / Inhabilitar</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-secondary text-center py-4 fs-6">No se encontraron plantas hospedadoras registradas en la base de datos.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($tab === 'cajas'): ?>
            <div class="col-12">
                <div class="card shadow-sm border-info">
                    <div class="card-header bg-info text-dark d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0">Ruta Líquida: Almacenamiento en Cajas</h4>
                        <button class="btn btn-light text-info fw-bold" data-bs-toggle="modal" data-bs-target="#modalCaja">+ Nueva Caja</button>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">A continuación se muestran las cajas de preservación en líquido del laboratorio. Haz clic sobre cualquiera de ellas para desplegar la lista de viales contenidos.</p>
                        
                        <div class="accordion" id="acordeonCajas">
                            <?php if (!empty($cajas)): ?>
                                <?php foreach ($cajas as $index => $c): 
                                    $codCaja = isset($c['codigo']) ? $c['codigo'] : (isset($c['codigo_caja']) ? $c['codigo_caja'] : '');
                                ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingCaj<?php echo $index; ?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCaj<?php echo $index; ?>">
                                                <span class="fs-5 me-3">📦</span>
                                                <strong class="fs-5"><?php echo htmlspecialchars($codCaja); ?></strong>
                                            </button>
                                        </h2>
                                        <div id="collapseCaj<?php echo $index; ?>" class="accordion-collapse collapse" data-bs-parent="#acordeonCajas">
                                            <div class="accordion-body bg-light p-4">
                                                
                                                <span class="d-block fw-bold text-secondary mb-3 small uppercase tracking-wider">Viales e Insectos Contenidos:</span>

                                                <?php if (!empty($c['viales'])): ?>
                                                    <ul class="list-group list-group-flush shadow-sm mb-4 rounded border">
                                                        <?php foreach ($c['viales'] as $v): 
                                                            $codVial = isset($v['vial']) ? $v['vial'] : (isset($v['codigo_vial']) ? $v['codigo_vial'] : (isset($v['codigo']) ? $v['codigo'] : ''));
                                                        ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                                                <span class="fw-bold text-dark fs-6">🧪 <?php echo htmlspecialchars($codVial); ?></span>
                                                                <a href="?controlador=Vial&accion=mostrarEditar&id=<?php echo $v['id_vial']; ?>" class="text-primary small text-decoration-none fw-bold">Editar Vial</a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php else: ?>
                                                    <div class="alert alert-light border text-muted small py-3 mb-4">Esta caja de seguridad se encuentra actualmente vacía, sin viales asociados.</div>
                                                <?php endif; ?>
                                                
                                                <div class="text-end border-top pt-3 mt-2">
                                                    <a href="?controlador=Caja&accion=mostrarEditar&id=<?php echo $c['id_caja']; ?>" class="btn btn-sm btn-info text-dark px-3 fw-bold">
                                                        + Añadir Vial / Editar Datos de la Caja
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-secondary text-center py-4 fs-6">No se encontraron cajas independientes registradas en la base de datos.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="modal fade" id="modalGabinete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Registrar Nuevo Gabinete Técnico</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="?controlador=Infraestructura&accion=registrarGabinete">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Código Identificador <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" class="form-control form-control-lg" placeholder="Ej: GAB-01" required>
                        <div class="form-text">Ingrese un código único para referenciar la estructura física.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notas de Ubicación / Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Ej: Pasillo norte, estante número 3..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4">Guardar Estructura</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCaja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-dark">
                <h5 class="modal-title fw-bold">Registrar Nueva Caja Líquida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="?controlador=Infraestructura&accion=registrarCaja">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Código de la Caja <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" class="form-control form-control-lg" placeholder="Ej: CAJA-10" required>
                        <div class="form-text">Las cajas se registran de forma independiente para viales en alcohol.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-dark fw-bold px-4">Guardar Contenedor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPlanta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold">Registrar Nueva Planta Hospedadora</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="?controlador=Planta&accion=registrar">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre de la Planta <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control form-control-lg" placeholder="Ej: Quercus robur" required>
                        <div class="form-text">Ingrese el nombre común o científico de la planta hospedadora.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4">Guardar Planta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'public/footer.php'; ?>