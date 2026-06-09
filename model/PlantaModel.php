<?php
class PlantaModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarPlantas()
    {
        $consulta = $this->db->prepare('CALL sp_listar_plantas_hospedadoras()');
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarPlanta($nombre, $id_usuario)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_planta_hospedadora(?, ?)');
            $consulta->execute([$nombre, $id_usuario]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    public function buscarPlantaPorId($id_planta)
    {
        $consulta = $this->db->prepare('CALL sp_buscar_planta_hospedadora(?)');
        $consulta->execute([$id_planta]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function editarPlanta($id_planta, $nombre, $id_usuario)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_editar_planta_hospedadora(?, ?, ?)');
            $consulta->execute([$id_planta, $nombre, $id_usuario]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    public function inhabilitarPlanta($id_planta, $id_usuario)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_inhabilitar_planta_hospedadora(?, ?)');
            $consulta->execute([$id_planta, $id_usuario]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }
}
?>
