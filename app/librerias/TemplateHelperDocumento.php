<?php

class TemplateHelperDocumento {
        

    public static function buildRowGridDocument($params, $articulos, $ivas)
    {        
            $html = '';

            $filaOrden = $params['filaOrden'];
            if($params['tipo_linea'] == 'articulo'){
                $optionProducts = self::buildSelectProducts($articulos, false);
                $field_articulo_description = self::buildRowGridDocumentProduct($filaOrden, $optionProducts);
            }else if($params['tipo_linea'] == 'descripcion'){
                $field_articulo_description = self::buildRowGridDocumentDescription($filaOrden);
            }                            
            
            $optionIva = self::buildSelectIva($ivas);
    
            $html .= '
                <tr class="thead-light" id="fila_grilla_id_'.$filaOrden.'">                        
                                    
                    <td style="display:none;">
                        <input class="shortWidthField inputGrillaAuto numeroOrden" name="numeroOrden[]" id="numeroOrden'.$filaOrden.'" value="'.$filaOrden.'" readonly="">
                    </td>
    
                    <td style="display:cell;" class="description_title_grilla">
                        <div class="cont_prod_del">
                            '.$field_articulo_description.'
                        </div>              
                    </td>
                       
    
                    <td>
                        <input type="number" class="shortWidthField2 inputGrillaAuto cantidad dblClickInput" name="cantidadArticulo[]" id="cantidadArticulo'.$filaOrden.'" step="0.01" value="">
                    </td>
    
                    <td style="text-align:center;">
                        
                    </td>
    
                    <td>
                        <input type="number" class="shortWidthField2 inputGrillaAuto precio dblClickInput" name="precioArticulo[]" id="precioArticulo'.$filaOrden.'" step="0.01" value="">
                    </td>                    

                    <td>
                        <input type="number" class="shortWidthField2 inputGrillaAuto descuento dblClickInput" name="descuentoArticulo[]" id="descuentoArticulo'.$filaOrden.'" step="0.01" value="">
                    </td>                    
    
                    <td>
                        <input type="number" class="shortWidthField2 inputGrillaAuto totalLinea dblClickInput" step="0.01" name="totalLinea[]" id="totalLinea'.$filaOrden.'" value="0" readonly>
                    </td>                                
    
                    <td class="lineaIva">
                        <select class="inputGrillaAuto iva" name="iva[]" id="iva'.$filaOrden.'" >
                            '.$optionIva.'
                        </select>
                    </td>
    
                </tr>';    
                
        return $html;

    }

    private static function buildSelectProducts($articulos, $productdefault=false)
    {
        $options = '';
        if($articulos && count($articulos) > 0){

            for ($i=0; $i < count($articulos) ; $i++) { 
                $selected = '';
                if($productdefault){
                    $selected = ($productdefault && $productdefault==$articulos[$i]->numero)? 'selected': '';
                }                
                $options .= '<option value="'.$articulos[$i]->numero.'" '.$selected.'>'.$articulos[$i]->numero. ' - '. $articulos[$i]->nombre.'</option>';
            }            
        }
        return $options;
    }

    private static function buildSelectIva($tiposiva)
    {
       
        $options = '';
        if($tiposiva && count($tiposiva) > 0){

            for ($i=0; $i < count($tiposiva) ; $i++) {             
                $selected = (TIPO_IVA_DEFAULT==$tiposiva[$i]->tipoiva)? 'selected': '';
                $options .= '<option value="'.$tiposiva[$i]->tipoiva.'" '.$selected.'>'. $tiposiva[$i]->tipoiva.'%</option>';
            }
        }
        return $options;         
    }    

    private static function buildRowGridDocumentProduct($filaOrden, $optionProducts)
    {
        $html = '<select class="shortWidthField inputGrillaAuto articulo" data-idorden="'.$filaOrden.'" name="idArticulo[]" id="idArticulo'.$filaOrden.'">
                    <option value="" disabled="" selected>Buscar producto</option>
                </select>
                <input type="hidden" name="tipoDescripcion[]" value="codigo">
                <i class="fa fa-search buscar_articulo" data-idfila="'.$filaOrden.'"></i>
                <span class="eliminar_fila" data-idfila="'.$filaOrden.'">x</span>';
        return $html;
    }

