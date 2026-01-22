

            

            <div class="grid grid-cols-2 xl:grid-cols-5 gap-2 md:gap-2 m-2">
                <div class="grid grid-cols-1 ">
                    <label class=" md:text-sm text-xs text-gray-500 text-light font-semibold">Año</label>
                    <select id="anioClientes" class="p-1 rounded-lg border-2 border-gray-200 mt-1 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent">
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
     
            <?php //graficos ;?>


            
            <?php //tabla horas clientes ------ en constuccion ?>

            <div class="grid grid-cols-1 md:col-span-2 2xl:col-span-2 bg-white border-transparent rounded-lg shadow-xl my-2 2xl:h-full" >
                <div class="bg-gradient-to-b from-blue-200 to-blue-100  text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2" style="height: fit-content;">
                    <h5 class="font-bold  text-gray-600 text-center text-sm lg:text-base">Horas Clientes</h5>
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
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden" id="contenedorTablaHrasClientes">
                                
                            </div>
                        </div>        

                        <?php //MONTAJE DEL PAGINADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                        <div id="paginador"></div>

                </div>
            </div>

            <?php //fin de horas clientes ?>





            <div class="flex flex-row flex-wrap flex-grow mt-2">

                <div class="w-full lg:w-1/2 xl:w-1/2 2xl:w-1/4 p-1">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Incidencias & Clientes</h5>
                        </div>
                        <div class="p-1 lg:p-5">
                            <div id="historaialNumeroIncidencias" class="mb-3 text-center mr-2 pr-2" width="100%" height="auto"></div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 xl:w-1/2 2xl:w-1/4 p-1">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Modal. Bolsa horas</h5>
                        </div>
                        <div class="p-1 lg:p-5">                            
                            <div id="historialHorasClientesBolsaHoras" class="mb-3 text-center mr-2 pr-2" width="100%" height="auto"></div>
                        </div>
                    </div>
                </div>


                <div class="w-full lg:w-1/2 xl:w-1/2 2xl:w-1/4 p-1">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Modal. Mantenimiento</h5>
                        </div>
                        <div class="p-1 lg:p-5">                            
                            <div id="historialHorasEquiposMantenimiento" class="mb-3 text-center mr-2 pr-2" width="100%" height="auto"></div>
                        </div>
                    </div>
                </div>


                <div class="w-full lg:w-1/2 xl:w-1/2 2xl:w-1/4 p-1 2xl:h-full">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Comparativa Modalid.</h5>
                        </div>
                        <div class="px-2">          
                            
                            <div class="mb-3 text-center" width="100%" height="auto">

                                    <div class="container mx-auto px-2">            
                                        <div class="w-full mb-8 overflow-hidden">
                                            <div class="w-full overflow-x-auto">
                                                <table class="w-full" id="tablaHorasConsumidasClientesAmbasModalidadesContrato">
                                                    <thead>
                                                        <tr class="text-sm font-semibold tracking-wide text-left text-gray-900 border-b border-gray-600">
                                                            <th class="px-1 py-2 text-center">Mes</th>
                                                            <th class="px-1 py-2 text-center">Bolsa hrs.</th>
                                                            <th class="px-1 py-2 text-center">Manten.</th>                                
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

            </div>
           
            <?php // fin gráficos ?>



