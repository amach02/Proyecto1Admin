<?php
require_once 'model/PlantaModel.php';

class PlantaController
{
    private $view;
    private $model;

    public function __construct()
    {
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
        $nombre     = trim($_POST['nombre']);
        $id_usuario = $_SESSION['id_usuario'];

        if (empty($nombre)) {
            header('Location: ?controlador=Planta&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarPlanta($nombre, $id_usuario);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=plantas&status=registrada_success');
        } else {
            header('Location: ?controlador=Planta&accion=mostrarRegistrar&status=error');
        }
    }

    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

        $datos = $this->model->buscarPlantaPorId((int)$_GET['id']);
        if ($datos && isset($datos['id_planta'])) {
            $this->view->show('editarPlantaView.php', ['planta' => $datos]);
        } else {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=plantas&status=no_encontrado');
        }
    }

    public function editar()
    {
        $id         = (int)trim($_POST['id_planta']);
        $nombre     = trim($_POST['nombre']);
        $id_usuario = $_SESSION['id_usuario'];

        if (empty($nombre)) {
            header("Location: ?controlador=Planta&accion=mostrarEditar&id=$id&status=invalido");
            return;
        }

        $respuesta = $this->model->editarPlanta($id, $nombre, $id_usuario);

        if (isset($respuesta['Exito']) && $respuesta['Exito'] == 1) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=plantas&status=editada_success');
        } else {
            header("Location: ?controlador=Planta&accion=mostrarEditar&id=$id&status=error");
        }
    }

    public function inhabilitar()
    {
        $id         = (int)$_GET['id'];
        $id_usuario = $_SESSION['id_usuario'];

        $respuesta = $this->model->inhabilitarPlanta($id, $id_usuario);

        if (isset($respuesta['Exito']) && $respuesta['Exito'] == 1) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=plantas&status=inhabilitada_success');
        } else {
            header("Location: ?controlador=Planta&accion=mostrarEditar&id=$id&status=con_especimenes");
        }
    }
}
?>
