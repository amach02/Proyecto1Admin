<?php
require_once 'model/EspecieModel.php';

class EspecieController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new EspecieModel();
    }

    public function mostrarListar()
    {
        $id_genero = isset($_GET['id_genero']) ? (int)$_GET['id_genero'] : 0;
        $especies = $this->model->listarEspecies($id_genero);
        $this->view->show('listarEspeciesView.php', ['especies' => $especies]);
    }

    public function mostrarRegistrar()
    {
        require_once 'model/GeneroModel.php';
        $generos = (new GeneroModel())->listarGeneros();
        $this->view->show('registrarEspecieView.php', ['generos' => $generos]);
    }

    public function registrar()
    {
        $nombre    = trim($_POST['nombre']);
        $id_genero = trim($_POST['id_genero']);

        if (empty($nombre) || empty($id_genero)) {
            header('Location: ?controlador=Especie&accion=mostrarRegistrar&status=invalido');
            return;
        }

        $respuesta = $this->model->registrarEspecie($nombre, $id_genero);

        if ($respuesta['Exito'] == 1) {
            header('Location: ?controlador=Especie&accion=mostrarListar&status=registrada_success');
        } else {
            header('Location: ?controlador=Especie&accion=mostrarRegistrar&status=error');
        }
    }
}
?>
