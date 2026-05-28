<?php

require_once 'model/ClienteModel.php';

class ClienteController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';

        $this->view = new View();
        $this->model = new ClienteModel();
    }
    // Mostrar vista
    public function mostrar()
    {
        $this->view->show('clienteView.php');
    }

    // Registrar cliente
    public function registrar()
    {
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $apellidos = trim($_POST['apellidos']);
        $direccion = trim($_POST['direccion']);
        // Validar campos vacíos
        if (
            empty($cedula) ||
            empty($nombre) ||
            empty($apellidos) ||
            empty($direccion)
        ) {
            header(
                'Location: ?controlador=Cliente&accion=mostrar&status=invalido'
            );
            return;
        }

        // Validar duplicado
        $existe = $this->model->existeCedula($cedula);
        if ($existe) {
            header(
                'Location: ?controlador=Cliente&accion=mostrar&status=duplicado'
            );
            return;
        }
        $resultado = $this->model->registrarCliente(
            $cedula,
            $nombre,
            $apellidos,
            $direccion
        );
        if ($resultado) {
            header(
                'Location: ?controlador=Cliente&accion=mostrar&status=success'
            );
        } else {
            header(
                'Location: ?controlador=Cliente&accion=mostrar&status=error'
            );
        }
    }

    // Buscar cliente AJAX
    public function buscarAjax()
    {
        $cedula = $_GET['cedula'];
        $cliente = $this->model->buscarCliente($cedula);
        echo json_encode($cliente);
    }
}
