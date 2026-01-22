
    <style type="text/css">
        #datosEmpresas,
        #numFactura,
        #contenido, #contenido2 {
            border-collapse: collapse;
            width: 100%;
        }
        

        .pie {
            background-color: #00BCD4;
            margin-top: 20px;
            height: 50px;
        }

        #datosEmpresas td,
        #numFactura td,
        #contenido td, #contenido2 td {
            /* border: 1px solid #ddd; */
            padding-left: 8px;
            padding-right: 8px;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        #numFactura th,
        #datosEmpresas th,
        #contenido th, #contenido2 th {
            /* border: 1px solid #ddd; */
            padding-left: 8px;
            padding-right: 8px;
            padding-top: 4px;
            padding-bottom: 4px;
            background-color: #e9ecef;
        }

        .titulo {
            font-size: 15px;
            font-weight: 1000;
        }

        .contenedor {
            margin-bottom: 20px;
            margin-right: 20px;
            
        }
        .contenedor2 {
            margin-bottom: 20px;
            margin-right: 20px;               
        }

        .izquierda {
            text-align: left;
        }

        .derecha {
            text-align: right;
        }

        td.informa {
            width: 320px;
            text-align: left;
        }

        td.cliente {
            width: 320px;
            text-align: left;
        }

       

        td.subtitulo,
        th.subtitulo {
            width: 150px;
            font-size: 10px;            
        }    

        td.subtitulo_cabecera_derecha,
        th.subtitulo_cabecera_derecha {
            width: 150px;
            font-size: 14px;            
        }

        td.subtitulo_cabecera_izquierda,
        th.subtitulo_cabecera_izquierda {
            width: 220px;
            font-size: 11px;            
        }

        th.vacio {
            width: 75px;
        }

        .imagen {
            width: 320px;
        }

        th.conceptoCol,
        td.conceptoCol {
            width: 240px;
        }             

        th.conceptoColOnly,
        td.conceptoColOnly {
            width: 252px;
        } 

        th.totalesCol,
        td.totalesCol {
            width: 400px;
        }
        
        th.totalesColValue,
        td.totalesColValue {
            width: 100px;
        }

        th.importeCol,
        td.importeCol {
            width: 55px;
        }
        td.importeColMini,
        th.importeColMini{
            width: 35px;
            text-align: center;
        }
        td.importeColMiniPlus,
        th.importeColMiniPlus{
            width: 15px;
            text-align: center;
        }
        
        td.importeColMiniRef,
        th.importeColMiniRef{
            width: 25px;
        }

        th.importeCol{
            text-align: center;
        }

        .rgpd {
            font-size: 10px;
            margin-left: 40px;
            margin-right: 40px;
            color: #606060;
            margin-top: 5px;
            margin-bottom: 5px;        
            text-align: center;
        }
        .rgpd_final {
            font-size: 10px;
            margin-left: 40px;
            margin-right: 40px;
            color: #606060;
            margin-top: 5px;
            margin-bottom: 60px;        
            text-align: center;
        }

        .cuentasbancarias{
            font-size: 11px;            
            margin-right: 40px;
            color: #606060;
            margin-top: 8px;      
            margin-left: 3px;          
            text-align: left;
        }

        .cuerpo{
            top: 80px;
        }

        .subrayado {
            border-bottom: solid 3px blue;
        }

        .principal {
            margin-left: 34px;  
            font-size: 11px;          
        }

        .contLogo {
            margin-left: 70px;                      
        }

        .divisor {
            width: 690px;            
            border: 0.2px solid black;
        }
        .cont_logo{                                    
            width: 380px;
            height: auto;
            position:absolute;
            left: 25px;
            top: -40px;            
        }
        img.logo_documento{
            width: 100%;
        }
        .contTextoslogo {
            margin-left: 35px;  
                                      
        }
        .texto-logo-derecha{
            font-size: 12px;         
            width: 40px;   
        }
        
        .texto-logo-izquierda{
            font-size: 12px;         
            width: 200px;   
        }
           
            
        td.totNumber,
        th.totNumber {            
            font-size: 11px;              
        }
        
        td.totText,
        th.totText {            
            font-size: 10px;              
        }

        .texto_centrado{
            text-align: justify;
        }
      
    </style>
    
    <page style="font-family: Arial, sans-serif;"  footer='page' backtop="10mm">

            <?php                       
                $cabecera = $datos['cabecera'];                               
            ?>

            <div class="cont_logo">
                <img class="logo_documento" src="<?php echo $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);  ?>/img/logo_telesat.jpg">
            </div>            

            <table class="contLogo">
                <tr>
                    <td rowspan='11' class='imagen'></td>
                    <td class='titulo' style="color:#048bd3;">
                        <span>Tecnologías Aplicadas</span>
                        <br>
                        <span>Telesat</span>
                    </td>
                    <td class='titulo'></td>
                </tr>
                <?php
                
                
                echo "
                    <tr>
                        <td class='subtitulo_cabecera_izquierda'>CIF: <span style='text-transform:uppercase;'>".NIF_INFOMALAGA."</span></td>
                        <td class='subtitulo_cabecera_derecha'></td> 
                    </tr>
                    <tr>
                        <td class='subtitulo_cabecera_izquierda'>".DIRECCION_INFOMALAGA."</td>
                        <td class='subtitulo_cabecera_derecha'></td>
                    </tr>
                    <tr>
                        <td class='subtitulo_cabecera_izquierda'>" . CODIGO_POSTAL_INFOMALAGA." - ".LOCALIDAD_INFOMALAGA . " - " . PROVINCIA_INFOMALAGA. "</td>
                        <td class='subtitulo_cabecera_derecha'></td>
                    </tr>
                    <tr>
                        <td class='subtitulo_cabecera_izquierda'>Telf. " . TELEFONO_FIJO." | Móvil: ".TELEFONO_MOVIL . "</td>
                        <td class='subtitulo_cabecera_derecha'></td>
                    </tr>
                    <tr>
                        <td class='subtitulo_cabecera_izquierda' style='color:#048bd3;'>Email: " . CUENTA_CORREO."</td>
                        <td class='subtitulo_cabecera_derecha'></td>
                    </tr>";
                    
                ?>
            </table>

            <br>          

            
            <div class="principal">
                <?php
                                        
                  
                        echo "        
                        
                            <div class='divisor'></div>

                            <div class='contenedor'>             
                                                           
                                <br>

                                <table id='datosEmpresas'>
                                    
                                    <tbody>
                                        <tr>                                 
                                            <td>Cliente: " . $cabecera->idcliente . "</td>           
                                            <td class='informa'  style='text-transform:capitalize;'><b>".$datos['tipo'] ." &nbsp;&nbsp; <span style='font-size:14px;'>". $cabecera->numero ."</span></b></td>
                                        </tr>
                                        <tr>
                                            <td class='cliente'>Nombre: " . $cabecera->cliente . "</td>
                                            <td><b>Fecha &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> ".date('d/m/Y', strtotime($cabecera->fecha))."</td>
                                        </tr>
                                        <tr>                           
                                            <td>CIF: " . $cabecera->cif . "</td>
                                            <td></td>
                                        </tr>
                                        <tr>                                 
                                            <td>Dirección: " . $cabecera->direccion . "</td>           
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>CP: " . $cabecera->codigopostal . " Población: " . $cabecera->poblacion . " - ". $cabecera->provincia . "</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Telf. | Móvil</td>           
                                            <td></td>
                                        </tr>                                            
                                    </tbody>
                                </table>

                            </div>                       
                        
                            <div class='divisor'></div>

                            <div class='contenedor'>

                                <table id='contenido'>
                                    <thead>
                                        <tr>                                        
                                            <th scope='col' class='subtitulo importeColMiniRef'>Ref</th>
                                            <th scope='col' class='subtitulo conceptoColOnly'>Concepto</th>
                                            <th scope='col' class='subtitulo importeColMini'>Cant.</th>
                                            <th scope='col' class='subtitulo importeCol'>Precio</th>
                                            <th scope='col' class='subtitulo importeColMiniRef'>%Dscto</th>
                                            <th scope='col' class='subtitulo importeCol'>Importe</th>
                                            <th scope='col' class='subtitulo importeColMiniPlus'>IVA</th>
                                        </tr>
                                    </thead>
                                    <tbody>";                                        
                                        
                                        if(isset($datos['detalle']) && count($datos['detalle']) > 0){
                                            foreach ($datos['detalle'] as $fila) {
                                                echo"
                                                <tr>                                        
                                                    <td class='derecha'>".((!empty($fila->idproducto))?$fila->idproducto:'')."</td>
                                                    <td class='conceptoCol texto_centrado'>".$fila->descripcion."</td>     
                                                    <td class='derecha'>".$fila->cantidad."</td>
                                                    <td class='derecha'>".number_format($fila->precio,'2',',','.')."</td>
                                                    <td class='derecha'>".number_format($fila->descuento,'2',',','.')."</td>
                                                    <td class='derecha'>".number_format($fila->subtotal,'2',',','.')."</td>
                                                    <td class='derecha'>".$fila->ivatipo."%</td>
                                                </tr>
                                                ";
                                            }
                                        }
                                        echo"
                                    </tbody>
                                </table>

                             

                                <br><br>"; 
                                
                                
                                if(isset($cabecera->observaciones) && $cabecera->observaciones != ''){
                                    echo"<div class='cuentasbancarias'>Observaciones: ".$cabecera->observaciones." </div>";
                                }
                                

                                
                                if(!empty($cabecera->vencimiento) && $cabecera->vencimiento != '0000-00-00'){
                                    echo"
                                    <div class='cuentasbancarias'>Fecha de vencimiento: ".((isset($cabecera->vencimiento) && $cabecera->vencimiento > 0)? date("d-m-Y",strtotime($cabecera->vencimiento)): '')." </div>";
                                }
                                

                                
                                if(isset($cabecera->formapago)){
                                    echo"
                                    <div class='cuentasbancarias'><div>
                                    Forma de pago: ".(($cabecera->formapago && $cabecera->formapago != '')? $cabecera->formapago: '')." </div>
                                    <div>".((isset($datos['rectificativa']) && $datos['rectificativa'] > 0)? 'Factura origen: '.$datos['numFacturaOrigen']: '')." </div></div>";
                                        
                                }                                                                                              

                                echo"
                            </div>                                                
                            ";
                    
                            
                ?>
            </div>
        
      


        <page_footer>

            <?php                        
                echo"<div class='principal'>
                        <div class='divisor'></div>";

                        $baseimponible = (isset($datos['baseimponiblesindscto']))? $datos['baseimponiblesindscto']: 0;   
                        $descuentoimporte = ((isset($datos['descuento']) && $datos['descuento'] !='')? $datos['descuento']: 0);                    
                        $subtotal = $baseimponible - $descuentoimporte;
                        $ivatotal = (isset($cabecera->ivatotal))? $cabecera->ivatotal: 0;   
                        $totalcalculado = $baseimponible - $descuentoimporte + $ivatotal;

                echo"
                        <div class='contenedor2'>
                            <table id='contenido2'>
                                <tbody>                         
                                <tr>
                                    <td class='derecha totalesCol subtitulo totText'></td>
                                    <td class='derecha totalesColValue totNumber' style='background-color: #e9ecef;'><b>Importe</b></td>
                                    <td class='derecha totalesColValue totNumber'>" . number_format($baseimponible, '2', ',', '.') . " €</td>
                                </tr>

                                <tr>
                                    <td class='derecha totalesCol subtitulo totText'></td>
                                    <td class='derecha totalesColValue totNumber' style='background-color: #e9ecef;'><b>Descuento</b></td>
                                    <td class='derecha totalesColValue totNumber'>" .  number_format($descuentoimporte, '2', ',', '.') . " €</td>
                                </tr>

                                <tr>
                                    <td class='derecha totalesCol subtitulo totText'></td>
                                    <td class='derecha totalesColValue totNumber' style='background-color: #e9ecef;'><b>Subtotal </b></td>
                                    <td class='derecha totalesColValue totNumber'>" . number_format($subtotal, '2', ',', '.') . " €</td>
                                </tr>    

                                <tr>
                                    <td class='derecha totalesCol subtitulo totText'></td>
                                    <td class='derecha totalesColValue totNumber' style='background-color: #e9ecef;'><b>IVA </b></td>
                                    <td class='derecha totalesColValue totNumber'>" . number_format($ivatotal, '2', ',', '.') . " €</td>
                                </tr> 

                                <tr>
                                    <td class='derecha totalesCol subtitulo totText'></td>
                                    <td class='derecha totalesColValue totNumber' style='background-color: #e9ecef;'><b>TOTAL</b></td>
                                    <td class='derecha total totalesColValue totNumber'>" . number_format($totalcalculado, '2', ',', '.') . " €</td>
                                </tr>  
                                </tbody>
                            </table>
                        </div>

                        <div class='divisor'></div>
                    </div>";            
            
            ?>

            <?php
            
                
                    echo"
                    <div class='rgpd'><b>".CUENTA_BANCARIA."</b></div>";
                                    
            ?>
            <div class='rgpd'><?php echo GARANTIA;?></div>
            <div class='rgpd'><b>GRACIAS POR SU CONFIANZA</b></div>
            <div class="rgpd_final">              
                <?php echo INSCRIPCION; ?>
            </div>            
        </page_footer>
    </page>
