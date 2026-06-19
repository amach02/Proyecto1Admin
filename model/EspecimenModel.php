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

    // Ejecutar el UPDATE
    public function editarEspecimen($id, $codigo, $localizacion, $fecha, $estado, $id_especie, $id_gaveta, $id_vial)
    {
        try {
            $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

            // fecha vacía → null
            $fecha = !empty($fecha) ? $fecha : null;
            // ids vacíos → null
            $id_especie = !empty($id_especie) ? $id_especie : null;
            $id_gaveta  = !empty($id_gaveta)  ? $id_gaveta  : null;
            $id_vial    = !empty($id_vial)    ? $id_vial    : null;

            $consulta = $this->db->prepare('CALL sp_editarEspecimen(?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $consulta->execute([
                $id,
                $codigo,
                $localizacion,
                $fecha,
                $estado,
                $id_especie,
                $id_gaveta,
                $id_vial,
                $id_usuario
            ]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return isset($resultado['Exito']) && $resultado['Exito'] == 1;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Ejecutar el INSERT (Adaptado a tus 5 parámetros)
    public function registrarEspecimen(
        $codigo_id,
        $localizacion_recoleccion,
        $fecha_recoleccion,
        $estado,
        $id_especie,
        $id_gaveta,
        $id_vial,
        $id_usuario_accion
    ) {
        try {

            $consulta = $this->db->prepare(
                'CALL sp_registrar_especimen(?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $consulta->execute([
                $codigo_id,
                $localizacion_recoleccion,
                $fecha_recoleccion,
                $estado,
                $id_especie,
                $id_gaveta,
                $id_vial,
                $id_usuario_accion
            ]);

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            $consulta->closeCursor();

            return $resultado;
        } catch (PDOException $e) {

            return [
                'Resultado' => $e->getMessage(),
                'Exito' => 0
            ];
        }
    }

    // Ejecutar INHABILITAR (Usa consulta directa para no tocar MySQL)
    public function inhabilitarEspecimen($id_especimen)
    {
        try {
            $consulta = $this->db->prepare("UPDATE tb_especimen SET estado = 'inactivo' WHERE id_especimen = ?");
            return $consulta->execute([$id_especimen]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarEspecimenes()
    {
        $consulta = $this->db->prepare('CALL sp_listar_especimenes()');
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function buscarEspecimenPorCodigo($codigo_id)
    {
        $consulta = $this->db->prepare('CALL sp_buscarEspecimenPorCodigo(?)');
        $consulta->execute([$codigo_id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function consultarRutaFisica($id_especimen)
    {
        $consulta = $this->db->prepare('CALL sp_consultar_ruta_fisica(?)');
        $consulta->execute([$id_especimen]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }

    public function vincularPlanta($id_especimen, $id_planta, $id_usuario)
    {
        try {
            $consulta = $this->db->prepare('CALL sp_vincular_planta(?, ?, ?)');
            $consulta->execute([$id_especimen, $id_planta, $id_usuario]);
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $consulta->closeCursor();
            return $resultado;
        } catch (PDOException $e) {
            return ['Resultado' => 'Error de conexión a la base de datos.', 'Exito' => 0];
        }
    }

    public function buscarEspecimenes($criterio)
    {
        $stmt = $this->db->prepare("CALL sp_buscar_especimenes(:criterio)");
        $stmt->bindParam(':criterio', $criterio, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $resultado;
    }
}
