<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'model/BitacoraModel.php';
require_once 'libs/View.php';

class BitacoraController
{
    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new View();
        $this->model = new BitacoraModel();
    }

    public function index()
    {
        try {
            $fecha_fin = date('Y-m-d');
            $fecha_inicio = date('Y-m-d', strtotime('-30 days'));

            $bitacora = $this->model->obtenerBitacoraPorFecha(
                $fecha_inicio,
                $fecha_fin
            );

            $this->view->show(
                'bitacoraPruebaView.php',
                array(
                    'bitacora' => $bitacora,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin
                )
            );

        } catch (Exception $e) {
            die('Error en Bitácora: ' . $e->getMessage());
        }
    }

    public function filtrar()
    {
        try {
            $fecha_inicio = isset($_POST['fecha_inicio'])
                ? $_POST['fecha_inicio']
                : date('Y-m-d', strtotime('-30 days'));

            $fecha_fin = isset($_POST['fecha_fin'])
                ? $_POST['fecha_fin']
                : date('Y-m-d');

            $bitacora = $this->model->obtenerBitacoraPorFecha(
                $fecha_inicio,
                $fecha_fin
            );

            $this->view->show(
                'bitacoraView.php',
                array(
                    'bitacora' => $bitacora,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin
                )
            );

        } catch (Exception $e) {
            die('Error al filtrar bitácora: ' . $e->getMessage());
        }
    }
}
?>