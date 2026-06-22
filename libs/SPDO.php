<?php

class SPDO extends PDO
{

    private static $instance = null;

    public function __construct()
    {
        $config = Config::singleton();

        // Solo usamos la forma moderna: charset=utf8 directo en la cadena
        parent::__construct(
            'mysql:host=' . $config->get('dbhost') . ';dbname=' . $config->get('dbname') . ';charset=utf8',
            $config->get('dbuser'),
            $config->get('dbpass')
        );
    } // constructor

    public static function singleton()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    } // singleton

} // fin clase