    private static function buildRowGridDocumentDescription($filaOrden)
    {
        $html = '<textarea class="shortWidthField inputGrillaAuto articulo" regexp="[a-zA-ZäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙçÇñÑ0-9\s]{1,100}" data-idorden="'.$filaOrden.'" name="idArticulo[]" id="idArticulo'.$filaOrden.'"></textarea><input type="hidden" name="tipoDescripcion[]" value="texto">
                <span class="eliminar_fila" data-idfila="'.$filaOrden.'">x</span>';
        return $html;
    }

    public static function buildGridRows($rowsObject, $datos, $tipoDoc='')
    {     
        $html = '';
        if($rowsObject && count($rowsObject) > 0){
            $cont=0;
            foreach ($rowsObject as $key) {
                
                $cont++;
                $html.='

                    <tr class="thead-light" id="fila_grilla_id_'.$cont.'">              
                        
                        <td style="display:none;">
                            <input class="shortWidthField inputGrillaAuto numeroOrden" name="numeroOrden[]" id="numeroOrden'.$cont.'" value="'.$cont.'" readonly>
                            <input name="idFila[]" id="idFila'.$cont.'" value="'.$key->id.'" readonly>
                        </td>';

                        if($tipoDoc =='factura_cliente'){
                            $html.='
                            <td class="celdaDescripcion">
                                <div class="cont_prod_del">';

                                if($key->idproducto > 0){

                                    $html.='
                                    <select class="shortWidthField inputGrillaAuto articulo" name="idArticulo[]" id="idArticulo'.$cont.'" data-idordenguardado="'.$cont.'">';
                                        
                                        $html.="<option value='".$key->idproducto."'>".$key->idproducto." - ".$key->descripcion."</option>";
                                                                                   
                                            $html.='
                                    </select> 
                                    <input type="hidden" name="tipoDescripcion[]" value="codigo">
                                    <i class="fa fa-search buscar_articulo" data-idfila="'.$cont.'"></i>
                                    <span class="eliminar_fila" data-idfila="'.$cont.'">x</span>';

                                }else{
                                    $html .= '<textarea class="shortWidthField inputGrillaAuto articulo" regexp="[a-zA-ZäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙçÇñÑ0-9\s]{1,100}" name="idArticulo[]" id="idArticulo'.$cont.'">'.$key->descripcion.'</textarea><input type="hidden" name="tipoDescripcion[]" value="texto"><span class="eliminar_fila" data-idfila="'.$cont.'">x</span>';
                                }



                                    $html.='
                                </div>
                            </td>';
                                                    
                        }else if($tipoDoc =='factura' || $tipoDoc =='facturanegativa'){
                            $html.='
                            <td class="celdaDescripcion">
                                    <textarea type="text" name="descripcion[]" id="descripcion'.$cont.'" class="largeWidthField inputGrillaDescripcion dblClickInput" readonly>'. $key->descripcion .'</textarea>
                            </td>';
                        }

                        
                        if($tipoDoc == 'facturanegativa'){
                            $html.='<td><div class="cont_negativo"><span class="signo_negativo"> - </span><input type="number" class="shortWidthField2 inputGrillaAuto cantidad dblClickInput" name="cantidadArticulo[]" id="cantidadArticulo'.$cont.'" value="'.abs($key->cantidad).'" step="0.01"></td>';
                        
                        }else{
                            $html.='<td><input type="number" class="shortWidthField2 inputGrillaAuto cantidad dblClickInput" name="cantidadArticulo[]" id="cantidadArticulo'.$cont.'" value="'.$key->cantidad.'" step="0.01"></td>';
                        }                   
                        
                        $html.='
                        <td style="text-align:center;">'.$key->unidadproducto.'</td>
                        
                        <td>
                            <input type="number" class="shortWidthField2 inputGrillaAuto precio dblClickInput" name="precioArticulo[]" id="precioArticulo'.$cont.'" value="'.$key->precio.'" step="0.01">
                        </td>                    

                        <td>
                            <input type="number" class="shortWidthField2 inputGrillaAuto descuento dblClickInput" name="descuentoArticulo[]" id="descuentoArticulo'.$cont.'" step="0.01" value="'.$key->descuento.'">
                        </td>                    
                            
                        
                        <td>
                            <input type="number" class="shortWidthField2 inputGrillaAuto totalLinea dblClickInput" step="0.01" name="totalLinea[]" id="totalLinea'.$cont.'" value="'.$key->subtotal.'" readonly>
                        </td>                                
                        
                        <td class="lineaIva">
                            <select class="inputGrillaAuto iva" name="iva[]" id="iva'.$cont.'">
                                <option value="" selected disabled></option>';

                                if(isset($datos['tiposIva']) && count($datos['tiposIva']) > 0){                                                                      
                                    foreach ($datos['tiposIva'] as $tipo) {                                        
                                        $selected = ($tipo->tipoiva == $key->ivatipo)? 'selected': '';
                                        $html.="<option value='".$tipo->tipoiva."' ".$selected.">".$tipo->tipoiva." %</option>";
                                    }
                                }
                                $html.='                          
                            </select>
                        </td>
            
                    </tr>';

            }
        }

        return $html;
    
    }    

