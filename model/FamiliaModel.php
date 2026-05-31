<?php
class FamiliaModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function buscarFamiliaPorId($id_familia)
    {
        $consulta = $this->db->prepare('CALL sp_buscarFamiliaPorId(?)');
        $consulta->execute([$id_familia]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function editarFamilia($id_familia, $nombre, $id_orden)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_editarFamilia(?, ?, ?)');
            $resultado = $consulta->execute([$id_familia, $nombre, $id_orden]);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarFamilias($id_orden = 0)
    {
        $consulta = $this->db->prepare('CALL sp_listar_familias(?)');
        $consulta->execute([$id_orden]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarFamilia($nombre, $id_orden)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_familia(?, ?)');
            $consulta->execute([$nombre, $id_orden]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }
}
?>