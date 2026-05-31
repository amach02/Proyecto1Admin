<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">
<div class="card shadow-sm" style="width:100%;max-width:400px">
    <div class="card-body p-4">
        <h5 class="mb-1">Nueva contraseña</h5>
        <p class="text-muted mb-4" style="font-size:14px">Ingresá tu nueva contraseña.</p>

        <?php
        $mensajes = array(
            'campos_vacios' => array('danger',  'Completá todos los campos.'),
            'no_coinciden'  => array('danger',  'Las contraseñas no coinciden.'),
            'muy_corta'     => array('danger',  'La contraseña debe tener al menos 6 caracteres.'),
            'token_invalido'=> array('danger',  'El enlace es inválido o ya expiró.'),
        );
        if (!empty($status) && isset($mensajes[$status])):
            $msg = $mensajes[$status];
        ?>
        <div class="alert alert-<?php echo $msg[0]; ?> py-2" style="font-size:14px">
            <?php echo $msg[1]; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="?controlador=Session&accion=cambiarContrasena">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <div class="mb-3">
                <label class="form-label">Nueva contraseña</label>
                <input type="password" name="nueva" class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="confirma" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cambiar contraseña</button>
        </form>
    </div>
</div>
</body>
</html>