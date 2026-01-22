<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">
        <slot></slot>
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->

        
            <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Incidencias</h2>
              <!-- Inicio de la tarjeta explicativa -->
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
      <div class="md:flex">
        <div class="md:flex-shrink-0">
          <img class="h-48 w-full object-cover md:w-48" src="<?php print RUTA_URL;  ?>/public/img/card-img.jpg" alt="Mockup">
        </div>
        <div class="p-8">
          <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Incidencias</div>
          <a href="#" class="block mt-1 text-lg leading-tight font-medium text-black hover:underline">Aqu&iacute; se relacioan todas las incidencias enviadas a los tecnicos y qu&eacute; clientes las han enviado. Esta pantalla permite que cualquier t&eacute;cnico pueda ver las incidencias que hay pendientes, por si tiene que apoyar a alg&uacute;n compañero con clientes que no tenag asignados.</a>
          <p class="mt-2 text-gray-500">El listado se hace visible por defecto para las incidencias pendientes o en curso, pudiendose filtrar por el resto de estados, clientes, y t&eacute;cnicos</p>
          <p class="font-bold text-xs mt-3">Visible solo para los usuarios Admin y T&eacute;cnicos</p>
        </div>
      </div>
    </div>
    <!-- Fin de la tarjeta explicativa -->
        <!-- ****** FIN DEL CONTENIDO DE CADA PAGINA ****** -->
      </main>
  </div>

</div>

</main> <!--Esta etiqueta Main es el fin del sidebar -->


<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>