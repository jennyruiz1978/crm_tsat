<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="crear-tipoiva">
  <div class="relative w-auto my-6 mx-auto max-w-xs">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between px-5 py-3 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-base font-semibold mr-2">
          Crear nueva tipo iva
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarModalCrearTipoIva" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <form method="POST" action="<?php echo RUTA_URL; ?>/TiposIva/registrarTiposIva">     
        <!--body-->
        <div class="relative p-3 flex-auto">                          
          <span id="msgIniciar" class="font-bold font-bold text-pink-600"></span>
                                                            
                      <div class="grid grid-cols-1 gap-5 md:gap-8 m-2">
                          <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Tipo IVA</label>
                            <input id="tipoiva" name="tipoiva" type="number" maxlength="2" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:ring-2 focus:ring-blue-700 focus:border-transparent" required />
                          </div>                                                
                      </div>                        
        </div>
        
        <!--footer-->
        <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
          <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-2 cerrarModalCrearTipoIva">Cerrar</button>
          <button type="submit" id="crearModalidad" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2">Agregar</button>
        </div>

      </form>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="crear-tipoiva-backdrop"></div>