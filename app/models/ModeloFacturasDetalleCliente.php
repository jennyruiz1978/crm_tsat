<?php


class ModeloFacturasDetalleCliente{

    private $db;


    public function __construct(){
        $this->db = new Base;
    } 
    
    public function obtenerFilasFactura($idFactura){
        $this->db->query("SELECT fd.*, pro.unidad AS unidadproducto FROM facturasdetclientes fd
        LEFT JOIN productos pro ON fd.idproducto=pro.id
        WHERE fd.idfactura = '$idFactura' ");                

        $filas = $this->db->registros();
        return $filas;
    }

    public function obtenerTotalesFactura($idFactura){
        $this->db->query("SELECT 
                        IF(ROUND(SUM(subtotal), 2) IS NULL, 0, ROUND(SUM(subtotal), 2)) AS suma_base_imponible,
                        IF(ROUND(SUM(subtotal * ivatipo / 100), 2) IS NULL, 0, ROUND(SUM(subtotal * ivatipo / 100), 2)) AS suma_iva,
                        IF(ROUND(SUM(subtotal), 2) + ROUND(SUM(subtotal * ivatipo / 100), 2) IS NULL, 0, ROUND(SUM(subtotal), 2) + ROUND(SUM(subtotal * ivatipo / 100), 2)) AS total_final
                        FROM facturasdetclientes 
                        WHERE idfactura = '$idFactura' ");                

        $filas = $this->db->registro();
        return $filas;
    }

      
    public function obtenerTotalesFacturaFormat($idFactura){
        $this->db->query("SELECT                        
                        FORMAT(SUM(subtotal),2,'es_ES') AS baseimponible,                                                
                        FORMAT(SUM(subtotal * ivatipo / 100),2,'es_ES') AS ivatotal,
                        FORMAT(ROUND(SUM(subtotal),2) + ROUND(SUM(subtotal * ivatipo / 100),2),2,'es_ES') AS total

                        FROM facturasdetclientes 
                        WHERE idfactura = '$idFactura' ");                

        $fila = $this->db->registro();
        return $fila;
    }

    public function obtenerFilasPrefactura($idIncidencia){
        $this->db->query("SELECT pf.*, pro.unidad AS unidadproducto 
        FROM prefacturasdetclientes pf
        LEFT JOIN productos pro ON pf.idproducto=pro.id
        WHERE pf.idincidencia = '$idIncidencia' ");                

        $filas = $this->db->registros();
        return $filas;
    }

    public function obtenerIdFilaFacturaByIdFilaPreFactura($idFilaPreFactura){
        $this->db->query("SELECT idfilafactura FROM prefacturasdetclientes WHERE id = '$idFilaPreFactura' ");                

        $fila = $this->db->registro();
        return (isset($fila->idfilafactura) && $fila->idfilafactura != '')? $fila->idfilafactura: false;
    }


    public function actualizarEstadoIdFacturaEnPrefactura($idFactura,$estado,$idFila)
    {

        $this->db->query("UPDATE prefacturasdetclientes 
                        SET estado = '$estado' , idfactura = '$idFactura'                       
                        WHERE id = '$idFila' ");

        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }      
    }

    public function actualizarIdFilaFacturaEnPreFactura($idFactura, $idFilaFactura, $idFila, $estado)
    {        

        $this->db->query("UPDATE prefacturasdetclientes 
                        SET idfilafactura = '$idFilaFactura', idfactura = '$idFactura' , estado = '$estado'                       
                        WHERE id = '$idFila' ");

        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }      
    }

    public function actualizarFilaPreFacturaByIdFactura($idFactura, $idFilaFactura,$estado)
    {        

        $this->db->query("UPDATE prefacturasdetclientes 
                        SET idfactura = '$idFactura' , estado = '$estado'                       
                        WHERE idfilafactura = '$idFilaFactura' ");

        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }      
    }

    public function actualizarIdFilaFactura($idFilaFactura)
    {
        
        $this->db->query("UPDATE prefacturasdetclientes SET idfilafactura = 0 WHERE idfilafactura = '$idFilaFactura' ");

        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }     
    }

    public function contarLineasPreFacturaSinFacturar($idIncidencia){
        $this->db->query("SELECT COUNT(*) AS sinfacturar FROM prefacturasdetclientes det
        WHERE det.idincidencia = '$idIncidencia' AND det.estado='sin Fact.' ");                

        $fila = $this->db->registro();
        return (isset($fila->sinfacturar) && $fila->sinfacturar != '')? $fila->sinfacturar: 0;
    }

    public function contarLineasPreFacturaFacturadas($idIncidencia){
        $this->db->query("SELECT COUNT(*) AS facturado FROM prefacturasdetclientes det
        WHERE det.idincidencia = '$idIncidencia' AND det.estado='facturado' ");                

        $fila = $this->db->registro();
        return (isset($fila->facturado) && $fila->facturado != '')? $fila->facturado: 0;
    }
    

    public function verificarSiFilPrefacturaEstaFacturada($idFilaPrefactura)
    {
        $this->db->query("SELECT idfilafactura
                        FROM prefacturasdetclientes 
                        WHERE id = '$idFilaPrefactura' ");                

        $fila = $this->db->registro();
        return (isset($fila->idfilafactura) && $fila->idfilafactura != '')? $fila->idfilafactura: 0;
    }
    
    public function eliminarFilaFactura($id)
    {
        $this->db->query("DELETE FROM facturasdetclientes WHERE id='$id' ");
        if($this->db->execute()==false){
            return false;
        }
        return true;
    }

    public function insertarNuevaFilaFactura($datos)
    {                       
        $this->db->query("INSERT INTO facturasdetclientes (idproducto,descripcion,unidad,cantidad,precio,descuento,ivatipo,subtotal,idfactura) 
                        VALUES (:idproducto,:descripcion,:unidad,:cantidad,:precio,:descuento,:ivatipo,:subtotal,:idfactura)");                    
                                 
        $this->db->bind(':idproducto', $datos['idproducto']);
        $this->db->bind(':descripcion', $datos['descripcion']);
        $this->db->bind(':unidad', $datos['unidad']);
        $this->db->bind(':cantidad', $datos['cantidad']);
        $this->db->bind(':precio', $datos['precio']);
        $this->db->bind(':descuento', $datos['descuento']);
        $this->db->bind(':ivatipo', $datos['ivatipo']);
        $this->db->bind(':subtotal', $datos['subtotal']);
        $this->db->bind(':idfactura', $datos['idfactura']);                        

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }        
    }
    
    public function actualizarFilaFactura($datos)
    {

        $this->db->query("UPDATE facturasdetclientes 
                        SET idproducto= :idproducto, descripcion= :descripcion, unidad= :unidad, cantidad= :cantidad, precio= :precio, descuento= :descuento, ivatipo= :ivatipo, subtotal= :subtotal
                        WHERE id = :id ");

        $this->db->bind(':idproducto', $datos['idproducto']);
        $this->db->bind(':descripcion', $datos['descripcion']);
        $this->db->bind(':unidad', $datos['unidad']);
        $this->db->bind(':cantidad', $datos['cantidad']);
        $this->db->bind(':precio', $datos['precio']);
        $this->db->bind(':descuento', $datos['descuento']);
        $this->db->bind(':ivatipo', $datos['ivatipo']);
        $this->db->bind(':subtotal', $datos['subtotal']);        
        $this->db->bind(':id', $datos['id']); 

        if($this->db->execute()){
            return 1;
        }else {
            return 0;
        }      
    }

}