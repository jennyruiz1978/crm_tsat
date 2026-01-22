<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="rechazar-incidencia">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-5 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-xl font-semibold mr-2" id="tituloRechazar">
          Rechazar incidencia
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarRechazarIncidencia" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative p-6 flex-auto">                  
        <input type="hidden" id="idIncidenciaRechz">
        <span id="msgRechazarInc" class="font-bold font-bold text-pink-600"></span>
        <form class="flex flex-col space-y-5" id="bodyModalRechazarIncidencia">

                <div class="flex flex-col grid grid-cols-1 space-y-4">
                    
                    <label for="selectTecnicosRechz" class="text-sm font-semibold text-gray-500">Reasignar a:</label>
                      
                  <?php
                    echo'
                        <select id="selectTecnicosRechz" name="selectTecnicosRechz" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700">                           
                        </select>
                </div>';
                ?>

              <div class="w-full mx-auto">
                <div class="bg-white shadow-md my-6">
                  <table id="tablaTecnicosReasignado" class="text-left w-full border-collapse">
                    <thead>
                      <tr><th class="p-4 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light" style="display:none">id</th><th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Nombre</th><th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Eliminar</th></tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
        </form>
      </div>
      
      <!--footer-->
      <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
        <a class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-2 cerrarRechazarIncidencia">Cerrar</a>
        <a id="rechazarIncidencia" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2">Rechazar</a>


      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="rechazar-incidencia-backdrop"></div>

<?php require_once(RUTA_APP . '/views/incidencias/modalLoadAjax2.php'); ?>