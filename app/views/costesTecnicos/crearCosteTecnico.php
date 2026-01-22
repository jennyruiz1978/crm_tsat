<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="crear-CosteTecnico">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-sm xl:text-base font-semibold mr-2">
          Configurar coste hora técnico
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarCrearCosteTecnico" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      
      
        
      <form class="flex flex-col space-y-5" id="bodyModalCrearCosteTecnico" method="POST" action="<?php echo RUTA_URL; ?>/CostesTecnicos/registrarCosteTecnico">
          
        <!--body-->
        <div class="relative p-2 flex-auto">      
        
        <span id="mensajeValidacion" class="font-bold font-bold text-pink-600"></span>
          
          
                  <div class="grid grid-cols-2">

                      <div class="flex flex-col grid grid-cols-1 mr-2">
                      
                          <label for="idTecnicoCrear" class="text-sm font-semibold text-gray-500">Técnico</label>
                      
                          <select id="idTecnicoCrear" name="idTecnicoCrear" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700 text-sm" required>
                          <?php                        
                                  echo'<option disabled selected>Seleccionar</option>';
                                  if ($datos['tecnicos'] && count($datos['tecnicos'])>0) {
                                    $tecnicos = $datos['tecnicos'];
                                    
                                    foreach ($tecnicos as $tecnico) {
                                      echo"
                                        <option value='".$tecnico->id."'>".$tecnico->nombre." ".$tecnico->apellidos."</option>
                                      ";
                                    }   
                                }
                          ?>                         
                    
                          </select>
                      </div>
                    
                                      
                      <div class="flex flex-col grid grid-cols-1 mx-2">
                          <div class="flex items-center justify-between">
                              <label for="costeHoras" class="text-sm font-semibold text-gray-500">Coste hora(€)</label>              
                          </div> 
                          <input type="number" step="0.01" id="costeHoras" name="costeHoras"
                          class="px-4 py-2 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700 text-sm" required />
                      </div>


                      <div class="flex flex-col grid grid-cols-1 mr-2">
                          <div class="flex items-center justify-between">
                              <label for="mesInicio" class="text-sm font-semibold text-gray-500">Desde</label>              
                          </div>                        
                          
                          <select id="mesInicio" name="mesInicio" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700 text-sm" required >
                              <option disabled selected>Seleccionar mes</option>
                              <?php
                              
                              $meses = ["Ene"=>1,"Feb"=>2,"Mar"=>3,"Abr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Ago"=>8,"Set"=>9,"Oct"=>10,"Nov"=>11,"Dic"=>12];
                              
                              foreach ($meses as $mes => $or) {
                                  echo'<option value="'.$or.'">'.$mes.'</option>';
                              }                              
                              ?>
                              
                              </select>                                  
                      </div>

                      <div class="flex flex-col grid grid-cols-1 mx-2">
                          <div class="flex items-center justify-between">
                              <label for="anioInicio" class="text-sm font-semibold text-gray-500">&nbsp;</label>              
                          </div>                         

                          
                          <select id="anioInicio" name="anioInicio" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700 text-sm" required >
                              

                              <?php
                                                            
                              if (isset($datos['anios']) && count($datos['anios'])>0) {
                                $anioAnterior = ($datos['anios'][0]->anio)-1;
                                $ultimo = count($datos['anios']) - 1;
                                $anioPosterior = ($datos['anios'][$ultimo]->anio)+1;

                                echo'<option value="'.$anioAnterior.'" >'.$anioAnterior.'</option>';                               
                                foreach ($datos['anios'] as $key) {
                                  echo'<option value="'.$key->anio.'" '.((date('Y')==$key->anio)? 'selected' : '').' >'.$key->anio.'</option>';
                                }
                                echo'<option value="'.$anioPosterior.'" >'.$anioPosterior.'</option>';
                                
                              }else{
                                $anios = [date('Y')-1,date('Y'),date('Y')+1];
                                foreach ($anios as $anio) {
                                    echo'<option value="'.$anio.'" '.((date('Y')==$anio)? 'selected' : '').'>'.$anio.'</option>';
                                } 
                              }                                 
                              ?>

                          </select>                            
                      </div>

                      

                      <div class="flex flex-col grid grid-cols-1 mr-2">
                          <div class="flex items-center justify-between">
                              <label for="mesFin" class="text-sm font-semibold text-gray-500">Hasta</label>              
                          </div>                        
                          
                          <select id="mesFin" name="mesFin" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700 text-sm" required >
                              <option disabled selected>Seleccionar mes</option>
                              <?php
                              
                              $meses = ["Ene"=>1,"Feb"=>2,"Mar"=>3,"Abr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Ago"=>8,"Set"=>9,"Oct"=>10,"Nov"=>11,"Dic"=>12];
                              
                              foreach ($meses as $mes => $or) {
                                  echo'<option value="'.$or.'">'.$mes.'</option>';
                              }                              
                              ?>
                              
                              </select>                                  
                      </div>

                      <div class="flex flex-col grid grid-cols-1 mx-2">
                          <div class="flex items-center justify-between">
                              <label for="anioFin" class="text-sm font-semibold text-gray-500">&nbsp;</label>              
                          </div>                         

                          
                          <select id="anioFin" name="anioFin" class="py-2 px-3 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-700 text-sm" required >
                              <?php
                                                            
                              if (isset($datos['anios']) && count($datos['anios'])>0) {
                                $anioAnterior = ($datos['anios'][0]->anio)-1;
                                $ultimo = count($datos['anios']) - 1;
                                $anioPosterior = ($datos['anios'][$ultimo]->anio)+1;

                                echo'<option value="'.$anioAnterior.'" >'.$anioAnterior.'</option>';                               
                                foreach ($datos['anios'] as $key) {
                                  echo'<option value="'.$key->anio.'" '.((date('Y')==$key->anio)? 'selected' : '').' >'.$key->anio.'</option>';
                                }
                                echo'<option value="'.$anioPosterior.'" >'.$anioPosterior.'</option>';
                                
                              }else{
                                $anios = [date('Y')-1,date('Y'),date('Y')+1];
                                foreach ($anios as $anio) {
                                    echo'<option value="'.$anio.'" '.((date('Y')==$anio)? 'selected' : '').'>'.$anio.'</option>';
                                } 
                              }                                 
                              ?>
                              </select>                            
                      </div>


  
                  </div>
                

      


        </div>
        
        <!--footer-->
        <div class="flex items-center justify-end p-2 border-t border-solid border-blueGray-200 rounded-b">
          <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl text-white text-sm xl:text-base px-4 py-1 mr-2 cerrarCrearCosteTecnico">Cerrar</button>
          <button id="crearCostes" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-white text-sm xl:text-base px-4 py-1">Crear</button>
        </div>

      </form>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="crear-CosteTecnico-backdrop"></div>