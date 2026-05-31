<?php
class EspecieModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarEspecies($id_genero = 0)
    {
        $consulta = $this->db->prepare('CALL sp_listar_especies(?)');
        $consulta->execute([$id_genero]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarEspecie($nombre, $id_genero)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_especie(?, ?)');
            $consulta->execute([$nombre, $id_genero]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }
}
?>
