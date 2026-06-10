<?php include 'public/header.php'; ?>
<?php
$status        = isset($_GET['status'])        ? $_GET['status']        : '';
$busqueda      = isset($busqueda)              ? $busqueda              : '';
$sin_resultados = isset($sin_resultados)       ? $sin_resultados        : false;
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Especímenes Entomológicos</h2>
        <a href="?controlador=Especimen&accion=mostrarRegistrar" class="btn btn-success">+ Registrar Espécimen</a>
    </div>

    <!-- Búsqueda por código -->
    <div class="card shadow-sm mb-3 p-3">
        <form method="GET" action="index.php" class="row g-2 align-items-end">
            <input type="hidden" name="controlador" value="Especimen">
            <input type="hidden" name="accion" value="buscarPorCodigo">
            <div class="col-md-6">
                <label class="form-label mb-1">Buscar por Código ID</label>
                <input type="text" name="codigo" class="form-control" placeholder="Ej: UCR-ENT-001"
                    value="<?php echo htmlspecialchars($busqueda); ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if ($busqueda): ?>
                    <a href="?controlador=Especimen&accion=mostrarListar" class="btn btn-outline-secondary">Ver todos</a>
                <?php endif; ?>
            </div>
        </form>
        <?php if ($sin_resultados): ?>
            <div class="alert alert-warning mt-2 mb-0">No se encontró ningún espécimen con el código <strong><?php echo htmlspecialchars($busqueda); ?></strong>.</div>
        <?php endif; ?>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Código ID</th>
                        <th>Especie</th>
                        <th>Localización</th>
                        <th>Fecha Recolección</th>
                        <th>Estado</th>
                        <th>Ubicación Física</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($especimenes as $e): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($e['codigo_id']); ?></strong></td>
                            <td><em><?php echo htmlspecialchars(isset($e['especie']) ? $e['especie'] : '—'); ?></em></td>
                            <td><?php echo htmlspecialchars(isset($e['localizacion_recoleccion']) ? $e['localizacion_recoleccion'] : '—'); ?></td>
                            <td><?php echo isset($e['fecha_recoleccion']) ? $e['fecha_recoleccion'] : '—'; ?></td>
                            <td>
                                <?php
                                $badgeMap = array(
                                    'disponible'               => 'bg-success',
                                    'prestado'                 => 'bg-warning text-dark',
                                    'pendiente_identificacion' => 'bg-secondary'
                                );
                                $badgeClass  = isset($badgeMap[$e['estado']]) ? $badgeMap[$e['estado']] : 'bg-light text-dark';
                                $estadoLabel = str_replace('_', ' ', ucfirst($e['estado']));
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo $estadoLabel; ?></span>
                            </td>
                            <td><small><?php echo htmlspecialchars(isset($e['ubicacion_fisica']) ? $e['ubicacion_fisica'] : '—'); ?></small></td>
                            <td>
                                <a href="?controlador=Especimen&accion=mostrarDetalle&id=<?php echo $e['id_especimen']; ?>"
                                    class="btn btn-primary btn-sm">Ver</a>
                                <a href="?controlador=Especimen&accion=mostrarEditar&id=<?php echo $e['id_especimen']; ?>"
                                    class="btn btn-warning btn-sm">Editar</a>
                                <a href="?controlador=Especimen&accion=rutaFisica&id=<?php echo $e['id_especimen']; ?>"
                                    class="btn btn-info btn-sm">Ruta</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($especimenes) && !$sin_resultados): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No hay especímenes registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include 'public/footer.php'; ?>
<script>
    <?php if ($status === 'registrado_success'): ?>
        document.addEventListener('DOMContentLoaded', () => mostrarToast('Espécimen registrado exitosamente.', 'success'));
    <?php elseif ($status === 'especimen_editado_success'): ?>
        document.addEventListener('DOMContentLoaded', () => mostrarToast('Espécimen actualizado exitosamente.', 'success'));
    <?php elseif ($status === 'error'): ?>
        document.addEventListener('DOMContentLoaded', () => mostrarToast('Ocurrió un error. Intente nuevamente.', 'danger'));
    <?php endif; ?>
</script>