<?php

require_once 'model/FamiliaModel.php';

class FamiliaController
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
        $this->model = new FamiliaModel();
    }

    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header(
                'Location: ?controlador=Index'
                . '&accion=mostrar'
                . '&status=error'
            );
            exit;
        }

        $id = $_GET['id'];

        $datosFamilia = $this->model->buscarFamiliaPorId($id);

        if ($datosFamilia) {
            /*
             * La vista de edición posiblemente necesita la lista
             * de órdenes para cambiar la relación.
             */
            require_once 'model/OrdenModel.php';

            $ordenes = (new OrdenModel())->listarOrdenes();

            $this->view->show(
                'editarFamiliaView.php',
                array(
                    'familia' => $datosFamilia,
                    'ordenes' => $ordenes
                )
            );

            return;
        }

        header(
            'Location: ?controlador=Index'
            . '&accion=mostrar'
            . '&status=no_encontrado'
        );
        exit;
    }

    public function registrar()
    {
        try {
            $nombre = isset($_POST['nombre'])
                ? trim($_POST['nombre'])
                : '';

            $id_orden = isset($_POST['id_orden'])
                ? trim($_POST['id_orden'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($nombre) || empty($id_orden)) {
                header(
                    'Location: ?controlador=Familia'
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

            /*
             * Se agrega el usuario como tercer parámetro.
             */
            $respuesta = $this->model->registrarFamilia(
                $nombre,
                $id_orden,
                $id_usuario_accion
            );

            if (
                is_array($respuesta)
                && isset($respuesta['Exito'])
                && (int) $respuesta['Exito'] === 1
            ) {
                header(
                    'Location: ?controlador=Taxonomia'
                    . '&accion=mostrar'
                    . '&tab=familias'
                    . '&status=registrada_success'
                );
                exit;
            }

            $mensaje = is_array($respuesta)
                && isset($respuesta['Resultado'])
                ? $respuesta['Resultado']
                : 'No se pudo registrar la familia.';

            throw new Exception($mensaje);

        } catch (Exception $e) {
            die(
                '<strong>Error al registrar la familia:</strong> '
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
            $id = isset($_POST['id_familia'])
                ? trim($_POST['id_familia'])
                : '';

            $nombre = isset($_POST['nombre'])
                ? trim($_POST['nombre'])
                : '';

            $id_orden = isset($_POST['id_orden'])
                ? trim($_POST['id_orden'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (
                empty($id)
                || empty($nombre)
                || empty($id_orden)
            ) {
                header(
                    'Location: ?controlador=Familia'
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

            /*
             * Se agrega el usuario como cuarto parámetro.
             */
            $respuesta = $this->model->editarFamilia(
                $id,
                $nombre,
                $id_orden,
                $id_usuario_accion
            );

            /*
             * El procedimiento debería devolver Exito y Resultado.
             * También se mantiene compatibilidad si el modelo devuelve booleano.
             */
            $exito = false;

            if (is_array($respuesta)) {
                $exito = isset($respuesta['Exito'])
                    && (int) $respuesta['Exito'] === 1;
            } else {
                $exito = (bool) $respuesta;
            }

            if ($exito) {
                header(
                    'Location: ?controlador=Taxonomia'
                    . '&accion=mostrar'
                    . '&tab=familias'
                    . '&status=familia_editada'
                );
                exit;
            }

            $mensaje = is_array($respuesta)
                && isset($respuesta['Resultado'])
                ? $respuesta['Resultado']
                : 'No se pudo editar la familia.';

            throw new Exception($mensaje);

        } catch (Exception $e) {
            die(
                '<strong>Error al editar la familia:</strong> '
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
        $id_orden = isset($_GET['id_orden'])
            ? (int) $_GET['id_orden']
            : 0;

        $familias = $this->model->listarFamilias($id_orden);

        $this->view->show(
            'listarFamiliasView.php',
            array(
                'familias' => $familias
            )
        );
    }

    public function mostrarRegistrar()
    {
        require_once 'model/OrdenModel.php';

        $ordenes = (new OrdenModel())->listarOrdenes();

        $this->view->show(
            'registrarFamiliaView.php',
            array(
                'ordenes' => $ordenes
            )
        );
    }
}
?>