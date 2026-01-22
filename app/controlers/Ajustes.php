<?php

class Ajustes extends Controlador {

   

    public function __construct() {
        session_start();
        $this->controlPermisos();
    
    }

    public function index($msg=0) {
        
        

        $datos = [
           // "permisos" => $permisos
        ];
       
        $this->vista('ajustes/clientes',$datos);
    }

    public function tecnicos($msg=0) {
        
        

        $datos = [
           // "permisos" => $permisos
        ];
       
        $this->vista('ajustes/tecnicos',$datos);
    }



   
}