<?php


class ModeloModalidadesMantto{

    private $db;


    public function __construct(){
        $this->db = new Base;
    } 
       
    public function obtenerModalidadesTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond){
        $this->db->query("SELECT id AS 'Nº', modalidad AS 'Modalidad' 
                        FROM modalidadesmantto WHERE activo=1  $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosModalidades()
    {
        $this->db->query("SELECT count(*) AS contador FROM modalidadesmantto WHERE activo=1 ");
                        
        $fila = $this->db->registro();
        return $fila;
    }

    public function totalRegistrosModalidadesBuscar($cond)
    {
        $this->db->query("SELECT count(*) AS contador FROM modalidadesmantto WHERE activo=1 $cond ");
        $fila = $this->db->registro();
        return $fila;
    }
    
    public function insertarNuevaModalidad($datos)
    {                            
        $modalidad = $datos['modalidad'];        
        $activo = 1;

        $this->db->query("INSERT INTO modalidadesmantto (modalidad,activo) 
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

        $this->db->query("UPDATE modalidadesmantto 
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
        $this->db->query("DELETE FROM modalidadesmantto WHERE id = $id ");
        if($this->db->execute()){
        return 1;
        }else {
        return 0;
        }
    }


}