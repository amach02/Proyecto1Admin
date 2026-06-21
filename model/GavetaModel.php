<?php

class GavetaModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';

        $this->db = SPDO::singleton();
    }

    public function listarGavetas($id_gabinete = 0)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listar_gavetas(?)'
            );

            $consulta->execute(
                array($id_gabinete)
            );

            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function registrarGaveta(
        $codigo,
        $id_gabinete,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_registrar_gaveta(?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $codigo,
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
                'Resultado' => 'Error al registrar la gaveta: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function buscarGavetaPorId($id_gaveta)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarGavetaPorId(?)'
            );

            $consulta->execute(
                array($id_gaveta)
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

    public function editarGaveta(
        $id_gaveta,
        $codigo,
        $id_gabinete,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_editarGaveta(?, ?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $id_gaveta,
                    $codigo,
                    $id_gabinete,
                    $id_usuario_accion
                )
            );

            /*
             * Antes devolvía solamente execute().
             * Ahora lee Exito y Resultado del procedimiento.
             */
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al editar la gaveta: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function inhabilitarGaveta(
        $id_gaveta,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_inhabilitarGaveta(?, ?)'
            );

            $consulta->execute(
                array(
                    $id_gaveta,
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
                'Resultado' => 'Error al inhabilitar la gaveta: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function listarPorGabinete($id_gabinete)
    {
        try {
            $consulta = $this->db->prepare(
                'SELECT *
                 FROM tb_gaveta
                 WHERE id_gabinete = ?'
            );

            $consulta->execute(
                array($id_gabinete)
            );

            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function listarGavetasConRuta()
{
    $sql = "SELECT
                gv.id_gaveta,
                gv.codigo,
                gv.id_gabinete,
                CONCAT(g.codigo, ' > ', gv.codigo) AS ruta_completa
            FROM tb_gaveta gv
            INNER JOIN tb_gabinete g
                ON gv.id_gabinete = g.id_gabinete
            WHERE gv.estado = 'activo'
              AND g.estado = 'activo'
            ORDER BY g.codigo, gv.codigo";

    $consulta = $this->db->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

public function listarPorGabineteConRuta($id_gabinete)
{
    $sql = "SELECT
                gv.id_gaveta,
                gv.codigo,
                gv.id_gabinete,
                CONCAT(g.codigo, ' > ', gv.codigo) AS ruta_completa
            FROM tb_gaveta gv
            INNER JOIN tb_gabinete g
                ON gv.id_gabinete = g.id_gabinete
            WHERE gv.id_gabinete = ?
              AND gv.estado = 'activo'
              AND g.estado = 'activo'
            ORDER BY gv.codigo";

    $consulta = $this->db->prepare($sql);
    $consulta->execute(array($id_gabinete));

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}
}

?>