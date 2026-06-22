PlantaController



<?php
require_once 'model/PlantaModel.php';

class PlantaController
{
    private $view;
    private $model;

    public function __construct()
    {
        // 1. Agregamos el inicio de sesión aquí, igual que en el otro controlador
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once 'libs/View.php';
        $this->view  = new View();
        $this->model = new PlantaModel();
    }

    public function mostrarListar()
    {
        $plantas = $this->model->listarPlantas();
        $this->view->show('listarPlantasView.php', ['plantas' => $plantas]);
    }

    public function mostrarRegistrar()
    {
        $this->view->show('registrarPlantaView.php', []);
    }

    public function registrar()
    {
        $nombre_cientifico = trim($_POST['nombre_cientifico']);
        $nombre_comun      = trim($_POST['nombre_comun']);
        $id_usuario        = $_SESSION['id_usuario'];

        if (empty($nombre_cientifico)) {
            header('Location: ?controlador=Planta&accion=mostrarRegistrar&status=invalido');
            exit; // Agregado
        }

        $respuesta = $this->model->registrarPlanta($nombre_cientifico, $nombre_comun, $id_usuario);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Planta&accion=mostrarListar&status=registrada_success');
            exit;
        } else {
            header('Location: ?controlador=Planta&accion=mostrarRegistrar&status=error');
            exit; // Agregado
        }
    }

    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            exit; // Agregado
        }

        $datos = $this->model->buscarPlantaPorId((int)$_GET['id']);
        if ($datos && isset($datos['id_planta'])) {
            $this->view->show('editarPlantaView.php', ['planta' => $datos]);
        } else {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=plantas&status=no_encontrado');
            exit; // Agregado
        }
    }

    public function editar()
    {
        $id                = (int)trim($_POST['id_planta']);
        $nombre_cientifico = trim($_POST['nombre_cientifico']);
        $nombre_comun      = trim($_POST['nombre_comun']);
        $id_usuario        = $_SESSION['id_usuario'];

        if (empty($nombre_cientifico)) {
            header("Location: ?controlador=Planta&accion=mostrarEditar&id=$id&status=invalido");
            exit; // Agregado
        }

        $respuesta = $this->model->editarPlanta($id, $nombre_cientifico, $nombre_comun, $id_usuario);

        if (isset($respuesta['Exito']) && $respuesta['Exito'] == 1) {
            header('Location: ?controlador=Planta&accion=mostrarListar&status=editada_success');
            exit;
        } else {
            header("Location: ?controlador=Planta&accion=mostrarEditar&id=$id&status=error");
            exit; // Agregado
        }
    }

    public function inhabilitar()
    {
        $id         = (int)$_GET['id'];
        $id_usuario = $_SESSION['id_usuario'];

        $respuesta = $this->model->inhabilitarPlanta($id, $id_usuario);

        if (isset($respuesta['Exito']) && $respuesta['Exito'] == 1) {
            header('Location: ?controlador=Planta&accion=mostrarListar&status=inhabilitada_success');
            exit;
        } else {
            header("Location: ?controlador=Planta&accion=mostrarEditar&id=$id&status=con_especimenes");
            exit; // Agregado
        }
    }
}