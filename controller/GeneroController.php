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
            header('Location: ?controlador=Genero&accion=mostrarListar&status=registrado_success');
        } else {
            header('Location: ?controlador=Genero&accion=mostrarRegistrar&status=error');
        }
    }
}
?>
