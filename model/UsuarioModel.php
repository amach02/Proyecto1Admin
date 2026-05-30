<?php
class UsuarioModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    // Traer datos para llenar el formulario
    public function buscarUsuarioPorId($id_usuario)
    {
        $consulta = $this->db->prepare('CALL sp_buscarUsuarioPorId(?)');
        $consulta->execute([$id_usuario]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    // Actualizar (Update)
    public function editarUsuario($id_usuario, $nombre, $correo, $id_rol)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_editarUsuario(?, ?, ?, ?)');
            $resultado = $consulta->execute([$id_usuario, $nombre, $correo, $id_rol]);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    // "Eliminar" de forma lógica
    public function inhabilitarUsuario($id_usuario)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_inhabilitarUsuario(?)');
            $resultado = $consulta->execute([$id_usuario]);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>