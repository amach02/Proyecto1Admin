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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->view = new View();
        $this->model = new BitacoraModel();
    }

    public function index()
    {
        try {
            $fecha_fin = date('Y-m-d');
            $fecha_inicio = date('Y-m-d', strtotime('-30 days'));

            $this->mostrarBitacoraPaginada($fecha_inicio, $fecha_fin);

        } catch (Exception $e) {
            die('Error en historial: ' . $e->getMessage());
        }
    }

    public function filtrar()
    {
        try {
            if (isset($_POST['fecha_inicio'])) {
                $fecha_inicio = $_POST['fecha_inicio'];
            } elseif (isset($_GET['fecha_inicio'])) {
                $fecha_inicio = $_GET['fecha_inicio'];
            } else {
                $fecha_inicio = date('Y-m-d', strtotime('-30 days'));
            }

            if (isset($_POST['fecha_fin'])) {
                $fecha_fin = $_POST['fecha_fin'];
            } elseif (isset($_GET['fecha_fin'])) {
                $fecha_fin = $_GET['fecha_fin'];
            } else {
                $fecha_fin = date('Y-m-d');
            }

            if ($fecha_inicio == '') {
                $fecha_inicio = date('Y-m-d', strtotime('-30 days'));
            }

            if ($fecha_fin == '') {
                $fecha_fin = date('Y-m-d');
            }

            $this->mostrarBitacoraPaginada($fecha_inicio, $fecha_fin);

        } catch (Exception $e) {
            die('Error al filtrar historial: ' . $e->getMessage());
        }
    }

    private function mostrarBitacoraPaginada($fecha_inicio, $fecha_fin)
    {
        $bitacoraCompleta = $this->model->obtenerBitacoraPorFecha(
            $fecha_inicio,
            $fecha_fin
        );

        $registros_por_pagina = 10;

        $pagina_actual = isset($_GET['pagina'])
            ? intval($_GET['pagina'])
            : 1;

        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        }

        $total_registros = count($bitacoraCompleta);

        $total_paginas = ceil($total_registros / $registros_por_pagina);

        if ($total_paginas < 1) {
            $total_paginas = 1;
        }

        if ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }

        $inicio = ($pagina_actual - 1) * $registros_por_pagina;

        $bitacora = array_slice(
            $bitacoraCompleta,
            $inicio,
            $registros_por_pagina
        );

        $this->view->show(
            'bitacoraView.php',
            array(
                'bitacora' => $bitacora,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'pagina_actual' => $pagina_actual,
                'total_paginas' => $total_paginas,
                'total_registros' => $total_registros,
                'registros_por_pagina' => $registros_por_pagina
            )
        );
    }
}
?>