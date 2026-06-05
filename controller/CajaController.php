<?php
require_once 'model/CajaModel.php';
require_once 'model/VialModel.php'; // Agregamos Vial para listar sus dependientes

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
        $cajas = $this->model->listarCajas();
        $this->view->show('listarCajasView.php', array('cajas' => $cajas));
    }

    public function mostrarRegistrar()
    {
        $this->view->show('registrarCajaView.php', array());
    }

    public function registrar()
    {
        $codigo = trim($_POST['codigo']);

        if (empty($codigo)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarCaja($codigo);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=registrada_success');
        } else {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=error');
        }
    }

    public function mostrarEditar() 
    {
        if (!isset($_GET['id'])) { 
            header('Location: ?controlador=Index&accion=mostrar&status=error'); 
            return; 
        }
        
        $datos = $this->model->buscarCajaPorId($_GET['id']);
        
        if ($datos) {
            // Traemos los viales que pertenecen a esta caja
            $viales = (new VialModel())->listarPorCaja($_GET['id']);
            if (!$viales) {
                $viales = array();
            }
            
            $this->view->show('editarCajaView.php', array('caja' => $datos, 'viales' => $viales));
        } else {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=error');
        }
    }

    public function editar() 
    {
        $id     = trim($_POST['id_caja']); 
        $codigo = trim($_POST['codigo']); 
        
        if (empty($id) || empty($codigo)) {
            header("Location: ?controlador=Caja&accion=mostrarEditar&id=$id&status=invalido"); 
            return;
        }

        if ($this->model->editarCaja($id, $codigo)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=editado_success');
        } else { 
            header("Location: ?controlador=Caja&accion=mostrarEditar&id=$id&status=error"); 
        }
    }

    public function inhabilitar() 
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

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