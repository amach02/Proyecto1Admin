<?php
require_once 'model/GabineteModel.php';

class GabineteController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new GabineteModel();
    }

    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

        $id = $_GET['id'];
        $datosGabinete = $this->model->buscarGabinetePorId($id);

        if ($datosGabinete) {
            $this->view->show('editarGabineteView.php', ['gabinete' => $datosGabinete]);
        } else {
            header('Location: ?controlador=Index&accion=mostrar&status=no_encontrado');
        }
    }

    public function editar()
    {
        $id = trim($_POST['id_gabinete']);
        $codigo = trim($_POST['codigo']);
        $descripcion = trim($_POST['descripcion']);

        if (empty($id) || empty($codigo)) {
            header("Location: ?controlador=Gabinete&accion=mostrarEditar&id=$id&status=invalido");
            return;
        }

        $resultado = $this->model->editarGabinete($id, $codigo, $descripcion);

        if ($resultado) {
            header('Location: ?controlador=Index&accion=mostrar&status=gabinete_editado');
        } else {
            header("Location: ?controlador=Gabinete&accion=mostrarEditar&id=$id&status=error");
        }
    }

    public function inhabilitar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

        $id = $_GET['id'];
        $respuesta = $this->model->inhabilitarGabinete($id);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Gabinete&accion=mostrarListar&status=gabinete_inhabilitado');
        } else {
            // Si el SP devolvió Exito = 0 significa que falló por la validación de insectos dentro
            header("Location: ?controlador=Gabinete&accion=mostrarEditar&id=$id&status=con_especimenes");
        }
    }

    public function mostrarListar()
    {
        $gabinetes = $this->model->listarGabinetes();
        $this->view->show('listarGabinetesView.php', ['gabinetes' => $gabinetes]);
    }

    public function mostrarRegistrar()
    {
        $this->view->show('registrarGabineteView.php', []);
    }

    public function registrar()
    {
        $codigo      = trim($_POST['codigo']);
        $descripcion = trim($_POST['descripcion']);

        if (empty($codigo)) {
            header('Location: ?controlador=Gabinete&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarGabinete($codigo, $descripcion);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Gabinete&accion=mostrarListar&status=registrado_success');
        } else {
            header('Location: ?controlador=Gabinete&accion=mostrarRegistrar&status=error');
        }
    }
}
?>