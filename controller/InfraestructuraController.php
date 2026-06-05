<?php
require_once 'model/GabineteModel.php';
require_once 'model/GavetaModel.php';
require_once 'model/CajaModel.php';
require_once 'model/VialModel.php';

class InfraestructuraController
{
    private $view;

    public function __construct() {
        require_once 'libs/View.php';
        $this->view = new View();
    }

    public function mostrar()
    {
        $gabinetes = (new GabineteModel())->listarGabinetes() ?: array();
        $cajas     = (new CajaModel())->listarCajas() ?: array();
        
        $gavetaModel = new GavetaModel();
        $vialModel   = new VialModel();

        // Recorremos cada gabinete y le metemos sus gavetas adentro
        foreach ($gabinetes as $key => $gab) {
            $gabinetes[$key]['gavetas'] = $gavetaModel->listarPorGabinete($gab['id_gabinete']) ?: array();
        }

        // Recorremos cada caja y le metemos sus viales adentro
        foreach ($cajas as $key => $caja) {
            $cajas[$key]['viales'] = $vialModel->listarPorCaja($caja['id_caja']) ?: array();
        }

        $this->view->show('infraestructuraView.php', array(
            'gabinetes' => $gabinetes,
            'cajas'     => $cajas
        ));
    }
    public function registrarGabinete()
    {
        $codigo      = trim($_POST['codigo']);
        $descripcion = trim($_POST['descripcion']);
        
        if (empty($codigo)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=gabinetes&status=invalido');
            return;
        }
        
        $r = (new GabineteModel())->registrarGabinete($codigo, $descripcion);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Infraestructura&accion=mostrar&tab=gabinetes&status=$destino");
    }

    public function registrarGaveta()
    {
        $codigo      = trim($_POST['codigo']);
        $id_gabinete = trim($_POST['id_gabinete']); // La gaveta pertenece al gabinete
        
        if (empty($codigo) || empty($id_gabinete)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=gavetas&status=invalido');
            return;
        }
        
        $r = (new GavetaModel())->registrarGaveta($codigo, $id_gabinete);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Infraestructura&accion=mostrar&tab=gavetas&status=$destino");
    }

    public function registrarCaja()
    {
        $codigo = trim($_POST['codigo']);
        
        // La caja es el inicio de la Ruta Líquida, por lo que no pide id_gaveta ni id_gabinete
        if (empty($codigo)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=invalido');
            return;
        }
        
        // Llamamos al modelo solo con el código
        $r = (new CajaModel())->registrarCaja($codigo);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=$destino");
    }

    public function registrarVial()
    {
        $codigo  = trim($_POST['codigo']);
        $id_caja = trim($_POST['id_caja']); // El vial pertenece a la caja
        
        if (empty($codigo) || empty($id_caja)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=viales&status=invalido');
            return;
        }
        
        $r = (new VialModel())->registrarVial($codigo, $id_caja);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Infraestructura&accion=mostrar&tab=viales&status=$destino");
    }

    public function inhabilitarGabinete()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&status=error');
            return;
        }
        
        $id = $_GET['id'];
        $r  = (new GabineteModel())->inhabilitarGabinete($id);
        
        if ($r['Exito'] == 1) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=gabinetes&status=inhabilitado_success');
        } else {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=gabinetes&status=con_especimenes');
        }
    }
}
?>