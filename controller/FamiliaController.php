<?php
require_once 'model/FamiliaModel.php';

class FamiliaController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new FamiliaModel();
    }

    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

        $id = $_GET['id'];
        $datosFamilia = $this->model->buscarFamiliaPorId($id);

        if ($datosFamilia) {
            $this->view->show('editarFamiliaView.php', ['familia' => $datosFamilia]);
        } else {
            header('Location: ?controlador=Index&accion=mostrar&status=no_encontrado');
        }
    }

    public function editar()
    {
        $id = trim($_POST['id_familia']);
        $nombre = trim($_POST['nombre']);
        $id_orden = trim($_POST['id_orden']);

        if (empty($id) || empty($nombre) || empty($id_orden)) {
            header("Location: ?controlador=Familia&accion=mostrarEditar&id=$id&status=invalido");
            return;
        }

        $resultado = $this->model->editarFamilia($id, $nombre, $id_orden);

        if ($resultado) {
            header('Location: ?controlador=Familia&accion=mostrarListar&status=familia_editada');
        } else {
            header("Location: ?controlador=Familia&accion=mostrarEditar&id=$id&status=error");
        }
    }

    public function mostrarListar()
    {
        $id_orden = isset($_GET['id_orden']) ? (int)$_GET['id_orden'] : 0;
        $familias = $this->model->listarFamilias($id_orden);
        $this->view->show('listarFamiliasView.php', ['familias' => $familias]);
    }

    public function mostrarRegistrar()
    {
        require_once 'model/OrdenModel.php';
        $ordenes = (new OrdenModel())->listarOrdenes();
        $this->view->show('registrarFamiliaView.php', ['ordenes' => $ordenes]);
    }

    public function registrar()
    {
        $nombre   = trim($_POST['nombre']);
        $id_orden = trim($_POST['id_orden']);

        if (empty($nombre) || empty($id_orden)) {
            header('Location: ?controlador=Familia&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarFamilia($nombre, $id_orden);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Familia&accion=mostrarListar&status=registrada_success');
        } else {
            header('Location: ?controlador=Familia&accion=mostrarRegistrar&status=error');
        }
    }
}
?>