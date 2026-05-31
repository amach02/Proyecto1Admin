<?php
require_once 'model/GavetaModel.php';

class GavetaController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new GavetaModel();
    }

    public function mostrarListar()
    {
        $id_gabinete = isset($_GET['id_gabinete']) ? (int)$_GET['id_gabinete'] : 0;
        $gavetas = $this->model->listarGavetas($id_gabinete);
        $this->view->show('listarGavetasView.php', ['gavetas' => $gavetas]);
    }

    public function mostrarRegistrar()
    {
        require_once 'model/GabineteModel.php';
        $gabinetes = (new GabineteModel())->listarGabinetes();
        $this->view->show('registrarGavetaView.php', ['gabinetes' => $gabinetes]);
    }

    public function registrar()
    {
        $codigo      = trim($_POST['codigo']);
        $id_gabinete = trim($_POST['id_gabinete']);

        if (empty($codigo) || empty($id_gabinete)) {
            header('Location: ?controlador=Gaveta&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarGaveta($codigo, $id_gabinete);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Gaveta&accion=mostrarListar&status=registrada_success');
        } else {
            header('Location: ?controlador=Gaveta&accion=mostrarRegistrar&status=error');
        }
    }
}
?>
