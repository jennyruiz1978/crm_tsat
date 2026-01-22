<?php                            
        $fechaInicio = new DateTime();
        $fechaInicio->modify('first day of this month');
        $fechaIni = $fechaInicio->format('Y-m-d');
            
        $fechaFinal = new DateTime();
        $fechaFinal->modify('last day of this month');
        $fechaFin = $fechaFinal->format('Y-m-d');
            
        $anioActual = date('Y');                                    
?>
            
<div class="grid grid-cols-2 xl:grid-cols-5 gap-2 md:gap-2 m-2">
  <div class="grid grid-cols-1 ">
      <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Fecha inicio</label>
      <input type="date" id="fechaIni" class="p-1 rounded-lg border-2 border-gray-200 mt-1 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent" type="text" value="<?php echo $fechaIni;?>"/>
  </div>
  <div class="grid grid-cols-1 ">
      <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Fecha fin</label>
      <input type="date" id="fechaFin" class="p-1 rounded-lg border-2 border-gray-200 mt-1 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent" type="text" value="<?php echo $fechaFin;?>" />
  </div>
  <div class="mt-2" style="height: min-content;">
    <button class='w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-base text-white px-4 py-1 lg:mt-5' id="btnBuscarDashboard">Buscar</button>
  </div>
</div>    
            

<?php

    $contadorRango = 0;
    if ($datos['contadorRango']) {
        $contadorRango = $datos['contadorRango']->contador;
    }
    $contadorAnio = 0;
    if ($datos['contadorAnio']) {
        $contadorAnio = $datos['contadorAnio']->contador;
    }
    $tiempoRango = 0;
    if ($datos['tiempoRango']) {
        $tiempoRango = $datos['tiempoRango'];
    }
    $tiempoAnio = 0;
    if ($datos['tiempoAnio']) {
        $tiempoAnio = $datos['tiempoAnio'];
    }
    $pendientesRango = 0;
    if ($datos['pendientesRango']) {
        $pendientesRango = $datos['pendientesRango']->contador;
    }
    $pendientesAnio = 0;
    if ($datos['pendientesAnio']) {
        $pendientesAnio = $datos['pendientesAnio']->contador;
    }
    $clientesRango = 0;
    if ($datos['clientesRango']) {
        $clientesRango = $datos['clientesRango']->contador;
    }
    $clientesAnio = 0;
    if ($datos['clientesAnio']) {
        $clientesAnio = $datos['clientesAnio']->contador;
    }
    $equiposRango = 0;
    if ($datos['equiposRango']) {
        $equiposRango = $datos['equiposRango']->contador;
    }
    $equiposAnio = 0;
    if ($datos['equiposAnio']) {
        $equiposAnio = $datos['equiposAnio']->contador;
    }

?>



