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
            header('Location: ?controlador=Index&accion=mostrar&status=especimen_editado_success');
        } else {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id&status=error");
        }
    }
}
?>