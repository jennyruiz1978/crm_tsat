<?php


class ModeloBase{

    private $db;


    public function __construct(){
        $this->db = new Base;
    }

  

    public function insertRow($tabla, $strFields, $strValues){       
      
        $this->db->query("INSERT INTO $tabla  $strFields VALUES $strValues ");      

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }

    }           
    

    public function maximoNumDocumentoAnioAntes($field, $tabla, $anio)
    {        
        $this->db->query("SELECT MAX($field) AS maximo 
                        FROM $tabla 
                        WHERE YEAR(fecha) = '$anio' ");        
        $fila = $this->db->registro();        
        return ($fila->maximo ?? 0) + 1;
    }

    public function maximoNumDocumentoAnio($field, $tabla, $anio, $rectificativa = 0)
    {        
        $this->db->query("SELECT MAX($field) AS maximo 
                        FROM $tabla 
                        WHERE YEAR(fecha) = '$anio' 
                        AND rectificativa = '$rectificativa'
        ");        

       /*  echo"<br><br>this<br>";
        print_r($this); */

        $fila = $this->db->registro();        
        return ($fila->maximo ?? 0) + 1;
    }



    public function existIdInvoice($tabla, $idDoc)
    {
        $this->db->query("SELECT id FROM $tabla  WHERE id = '$idDoc' ");        
        $row = $this->db->registro();        
        return (isset($row->id))? $row->id: 0;
    }

    public function updateRow($tabla, $fieldsValues, $where){

        $this->db->query("UPDATE $tabla SET $fieldsValues WHERE $where ");    
              
        if($this->db->execute()){
            return true;
        }else {
            return false;
        }
    } 
    
  
           
    public function deleteRow($tabla, $where){

        $this->db->query("DELETE FROM $tabla WHERE $where ");        

        if($this->db->execute()){
            return true;
        }else {
            return false;
        }
    }    












    //=================================
    

  

    
    public function updateFieldTabla($tabla, $field, $value, $id){
        $this->db->query("UPDATE $tabla SET $field = '$value' WHERE id = '$id' ");

        if($this->db->execute()){
            return true;
        }else {
            return false;
        }
    }

    public function updateFieldTablaWithCustomizeWhere($tabla, $field, $value, $whereField, $whereValueString){
        $this->db->query("UPDATE $tabla SET $field = '$value' WHERE $whereField IN ( $whereValueString ) ");

        if($this->db->execute()){
            return true;
        }else {
            return false;
        }
    }

    public function updateFieldTablaByStringIn($tabla, $field, $value, $string){
        $this->db->query("UPDATE $tabla SET $field = '$value' WHERE id IN ( $string ) ");

        if($this->db->execute()){
            return true;
        }else {
            return false;
        }
    }



    public function getFieldTabla($tabla, $field, $id){
        $this->db->query("SELECT $field FROM $tabla  WHERE id = '$id' ");
        
        $row = $this->db->registro();
        return $row;
    }

   

    public function max($query){

        $this->db->query("$query");        

        $fila = $this->db->registro();
        $maximo = 1;
        if(isset($fila->maximo) && $fila->maximo > 0){
            $maximo = $fila->maximo + 1;
        }
        return $maximo;

    }

    public function maximoIdTabla($field, $tabla)
    {        
        $this->db->query("SELECT MAX($field) AS maximo FROM $tabla ");        
        
        $fila = $this->db->registro();
        $maximo = 1;
        if(isset($fila->maximo) && $fila->maximo > 0){
            $maximo = $fila->maximo + 1;
        }
        return $maximo;
    }

    public function getAllFieldsTablaByFieldFilter($tabla, $fieldFilter, $fieldValue, $fieldOrder, $orderBy){
        $this->db->query("SELECT * FROM $tabla  WHERE $fieldFilter = '$fieldValue' order by $fieldOrder $orderBy ");        
        $rows = $this->db->registros();
        return $rows;
    }

    public function getAllFieldsTablaByFieldsFilters($tabla, $filters = [], $fieldOrder = 'id', $orderBy = 'ASC') {
        
        $whereClauses = [];
        foreach ($filters as $field => $value) {
            $whereClauses[] = "$field = '$value'";
        }
        $whereSql = implode(' AND ', $whereClauses);
                
        $query = "SELECT * FROM $tabla WHERE $whereSql ORDER BY $fieldOrder $orderBy";
        $this->db->query($query);
        return $this->db->registros();
    }



}