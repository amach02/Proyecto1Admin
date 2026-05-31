<?php
require_once 'model/CajaModel.php';

class CajaController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new CajaModel();
    }

    public function mostrarListar()
    {
        $id_gaveta = isset($_GET['id_gaveta']) ? (int)$_GET['id_gaveta'] : 0;
        $cajas = $this->model->listarCajas($id_gaveta);
        $this->view->show('listarCajasView.php', ['cajas' => $cajas]);
    }

    public function mostrarRegistrar()
    {
        require_once 'model/GavetaModel.php';
        $gavetas = (new GavetaModel())->listarGavetas();
        $this->view->show('registrarCajaView.php', ['gavetas' => $gavetas]);
    }

    public function registrar()
    {
        $codigo    = trim($_POST['codigo']);
        $id_gaveta = trim($_POST['id_gaveta']);

        if (empty($codigo) || empty($id_gaveta)) {
            header('Location: ?controlador=Caja&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarCaja($codigo, $id_gaveta);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Caja&accion=mostrarListar&status=registrada_success');
        } else {
            header('Location: ?controlador=Caja&accion=mostrarRegistrar&status=error');
        }
    }
}
?>
