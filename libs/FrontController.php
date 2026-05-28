<?php

class FrontController {

    static function main() {
        require 'libs/View.php';
        require 'libs/configuration.php';

        $config = Config::singleton();

        // 1. Obtener nombre del controlador (Ej: Retiro)
        $controladorURL = !empty($_GET['controlador']) ? $_GET['controlador'] : 'Index';
        $controllerName = $controladorURL . 'Controller';

        // 2. Obtener nombre de la acción (Ej: procesarRetiro)
        $nombreAccion = !empty($_GET['accion']) ? $_GET['accion'] : 'mostrar';

        // 3. Ruta del archivo
        $rutaControlador = $config->get('controllerFolder') . $controllerName . '.php';

        if (is_file($rutaControlador)) {
            require $rutaControlador;
        } else {
            die("<b>Error:</b> No existe el archivo <code>$rutaControlador</code>");
        }

        // 4. Validar si la clase existe
        if (!class_exists($controllerName)) {
            die("<b>Error:</b> La clase <code>$controllerName</code> no existe dentro del archivo.");
        }

        $controller = new $controllerName();

        // 5. Validar si la FUNCIÓN existe (Aquí es donde te daba el error 40)
        if (method_exists($controller, $nombreAccion)) {
            $controller->$nombreAccion();
        } else {
            die("<b>Error:</b> El controlador <code>$controllerName</code> no tiene la función <code>$nombreAccion</code>. <br> 
                 <i>Sugerencia: Revisa que en el archivo dice 'public function $nombreAccion'</i>");
        }
    }
}
