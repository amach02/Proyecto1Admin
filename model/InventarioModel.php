<?php
require_once 'libs/SPDO.php';

class InventarioModel {
    private $db;

    public function __construct() {
        $this->db = SPDO::singleton();
    }

    // Carga inicial de gabinetes activos
    public function obtenerGabinetes() {
        $stmt = $this->db->prepare("SELECT id_gabinete, codigo, descripcion FROM tb_gabinete WHERE estado = 'activo'");
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultados;
    }

    // Carga dependiente de gavetas por gabinete
    public function obtenerGavetasPorGabinete($id_gabinete) {
        $stmt = $this->db->prepare("SELECT id_gaveta, codigo FROM tb_gaveta WHERE id_gabinete = :id");
        $stmt->execute(array(':id' => $id_gabinete));
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultados;
    }

    // Carga dependiente de cajas por gaveta
    public function obtenerCajasPorGaveta($id_gaveta) {
        $stmt = $this->db->prepare("SELECT id_caja, codigo FROM tb_caja WHERE id_gaveta = :id");
        $stmt->execute(array(':id' => $id_gaveta));
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultados;
    }

    // Carga dependiente de viales por caja
    public function obtenerVialesPorCaja($id_caja) {
        $stmt = $this->db->prepare("SELECT id_vial, codigo FROM tb_vial WHERE id_caja = :id");
        $stmt->execute(array(':id' => $id_caja));
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultados;
    }

    // Ejecuta tu procedimiento almacenado sp_registrar_especimen
    public function registrarEspecimenInfrastructura($codigo_id, $localizacion, $fecha, $estado, $id_especie, $id_vial, $id_usuario) {
        $stmt = $this->db->prepare("CALL sp_registrar_especimen(:codigo, :localizacion, :fecha, :estado, :especie, :vial, :usuario)");
        
        $stmt->execute(array(
            ':codigo'       => $codigo_id,
            ':localizacion' => $localizacion,
            ':fecha'        => $fecha,
            ':estado'       => $estado,
            ':especie'      => $id_especie,
            ':vial'         => $id_vial,
            ':usuario'      => $id_usuario
        ));
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado;
    }
}
?>