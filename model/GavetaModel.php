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
        $consulta = $this->db->prepare('CALL sp_listar_gavetas(?)');
        $consulta->execute([$id_gabinete]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarGaveta($codigo, $id_gabinete)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_gaveta(?, ?)');
            $consulta->execute([$codigo, $id_gabinete]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    public function buscarGavetaPorId($id_gaveta) {
        $consulta = $this->db->prepare('CALL sp_buscarGavetaPorId(?)');
        $consulta->execute([$id_gaveta]);
        $res = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor(); return $res;
    }

    public function editarGaveta($id_gaveta, $codigo, $id_gabinete) {
        try {
            $consulta = $this->db->prepare('CALL sp_editarGaveta(?, ?, ?)');
            return $consulta->execute([$id_gaveta, $codigo, $id_gabinete]);
        } catch (PDOException $e) { return false; }
    }

    public function inhabilitarGaveta($id_gaveta) {
        try {
            $consulta = $this->db->prepare('CALL sp_inhabilitarGaveta(?)');
            $consulta->execute([$id_gaveta]);
            $res = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor(); return $res; 
        } catch (PDOException $e) { return ['Exito' => 0]; }
    }
}
?>
