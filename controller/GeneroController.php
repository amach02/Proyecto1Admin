<?php
require_once 'model/GeneroModel.php';

class GeneroController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new GeneroModel();
    }

    public function mostrarListar()
    {
        $id_familia = isset($_GET['id_familia']) ? (int)$_GET['id_familia'] : 0;
        $generos = $this->model->listarGeneros($id_familia);
        $this->view->show('listarGenerosView.php', ['generos' => $generos]);
    }

    public function mostrarRegistrar()
    {
        require_once 'model/FamiliaModel.php';
        $familias = (new FamiliaModel())->listarFamilias();
        $this->view->show('registrarGeneroView.php', ['familias' => $familias]);
    }

public function registrar()
    {
        $nombre     = trim($_POST['nombre']);
        $id_familia = trim($_POST['id_familia']);

        if (empty($nombre) || empty($id_familia)) {
            header('Location: ?controlador=Genero&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarGenero($nombre, $id_familia);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=generos&status=registrado_success');
        } else {
            header('Location: ?controlador=Genero&accion=mostrarRegistrar&status=error');
        }
    }

    public function editar() {
        $id = trim($_POST['id_genero']);
        $nombre = trim($_POST['nombre']);
        $id_familia = trim($_POST['id_familia']);

        if (empty($id) || empty($nombre) || empty($id_familia)) {
            header("Location: ?controlador=Genero&accion=mostrarEditar&id=$id&status=invalido"); return;
        }

        if ($this->model->editarGenero($id, $nombre, $id_familia)) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=generos&status=editado_success');
        } else {
            header("Location: ?controlador=Genero&accion=mostrarEditar&id=$id&status=error");
        }
    }

    public function mostrarEditar() {
        if (!isset($_GET['id'])) { header('Location: ?controlador=Index&accion=mostrar&status=error'); return; }
        $datos = $this->model->buscarGeneroPorId($_GET['id']);
        if ($datos) {
            require_once 'model/FamiliaModel.php';
            $familias = (new FamiliaModel())->listarFamilias();
            $this->view->show('editarGeneroView.php', ['genero' => $datos, 'familias' => $familias]);
        }
    }
}
?>
