<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">
    <div class="card shadow-sm" style="width:100%;max-width:400px">
        <div class="card-body p-4">
            <h5 class="mb-1">Recuperar contraseña</h5>
            <p class="text-muted mb-4" style="font-size:14px">Ingresá tu correo y te daremos acceso para cambiarla.</p>

            <?php
            $mensajes = array(
                'campo_vacio'         => array('danger',  'Ingresá tu correo.'),
                'correo_no_encontrado' => array('danger',  'El correo no está registrado o la cuenta está inhabilitada.'),
                'token_invalido'      => array('danger',  'El enlace es inválido o ya expiró.'),
                'correo_enviado' => array('success', 'Revisá tu bandeja de entrada, te enviamos el enlace.'),
                'error_envio'    => array('danger',  'No se pudo enviar el correo. Intentá más tarde.'),
            );
            if (!empty($status) && isset($mensajes[$status])):
                $msg = $mensajes[$status];
            ?>
                <div class="alert alert-<?php echo $msg[0]; ?> py-2" style="font-size:14px">
                    <?php echo $msg[1]; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="?controlador=Session&accion=recuperar">
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary w-100">Continuar</button>
                <a href="?controlador=Session&accion=mostrarLogin"
                    class="btn btn-link w-100 mt-2">Volver al login</a>
            </form>
        </div>
    </div>
</body>

</html>