    public static function buildRowGridRequest($params, $articulos, $ivas)
    {        
            $html = '';

            $filaOrden = $params['filaOrden'];
            if($params['tipo_linea'] == 'articulo'){
                $optionProducts = self::buildSelectProducts($articulos, false);
                $field_articulo_description = self::buildRowGridDocumentProduct($filaOrden, $optionProducts);
            }else if($params['tipo_linea'] == 'descripcion'){
                $field_articulo_description = self::buildRowGridDocumentDescription($filaOrden);
            }                            
            
            $optionIva = self::buildSelectIva($ivas);
            /*
            <input type="text" class="shortWidthField2 inputGrillaAuto unidad dblClickInput" name="unidadArticulo[]" id="unidadArticulo'.$filaOrden.'" value="" readonly>
            */
            $html .= '
                <tr class="thead-light" id="fila_grilla_id_'.$filaOrden.'">                        
                                    
                    <td style="display:none;">
                        <input class="shortWidthField inputGrillaAuto numeroOrden" name="numeroOrden[]" id="numeroOrden'.$filaOrden.'" value="'.$filaOrden.'" readonly="">
                    </td>
    
                    <td style="display:cell;" class="description_title_grilla">
                        <div class="cont_prod_del">
                            '.$field_articulo_description.'
                        </div>              
                    </td>
                       
    
                    <td>
                        <input type="number" class="shortWidthField2 inputGrillaAuto cantidad dblClickInput" name="cantidadArticulo[]" id="cantidadArticulo'.$filaOrden.'" step="0.01" value="">
                    </td>
    
                    <td style="text-align:center;">
                        
                    </td>
    
                    <td>
                        <input type="number" class="shortWidthField2 inputGrillaAuto precio dblClickInput" name="precioArticulo[]" id="precioArticulo'.$filaOrden.'" step="0.01" value="">
                    </td>                    

                    <td>
                        <input type="number" class="shortWidthField2 inputGrillaAuto descuento dblClickInput" name="descuentoArticulo[]" id="descuentoArticulo'.$filaOrden.'" step="0.01" value="">
                    </td>                    
    
                    <td>
                        <input type="number" class="shortWidthField2 inputGrillaAuto totalLinea dblClickInput" step="0.01" name="totalLinea[]" id="totalLinea'.$filaOrden.'" value="0" readonly>
                    </td>                                
    
                    <td class="lineaIva">
                        <select class="inputGrillaAuto iva" name="iva[]" id="iva'.$filaOrden.'" >
                            '.$optionIva.'
                        </select>
                    </td>

                    <td></td> 
                    
                    <td></td> 
    
                </tr>';    
                
        return $html;

    }

