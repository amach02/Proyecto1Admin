<?php

require_once 'libs/View.php';
require_once 'model/EspecimenModel.php';

class EspecimenController
{
    private $view;
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->view = new View();
        $this->model = new EspecimenModel();
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

        $datosEspecimen = $this->model->buscarEspecimenPorId($id);

        if ($datosEspecimen) {
            require_once 'model/EspecieModel.php';
            require_once 'model/VialModel.php';
            require_once 'model/GavetaModel.php';

            $listaEspecies = (new EspecieModel())->listarEspecies();
            $listaViales = (new VialModel())->listarVialesDisponibles();
            $listaGavetas = (new GavetaModel())->listarGavetas();

            $this->view->show(
                'editarEspecimenView.php',
                array(
                    'especimen' => $datosEspecimen,
                    'especies'  => $listaEspecies,
                    'viales'    => $listaViales,
                    'gavetas'   => $listaGavetas
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

    public function editar()
    {
        try {
            $id = isset($_POST['id_especimen'])
                ? trim($_POST['id_especimen'])
                : '';

            $codigo = isset($_POST['codigo_id'])
                ? trim($_POST['codigo_id'])
                : '';

            $localizacion = isset($_POST['localizacion_recoleccion'])
                ? trim($_POST['localizacion_recoleccion'])
                : '';

            $fecha = isset($_POST['fecha_recoleccion'])
                ? trim($_POST['fecha_recoleccion'])
                : '';

            $estado = isset($_POST['estado'])
                ? trim($_POST['estado'])
                : '';

            $id_especie = isset($_POST['id_especie'])
                ? trim($_POST['id_especie'])
                : '';

            $id_gaveta = !empty($_POST['id_gaveta'])
                ? trim($_POST['id_gaveta'])
                : null;

            $id_vial = !empty($_POST['id_vial'])
                ? trim($_POST['id_vial'])
                : null;

            /*
             * Usuario que realiza la modificación.
             */
            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($id) || empty($codigo)) {
                header(
                    'Location: ?controlador=Especimen'
                    . '&accion=mostrarEditar'
                    . '&id=' . urlencode($id)
                    . '&status=invalido'
                );
                exit;
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado en la sesión.'
                );
            }

            /*
             * Se agrega el usuario como último parámetro.
             *
             * El método editarEspecimen() del modelo también debe
             * recibir este noveno parámetro.
             */
            $resultado = $this->model->editarEspecimen(
                $id,
                $codigo,
                $localizacion,
                $fecha,
                $estado,
                $id_especie,
                $id_gaveta,
                $id_vial,
                $id_usuario_accion
            );

            /*
             * Permite que el modelo devuelva un booleano o el arreglo
             * producido por un procedimiento almacenado.
             */
            $exito = false;

            if (is_array($resultado)) {
                $exito = isset($resultado['Exito'])
                    && (int) $resultado['Exito'] === 1;
            } else {
                $exito = (bool) $resultado;
            }

            if ($exito) {
                header(
                    'Location: ?controlador=Especimen'
                    . '&accion=mostrarListar'
                    . '&status=especimen_editado_success'
                );
                exit;
            }

            header(
                'Location: ?controlador=Especimen'
                . '&accion=mostrarEditar'
                . '&id=' . urlencode($id)
                . '&status=error'
            );
            exit;

        } catch (Exception $e) {
            die(
                '<strong>Error al editar el espécimen:</strong> '
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
        $especimenes = $this->model->listarEspecimenes();

        $this->view->show(
            'listarEspecimenesView.php',
            array(
                'especimenes' => $especimenes
            )
        );
    }

   public function mostrarRegistrar()
{
    require_once 'model/EspecieModel.php';
    require_once 'model/GabineteModel.php';
    require_once 'model/CajaModel.php';

    $especieModel = new EspecieModel();
    $gabineteModel = new GabineteModel();
    $cajaModel = new CajaModel();

    $especies = $especieModel->listarEspecies();
    $gabinetes = $gabineteModel->listarGabinetes();
    $cajas = $cajaModel->listarCajas();

    $this->view->show(
        'registrarEspecimenView.php',
        array(
            'especies' => $especies,
            'gabinetes' => $gabinetes,
            'cajas' => $cajas
        )
    );
}

    public function registrar()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    try {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $codigo_id = isset($_POST['codigo_id'])
            ? trim($_POST['codigo_id'])
            : '';

        $fecha_recoleccion = !empty($_POST['fecha_recoleccion'])
            ? $_POST['fecha_recoleccion']
            : null;

        $localizacion = isset($_POST['localizacion_recoleccion'])
            ? trim($_POST['localizacion_recoleccion'])
            : '';

        $estado = isset($_POST['estado']) && $_POST['estado'] != ''
            ? $_POST['estado']
            : 'disponible';

        $id_especie = !empty($_POST['id_especie'])
            ? $_POST['id_especie']
            : null;

        $tipo_contenedor = isset($_POST['tipo_contenedor'])
            ? $_POST['tipo_contenedor']
            : '';

        $id_gaveta = !empty($_POST['id_gaveta'])
            ? $_POST['id_gaveta']
            : null;

        $id_vial = !empty($_POST['id_vial'])
            ? $_POST['id_vial']
            : null;

        $id_usuario_accion = isset($_SESSION['id_usuario'])
            ? $_SESSION['id_usuario']
            : null;

        if ($codigo_id == '') {
            throw new Exception('El código ID del espécimen es obligatorio.');
        }

        if ($id_usuario_accion === null) {
            throw new Exception('No se encontró el id_usuario en la sesión.');
        }

        if ($tipo_contenedor === 'gabinete') {
            $id_vial = null;

            if ($id_gaveta === null) {
                throw new Exception(
                    'Debe seleccionar una gaveta para guardar el espécimen en almacenamiento seco.'
                );
            }

        } elseif ($tipo_contenedor === 'caja') {
            $id_gaveta = null;

            if ($id_vial === null) {
                throw new Exception(
                    'Debe seleccionar un vial disponible para guardar el espécimen en almacenamiento líquido.'
                );
            }

        } else {
            throw new Exception('Debe seleccionar gabinete o caja.');
        }

        $resultado = $this->model->registrarEspecimen(
            $codigo_id,
            $localizacion,
            $fecha_recoleccion,
            $estado,
            $id_especie,
            $id_vial,
            $id_gaveta,
            $id_usuario_accion
        );

        $exito = false;
        $mensajeError = 'No se pudo registrar el espécimen.';

        if (is_array($resultado)) {
            if (isset($resultado['Exito']) && (int) $resultado['Exito'] === 1) {
                $exito = true;
            }

            if (isset($resultado['Resultado'])) {
                $mensajeError = $resultado['Resultado'];
            }
        } else {
            $exito = (bool) $resultado;
        }

        if (!$exito) {
            throw new Exception($mensajeError);
        }

        header(
            'Location: ?controlador=Especimen'
            . '&accion=mostrarRegistrar'
            . '&status=registrado_success'
        );
        exit;

    } catch (Exception $e) {
        die(
            '<strong>ERROR FATAL AL GUARDAR:</strong> '
            . $e->getMessage()
        );
    }
}

    public function buscarPorCodigo()
    {
        $codigo = isset($_GET['codigo'])
            ? trim($_GET['codigo'])
            : '';

        if (empty($codigo)) {
            header(
                'Location: ?controlador=Especimen'
                . '&accion=mostrarListar'
                . '&status=invalido'
            );
            exit;
        }

        $especimen = $this->model->buscarEspecimenPorCodigo($codigo);

        $this->view->show(
            'listarEspecimenesView.php',
            array(
                'especimenes' => $especimen
                    ? array($especimen)
                    : array(),
                'busqueda' => $codigo
            )
        );
    }

    public function rutaFisica()
    {
        if (!isset($_GET['id'])) {
            header(
                'Location: ?controlador=Especimen'
                . '&accion=mostrarListar'
                . '&status=error'
            );
            exit;
        }

        $id = $_GET['id'];

        $ruta = $this->model->consultarRutaFisica($id);

        $this->view->show(
            'rutaFisicaView.php',
            array(
                'ruta' => $ruta
            )
        );
    }

    public function cargarGavetas()
{
    require_once 'model/GavetaModel.php';

    $id_gabinete = isset($_GET['id_gabinete']) ? $_GET['id_gabinete'] : 0;

    $model = new GavetaModel();
    $gavetas = $model->listarPorGabineteConRuta($id_gabinete);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($gavetas);
    exit;
}

public function cargarViales()
{
    require_once 'model/VialModel.php';

    $id_caja = isset($_GET['id_caja']) ? $_GET['id_caja'] : 0;

    $model = new VialModel();
    $viales = $model->listarDisponiblesPorCajaConRuta($id_caja);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($viales);
    exit;
}

    public function vincularPlanta()
    {
        try {
            $id_especimen = isset($_POST['id_especimen'])
                ? trim($_POST['id_especimen'])
                : '';

            $id_planta = isset($_POST['id_planta'])
                ? trim($_POST['id_planta'])
                : '';

            $id_usuario_accion = isset($_SESSION['id_usuario'])
                ? $_SESSION['id_usuario']
                : null;

            if (empty($id_especimen) || empty($id_planta)) {
                header(
                    'Location: ?controlador=Especimen'
                    . '&accion=mostrarEditar'
                    . '&id=' . urlencode($id_especimen)
                    . '&status=invalido'
                );
                exit;
            }

            if ($id_usuario_accion === null) {
                throw new Exception(
                    'No se encontró el usuario autenticado.'
                );
            }

            $respuesta = $this->model->vincularPlanta(
                $id_especimen,
                $id_planta,
                $id_usuario_accion
            );

            if (
                is_array($respuesta)
                && isset($respuesta['Exito'])
                && (int) $respuesta['Exito'] === 1
            ) {
                header(
                    'Location: ?controlador=Especimen'
                    . '&accion=mostrarEditar'
                    . '&id=' . urlencode($id_especimen)
                    . '&status=planta_vinculada'
                );
                exit;
            }

            header(
                'Location: ?controlador=Especimen'
                . '&accion=mostrarEditar'
                . '&id=' . urlencode($id_especimen)
                . '&status=error_vinculo'
            );
            exit;

        } catch (Exception $e) {
            die(
                '<strong>Error al vincular la planta:</strong> '
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