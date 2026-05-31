<?php
require_once 'model/OrdenModel.php';

class OrdenController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new OrdenModel();
    }

    public function mostrarListar()
    {
        $ordenes = $this->model->listarOrdenes();
        $this->view->show('listarOrdenesView.php', ['ordenes' => $ordenes]);
    }

    public function mostrarRegistrar()
    {
        $this->view->show('registrarOrdenView.php', []);
    }

    public function registrar()
    {
        $nombre = trim($_POST['nombre']);

        if (empty($nombre)) {
            header('Location: ?controlador=Orden&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarOrden($nombre);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Orden&accion=mostrarListar&status=registrado_success');
        } else {
            header('Location: ?controlador=Orden&accion=mostrarRegistrar&status=error');
        }
    }
}
?>
