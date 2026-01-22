<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">
        <slot></slot>
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->

        
            <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Tiempos</h2>
        
            <ps-boton nombre="Control de tiempos"></ps-boton>
            <ps-boton-alta nombre="Tiempos"></ps-boton-alta>
              <!-- Inicio de la tarjeta explicativa -->
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
      <div class="md:flex">
        <div class="md:flex-shrink-0">
          <img class="h-48 w-full object-cover md:w-48" src="<?php print RUTA_URL;  ?>/public/img/card-img.jpg" alt="Mockup">
        </div>
        <div class="p-8">
          <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Control de tiempos</div>
          <a href="#" class="block mt-1 text-lg leading-tight font-medium text-black hover:underline">Cuadro de mando con el control de tiempos por incidencia, por tecnicos, cliente, etc.</a>
          <p class="mt-2 text-gray-500">Este cuadro de mandos se podrá filtrar por fechas, por cliente, t&eacute;cnico, horas de resolución de incidencias, comparativa con horas total trabajadas, etc. </p>
            <p class="font-bold text-xs mt-3">Visible solo para usuario Admin</p>
        </div>
      </div>
    </div>
    <!-- Fin de la tarjeta explicativa -->
    <grafico-prueba></grafico-prueba>
        <!-- ****** FIN DEL CONTENIDO DE CADA PAGINA ****** -->
      </main>
  </div>

</div>

</main> <!--Esta etiqueta Main es el fin del sidebar -->


<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>