<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="delete-tecnico">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">

      <form method="POST" action="<?php echo RUTA_URL; ?>/Tecnicos/eliminarTecnico">

        <!--header-->
        <div class="flex items-start justify-between p-5 border-b border-solid border-blueGray-200 rounded-t">
          <h3 class="text-2xl font-semibold">
            Eliminar técnico
          </h3>
          <button class="p-1 ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerraModDeleteTecnico" >
            <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
              ×
            </span>
          </button>
        </div>
        <!--body-->
        <div class="relative p-6 flex-auto">
          <p class="my-4 text-blueGray-500 text-lg leading-relaxed">
            Se va a eliminar el técnico <span id="datoSucursal"></span> .
          </p>        
          <input type="hidden" id="idTecnico" name="idTecnico">
        </div>
        
        <!--footer-->
        <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
      
          <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-2 cerraModDeleteTecnico">Cerrar</button>
          <button  type="submit" id="ModDeleteTecnico" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2">Eliminar</button>
        </div>

      </form>

    </div>
  </div>
</div>

<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="delete-tecnico-backdrop"></div>