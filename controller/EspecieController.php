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
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=especies&status=registrada_success');
        } else {
            header('Location: ?controlador=Especie&accion=mostrarRegistrar&status=error');
        }
    }

    public function editar() {
        $id = trim($_POST['id_especie']); $nombre = trim($_POST['nombre']); $id_genero = trim($_POST['id_genero']);
        if ($this->model->editarEspecie($id, $nombre, $id_genero)) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=especies&status=editado_success');
        } else { 
            header("Location: ?controlador=Especie&accion=mostrarEditar&id=$id&status=error"); 
        }
    }

    public function mostrarEditar() {
        if (!isset($_GET['id'])) { header('Location: ?controlador=Index&accion=mostrar&status=error'); return; }
        $datos = $this->model->buscarEspeciePorId($_GET['id']);
        if ($datos) {
            require_once 'model/GeneroModel.php';
            $generos = (new GeneroModel())->listarGeneros();
            $this->view->show('editarEspecieView.php', ['especie' => $datos, 'generos' => $generos]);
        }
    }
}
?>
