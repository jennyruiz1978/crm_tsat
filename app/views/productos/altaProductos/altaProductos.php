<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">

    <main class="w-full flex-grow p-6">       
        
                
        <div class="flex items-center justify-center  mt-8">
            <div class="grid bg-white rounded-lg shadow-xl w-11/12">
              
                <div class="mt-6 ml-6">
                    <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Alta artículo</h2>     
                    <span id="msgValidaCliente" class="font-bold font-bold text-pink-600"></span> 
                </div>
                                
                <form id="formAltaProducto">
                
                    <input name="idProducto" id="idProducto" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 xl:grid-cols-6 gap-5 md:gap-8 mt-5 mx-7">
                    
                        <div class="grid grid-cols-1 md:col-span-2">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Nombre de artículo</label>
                            <input name="nombre" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Nombre" required/>
                        </div>
        
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Stock</label>
                            <input name="stock" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="number" placeholder="Stock"/>
                        </div>

                        <div class="grid grid-cols-1" style="display:none;">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Unidad</label>
                            <input name="unidad" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Unidad"/>
                        </div>

                        
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">IVA</label>
                            <select name="iva" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                                <?php                            
                                    if(isset($datos['tiposiva']) && count($datos['tiposiva']) > 0){
                                        foreach ($datos['tiposiva'] as $tipo) {

                                            $selected = ($tipo->tipoiva==TIPO_IVA_DEFAULT)? 'selected': '';
                                            
                                            echo'<option value="'.$tipo->tipoiva.'" '.$selected.'>'.$tipo->tipoiva.'%</option>';
                                        }
                                    }
                                ?>                            
                            </select>
                        </div>    
                    
                    
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Estado</label>
                            <select name="estado" id="estado" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">                            
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>                            
                            </select>
                        </div>

                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Marca</label>
                            <input name="marca" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Marca"/>
                        </div>
                    </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 md:gap-8 mt-5 mx-7">
                    
         
                                                  
                                     
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">                            
                    <div class="grid grid-cols-1">
                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="w-full py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"></textarea>
                    </div>
                </div>

                <div class="md:gap-8 mt-5 mx-7">

                        <div class="inline-flex m-2">                       
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold mr-4 ">Proveedores / Precios</label>
                                <a id="addProveedor" title="Agregar proveedor" class="rounded-full h-6 w-6 flex items-center justify-center  bg-blue-700 text-white flex-1"><i class="fas fa-plus-circle"></i></a>                        
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-2 md:gap-4 m-2">
                            <div class="grid grid-cols-1">
                                <table id="tablaProveedoresPrecios">

                                    <thead>
                                    <tr>                            
                                                <td width="35%" class="font-bold">Proveedor</td>
                                                <td width="20%" class="font-bold">Referencia</td>
                                                <td width="15%" class="font-bold">Coste</td>
                                                <td width="15%" class="font-bold" title="Margen">%</td>
                                                <td width="15%" class="font-bold" title="Precio de venta">P.Vta.</td>
                                                <td width="10%" class="font-bold" title="Preferente">Pfte.</td>
                                                <td width="10%" class="font-bold"></td>
                                            </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                            
                                </table>
                            </div>
                            <div class="grid grid-cols-1"></div>
                        </div> 

                </div>

                <span id="mensajeValidacion" class="text-xl font-bold text-pink-600 mx-4"></span>

                <div class='flex items-center justify-center  md:gap-8 gap-4 pt-5 pb-5 border-t border-gray-300 mt-6'>
                    <a href="<?php echo RUTA_URL; ?>/Productos" class='w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>Cancelar</a>
                    <button id="crearProducto" class='w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>Crear</button>
                </div>
                </form>
            </div>
        </div>

        <!-- ****** FIN DEL CONTENIDO DE CADA PAGINA ****** -->
    </main>
</div>

</div>

</main>
<!--Esta etiqueta Main es el fin del sidebar -->



<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>

