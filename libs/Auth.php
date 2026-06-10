<?php
class Auth
{

    private static $permisos = array(

        'Administrador' => array(
            'Usuario'         => '*',
            'Infraestructura' => '*',
            'Gabinete'        => '*',
            'Gaveta'          => '*',
            'Caja'            => '*',
            'Vial'            => '*',
            'Planta'          => '*',
            'Fotografia'      => '*',
            'Inventario'      => '*',
            'Taxonomia'       => '*',
            'Orden'           => '*',
            'Familia'         => '*',
            'Genero'          => '*',
            'Especie'         => '*',
            'Especimen'       => '*',
            'eliminarFoto'     => '*',
            'Reporte'         => '*',
            'Index'           => '*',
            'Session'         => '*',
        ),

        'Curador' => array(
            'Especimen'  => array(
                'mostrarListar',
                'mostrarRegistrar',
                'registrar',
                'mostrarEditar',
                'editar',
                'buscarPorCodigo',
                'rutaFisica',
                'vincularPlanta',
                'mostrarDetalle'    // ← ver el detalle con carrusel
            ),
            'Fotografia' => array(
                'guardarFoto',      // ← puede subir
                'eliminarFoto',     // ← puede eliminar
                'listarFotos'
            ),
            'Taxonomia'  => array('mostrar'),
            'Orden'      => array(
                'mostrarListar',
                'mostrarRegistrar',
                'registrar',
                'mostrarEditar',
                'editar'
            ),
            'Familia'    => array(
                'mostrarListar',
                'mostrarRegistrar',
                'registrar',
                'mostrarEditar',
                'editar'
            ),
            'Genero'     => array(
                'mostrarListar',
                'mostrarRegistrar',
                'registrar',
                'mostrarEditar',
                'editar'
            ),
            'Especie'    => array(
                'mostrarListar',
                'mostrarRegistrar',
                'registrar',
                'mostrarEditar',
                'editar'
            ),
            'Inventario' => array('index', 'cargarGavetas', 'cargarCajas', 'cargarViales'),
            'Index'      => array('mostrar'),
            'Session'    => array('mostrarLogin', 'logout'),
        ),

        'Estudiante' => array(
            'Especimen'  => array(
                'mostrarListar',
                'buscarPorCodigo',
                'rutaFisica',
                'mostrarDetalle'    // ← ver el detalle con carrusel
            ),
            'Fotografia' => array(
                'listarFotos'       // ← solo ver, no puede subir ni eliminar
            ),
            'Taxonomia'  => array('mostrar'),
            'Inventario' => array('index', 'cargarGavetas', 'cargarCajas', 'cargarViales'),
            'Index'      => array('mostrar'),
            'Session'    => array('mostrarLogin', 'logout'),
        ),
    );

    private static $publicas = array(
        'Session' => array(
            'mostrarLogin',
            'login',
            'mostrarRecuperar',
            'recuperar',
            'mostrarNuevaContrasena',
            'cambiarContrasena'
        ),
    );

    public static function verificar($controlador, $accion)
    {;

        if (self::esPublica($controlador, $accion)) {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // LÍNEA TEMPORAL DE DEBUG — borrala después
        if ($controlador === 'Index' && $accion === 'mostrar') {
            if (!isset($_SESSION['id_usuario'])) {
                die('Auth está corriendo pero no hay sesión. FrontController sí carga Auth.php correctamente.');
            }
        }

        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ?controlador=Session&accion=mostrarLogin&status=sesion_requerida');
            exit;
        }

        $rol = isset($_SESSION['nombre_rol']) ? $_SESSION['nombre_rol'] : '';

        if (!self::tienePermiso($rol, $controlador, $accion)) {
            header('Location: ?controlador=Index&accion=mostrar&status=sin_permiso');
            exit;
        }
    }

    public static function tienePermiso($rol, $controlador, $accion)
    {
        if (!isset(self::$permisos[$rol])) {
            return false;
        }

        $mapa = self::$permisos[$rol];

        if (!isset($mapa[$controlador])) {
            return false;
        }

        if ($mapa[$controlador] === '*') {
            return true;
        }

        return in_array($accion, $mapa[$controlador]);
    }

    public static function esPublica($controlador, $accion)
    {
        if (!isset(self::$publicas[$controlador])) {
            return false;
        }
        return in_array($accion, self::$publicas[$controlador]);
    }

    public static function esAdmin()
    {
        return isset($_SESSION['nombre_rol']) && $_SESSION['nombre_rol'] === 'Administrador';
    }

    public static function esCurador()
    {
        return isset($_SESSION['nombre_rol']) && $_SESSION['nombre_rol'] === 'Curador';
    }

    public static function esEstudiante()
    {
        return isset($_SESSION['nombre_rol']) && $_SESSION['nombre_rol'] === 'Estudiante';
    }

    public static function rolActual()
    {
        return isset($_SESSION['nombre_rol']) ? $_SESSION['nombre_rol'] : '';
    }
}
