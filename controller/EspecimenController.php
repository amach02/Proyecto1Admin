<?php
require_once 'model/EspecimenModel.php';

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
        $gavetas  = (new GavetaModel())->listarGavetas();
        $viales   = (new VialModel())->listarVialesDisponibles();
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
        // 1. Mostrar errores temporalmente
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        try {
            // 2. Recibir los datos fijos
            $codigo_id = trim($_POST['codigo_id']);
            $fecha_recoleccion = !empty($_POST['fecha_recoleccion']) ? $_POST['fecha_recoleccion'] : null;
            $localizacion = trim($_POST['localizacion_recoleccion']);
            $estado = $_POST['estado'];
            $id_especie = !empty($_POST['id_especie']) ? $_POST['id_especie'] : null;

            // 3. RECIBIR LA NUEVA LÓGICA DINÁMICA (Gabinete o Caja)
            $tipo_contenedor = isset($_POST['tipo_contenedor']) ? $_POST['tipo_contenedor'] : '';
            $id_gaveta = (!empty($_POST['id_gaveta'])) ? $_POST['id_gaveta'] : null;
            $id_vial   = (!empty($_POST['id_vial'])) ? $_POST['id_vial'] : null;

            // 4. Decidir cuál se va a guardar en la base de datos
            if ($tipo_contenedor === 'gabinete') {
                // Llamas a tu modelo y le pasas $id_gaveta (y dejas el vial en null)
            } else if ($tipo_contenedor === 'caja') {
                // Llamas a tu modelo y le pasas $id_vial (y dejas la gaveta en null)
            }

            // 5. ¡LA REDIRECCIÓN! (Esto evita que se quede la pantalla en blanco al final)
            header("Location: ?controlador=Especimen&accion=mostrarRegistrar&status=registrado_success");
            exit();

        } catch (Exception $e) {
            // Si la Base de Datos falla, que lo imprima en vez de dar pantalla blanca
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
}
?>