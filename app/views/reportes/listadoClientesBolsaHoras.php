<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">        

        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->                

        <!-- ****** INICIO DEL LISTADO DE INCIDENCIAS ****** -->
        <div class="container mx-auto px-1 xl:px-2">

          <!-- ****** ALERTAS DE ERROR ****** -->
            
          


          <div class="py-4">
                  <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Listado de Cliente con bolsa de horas</h2>
                                        
                  <?php //MONTAJE DEL BUSCADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                  <div class="my-2 flex sm:flex-row flex-col">
                      <div class="flex flex-row mb-1 sm:mb-0">
                          <div class="relative flex" id="buscador">                            
                          </div>                          
                      </div>
                  </div>

                  <?php //MONTAJE DE LA TABLA QUE VIENE DE LA CLASE JS TABLACLASS ?>
                  <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                      <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                          <div id="destinoclientesBolsaHorasajax"></div>
                      </div>
                  </div>        

                  <?php //MONTAJE DEL PAGINADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                  <div id="paginador"></div>
                  <!-- ========= End  ajax puro =================== -->
              
                              
          </div>
        </div>    
        <!-- ****** FIN DEL LISTADO DE INCIDENCIAS ****** -->





      </main>
  </div>

</div>

</main> <!--Esta etiqueta Main es el fin del sidebar -->

<script  type="module">

  import arrancar from "<?php print RUTA_URL;  ?>/public/js/tablaClass/tablaClass.js" 
  arrancar("tablaclientesbolsahoras","Reportes/crearTablaClientesBolsaHoras", "destinoclientesBolsaHorasajax", "tie.creacion", "DESC", 0, "buscador","Reportes/totalRegistrosClientesBolsaHoras", [20, 30, 40, 50],"min-w-full leading-normal","paginador",[],"","");

</script>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>