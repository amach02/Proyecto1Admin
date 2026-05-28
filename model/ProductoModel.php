<?php

class ProductoModel
{

    private $db;

    public function __construct()
    {

        require_once 'libs/SPDO.php';

        $this->db = SPDO::singleton();
    }

    // Registrar producto
    public function registrarProducto(
        $codigo,
        $marca,
        $descripcion,
        $precio
    ) {

        try {

            $consulta = $this->db->prepare(
                'CALL sp_registrarProducto(?, ?, ?, ?)'
            );

            $resultado = $consulta->execute([
                $codigo,
                $marca,
                $descripcion,
                $precio
            ]);

            $consulta->closeCursor();

            return $resultado;
        } catch (PDOException $e) {

            return false;
        }
    }

    // Buscar producto por marca
    public function buscarProducto($marca)
    {

        $consulta = $this->db->prepare(
            'CALL sp_buscarProducto(?)'
        );

        $consulta->execute([$marca]);

        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta->closeCursor();

        return $resultado;
    }

    // Validar código repetido
    public function existeCodigo($codigo)
    {

        $consulta = $this->db->prepare(
            'SELECT codigo FROM producto WHERE codigo = ?'
        );

        $consulta->execute([$codigo]);

        $resultado = $consulta->fetch();

        $consulta->closeCursor();

        return $resultado;
    }

    // Listar productos
    public function listarProductos()
    {
        $consulta = $this->db->prepare(
            'CALL sp_listarProductos()'
        );
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta->closeCursor();
        return $resultado;
    }
}
