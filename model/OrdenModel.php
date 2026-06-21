<?php
class OrdenModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarOrdenes()
    {
        $consulta = $this->db->prepare('CALL sp_listar_ordenes()');
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarOrden($nombre, $id_usuario_accion)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_orden(?, ?)');
            $consulta->execute([$nombre, $id_usuario_accion]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    public function buscarOrdenPorId($id_orden)
    {
        $consulta = $this->db->prepare('CALL sp_buscarOrdenPorId(?)');
        $consulta->execute([$id_orden]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function editarOrden($id_orden, $nombre, $id_usuario_accion)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_editarOrden(?, ?, ?)');
            return $consulta->execute([$id_orden, $nombre, $id_usuario_accion]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
