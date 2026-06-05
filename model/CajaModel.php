<?php
class CajaModel 
{
    private $db;

    public function __construct() 
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarCajas() 
    {
        // Llamamos al SP para listar
        $consulta = $this->db->prepare('CALL sp_listar_cajas()');
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarCaja($codigo) 
    {
        try {
            // Llamamos al SP para registrar
            $consulta = $this->db->prepare('CALL sp_registrar_caja(?)');
            $consulta->execute(array($codigo));
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado; // Retorna el array con 'Exito'
        } catch (PDOException $e) {
            return array('Resultado' => 'Error de conexión', 'Exito' => 0);
        }
    }

    public function buscarCajaPorId($id) 
    {
        $consulta = $this->db->prepare('CALL sp_buscarCajaPorId(?)');
        $consulta->execute(array($id));
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function editarCaja($id_caja, $codigo) 
    {
        try {
            // Llamamos al SP para editar
            $consulta = $this->db->prepare('CALL sp_editarCaja(?, ?)');
            $resultado = $consulta->execute(array($id_caja, $codigo));
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) { 
            return false; 
        }
    }

    public function inhabilitarCaja($id) 
    {
        try {
            // Llamamos al SP para inhabilitar
            $consulta = $this->db->prepare('CALL sp_inhabilitarCaja(?)');
            $consulta->execute(array($id));
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado; // Retorna el array con 'Exito'
        } catch (PDOException $e) {
            return array('Resultado' => 'Error', 'Exito' => 0);
        }
    }
}
?>