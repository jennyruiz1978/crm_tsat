<?php


class ModeloFormasDePago{

    private $db;


    public function __construct(){
        $this->db = new Base;
    }       

    public function obtenerFormasDePagoTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond){
        $this->db->query("SELECT id AS 'Nº', formadepago AS 'Forma de pago', estado
                        FROM formasdepago WHERE 1  $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosFormasDePago()
    {
        $this->db->query("SELECT count(*) AS contador FROM formasdepago ");
                        
        $fila = $this->db->registro();
        return $fila;
    }

    public function totalRegistrosFormasDePagoBuscar($cond)
    {
        $this->db->query("SELECT count(*) AS contador FROM formasdepago WHERE 1 $cond ");
        $fila = $this->db->registro();
        return $fila;
    }
    
    public function insertarNuevaFormaDePago($datos)
    {                            
        $formadepago = $datos['formadepago'];                
        $estado = 'activo';

        $this->db->query("INSERT INTO formasdepago (formadepago,estado) 
                        VALUES ('$formadepago','$estado' )");
        
        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return 0;
        }      

    }
 
    public function actualizarDatosFormaDePago($datos)
    {
        $id = $datos['id'];
        $formadepago = $datos['formadepago'];        
        $estado = $datos['estado'];                

        $this->db->query("UPDATE formasdepago 
                        SET  formadepago = '$formadepago', estado='$estado'
                        WHERE id = '$id' ");             
       
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function inactivarFormaDePago($id)
    {
        $this->db->query("UPDATE formasdepago SET estado = 'inactivo' WHERE id = $id ");
        if($this->db->execute()){
        return 1;
        }else {
        return 0;
        }
    }

    public function eliminarFormaDePago($id)
    {
        $this->db->query("DELETE FROM formasdepago WHERE id = $id ");
        if($this->db->execute()){
        return 1;
        }else {
        return 0;
        }
    }

    public function obtenerDatosFormaDePagoPorId($id)
    {
        $this->db->query("SELECT * FROM formasdepago WHERE id = '$id' ");
        $fila = $this->db->registro();
        return (isset($fila->id))? $fila: false;
    }    

    public function obtenerFormaDePagoPorFormaDePago($formadepago)
    {
        $this->db->query("SELECT id FROM formasdepago WHERE formadepago = '$formadepago' ");
        $fila = $this->db->registro();
        return (isset($fila->id))? $fila->id: false;
    }    

    public function obtenerFormaDePagoPorId($id)
    {
        $this->db->query("SELECT formadepago FROM formasdepago WHERE id = '$id' ");
        $fila = $this->db->registro();
        return (isset($fila->formadepago) && $fila->formadepago > 0)? $fila->formadepago: false;
    }  

    public function obtenerFormaDePagoActivos()
    {
        $this->db->query("SELECT * FROM formasdepago WHERE estado = 'activo' ");
        $filas = $this->db->registros();
        return $filas;
    }    

    public function obtenerFormasDePagoSelect()
    {
        $this->db->query("SELECT id, formadepago FROM formasdepago WHERE estado = 'activo' 
                        ORDER BY formadepago ASC ");

        $resultados = $this->db->registros();

        return $resultados;
    }


}