    public static function buildRowGridRequestWithData($rowsObject, $datos)
    {     
        $html = '';
        if($rowsObject && count($rowsObject) > 0){
            $cont=0;
            foreach ($rowsObject as $key) {
                
                $cont++;
                $html.='

                    <tr class="thead-light" id="fila_grilla_id_'.$cont.'">              
                        
                        <td style="display:none;">
                            <input class="shortWidthField inputGrillaAuto numeroOrden" name="numeroOrden[]" id="numeroOrden'.$cont.'" value="'.$cont.'" readonly>
                            <input name="idFila[]" id="idFila'.$cont.'" value="'.$key->id.'" readonly>
                        </td>';

                        
                        $html.='
                            <td class="celdaDescripcion">
                                <div class="cont_prod_del">';

                                if($key->idproducto > 0){

                                    $html.='
                                    <select class="shortWidthField inputGrillaAuto articulo" name="idArticulo[]" id="idArticulo'.$cont.'" data-idordenguardado="'.$cont.'">';
                                        
                                            $html.="<option value='".$key->idproducto."'>".$key->idproducto." - ".$key->descripcion."</option>";

                                            $html.='
                                    </select> 
                                    <input type="hidden" name="tipoDescripcion[]" value="codigo">
                                    <i class="fa fa-search buscar_articulo" data-idfila="'.$cont.'"></i>
                                    <span class="eliminar_fila" data-idfila="'.$cont.'">x</span>';

                                }else{
                                    $html .= '<textarea class="shortWidthField inputGrillaAuto articulo" regexp="[a-zA-ZäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙçÇñÑ0-9\s]{1,100}" name="idArticulo[]" id="idArticulo'.$cont.'">'.$key->descripcion.'</textarea><input type="hidden" name="tipoDescripcion[]" value="texto"><span class="eliminar_fila" data-idfila="'.$cont.'">x</span>';
                                }

                                    $html.='
                                </div>
                            </td>';                                                                                                
                        
                        $html.='<td><input type="number" class="shortWidthField2 inputGrillaAuto cantidad dblClickInput" name="cantidadArticulo[]" id="cantidadArticulo'.$cont.'" value="'.$key->cantidad.'" step="0.01"></td>';
                       
                        $html.='
                        <td style="text-align:center;">'.$key->unidadproducto.'</td>
                        
                        <td>
                            <input type="number" class="shortWidthField2 inputGrillaAuto precio dblClickInput" name="precioArticulo[]" id="precioArticulo'.$cont.'" value="'.$key->precio.'" step="0.01">
                        </td>                    

                        <td>
                            <input type="number" class="shortWidthField2 inputGrillaAuto descuento dblClickInput" name="descuentoArticulo[]" id="descuentoArticulo'.$cont.'" step="0.01" value="'.$key->descuento.'">
                        </td>                    
                            
                        
                        <td>
                            <input type="number" class="shortWidthField2 inputGrillaAuto totalLinea dblClickInput" step="0.01" name="totalLinea[]" id="totalLinea'.$cont.'" value="'.$key->subtotal.'" readonly>
                        </td>                                
                        
                        <td class="lineaIva">
                            <select class="inputGrillaAuto iva" name="iva[]" id="iva'.$cont.'">
                                <option value="" selected disabled></option>';

                                if(isset($datos['tiposIva']) && count($datos['tiposIva']) > 0){                                                                      
                                    foreach ($datos['tiposIva'] as $tipo) {                                        
                                        $selected = ($tipo->tipoiva == $key->ivatipo)? 'selected': '';
                                        $html.="<option value='".$tipo->tipoiva."' ".$selected.">".$tipo->tipoiva." %</option>";
                                    }
                                }
                                $html.='                          
                            </select>
                        </td>

                        <td>';

                        $displayCheckbox = 'block';
                        if($key->estado == 'facturado'){
                            $displayCheckbox = 'none';
                        }
                        
                            $html.='  
                            <div style="display: flex;justify-content: center;">
                                <input class="checkMarcaFacturar" style="display:'.$displayCheckbox.';" type="checkbox" value="no" name="marcaEnviar[]" id="marcaEnviar'.$cont.'">
                            </div>';
                        
                       
                        $html.='  
                        </td>
                        <td>'.$key->estado.'</td>
            
                    </tr>';

            }
        }

        return $html;
    
    } 

