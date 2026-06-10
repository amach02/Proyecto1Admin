<?php

class GeneroModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';

        $this->db = SPDO::singleton();
    }

    public function listarGeneros($id_familia = 0)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listar_generos(?)'
            );

            $consulta->execute(
                array($id_familia)
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

    public function registrarGenero(
        $nombre,
        $id_familia,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_registrar_genero(?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $nombre,
                    $id_familia,
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
                'Resultado' => 'Error al registrar el género: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function buscarGeneroPorId($id_genero)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarGeneroPorId(?)'
            );

            $consulta->execute(
                array($id_genero)
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

    public function editarGenero(
        $id_genero,
        $nombre,
        $id_familia,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_editarGenero(?, ?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $id_genero,
                    $nombre,
                    $id_familia,
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
                'Resultado' => 'Error al editar el género: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }
}
?>