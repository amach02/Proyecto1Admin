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

    // NUEVO: Para la HU14 (C#)
    public function listarPlantasPorEspecimen($id_especimen)
    {
        $consulta = $this->db->prepare('CALL sp_listar_plantas_por_especimen(?)');
        $consulta->execute([$id_especimen]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    // NUEVO: Para la HU14 (C#)
    public function vincularPlanta($id_especimen, $id_planta, $id_usuario)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_vincular_planta(?, ?, ?)');
            $consulta->execute([$id_especimen, $id_planta, $id_usuario]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    // NUEVO: Para la HU14 (C#)
    public function desvincularPlanta($id_especimen, $id_planta)
    {
        try {
            // Se hace un DELETE directo a la tabla intermedia
            $consulta = $this->db->prepare('DELETE FROM tb_especimen_planta WHERE id_especimen = ? AND id_planta = ?');
            return $consulta->execute([$id_especimen, $id_planta]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ACTUALIZADO: 2 nombres
    public function registrarPlanta($nombre_cientifico, $nombre_comun, $id_usuario)
    {
        try {
            $nombre_comun = empty($nombre_comun) ? null : $nombre_comun;
            $consulta = $this->db->prepare('CALL sp_registrar_planta_hospedadora(?, ?, ?)');
            $consulta->execute([$nombre_cientifico, $nombre_comun, $id_usuario]);
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

    // ACTUALIZADO: 2 nombres
    public function editarPlanta($id_planta, $nombre_cientifico, $nombre_comun, $id_usuario)
    {
        try {
            $nombre_comun = empty($nombre_comun) ? null : $nombre_comun;
            $consulta = $this->db->prepare('CALL sp_editar_planta_hospedadora(?, ?, ?, ?)');
            $consulta->execute([$id_planta, $nombre_cientifico, $nombre_comun, $id_usuario]);
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