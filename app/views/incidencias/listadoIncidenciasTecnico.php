<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">        

        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->        
         
        

        <!-- ****** INICIO DEL LISTADO DE INCIDENCIAS ****** -->
        <div class="container mx-auto px-1 xl:px-2">
          <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Listado de solicitudes</h2>
          <div class="inline-flex my-2">                                            
              <a href="<?php echo RUTA_URL;  ?>/Incidencias/crearIncidenciaTecnico" id="nuevaIncidencia" class='w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'><i class="fas fa-plus mr-2 text-base"></i> Nueva solicitud</a>
          </div> 

          <!-- ****** ALERTAS DE ERROR ****** -->
            
          <?php                    
                  //control de mensajes de error o éxito:
                  $color ='';
                  if(isset($_SESSION['message'])){
                    if( strpos( $_SESSION['message'], 'corréctamente' ) != false ){
                      $color = 'bg-green-700';
                    }else{
                      $color = 'bg-blue-700';
                    }
          ?>
          <div class="text-white px-6 py-4 border-0 rounded relative mb-4 <?php echo $color;?>">
                  <span class="text-xl inline-block mr-5 align-middle">
                  <i class="fas fa-bell"></i>
                  </span>
                  <span class="inline-block align-middle mr-8">
                    <b><?php echo $_SESSION['message'];?></b>
                  </span>
                  <button class="butonCerrarAlerta absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none">
                    <span>×</span>
                  </button>
          </div>
          <?php
                      unset($_SESSION['message']);
                    }
          ?>




          <div class="py-4">
                  


                  <?php //INCIO DE TAB PANE LISTADOS ?>
                  <div class="flex flex-wrap" id="tabs-id">
                    <div class="w-full">
                        <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
                            <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                                <a class="tab-incidencias text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-white bg-violeta-oscuro" data-tab="tab-misincidencias" data-metodo="listarIncidenciasTecnico">
                                <i class="fas fa-space-shuttle text-base mr-1"></i>  Asignadas a mí
                                </a>
                            </li>
                            <?php
                              if (isset($datos['permiso']) && $datos['permiso'] == 1) {                                
                            ?>
                            <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                                <a class="tab-incidencias text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal texto-violeta-oscuro bg-white" data-tab="tab-todas" data-metodo="listarTodasLasIncidencias" >
                                <i class="fas fa-cog text-base mr-1"></i>  Todas
                                </a>
                            </li>
                            <?php
                              }
                            ?>
                        </ul>
                        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                            <div class="px-4 py-5 flex-auto">
                                <span id="msgIniciarDetener" class="font-bold font-bold text-pink-600"></span>
                                <div class="tab-content tab-space">    
                                  
                                  <?php //LISTADO DE INCIDENCIAS POR CADA TÉCNICO ?>
                                  <div class="block tab-apartado" id="tab-misincidencias">                                 
                                      <?php //MONTAJE DEL BUSCADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                                      <div class="my-2 flex sm:flex-row flex-col">
                                          <div class="flex flex-row mb-1 sm:mb-0">
                                              <div class="relative flex" id="buscador">                            
                                              </div>                                              
                                          </div>                                          
                                      </div>

                                      <?php //MONTAJE DE LA TABLA QUE VIENE DE LA CLASE JS TABLACLASS ?>
                                      <div class="overflow-x-auto">
                                          <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                              <div id="destinoincidenciastecnicosajax"></div>
                                          </div>
                                      </div>        

                                      <?php //MONTAJE DEL PAGINADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                                      <div id="paginador"></div>
                                      <!-- ========= End  ajax puro =================== -->
                                      <script  type="module">

                                        import arrancar from "<?php print RUTA_URL;  ?>/public/js/tablaClass/tablaClass.js" 
                                        arrancar("tablaincidencias","Incidencias/crearTablaIncidenciasTecnicos", "destinoincidenciastecnicosajax", "inc.estado ASC, inc.fechahora DESC", "DESC", 0, "buscador","Incidencias/totalRegistrosIncidenciasTecnicos", [20, 30, 40, 50],"min-w-full leading-normal","paginador",["estadoatencion","ver","reasignar","terminar","historial"],"<?php echo RUTA_URL.'/Incidencias/editarIncidencia';?>","");

                                      </script>
                                  </div>  


                                  <?php //LISTADO DE TODAS LAS INCIDENCIAS ?>
                                  <div class="hidden tab-apartado" id="tab-todas">
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
          </div>
        </div>    
        <!-- ****** FIN DEL LISTADO DE INCIDENCIAS ****** -->





      </main>

</div>

</main> <!--Esta etiqueta Main es el fin del sidebar -->

<?php require_once(RUTA_APP . '/views/incidencias/reasignarVerTecnicos.php'); ?>
<?php require_once(RUTA_APP . '/views/incidencias/iniciarAtencion.php'); ?>
<?php require_once(RUTA_APP . '/views/incidencias/detenerAtencion.php'); ?>
<?php require_once(RUTA_APP . '/views/incidencias/finalizarIncidencia.php'); ?>
<?php require_once(RUTA_APP . '/views/incidencias/verHistorialTiempos.php'); ?>
<?php require_once(RUTA_APP . '/views/incidencias/eliminarAtencion.php'); ?>   
<?php require_once(RUTA_APP . '/views/incidencias/modalLoadAjax.php'); ?>
<?php require_once(RUTA_APP . '/views/incidencias/eliminarIncidencia.php'); ?> 
<?php require_once(RUTA_APP . '/views/incidencias/reabrirIncidencia.php'); ?>
<?php require_once(RUTA_APP . '/views/incidencias/modalFacturarPresupuestar.php'); ?>
<?php //require_once(RUTA_APP . '/views/incidencias/rechazarIncidencia.php'); ?>


<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>