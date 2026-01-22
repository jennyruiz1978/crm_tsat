<?php


class ModeloTiposIva{

    private $db;


    public function __construct(){
        $this->db = new Base;
    }       

    public function obtenerTiposIvaTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond){
        $this->db->query("SELECT id AS 'Nº', tipoiva AS 'Tipo IVA (%)', activo as 'estado' 
                        FROM tiposiva WHERE 1  $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosTiposIva()
    {
        $this->db->query("SELECT count(*) AS contador FROM tiposiva ");
                        
        $fila = $this->db->registro();
        return $fila;
    }

    public function totalRegistrosTiposIvaBuscar($cond)
    {
        $this->db->query("SELECT count(*) AS contador FROM tiposiva WHERE 1 $cond ");
        $fila = $this->db->registro();
        return $fila;
    }
    
    public function insertarNuevaTipoIva($datos)
    {                            
        $tipoiva = $datos['tipoiva'];        
        $activo = 1;

        $this->db->query("INSERT INTO tiposiva (tipoiva,activo) 
                        VALUES ('$tipoiva','$activo' )");
        
        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return 0;
        }      

    }
 
    public function actualizarDatosTipoIva($datos)
    {
        $id = $datos['id'];
        $tipoiva = $datos['tipoiva'];        

        $this->db->query("UPDATE tiposiva 
                        SET  tipoiva = '$tipoiva'
                        WHERE id = '$id' ");
                        
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function inactivarTipoIva($id)
    {
        $this->db->query("UPDATE tiposiva SET activo = 'inactivo' WHERE id = $id ");
        if($this->db->execute()){
        return 1;
        }else {
        return 0;
        }
    }

    public function eliminarTipoIva($id)
    {
        $this->db->query("DELETE FROM tiposiva WHERE id = $id ");
        if($this->db->execute()){
        return 1;
        }else {
        return 0;
        }
    }

    public function obtenerDatosTipoIvaPorId($id)
    {
        $this->db->query("SELECT * FROM tiposiva WHERE id = '$id' ");
        $fila = $this->db->registro();
        return (isset($fila->id))? $fila: false;
    }    

    public function obtenerTipoIvaPorTipo($tipo)
    {
        $this->db->query("SELECT id FROM tiposiva WHERE tipoiva = '$tipo' ");
        $fila = $this->db->registro();
        return (isset($fila->id))? $fila->id: false;
    }    

    public function obtenerTipoIvaPorId($idTipo)
    {
        $this->db->query("SELECT tipoiva FROM tiposiva WHERE id = '$idTipo' ");
        $fila = $this->db->registro();
        return (isset($fila->tipoiva) && $fila->tipoiva > 0)? $fila->tipoiva: false;
    }  

    public function obtenerTipoIvaActivos()
    {
        $this->db->query("SELECT * FROM tiposiva WHERE activo = 'activo' ");
        $filas = $this->db->registros();
        return $filas;
    }    


}