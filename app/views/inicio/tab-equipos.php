
<div class="grid grid-cols-2 xl:grid-cols-5 gap-2 md:gap-2 m-2">
  <div class="grid grid-cols-1 ">
      <label class=" md:text-sm text-xs text-gray-500 text-light font-semibold">Año</label>
      <select id="anioEquipos" class="p-1 rounded-lg border-2 border-gray-200 mt-1 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent">1
          <?php

            $anioActual = date('Y');            
            if (isset($datos['anios']) && count($datos['anios'])>0) {
                foreach ($datos['anios'] as $key) {
                    echo"<option value='".$key->anio."' ".(($key->anio == $anioActual)? 'selected': '').">".$key->anio."</option>";
                }
            }else{
                echo"<option value='".$anioActual."'>".$anioActual."</option>";
            }            
          ?>
      </select>
  </div>
</div>
     

<?php // inicio gráficos ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-1 m-1">
                            
    <div class="grid grid-cols-1 bg-white border-transparent rounded-lg shadow-xl my-2" style="height: fit-content;">
        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Equipos atendidos</h5>
        </div>
        <div class="grid grid-cols-1 bg-white shadow-md">
            <div id="numeroEquiposAtendidos" class="text-center m-2 md:m-5" width="100%" height="auto"></div>
        </div>
    </div>                       
               
    <div class="grid grid-cols-1 bg-white border-transparent rounded-lg shadow-xl my-2" style="height: fit-content;">
        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Eq. por modalidad</h5>
        </div>
        <div class="grid grid-cols-1 bg-white shadow-md">
            <div id="numeroEquiposAtendidosPorModalContratada" class="text-center m-2 md:m-5" width="100%" height="auto"></div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:col-span-2 2xl:col-span-2 bg-white border-transparent rounded-lg shadow-xl my-2 2xl:h-full" >
        <div class="bg-gradient-to-b from-blue-200 to-blue-100  text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2" style="height: fit-content;">
            <h5 class="font-bold  text-gray-600 text-center text-sm lg:text-base">Equipos menos rentables</h5>
        </div>
        <div class="grid grid-cols-1 bg-white">
                
                <?php //MONTAJE DEL BUSCADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>                

                <?php //MONTAJE DEL BUSCADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                  <div class="sm:mx-8 px-4 sm:px-8 my-2 flex sm:flex-row flex-col">
                      <div class="flex flex-row mb-1 sm:mb-0">
                          <div class="relative flex" id="buscador">                            
                          </div>                          
                      </div>
                  </div>

                  <?php //MONTAJE DE LA TABLA QUE VIENE DE LA CLASE JS TABLACLASS ?>
                  <div class="sm:mx-8 px-4 sm:px-8 overflow-x-auto">
                      <div class="inline-block min-w-full shadow rounded-lg overflow-hidden" id="contenedorTablaYScript">
                        
                      </div>
                  </div>        

                  <?php //MONTAJE DEL PAGINADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                  <div id="paginador"></div>

        </div>
    </div>


</div>
<?php // fin gráficos ?>

