<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="iniciar-atencion">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-5 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-xl font-semibold mr-2">
          ¿Está seguro de iniciar la acción?
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarIniciarAtencion" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative p-6 flex-auto">                  
        <input type="hidden" id="idIncidenciaPlay">
        <span id="msgIniciar" class="font-bold font-bold text-pink-600"></span>
        <form class="flex flex-col space-y-5" id="bodyModalIniciarAtencion">                                      
          <input type="hidden" id="rolUsuarioIniciar" value="<?php echo $_SESSION['nombrerol']; ?>">
                      <div class="flex flex-col grid grid-cols-1 space-y-4">
                    
                          <label for="modAtencionInicio" class="text-sm font-semibold text-gray-500">Modalidad atención</label>
                            
                        <?php
                        echo'
                            <select id="modAtencionInicio" name="modAtencionInicio" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700" required>
                                <option disabled selected>Seleccionar</option>';                                
                                
                                if (isset($datos['modalidadestecnicos']) && count($datos['modalidadestecnicos']) > 0) {
                                  foreach ($datos['modalidadestecnicos'] as $key) {
                                    echo'<option value="'.$key->id.'" >'.$key->modalidad.'</option>';
                                  }
                                }
                                
                        echo'
                            </select>
                      </div>';
                ?>
               
        </form>
      </div>
      
      <!--footer-->
      <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
        <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-2 cerrarIniciarAtencion">Cerrar</button>
        <button id="iniciarAccion" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2">Iniciar</button>


      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="iniciar-atencion-backdrop"></div>