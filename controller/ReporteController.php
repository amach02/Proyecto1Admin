<?php

require_once 'model/ReporteModel.php';

class ReporteController
{

    private $view;
    private $model;

    public function __construct()
    {

        require_once 'libs/View.php';

        $this->view = new View();

        $this->model = new ReporteModel();
    }

    // Mostrar vista
    public function mostrar()
    {

        $this->view->show(
            'reporteView.php'
        );
    }

    // Buscar reporte
    public function buscar()
    {

        $cedula =
            $_GET['cedula'];

        $reporte =
            $this->model->reporteCliente(
                $cedula
            );

        $this->view->show(
            'reporteView.php',
            [
                'reporte' => $reporte,
                'cedula' => $cedula
            ]
        );
    }
}
