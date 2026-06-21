<?php
require_once 'libs/SPDO.php';
class BitacoraModel
{
    private $db;

    public function __construct()
    {
        $this->db = SPDO::singleton();
    }

    /*
     * Guarda una acción en la bitácora.
     */
    public function registrar($accion, $tablaAfectada, $idUsuario)
    {
        try {
            $sql = "INSERT INTO tb_bitacora
                    (accion, tabla_afectada, id_usuario)
                    VALUES (?, ?, ?)";

            $consulta = $this->db->prepare($sql);

            return $consulta->execute([
                $accion,
                $tablaAfectada,
                $idUsuario
            ]);

        } catch (PDOException $e) {
            throw new Exception(
                'Error al registrar la bitácora: ' . $e->getMessage()
            );
        }
    }

    /*
     * Obtiene el historial entre dos fechas.
     * Incluye nombre y correo del usuario.
     */
    public function obtenerBitacoraPorFecha($fechaInicio, $fechaFin)
    {
        try {
            // Agregar un día a fecha_fin para incluir todo ese día
            $fechaFin = date('Y-m-d', strtotime($fechaFin . ' +1 day'));

            $sql = "SELECT 
                        b.id_bitacora,
                        b.accion,
                        b.tabla_afectada,
                        b.fecha_hora,
                        b.id_usuario,
                        u.nombre AS nombre_usuario,
                        u.correo AS correo_usuario
                    FROM tb_bitacora b
                    LEFT JOIN tb_usuario u ON b.id_usuario = u.id_usuario
                    WHERE DATE(b.fecha_hora) >= ? 
                    AND DATE(b.fecha_hora) < ?
                    ORDER BY b.fecha_hora DESC";

            $consulta = $this->db->prepare($sql);
            $consulta->execute([$fechaInicio, $fechaFin]);

            return $consulta->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new Exception(
                'Error al consultar la bitácora: ' . $e->getMessage()
            );
        }
    }
}