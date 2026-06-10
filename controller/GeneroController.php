<?php

require_once 'model/GeneroModel.php';

class GeneroController
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
        $this->model = new GeneroModel();
    }

    public function mostrarListar()
    {
        $id_familia = isset($_GET['id_familia'])
            ? (int) $_GET['id_familia']
            : 0;

        $generos = $this->model->listarGeneros($id_familia);

        $this->view->show(
            'listarGenerosView.php',
            array(
                'generos' => $generos
            )
        );
    }

    public function mostrarRegistrar()
    {
        require_once 'model/FamiliaModel.php';

        $familias = (new FamiliaModel())->listarFamilias();

        $this->view->show(
            'registrarGeneroView.php',
            array(
                'familias' => $familias
            )
        );
    }

    public function registrar()
    {
        try {
            $nombre = isset($_POST['nombre'])
                ? trim($_POST['nombre'])
                : '';

            $id_familia = isset($_POST['id_familia'])
                ? trim($_POST['id_familia'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($nombre) || empty($id_familia)) {
                header(
                    'Location: ?controlador=Genero'
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

            $respuesta = $this->model->registrarGenero(
                $nombre,
                $id_familia,
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
                    . '&tab=generos'
                    . '&status=registrado_success'
                );
                exit;
            }

            $mensaje = 'No se pudo registrar el género.';

            if (
                is_array($respuesta)
                && isset($respuesta['Resultado'])
            ) {
                $mensaje = $respuesta['Resultado'];
            }

            throw new Exception($mensaje);

        } catch (Exception $e) {
            die(
                '<strong>Error al registrar el género:</strong> '
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
            $id = isset($_POST['id_genero'])
                ? trim($_POST['id_genero'])
                : '';

            $nombre = isset($_POST['nombre'])
                ? trim($_POST['nombre'])
                : '';

            $id_familia = isset($_POST['id_familia'])
                ? trim($_POST['id_familia'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (
                empty($id)
                || empty($nombre)
                || empty($id_familia)
            ) {
                header(
                    'Location: ?controlador=Genero'
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

            $respuesta = $this->model->editarGenero(
                $id,
                $nombre,
                $id_familia,
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
                    . '&tab=generos'
                    . '&status=editado_success'
                );
                exit;
            }

            $mensaje = 'No se pudo editar el género.';

            if (
                is_array($respuesta)
                && isset($respuesta['Resultado'])
            ) {
                $mensaje = $respuesta['Resultado'];
            }

            throw new Exception($mensaje);

        } catch (Exception $e) {
            die(
                '<strong>Error al editar el género:</strong> '
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
                'Location: ?controlador=Index'
                . '&accion=mostrar'
                . '&status=error'
            );
            exit;
        }

        $id = $_GET['id'];

        $datos = $this->model->buscarGeneroPorId($id);

        if (!$datos) {
            header(
                'Location: ?controlador=Taxonomia'
                . '&accion=mostrar'
                . '&tab=generos'
                . '&status=no_encontrado'
            );
            exit;
        }

        require_once 'model/FamiliaModel.php';

        $familias = (new FamiliaModel())->listarFamilias();

        $this->view->show(
            'editarGeneroView.php',
            array(
                'genero' => $datos,
                'familias' => $familias
            )
        );
    }
}
?>