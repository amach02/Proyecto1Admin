<?php
class VialModel {
    private $db;

    public function __construct() {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarVialesDisponibles()
{
    $consulta = $this->db->prepare(
        'CALL sp_listar_viales_disponibles()'
    );

    $consulta->execute();

    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $consulta->closeCursor();

    return $resultado;
}

    public function registrarVial($codigo, $id_caja)
{
    try {

        $consulta = $this->db->prepare(
            'CALL sp_registrar_vial(?, ?)'
        );

        $consulta->execute(array($codigo, $id_caja));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $resultado;

    } catch (PDOException $e) {

        return array(
            'Resultado' => $e->getMessage(),
            'Exito' => 0
        );
    }
}

    public function buscarVialPorId($id_vial)
{
    $consulta = $this->db->prepare(
        'CALL sp_buscarVialPorId(?)'
    );

    $consulta->execute(array($id_vial));

    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    $consulta->closeCursor();

    return $resultado;
}

    public function editarVial($id_vial, $codigo, $id_caja)
{
    try {

        $consulta = $this->db->prepare(
            'CALL sp_editarVial(?, ?, ?)'
        );

        $resultado = $consulta->execute(
            array($id_vial, $codigo, $id_caja)
        );

        $consulta->closeCursor();

        return $resultado;

    } catch (PDOException $e) {

        return false;
    }
}

    public function inhabilitarVial($id)
{
    try {

        $consulta = $this->db->prepare(
            'CALL sp_inhabilitarVial(?)'
        );

        $consulta->execute(array($id));

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $resultado;

    } catch (PDOException $e) {

        return array(
            'Resultado' => 'Error',
            'Exito' => 0
        );
    }
}

    // NUEVO: Este método lo necesita CajaController para listar los viales dentro de una caja
    public function listarPorCaja($id_caja)
{
    $consulta = $this->db->prepare(
        'CALL sp_listarVialesPorCaja(?)'
    );

    $consulta->execute(array($id_caja));

    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $consulta->closeCursor();

    return $resultado;
}
}
?>