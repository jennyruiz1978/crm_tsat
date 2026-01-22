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
                                <h1 class="text-center text-sm lg:text-base uppercase texto-violeta-oscuro font-semibold pt-1">Crear factura</h1>                                
                            </div>

                            <div class="flex flex-wrap" id="tabs-id">
                                <div class="w-full">

                                    <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
                                    
                                        <li class="my-1 mr-2 last:mr-0 flex-auto text-center">
                                            <a class="tab-proveedores text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-white bg-violeta-oscuro" data-tab="tab-profile">
                                            <i class="fas fa-space-shuttle text-base mr-1"></i>  Datos de la factura
                                            </a>
                                        </li>
                                        <li class="my-1 mr-2 last:mr-0 flex-auto text-center">
                                            <a class="tab-proveedores text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal texto-violeta-oscuro bg-white" data-tab="tab-settings" data-metodo="verSucursalesProveedor">
                                            <i class="fas fa-cog text-base mr-1"></i>  Otros
                                            </a>
                                        </li>                                
                                                        
                                    </ul>

                                    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6">
                                        <div class="px-4 py-1 flex-auto">
                                            <div class="tab-content tab-space">

                                                <div class="block" id="tab-profile">                    
                                                    <form id="formAltaFactura" >

                                                            

                                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-2 md:gap-4 m-2">

                                                                <div class="container-field-factura grid grid-cols-1 md:col-span-2">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Razón Social (*)</label>

                                                                    <div class="cont_select_dinamic">
                                                                        <select name="idcliente" id="idcliente" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"  placeholder="Razón social" value="">
                                                                                       

                                                                        </select>
                                                                        <i class="fa fa-search" id="boton_buscar_cliente"></i>
                                                                    </div>

                                                                    <span class="mensaje_required" id="error_idcliente"></span>
                                                                </div>

                                                                <div class="container-field-factura grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">CIF/NIF(*)</label>
                                                                    <input type="text" regexp="[a-zA-Z0-9]{0,9}" name="cif" id="cif" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="CIF" value="" style="text-transform: uppercase;">
                                                                    <span class="mensaje_required" id="error_cif"></span>
                                                                    <input type="hidden" id="cifguardar" name="cifguardar" value="0">
                                                                </div>

                                                                <div class="container-field-factura grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Fecha(*)</label>
                                                                    <input type="date" name="fecha" id="fecha" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"value="" />
                                                                    <span class="mensaje_required" id="error_fecha"></span>
                                                                </div>

                                                                <div class="container-field-factura grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Situación (*)</label>
                                                                    <select name="estado" id="estado" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                                                                        <option value="impagado">Impagado</option>
                                                                        <option value="pagado">Pagado</option>
                                                                        <option value="pagado parcial">Pagado parcial</option>
                                                                    </select>
                                                                    <span class="mensaje_required" id="error_estado"></span>
                                                                </div>

                                                            </div>

                                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 md:gap-4 m-2">

                                                                <div class="container-field-factura grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Forma de pago</label>
                                                                    <select name="idformacobro" id="idformacobro" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">

                                                                        <option value="" disabled selected>Seleccionar</option>
                                                                        <?php

                                                                            if(isset($datos['formasdepago']) && count($datos['formasdepago'])>0){

                                                                                $formasdepago = $datos['formasdepago'];

                                                                                foreach ($formasdepago as $fp) {
                                                                                    echo'<option value="'.$fp->id.'">'.$fp->formadepago.'</option>';
                                                                                }
                                                                            }             
                                                                        ?>        
                                                                    </select>
                                                                    
                                                                </div>          
                                                            
                                                                <div class="container-field-factura grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Pago a </label>
                                                                    <input name="diascobro" id="diascobro" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Días de pago" value="" />
                                                                    
                                                                </div>

                                                                <div class="container-field-factura grid grid-cols-1">
                                                                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Vencimiento</label>
                                                                    <input name="vencimiento" id="vencimiento" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="date" value="" />
                                                                    
                                                                </div>

                                                                <div class="container-field-factura grid grid-cols-1">
                                                                    <label for="rectificativa" class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">
                                                                        Rectificativa
                                                                    </label>
                                                                    <input 
                                                                        name="rectificativa" 
                                                                        id="rectificativa" 
                                                                        type="checkbox"
                                                                        class="mt-1 h-5 w-5 text-blue-700 focus:ring-blue-700 border-gray-300 rounded"
                                                                    />
                                                                </div>

                                                                
                                                                <div class="container-field-factura grid grid-cols-1" style="display:none;">
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
                                                                    <textarea name="observaciones" id="observaciones" class="w-full py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="flex items-center justify-center px-6 pt-3 border-t border-solid border-blueGray-200 rounded-b">
                                                                <a class="cancelarCerrar w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-xs md:text-sm lg:text-base text-white px-4 py-1 mr-3" href="<?php echo RUTA_URL; ?>/FacturasCliente" >Cerrar</a>
                                                                <button class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-xs md:text-sm lg:text-base text-white px-4 py-1 mr-3" type="submit">Guardar</button>
                                                            </div>
                                                    </form>
                                                </div>

                                                <div class="hidden pastilla" id="tab-settings"></div>    
                                            
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
<?php require_once(RUTA_APP . '/views/facturasCliente/modalBuscarCliente.php'); ?>
<?php require_once(RUTA_APP . '/views/facturasCliente/modalBuscarProducto.php'); ?>
