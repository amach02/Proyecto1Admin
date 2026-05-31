<?php
require_once 'model/GabineteModel.php';
require_once 'model/GavetaModel.php';
require_once 'model/CajaModel.php';
require_once 'model/VialModel.php';

class InfraestructuraController
{
    private $view;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
    }

    public function mostrar()
    {
        $gabinetes = (new GabineteModel())->listarGabinetes();
        $gavetas   = (new GavetaModel())->listarGavetas();
        $cajas     = (new CajaModel())->listarCajas();
        $viales    = (new VialModel())->listarVialesDisponibles();
        $this->view->show('infraestructuraView.php', [
            'gabinetes' => $gabinetes,
            'gavetas'   => $gavetas,
            'cajas'     => $cajas,
            'viales'    => $viales
        ]);
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
        $id_gabinete = trim($_POST['id_gabinete']);
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
        $codigo    = trim($_POST['codigo']);
        $id_gaveta = trim($_POST['id_gaveta']);
        if (empty($codigo) || empty($id_gaveta)) {
            header('Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=invalido');
            return;
        }
        $r = (new CajaModel())->registrarCaja($codigo, $id_gaveta);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Infraestructura&accion=mostrar&tab=cajas&status=$destino");
    }

    public function registrarVial()
    {
        $codigo  = trim($_POST['codigo']);
        $id_caja = trim($_POST['id_caja']);
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
