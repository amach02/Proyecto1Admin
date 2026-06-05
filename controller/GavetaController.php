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
        $this->view->show('listarGavetasView.php', array('gavetas' => $gavetas));
    }

    public function mostrarRegistrar()
    {
        require_once 'model/GabineteModel.php';
        $gabinetes = (new GabineteModel())->listarGabinetes();
        $this->view->show('registrarGavetaView.php', array('gabinetes' => $gabinetes));
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
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=gavetas&status=registrada_success');
        } else {
            header('Location: ?controlador=Gaveta&accion=mostrarRegistrar&status=error');
        }
    }

    public function editar() 
    {
        $id          = trim($_POST['id_gaveta']); 
        $codigo      = trim($_POST['codigo']); 
        $id_gabinete = trim($_POST['id_gabinete']);
        
        if ($this->model->editarGaveta($id, $codigo, $id_gabinete)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=gavetas&status=editado_success');
        } else { 
            header("Location: ?controlador=Gaveta&accion=mostrarEditar&id=$id&status=error"); 
        }
    }

    public function inhabilitar() 
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }
        
        $id = $_GET['id'];
        $respuesta = $this->model->inhabilitarGaveta($id);
        
        if ($respuesta['Exito'] == 1) { 
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=gavetas&status=inhabilitado_success'); 
        } else { 
            header("Location: ?controlador=Gaveta&accion=mostrarEditar&id=$id&status=con_especimenes"); 
        }
    }

    public function mostrarEditar() 
    {
        if (!isset($_GET['id'])) { 
            header('Location: ?controlador=Index&accion=mostrar&status=error'); 
            return; 
        }
        
        $datos = $this->model->buscarGavetaPorId($_GET['id']);
        
        if ($datos) {
            require_once 'model/GabineteModel.php';
            $gabinetes = (new GabineteModel())->listarGabinetes();
            $this->view->show('editarGavetaView.php', array('gaveta' => $datos, 'gabinetes' => $gabinetes));
        } else {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=gavetas&status=error');
        }
    }
}
?>