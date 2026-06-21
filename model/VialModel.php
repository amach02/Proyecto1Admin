<?php

class VialModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarVialesDisponibles()
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listar_viales_disponibles()'
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

    public function registrarVial(
        $codigo,
        $id_caja,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_registrar_vial(?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $codigo,
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
                'Resultado' => 'Error al registrar el vial: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function buscarVialPorId($id_vial)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarVialPorId(?)'
            );

            $consulta->execute(
                array($id_vial)
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

    public function editarVial(
        $id_vial,
        $codigo,
        $id_caja,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_editarVial(?, ?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $id_vial,
                    $codigo,
                    $id_caja,
                    $id_usuario_accion
                )
            );

            /*
             * Ahora se recuperan Exito y Resultado
             * enviados por el procedimiento.
             */
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al editar el vial: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function inhabilitarVial(
        $id_vial,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_inhabilitarVial(?, ?)'
            );

            $consulta->execute(
                array(
                    $id_vial,
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
                'Resultado' => 'Error al inhabilitar el vial: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function listarPorCaja($id_caja)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listarVialesPorCaja(?)'
            );

            $consulta->execute(
                array($id_caja)
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
    public function listarVialesDisponiblesConRuta()
{
    $sql = "SELECT
                v.id_vial,
                v.codigo,
                v.id_caja,
                CONCAT(c.codigo, ' > ', v.codigo) AS ruta_completa
            FROM tb_vial v
            INNER JOIN tb_caja c
                ON v.id_caja = c.id_caja
            LEFT JOIN tb_especimen e
                ON e.id_vial = v.id_vial
            WHERE v.estado = 'activo'
              AND c.estado = 'activo'
              AND e.id_vial IS NULL
            ORDER BY c.codigo, v.codigo";

    $consulta = $this->db->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

public function listarDisponiblesPorCajaConRuta($id_caja)
{
    $sql = "SELECT
                v.id_vial,
                v.codigo,
                v.id_caja,
                CONCAT(c.codigo, ' > ', v.codigo) AS ruta_completa
            FROM tb_vial v
            INNER JOIN tb_caja c
                ON v.id_caja = c.id_caja
            LEFT JOIN tb_especimen e
                ON e.id_vial = v.id_vial
            WHERE v.id_caja = ?
              AND v.estado = 'activo'
              AND c.estado = 'activo'
              AND e.id_vial IS NULL
            ORDER BY v.codigo";

    $consulta = $this->db->prepare($sql);
    $consulta->execute(array($id_caja));

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

}
?>