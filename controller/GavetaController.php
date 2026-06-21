<?php

require_once 'model/GavetaModel.php';

class GavetaController
{
    private $view;
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once 'libs/View.php';

        $this->view = new View();
        $this->model = new GavetaModel();
    }

    public function mostrarListar()
    {
        $id_gabinete = isset($_GET['id_gabinete'])
            ? (int) $_GET['id_gabinete']
            : 0;

        $gavetas = $this->model->listarGavetas($id_gabinete);

        $this->view->show(
            'listarGavetasView.php',
            array(
                'gavetas' => $gavetas
            )
        );
    }

    public function mostrarRegistrar()
    {
        require_once 'model/GabineteModel.php';

        $gabinetes = (new GabineteModel())->listarGabinetes();

        $this->view->show(
            'registrarGavetaView.php',
            array(
                'gabinetes' => $gabinetes
            )
        );
    }

    public function registrar()
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
                    'Location: ?controlador=Gaveta'
                    . '&accion=mostrarRegistrar'
                    . '&status=invalido'
                );
                exit;
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            $respuesta = $this->model->registrarGaveta(
                $codigo,
                $id_gabinete,
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
                    . '&tab=gavetas'
                    . '&status=registrada_success'
                );
                exit;
            }

            $mensaje = is_array($respuesta)
                && isset($respuesta['Resultado'])
                ? $respuesta['Resultado']
                : 'No se pudo registrar la gaveta.';

            throw new Exception($mensaje);

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

    public function editar()
    {
        try {
            $id = isset($_POST['id_gaveta'])
                ? trim($_POST['id_gaveta'])
                : '';

            $codigo = isset($_POST['codigo'])
                ? trim($_POST['codigo'])
                : '';

            $id_gabinete = isset($_POST['id_gabinete'])
                ? trim($_POST['id_gabinete'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (
                empty($id)
                || empty($codigo)
                || empty($id_gabinete)
            ) {
                header(
                    'Location: ?controlador=Gaveta'
                    . '&accion=mostrarEditar'
                    . '&id=' . urlencode($id)
                    . '&status=invalido'
                );
                exit;
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            $respuesta = $this->model->editarGaveta(
                $id,
                $codigo,
                $id_gabinete,
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
                    . '&tab=gavetas'
                    . '&status=editado_success'
                );
                exit;
            }

            $mensaje = is_array($respuesta)
                && isset($respuesta['Resultado'])
                ? $respuesta['Resultado']
                : 'No se pudo editar la gaveta.';

            throw new Exception($mensaje);

        } catch (Exception $e) {
            die(
                '<strong>Error al editar la gaveta:</strong> '
                . htmlspecialchars(
                    $e->getMessage(),
                    ENT_QUOTES,
                    'UTF-8'
                )
            );
        }
    }

    public function inhabilitar()
    {
        try {
            if (!isset($_GET['id'])) {
                header(
                    'Location: ?controlador=Infraestructura'
                    . '&accion=mostrar'
                    . '&tab=gavetas'
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

            $respuesta = $this->model->inhabilitarGaveta(
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
                    . '&tab=gavetas'
                    . '&status=inhabilitado_success'
                );
                exit;
            }

            header(
                'Location: ?controlador=Gaveta'
                . '&accion=mostrarEditar'
                . '&id=' . urlencode($id)
                . '&status=con_especimenes'
            );
            exit;

        } catch (Exception $e) {
            die(
                '<strong>Error al inhabilitar la gaveta:</strong> '
                . htmlspecialchars(
                    $e->getMessage(),
                    ENT_QUOTES,
                    'UTF-8'
                )
            );
        }
    }

    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header(
                'Location: ?controlador=Infraestructura'
                . '&accion=mostrar'
                . '&tab=gavetas'
                . '&status=error'
            );
            exit;
        }

        $datos = $this->model->buscarGavetaPorId($_GET['id']);

        if ($datos) {
            require_once 'model/GabineteModel.php';

            $gabinetes = (new GabineteModel())->listarGabinetes();

            $this->view->show(
                'editarGavetaView.php',
                array(
                    'gaveta' => $datos,
                    'gabinetes' => $gabinetes
                )
            );

            return;
        }

        header(
            'Location: ?controlador=Infraestructura'
            . '&accion=mostrar'
            . '&tab=gavetas'
            . '&status=error'
        );
        exit;
    }
}
?>