<?php

class UtilsHelper {
        

    public static function formatNumberTypePrice($precio)
    {                
        $precio = ($precio != '')? str_replace(",", ".", $precio): 0;
        return $precio;    
    }

    public static function buildStringsInsertQueryNuevo2($arrValues, $arrFields){
                       
        $retorno['ok'] = false;

        $str = " ( ";
        $val = " ( ";        
        $cont = 0;        

       
      

        if(count($arrValues) > 0 && count($arrFields) > 0){
            
            foreach ($arrValues as $key => $value) {
        
                if(in_array($key,$arrFields)){
                    $cont++;                   

                    if ($cont == count($arrFields) ) {
                       
                        $str .= " $key )";
                        $val .= " '$value' )";
                    } else {
                        
                        $str .= " $key ,";
                        $val .= " '$value' ,";
                    }
                }
            }              
           
            if($cont == count($arrFields)){
                $retorno['ok'] = true;
                $retorno['strFields'] = $str;
                $retorno['strValues'] = $val;
            }
        }       
        return $retorno;
    }

   
    
    public static function validateRequiredFields($post, $arrFieldsValidate)
    {
           
        $arrError = []; 
      
        if(count($arrFieldsValidate) > 0 && count($post) > 0){                       

            foreach ($arrFieldsValidate as $key) {
                 
                    if(!isset($post[$key]) || trim($post[$key]) == ''){
                       
                        array_push($arrError, $key);
                    }                
            }              
        }                     
        return $arrError;
    }      

    public static function buildStringsUpdateQuery($arrValues, $arrFields){
     

        $retorno['ok'] = false;
        

        $str = " ";        
        $cont = 0;

        if(count($arrFields) > 0 && count($arrValues) > 0 ){
            
            foreach ($arrFields as $k) {
        
                if (array_key_exists($k,$arrValues)) {
               
                    $cont++;
                    
                    if ($cont == count($arrFields)  ) {
                        $str .= " $k = '".$arrValues[$k]."' ";
                        
                    } else {
                        $str .= " $k = '".$arrValues[$k]."' , ";
                    }
                    
                }
            }              
            
            if($cont == count($arrFields)){
                $retorno['ok'] = true;
                $retorno['strFieldsValues'] = $str;            
            }  
        }
       
        return $retorno;
    }
    
    public static function buildStringsWhereQuery($arrWhere = []){

        $str = "";
        $cont = 0;        
        $retorno['ok'] = false;

        if(count($arrWhere) > 0){
            
            foreach ($arrWhere as $w => $v) {
                $cont++;
                   
                    if ($cont == count($arrWhere)  ) {
                        $str .= " $w = '$v' ";

                    } else {
                        $str .= " $w = '$v' AND ";
                    }
            }
            if($cont == count($arrWhere)){
                $retorno['ok'] = true;
                $retorno['strWhere'] = $str;
            }
        }         
        return $retorno;
    }    

    
    public static function buildStringsFieldsUpdateQuery($arrFieldsValues){
     
        $retorno = '';
        $str = " ";        
        $cont = 0;

        if(count($arrFieldsValues) > 0 ){
            
            foreach ($arrFieldsValues as $k => $v) {
                                    
                $cont++;
                if ($cont == count($arrFieldsValues)  ) {
                    $str .= " $k = '".$v."' ";                  
                } else {
                    $str .= " $k = '".$v."' , ";
                }
                                                   
            }                                     
            $retorno = $str;            
           
        }
       
        return $retorno;
    }    

    public static function buildStringsWhereQueryOnly($arrWhere = []){

        $str = "";
        $cont = 0;        
        $retorno = '';

        if(count($arrWhere) > 0){
            
            foreach ($arrWhere as $w => $v) {
                $cont++;
                   
                    if ($cont == count($arrWhere)  ) {
                        $str .= " $w = '$v' ";

                    } else {
                        $str .= " $w = '$v' AND ";
                    }
            }
            if($cont == count($arrWhere)){               
                $retorno = $str;
            }
        }         
        return $retorno;
    }    

    public static function buildStringsInsertQuery($arrValues, $arrFields){            

        $retorno['ok'] = false;

        $str = " ( ";
        $val = " ( ";        
        $cont = 0;       
                   
        if(count($arrValues) > 0 && count($arrFields) > 0 && count($arrValues)==count($arrFields)){
                   
            foreach ($arrValues as $key => $value) {
        
                if(in_array($key,$arrFields)){
                    $cont++;
                    if ($cont == count($arrValues)  ) {
                        $str .= " $key )";
                        $val .= " '$value' )";
                    } else {
                        $str .= " $key ,";
                        $val .= " '$value' ,";
                    }
                }
            }    

            if($cont == count($arrFields)){
                $retorno['ok'] = true;
                $retorno['strFields'] = $str;
                $retorno['strValues'] = $val;
            }        
        }       
        return $retorno;
    }


}