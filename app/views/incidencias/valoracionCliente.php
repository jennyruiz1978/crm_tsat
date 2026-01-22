<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="valoracion-incidencia">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-lg font-semibold mr-2 px-6">
          Puedes dejarnos una valoración:
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarValoracionIncidencia" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative p-2 flex-auto">                  
        <input type="hidden" id="idIncidenciaVal">
        <span id="msgValorarIncidencia" class="px-6 font-bold font-bold text-pink-600"></span>
        <form class="flex flex-col space-y-5" id="formModalValorarIncidencia">                                      

            <h3 class="text-base font-semibold px-6">Marca del 1 al 5 tu grado de satisfacción con el servicio.</h3>
                
              
            <p class="clasificacion">
        <input class="inputEstrella" id="radio1" type="radio" name="estrellas" value="5"><!--
    --><label for="radio1"><i class="fas fa-star"></i></label><!--
    --><input class="inputEstrella" id="radio2" type="radio" name="estrellas" value="4"><!--
    --><label for="radio2"><i class="fas fa-star"></i></label><!--
    --><input class="inputEstrella" id="radio3" type="radio" name="estrellas" value="3"><!--
    --><label for="radio3"><i class="fas fa-star"></i></label><!--
    --><input class="inputEstrella" id="radio4" type="radio" name="estrellas" value="2"><!--
    --><label for="radio4"><i class="fas fa-star"></i></label><!--
    --><input class="inputEstrella" id="radio5" type="radio" name="estrellas" value="1"><!--
    --><label for="radio5"><i class="fas fa-star"></i></label>
  </p>
             
              <input type="hidden" id="valorEstrella">
 

            
            <div class="grid grid-cols-1 gap-5 md:gap-8 my-5 mx-7">
              <div class="grid grid-cols-1">                                        
                <textarea name="comentarioCliente" id="comentarioCliente" maxlength="1000" class="w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" placeholder="Déjanos un comentario sobre el servicio brindado" type="text" required></textarea>
              </div> 
            </div>


               
        </form>
      </div>
      
      <!--footer-->
      <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
        <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-2 cerrarValoracionIncidencia">Cerrar</button>
        <button id="valorarIncidencia" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2">Guardar</button>


      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="valoracion-incidencia-backdrop"></div>