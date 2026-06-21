<?php

class FamiliaModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function buscarFamiliaPorId($id_familia)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarFamiliaPorId(?)'
            );

            $consulta->execute(array($id_familia));

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function editarFamilia(
        $id_familia,
        $nombre,
        $id_orden,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_editarFamilia(?, ?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $id_familia,
                    $nombre,
                    $id_orden,
                    $id_usuario_accion
                )
            );

            /*
             * Leer la respuesta enviada por el procedimiento:
             * Resultado y Exito.
             */
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al editar la familia: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function listarFamilias($id_orden = 0)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listar_familias(?)'
            );

            $consulta->execute(array($id_orden));

            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function registrarFamilia(
        $nombre,
        $id_orden,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_registrar_familia(?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $nombre,
                    $id_orden,
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
                'Resultado' => 'Error al registrar la familia: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }
}
?>