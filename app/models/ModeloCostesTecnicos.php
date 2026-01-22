<?php


class ModeloCostesTecnicos{

    private $db;


    public function __construct(){
        $this->db = new Base;
    } 

    public function obtenerCostesTecnicosTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond){
        $this->db->query("SELECT coste.id as 'pktabla',
                        coste.codigotecnico AS 'Código', usu.nombre AS 'Nombre', usu.apellidos AS 'Apellidos',
                        coste.mes AS 'Mes', coste.anio AS 'Año', coste.costehora AS 'Coste'
                        FROM costestecnicos coste 
                        LEFT JOIN usuarios usu ON coste.idtecnico=usu.id
                        WHERE 1 $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");
        
        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosCostesTecnicosBuscar($cond)
    {
        $this->db->query("SELECT COUNT(*) AS contador
                        FROM costestecnicos coste 
                        LEFT JOIN usuarios usu ON coste.idtecnico=usu.id 
                        WHERE 1  $cond  ");       
        $fila = $this->db->registro();
        return $fila;
    }
    
    public function listadoTecnicosActivos()
    {
        $this->db->query("SELECT id, nombre, apellidos
                        FROM usuarios 
                        WHERE activo =1 and rol =2
                        ORDER BY id DESC");

        $resultado = $this->db->registros();

        return $resultado;
    }
    public function codigoTecnicoPorIdUsuario($idUsuario)
    {
        $this->db->query("SELECT codigotecnico
        FROM usuarios 
        WHERE id = '$idUsuario' ");

        $resultado = $this->db->registro();

        return $resultado->codigotecnico;
    }

    
    public function insertarNuevoCosteTecnico($datos)
    {                            
                    
        $idtecnico = $datos['idtecnico'];
        $codigotecnico = $datos['codigotecnico'];
        $costehora = $datos['costehora'];
        $mes = $datos['mes'];
        $anio = $datos['anio'];
        $creacion = $datos['creacion'];
                
        $this->db->query("INSERT INTO costestecnicos (idtecnico,codigotecnico,costehora,mes,anio,creacion) 
                        VALUES ('$idtecnico','$codigotecnico','$costehora','$mes','$anio','$creacion')");
        
        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }      

    }



   
   
    public function obtenerDatosDetalleCoste($idCoste)
    {            
        $this->db->query("SELECT coste.*, usu.nombre, usu.apellidos
                        FROM costestecnicos coste 
                        LEFT JOIN usuarios usu ON coste.idtecnico=usu.id 
                        WHERE coste.id= '$idCoste' ");
        $fila = $this->db->registro();
        return $fila;
    }


   
    public function actualizarDatosCosteTecnico($idEditCoste,$costeHorasEditar)
    {   
        $this->db->query("UPDATE costestecnicos 
                        SET  costehora = '$costeHorasEditar'
                        WHERE id = '$idEditCoste' ");
                        
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function eliminarCosteTecnico($id)
    {
        $this->db->query("DELETE FROM costestecnicos WHERE id = $id ");
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function aniosConIncidencias()
    {
        $this->db->query("SELECT DISTINCT(YEAR(creacion)) as anio FROM incidencias ");
        $filas = $this->db->registros();
        return $filas;
    }
            
    public function borraCosteAsignadoMes($datos)
    {
        $idtecnico = $datos['idtecnico'];
        $mes = $datos['mes'];
        $anio = $datos['anio'];

        $this->db->query("DELETE FROM costestecnicos WHERE idtecnico = '$idtecnico' AND mes = '$mes' AND anio = '$anio' ");
        $this->db->execute();        
    }            

}