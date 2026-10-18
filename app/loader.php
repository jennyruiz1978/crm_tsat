<?php
//Cargamos la libreria
require_once(__DIR__ . '/config/configurar.php');

require_once(__DIR__ . '/helpers/url_helpers.php');


//Autoload php
spl_autoload_register(function($nombreClase){
    $archivo = __DIR__ . '/librerias/' . $nombreClase . '.php';
    if (file_exists($archivo)) {
        require_once($archivo);
    }
});