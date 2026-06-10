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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once 'libs/View.php';
        $this->view = new View();
    }

    public function mostrar()
    {
        $gabinetes = (new GabineteModel())->listarGabinetes();

        if (!$gabinetes) {
            $gabinetes = array();
        }

        $cajas = (new CajaModel())->listarCajas();

        if (!$cajas) {
            $cajas = array();
        }

        $gavetaModel = new GavetaModel();
        $vialModel = new VialModel();

        foreach ($gabinetes as $key => $gabinete) {
            $gavetas = $gavetaModel->listarPorGabinete(
                $gabinete['id_gabinete']
            );

            $gabinetes[$key]['gavetas'] = $gavetas
                ? $gavetas
                : array();
        }

        foreach ($cajas as $key => $caja) {
            $viales = $vialModel->listarPorCaja(
                $caja['id_caja']
            );

            $cajas[$key]['viales'] = $viales
                ? $viales
                : array();
        }

        $this->view->show(
            'infraestructuraView.php',
            array(
                'gabinetes' => $gabinetes,
                'cajas' => $cajas
            )
        );
    }

    public function registrarGabinete()
    {
        try {
            $codigo = isset($_POST['codigo'])
                ? trim($_POST['codigo'])
                : '';

            $descripcion = isset($_POST['descripcion'])
                ? trim($_POST['descripcion'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($codigo)) {
                header(
                    'Location: ?controlador=Infraestructura'
                    . '&accion=mostrar'
                    . '&tab=gabinetes'
                    . '&status=invalido'
                );
                exit;
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            $respuesta = (new GabineteModel())->registrarGabinete(
                $codigo,
                $descripcion,
                $id_usuario_accion
            );

            $estado = (
                is_array($respuesta)
                && isset($respuesta['Exito'])
                && (int) $respuesta['Exito'] === 1
            )
                ? 'registrado_success'
                : 'error';

            header(
                'Location: ?controlador=Infraestructura'
                . '&accion=mostrar'
                . '&tab=gabinetes'
                . '&status=' . $estado
            );
            exit;

        } catch (Exception $e) {
            die(
                '<strong>Error al registrar el gabinete:</strong> '
                . htmlspecialchars(
                    $e->getMessage(),
                    ENT_QUOTES,
                    'UTF-8'
                )
            );
        }
    }

    public function registrarGaveta()
    {
        try {
            $codigo = isset($_POST['codigo'])
                ? trim($_POST['codigo'])
                : '';

            $id_gabinete = isset($_POST['id_gabinete'])
                ? trim($_POST['id_gabinete'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($codigo) || empty($id_gabinete)) {
                header(
                    'Location: ?controlador=Infraestructura'
                    . '&accion=mostrar'
                    . '&tab=gavetas'
                    . '&status=invalido'
                );
                exit;
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            $respuesta = (new GavetaModel())->registrarGaveta(
                $codigo,
                $id_gabinete,
                $id_usuario_accion
            );

            $estado = (
                is_array($respuesta)
                && isset($respuesta['Exito'])
                && (int) $respuesta['Exito'] === 1
            )
                ? 'registrado_success'
                : 'error';

            header(
                'Location: ?controlador=Infraestructura'
                . '&accion=mostrar'
                . '&tab=gavetas'
                . '&status=' . $estado
            );
            exit;

        } catch (Exception $e) {
            die(
                '<strong>Error al registrar la gaveta:</strong> '
                . htmlspecialchars(
                    $e->getMessage(),
                    ENT_QUOTES,
                    'UTF-8'
                )
            );
        }
    }

    public function registrarCaja()
    {
        try {
            $codigo = isset($_POST['codigo'])
                ? trim($_POST['codigo'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($codigo)) {
                header(
                    'Location: ?controlador=Infraestructura'
                    . '&accion=mostrar'
                    . '&tab=cajas'
                    . '&status=invalido'
                );
                exit;
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            /*
             * CajaModel debe recibir el usuario como segundo parámetro.
             */
            $respuesta = (new CajaModel())->registrarCaja(
                $codigo,
                $id_usuario_accion
            );

            $estado = (
                is_array($respuesta)
                && isset($respuesta['Exito'])
                && (int) $respuesta['Exito'] === 1
            )
                ? 'registrado_success'
                : 'error';

            header(
                'Location: ?controlador=Infraestructura'
                . '&accion=mostrar'
                . '&tab=cajas'
                . '&status=' . $estado
            );
            exit;

        } catch (Exception $e) {
            die(
                '<strong>Error al registrar la caja:</strong> '
                . htmlspecialchars(
                    $e->getMessage(),
                    ENT_QUOTES,
                    'UTF-8'
                )
            );
        }
    }

    public function registrarVial()
    {
        try {
            $codigo = isset($_POST['codigo'])
                ? trim($_POST['codigo'])
                : '';

            $id_caja = isset($_POST['id_caja'])
                ? trim($_POST['id_caja'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($codigo) || empty($id_caja)) {
                header(
                    'Location: ?controlador=Infraestructura'
                    . '&accion=mostrar'
                    . '&tab=viales'
                    . '&status=invalido'
                );
                exit;
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            /*
             * VialModel debe recibir el usuario como tercer parámetro.
             */
            $respuesta = (new VialModel())->registrarVial(
                $codigo,
                $id_caja,
                $id_usuario_accion
            );

            $estado = (
                is_array($respuesta)
                && isset($respuesta['Exito'])
                && (int) $respuesta['Exito'] === 1
            )
                ? 'registrado_success'
                : 'error';

            header(
                'Location: ?controlador=Infraestructura'
                . '&accion=mostrar'
                . '&tab=viales'
                . '&status=' . $estado
            );
            exit;

        } catch (Exception $e) {
            die(
                '<strong>Error al registrar el vial:</strong> '
                . htmlspecialchars(
                    $e->getMessage(),
                    ENT_QUOTES,
                    'UTF-8'
                )
            );
        }
    }

    public function inhabilitarGabinete()
    {
        try {
            if (!isset($_GET['id'])) {
                header(
                    'Location: ?controlador=Infraestructura'
                    . '&accion=mostrar'
                    . '&tab=gabinetes'
                    . '&status=error'
                );
                exit;
            }

            $id = $_GET['id'];

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            $respuesta = (new GabineteModel())->inhabilitarGabinete(
                $id,
                $id_usuario_accion
            );

            if (
                is_array($respuesta)
                && isset($respuesta['Exito'])
                && (int) $respuesta['Exito'] === 1
            ) {
                header(
                    'Location: ?controlador=Infraestructura'
                    . '&accion=mostrar'
                    . '&tab=gabinetes'
                    . '&status=inhabilitado_success'
                );
                exit;
            }

            header(
                'Location: ?controlador=Infraestructura'
                . '&accion=mostrar'
                . '&tab=gabinetes'
                . '&status=con_especimenes'
            );
            exit;

        } catch (Exception $e) {
            die(
                '<strong>Error al inhabilitar el gabinete:</strong> '
                . htmlspecialchars(
                    $e->getMessage(),
                    ENT_QUOTES,
                    'UTF-8'
                )
            );
        }
    }
}
?>