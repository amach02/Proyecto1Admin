<?php
require_once 'model/OrdenModel.php';
require_once 'libs/View.php'; 

class OrdenController
{
    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new View();
        $this->model = new OrdenModel();

        // Asegurarnos de que la sesión esté iniciada para poder usar la Bitácora
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function mostrarListar()
    {
        $ordenes = $this->model->listarOrdenes();
        $this->view->show('listarOrdenesView.php', ['ordenes' => $ordenes]);
    }

    public function mostrarRegistrar()
    {
        $this->view->show('registrarOrdenView.php', []);
    }

    public function registrar()
    {
        $nombre = trim($_POST['nombre'] ?? '');

        // Ajustado al nombre de variable correcto
        $id_usuario_accion = $_SESSION['id_usuario_accion'] ?? 1; 

        if (empty($nombre)) {
            header('Location: ?controlador=Orden&accion=mostrarRegistrar&status=invalido');
            exit(); // Freno de mano
        }

        // Le pasamos la variable correcta al modelo
        $respuesta = $this->model->registrarOrden($nombre, $id_usuario_accion);

        if ((is_array($respuesta) && isset($respuesta['Exito']) && $respuesta['Exito'] == 1) || $respuesta === true) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=ordenes&status=registrado_success');
            exit();
        } else {
            header('Location: ?controlador=Orden&accion=mostrarRegistrar&status=error');
            exit();
        }
    }

    public function mostrarEditar() 
    {
        if (!isset($_GET['id'])) { 
            header('Location: ?controlador=Index&accion=mostrar&status=error'); 
            exit(); 
        }

        $datos = $this->model->buscarOrdenPorId($_GET['id']);
        
        if ($datos) {
            $this->view->show('editarOrdenView.php', ['orden' => $datos]);
        } else {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=ordenes&status=error');
            exit();
        }
    }

    public function editar() 
    {
        $id = trim($_POST['id_orden'] ?? ''); 
        $nombre = trim($_POST['nombre'] ?? '');
        
        // Ajustado al nombre de variable correcto
        $id_usuario_accion = $_SESSION['id_usuario_accion'] ?? 1;

        if (empty($id) || empty($nombre)) {
            header("Location: ?controlador=Orden&accion=mostrarEditar&id=$id&status=error"); 
            exit();
        }

        // Le pasamos la variable correcta al modelo
        if ($this->model->editarOrden($id, $nombre, $id_usuario_accion)) {
            header('Location: ?controlador=Taxonomia&accion=mostrar&tab=ordenes&status=editado_success');
            exit();
        } else { 
            header("Location: ?controlador=Orden&accion=mostrarEditar&id=$id&status=error"); 
            exit();
        }
    }
}
?>