<?php
require_once 'model/EspecimenModel.php';

class EspecimenController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new EspecimenModel();
    }

    // Carga la interfaz pasándole los datos recuperados por ID
    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

        $id = $_GET['id'];
        $datosEspecimen = $this->model->buscarEspecimenPorId($id);

        if ($datosEspecimen) {
            $this->view->show('editarEspecimenView.php', ['especimen' => $datosEspecimen]);
        } else {
            header('Location: ?controlador=Index&accion=mostrar&status=no_encontrado');
        }
    }

    // Procesa el envío del formulario (Método POST)
    public function editar()
    {
        $id = trim($_POST['id_especimen']);
        $codigo = trim($_POST['codigo_id']);
        $localizacion = trim($_POST['localizacion_recoleccion']);
        $fecha = trim($_POST['fecha_recoleccion']);
        $estado = trim($_POST['estado']);
        $id_especie = trim($_POST['id_especie']);
        $id_vial = trim($_POST['id_vial']);

        // Validación técnica en servidor del único campo estrictamente obligatorio
        if (empty($id) || empty($codigo)) {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id&status=invalido");
            return;
        }

        $resultado = $this->model->editarEspecimen($id, $codigo, $localizacion, $fecha, $estado, $id_especie, $id_vial);

        if ($resultado) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=especimen_editado_success');
        } else {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id&status=error");
        }
    }

    public function mostrarListar()
    {
        $especimenes = $this->model->listarEspecimenes();
        $this->view->show('listarEspecimenesView.php', ['especimenes' => $especimenes]);
    }

    public function mostrarRegistrar()
    {
        require_once 'model/EspecieModel.php';
        require_once 'model/VialModel.php';
        $especies = (new EspecieModel())->listarEspecies();
        $viales   = (new VialModel())->listarVialesDisponibles();
        $this->view->show('registrarEspecimenView.php', [
            'especies' => $especies,
            'viales'   => $viales
        ]);
    }

    public function registrar()
    {
        $codigo      = trim($_POST['codigo_id']);
        $localizacion = trim($_POST['localizacion_recoleccion']);
        $fecha       = trim($_POST['fecha_recoleccion']);
        $estado      = trim($_POST['estado']);
        $id_especie  = trim($_POST['id_especie']);
        $id_vial     = trim($_POST['id_vial']);
        $id_usuario  = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

        if (empty($codigo)) {
            header('Location: ?controlador=Especimen&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarEspecimen($codigo, $localizacion, $fecha, $estado, $id_especie, $id_vial, $id_usuario);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=registrado_success');
        } else {
            header('Location: ?controlador=Especimen&accion=mostrarRegistrar&status=error');
        }
    }

    public function buscarPorCodigo()
    {
        $codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';

        if (empty($codigo)) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=invalido');
            return;
        }

        $especimen = $this->model->buscarEspecimenPorCodigo($codigo);
        $this->view->show('listarEspecimenesView.php', [
            'especimenes' => $especimen ? [$especimen] : [],
            'busqueda'    => $codigo
        ]);
    }

    public function rutaFisica()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=error');
            return;
        }

        $id   = $_GET['id'];
        $ruta = $this->model->consultarRutaFisica($id);
        $this->view->show('rutaFisicaView.php', ['ruta' => $ruta]);
    }

    public function vincularPlanta()
    {
        $id_especimen = trim($_POST['id_especimen']);
        $id_planta    = trim($_POST['id_planta']);
        $id_usuario   = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

        if (empty($id_especimen) || empty($id_planta)) {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id_especimen&status=invalido");
            return;
        }

        $respuesta = $this->model->vincularPlanta($id_especimen, $id_planta, $id_usuario);

        if ($respuesta['Exito'] == 1) {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id_especimen&status=planta_vinculada");
        } else {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id_especimen&status=error_vinculo");
        }
    }
}
?>