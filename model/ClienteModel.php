<?php

class ClienteModel
{

    private $db;

    public function __construct()
    {

        require_once 'libs/SPDO.php';

        $this->db = SPDO::singleton();
    }

    // Registrar cliente
    public function registrarCliente(
        $cedula,
        $nombre,
        $apellidos,
        $direccion
    ) {

        try {

            $consulta = $this->db->prepare(
                'CALL sp_registrarCliente(?, ?, ?, ?)'
            );

            $resultado = $consulta->execute([
                $cedula,
                $nombre,
                $apellidos,
                $direccion
            ]);

            $consulta->closeCursor();

            return $resultado;
        } catch (PDOException $e) {

            return false;
        }
    }

    // Buscar cliente
    public function buscarCliente($cedula)
    {

        $consulta = $this->db->prepare(
            'CALL sp_buscarCliente(?)'
        );

        $consulta->execute([$cedula]);

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $resultado;
    }

    // Verificar si existe la cédula
    public function existeCedula($cedula)
    {

        $consulta = $this->db->prepare(
            'SELECT cedula FROM cliente WHERE cedula = ?'
        );

        $consulta->execute([$cedula]);

        $resultado = $consulta->fetch();

        $consulta->closeCursor();

        return $resultado;
    }
}
