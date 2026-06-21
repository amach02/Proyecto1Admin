<?php

require_once 'model/GabineteModel.php';
require_once 'model/GavetaModel.php';

class GabineteController
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
        $this->model = new GabineteModel();
    }

    public function mostrarEditar()
    {
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

        $gabinete = $this->model->buscarGabinetePorId($id);
        $gavetas = (new GavetaModel())->listarPorGabinete($id);

        if (!$gabinete) {
            header(
                'Location: ?controlador=Infraestructura'
                . '&accion=mostrar'
                . '&tab=gabinetes'
                . '&status=no_encontrado'
            );
            exit;
        }

        $this->view->show(
            'editarGabineteView.php',
            array(
                'gabinete' => $gabinete,
                'gavetas'  => $gavetas
            )
        );
    }

    public function registrar()
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
                    'Location: ?controlador=Gabinete'
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

            $respuesta = $this->model->registrarGabinete(
                $codigo,
                $descripcion,
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
                    . '&status=registrado_success'
                );
                exit;
            }

            $mensaje = is_array($respuesta)
                && isset($respuesta['Resultado'])
                ? $respuesta['Resultado']
                : 'No se pudo registrar el gabinete.';

            throw new Exception($mensaje);

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

    public function editar()
    {
        try {
            $id = isset($_POST['id_gabinete'])
                ? trim($_POST['id_gabinete'])
                : '';

            $codigo = isset($_POST['codigo'])
                ? trim($_POST['codigo'])
                : '';

            $descripcion = isset($_POST['descripcion'])
                ? trim($_POST['descripcion'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($id) || empty($codigo)) {
                header(
                    'Location: ?controlador=Gabinete'
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

            $respuesta = $this->model->editarGabinete(
                $id,
                $codigo,
                $descripcion,
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
                    . '&status=gabinete_editado'
                );
                exit;
            }

            $mensaje = is_array($respuesta)
                && isset($respuesta['Resultado'])
                ? $respuesta['Resultado']
                : 'No se pudo editar el gabinete.';

            throw new Exception($mensaje);

        } catch (Exception $e) {
            die(
                '<strong>Error al editar el gabinete:</strong> '
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

            $respuesta = $this->model->inhabilitarGabinete(
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
                    . '&status=gabinete_inhabilitado'
                );
                exit;
            }

            header(
                'Location: ?controlador=Gabinete'
                . '&accion=mostrarEditar'
                . '&id=' . urlencode($id)
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

    public function mostrarListar()
    {
        $gabinetes = $this->model->listarGabinetes();

        $this->view->show(
            'listarGabinetesView.php',
            array(
                'gabinetes' => $gabinetes
            )
        );
    }

    public function mostrarRegistrar()
    {
        $this->view->show(
            'registrarGabineteView.php',
            array()
        );
    }
}
?>