    public static function buildHtmlLinkInvoice($numFactura, $idfactura)
    {
        return $html = '<span>Factura N°</span><a id="numFactura" data-idfactura="'.$idfactura.'" class="cursor-pointer">'.$numFactura.'</a>';        
    }

    public static function buildButtonsActionInvoicesFromRequest($idFactura)
    {        
        $html = '
        
            <a class="agregar_linea_documento w-auto bg-gray-400 hover:bg-gray-300 rounded-lg shadow-xl font-medium cursor-pointer text-xs text-white px-4 py-1 mr-3" data-linea="descripcion">Nueva línea</a>
            <a class="agregar_linea_documento w-auto bg-gray-400 hover:bg-gray-300 rounded-lg shadow-xl font-medium cursor-pointer text-xs text-white px-4 py-1 mr-3" data-linea="articulo">Agregar artículo</a>
            <a class="w-auto bg-green-700 hover:bg-green-800 rounded-lg shadow-xl font-medium text-xs text-white cursor-pointer px-4 py-1 mr-3" id="guardar_detalle_factura">Guardar datos</a>
            <a class="w-auto bg-red-700 hover:bg-red-800 rounded-lg shadow-xl font-medium text-xs text-white cursor-pointer px-4 py-1 mr-3" id="facturar_desde_incidencia">Facturar líneas</a>';

        if(!$idFactura || $idFactura == 0){
            $html .= '<a class="w-auto bg-blue-700 hover:bg-blue-800 rounded-lg shadow-xl font-medium text-xs text-white cursor-pointer px-4 py-1 mr-3" id="buscar_factura">Buscar factura</a>';
        }      

        

        return $html;
    }

    public static function buildSelectOptionsFacturasCliente($facturas)
    {
        $html = '<option disabled selected>Seleccionar</option>';
        
        if(isset($facturas) && count($facturas) > 0){

            foreach ($facturas as $key) {
                
                $html .= '<option value="'.$key->id.'">'.$key->numero.' - del '.date("d/m/Y",strtotime($key->fecha)).' - Total: '.number_format($key->total,2,",",".").'</option>';
                
            }
        }        

        return $html;
    }


    public static function buildHTMLListSentEmailsDocumento($envios, $tipoDoc=false)
    {
            $text = '';
            if($tipoDoc == 'parte'){
                $text = '<label class="uppercase text-sm xl:text-base text-gray-500 text-light font-semibold mb-4">Emails enviados</label>';
            }  
            $html = $text.'<div class="container_emails">';                  
            
            foreach ($envios as $key) {                               
                $html2 = '<div class="fila_email">
                <div class="field_text"><span class="etiqueta_email">Fecha:</span> '.DateTimeHelper::convertDateTimeToFormat($key->fecha).'</div>
                <div class="field_text"><span class="etiqueta_email">Asunto:</span> '.$key->asunto.'</div>                
                <div class="field_text"><span class="etiqueta_email">Remitente:</span> '.$key->correoremitente.' - '.$key->nomremitente.'</div>
                <div class="field_text"><span class="etiqueta_email">Destinatarios:</span> '.self::quitarCorchetes($key->destinatarios).'</div>
                <div class="field_text"><span class="etiqueta_email">Mensaje:</span> '.$key->mensaje.'</div>
                <div class="field_text"><span class="etiqueta_email">Documento enviado:</span>: '.$key->nomfichero.'</div>
                </div>';
                $html .= $html2;
            }        
            $html .= '</div>';
        
            return $html;
    }
    

    public static function quitarCorchetes($jsonString) {        
        $array = json_decode($jsonString, true);            
        if (is_array($array)) {            
            $resultString = implode(', ', $array);
            return $resultString;
        } else {            
            return '';
        }
    }

}