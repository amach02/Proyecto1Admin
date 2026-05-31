<?php
require_once 'model/InventarioModel.php';
require_once 'libs/View.php';

class InventarioController {
    private $view;
    private $model;

    public function __construct() {
        $this->view = new View();
        $this->model = new InventarioModel();
    }

    // Carga la pantalla inicial con la lista de gabinetes básicos
    public function index() {
        $gabinetes = $this->model->obtenerGabinetes();
        $this->view->show('inventarioView.php', array('gabinetes' => $gabinetes));
    }

    // API AJAX: Retorna las gavetas de un gabinete
    public function cargarGavetas() {
        header('Content-Type: application/json');
        $id = isset($_GET['id_gabinete']) ? $_GET['id_gabinete'] : 0;
        $data = $this->model->obtenerGavetasPorGabinete($id);
        echo json_encode($data);
        exit;
    }

    // API AJAX: Retorna las cajas de una gaveta
    public function cargarCajas() {
        header('Content-Type: application/json');
        $id = isset($_GET['id_gaveta']) ? $_GET['id_gaveta'] : 0;
        $data = $this->model->obtenerCajasPorGaveta($id);
        echo json_encode($data);
        exit;
    }

    // API AJAX: Retorna los viales de una caja
    public function cargarViales() {
        header('Content-Type: application/json');
        $id = isset($_GET['id_caja']) ? $_GET['id_caja'] : 0;
        $data = $this->model->obtenerVialesPorCaja($id);
        echo json_encode($data);
        exit;
    }

    // Procesa el envío del formulario
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) session_start();
            
            $codigo_id    = isset($_POST['codigo_id']) ? $_POST['codigo_id'] : '';
            $localizacion = isset($_POST['localizacion']) ? $_POST['localizacion'] : null;
            $fecha        = isset($_POST['fecha']) ? $_POST['fecha'] : null;
            $estado       = isset($_POST['estado']) ? $_POST['estado'] : 'pendiente_identificacion';
            $id_especie   = isset($_POST['id_especie']) ? $_POST['id_especie'] : null;
            $id_vial      = isset($_POST['id_vial']) ? $_POST['id_vial'] : null;
            $id_usuario   = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1;

            $respuesta = $this->model->registrarEspecimenInfrastructura($codigo_id, $localizacion, $fecha, $estado, $id_especie, $id_vial, $id_usuario);
            $gabinetes = $this->model->obtenerGabinetes();
            
            // Usamos exactamente las columnas que manda tu SP
            if (isset($respuesta['Exito']) && $respuesta['Exito'] == 1) {
                $this->view->show('inventarioView.php', array('gabinetes' => $gabinetes, 'success' => $respuesta['Resultado']));
            } else {
                $this->view->show('inventarioView.php', array('gabinetes' => $gabinetes, 'error' => $respuesta['Resultado']));
            }
        }
    }
}
?>