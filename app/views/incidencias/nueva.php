<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">
        <slot></slot>
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->

        
            <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Nueva incidencia</h2>
              <!-- Inicio de la tarjeta explicativa -->
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
      <div class="md:flex">
        <div class="md:flex-shrink-0">
          <img class="h-48 w-full object-cover md:w-48" src="<?php print RUTA_URL;  ?>/public/img/card-img.jpg" alt="Mockup">
        </div>
        <div class="p-8">
          <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Nueva Incidencia</div>
          <a href="#" class="block mt-1 text-lg leading-tight font-medium text-black hover:underline">Formulario para dar de alta una nueva incidencia por parte del cliente</a>
          <p class="mt-2 text-gray-500">Aqu&iacute; el cliente crea la incidencia que le va a llegar al tecnico, pudiendo añadir por escrito la raz&oacute;n de la incidencia. Adem&acute;s el sistema detecta automaticamente los datos del cliente para informar al t&eacute;cnico asignado.</p>
          <p class="font-bold text-xs mt-3">Visible para usuarios Admin y Clientes</p>
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