<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="crear-cta">
  <div class="relative w-full my-6 max-w-xs">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between px-5 py-3 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-base font-semibold mr-2">
          Crear cuenta bancaria
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarModalCrearCuenta" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <form method="POST" action="<?php echo RUTA_URL; ?>/CuentasBancarias/registrarCuentasBancarias">     
        <!--body-->
        <div class="relative p-3 flex-auto">                          
          <span id="msgIniciar" class="font-bold font-bold text-pink-600"></span>
                                                            
                      <div class="grid grid-cols-1 gap-5 md:gap-8 m-2">
                          <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Número cuenta bancaria</label>
                            <input id="numerocuenta" name="numerocuenta" type="text" regexp="[a-zA-Z0-9]{0,24}" class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Ingrese el IBAN" required />
                          </div>
                          <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Banco</label>
                            <input id="banco" name="banco" type="text" regexp='[0-9a-zA-ZäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙçÇñÑ\s]{1,30}' class="py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Ingrese nombre del banco" required />
                          </div>                          
                      </div>                        
        </div>
        
        <!--footer-->
        <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
          <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-2 cerrarModalCrearCuenta">Cerrar</button>
          <button type="submit" id="crearModalidad" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2">Agregar</button>
        </div>

      </form>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="crear-cta-backdrop"></div>