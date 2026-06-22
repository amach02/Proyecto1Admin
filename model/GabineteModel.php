<?php

class GabineteModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function buscarGabinetePorId($id_gabinete)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarGabinetePorId(?)'
            );

            $consulta->execute(
                array($id_gabinete)
            );

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function editarGabinete(
        $id_gabinete,
        $codigo,
        $descripcion,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_editarGabinete(?, ?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $id_gabinete,
                    $codigo,
                    $descripcion,
                    $id_usuario_accion
                )
            );

            /*
             * Leer Exito y Resultado devueltos por el SP.
             */
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al editar el gabinete: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function inhabilitarGabinete(
        $id_gabinete,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_inhabilitarGabinete(?, ?)'
            );

            $consulta->execute(
                array(
                    $id_gabinete,
                    $id_usuario_accion
                )
            );

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al inhabilitar el gabinete: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function listarGabinetes()
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listar_gabinetes()'
            );

            $consulta->execute();

            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function registrarGabinete(
        $codigo,
        $descripcion,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_registrar_gabinete(?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $codigo,
                    $descripcion,
                    $id_usuario_accion
                )
            );

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al registrar el gabinete: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }
}
?>