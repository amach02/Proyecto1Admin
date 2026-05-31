<?php

class FrontController
{

    static function main()
    {
        require 'libs/View.php';
        require 'libs/configuration.php';
        require 'libs/Auth.php';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $config = Config::singleton();

        $controladorURL = (isset($_GET['controlador']) && $_GET['controlador'] != '') ? $_GET['controlador'] : 'Session';
        $nombreAccion   = (isset($_GET['accion'])      && $_GET['accion']      != '') ? $_GET['accion']      : 'mostrarLogin';
        $controllerName  = $controladorURL . 'Controller';
        $nombreAccion    = !empty($_GET['accion']) ? $_GET['accion'] : 'mostrarLogin';
        $rutaControlador = $config->get('controllerFolder') . $controllerName . '.php';

        // Verificar acceso ANTES de cargar cualquier controlador
        Auth::verificar($controladorURL, $nombreAccion);

        if (is_file($rutaControlador)) {
            require $rutaControlador;
        } else {
            die("<b>Error:</b> No existe el archivo <code>$rutaControlador</code>");
        }

        if (!class_exists($controllerName)) {
            die("<b>Error:</b> La clase <code>$controllerName</code> no existe.");
        }

        $controller = new $controllerName();

        if (method_exists($controller, $nombreAccion)) {
            $controller->$nombreAccion();
        } else {
            die("<b>Error:</b> El controlador <code>$controllerName</code> no tiene la función <code>$nombreAccion</code>.");
        }
    }
}
