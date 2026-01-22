<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<?php


    $idTecnico = '';
    $title = '';

    if(isset($datos['detalles'])){
        $detalles = $datos['detalles'];

        $idTecnico = $detalles->codigotecnico;
        $title = 'Nº '. $idTecnico;        

    }

?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">
        
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->
              
            <div class="flex items-center justify-center  mt-8">
              

              <div class="grid bg-white rounded-lg shadow-xl w-11/12">     
              
                      
                <div class="flex items-center justify-center border-b border-solid border-blueGray-200">  
                    <h2 class="text-2xl font-semibold leading-tight mr-2 my-4">Técnico <?php echo $title; ?></h2>
                </div>
              
                <form method="POST" action="<?php echo RUTA_URL; ?>/Tecnicos/actualizarTecnico">
                    <input type="hidden" id="id" name="id" value="<?php echo $detalles->id;?>">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8 mt-5 mx-7">

                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Nombre</label>
                            <input id="nombres" name="nombres" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" required value="<?php echo $detalles->nombre;?>" />
                        </div>

                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Apellidos</label>
                            <input id="apellidos" name="apellidos" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" required value="<?php echo $detalles->apellidos;?>" />                         
                        </div>                   

                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Email</label>
                            <input type="email" id="email" name="email" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" required value="<?php echo $detalles->correo;?>" />                     
                        </div>                        
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 2xl:grid-cols-6 gap-5 md:gap-8 my-5 mx-7">
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Contrase&ntilde;a</label>
                            <input type="password" name="contra" name="contra" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" required value="<?php echo $detalles->contra;?>" />                     
                        </div> 
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Teléfono</label>
                            <input id="telefono" name="telefono" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" value="<?php echo $detalles->telefono;?>" />
                        </div>        
                        
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="editarTiempo" value="1" <?php echo (($detalles->editartiempo==1)? 'checked':'');?>>
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Permiso edición tiempos</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="verTodas" value="1" <?php echo (($detalles->vertodas==1)? 'checked':'');?>>
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">ver todas las incidencias</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <?php
                                    $checked = '';
                                    if ($detalles->recibemails == 0) {
                                        $checked = 'checked';
                                    }
                                ?>
                                <input <?php echo  $checked;?> type="checkbox" class="form-checkbox h-4 w-4" name="recibemails">
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Bloquear envío emails</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="editarClientes" value="1" <?php echo (($detalles->editarclientes==1)? 'checked':'');?>>
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Permiso edición clientes</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4" name="soloVerClientes" value="1" <?php echo (($detalles->verclientes==1)? 'checked':'');?>>
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Sólo ver clientes</span>
                            </label>
                        </div>

                        
                    </div>
                    <div class="flex items-center justify-center px-6 py-3 border-t border-solid border-blueGray-200 rounded-b">
                            <a href="<?php echo RUTA_URL; ?>/Tecnicos" class='w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-3'>Cancelar</a>
                            <button type="submit" class='w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>Guardar</button>
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