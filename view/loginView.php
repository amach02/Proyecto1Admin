<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — Colección Entomológica UCR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">

<div class="card shadow-sm" style="width:100%;max-width:400px">
    <div class="card-body p-4">
        <h4 class="mb-1">Colección Entomológica UCR</h4>
        <p class="text-muted mb-4" style="font-size:14px">Iniciá sesión para continuar</p>

        <?php if (!empty($status)): ?>
        <?php
            $mensajes = array(
                'campos_vacios'         => array('danger',  'Completá todos los campos.'),
                'correo_no_registrado'  => array('danger',  'El correo no está registrado.'),
                'contrasena_incorrecta' => array('danger',  'La contraseña es incorrecta.'),
                'inhabilitado'          => array('danger',  'Tu cuenta está inhabilitada. Contactá al administrador.'),
                'sesion_requerida'      => array('warning', 'Debés iniciar sesión para acceder.'),
                'sin_permiso'           => array('warning', 'No tenés permiso para acceder a esa sección.'),
            );
            $msg = isset($mensajes[$status]) ? $mensajes[$status] : null;
        ?>
        <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msg[0]; ?> py-2" style="font-size:14px">
                <?php echo $msg[1]; ?>
            </div>
        <?php endif; ?>
        <?php endif; ?>

        <form method="POST" action="?controlador=Session&accion=login">
            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="correo" class="form-control"
                       placeholder="usuario@ucr.ac.cr" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <input type="password" name="contrasena" class="form-control"
                       placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
        </form>
    </div>
</div>

</body>
</html>