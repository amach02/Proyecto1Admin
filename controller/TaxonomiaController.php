<?php
require_once 'model/OrdenModel.php';
require_once 'model/FamiliaModel.php';
require_once 'model/GeneroModel.php';
require_once 'model/EspecieModel.php';

class TaxonomiaController
{
    private $view;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
    }

    public function mostrar()
    {
        $ordenes  = (new OrdenModel())->listarOrdenes();
        $familias = (new FamiliaModel())->listarFamilias();
        $generos  = (new GeneroModel())->listarGeneros();
        $especies = (new EspecieModel())->listarEspecies();
        $this->view->show('taxonomiaView.php', [
            'ordenes'  => $ordenes,
            'familias' => $familias,
            'generos'  => $generos,
            'especies' => $especies
        ]);
    }

    public function registrarOrden()
    {
        $nombre = trim($_POST['nombre']);
        if (empty($nombre)) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=ordenes&status=invalido');
            return;
        }
        $r = (new OrdenModel())->registrarOrden($nombre, $_SESSION['id_usuario_accion']);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Taxonomia&accion=mostrar&tab=ordenes&status=$destino");
    }

    public function registrarFamilia()
    {
        $nombre   = trim($_POST['nombre']);
        $id_orden = trim($_POST['id_orden']);
        if (empty($nombre) || empty($id_orden)) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=familias&status=invalido');
            return;
        }
        $r = (new FamiliaModel())->registrarFamilia($nombre, $id_orden, $_SESSION['id_usuario_accion']);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Taxonomia&accion=mostrar&tab=familias&status=$destino");
    }

    public function registrarGenero()
    {
        $nombre     = trim($_POST['nombre']);
        $id_familia = trim($_POST['id_familia']);
        if (empty($nombre) || empty($id_familia)) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=generos&status=invalido');
            return;
        }
        $r = (new GeneroModel())->registrarGenero($nombre, $id_familia, $_SESSION['id_usuario_accion']);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Taxonomia&accion=mostrar&tab=generos&status=$destino");
    }

    public function registrarEspecie()
    {
        $nombre    = trim($_POST['nombre']);
        $id_genero = trim($_POST['id_genero']);
        if (empty($nombre) || empty($id_genero)) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=especies&status=invalido');
            return;
        }
        $r = (new EspecieModel())->registrarEspecie($nombre, $id_genero, $_SESSION['id_usuario_accion']);
        $destino = $r['Exito'] == 1 ? 'registrado_success' : 'error';
        header("Location: ?controlador=Taxonomia&accion=mostrar&tab=especies&status=$destino");
    }
}
?>
