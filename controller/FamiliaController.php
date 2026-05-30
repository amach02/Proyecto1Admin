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
            header('Location: ?controlador=Index&accion=mostrar&status=familia_editada');
        } else {
            header("Location: ?controlador=Familia&accion=mostrarEditar&id=$id&status=error");
        }
    }
}
?>