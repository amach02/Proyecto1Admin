<?php

class EspecieModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';

        $this->db = SPDO::singleton();
    }

    public function listarEspecies($id_genero = 0)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listar_especies(?)'
            );

            $consulta->execute(
                array($id_genero)
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

    public function registrarEspecie(
        $nombre,
        $id_genero,
        $id_usuario_accion
    ) {
        try {
            /*
             * El procedimiento debe recibir:
             * nombre, género y usuario que realiza la acción.
             */
            $consulta = $this->db->prepare(
                'CALL sp_registrarEspecie(?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $nombre,
                    $id_genero,
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
                'Resultado' => 'Error al registrar la especie: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function buscarEspeciePorId($id_especie)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarEspeciePorId(?)'
            );

            $consulta->execute(
                array($id_especie)
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

    public function editarEspecie(
        $id,
        $nombre,
        $id_genero,
        $id_usuario_accion
    ) {
        try {
            /*
             * Ya no se recibe $id_usuario adicional.
             * Solamente se necesita el usuario que ejecuta la acción.
             */
            $consulta = $this->db->prepare(
                'CALL sp_editarEspecie(?, ?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $id,
                    $nombre,
                    $id_genero,
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
                'Resultado' => 'Error al editar la especie: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }
}
?>