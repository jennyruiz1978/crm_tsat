<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="reasignar-tecnico">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-5 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-xl font-semibold mr-2">
          Reasignar técnicos
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarReasignarTecnico" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative p-6 flex-auto">                  
        <input type="hidden" id="idIncidenciaAsign">
        <span id="msgValorar" class="font-bold font-bold text-pink-600"></span>
        <form class="flex flex-col space-y-5" id="bodyModalReasignarTecnico">                                      
          <input type="hidden" id="nombreRolUsuarioReasignar" value="<?php echo $_SESSION['nombrerol'] ;?>">

                <div class="flex flex-col grid grid-cols-1 space-y-4">
                    
                    <label for="selectTecnicos" class="text-sm font-semibold text-gray-500">Técnicos</label>
                      
                  <?php
                    echo'
                        <select id="selectTecnicos" name="selectTecnicos" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700" required>
                            <option disabled selected>Seleccionar</option>';                                
                            
                            if (isset($datos['tecnicos']) && count($datos['tecnicos']) > 0) {
                              foreach ($datos['tecnicos'] as $key) {
                                echo'<option value="'.$key->codigotecnico.'" >'.$key->nombre.' ' .$key->apellidos.'</option>';
                              }
                            }
                            
                    echo'
                        </select>
                </div>';
                ?>

              <div class="w-full mx-auto">
                <div class="bg-white shadow-md my-6">
                  <table id="tablaTecnicosIncidencia" class="text-left w-full border-collapse">
                    <thead>
                      <tr><th class="p-4 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">id</th><th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Nombre</th><th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Eliminar</th></tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
        </form>
      </div>
      
      <!--footer-->
      <div class="flex items-center justify-end p-6 border-t border-solid border-blueGray-200 rounded-b">
        <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2 mr-2 cerrarReasignarTecnico">Cerrar</button>
        <button id="asignarNuevosTecnicos" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2">Guardar cambios</button>


      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="reasignar-tecnico-backdrop"></div>