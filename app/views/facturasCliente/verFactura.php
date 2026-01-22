<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>



    <div class="w-full overflow-x-hidden border-t flex flex-col">
    
        <main class="w-full flex-grow p-2">        

            <div class="container mx-auto px-1 xl:px-2">
                <span id="mensajeValidacion" class="text-xl font-bold text-pink-600 mx-4"></span>
                
                <?php                
                    if ($_SESSION['nombrerol'] == 'admin') {             
                        
                        
                ?>
                <div class="py-1">                    

                        <div class="grid grid-cols-1 bg-white rounded-lg shadow-xl w-100 mb-5" id="contenedorNuevoProveedor">

                            <div class="flex items-start justify-between p-3 border-b border-solid border-blueGray-200 rounded-t">
                                <h1 class="text-center text-sm lg:text-base uppercase texto-violeta-oscuro font-semibold pt-1">Ver factura <?php echo (isset($datos['rectificativa']) && $datos['rectificativa']==1)? 'RECTIFICATIVA': '';?></h1>    
                                
                                <div class="flex items-start justify-between">

                                    <a class="w-auto bg-white-300 border-2 border-red-300 hover:bg-red-500 rounded-lg shadow-xl font-medium text-xs text-red-500 hover:text-white px-4 py-1 mr-3 flex gap-2 items-center"  id="generar_pdf" style="cursor:pointer;"><i class="fa fa-file-pdf" style="font-size: 1.25rem;"></i><span>PDF</span></a>

                                    <a class="w-auto bg-white-300 border-2 border-gray-300 hover:bg-gray-500 rounded-lg shadow-xl font-medium text-xs text-gray-500 hover:text-white px-4 py-1 mr-3 flex gap-2 items-center"  id="enviar_email" style="cursor:pointer;"><i class="fa fa-envelope" style="font-size: 1.25rem;"></i><span>EMAIL</span></a>
                                    
                                </div>
                                
                            </div>

                            <div class="flex flex-wrap" id="tabs-id">
                                <div class="w-full">

                                    <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
                                    
                                        <li class="my-1 mr-2 last:mr-0 flex-auto text-center">
                                            <a class="tab-factura text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-white bg-violeta-oscuro" data-tab="tab-profile">
                                            <i class="fas fa-space-shuttle text-base mr-1"></i>  Datos de la factura
                                            </a>
                                        </li>
                                        <li class="my-1 mr-2 last:mr-0 flex-auto text-center">
                                            <a class="tab-factura text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal texto-violeta-oscuro bg-white" data-tab="tab-settings" data-metodo="verSucursalesProveedor">
                                            <i class="fas fa-cog text-base mr-1"></i>  Envíos de factura
                                            </a>
                                        </li>                                
                                                        
                                    </ul>

                                    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6">
                                        <div class="px-4 py-1 flex-auto">
                                            <div class="tab-content tab-space">

                                                <div class="block pastillaTabsFactura" id="tab-profile">
                                                    <form id="formVerFactura" >

                                                            <input type="hidden" name="id" id="id" value="<?php echo $datos['documento']->id?>">

                                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-2 md:gap-4 m-2">

                                                                <div class="grid grid-cols-1">
                                                                    
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Número factura</label>
                                                                    <input type="text" id="numero" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" value="<?php echo $datos['documento']->numero;?>" readonly> 
                                                                </div>

                                                                <div class="grid grid-cols-1 md:col-span-2">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Razón Social (*)</label>
                                                                    
                                                                    <div class="cont_select_dinamic">
                                                                        <select name="idcliente" id="idcliente" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"  placeholder="Razón social" value="">
                                                                            
                                                                            <?php
                                                                                echo'<option value="'.$datos['documento']->idcliente.'">'.$datos['documento']->cliente.'</option>';
                                                                            ?>

                                                                        </select>
                                                                        <i class="fa fa-search" id="boton_buscar_cliente"></i>
                                                                    </div>
                                                                    
                                                                    <span class="mensaje_required" id="error_idcliente"></span>
                                                                </div>

                                                                <div class="grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">CIF/NIF(*)</label>
                                                                    <input type="text" regexp="[a-zA-Z0-9]{0,9}"  name="cif" id="cif" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="CIF" value="<?php echo $datos['documento']->cif;?>" readonly style="background-color: rgb(225, 217, 217);text-transform: uppercase;">
                                                                    <span class="mensaje_required" id="error_cif"></span>
                                                                    <input type="hidden" id="cifguardar" name="cifguardar" value="0" >
                                                                </div>

                                                                <div class="grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Fecha(*)</label>
                                                                    <input type="date" name="fecha" id="fecha" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" value="<?php echo $datos['documento']->fecha;?>" />
                                                                    <span class="mensaje_required" id="error_fecha"></span>
                                                                </div>

                                                              
                                                            </div>

                                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 md:gap-4 m-2">

                                                                <div class="grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Situación (*)</label>
                                                                    <select name="estado" id="estado" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                                                                        <option value="impagado" <?php echo ($datos['documento']->estado=='impagado')? 'selected':'';?>>Impagado</option>
                                                                        <option value="pagado" <?php echo ($datos['documento']->estado=='pagado')? 'selected':'';?>>Pagado</option>
                                                                        <option value="pagado parcial" <?php echo ($datos['documento']->estado=='pagado parcial')? 'selected':'';?>>Pagado parcial</option>
                                                                    </select>
                                                                    <span class="mensaje_required" id="error_estado"></span>
                                                                </div>


                                                                <div class="grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Forma de pago</label>
                                                                    <select name="idformacobro" id="idformacobro" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">

                                                                        <option value="" disabled selected>Seleccionar</option>
                                                                        <?php

                                                                            if(isset($datos['formasdepago']) && count($datos['formasdepago'])>0){

                                                                                $formasdepago = $datos['formasdepago'];

                                                                                foreach ($formasdepago as $fp) {
                                                                                    $selectedFp = ($fp->id==$datos['documento']->idformacobro)? 'selected':'' ;
                                                                                    echo'<option value="'.$fp->id.'" '.$selectedFp.'>'.$fp->formadepago.'</option>';
                                                                                }
                                                                            }             
                                                                        ?>        
                                                                    </select>
                                                                    
                                                                </div>
                                                            
 
                                                            
                                                                <div class="grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Pago a </label>
                                                                    <?php
                                                                        $diasDisabled = '';
                                                                        $vencimientoDisabled = '';
                                                                        $bgInput = 'white';

                                                                        if($datos['documento']->idformacobro == 1){
                                                                            $diasDisabled = 'disabled';                   
                                                                            $vencimientoDisabled = 'disabled';
                                                                            $bgInput = '#e1d9d9';
                                                                        };
                                                                    ?>
                                                                    <input name="diascobro" id="diascobro" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Días de pago" <?php echo $diasDisabled;?> style="background-color: <?php echo $bgInput;?>;" value="<?php echo $datos['documento']->diascobro;?>" />
                                                                    
                                                                </div>

                                                                <div class="grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Vencimiento</label>
                                                                    <input name="vencimiento" id="vencimiento" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" <?php echo $vencimientoDisabled;?> style="background-color: <?php echo $bgInput;?>;" type="date" value="<?php echo $datos['documento']->vencimiento;?>"  />
                                                                    
                                                                </div>
                                                                
                                                                <div class="grid grid-cols-1" style="display:none;">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Cuenta bancaria</label>
                                                                    <select name="idcuentabancaria" id="idcuentabancaria" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">              
                                                                        <option value="" disabled selected>Seleccionar</option>
                                                                    </select>
                                                                </div>                                                               
                                                            </div>



                                                            <div class="grid grid-cols-1 gap-2 md:gap-4 m-2">
                                                            
                                                                <div class="flex items-center justify-start pt-3 border-t border-solid border-blueGray-200 rounded-b">
                                                                    <a class="agregar_linea_documento w-auto bg-green-500 hover:bg-green-700 rounded-lg shadow-xl font-medium text-xs text-white px-4 py-1 mr-3" data-linea="descripcion">Nueva línea</a>
                                                                    <a class="agregar_linea_documento w-auto bg-blue-500 hover:bg-blue-700 rounded-lg shadow-xl font-medium text-xs text-white px-4 py-1 mr-3" data-linea="articulo">Agregar artículo</a>
                                                                </div>

                                                                <div class="row container_products">
                                                                    <?php require(RUTA_APP . '/views/facturasCliente/grilla_desktop.php'); ?>
                                                                </div>  

                                                            </div>


                                                            <div class="grid grid-cols-1 gap-2 md:gap-4 m-2">                            
                                                                <div class="grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Observaciones</label>
                                                                    <textarea name="observaciones" id="observaciones" class="w-full py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"><?php echo $datos['documento']->observaciones;?></textarea>
                                                                </div>
                                                            </div>


                                                            <div class="grid grid-cols-1 gap-2 md:gap-4 m-2">   
                                                                <div class="col-md-6 mt-3">
                                                                    <div class="container_totales">
                                                                        <div class="cont_table_totales">
                                                                                <table class="table table-sm table-bordered">  
                                                                                    
                                                                                    <?php
                                                                                        $baseimponible = (isset($datos['baseimponiblesindscto']))? $datos['baseimponiblesindscto']: 0;

                                                                                        $descuento = (isset($datos['descuento']))? $datos['descuento']: 0;

                                                                                        $ivatotal = (isset($datos['ivatotal']))? $datos['ivatotal']: 0;

                                                                                        $subtotal = $baseimponible - $descuento;

                                                                                        $tot = $baseimponible - $descuento + $ivatotal;
                                                                                    ?>

                                                                                    <tr>                                            
                                                                                            <td class="cell_total"><strong>Base Imponible</strong></td>
                                                                                            <td class="cell_total_amount"><span id="baseimponible_importe"><?php echo number_format($baseimponible,2,',','');?></span></td>
                                                                                    </tr>       
                                                                                  
                                                                                    <tr>                                            
                                                                                            <td class="cell_total"><strong>Descuento</strong></td>
                                                                                            <td class="cell_total_amount"><span id="dsctototal_importe"><?php echo number_format($descuento,2,',','');?></span></td>                                                
                                                                                    </tr>
                                                                                                          
                                                                                    <tr>                                            
                                                                                            <td class="cell_total"><strong>Subtotal</strong></td>
                                                                                            <td class="cell_total_amount"><span id="subtotal_importe"><?php echo number_format($subtotal,2,',','');?></span></td>                                                
                                                                                    </tr> 

                                                                                    <tr>                                            
                                                                                            <td class="cell_total"><strong>IVA</strong></td>
                                                                                            <td class="cell_total_amount"><span id="ivatotal_importe"><?php echo number_format($ivatotal,2,',','');?></span></td>                                                
                                                                                    </tr>

                                                                                    <tr>                                            
                                                                                            <td class="cell_total"><strong>Total</strong></td>
                                                                                            <td class="cell_total_amount"><span id="total_importe"><?php echo number_format($tot,2,',','');?></span></td>
                                                                                    </tr>                                        
                                                                                    </tbody>
                                                                                </table>
                                                                        </div>
                                                                    </div>  
                                                                </div>
                                                            </div>                                                            

                                                            <div class="flex items-center justify-center px-6 pt-3 border-t border-solid border-blueGray-200 rounded-b">
                                                                <a class="cancelarCerrar w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-xs md:text-sm lg:text-base text-white px-4 py-1 mr-3"  href="<?php echo RUTA_URL; ?>/FacturasCliente">Cerrar</a>
                                                                <button id="actualizarFactura" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-xs md:text-sm lg:text-base text-white px-4 py-1 mr-3" type="submit">Guardar</button>
                                                                
                                                            </div>
                                                    </form>
                                                </div>

                                                <div class="hidden pastillaTabsFactura" id="tab-settings">
                                                    Cargando datos ...
                                                </div>    
                                            
                                            </div>
                                        </div>
                                    </div>
        
                                </div>
                            </div>

                        </div>

                </div>
                <?php
                    }
                ?>
            </div>
            
              
    
        
        </main>

    </div>





</div>

</main> <!--Esta etiqueta Main es el fin del sidebar -->


<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>
<?php require_once(RUTA_APP . '/views/facturasCliente/modalEnviarEmailFactura.php'); ?>
<?php require_once(RUTA_APP . '/views/facturasCliente/modalBuscarCliente.php'); ?>
<?php require_once(RUTA_APP . '/views/facturasCliente/modalBuscarProducto.php'); ?>