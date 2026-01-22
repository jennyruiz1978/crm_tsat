<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="reabrir-incidencia">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between px-4 py-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-sm xl:text-base font-semibold mr-2">
          Reabrir incidencia
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarReabrirIncidencia" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative px-4 py-2 flex-auto text-xs lg:text-sm 2xl:text-base">                  
        <input type="hidden" id="idIncidenciaAbrir">
        <input type="hidden" id="nombreRolUsuarioReabrir" value="<?php echo $_SESSION['nombrerol'] ;?>">
        <span id="msgReabrir" class="font-bold font-bold text-pink-600"></span>
        <p>¿ Desea reabrir la incidencia Nº <span id="numIncidencia"></span> ? </p>
      </div>
      
      <!--footer-->
      <div class="flex items-center justify-end px-4 py-2 border-t border-solid border-blueGray-200 rounded-b">
        <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-1 text-xs lg:text-sm 2xl:text-base mr-2 cerrarReabrirIncidencia">Cerrar</button>
        <button id="reabrirIncidencia" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-1 text-xs lg:text-sm 2xl:text-base">Reabrir</button>


      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="reabrir-incidencia-backdrop"></div>