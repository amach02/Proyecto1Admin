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

    Ejecutar el UPDATE
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
}
?>