<?php // inicio pastillas ?>
<div class="flex flex-wrap">

    <div class="w-full md:w-1/2 lg:w-1/2 xl:w-1/3 2xl:w-1/5 p-1 my-1">
        <!--Metric Card-->
        <div class="bg-gradient-to-b from-purple-200 to-purple-100 border-b-4 border-purple-600 rounded-lg shadow-xl p-2">
            <div class="flex items-center">
                <div class="flex-shrink pr-2">
                    <div class="rounded-full p-2 bg-purple-600"><i class="fas fa-info-circle fa-lg fa-inverse"></i></div>
                </div>
                <div class="flex-auto text-center">
                    <h5 class="text-sm lg:text-base font-bold text-gray-600">Inc. totales</h5>
                    <h3 class="font-bold xs:text-sm lg:text-lg"><span id="incidenciasTotales"><?php echo $contadorRango;?></span> <span class="text-purple-600"><i class="far fa-file-alt"></i></span></h3>
                </div>
                <div class="flex-auto text-right">
                    <h5 class="text-sm text-gray-600">Acum. <?php echo $anioActual; ?></h5>
                    <h3 class="font-bold text-sm"><?php echo $contadorAnio;?> <span class="text-purple-600"><i class="far fa-file-alt"></i></span></h3>
                </div>
            </div>
        </div>
        <!--/Metric Card-->
    </div>

    <div class="w-full md:w-1/2 lg:w-1/2 xl:w-1/3 2xl:w-1/5 p-1 lg:my-1">
        <!--Metric Card-->
        <div class="bg-gradient-to-b from-indigo-200 to-indigo-100 border-b-4 border-indigo-500 rounded-lg shadow-xl p-2">
            <div class="flex items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-2 bg-indigo-600"><i class="fas fa-server fa-lg fa-inverse"></i></div>
                </div>
                <div class="flex-auto text-center">
                    <h5 class="text-sm lg:text-base font-bold text-gray-600">Inc. Pend.</h5>
                    <h3 class="font-bold xs:text-sm lg:text-lg"><span id="incidenciasPendientes"><?php echo $pendientesRango;?></span> <span class="text-indigo-600"><i class="fas fa-hourglass-start"></i></span></h3>                                           
                </div>
                <div class="flex-auto text-right">
                    <h5 class="text-sm text-gray-600">Acum.<?php echo $anioActual; ?></h5>
                    <h3 class="font-bold text-sm"><?php echo $pendientesAnio;?> <span class="text-indigo-600"><i class="fas fa-hourglass-start"></i></span></h3>
                </div>
            </div>
        </div>
        <!--/Metric Card-->
    </div>    

    <div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/3 2xl:w-1/5 p-1 lg:my-1">
        <!--Metric Card-->
        <div class="bg-gradient-to-b from-blue-200 to-blue-100 border-b-4 border-blue-500 rounded-lg shadow-xl p-2">
            <div class="flex items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-2 bg-blue-600"><i class="far fa-clock fa-lg fa-inverse"></i></div>
                </div>
                <div class="flex-auto text-center">
                    <h5 class="text-sm lg:text-base font-bold text-gray-600">Hrs. Técn.</h5>
                    <h3 class="font-bold xs:text-sm lg:text-lg"><span id="horasRealizadas"><?php echo $tiempoRango->tiempototal;?></span> <span class="text-blue-500"><i class="far fa-clock"></i></span></h3>                                              
                </div>
                <div class="flex-auto text-right">
                    <h5 class="text-sm text-gray-600">Acum.<?php echo $anioActual; ?></h5>
                    <h3 class="font-bold text-sm"><?php echo $tiempoAnio->tiempototal; ?> <span class="text-blue-500"><i class="far fa-clock"></i></span></h3>
                </div>
            </div>
        </div>
        <!--/Metric Card-->
    </div>
                                    
    <div class="w-full md:w-1/2 lg:w-1/3 xl:w-1/2 2xl:w-1/5 p-1 lg:my-1">
        <!--Metric Card-->
        <div class="bg-gradient-to-b from-red-200 to-red-100 border-b-4 border-red-600 rounded-lg shadow-xl p-2">
            <div class="flex items-center">
                <div class="flex-shrink pr-2">
                    <div class="rounded-full p-2 bg-red-600"><i class="fas fa-users fa-lg fa-inverse"></i></div>
                </div>
                <div class="flex-auto text-center">
                    <h5 class="text-sm lg:text-base font-bold text-gray-600">Clientes</h5>
                    <h3 class="font-bold xs:text-sm lg:text-lg"><span id="clientesAtendidos"><?php echo $clientesRango;?></span> <span class="text-red-600"><i class="fas fa-users"></i></span></h3>
                </div>
                <div class="flex-auto text-right">
                    <h5 class="text-sm text-gray-600">Acum. <?php echo $anioActual; ?></h5>
                    <h3 class="font-bold text-sm"><?php echo $clientesAnio;?> <span class="text-red-600"><i class="fas fa-users"></i></span></h3>
                </div>
            </div>
        </div>
        <!--/Metric Card-->
    </div>

    <div class="w-full md:w-2/2 lg:w-1/3 xl:w-1/2 2xl:w-1/5 p-1 lg:my-1">
        <!--Metric Card-->
        <div class="bg-gradient-to-b from-blue-200 to-blue-100 border-b-4 border-violeta-oscuro rounded-lg shadow-xl p-2">
            <div class="flex items-center">
                <div class="flex-shrink pr-4">
                    <div class="rounded-full p-2 bg-violeta-oscuro"><i class="fas fa-laptop fa-lg fa-inverse"></i></div>
                </div>
                <div class="flex-auto text-center">
                    <h5 class="text-sm lg:text-base font-bold text-gray-600">Equipos</h5>
                    <h3 class="font-bold xs:text-sm lg:text-lg"><span id="equiposAtendidos"><?php echo $equiposRango;?></span> <span class="text-violeta-oscuro"><i class="fas fa-laptop"></i></span></h3>                                              
                </div>
                <div class="flex-auto text-right">
                    <h5 class="text-sm text-gray-600">Acum.<?php echo $anioActual; ?></h5>
                    <h3 class="font-bold text-sm"> <?php echo $equiposAnio; ?> <span class="text-violeta-oscuro"><i class="fas fa-laptop"></i></span></h3>
                </div>
            </div>
        </div>
        <!--/Metric Card-->
    </div>

    

</div>                                      
<?php // fin pastillas ?>



<?php // inicio gráficos ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-1 m-1">
                            
    <div class="bg-white border-transparent rounded-lg shadow-xl my-2">
        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Modalidades de atención</h5>
        </div>
        <div class="grid grid-cols-1 bg-white shadow-md">
            <div id="donaModalidades" class="mb-3 text-center" width="100%" height="auto"></div>
        </div>
    </div>                       

    <div class="bg-white border-transparent rounded-lg shadow-xl my-2">
        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Estado de incidencias</h5>
        </div>
        <div class="grid grid-cols-1 bg-white shadow-md">
            <div id="pieIncidenciasEstado" class="mb-3 text-center" width="100%" height="auto"></div>
        </div>
    </div>
    
    <div class="bg-white border-transparent rounded-lg shadow-xl my-2">
        <div class="bg-gradient-to-b from-blue-200 to-blue-100 text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
            <h5 class="font-bold text-gray-600 text-center text-sm lg:text-base">Hrs. Contrat. por Modal.</h5>
        </div>
        <div class="grid grid-cols-1 bg-white shadow-md">
            <div id="horasSegunModalidadConstratada" class="mb-3 text-center" width="100%" height="auto"></div>
        </div>
    </div>  

</div>
<?php // fin gráficos ?>
