<?php
class GeneroModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function listarGeneros($id_familia = 0)
    {
        $consulta = $this->db->prepare('CALL sp_listar_generos(?)');
        $consulta->execute([$id_familia]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarGenero($nombre, $id_familia)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_genero(?, ?)');
            $consulta->execute([$nombre, $id_familia]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }
}
?>
