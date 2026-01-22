<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

  <div class="w-full overflow-x-hidden border-t flex flex-col">

    <main class="w-full flex-grow p-6">    
      <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->


        <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Inicio</h2>    

        <!--inicio de tab pane dashboard-->
        <div class="flex flex-wrap" id="tabs-dashboard">
          <div class="w-full">

              <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
                  <li class="py-1 mr-2 last:mr-0 flex-auto text-center">
                      <a class="tab-dashboard text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-white bg-violeta-oscuro" data-tab="tab-generales" data-metodo="verDashboardGenerales">
                      <i class="fas fa-chart-pie text-base mr-1"></i>  Datos generales
                      </a>
                  </li>
                  <li class="py-1 mr-2 last:mr-0 flex-auto text-center">
                      <a class="tab-dashboard text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal texto-violeta-oscuro bg-white" data-tab="tab-clientes" data-metodo="verDashboardClientes">
                      <i class="fas fa-user-tie text-base mr-1"></i>  Clientes
                      </a>
                  </li>
                  <li class="py-1 mr-2 last:mr-0 flex-auto text-center">
                      <a class="tab-dashboard text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal texto-violeta-oscuro bg-white" data-tab="tab-tecnicos" data-metodo="verDashboardTecnicos">
                      <i class="fas fa-tools"></i>  Técnicos
                      </a>
                  </li>
                  <li class="py-1 mr-2 last:mr-0 flex-auto text-center">
                      <a class="tab-dashboard text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal texto-violeta-oscuro bg-white" data-tab="tab-equipos" data-metodo="verDashboardEquipos">
                      <i class="fas fa-laptop"></i>  Equipos
                      </a>
                  </li>
                  <li class="py-1 mr-2 last:mr-0 flex-auto text-center">
                      <a class="tab-dashboard text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal texto-violeta-oscuro bg-white" data-tab="tab-rentabilidad" data-metodo="verDashboardRentabilidad">
                      <i class="fas fa-euro-sign"></i>  Rentabilidad
                      </a>
                  </li>
              </ul>
              
              <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded">
                  <div class="flex-auto">
                      <div class="tab-content tab-space">

                        <div class="block pastillDashboard" id="tab-generales">     
                              <?php require_once(RUTA_APP . '/views/inicio/tab-datosgenerales.php'); ?>
                        </div>

                        <div class="hidden pastillDashboard" id="tab-clientes">
                          <?php require_once(RUTA_APP . '/views/inicio/tab-clientes.php'); ?>
                        </div>

                        <div class="hidden pastillDashboard" id="tab-tecnicos">
                          <?php require_once(RUTA_APP . '/views/inicio/tab-tecnicos.php'); ?>
                        </div>

                        <div class="hidden pastillDashboard" id="tab-equipos">
                          <?php require_once(RUTA_APP . '/views/inicio/tab-equipos.php'); ?>
                        </div>

                        <div class="hidden pastillDashboard" id="tab-rentabilidad">
                          <?php require_once(RUTA_APP . '/views/inicio/tab-rentabilidad.php'); ?>
                        </div>

                      </div>
                  </div>
              </div>
          </div>
        </div>
        <!--fin de tab pane dashboard-->

      <!-- ****** FIN DEL CONTENIDO DE CADA PAGINA ****** -->
    </main>
  </div>

  
  
</div>

</main>

<!--Esta etiqueta Main es el fin del sidebar -->


<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>