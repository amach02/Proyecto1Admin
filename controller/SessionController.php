<?php
require_once 'model/UsuarioModel.php';

class SessionController {

    private $view;
    private $model;

    public function __construct() {
        require_once 'libs/View.php';
        $this->view  = new View();
        $this->model = new UsuarioModel();
    }

    public function mostrarLogin() {
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

    public function login() {
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

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ?controlador=Session&accion=mostrarLogin');
        exit;
    }
}
?>