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
        $consulta->execute(array($id_gabinete));
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarGaveta($codigo, $id_gabinete)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_gaveta(?, ?)');
            $consulta->execute(array($codigo, $id_gabinete));
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return array('Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0);
        }
    }

    public function buscarGavetaPorId($id_gaveta) 
    {
        $consulta = $this->db->prepare('CALL sp_buscarGavetaPorId(?)');
        $consulta->execute(array($id_gaveta));
        $res = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor(); 
        return $res;
    }

    public function editarGaveta($id_gaveta, $codigo, $id_gabinete) 
    {
        try {
            $consulta = $this->db->prepare('CALL sp_editarGaveta(?, ?, ?)');
            return $consulta->execute(array($id_gaveta, $codigo, $id_gabinete));
        } catch (PDOException $e) { 
            return false; 
        }
    }

    public function inhabilitarGaveta($id_gaveta) 
    {
        try {
            $consulta = $this->db->prepare('CALL sp_inhabilitarGaveta(?)');
            $consulta->execute(array($id_gaveta));
            $res = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor(); 
            return $res; 
        } catch (PDOException $e) { 
            return array('Exito' => 0); 
        }
    }

    // NUEVO: Este método es requerido por GabineteController para listar gavetas internamente
    public function listarPorGabinete($id_gabinete)
    {
        $consulta = $this->db->prepare('SELECT * FROM tb_gaveta WHERE id_gabinete = ?');
        $consulta->execute(array($id_gabinete));
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }
}
?>