<?php
class GabineteModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function buscarGabinetePorId($id_gabinete)
    {
        $consulta = $this->db->prepare('CALL sp_buscarGabinetePorId(?)');
        $consulta->execute(array($id_gabinete));
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function editarGabinete($id_gabinete, $codigo, $descripcion)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_editarGabinete(?, ?, ?)');
            $resultado = $consulta->execute(array($id_gabinete, $codigo, $descripcion));
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function inhabilitarGabinete($id_gabinete)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_inhabilitarGabinete(?)');
            $consulta->execute(array($id_gabinete));
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return array('Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0);
        }
    }

    public function listarGabinetes()
    {
        $consulta = $this->db->prepare('CALL sp_listar_gabinetes()');
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarGabinete($codigo, $descripcion)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_gabinete(?, ?)');
            $consulta->execute(array($codigo, $descripcion));
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return array('Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0);
        }
    }
}
?>