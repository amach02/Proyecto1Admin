<?php

require_once 'model/ProductoModel.php';

class ProductoController
{

    private $view;
    private $model;

    public function __construct()
    {

        require_once 'libs/View.php';

        $this->view = new View();
        $this->model = new ProductoModel();
    }

    // Mostrar vista
    public function mostrar()
    {

        $this->view->show('productoView.php');
    }

    // Registrar producto
    public function registrar()
    {

        $codigo = trim($_POST['codigo']);
        $marca = trim($_POST['marca']);
        $descripcion = trim($_POST['descripcion']);
        $precio = trim($_POST['precio']);

        // Validar vacíos
        if (
            empty($codigo) ||
            empty($marca) ||
            empty($descripcion) ||
            empty($precio)
        ) {

            header(
                'Location: ?controlador=Producto&accion=mostrar&status=invalido'
            );

            return;
        }

        // Validar precio
        if ($precio <= 0) {

            header(
                'Location: ?controlador=Producto&accion=mostrar&status=precio'
            );

            return;
        }

        // Validar duplicado
        $existe = $this->model->existeCodigo($codigo);

        if ($existe) {

            header(
                'Location: ?controlador=Producto&accion=mostrar&status=duplicado'
            );

            return;
        }

        $resultado = $this->model->registrarProducto(
            $codigo,
            $marca,
            $descripcion,
            $precio
        );

        if ($resultado) {

            header(
                'Location: ?controlador=Producto&accion=mostrar&status=success'
            );
        } else {
            header(
                'Location: ?controlador=Producto&accion=mostrar&status=error'
            );
        }
    }

    // AJAX búsqueda
    public function buscarAjax()
    {
        $marca = $_GET['marca'];
        $productos = $this->model->buscarProducto($marca);
        echo json_encode($productos);
    }
}
