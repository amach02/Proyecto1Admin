<?php
require_once 'model/VialModel.php';

class VialController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new VialModel();
    }

    public function mostrarListar()
    {
        $viales = $this->model->listarVialesDisponibles();
        $this->view->show('listarVialesView.php', ['viales' => $viales]);
    }

    public function mostrarRegistrar()
    {
        require_once 'model/CajaModel.php';
        $cajas = (new CajaModel())->listarCajas();
        $this->view->show('registrarVialView.php', ['cajas' => $cajas]);
    }

    public function registrar()
    {
        $codigo   = trim($_POST['codigo']);
        $id_caja  = trim($_POST['id_caja']);

        if (empty($codigo) || empty($id_caja)) {
            header('Location: ?controlador=Vial&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarVial($codigo, $id_caja);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Vial&accion=mostrarListar&status=registrado_success');
        } else {
            header('Location: ?controlador=Vial&accion=mostrarRegistrar&status=error');
        }
    }
}
?>
