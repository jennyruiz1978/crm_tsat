<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="detener-atencion">
  <div class="relative w-auto my-2 mx-auto max-w-xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-sm xl:text-lg font-semibold mr-2">
          ¿Está seguro de detener la acción?
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarDetenerAtencion" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative p-2 flex-auto">      
        
        <input type="hidden" id="idIncidenciaStop">
        
        <form class="flex flex-col space-y-2" id="bodyModalDetenerAtencion">                  
          
                      <input type="hidden" id="nombreRolUsuario" value="<?php echo $_SESSION['nombrerol'] ;?>">

                      <div class="flex flex-col grid grid-cols-1 space-y-2">
                        <label for="modAtencionDetener" class="text-sm font-semibold text-gray-500">Modalidad atención</label>                            
                        <?php
                          echo'
                            <input id="modAtencionDetener" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700" readonly>'
                        ?>
                      </div>

                      <div class="flex flex-col grid grid-cols-1 space-y-2">
                        <label for="creacionInicio" class="text-sm font-semibold text-gray-500">Fecha/Hora de inicio</label>                            
                        <?php
                          echo'
                            <input id="creacionInicio" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700" readonly>'
                        ?>
                      </div>

                      <div class="flex flex-col grid grid-cols-1 space-y-2">
                        <label for="comentClienteStop" class="text-sm font-semibold text-gray-500">Comentario para el cliente</label>                        
                          <textarea name="comentClienteStop" id="comentClienteStop" maxlength="1000" class="text-sm w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Deja un comentario para el cliente (opcional)" type="text"></textarea>
                      </div>




                      <div class="flex flex-col grid grid-cols-1 space-y-2">                                
                        <div class="inline-flex">
                            <label class="py-1 2xl:py-2 flex-1 md:text-sm text-xs text-gray-500 text-light font-semibold">Adjuntar ficheros del trabajo realizado</label>
                        </div>
                                            
                        <div class="grid grid-cols-1 mt-2">
                            <p class="text-xs text-gray-500">Tamaño máximo de cada archivo o suma de todos = 6MB</p>
                            <span id="msgValidaFicheroDetener" class="text-xs lg:text-sm xl:text-base font-bold text-pink-600 mx-4"></span> 
                            <input type="file" class="text-xs py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent inputFichero" name="ficheroDetenerAtencion[]" id="ficheroDetenerAtencion" multiple="" placeholder="Adjunte fichero">
                        </div>                            
                      </div>









                      <div id="apartadoFacturarPresupuestar" style="display: none;">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-8 mt-5">
                          
                          <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Facturar / Presupuestar</label>                
                            <select name="tipoAccion" id="tipoAccion" class="py-1 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">                              
                            </select>

                          </div>                
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:gap-8 my-5">
                          <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Comentario para facturador</label>              
                            <textarea name="comentarioParaFacturador" id="comentarioParaFacturador" maxlength="1000" class="text-sm w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Ingrese un comentario para el Dpto. de facturación (opcional)" type="text"></textarea>
                          </div> 
                        </div>  
                      </div>


               
        </form>
        <span id="msgDetener" class="font-bold font-bold text-pink-600"></span>
      </div>

      
      
      <!--footer-->
      <div class="flex items-center justify-end p-2 border-t border-solid border-blueGray-200 rounded-b">
        <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl text-sm md:text-md 3xl:text-base text-white px-4 py-1 mr-2 cerrarDetenerAtencion">Cerrar</button>
        <button id="detenerAccion" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-sm md:text-md 3xl:text-base text-white px-4 py-1">Detener</button>


      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="detener-atencion-backdrop"></div>