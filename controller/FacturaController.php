<?php

require_once 'model/FacturaModel.php';
require_once 'model/ClienteModel.php';
require_once 'model/ProductoModel.php';

class FacturaController
{

    private $view;
    private $model;

    public function __construct()
    {

        require_once 'libs/View.php';

        $this->view = new View();
        $this->model = new FacturaModel();
    }

    // Mostrar vista
    public function mostrar()
    {
        $codigoFactura =
            strtoupper(
                substr(
                    md5(uniqid(rand(), true)),
                    0,
                    4
                )
            );
        $this->view->show(
            'facturaView.php',
            [
                'codigoFactura' => $codigoFactura
            ]
        );
    }
    // Guardar factura completa
    public function registrar()
    {

        header('Content-Type: application/json');

        try {
            $data = json_decode(
                file_get_contents("php://input"),
                true
            );
            if (!$data) {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'No llegaron datos'
                ]);
                return;
            }
            $codigoFactura =
                $data['codigoFactura'];
            $cedula =
                $data['cedula'];
            $productos =
                $data['productos'];

            // Guardar factura
            $this->model->registrarFactura(
                $codigoFactura,
                $cedula
            );

            // Guardar detalles
            foreach ($productos as $producto) {

                $this->model->registrarDetalle(
                    $codigoFactura,
                    $producto['codigo'],
                    $producto['cantidad'],
                    $producto['precio']
                );
            }
            echo json_encode([
                'success' => true
            ]);
        } catch (Exception $e) {

            echo json_encode([
                'success' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
}
