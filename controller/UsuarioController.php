<?php
require_once 'model/UsuarioModel.php';

class UsuarioController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new UsuarioModel();
    }

    // Mostrar el formulario de edición con los datos cargados
    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

        $id = $_GET['id'];
        $datosUsuario = $this->model->buscarUsuarioPorId($id);

        if ($datosUsuario) {
            // Le pasamos los datos del usuario a la vista
            $this->view->show('editarUsuarioView.php', ['usuario' => $datosUsuario]);
        } else {
            header('Location: ?controlador=Index&accion=mostrar&status=no_encontrado');
        }
    }

    // Procesar el formulario de edición (UPDATE)
    public function editar()
    {
        $id = trim($_POST['id_usuario']);
        $nombre = trim($_POST['nombre']);
        $correo = trim($_POST['correo']);
        $id_rol = trim($_POST['id_rol']);

        if (empty($id) || empty($nombre) || empty($correo) || empty($id_rol)) {
            header("Location: ?controlador=Usuario&accion=mostrarEditar&id=$id&status=invalido");
            return;
        }

        $resultado = $this->model->editarUsuario($id, $nombre, $correo, $id_rol);

        if ($resultado) {
            header('Location: ?controlador=Index&accion=mostrar&status=editado_success');
        } else {
            header("Location: ?controlador=Usuario&accion=mostrarEditar&id=$id&status=error");
        }
    }

    // Procesar la inhabilitación (LOGICAL DELETE)
    public function inhabilitar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

        $id = $_GET['id'];
        $resultado = $this->model->inhabilitarUsuario($id);

        if ($resultado) {
            header('Location: ?controlador=Usuario&accion=mostrarListar&status=inhabilitado_success');
        } else {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
        }
    }

    public function mostrarListar()
    {
        $usuarios = $this->model->listarUsuarios();
        $this->view->show('listarUsuariosView.php', ['usuarios' => $usuarios]);
    }

    public function mostrarRegistrar()
    {
        $this->view->show('registrarUsuarioView.php', []);
    }

    public function registrar()
    {
        $nombre   = trim($_POST['nombre']);
        $correo   = trim($_POST['correo']);
        $contrasena = trim($_POST['contrasena']);
        $id_rol   = trim($_POST['id_rol']);

        if (empty($nombre) || empty($correo) || empty($contrasena) || empty($id_rol)) {
            header('Location: ?controlador=Usuario&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarUsuario($nombre, $correo, $contrasena, $id_rol);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Usuario&accion=mostrarListar&status=registrado_success');
        } else {
            header('Location: ?controlador=Usuario&accion=mostrarRegistrar&status=error');
        }
    }

    public function buscarPorCorreo()
    {
        $correo = isset($_GET['correo']) ? trim($_GET['correo']) : '';

        if (empty($correo)) {
            header('Location: ?controlador=Usuario&accion=mostrarListar&status=invalido');
            return;
        }

        $usuario = $this->model->buscarUsuarioPorCorreo($correo);
        $this->view->show('listarUsuariosView.php', ['usuarios' => $usuario ? [$usuario] : [], 'busqueda' => $correo]);
    }

    public function autenticar()
    {
        $correo = trim($_POST['correo']);
        $contrasena = trim($_POST['contrasena']);

        if (empty($correo) || empty($contrasena)) {
            header('Location: ?controlador=Index&accion=mostrar&status=invalido');
            return;
        }

        $datos = $this->model->autenticarUsuario($correo);

        if (!$datos || $datos['id_usuario'] === null) {
            header('Location: ?controlador=Index&accion=mostrar&status=correo_no_registrado');
            return;
        }

        if ($datos['estado'] === 'inhabilitado') {
            header('Location: ?controlador=Index&accion=mostrar&status=inhabilitado');
            return;
        }

        $hashIngresado = hash('sha256', $contrasena);
        if ($hashIngresado !== $datos['contrasena_hash']) {
            header('Location: ?controlador=Index&accion=mostrar&status=contrasena_incorrecta');
            return;
        }

        session_start();
        $_SESSION['id_usuario']  = $datos['id_usuario'];
        $_SESSION['nombre']      = $datos['nombre'];
        $_SESSION['nombre_rol']  = $datos['nombre_rol'];

        header('Location: ?controlador=Index&accion=mostrar');
    }
}
?>