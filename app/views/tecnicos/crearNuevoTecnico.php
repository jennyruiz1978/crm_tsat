<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">
        
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->
              
            <div class="flex items-center justify-center  mt-8">
              

              <div class="grid bg-white rounded-lg shadow-xl w-11/12">     
              
                      
                <div class="flex items-center justify-center border-b border-solid border-blueGray-200">  
                    <h2 class="text-2xl font-semibold leading-tight mr-2 my-4">Nuevo técnico </h2>
                </div>
              
                <form method="POST" action="<?php echo RUTA_URL; ?>/Tecnicos/registrarTecnico">

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 md:gap-8 mt-5 mx-7">

                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Nombre</label>
                            <input id="nombres" name="nombres" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" required />
                        </div>
                    
                        <div class="grid grid-cols-1">
                          <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Apellidos</label>
                          <input id="apellidos" name="apellidos" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" required />                         
                        </div>                   
                        
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Email</label>
                            <input type="email" id="email" name="email" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" required />                     
                        </div>                        
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 md:gap-8 my-5 mx-7">
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Contrase&ntilde;a</label>
                            <input type="password" name="contra" name="contra" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" required />                     
                        </div> 
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Teléfono</label>
                            <input id="telefono" name="telefono" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" />
                        </div>        
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Asignar cliente</label>
                            <select name="idcliente[]" id="idcliente" multiple="multiple" class="todos py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">                                
                                <?php
                                    
                                    if (isset($datos['clientes']) && count($datos['clientes'])>0) {                        
                                        $clientes = $datos['clientes'];
                                        foreach ($clientes as $cliente) {                                                            
                                            echo"
                                                <option value='".$cliente->id."'>".$cliente->nombre."</option>";
                                        }
                                    }
                                    
                                ?>
                            </select>
                        </div>
                        
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 2xl:grid-cols-4 gap-5 md:gap-8 my-5 mx-7">
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="editarTiempo" value="1">
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Permiso edición tiempos</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="verTodas" value="1">
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">ver todas las incidencias</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="recibemails" value="0">
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Bloquear envío emails</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="editarClientes" value="1">
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Permiso edición clientes</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="soloVerClientes" value="1">
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Sólo ver clientes</span>
                            </label>
                        </div>

                    </div>


                    <div class="flex items-center justify-center px-6 py-3 border-t border-solid border-blueGray-200 rounded-b">
                        <a href="<?php echo RUTA_URL; ?>/Tecnicos" class='w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-3'>Cancelar</a>
                        <button type="submit" class='w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>Crear</button>
                    </div>

                </form>
            </div>
        </div>




              
    
        <!-- ****** FIN DEL CONTENIDO DE CADA PAGINA ****** -->
      </main>
  </div>

</div>

</main> <!--Esta etiqueta Main es el fin del sidebar -->


<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>