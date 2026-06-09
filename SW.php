<?php
// Reporte de errores activado para facilitar la depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Encabezados requeridos para la API REST y CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// IMPORTACIONES
require_once 'libs/configuration.php';
require_once 'libs/SPDO.php';

require_once 'model/EspecimenModel.php';
require_once 'model/EspecieModel.php';
require_once 'model/GavetaModel.php';
require_once 'model/VialModel.php';

// FUNCIÓN DE RESPUESTA MEJORADA (Evita la pantalla negra por errores de tildes o eñes)
function enviarRespuesta($codigoHttp, $mensaje, $datos = null)
{
    http_response_code($codigoHttp);

    $arregloRespuesta = [
        "status" => ($codigoHttp >= 200 && $codigoHttp < 300) ? "success" : "error",
        "message" => $mensaje,
        "data" => $datos
    ];

    $json_codificado = json_encode($arregloRespuesta);

    if ($json_codificado === false) {
        // Si hay caracteres no admitidos (como UTF-8 mal formado), json_encode falla.
        // Aquí capturamos ese error para que no quede la pantalla en blanco.
        echo json_encode([
            "status" => "error",
            "message" => "Error interno al crear el JSON: " . json_last_error_msg() . ". Verifica que el charset=utf8 esté en tu SPDO.php",
            "data" => null
        ]);
    } else {
        echo $json_codificado;
    }

    exit();
}

$metodo = $_SERVER['REQUEST_METHOD'];

// LÓGICA PRINCIPAL PROTEGIDA
try {
    $model = new EspecimenModel();

    switch ($metodo) {
        case 'GET':
            if (isset($_GET['catalogo'])) {
                switch ($_GET['catalogo']) {
                    case 'especies':
                        $modelo = new EspecieModel();
                        enviarRespuesta(200, "Especies obtenidas", $modelo->listarEspecies());
                        break;
                    case 'gavetas':
                        $modelo = new GavetaModel();
                        enviarRespuesta(200, "Gavetas obtenidas", $modelo->listarGavetas(0));
                        break;
                    case 'viales':
                        $modelo = new VialModel();
                        enviarRespuesta(200, "Viales obtenidos", $modelo->listarVialesDisponibles());
                        break;
                }
                exit();
            }

            // Buscar un solo espécimen por ID
            if (isset($_GET['id'])) {
                $resultado = $model->buscarEspecimenPorId($_GET['id']);

                if ($resultado !== false && !empty($resultado)) {
                    enviarRespuesta(200, "Espécimen obtenido exitosamente", $resultado);
                } else {
                    enviarRespuesta(404, "Espécimen no encontrado");
                }
                break; // Importante para que no siga ejecutando lo de abajo
            }

            // LISTAR ESPECÍMENES (Si no pidieron catálogo ni ID)
            $resultado = $model->listarEspecimenes();
            if ($resultado !== false) {
                enviarRespuesta(200, "Especímenes obtenidos exitosamente", $resultado);
            } else {
                enviarRespuesta(500, "Error al obtener la lista de especímenes");
            }
            break;

        case 'POST':
            $datos = json_decode(file_get_contents("php://input"), true);
            $identificador = isset($datos['codigo_id']) ? $datos['codigo_id'] : null;

            if (!empty($identificador)) {
                $res = $model->registrarEspecimen(
                    $identificador,
                    isset($datos['localizacion_recoleccion']) ? $datos['localizacion_recoleccion'] : null,
                    isset($datos['fecha_recoleccion']) ? $datos['fecha_recoleccion'] : null,
                    isset($datos['estado']) ? $datos['estado'] : 'activo',
                    isset($datos['id_especie']) ? $datos['id_especie'] : null,
                    isset($datos['id_gaveta']) ? $datos['id_gaveta'] : null,
                    isset($datos['id_vial']) ? $datos['id_vial'] : null,
                    isset($datos['id_usuario']) ? $datos['id_usuario'] : 1
                );

                if ($res && isset($res['Exito']) && $res['Exito'] == 1) {
                    enviarRespuesta(201, "Espécimen registrado correctamente", $res);
                } else {
                    $errorMsg = isset($res['Resultado']) ? $res['Resultado'] : 'Desconocido';
                    enviarRespuesta(500, "Error al registrar: " . $errorMsg);
                }
            } else {
                enviarRespuesta(400, "El campo 'codigo_id' es obligatorio");
            }
            break;

        case 'PUT':
            $datos = json_decode(file_get_contents("php://input"), true);
            $identificador = isset($datos['codigo_id']) ? $datos['codigo_id'] : null;

            if (!empty($datos['id_especimen'])) {
                $res = $model->editarEspecimen(
                    $datos['id_especimen'],
                    $identificador,
                    isset($datos['id_gaveta']) ? $datos['id_gaveta'] : null,
                    isset($datos['id_vial']) ? $datos['id_vial'] : null,
                    isset($datos['id_usuario']) ? $datos['id_usuario'] : 1
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

        case 'DELETE':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                $datos = json_decode(file_get_contents("php://input"), true);
                if (isset($datos['id_especimen'])) {
                    $id = $datos['id_especimen'];
                }
            }

            if ($id) {
                $res = $model->inhabilitarEspecimen($id);
                if ($res) {
                    enviarRespuesta(200, "Espécimen inhabilitado (trazabilidad conservada)");
                } else {
                    enviarRespuesta(500, "No se pudo inhabilitar.");
                }
            } else {
                enviarRespuesta(400, "ID requerido");
            }
            break;

        default:
            enviarRespuesta(405, "Método no permitido");
            break;
    }
} catch (Throwable $e) {
    enviarRespuesta(500, "Error crítico del servidor: " . $e->getMessage() . " en el archivo " . $e->getFile() . " línea " . $e->getLine());
}
