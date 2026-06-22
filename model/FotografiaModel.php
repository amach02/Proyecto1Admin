<?php
class FotografiaModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    /**
     * Guarda la URL de Cloudinary en tb_fotografias_especimen
     */
    public function registrarFotografia($ruta, $id_especimen, $id_usuario)
{
    try {
        // Limpiar cualquier resultado pendiente de consultas anteriores
        $this->db->query('SELECT 1');
        
        $consulta = $this->db->prepare('CALL sp_registrar_fotografia(?, ?, ?)');
        $consulta->execute([$ruta, $id_especimen, $id_usuario]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        
        // Si el SP no devolvió nada, asumir éxito si no hubo excepción
        if (empty($resultado)) {
            return array('Resultado' => 'Fotografía registrada.', 'Exito' => 1);
        }
        
        return $resultado;
    } catch (PDOException $e) {
        return array('Resultado' => $e->getMessage(), 'Exito' => 0);
    }
}

    public function eliminarFotografia($id_fotografia, $id_usuario)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_eliminar_fotografia(?, ?)');
            $consulta->execute([$id_fotografia, $id_usuario]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return array('Resultado' => 'Error de conexión.', 'Exito' => 0);
        }
    }

    /**
     * Devuelve todas las fotos (URLs) de un espécimen dado su ID
     */
    public function listarFotosPorEspecimen($id_especimen)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_listar_fotos_especimen(?)');
            $consulta->execute([$id_especimen]);
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return [];
        }
    }
}
