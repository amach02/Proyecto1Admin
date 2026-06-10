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
    public function editarUsuario($id_usuario, $nombre, $correo, $id_rol, $id_usuario_accion)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_editarUsuario(?, ?, ?, ?, ?)');
            $resultado = $consulta->execute([$id_usuario, $nombre, $correo, $id_rol, $id_usuario_accion]);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    // "Eliminar" de forma lógica
    public function inhabilitarUsuario($id_usuario, $id_usuario_accion)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_inhabilitarUsuario(?, ?)');
            $resultado = $consulta->execute([$id_usuario, $id_usuario_accion]);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarUsuarios()
    {
        $consulta = $this->db->prepare('CALL sp_listar_usuarios()');
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function registrarUsuario($nombre, $correo, $contrasena, $id_rol, $id_usuario_accion)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_registrar_usuario(?, ?, ?, ?, ?)');
            $consulta->execute([$nombre, $correo, $contrasena, $id_rol, $id_usuario_accion]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    public function autenticarUsuario($correo)
    {
        $consulta = $this->db->prepare('CALL sp_autenticar_usuario(?)');
        $consulta->execute([$correo]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarUsuarioPorCorreo($correo)
    {
        $consulta = $this->db->prepare('CALL sp_buscarUsuarioPorCorreo(?)');
        $consulta->execute([$correo]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }
}
?>