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
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarCaja($codigo, $id_gaveta);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=registrada_success');
        } else {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=error');
        }
    }

    public function mostrarEditar() {
        if (!isset($_GET['id'])) { header('Location: ?controlador=Index&accion=mostrar&status=error'); return; }
        $datos = $this->model->buscarCajaPorId($_GET['id']);
        if ($datos) {
            require_once 'model/GavetaModel.php';
            $gavetas = (new GavetaModel())->listarGavetas();
            $this->view->show('editarCajaView.php', ['caja' => $datos, 'gavetas' => $gavetas]);
        }
    }

    public function editar() {
        $id = trim($_POST['id_caja']); $codigo = trim($_POST['codigo']); $id_gaveta = trim($_POST['id_gaveta']);
        if ($this->model->editarCaja($id, $codigo, $id_gaveta)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=editado_success');
        } else { 
            header("Location: ?controlador=Caja&accion=mostrarEditar&id=$id&status=error"); 
        }
    }

    public function inhabilitar() {
        $id = $_GET['id'];
        $respuesta = $this->model->inhabilitarCaja($id);
        if ($respuesta['Exito'] == 1) { 
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=inhabilitado_success'); 
        } else { 
            header("Location: ?controlador=Caja&accion=mostrarEditar&id=$id&status=con_especimenes"); 
        }
    }
}
?>