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
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=ordenes&status=registrado_success');
        } else {
            header('Location: ?controlador=Orden&accion=mostrarRegistrar&status=error');
        }
    }

    // Se agregan las funciones de edición que faltaban
    public function mostrarEditar() {
        if (!isset($_GET['id'])) { header('Location: ?controlador=Index&accion=mostrar&status=error'); return; }
        $datos = $this->model->buscarOrdenPorId($_GET['id']);
        if ($datos) {
            $this->view->show('editarOrdenView.php', ['orden' => $datos]);
        }
    }

    public function editar() {
        $id = trim($_POST['id_orden']); $nombre = trim($_POST['nombre']);
        if ($this->model->editarOrden($id, $nombre)) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=ordenes&status=editado_success');
        } else { 
            header("Location: ?controlador=Orden&accion=mostrarEditar&id=$id&status=error"); 
        }
    }
}
?>
