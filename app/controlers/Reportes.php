<?php

class Reportes extends Controlador {
   
    public function __construct() {
        session_start();
        $this->controlPermisos();
        $this->ModelReportes = $this->modelo('ModeloReportes');
    
    }

    public function index()
    {                
        $datos = [];  
        
            $datos = [];
            $this->vista('reportes/listadoClientesBolsaHoras',$datos);
        
    }

    public function crearTablaClientesBolsaHoras()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $buscar = $_POST['busqueda'];
            $filas = $_POST['filas'];
            $pagina = $_POST['pagina'];
            $orden = $_POST['orden'];
            $tipoOrden = $_POST['tipoOrden'];                
        }
        
        $cond = '';      

        $filaspagina = $filas * $pagina;
    
        if ($buscar != "") {            
            $datos = json_decode($buscar);            
            $cond .= $this->construirCondicionesBuscar($datos);   //falta construir 
        }
        $resultado = $this->ModelReportes->obtenerClientesBolsaHoras($filas,$orden,$filaspagina,$tipoOrden,$cond);      
        print(json_encode($resultado));  
    }
    public function totalRegistrosClientesBolsaHoras()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $buscar = $_POST['busqueda'];               
        }

        $cond = '';        
    
        if ($buscar != "") {                           
            $datos = json_decode($buscar);            
            $cond .= $this->construirCondicionesBuscar($datos);     //falta construir       
        }

        $resultado = $this->ModelReportes->totalRegistrosClientesBolsaHoras($cond);      
        print(json_encode($resultado));
    }

    public function construirCondicionesBuscar($datos)
    {
        $tamanio = count((array) $datos);
        $cond = '';
        if ($tamanio > 0) {                                
            $cont = 0;
            $cond = " AND  (";
            foreach ($datos as $key => $value) {

                $cont++;                   
                
                if ($cont < ($tamanio) ) {                    
                    $y =  " LIKE " . "'%$value%'" . " AND ";
                } else {                    
                    $y =  " LIKE " . "'%$value%'" . ") ";
                }
                if ($key == 'Nº') {
                    $cond .= "inc.id" . $y;
                }
                if ($key == 'Creación') {
                        
                    $fechaEstandar = " DATE_FORMAT( inc.creacion, '%d/%m/%Y' ) LIKE '%".$value."%' ";
                    
                    if ($cont < ($tamanio) ) {                    
                        $m =  " AND ";
                    } else {                    
                        $m =  " ) ";
                    }

                    $cond .= $fechaEstandar . $m;
                }
                if ($key == 'Usuario') {
                    $cond .= " CONCAT(usu.nombre, ' ', usu.apellidos) " . $y;
                } 
                if ($key == 'Cliente') {
                    $cond .= "cli.nombre" . $y;
                }
                if ($key == 'Sucursal') {
                    $cond .= "suc.nombre" . $y;
                }
                if ($key == 'Equipo') {
                    $cond .= "equ.nombre" . $y;
                }                    
                if ($key == 'Estado') {

                
                    $estados = ["pendiente" => " inc.estado=1 ", 
                                "en curso" => " inc.estado=2 " ,
                                "terminada" => " inc.estado=3 "];
                                            
                    $condEstado = ' ';
                    $numEstados = 0;
                    $arrEstados = [];

                    foreach ($estados as $estado => $parte) {                       

                        $pos = stripos($estado, $value);
                        if ($pos !== false) {                        
                            $numEstados++;
                            $arrEstados[] = $parte;
                        }
                    }     

                    if ($arrEstados && count($arrEstados) >0 ) {

                        $b = 0;
                        foreach ($arrEstados as $est) {
                            $b++;  
                            if ($b < $numEstados) {
                                $condEstado .=  $est . " OR ";
                            } else {
                                $condEstado .=  $est . " ";
                            }   
                        }
                    }

                    if ($cont < ($tamanio) ) {                    
                        $z =  " AND ";
                    } else {                    
                        $z =  " ) ";
                    }

                    $cond .= $condEstado . $z;

                }
                if ($key == 'Técnicos') {
                    $cond .= "inc.nombrestecnicos" . $y;
                }
            }                                        
        }

        return $cond;
    }
}
