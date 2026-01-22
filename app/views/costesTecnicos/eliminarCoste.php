<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="delete-costeTecnico">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">

      <form method="POST" action="<?php echo RUTA_URL; ?>/CostesTecnicos/eliminarCosteTecnico">

        <!--header-->
        <div class="flex items-start justify-between p-2 border-b border-solid border-blueGray-200 rounded-t">
          <h3 class="text-lg font-semibold">
            Eliminar coste
          </h3>
          <button class="p-1 ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerraModDeleteCosteTecnico" >
            <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
              ×
            </span>
          </button>
        </div>
        <!--body-->
        <div class="relative p-6 flex-auto">
          <p class="my-4 text-blueGray-500 text-base leading-relaxed">
            Se va a eliminar el coste <span id="datosCoste"></span> .
          </p>        
          <input type="hidden" id="idCosteDel" name="idCosteDel">
        </div>
        
        <!--footer-->
        <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
      
          <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-1 mr-2 cerraModDeleteCosteTecnico">Cerrar</button>
          <button  type="submit" id="ModDeleteCoste" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-1">Eliminar</button>
        </div>

      </form>

    </div>
  </div>
</div>

<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="delete-costeTecnico-backdrop"></div>