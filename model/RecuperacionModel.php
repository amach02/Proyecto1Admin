<?php
class RecuperacionModel {

    private $db;

    public function __construct() {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function crearToken($correo, $id_usuario_accion) {
        $consulta = $this->db->prepare('CALL sp_crear_token_recuperacion(?, ?)');
        $consulta->execute(array($correo, $id_usuario_accion));
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function validarToken($token) {
        $consulta = $this->db->prepare('CALL sp_validar_token(?)');
        $consulta->execute(array($token));
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function cambiarContrasena($token, $nueva, $id_usuario_accion) {
        $consulta = $this->db->prepare('CALL sp_cambiar_contrasena_token(?, ?, ?)');
        $consulta->execute(array($token, $nueva, $id_usuario_accion));
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }
}
?>