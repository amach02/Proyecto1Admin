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
        $consulta = $this->db->prepare('CALL sp_listar_viales_disponibles()');
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarVial($codigo, $id_caja)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_vial(?, ?)');
            $consulta->execute([$codigo, $id_caja]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    public function buscarVialPorId($id_vial) {
        $consulta = $this->db->prepare('CALL sp_buscarVialPorId(?)');
        $consulta->execute([$id_vial]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor(); return $resultado;
    }

    public function editarVial($id_vial, $codigo, $id_caja) {
        try {
            $consulta = $this->db->prepare('CALL sp_editarVial(?, ?, ?)');
            return $consulta->execute([$id_vial, $codigo, $id_caja]);
        } catch (PDOException $e) { return false; }
    }

    public function inhabilitarVial($id_vial) {
        try {
            $consulta = $this->db->prepare('CALL sp_inhabilitarVial(?)');
            $consulta->execute([$id_vial]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor(); return $resultado;
        } catch (PDOException $e) { return ['Exito' => 0]; }
    }
}
?>
