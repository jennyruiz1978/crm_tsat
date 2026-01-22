<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="editar-CosteTecnico">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-sm xl:text-base font-semibold mr-2">
          Modificar coste hora técnico
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarEditarCosteTecnico" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      
      
        
      <form class="flex flex-col space-y-5" id="bodyModalModificarCosteTecnico" method="POST" action="<?php echo RUTA_URL; ?>/CostesTecnicos/actualizarCosteTecnico">
        <input type="hidden" id="idEditCoste" name="idEditCoste">
        <!--body-->
        <div class="relative px-6 py-2 flex-auto">      
                  
          
          
                  <div class="grid grid-cols-2">

                      <div class="flex flex-col grid grid-cols-1 mr-2">
                      
                          <label for="nombreTecnico" class="text-sm font-semibold text-gray-500">Técnico</label>
                      
                          <input id="nombreTecnico" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700" readonly>
                         
                      </div>

                      <div class="flex flex-col grid grid-cols-1 mx-2">
                          <div class="flex items-center justify-between">
                              <label for="costeHorasEditar" class="text-sm font-semibold text-gray-500">Coste hora(€)</label>              
                          </div> 
                          <input type="number" step="0.01" id="costeHorasEditar" name="costeHorasEditar"
                          class="px-4 py-2 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700" required />
                      </div>
                                     
                      <div class="flex flex-col grid grid-cols-1 mr-2">
                          <div class="flex items-center justify-between">
                              <label for="mesCosteHoraEditar" class="text-sm font-semibold text-gray-500">Mes</label>              
                          </div>                        
                          <input id="mesCosteHoraEditar" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700" readonly >
                                          
                      </div>

                      <div class="flex flex-col grid grid-cols-1 mx-2">
                          <div class="flex items-center justify-between">
                              <label for="anioBolsaHorasEditar" class="text-sm font-semibold text-gray-500">Año</label>              
                          </div>
                          
                          <input id="anioBolsaHorasEditar" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700" readonly >
                            
                      </div>

                      


                  </div>
                

      


        </div>
        
        <!--footer-->
        <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
          <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-2 cerrarEditarCosteTecnico">Cerrar</button>
          <button type="submit" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2">Actualizar</button>
        </div>

      </form>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="editar-CosteTecnico-backdrop"></div>