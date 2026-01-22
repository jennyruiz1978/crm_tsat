
    <div class="grid grid-cols-2 xl:grid-cols-5 gap-2 md:gap-2 m-2">
        <div class="grid grid-cols-1 ">
            <label class=" md:text-sm text-xs text-gray-500 text-light font-semibold">Año</label>
            <select id="anioTecnicos" class="p-1 rounded-lg border-2 border-gray-200 mt-1 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent">1
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
        

    



    <div class="flex flex-row flex-wrap flex-grow mt-2">

        <div class="w-full md:w-1/2 lg:w-1/3 p-1">
            <!--Graph Card-->
            <div class="bg-white border-transparent rounded-lg shadow-xl">
                <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                    <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Inc./Técnico</h5>
                </div>
                <div class="p-1 xl:p-5">                                            
                    <div id="numeroIncidenciasPorTecnico" class="mb-3 text-center mr-2 pr-2" width="100%" height="auto"></div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 lg:w-1/3 p-1">
            <!--Graph Card-->
            <div class="bg-white border-transparent rounded-lg shadow-xl">
                <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                    <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Hras. Realiz./Técnico</h5>
                </div>
                <div class="p-1 xl:p-5">                            
                    <div id="numeroHorasPorTecnico" class="mb-3 text-center mr-2 pr-2" width="100%" height="auto"></div>
                </div>
            </div>
        </div>


        <div class="w-full md:w-2/2 lg:w-1/3 p-1">
            <!--Graph Card-->
            <div class="bg-white border-transparent rounded-lg shadow-xl">
                <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                    <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Coste(€)/Técnico</h5>
                </div>
                <div class="p-1 xl:p-5">                            
                    <div id="costeHorasRealizadasPorTecnico" class="mb-3 text-center mr-2 pr-2" width="100%" height="auto"></div>
                </div>
            </div>
        </div>

    </div>









        <?php // inicio gráficos ?>

            <!--
            <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-1 m-1">
                                        
                <div class="grid grid-cols-1 bg-white border-transparent rounded-lg shadow-xl my-2" style="height: fit-content;">
                    <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                        <h5 class="font-bold text-gray-600 text-base">Incidencias por técnico</h5>
                    </div>
                    <div class="grid grid-cols-1 bg-white shadow-md">
                        <div id="numeroIncidenciasPorTecnico" class="mb-3 text-center" width="100%" height="auto"></div>
                    </div>
                </div>                       

                        
                <div class="grid grid-cols-1 bg-white border-transparent rounded-lg shadow-xl my-2" style="height: fit-content;">
                    <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                        <h5 class="font-bold text-gray-600 text-base">Horas realizadas por técnico</h5>
                    </div>
                    <div class="grid grid-cols-1 bg-white shadow-md">
                        <div id="numeroHorasPorTecnico" class="mb-3 text-center" width="100%" height="auto"></div>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 md:col-span-2 2xl:col-span-1 bg-white border-transparent rounded-lg shadow-xl my-2" style="height: fit-content;">
                    <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                        <h5 class="font-bold text-gray-600 text-base">Coste (€) realizado por técnico</h5>
                    </div>
                    <div class="grid grid-cols-1 bg-white shadow-md">
                        <div id="costeHorasRealizadasPorTecnico" class="mb-3 text-center" width="100%" height="auto"></div>
                    </div>
                </div>                       

            </div>
            -->
        <?php // fin gráficos ?>
