<?php
require_once 'model/EspecimenModel.php';
require_once 'model/FotografiaModel.php';

class EspecimenController
{
    private $view;
    private $model;

    public function __construct()
    {
        require_once 'libs/View.php';
        $this->view = new View();
        $this->model = new EspecimenModel();
    }

    // Carga la interfaz pasándole los datos recuperados por ID
    public function mostrarEditar()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Index&accion=mostrar&status=error');
            return;
        }

        $id = $_GET['id'];

        $datosEspecimen = $this->model->buscarEspecimenPorId($id);

        if ($datosEspecimen) {
            require_once 'model/EspecieModel.php';
            // NUEVO: Requerimos los modelos de ubicación para que puedas cambiarlos al editar
            require_once 'model/VialModel.php';
            require_once 'model/GavetaModel.php';

            $listaEspecies = (new EspecieModel())->listarEspecies();
            // NUEVO: Obtenemos las listas para los dropdowns de la vista de edición
            $listaViales   = (new VialModel())->listarVialesDisponibles();
            $listaGavetas  = (new GavetaModel())->listarGavetas(); // Asegúrate de tener este método en GavetaModel

            $this->view->show('editarEspecimenView.php', [
                'especimen' => $datosEspecimen,
                'especies'  => $listaEspecies,
                'viales'    => $listaViales,   // NUEVO
                'gavetas'   => $listaGavetas   // NUEVO
            ]);
        } else {
            header('Location: ?controlador=Index&accion=mostrar&status=no_encontrado');
        }
    }

    // Procesa el envío del formulario (Método POST)
    public function editar()
    {
        $id           = trim($_POST['id_especimen']);
        $codigo       = trim($_POST['codigo_id']);
        $localizacion = trim($_POST['localizacion_recoleccion']);
        $fecha        = trim($_POST['fecha_recoleccion']);
        $estado       = trim($_POST['estado']);
        $id_especie   = trim($_POST['id_especie']);

        // NUEVO: Capturamos ambas opciones de almacenamiento, usando isset por si vienen vacías
        $id_gaveta    = isset($_POST['id_gaveta']) ? trim($_POST['id_gaveta']) : '';
        $id_vial      = isset($_POST['id_vial']) ? trim($_POST['id_vial']) : '';

        // Validación técnica en servidor del único campo estrictamente obligatorio
        if (empty($id) || empty($codigo)) {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id&status=invalido");
            return;
        }

        // NUEVO: Agregamos $id_gaveta a la llamada del modelo
        $resultado = $this->model->editarEspecimen($id, $codigo, $localizacion, $fecha, $estado, $id_especie, $id_gaveta, $id_vial);

        if ($resultado) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=especimen_editado_success');
        } else {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id&status=error");
        }
    }

    public function mostrarListar()
    {
        $especimenes = $this->model->listarEspecimenes();
        $this->view->show('listarEspecimenesView.php', ['especimenes' => $especimenes]);
    }

    public function mostrarRegistrar()
    {
        // 1. Llamamos a los modelos de Infraestructura
        require_once 'model/GavetaModel.php';
        require_once 'model/VialModel.php';
        require_once 'model/EspecieModel.php';
        
        // 2. Traemos las listas de los contenedores finales
        $gavetas = (new GavetaModel())->listarGavetas();
        $viales  = (new VialModel())->listarVialesDisponibles();
        $especies = (new EspecieModel())->listarEspecies();
        
        // 3. Enviamos estas variables a la vista del espécimen
        require_once 'libs/View.php';
        $view = new View();
        $view->show('registrarEspecimenView.php', array(
            'gavetas'  => $gavetas,
            'viales'   => $viales,
            'especies' => $especies
        ));
    }

    public function registrar()
    {
        try {
            // 1. Recibir datos del formulario
            $codigo_id         = trim($_POST['codigo_id']);
            $fecha_recoleccion = !empty($_POST['fecha_recoleccion']) ? $_POST['fecha_recoleccion'] : null;
            $localizacion      = trim($_POST['localizacion_recoleccion']);
            $estado            = $_POST['estado'];
            $id_especie        = !empty($_POST['id_especie']) ? $_POST['id_especie'] : null;
            $id_usuario_accion = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

            // 2. Validar campo obligatorio
            if (empty($codigo_id)) {
                header("Location: ?controlador=Especimen&accion=mostrarRegistrar&status=invalido");
                exit;
            }

            // 3. Decidir contenedor según selección
            $tipo_contenedor = isset($_POST['tipo_contenedor']) ? $_POST['tipo_contenedor'] : '';
            $id_gaveta       = null;
            $id_vial         = null;

            if ($tipo_contenedor === 'gabinete') {
                $id_gaveta = !empty($_POST['id_gaveta']) ? $_POST['id_gaveta'] : null;
            } else if ($tipo_contenedor === 'caja') {
                $id_vial = !empty($_POST['id_vial']) ? $_POST['id_vial'] : null;
            }

            // 4. Guardar el espécimen en BD
            $resultado = $this->model->registrarEspecimen(
                $codigo_id,
                $localizacion,
                $fecha_recoleccion,
                $estado,
                $id_especie,
                $id_gaveta,
                $id_vial,
                $id_usuario_accion
            );

            

            if (isset($resultado['Exito']) && $resultado['Exito'] == 1) {
                $id_nuevo = $resultado['id_nuevo_especimen'];

                // 5. Guardar fotos si se subieron desde el widget
                if (!empty($_POST['fotos']) && is_array($_POST['fotos'])) {
                    require_once 'model/FotografiaModel.php';
                    $fotoModel = new FotografiaModel();
                    foreach ($_POST['fotos'] as $url_foto) {
                        $url_foto = trim($url_foto);
                        if (!empty($url_foto)) {
                            $fotoModel->registrarFotografia($url_foto, $id_nuevo, $id_usuario_accion);
                        }
                    }
                }

                header("Location: ?controlador=Especimen&accion=mostrarListar&status=registrado_success");
            } else {
                header("Location: ?controlador=Especimen&accion=mostrarRegistrar&status=error");
            }
            exit;
        } catch (Exception $e) {
            die("<strong>ERROR FATAL AL GUARDAR:</strong> " . $e->getMessage());
        }
    }

    public function buscarPorCodigo()
    {
        $codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';

        if (empty($codigo)) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=invalido');
            return;
        }

        $especimen = $this->model->buscarEspecimenPorCodigo($codigo);
        $this->view->show('listarEspecimenesView.php', [
            'especimenes' => $especimen ? [$especimen] : [],
            'busqueda'    => $codigo
        ]);
    }

    public function rutaFisica()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=error');
            return;
        }

        $id   = $_GET['id'];
        $ruta = $this->model->consultarRutaFisica($id);
        $this->view->show('rutaFisicaView.php', ['ruta' => $ruta]);
    }

    public function vincularPlanta()
    {
        $id_especimen = trim($_POST['id_especimen']);
        $id_planta    = trim($_POST['id_planta']);
        $id_usuario   = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

        if (empty($id_especimen) || empty($id_planta)) {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id_especimen&status=invalido");
            return;
        }

        $respuesta = $this->model->vincularPlanta($id_especimen, $id_planta, $id_usuario);

        if ($respuesta['Exito'] == 1) {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id_especimen&status=planta_vinculada");
        } else {
            header("Location: ?controlador=Especimen&accion=mostrarEditar&id=$id_especimen&status=error_vinculo");
        }
    }

    public function mostrarDetalle()
    {
        if (!isset($_GET['id'])) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=error');
            return;
        }

        $id        = intval($_GET['id']);
        $especimen = $this->model->buscarEspecimenPorId($id);

        if (!$especimen) {
            header('Location: ?controlador=Especimen&accion=mostrarListar&status=no_encontrado');
            return;
        }

        $fotografiaModel = new FotografiaModel();
        $fotos = $fotografiaModel->listarFotosPorEspecimen($id);

        $this->view->show('detalleEspecimenView.php', [
            'especimen' => $especimen,
            'fotos'     => $fotos,
        ]);
    }
}
