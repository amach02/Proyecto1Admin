<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'model/EspecimenModel.php';
$model = new EspecimenModel();
$metodo = $_SERVER['REQUEST_METHOD'];

function enviarRespuesta($codigoHttp, $mensaje, $datos = null)
{
    http_response_code($codigoHttp);
    echo json_encode(["status" => ($codigoHttp >= 200 && $codigoHttp < 300) ? "success" : "error", "message" => $mensaje, "data" => $datos]);
    exit();
}

try {
    switch ($metodo) {
        case 'GET':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $resultado = $model->buscarEspecimenPorId($_GET['id']);
                if ($resultado) {
                    enviarRespuesta(200, "Espécimen encontrado", $resultado);
                } else {
                    enviarRespuesta(404, "Espécimen no encontrado");
                }
            } else {
                $lista = $model->listarEspecimenes();
                enviarRespuesta(200, "Lista obtenida", $lista);
            }
            break;

        case 'POST':
            $datos = json_decode(file_get_contents("php://input"), true);

            if (!empty($datos['codigo_id'])) {
                // Mapeo exacto a tus 8 parámetros
                $res = $model->registrarEspecimen(
                    $datos['codigo_id'],
                    $datos['localizacion'] ?? null,
                    $datos['fecha'] ?? null,
                    $datos['estado'] ?? 'Activo',
                    $datos['id_especie'] ?? null,
                    $datos['id_gaveta'] ?? null,
                    $datos['id_vial'] ?? null,
                    $datos['id_usuario'] ?? 1 // Asumimos 1 o el ID de usuario que venga en el JSON
                );

                if ($res && isset($res['Exito']) && $res['Exito'] == 1) {
                    enviarRespuesta(201, "Espécimen registrado correctamente", $res);
                } else {
                    enviarRespuesta(500, "Error al registrar: " . ($res['Resultado'] ?? 'Desconocido'));
                }
            } else {
                enviarRespuesta(400, "El campo 'codigo_id' es obligatorio");
            }
            break;

        case 'PUT':
            $datos = json_decode(file_get_contents("php://input"), true);

            // Aquí es vital: asegúrate de enviar el id_especimen desde C#
            if (!empty($datos['id_especimen'])) {
                $res = $model->editarEspecimen(
                    $datos['id_especimen'],
                    $datos['codigo_id'],
                    $datos['localizacion'] ?? null,
                    $datos['fecha'] ?? null,
                    $datos['estado'] ?? 'Activo',
                    $datos['id_especie'] ?? null,
                    $datos['id_vial'] ?? null
                );

                if ($res) {
                    enviarRespuesta(200, "Espécimen actualizado exitosamente");
                } else {
                    enviarRespuesta(500, "Error al actualizar la base de datos");
                }
            } else {
                enviarRespuesta(400, "Se requiere 'id_especimen' para actualizar");
            }
            break;
        default:
            enviarRespuesta(405, "Método no permitido");
            break;
    }
} catch (Exception $e) {
    enviarRespuesta(500, "Error: " . $e->getMessage());
}
