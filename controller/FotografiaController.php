<?php
require_once 'model/FotografiaModel.php';

class FotografiaController
{
    private $model;

    public function __construct()
    {
        $this->model = new FotografiaModel();
    }

    /**
     * Recibe la URL de Cloudinary enviada por AJAX (fetch desde JS)
     * y la guarda en la base de datos.
     * Responde con JSON.
     */

    /**
     * Devuelve en JSON las fotos de un espécimen (usado internamente
     * si se necesita recargar el carrusel vía AJAX).
     */
    public function listarFotos()
    {
        $id_especimen = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id_especimen <= 0) {
            http_response_code(400);
            echo json_encode([]);
            exit;
        }

        header('Content-Type: application/json');
        $fotos = $this->model->listarFotosPorEspecimen($id_especimen);
        echo json_encode($fotos);
        exit;
    }

    public function guardarFoto()
    {
        // Leer FormData (igual que ya funciona)
        $ruta         = isset($_POST['ruta'])         ? trim($_POST['ruta'])         : '';
        $id_especimen = isset($_POST['id_especimen']) ? intval($_POST['id_especimen']) : 0;
        $formato      = isset($_POST['formato'])      ? strtolower(trim($_POST['formato'])) : '';
        $id_usuario   = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;

        if (empty($ruta) || $id_especimen <= 0) {
            header("Location: ?controlador=Especimen&accion=mostrarDetalle&id=$id_especimen&status=foto_error");
            exit;
        }

        $formatosPermitidos = array('png', 'jpg', 'jpeg');
        if (!in_array($formato, $formatosPermitidos)) {
            header("Location: ?controlador=Especimen&accion=mostrarDetalle&id=$id_especimen&status=foto_formato_error");
            exit;
        }

        $respuesta = $this->model->registrarFotografia($ruta, $id_especimen, $id_usuario);

        if (isset($respuesta['Exito']) && $respuesta['Exito'] == 1) {
            header("Location: ?controlador=Especimen&accion=mostrarDetalle&id=$id_especimen&status=foto_guardada");
        } else {
            header("Location: ?controlador=Especimen&accion=mostrarDetalle&id=$id_especimen&status=foto_error");
        }
        exit;
    }

    public function eliminarFoto()
    {
        $id_fotografia = isset($_POST['id_fotografia']) ? intval($_POST['id_fotografia']) : 0;
        $id_especimen  = isset($_POST['id_especimen'])  ? intval($_POST['id_especimen'])  : 0;
        $id_usuario    = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;

        if ($id_fotografia <= 0) {
            header("Location: ?controlador=Especimen&accion=mostrarDetalle&id=$id_especimen&status=foto_error");
            exit;
        }

        $respuesta = $this->model->eliminarFotografia($id_fotografia, $id_usuario);

        if (isset($respuesta['Exito']) && $respuesta['Exito'] == 1) {
            header("Location: ?controlador=Especimen&accion=mostrarDetalle&id=$id_especimen&status=foto_eliminada");
        } else {
            header("Location: ?controlador=Especimen&accion=mostrarDetalle&id=$id_especimen&status=foto_error");
        }
        exit;
    }
}
