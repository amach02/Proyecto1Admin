<?php
class EspecimenModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    // Método para obtener los datos que se van a editar
    public function buscarEspecimenPorId($id_especimen)
    {
        $consulta = $this->db->prepare('CALL sp_buscarEspecimenPorId(?)');
        $consulta->execute([$id_especimen]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    // Ejecutar el UPDATE
    public function editarEspecimen($id, $codigo, $localizacion, $fecha, $estado, $id_especie, $id_vial)
    {
        try {
            $localizacion = empty($localizacion) ? null : $localizacion;
            $fecha = empty($fecha) ? null : $fecha;
            $id_especie = empty($id_especie) ? null : $id_especie;
            $id_vial = empty($id_vial) ? null : $id_vial;

            $consulta = $this->db->prepare('CALL sp_editarEspecimen(?, ?, ?, ?, ?, ?, ?)');
            $resultado = $consulta->execute([$id, $codigo, $localizacion, $fecha, $estado, $id_especie, $id_vial]);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarEspecimenes()
    {
        $consulta = $this->db->prepare('CALL sp_listar_especimenes()');
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarEspecimenPorCodigo($codigo_id)
    {
        $consulta = $this->db->prepare('CALL sp_buscarEspecimenPorCodigo(?)');
        $consulta->execute([$codigo_id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function consultarRutaFisica($id_especimen)
    {
        $consulta = $this->db->prepare('CALL sp_consultar_ruta_fisica(?)');
        $consulta->execute([$id_especimen]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarEspecimen($codigo_id, $localizacion, $fecha, $estado, $id_especie, $id_vial, $id_usuario)
    {
        try {
            $localizacion = empty($localizacion) ? null : $localizacion;
            $fecha = empty($fecha) ? null : $fecha;
            $id_especie = empty($id_especie) ? null : $id_especie;
            $id_vial = empty($id_vial) ? null : $id_vial;

            $consulta = $this->db->prepare('CALL sp_registrar_especimen(?, ?, ?, ?, ?, ?, ?)');
            $consulta->execute([$codigo_id, $localizacion, $fecha, $estado, $id_especie, $id_vial, $id_usuario]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

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
}
?>