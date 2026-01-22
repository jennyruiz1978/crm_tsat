<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="finalizar-incidencia">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-base font-semibold mr-2" id="tituloIdSolicitud">          
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarFinalizarIncidencia" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative p-2 flex-auto">                  
        <input type="hidden" id="idIncidenciaFin">        
        <form class="flex flex-col space-y-5" id="bodyModalFinalizarIncidencia">                                      
          
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-8 mt-5 mx-3">

                <div class="grid grid-cols-1">
                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Solicitante</label>
                    <input id="solicitanteFin" class="py-1 px-3 text-sm 2xl:text-base rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" readonly />
                </div>

                <div class="grid grid-cols-1">
                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Cliente</label>
                    <input id="clienteFin" class="py-1 px-3 text-sm 2xl:text-base rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" readonly />
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-8 mt-5 mx-3">
              
                <div class="grid grid-cols-1">
                  <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Sucursal</label>
                  <input id="sucursalFin" class="py-1 px-3 text-sm 2xl:text-base rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" readonly />
                </div>                   
                <div class="grid grid-cols-1">
                    <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Equipo implicado</label>
                    <input id="equipoFin" class="py-1 px-3 text-sm 2xl:text-base rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" readonly />
                </div>     
            </div>            
            
            <div class="grid grid-cols-1 gap-5 md:gap-8 my-5 mx-3">
              <div class="grid grid-cols-1">
                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Comentario para el cliente</label>              
                <textarea name="comentario" id="comentario" maxlength="1000" class="w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Ingrese un comentario para el cliente" type="text" required></textarea>
              </div> 
            </div>

            
            <div class="gap-5 md:gap-8 mt-5 mx-3">                                
              <div class="inline-flex">
                  <label class="py-1 2xl:py-2 flex-1 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Adjuntar imágenes/ficheros del trabajo realizado</label>
              </div> 
                                  
              <div class="grid grid-cols-1 mt-2">
                  <p class="text-xs text-gray-500">Tamaño máximo de cada archivo o suma de todos = 6MB</p>
                  <span id="msgValidaFicheroFinalizar" class="text-xs lg:text-sm xl:text-base font-bold text-pink-600 mx-4"></span> 
                  <input type="file" class="text-xs py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent inputFichero" name="ficheroCrearIncidencia[]" id="ficheroCrearIncidencia" multiple="" placeholder="Adjunte fichero">
              </div>                            
            </div>

                 
            <div class="grid grid-cols-1 gap-5 md:gap-8 my-5 mx-3">
              <div class="grid grid-cols-1">
                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Comentario interno</label>              
                <textarea name="comentarioInterno" id="comentarioInterno" maxlength="1000" class="w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Ingrese un comentario interno para Infomálaga" type="text"></textarea>
              </div> 
            </div>    
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 md:gap-8 mt-5 mx-3">
              
              <div class="grid grid-cols-1">
                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Facturar / Presupuestar</label>                
                <select name="estadoFactPpto" id="estadoFactPpto" class="py-1 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">                    
                    
                </select>

              </div>                
            </div>

            <div class="grid grid-cols-1 gap-5 md:gap-8 my-5 mx-3">
              <div class="grid grid-cols-1">
                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Comentario para facturador</label>              
                <textarea name="comentarioParaFacturadorFinalizar" id="comentarioParaFacturadorFinalizar" maxlength="1000" class="w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Ingrese un comentario para el Dpto. de facturación (opcional)" type="text"></textarea>
              </div> 
            </div>  




            <div class="grid grid-cols-1 gap-5 md:gap-8 my-5 mx-3" id="firmaContainer">                   
            </div>  



               
        </form>
      </div>
      
      <!--footer-->
      <span id="msgFinalizarIncidencia" class="font-bold font-bold text-pink-600 mx-5"></span>
      <div class="flex items-center justify-end px-6 py-2 border-t border-solid border-blueGray-200 rounded-b">
        <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl text-white text-xs lg:text-sm 3xl:text-base px-4 py-1 mr-2  cerrarFinalizarIncidencia">Cerrar</button>
        <button id="finalizarIncidencia" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-white text-xs lg:text-sm 3xl:text-base px-4 py-1">Finalizar</button>


      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="finalizar-incidencia-backdrop"></div>