<?php

class ReporteModel
{

    private $db;

    public function __construct()
    {

        require_once 'libs/SPDO.php';

        $this->db = SPDO::singleton();
    }

    public function reporteCliente($cedula)
    {

        $consulta = $this->db->prepare(
            'CALL sp_reporteCliente(?)'
        );

        $consulta->execute([
            $cedula
        ]);

        $resultado =
            $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $resultado;
    }
}
