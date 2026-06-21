<?php

class EspecimenModel
{
    private $db;

    public function __construct()
    {
        require_once 'libs/SPDO.php';
        $this->db = SPDO::singleton();
    }

    public function buscarEspecimenPorId($id_especimen)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarEspecimenPorId(?)'
            );

            $consulta->execute(array($id_especimen));

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

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

    /*
     * Orden utilizado:
     * id, código, localización, fecha, estado, especie,
     * gaveta, vial y usuario.
     *
     * sp_editarEspecimen debe recibir los parámetros
     * exactamente en este mismo orden.
     */
    public function editarEspecimen(
        $id,
        $codigo,
        $localizacion,
        $fecha,
        $estado,
        $id_especie,
        $id_gaveta,
        $id_vial,
        $id_usuario_accion
    ) {
        try {
            $localizacion = empty($localizacion)
                ? null
                : $localizacion;

            $fecha = empty($fecha)
                ? null
                : $fecha;

            $id_especie = empty($id_especie)
                ? null
                : $id_especie;

            $id_gaveta = empty($id_gaveta)
                ? null
                : $id_gaveta;

            $id_vial = empty($id_vial)
                ? null
                : $id_vial;

            $consulta = $this->db->prepare(
                'CALL sp_editarEspecimen(
                    ?, ?, ?, ?, ?, ?, ?, ?, ?
                )'
            );

            $consulta->execute(
                array(
                    $id,
                    $codigo,
                    $localizacion,
                    $fecha,
                    $estado,
                    $id_especie,
                    $id_gaveta,
                    $id_vial,
                    $id_usuario_accion
                )
            );

            /*
             * Si el procedimiento devuelve Exito y Resultado,
             * se recuperan aquí.
             */
            $respuesta = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            /*
             * Si el procedimiento no devuelve un SELECT,
             * se devuelve true para mantener compatibilidad.
             */
            if ($respuesta === false) {
                return true;
            }

            return $respuesta;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al editar el espécimen: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function listarEspecimenes()
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_listar_especimenes()'
            );

            $consulta->execute();

            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array();
        }
    }

    public function buscarEspecimenPorCodigo($codigo_id)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_buscarEspecimenPorCodigo(?)'
            );

            $consulta->execute(array($codigo_id));

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarRutaFisica($id_especimen)
    {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_consultar_ruta_fisica(?)'
            );

            $consulta->execute(array($id_especimen));

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    /*
     * Orden utilizado:
     * código, localización, fecha, estado, especie,
     * vial, gaveta y usuario.
     *
     * Este orden coincide con el controlador corregido
     * y debe coincidir con sp_registrar_especimen.
     */
    public function registrarEspecimen(
        $codigo_id,
        $localizacion,
        $fecha,
        $estado,
        $id_especie,
        $id_vial,
        $id_gaveta,
        $id_usuario_accion
    ) {
        try {
            $localizacion = empty($localizacion)
                ? null
                : $localizacion;

            $fecha = empty($fecha)
                ? null
                : $fecha;

            $id_especie = empty($id_especie)
                ? null
                : $id_especie;

            $id_vial = empty($id_vial)
                ? null
                : $id_vial;

            $id_gaveta = empty($id_gaveta)
                ? null
                : $id_gaveta;

            $consulta = $this->db->prepare(
                'CALL sp_registrar_especimen(
                    ?, ?, ?, ?, ?, ?, ?, ?
                )'
            );

            /*
             * CORRECCIÓN:
             * antes se enviaba gaveta y luego vial.
             * Ahora se envía vial y luego gaveta.
             */
            $consulta->execute(
                array(
                    $codigo_id,
                    $localizacion,
                    $fecha,
                    $estado,
                    $id_especie,
                    $id_vial,
                    $id_gaveta,
                    $id_usuario_accion
                )
            );

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al registrar el espécimen: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }

    public function vincularPlanta(
        $id_especimen,
        $id_planta,
        $id_usuario_accion
    ) {
        try {
            $consulta = $this->db->prepare(
                'CALL sp_vincular_planta(?, ?, ?)'
            );

            $consulta->execute(
                array(
                    $id_especimen,
                    $id_planta,
                    $id_usuario_accion
                )
            );

            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

            while ($consulta->nextRowset()) {
            }

            $consulta->closeCursor();

            return $resultado;

        } catch (PDOException $e) {
            return array(
                'Resultado' => 'Error al vincular la planta: '
                    . $e->getMessage(),
                'Exito' => 0
            );
        }
    }
}
