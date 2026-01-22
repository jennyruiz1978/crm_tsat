<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">

    <main class="w-full flex-grow p-6">       
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->
                
        <div class="flex items-center justify-center  mt-8">
            <div class="grid bg-white rounded-lg shadow-xl w-11/12">
              
                <div class="mt-6 ml-6">
                    <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Alta Usuarios</h2>     
                    <span id="msgValidaCliente" class="font-bold font-bold text-pink-600"></span>                         
                </div>
                                
                <form id="formAltaUsuario" method="POST" action="<?php echo RUTA_URL; ?>/Usuarios/crearUsuario">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-5 md:gap-8 mt-5 mx-7">
                    <div class="grid grid-cols-1">
                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Nombre</label>
                        <input name="nombre" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Nombre" required/>
                    </div>
                    <div class="grid grid-cols-1">
                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Apellidos</label>
                        <input name="apellidos" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Apellidos" required/>
                    </div>
                
                    <div class="grid grid-cols-1">
                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Mail</label>
                        <input name="correo" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="email" required/>
                    </div>
                    <!--
                    <div class="grid grid-cols-1">
                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Contrase&ntilde;a</label>
                        <input type="password" name="contra" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="contrase&ntilde;a" required/>
                    </div>
                    -->
                    <div class="grid grid-cols-1">
                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Rol</label>
                        <select name="rol" id="rol" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" >
                            <option disabled selected>Seleccionar</option>
                            <option value="0">Admin</option>
                            <option value="1">Cliente</option>                            
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 md:gap-8 mt-5 mx-7">
                    
                    <div class="grid grid-cols-1">
                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Estado</label>
                        <select name="estado" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="recibemails" value="0">
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Bloquear envío emails</span>
                            </label>
                    </div>
                    <div class="grid grid-cols-1">
                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Solo para clientes</label>
                        <select name="idcliente" id="idcliente" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" disabled>
                            <option disabled selected>Seleccionar</option>
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

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 md:gap-8 mt-6 mx-7" id="contenedorSoloClientes" style="display: none;">

                    <div class="grid grid-cols-1" id="contenedorClienteTipo" style="height: fit-content;">                    

                    </div>

                    <div class="grid grid-cols-1" style="height: fit-content;" >
                        <div class="grid grid-cols-1 my-1" id="contenedorClienteSucursales">
                            
                        </div>
                        <div class="grid grid-cols-1 my-1" id="contenedorClienteEquipos">                            
                        </div>                                              
                    </div>
                    
                    <div class="grid grid-cols-1" id="contenedorEquiposAsignados">
                            
                    </div>

                </div>

                <div class='flex items-center justify-center  md:gap-8 gap-4 pt-5 pb-5 border-t border-gray-300 mt-6'>
                    <a href="<?php echo RUTA_URL; ?>/Usuarios" class='w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>Cancelar</a>
                    <button  class='w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>Crear</button>
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

