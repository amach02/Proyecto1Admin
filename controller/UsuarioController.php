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
            header('Location: ?controlador=Index&accion=mostrar&status=inhabilitado_success');
        } else {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
        }
    }
}
?>