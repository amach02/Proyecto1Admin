<?php

class CajaModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarCajas()
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listar_cajas()'
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

    public function registrarCaja(
        $codigo,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_registrar_caja(?, ?)'
            );

            $consulta->execute(
                array(
                    $codigo,
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
                'Resultado' => 'Error al registrar la caja: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function buscarCajaPorId($id_caja)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarCajaPorId(?)'
            );

            $consulta->execute(
                array($id_caja)
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

    public function editarCaja(
        $id_caja,
        $codigo,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_editarCaja(?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $id_caja,
                    $codigo,
                    $id_usuario_accion
                )
            );

            /*
             * Antes se devolvía execute(), que solamente
             * indica si se ejecutó el CALL.
             */
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al editar la caja: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function inhabilitarCaja(
        $id_caja,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_inhabilitarCaja(?, ?)'
            );

            $consulta->execute(
                array(
                    $id_caja,
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
                'Resultado' => 'Error al inhabilitar la caja: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }
}
?>