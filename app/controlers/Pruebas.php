<?php

class Pruebas extends Controlador
{



    public function __construct()
    {
        session_start();
        $this->controlPermisos();
    }

    public function index($msg = 0)
    {



        $datos = [
            // "permisos" => $permisos
        ];

        $this->vista('pruebas/pruebas', $datos);
    }

    public function traer()
    {
        
        $input = json_decode(file_get_contents("php://input"), true);

        $nombre = $input['nombre'];
        $apellidos = $input['apellidos'];
        $salida = ["nombre" => $nombre, "apellidos" => $apellidos];
        
        print_r(json_encode($salida));
    }
}