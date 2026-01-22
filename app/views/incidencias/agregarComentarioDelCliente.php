<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="agregar-comentarioCliente">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between px-5 py-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-sm xl:text-base font-semibold mr-2">
          Agregue un comentario para el técnico
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarAgregarComentarioCliente" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative p-6 flex-auto">

          <div class="flex flex-col grid grid-cols-1 space-y-4">
            

            <textarea id="comentarioIntAddCliente" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700"></textarea>
          </div>
      </div>
      
      <!--footer-->
      <div class="flex items-center justify-end px-4 py-2 border-t border-solid border-blueGray-200 rounded-b">
        <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl text-xs lg:text-sm 2xl:text-base text-white px-4 py-1 mr-2 cerrarAgregarComentarioCliente">Cerrar</button>
        <button id="guardarComentarioCliente" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-xs lg:text-sm 2xl:text-base text-white px-4 py-1">Agregar</button>


      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="agregar-comentarioCliente-backdrop"></div>