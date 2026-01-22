
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
            border: 1px solid #ddd;
            padding-left: 8px;
            padding-right: 8px;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        #numFactura th,
        #datosEmpresas th,
        #contenido th, #contenido2 th {
            border: 1px solid #ddd;
            padding-left: 8px;
            padding-right: 8px;
            padding-top: 4px;
            padding-bottom: 4px;
            background-color: #cce2fe;
        }

        .titulo {
            font-size: 18px;
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
            width: 245px;
            text-align: left;
        }

        td.cliente {
            width: 280px;
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
            width: 100px;
            font-size: 14px;            
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
            width: 537px;
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
            width: 710px;
            margin-left: 34px;
            border: 2px solid #048bd3;
        }
        .cont_logo{                                    
            width: 180px;
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
            font-size: 12px;              
        }
        
        td.totText,
        th.totText {            
            font-size: 10px;              
        }

        .texto_centrado{
            text-align: justify;
        }
      
    </style>
    
    <page style="font-family: Arial, sans-serif;"  footer='page' backtop="20mm">

            <?php                       
                $cabecera = $datos['cabecera'];                               
            ?>

            <div class="cont_logo">
                <img class="logo_documento" src="<?php echo $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);  ?>/img/logo_telesat.jpg">
            </div>            

            <table class="contLogo">
                <tr>
                    <td rowspan='11' class='imagen'></td>
                    <td class='titulo' style="text-transform:uppercase;">
                        <b>
                            <?php                             
                                echo $datos['tipo'];                
                            ?>
                        </b>
                    </td>
                    <td class='titulo'></td>
                </tr>
                <?php
                
                echo "
                    <tr>
                        <td class='subtitulo_cabecera_izquierda'><b>  Nº <span style='text-transform:capitalize;'>".$datos['tipo']."</span>:</b></td>
                        <td class='subtitulo_cabecera_derecha'>" . $cabecera->numero . "</td> 
                    </tr>
                    <tr>
                        <td class='subtitulo_cabecera_izquierda'><b>  Fecha ".$datos['tipo'].":</b></td>
                        <td class='subtitulo_cabecera_derecha'>" . date('d-m-Y', strtotime($cabecera->fecha)) . "</td>
                    </tr>
                    <tr>
                        <td class='subtitulo_cabecera_izquierda'><b>  Cliente Nº:</b></td>
                        <td class='subtitulo_cabecera_derecha'>" . $cabecera->idcliente . "</td>
                    </tr>";
                    
                ?>
            </table>

            <br>

            <table class="contTextoslogo">
                <tr>
                    <td class="texto-logo-derecha">Teléfonos</td>
                    <td>:</td>
                    <td class="texto-logo-izquierda">  <?php echo TELEFONO; ?> </td>
                </tr>       
                <tr>
                    <td class="texto-logo-derecha">Email</td>
                    <td>:</td>
                    <td class="texto-logo-izquierda">  info@instalacionestelesat.es</td>
                </tr>                      
            </table>

            <br>

            <div class="divisor"></div>
            
            <div class="principal">
                <?php
                                        
                    $descuentoimporte = ((isset($datos['descuento']) && $datos['descuento'] !='')? $datos['descuento']: 0);
                    
                    $subtotal = number_format($cabecera->baseimponible - $descuentoimporte, '2', ',', '.');

                    $totalcalculado = number_format($cabecera->baseimponible - $descuentoimporte + $cabecera->ivatotal , '2', ',', '.');

                        echo "                        
                            <div class='contenedor'>             
                            
                                <div style='margin-left:415px;'><b>PARA:</b></div>
                            
                                <br>

                                <table id='datosEmpresas'>
                                    
                                    <tbody>
                                        <tr>
                                            <th scope='row'>EMPRESA</th>
                                            <td class='informa'>".NOMBRE_FISCAL_INFOMALAGA."</td>
                                            <td class='cliente'>" . $cabecera->cliente . "</td>
                                        </tr>
                                        <tr>
                                            <th scope='row'>CIF</th>
                                            <td>".NIF_INFOMALAGA."</td>
                                            <td>" . $cabecera->cif . "</td>
                                        </tr>
                                        <tr>
                                            <th scope='row'>DIRECCIÓN</th>
                                            <td>".DIRECCION_INFOMALAGA."</td>
                                            <td>" . $cabecera->direccion . "</td>
                                        </tr>
                                        <tr>
                                            <th scope='row'>CP Y POBLACIÓN</th>
                                            <td>".CODIGO_POSTAL_INFOMALAGA." ".LOCALIDAD_INFOMALAGA."</td>
                                            <td>" . $cabecera->codigopostal . " - " . $cabecera->poblacion . "</td>
                                        </tr>
                                        <tr>
                                            <th scope='row'>PROVINCIA</th>
                                            <td>".PROVINCIA_INFOMALAGA."</td>
                                            <td>" . $cabecera->provincia . "</td>
                                        </tr>                                            
                                    </tbody>
                                </table>

                            </div>                       
                        
                      

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

                                <br>

                                <div class='contenedor2'>
                                    <table id='contenido2'>
                                        <tbody>                                        
                                        <tr>
                                            <td class='derecha totalesCol subtitulo totText'><b>BASE IMPONIBLE</b></td>
                                            <td class='derecha totalesColValue totNumber'><b>" . number_format($cabecera->baseimponible, '2', ',', '.') . "</b></td>
                                        </tr>

                                        <tr>
                                            <td class='derecha totalesCol subtitulo totText'><b>DESCUENTO</b></td>
                                            <td class='derecha totalesColValue totNumber'><b>" .  number_format($descuentoimporte, '2', ',', '.') . " </b></td>
                                        </tr>
                                                                       
                                        <tr>
                                            <td class='derecha totalesCol subtitulo totText'><b>SUBTOTAL </b></td>
                                            <td class='derecha totalesColValue totNumber'><b>" . $subtotal . " </b></td>
                                        </tr>    
                                        
                                        <tr>
                                            <td class='derecha totalesCol subtitulo totText'><b>IVA </b></td>
                                            <td class='derecha totalesColValue totNumber'><b>" . number_format($cabecera->ivatotal, '2', ',', '.') . " </b></td>
                                        </tr> 

                                        <tr>
                                            <td class='derecha totalesCol subtitulo totText total' style='background-color:#84bbd9; color:#053669;'><b>TOTAL</b></td>                                            
                                            <td class='derecha total totalesColValue totNumber' style='background-color:#84bbd9; color:#053669;'><b>" . $totalcalculado . " </b></td>
                                        </tr>  
                                        </tbody>
                                    </table>
                                </div>

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
            
                if(!empty($datos['cuentasBancarias'])) {   
                    echo"
                    <div class='rgpd'><b>".$datos['cuentasBancarias']."</b></div>";
                }    
            ?>
            <div class='rgpd'><?php echo GARANTIA;?></div>
            <div class='rgpd'><b>GRACIAS POR SU CONFIANZA</b></div>
            <div class="rgpd_final">              
                <?php echo INSCRIPCION; ?>
            </div>            
        </page_footer>
    </page>
