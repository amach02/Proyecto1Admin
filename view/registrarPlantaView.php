<?php include 'public/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow p-4" style="max-width: 600px; margin: 0 auto;">
        <h2 class="mb-4">Registrar Planta Hospedadora</h2>

        <?php $status = isset($_GET['status']) ? $_GET['status'] : ''; ?>

        <?php if ($status === 'invalido'): ?>
            <div class="alert alert-warning">El nombre de la planta es obligatorio.</div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-danger">Error al registrar. Ya existe una planta con ese nombre.</div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Planta&accion=registrar">
            <div class="mb-3">
                <label class="form-label fw-bold">Nombre de la Planta <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control form-control-lg"
                       placeholder="Ej: Quercus robur" required autofocus>
                <div class="form-text">Ingrese el nombre común o científico de la planta hospedadora.</div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning text-dark fw-bold px-4">Guardar Planta</button>
                <a href="?controlador=Infraestructura&accion=mostrar&tab=plantas" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include 'public/footer.php'; ?>
