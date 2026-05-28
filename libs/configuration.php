<?php
    require 'libs/Config.php';
    $config= Config::singleton();
    $config->set('controllerFolder','controller/');
    $config->set('modelFolder', 'model/');
    $config->set('viewFolder', 'view/');
    
    $config->set('dbhost', 'localhost'); // ip
    $config->set('dbname', 'db_examen1_alexanderMachado');
    $config->set('dbuser', 'root');
    $config->set('dbpass', '');
    
?>

