<?php

class View {
    
    public function __construct() {
        ;
    } // constructor
    
     public function show($nombreVista, $vars=array()){
         $config= Config::singleton();
         $path=$config->get('viewFolder').$nombreVista;
         
         if(is_file($path)==FALSE){
             trigger_error('Pagina ' . $path . 'No existe', E_USER_NOTICE);
             return FALSE;
         }
         
         if(is_array($vars)){
             extract($vars);
         } // if(is_array($vars))
         
         include $path;
         
     } // show
    
} // fin clase

?>
