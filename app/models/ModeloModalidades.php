<?php


class ModeloModalidades{

    private $db;


    public function __construct(){
        $this->db = new Base;
    } 
    
    /*
    public function obtenerTecnicosTablaClass($filas,$orden,$tipoOrden,$filaspagina){
        $this->db->query("SELECT id AS 'Nº', nombre AS 'Nombre', apellidos AS 'Apellidos',
                        correo AS 'Email', IF(telefono >0,telefono,0) AS 'Teléfono'
                        FROM usuarios WHERE activo = 1 AND rol=2
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");
                                           
        $resultado = $this->db->registros();

        return $resultado;
    }*/

    public function obtenerModalidadesTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond){
        $this->db->query("SELECT id AS 'Nº', modalidad AS 'Modalidad' 
                        FROM modalidadtecnico WHERE activo=1  $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosModalidades()
    {
        $this->db->query("SELECT count(*) AS contador FROM modalidadtecnico WHERE activo=1 ");
                        
        $fila = $this->db->registro();
        return $fila;
    }

    public function totalRegistrosModalidadesBuscar($cond)
    {
        $this->db->query("SELECT count(*) AS contador FROM modalidadtecnico WHERE activo=1 $cond ");
        $fila = $this->db->registro();
        return $fila;
    }
    
    public function insertarNuevaModalidad($datos)
    {                            
        $modalidad = $datos['modalidad'];        
        $activo = 1;

        $this->db->query("INSERT INTO modalidadtecnico (modalidad,activo) 
                        VALUES ('$modalidad','$activo' )");
        
        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return 0;
        }      

    }
 
    public function actualizarDatosModalidad($datos)
    {
        $id = $datos['id'];
        $modalidad = $datos['modalidad'];        

        $this->db->query("UPDATE modalidadtecnico 
                        SET  modalidad = '$modalidad'
                        WHERE id = '$id' ");
                        
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function eliminarModalidad($id)
    {
        $this->db->query("UPDATE modalidadtecnico SET activo = -1 WHERE id = $id ");
        if($this->db->execute()){
        return 1;
        }else {
        return 0;
        }
    }


}