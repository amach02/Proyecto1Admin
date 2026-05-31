<?php
class CajaModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarCajas($id_gaveta = 0)
    {
        $consulta = $this->db->prepare('CALL sp_listar_cajas(?)');
        $consulta->execute([$id_gaveta]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarCaja($codigo, $id_gaveta)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_caja(?, ?)');
            $consulta->execute([$codigo, $id_gaveta]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    public function buscarCajaPorId($id_caja) {
        $consulta = $this->db->prepare('CALL sp_buscarCajaPorId(?)');
        $consulta->execute([$id_caja]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor(); return $resultado;
    }

    public function editarCaja($id_caja, $codigo, $id_gaveta) {
        try {
            $consulta = $this->db->prepare('CALL sp_editarCaja(?, ?, ?)');
            return $consulta->execute([$id_caja, $codigo, $id_gaveta]);
        } catch (PDOException $e) { return false; }
    }

    public function inhabilitarCaja($id_caja) {
        try {
            $consulta = $this->db->prepare('CALL sp_inhabilitarCaja(?)');
            $consulta->execute([$id_caja]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor(); return $resultado;
        } catch (PDOException $e) { return ['Exito' => 0]; }
    }
}
?>
