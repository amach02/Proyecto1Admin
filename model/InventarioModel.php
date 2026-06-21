<?php

require_once 'libs/SPDO.php';

class InventarioModel
{
    private $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    public function obtenerGabinetes()
    {
        try {
            $sql = "SELECT
                        id_gabinete,
                        codigo,
                        descripcion
                    FROM tb_gabinete
                    WHERE estado = 'activo'
                    ORDER BY codigo";

            $consulta = $this->db->prepare($sql);
            $consulta->execute();

            $resultado = $consulta->fetchAll(
                PDO::FETCH_ASSOC
            );

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function obtenerGavetasPorGabinete($id_gabinete)
    {
        try {
            $sql = "SELECT
                        id_gaveta,
                        codigo
                    FROM tb_gaveta
                    WHERE id_gabinete = :id_gabinete
                      AND estado = 'activo'
                    ORDER BY codigo";

            $consulta = $this->db->prepare($sql);

            $consulta->execute(
                array(
                    ':id_gabinete' => $id_gabinete
                )
            );

            $resultado = $consulta->fetchAll(
                PDO::FETCH_ASSOC
            );

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function obtenerCajas()
    {
        try {
            $sql = "SELECT
                        id_caja,
                        codigo
                    FROM tb_caja
                    WHERE estado = 'activo'
                    ORDER BY codigo";

            $consulta = $this->db->prepare($sql);
            $consulta->execute();

            $resultado = $consulta->fetchAll(
                PDO::FETCH_ASSOC
            );

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function obtenerVialesPorCaja($id_caja)
    {
        try {
            $sql = "SELECT
                        id_vial,
                        codigo
                    FROM tb_vial
                    WHERE id_caja = :id_caja
                      AND estado = 'activo'
                    ORDER BY codigo";

            $consulta = $this->db->prepare($sql);

            $consulta->execute(
                array(
                    ':id_caja' => $id_caja
                )
            );

            $resultado = $consulta->fetchAll(
                PDO::FETCH_ASSOC
            );

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function registrarEspecimenInfraestructura(
        $codigo_id,
        $localizacion,
        $fecha,
        $estado,
        $id_especie,
        $id_vial,
        $id_gaveta,
        $id_usuario_accion
    ) {
        try {
            /*
             * Orden del SP:
             *
             * 1. código
             * 2. localización
             * 3. fecha
             * 4. estado
             * 5. especie
             * 6. vial
             * 7. gaveta
             * 8. usuario
             */
            $consulta = $this->db->prepare(
                'CALL sp_registrar_especimen(
                    ?, ?, ?, ?, ?, ?, ?, ?
                )'
            );

            $consulta->execute(
                array(
                    $codigo_id,
                    $localizacion,
                    $fecha,
                    $estado,
                    $id_especie,
                    $id_vial,
                    $id_gaveta,
                    $id_usuario_accion
                )
            );

            $resultado = $consulta->fetch(
                PDO::FETCH_ASSOC
            );

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al registrar el espécimen: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }
}
?>