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
        $codigo  = trim($_POST['codigo']);
        $id_caja = trim($_POST['id_caja']);

        if (empty($codigo) || empty($id_caja)) {
            header('Location: ?controlador=Vial&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarVial($codigo, $id_caja);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=viales&status=registrado_success');
        } else {
            header('Location: ?controlador=Vial&accion=mostrarRegistrar&status=error');
        }
    }

    public function editar() {
        $id = trim($_POST['id_vial']); $codigo = trim($_POST['codigo']); $id_caja = trim($_POST['id_caja']);
        if ($this->model->editarVial($id, $codigo, $id_caja)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=viales&status=editado_success');
        } else { 
            header("Location: ?controlador=Vial&accion=mostrarEditar&id=$id&status=error"); 
        }
    }

    public function inhabilitar() {
        $id = $_GET['id'];
        $respuesta = $this->model->inhabilitarVial($id);
        if ($respuesta['Exito'] == 1) { 
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=viales&status=inhabilitado_success'); 
        } else { 
            header("Location: ?controlador=Vial&accion=mostrarEditar&id=$id&status=ocupado"); 
        }
    }

    public function mostrarEditar() {
        if (!isset($_GET['id'])) { header('Location: ?controlador=Index&accion=mostrar&status=error'); return; }
        $datos = $this->model->buscarVialPorId($_GET['id']);
        if ($datos) {
            require_once 'model/CajaModel.php';
            $cajas = (new CajaModel())->listarCajas();
            $this->view->show('editarVialView.php', ['vial' => $datos, 'cajas' => $cajas]);
        }
    }
}
?>
