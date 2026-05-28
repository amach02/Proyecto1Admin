<?php

class FacturaModel
{

    private $db;

    public function __construct()
    {

        require_once 'libs/SPDO.php';

        $this->db = SPDO::singleton();
    }

    // Registrar factura
    public function registrarFactura(
        $codigo,
        $cedula
    ) {

        try {

            $consulta = $this->db->prepare(
                'CALL sp_registrarFactura(?, ?)'
            );

            $resultado = $consulta->execute([
                $codigo,
                $cedula
            ]);

            $consulta->closeCursor();

            return $resultado;

        } catch(PDOException $e){

            die($e->getMessage());
        }
    }

    // Registrar detalle
    public function registrarDetalle(
        $codigoFactura,
        $codigoProducto,
        $cantidad,
        $precio
    ) {

        try {

            $consulta = $this->db->prepare(
                'CALL sp_registrarDetalle(?, ?, ?, ?)'
            );

            $resultado = $consulta->execute([
                $codigoFactura,
                $codigoProducto,
                $cantidad,
                $precio
            ]);

            $consulta->closeCursor();

            return $resultado;

        } catch(PDOException $e){

            die($e->getMessage());
        }
    }
}