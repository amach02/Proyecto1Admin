<?php
class ComentarioModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function registrarComentario($id_especimen, $id_usuario, $comentario)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_comentario(?, ?, ?)');
            $consulta->execute([$id_especimen, $id_usuario, $comentario]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => $e->getMessage(), 'Exito' => 0];
        }
    }

    public function listarComentariosPorEspecimen($id_especimen)
    {
        $consulta = $this->db->prepare('CALL sp_listar_comentarios_especimen(?)');
        $consulta->execute([$id_especimen]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }
}
