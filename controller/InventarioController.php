<?php

require_once 'model/InventarioModel.php';
require_once 'libs/View.php';

class InventarioController
{
    private $view;
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->view = new View();
        $this->model = new InventarioModel();
    }

    public function index()
    {
        $gabinetes = $this->model->obtenerGabinetes();
        $cajas = $this->model->obtenerCajas();

        $this->view->show(
            'inventarioView.php',
            array(
                'gabinetes' => $gabinetes,
                'cajas' => $cajas
            )
        );
    }

    // Devuelve las gavetas de un gabinete mediante AJAX
    public function cargarGavetas()
    {
        header('Content-Type: application/json; charset=UTF-8');

        $id_gabinete = isset($_GET['id_gabinete'])
            ? (int) $_GET['id_gabinete']
            : 0;

        $datos = $this->model->obtenerGavetasPorGabinete(
            $id_gabinete
        );

        echo json_encode($datos);
        exit;
    }

    // Devuelve las cajas activas mediante AJAX
    public function cargarCajas()
    {
        header('Content-Type: application/json; charset=UTF-8');

        $datos = $this->model->obtenerCajas();

        echo json_encode($datos);
        exit;
    }

    // Devuelve los viales de una caja mediante AJAX
    public function cargarViales()
    {
        header('Content-Type: application/json; charset=UTF-8');

        $id_caja = isset($_GET['id_caja'])
            ? (int) $_GET['id_caja']
            : 0;

        $datos = $this->model->obtenerVialesPorCaja(
            $id_caja
        );

        echo json_encode($datos);
        exit;
    }

    public function guardar()
    {
        if (
            !isset($_SERVER['REQUEST_METHOD'])
            || $_SERVER['REQUEST_METHOD'] !== 'POST'
        ) {
            header(
                'Location: ?controlador=Inventario&accion=index'
            );
            exit;
        }

        try {
            $codigo_id = isset($_POST['codigo_id'])
                ? trim($_POST['codigo_id'])
                : '';

            $localizacion = isset($_POST['localizacion'])
                ? trim($_POST['localizacion'])
                : null;

            $fecha = !empty($_POST['fecha'])
                ? $_POST['fecha']
                : null;

            $estado = isset($_POST['estado'])
                ? $_POST['estado']
                : 'pendiente_identificacion';

            $id_especie = !empty($_POST['id_especie'])
                ? $_POST['id_especie']
                : null;

            $id_vial = !empty($_POST['id_vial'])
                ? $_POST['id_vial']
                : null;

            $id_gaveta = !empty($_POST['id_gaveta'])
                ? $_POST['id_gaveta']
                : null;

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($codigo_id)) {
                throw new Exception(
                    'Debe ingresar el código del espécimen.'
                );
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            /*
             * Debe seleccionarse una gaveta o un vial,
             * pero no ambos al mismo tiempo.
             */
            if (empty($id_gaveta) && empty($id_vial)) {
                throw new Exception(
                    'Debe seleccionar una gaveta o un vial.'
                );
            }

            if (!empty($id_gaveta) && !empty($id_vial)) {
                throw new Exception(
                    'Seleccione solamente una gaveta o un vial.'
                );
            }

            $respuesta =
                $this->model->registrarEspecimenInfraestructura(
                    $codigo_id,
                    $localizacion,
                    $fecha,
                    $estado,
                    $id_especie,
                    $id_vial,
                    $id_gaveta,
                    $id_usuario_accion
                );

            $gabinetes = $this->model->obtenerGabinetes();
            $cajas = $this->model->obtenerCajas();

            if (
                is_array($respuesta)
                && isset($respuesta['Exito'])
                && (int) $respuesta['Exito'] === 1
            ) {
                $mensaje = isset($respuesta['Resultado'])
                    ? $respuesta['Resultado']
                    : 'Espécimen registrado correctamente.';

                $this->view->show(
                    'inventarioView.php',
                    array(
                        'gabinetes' => $gabinetes,
                        'cajas' => $cajas,
                        'success' => $mensaje
                    )
                );

                return;
            }

            $mensaje = (
                is_array($respuesta)
                && isset($respuesta['Resultado'])
            )
                ? $respuesta['Resultado']
                : 'No se pudo registrar el espécimen.';

            $this->view->show(
                'inventarioView.php',
                array(
                    'gabinetes' => $gabinetes,
                    'cajas' => $cajas,
                    'error' => $mensaje
                )
            );

        } catch (Exception $e) {
            $gabinetes = $this->model->obtenerGabinetes();
            $cajas = $this->model->obtenerCajas();

            $this->view->show(
                'inventarioView.php',
                array(
                    'gabinetes' => $gabinetes,
                    'cajas' => $cajas,
                    'error' => $e->getMessage()
                )
            );
        }
    }
}
?>