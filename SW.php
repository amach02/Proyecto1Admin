<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'libs/configuration.php';
require_once 'libs/SPDO.php';

require_once 'model/EspecimenModel.php';
require_once 'model/EspecieModel.php';
require_once 'model/GavetaModel.php';
require_once 'model/VialModel.php';
require_once 'model/ComentarioModel.php';
require_once 'model/PlantaModel.php'; // NUEVO: Importamos el modelo de plantas

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
        echo json_encode([
            "status" => "error",
            "message" => "Error interno al crear el JSON. Verifica que el charset=utf8 esté en tu SPDO.php",
            "data" => null
        ]);
    } else {
        echo $json_codificado;
    }
    exit();
}

$metodo = $_SERVER['REQUEST_METHOD'];

try {
    $model = new EspecimenModel();

    switch ($metodo) {
        case 'GET':
            // NUEVO: Endpoints para Plantas (HU14)
            if (isset($_GET['accion'])) {
                $modPlanta = new PlantaModel();
                
                if ($_GET['accion'] === 'listar_plantas') {
                    enviarRespuesta(200, "Catálogo obtenido", $modPlanta->listarPlantas());
                }
                
                if ($_GET['accion'] === 'plantas_vinculadas' && isset($_GET['id_especimen'])) {
                    enviarRespuesta(200, "Plantas vinculadas", $modPlanta->listarPlantasPorEspecimen($_GET['id_especimen']));
                }
            }

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
                    case 'comentarios':
                        $modelo = new ComentarioModel();
                        if (isset($_GET['id_especimen'])) {
                            enviarRespuesta(200, "Comentarios obtenidos", $modelo->listarComentariosPorEspecimen($_GET['id_especimen']));
                        } else {
                            enviarRespuesta(400, "Se requiere el id_especimen para listar comentarios");
                        }
                        break;
                }
                exit();
            }

            if (isset($_GET['buscar'])) {
                $criterio = trim($_GET['buscar']);
                if (empty($criterio)) {
                    enviarRespuesta(400, "El criterio de búsqueda no puede estar vacío.");
                }
                $resultado = $model->buscarEspecimenes($criterio);
                enviarRespuesta(200, "Búsqueda completada", $resultado);
                break;
            }

            if (isset($_GET['id'])) {
                $resultado = $model->buscarEspecimenPorId($_GET['id']);
                if ($resultado !== false && !empty($resultado)) {
                    require_once 'model/FotografiaModel.php';
                    $modFoto = new FotografiaModel();
                    $resultado['fotografias'] = $modFoto->listarFotosPorEspecimen($_GET['id']);
                    enviarRespuesta(200, "Espécimen obtenido exitosamente", $resultado);
                } else {
                    enviarRespuesta(404, "Espécimen no encontrado");
                }
                break;
            }

            $resultado = $model->listarEspecimenes();
            if ($resultado !== false) {
                enviarRespuesta(200, "Especímenes obtenidos exitosamente", $resultado);
            } else {
                enviarRespuesta(500, "Error al obtener la lista de especímenes");
            }
            break;

        case 'POST':
            // Verificamos si la petición viene de un formulario (x-www-form-urlencoded) o si es JSON crudo
            // Para poder manejar los $_GET y peticiones con Query Params
            
            // NUEVO: Endpoint para Vincular Planta (HU14)
            if (isset($_GET['accion']) && $_GET['accion'] === 'vincular_planta') {
                $datos = json_decode(file_get_contents("php://input"), true);
                $modPlanta = new PlantaModel();
                
                $id_esp = $datos['id_especimen'];
                $id_pl = $datos['id_planta'];
                $id_usu = isset($datos['id_usuario']) ? $datos['id_usuario'] : 1;
                
                $res = $modPlanta->vincularPlanta($id_esp, $id_pl, $id_usu);
                if ($res && isset($res['Exito']) && $res['Exito'] == 1) {
                    enviarRespuesta(201, "Planta vinculada correctamente", $res);
                } else {
                    $errorMsg = isset($res['Resultado']) ? $res['Resultado'] : 'Desconocido';
                    enviarRespuesta(500, "Error al vincular: " . $errorMsg);
                }
            }

            $datos = json_decode(file_get_contents("php://input"), true);

            // 1. Lógica de Login
            if (isset($datos['accion']) && $datos['accion'] === 'login') {
                require_once 'model/UsuarioModel.php';
                $modUsuario = new UsuarioModel();

                $correo = isset($datos['correo']) ? $datos['correo'] : '';
                $contrasena = isset($datos['contrasena']) ? $datos['contrasena'] : '';

                $usuario = $modUsuario->autenticarUsuario($correo);

                if (!$usuario || $usuario['id_usuario'] === null) {
                    enviarRespuesta(401, "El correo no está registrado.");
                }

                if ($usuario['estado'] === 'inhabilitado') {
                    enviarRespuesta(403, "Tu cuenta está inhabilitada.");
                }

                if (hash('sha256', $contrasena) !== $usuario['contrasena_hash']) {
                    enviarRespuesta(401, "La contraseña es incorrecta.");
                }

                $nombre_rol = isset($usuario['nombre_rol']) ? $usuario['nombre_rol'] : 'Estudiante';

                enviarRespuesta(200, "Login exitoso", [
                    'id_usuario' => $usuario['id_usuario'],
                    'nombre' => $usuario['nombre'],
                    'nombre_rol' => $nombre_rol
                ]);
                break;
            }

            // 2. Lógica para Registrar Comentarios
            if (isset($datos['comentario']) && isset($datos['id_especimen'])) {
                $modComentario = new ComentarioModel();
                $id_usuario = isset($datos['id_usuario']) ? $datos['id_usuario'] : 1;
                $res = $modComentario->registrarComentario(
                    $datos['id_especimen'],
                    $id_usuario,
                    $datos['comentario']
                );

                if ($res && isset($res['Exito']) && $res['Exito'] == 1) {
                    enviarRespuesta(201, "Comentario publicado", $res);
                } else {
                    $mensajeError = isset($res['Resultado']) ? $res['Resultado'] : '';
                    enviarRespuesta(500, "Error al publicar: " . $mensajeError);
                }
                break;
            }

            // 3. Lógica para Registrar Espécimen
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
            // NUEVO: Endpoint para Desvincular Planta (HU14)
            if (isset($_GET['accion']) && $_GET['accion'] === 'desvincular_planta') {
                $datos = json_decode(file_get_contents("php://input"), true);
                $modPlanta = new PlantaModel();
                
                $res = $modPlanta->desvincularPlanta($datos['id_especimen'], $datos['id_planta']);
                if ($res) {
                    enviarRespuesta(200, "Planta desvinculada correctamente");
                } else {
                    enviarRespuesta(500, "Error interno al intentar desvincular");
                }
            }

            // Lógica original de inhabilitar espécimen
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
                    enviarRespuesta(200, "Espécimen inhabilitado");
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
} catch (Exception $e) {
    enviarRespuesta(500, "Error crítico: " . $e->getMessage());
}