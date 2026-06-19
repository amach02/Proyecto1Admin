<?php
require_once 'model/UsuarioModel.php';

class SessionController
{

    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view  = new View();
        $this->model = new UsuarioModel();
    }

    public function mostrarLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['id_usuario'])) {
            header('Location: ?controlador=Index&accion=mostrar');
            exit;
        }

        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $this->view->show('loginView.php', array('status' => $status));
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $correo     = isset($_POST['correo'])     ? trim($_POST['correo'])     : '';
        $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';

        if (empty($correo) || empty($contrasena)) {
            header('Location: ?controlador=Session&accion=mostrarLogin&status=campos_vacios');
            exit;
        }

        $datos = $this->model->autenticarUsuario($correo);

        if (!$datos || $datos['id_usuario'] === null) {
            header('Location: ?controlador=Session&accion=mostrarLogin&status=correo_no_registrado');
            exit;
        }

        if ($datos['estado'] === 'inhabilitado') {
            header('Location: ?controlador=Session&accion=mostrarLogin&status=inhabilitado');
            exit;
        }

        if (hash('sha256', $contrasena) !== $datos['contrasena_hash']) {
            header('Location: ?controlador=Session&accion=mostrarLogin&status=contrasena_incorrecta');
            exit;
        }

        $_SESSION['id_usuario'] = $datos['id_usuario'];
        $_SESSION['nombre']     = $datos['nombre'];
        $_SESSION['nombre_rol'] = $datos['nombre_rol'];

        header('Location: ?controlador=Index&accion=mostrar');
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ?controlador=Session&accion=mostrarLogin');
        exit;
    }

    public function mostrarRecuperar()
    {
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $this->view->show('recuperarView.php', array('status' => $status));
    }

    public function recuperar()
    {
        $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';

        if (empty($correo)) {
            header('Location: ?controlador=Session&accion=mostrarRecuperar&status=campo_vacio');
            exit;
        }

        require_once 'model/RecuperacionModel.php';
        $model     = new RecuperacionModel();
        $resultado = $model->crearToken($correo);

        if (!$resultado || $resultado['Exito'] == 0) {
            header('Location: ?controlador=Session&accion=mostrarRecuperar&status=correo_no_encontrado');
            exit;
        }

        $token = $resultado['Resultado'];

        // URL del sistema — ajustá el dominio si es necesario
        $url = 'http://localhost/Proyecto1Admin/?controlador=Session&accion=mostrarNuevaContrasena&token=' . $token;

        $cuerpo = '
    <div style="font-family:Arial,sans-serif; max-width:500px; margin:auto;">
        <h2 style="color:#198754;">Recuperación de contraseña</h2>
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta.</p>
        <p>Hacé clic en el botón para continuar. El enlace expira en <strong>1 hora</strong>.</p>
        <p style="text-align:center; margin:30px 0;">
            <a href="' . $url . '"
               style="background:#198754; color:#fff; padding:12px 28px;
                      text-decoration:none; border-radius:6px; font-size:16px;">
                Restablecer contraseña
            </a>
        </p>
        <p style="color:#888; font-size:13px;">
            Si no solicitaste esto, ignorá este correo.
        </p>
    </div>';

        require_once 'libs/Mailer.php';
        $enviado = Mailer::enviar($correo, $correo, 'Recuperación de contraseña — UCR Entomología', $cuerpo);

        if ($enviado) {
            header('Location: ?controlador=Session&accion=mostrarRecuperar&status=correo_enviado');
        } else {
            header('Location: ?controlador=Session&accion=mostrarRecuperar&status=error_envio');
        }
        exit;
    }

    public function mostrarNuevaContrasena()
    {
        $token  = isset($_GET['token'])  ? $_GET['token']  : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';

        if (empty($token)) {
            header('Location: ?controlador=Session&accion=mostrarLogin');
            exit;
        }

        require_once 'model/RecuperacionModel.php';
        $model     = new RecuperacionModel();
        $resultado = $model->validarToken($token);

        if (!$resultado || $resultado['Exito'] == 0) {
            header('Location: ?controlador=Session&accion=mostrarRecuperar&status=token_invalido');
            exit;
        }

        $this->view->show('nuevaContrasenaView.php', array('token' => $token, 'status' => $status));
    }

    public function cambiarContrasena()
    {
        $token    = isset($_POST['token'])    ? trim($_POST['token'])    : '';
        $nueva    = isset($_POST['nueva'])    ? trim($_POST['nueva'])    : '';
        $confirma = isset($_POST['confirma']) ? trim($_POST['confirma']) : '';

        if (empty($token) || empty($nueva) || empty($confirma)) {
            header('Location: ?controlador=Session&accion=mostrarNuevaContrasena&token=' . $token . '&status=campos_vacios');
            exit;
        }

        if ($nueva !== $confirma) {
            header('Location: ?controlador=Session&accion=mostrarNuevaContrasena&token=' . $token . '&status=no_coinciden');
            exit;
        }

        if (strlen($nueva) < 6) {
            header('Location: ?controlador=Session&accion=mostrarNuevaContrasena&token=' . $token . '&status=muy_corta');
            exit;
        }

        require_once 'model/RecuperacionModel.php';
        $model     = new RecuperacionModel();
        $resultado = $model->cambiarContrasena($token, $nueva);

        if ($resultado && $resultado['Exito'] == 1) {
            header('Location: ?controlador=Session&accion=mostrarLogin&status=contrasena_cambiada');
            exit;
        } else {
            header('Location: ?controlador=Session&accion=mostrarNuevaContrasena&token=' . $token . '&status=token_invalido');
            exit;
        }
    }
}
