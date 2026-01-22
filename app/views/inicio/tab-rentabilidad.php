
<div class="grid grid-cols-2 xl:grid-cols-5 gap-2 md:gap-2 m-2">
  <div class="grid grid-cols-1 ">
      <label class=" md:text-sm text-xs text-gray-500 text-light font-semibold">Año</label>
      <select id="anioRentabilidad" class="p-1 rounded-lg border-2 border-gray-200 mt-1 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent">
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
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-1 m-1">
                            




                        <div class="grid grid-cols-1 md:col-span-2 xl:col-span-2">

                            <div class="grid grid-cols-1 xl:grid-cols-2">





                                        <div class="bg-white border-transparent rounded-lg shadow-xl m-2">
                                            <div class="bg-gradient-to-b from-blue-200 to-blue-100  text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                                                <h5 class="font-bold  text-gray-600 text-center text-sm lg:text-base">Rend. Bolsa (hrs)</h5>
                                            </div>
                                            <div class="grid grid-cols-1 bg-white shadow-md">
                                                <div id="rendimientoClientesBolsa" class="m-2 md:m-5 text-center" width="100%" height="auto"></div>
                                            </div>
                                        </div>             
                                        
                                        
                                        <div class="bg-white border-transparent rounded-lg shadow-xl m-2">
                                            <div class="bg-gradient-to-b from-blue-200 to-blue-100  text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                                                <h5 class="font-bold  text-gray-600 text-center text-sm lg:text-base">Rend. Bolsa (€)</h5>
                                            </div>
                                            <div class="grid grid-cols-1 bg-white">
                                                    
                                                <div class="container mx-auto p-2">            
                                                    <div class="w-full mb-8 overflow-hidden">
                                                        <div class="w-full overflow-x-auto">
                                                            <table class="w-full" id="tablaIngresosClientesConBolsa">
                                                                <thead>
                                                                    <tr class="text-sm font-semibold tracking-wide text-left text-gray-900 border-b border-gray-600">
                                                                        <th class="px-2 py-2">Mes</th>
                                                                        <th class="px-2 py-2 text-center">Cost.(€)</th>
                                                                        <th class="px-2 py-2 text-center">Ingres.(€)</th>
                                                                        <th class="px-2 py-2 text-center">Rentab.(€)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="bg-white">                                       
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="bg-white border-transparent rounded-lg shadow-xl m-2">
                                            <div class="bg-gradient-to-b from-blue-200 to-blue-100  text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                                                <h5 class="font-bold  text-gray-600 text-center text-sm lg:text-base">Rend. Eq. Mantenim.(hrs)</h5>
                                            </div>
                                            <div class="grid grid-cols-1 bg-white shadow-md">
                                                <div id="rendimientoClientesPrecioFijo" class="m-2 md:m-5 text-center" width="100%" height="auto"></div>
                                            </div>
                                        </div>

                                        
                                        <div class="bg-white border-transparent rounded-lg shadow-xl m-2">
                                            <div class="bg-gradient-to-b from-blue-200 to-blue-100  text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                                                <h5 class="font-bold  text-gray-600 text-center text-sm lg:text-base">Rend. Eq. Mantenim.(€)</h5>
                                            </div>
                                            <div class="grid grid-cols-1 bg-white">
                                                    
                                                <div class="container mx-auto p-2">            
                                                    <div class="w-full mb-8 overflow-hidden">
                                                        <div class="w-full overflow-x-auto">
                                                            <table class="w-full" id="tablaIngresosClientesPrecioFijo">
                                                                <thead>
                                                                    <tr class="text-sm font-semibold tracking-wide text-left text-gray-900 border-b border-gray-600">
                                                                        <th class="px-2 py-2">Mes</th>
                                                                        <th class="px-2 py-2 text-center">Cost.(€)</th>
                                                                        <th class="px-2 py-2 text-center">Ingres.(€)</th>
                                                                        <th class="px-2 py-2 text-center">Rentab.(€)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="bg-white">                                       
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>





                            </div>
                        
                        </div>



                        <div class="grid grid-cols-1 md:col-span-2 xl:col-span-1">
                                                    
                                
                                <div class="bg-white border-transparent rounded-lg shadow-xl my-2">
                                    <div class="bg-gradient-to-b from-blue-200 to-blue-100  text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                                        <h5 class="font-bold  text-gray-600 text-center text-sm lg:text-base">Rentabilidad global (€)</h5>
                                    </div>
                                    <div class="grid grid-cols-1 bg-white shadow-md">
                                        <div id="ingresosNetosMensuales" class="m-2 md:m-5 text-center" width="100%" height="auto"></div>
                                    </div>
                                </div>

                        </div>











</div>
<?php // fin gráficos ?>
