<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>


<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">
        
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->
              
            <div class="flex items-center justify-center  mt-8">
              

              <div class="grid bg-white rounded-lg shadow-xl w-11/12">     
              
                      
                <div class="flex items-center justify-center border-b border-solid border-blueGray-200">  
                    <h2 class="text-sm lg:text-lg font-semibold leading-tight mr-2 my-2">Nueva solicitud </h2>
                    <span id="msgValidarIncidencia" class="text-xs lg:text-sm xl:text-base font-bold text-pink-600 mx-4"></span>  
                </div>
              
                <div id="pageloader">
                    <img src="http://cdnjs.cloudflare.com/ajax/libs/semantic-ui/0.16.1/images/loader-large.gif" alt="processing..." />
                </div>

                <form method="POST" action="<?php echo RUTA_URL; ?>/Incidencias/registrarIncidenciaTecnico" enctype="multipart/form-data" id="formAltaIncidenciaTecnico">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-8 mt-5 mx-7">
                                          

                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Solicitante</label>
                            <input name="solicitante" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" value="<?php echo $_SESSION['usuario'];?>" readonly />
                        </div>
                    
                        <div class="grid grid-cols-1">
                          <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Cliente</label>
                          
                            <div class="cont_select_dinamic">
                                <select id="cliente" name="cliente" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" >
                                    
                                </select>
                                <!-- <i class="fa fa-search" id="boton_buscar_cliente"></i> -->
                            </div>
                        </div>       
                        
                        <div class="grid grid-cols-1">
                          <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Sucursal</label>
                          <select id="sucursalesTecnico" name="sucursalesTecnico" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" >
                            <option disabled selected>Seleccionar</option>
                            
                          </select>
                        </div>   

                        <div class="grid grid-cols-1" id="contenedorEquipos">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Equipo(s) implicado(s)</label>
                            <select id="equiposTecnico" name="equiposTecnico" class="todos py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                            </select>
                        </div>                        
                    </div>


                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-8 my-2 mx-7">

                        <div>                               
                            <div class="inline-flex">
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Descripción de la solicitud</label>
                            </div>
                            <div>
                                <div class='mb-8 mt-4'>
                                    <textarea name="descripcion" id="descripcion" class="w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="ingrese el detalle de la solicitud" type="text" ></textarea>
                                </div>                            
                            </div>                              
                        </div>


           
                        <div >                                
                            <div class="inline-flex">
                                <label class="py-1 2xl:py-2 flex-1 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Adjuntar imágenes/ficheros</label>
                                
                                <a id="desplegar" class='w-auto bg-gray-400 hover:bg-gray-500 rounded-lg shadow-xl text-sm lg:text-sm xl:text-base text-white px-2 ml-3 flex items-center justify-center'><i class="far fa-image mr-2 text-xl"></i>Agregar</a>
                            </div> 
                                                                
                            <div id='formularioSubirFicheroIncidencia' style='display:none;' class="grid grid-cols-1 mt-4">
                                <p>Tamaño máximo de cada archivo = 6MB</p>
                                <span id="msgValidaFichero" class="text-xs lg:text-sm xl:text-base font-bold text-pink-600 mx-4"></span> 
                                <input type="file" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent inputFichero" name="ficheroCrearIncidencia[]" id="ficheroCrearIncidencia[]" multiple="" placeholder="Adjunte fichero">
                            </div>                            
                        </div>
    
                        
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-8 my-2 mx-7">                                          
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Fecha/Hora</label>
                            <input type="datetime-local" name="fechahora" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" />
                        </div>
                    </div>

                    <div class="flex items-center justify-center px-6 py-3 border-t border-solid border-blueGray-200 rounded-b">
                        <a href="<?php echo RUTA_URL; ?>/Incidencias" class='w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl text-sm xl:text-base text-white px-4 py-1 mr-3'>Cancelar</a>
                        <button type="submit" class='w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-sm xl:text-base text-white px-4 py-1'>Enviar</button>
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
<?php require_once(RUTA_APP . '/views/facturasCliente/modalBuscarCliente.php'); ?>