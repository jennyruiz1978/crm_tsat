<?php


class ModeloCuentasBancarias{

    private $db;


    public function __construct(){
        $this->db = new Base;
    }       

    public function obtenerCuentasBancariasTablaClassBuscar($filas,$orden,$filaspagina,$tipoOrden,$cond){
        $this->db->query("SELECT id AS 'Nº', numerocuenta AS 'IBAN', banco, estado                         
                        FROM cuentasbancarias WHERE 1  $cond 
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");

        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosCuentasBancarias()
    {
        $this->db->query("SELECT count(*) AS contador FROM cuentasbancarias ");
                        
        $fila = $this->db->registro();
        return $fila;
    }

    public function totalRegistrosCuentasBancariasBuscar($cond)
    {
        $this->db->query("SELECT count(*) AS contador FROM cuentasbancarias WHERE 1 $cond ");
        $fila = $this->db->registro();
        return $fila;
    }
    
    public function insertarNuevaCuentaBancaria($datos)
    {                            
        $numerocuenta = $datos['numerocuenta'];        
        $banco = $datos['banco'];     
        $estado = 'activo';

        $this->db->query("INSERT INTO cuentasbancarias (numerocuenta,banco,estado) 
                        VALUES ('$numerocuenta','$banco','$estado' )");
        
        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return 0;
        }      

    }
 
    public function actualizarDatosCuentaBancaria($datos)
    {
        $id = $datos['id'];
        $numerocuenta = $datos['numerocuenta'];        
        $banco = $datos['banco'];     
        $estado = $datos['estado'];   

      
       

        $this->db->query("UPDATE cuentasbancarias 
                        SET  numerocuenta = '$numerocuenta', banco = '$banco', estado='$estado'
                        WHERE id = '$id' ");
       
      
       
        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }
    }

    public function inactivarCuentaBancaria($id)
    {
        $this->db->query("UPDATE cuentasbancarias SET estado = 'inactivo' WHERE id = $id ");
        if($this->db->execute()){
        return 1;
        }else {
        return 0;
        }
    }

    public function eliminarCuentaBancaria($id)
    {
        $this->db->query("DELETE FROM cuentasbancarias WHERE id = $id ");
        if($this->db->execute()){
        return 1;
        }else {
        return 0;
        }
    }

    public function obtenerDatosCuentaBancariaPorId($id)
    {
        $this->db->query("SELECT * FROM cuentasbancarias WHERE id = '$id' ");
        $fila = $this->db->registro();
        return (isset($fila->id))? $fila: false;
    }    

    public function obtenerCuentaBancariaPorNumeroCuenta($numcuenta)
    {
        $this->db->query("SELECT id FROM cuentasbancarias WHERE numerocuenta = '$numcuenta' ");
        $fila = $this->db->registro();
        return (isset($fila->id))? $fila->id: false;
    }    

    public function obtenerCuentaBancariaPorId($id)
    {
        $this->db->query("SELECT numerocuenta FROM cuentasbancarias WHERE id = '$id' ");
        $fila = $this->db->registro();
        return (isset($fila->numerocuenta) && $fila->numerocuenta > 0)? $fila->numerocuenta: false;
    }  

    public function obtenerCuentaBancariaActivos()
    {
        $this->db->query("SELECT * FROM cuentasbancarias WHERE estado = 'activo' ");
        $filas = $this->db->registros();
        return $filas;
    }    

    
    public function obtenerCuentasBancariasSelect()
    {
        $this->db->query("SELECT id, numerocuenta FROM cuentasbancarias WHERE estado = 'activo' ");
        $filas = $this->db->registros();
        return $filas;
    }    

}