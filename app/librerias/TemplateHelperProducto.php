<?php

class TemplateHelperProducto {
        

    public static function buildLineSuppliersPricesLine($suppliers)
    {        
        $html = '<tr><td width="40%"><p>No existen proveedores creados</p></td></tr>';

        if($suppliers && count($suppliers) > 0){

            $html = '<tr><td width="35%"><select class="border-2 border-pink-200 rounded-lg border-opacity-50" name="proveedor" style="width: 100%;"><option selected disabled value="">Seleccionar</option>';          
            foreach ($suppliers as $key) {    
                $html .= '<option value="'.$key->id.'">'.$key->nombre.'</option>';
            }          
            
            $html .= '</select></td>
            <td width="15%"><input type="text" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="referenciaprov" style="width: 100%;" regexp="[a-zA-Z0-9\-/*_]{1,20}"></td>
            <td width="15%"><input type="number" step="0.01" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="precio" style="width: 100%;"></td>
            <td width="15%"><input type="number" step="0.01" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="margen" style="width: 100%;"></td>
            <td width="15%"><input title="Precio de venta" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="precioventa" style="width: 100%;" readonly></td>
            <td width="10%"><input type="radio" title="preferente" class="form-checkbox h-4 w-4 checkProveedorDefault" name="proveedordefault" value=""></td>            
            <td width="10%"><a class="eliminarProveedorPrecio" title="Eliminar"><i class="fas fa-user-minus" style="color:red;"></i></a></td>
            </tr>';

        }

        return $html;    
    }

    public static function buildLineSuppliersPricesLineWithData($suppliers, $dataLine)
    {        
        $html = '<tr><td width="40%"><p>No existen proveedores y precios para este artículo</p></td></tr>';

        if(json_decode($dataLine->provprecios)){
            
            $proveedores_precios = get_object_vars(json_decode($dataLine->provprecios));
            
            if($suppliers && count($suppliers) > 0 && count($proveedores_precios) > 0){

                $html = '';
    
                foreach ($proveedores_precios as $pp => $v) {
                  
    
                    $html2 = '<tr><td width="40%"><select class="border-2 border-pink-200 rounded-lg border-opacity-50" name="proveedor" style="width: 100%;"><option selected disabled value="">Seleccionar</option>';          
                    foreach ($suppliers as $key) {    
                        $html2 .= '<option value="'.$key->id.'" '.(($key->id==$pp)? 'selected':'' ).'>'.$key->nombre.'</option>';
                    }          
                    
                    $checked = (isset($dataLine->proveedordefault) && $dataLine->proveedordefault > 0 && $dataLine->proveedordefault ==$pp)? 'checked': '';
                    $value = (isset($dataLine->proveedordefault) && $dataLine->proveedordefault > 0 && $dataLine->proveedordefault ==$pp)? $pp: 0;
    
                    $html2 .= '</select></td>
                    <td width="15%"><input type="text" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="referenciaprov" value="'.$v->referencia.'" style="width: 100%;" regexp="[a-zA-Z0-9\-/*_]{1,20}"></td>
                    <td width="15%"><input type="number" step="0.01" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="precio" style="width: 100%;" value="'.$v->precio.'"></td>
                    <td width="15%"><input type="number" step="0.01" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="margen" style="width: 100%;" value="'.$v->margen.'"></td>
                    <td width="15%"><input class="border-2 border-pink-200 rounded-lg border-opacity-50" name="precioventa" style="width: 100%;" value="'.(isset($v->precioVta)? number_format($v->precioVta,2,",","."):0) .'" readonly></td>
                    <td width="10%"><input type="radio" '.$checked.' value="'.$value.'" class="form-checkbox h-4 w-4 checkProveedorDefault" name="proveedordefault" ></td>
                    <td width="10%"><a class="eliminarProveedorPrecio"><i class="fas fa-user-minus" style="color:red;"></i></a></td>
                    </tr>';
    
                    $html .= $html2; 
                        
                }
    
            }
        }
       
        return $html;    
    }

}