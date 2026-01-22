<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">
        <slot></slot>
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->

        
            <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Seguimiento incidencias</h2>
              <!-- Inicio de la tarjeta explicativa -->
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
      <div class="md:flex">
        <div class="md:flex-shrink-0">
          <img class="h-48 w-full object-cover md:w-48" src="<?php print RUTA_URL;  ?>/public/img/card-img.jpg" alt="Mockup">
        </div>
        <div class="p-8">
          <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Seguimiento incidencias</div>
          <a href="#" class="block mt-1 text-lg leading-tight font-medium text-black hover:underline">Aqu&iacute; el cliente puede ver el estado de sus incidencias, pendientes, en curso, terminadas, etc.  </a>
          <p class="mt-2 text-gray-500">Al hacer click sobre el listado se el muestra el contenido de la incidencia, y las observaciones del tecnico si las hubiere. El sistema detecta al usuario y si es un cliente solo le muestra sus incidencias, y si es el admin del sistema le enseña todas. Se añade un buscador para facilitar la localizaci&oacute;n de incidencias por tipo, por estado, por cliente, etc.</p>
          <p class="font-bold text-xs mt-3">Visible solo para los usuariso Admin y Clientes</p>
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