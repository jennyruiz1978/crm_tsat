<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="enviar-factura">
  <div class="relative sm:w-full lg:w-1/2 my-6 mx-auto">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between px-5 py-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-sm xl:text-base font-semibold mr-2">
          Envío de factura por email
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarEnviarFactura" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>

      <div id="spinner"></div>

      <form id="formSendEmailInvoice">

        <input type="hidden" id="idFacturaEnviar" name="idFacturaEnviar">
        <!--body-->
        <div class="relative p-6 flex-auto">
                    
            <div class="grid grid-cols-1 gap-2 md:gap-4 m-2">              
                  <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Agregar destinatario</label>                  
                  <div class="inline-flex gap-1 items-center w-full">
                    <input type="text" id="emailDestinatario" class="flex-grow py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" value="" placeholder="Escriba un correo">
                    <span class="cursor-pointer text-red-500 font-bold text-lg" id="agregarEmailInput" title="Haga clic para apregar el correo a los destinatarios">+</span>                    
                  </div>
            </div>

            <div class="grid grid-cols-1 gap-2 md:gap-4 m-2">              
                  <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Seleccionar correos guardados</label>
                  <select id="emails_contactos" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"></select>              
            </div>

            <div class="grid grid-cols-1  gap-2 md:gap-4 m-2">          
                  <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Destinatarios</label>
            </div>


            <div class="flex flex-wrap gap-2" id="emails_selected_to_send"></div>


            <div class="grid grid-cols-1 m-2 border-b-2 border-gray-500">                
            </div>

            <div class="grid grid-cols-1 gap-2 md:gap-4 m-2">            
              <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Asunto</label>
              <input type="text" id="emailAsunto" name="emailAsunto" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" value="Factura de servicios" placeholder="Escriba un asunto para el correo" >
            </div>
            
            <div class="grid grid-cols-1 gap-2 md:gap-4 m-2">            
              <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Mensaje</label>
              <textarea type="text" id="emailMensaje" name="emailMensaje" class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" rows="2" value="" placeholder="Escriba un mensaje o utilice el mensaje pr defecto">Buenos días:<?php echo "\n"?>A continuación, le adjuntamos la factura por los servicios prestados.</textarea>          
            </div>

        </div>

        <div class="flex items-center justify-end px-4 py-2 border-t border-solid border-blueGray-200 rounded-b"></div>
        
        <!--footer-->
        <div class="flex items-center justify-end px-4 py-2 border-t border-solid border-blueGray-200 rounded-b">
          <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl text-xs lg:text-sm 2xl:text-base text-white px-4 py-1 mr-2 cerrarEnviarFactura">Cerrar</button>
          <button id="enviarEmailFactura" type="submit" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-xs lg:text-sm 2xl:text-base text-white px-4 py-1">Enviar</button>
        </div>

      </form>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="enviar-factura-backdrop"></div>