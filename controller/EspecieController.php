<?php

require_once 'model/EspecieModel.php';

class EspecieController
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
        $this->model = new EspecieModel();
    }

    public function mostrarListar()
    {
        $id_genero = isset($_GET['id_genero'])
            ? (int) $_GET['id_genero']
            : 0;

        $especies = $this->model->listarEspecies($id_genero);

        $this->view->show(
            'listarEspeciesView.php',
            array(
                'especies' => $especies
            )
        );
    }

    public function mostrarRegistrar()
    {
        require_once 'model/GeneroModel.php';

        $generos = (new GeneroModel())->listarGeneros();

        $this->view->show(
            'registrarEspecieView.php',
            array(
                'generos' => $generos
            )
        );
    }

    public function registrar()
    {
        try {
            $nombre = isset($_POST['nombre'])
                ? trim($_POST['nombre'])
                : '';

            $id_genero = isset($_POST['id_genero'])
                ? trim($_POST['id_genero'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($nombre) || empty($id_genero)) {
                header(
                    'Location: ?controlador=Especie'
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

            $respuesta = $this->model->registrarEspecie(
                $nombre,
                $id_genero,
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
                    . '&tab=especies'
                    . '&status=registrada_success'
                );
                exit;
            }

            $mensaje = is_array($respuesta)
                && isset($respuesta['Resultado'])
                ? $respuesta['Resultado']
                : 'No se pudo registrar la especie.';

            throw new Exception($mensaje);

        } catch (Exception $e) {
            die(
                '<strong>Error al registrar la especie:</strong> '
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
            $id = isset($_POST['id_especie'])
                ? trim($_POST['id_especie'])
                : '';

            $nombre = isset($_POST['nombre'])
                ? trim($_POST['nombre'])
                : '';

            $id_genero = isset($_POST['id_genero'])
                ? trim($_POST['id_genero'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (
                empty($id)
                || empty($nombre)
                || empty($id_genero)
            ) {
                header(
                    'Location: ?controlador=Especie'
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

            $respuesta = $this->model->editarEspecie(
                $id,
                $nombre,
                $id_genero,
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
                    . '&tab=especies'
                    . '&status=editado_success'
                );
                exit;
            }

            $mensaje = is_array($respuesta)
                && isset($respuesta['Resultado'])
                ? $respuesta['Resultado']
                : 'No se pudo editar la especie.';

            throw new Exception($mensaje);

        } catch (Exception $e) {
            die(
                '<strong>Error al editar la especie:</strong> '
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

        $datos = $this->model->buscarEspeciePorId($id);

        if (!$datos) {
            header(
                'Location: ?controlador=Taxonomia'
                . '&accion=mostrar'
                . '&tab=especies'
                . '&status=no_encontrado'
            );
            exit;
        }

        require_once 'model/GeneroModel.php';

        $generos = (new GeneroModel())->listarGeneros();

        $this->view->show(
            'editarEspecieView.php',
            array(
                'especie' => $datos,
                'generos' => $generos
            )
        );
    }
}
?>