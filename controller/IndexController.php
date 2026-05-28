<?php

require_once 'model/ProductoModel.php';

class IndexController
{
    private $view;
    private $model;
    public function __construct()
    {

        require_once 'libs/View.php';

        $this->view = new View();

        $this->model = new ProductoModel();
    }

    public function mostrar()
    {

        $productos =
            $this->model->listarProductos();

        $this->view->show(
            'indexView.php',
            [
                'productos' => $productos
            ]
        );
    }
}
