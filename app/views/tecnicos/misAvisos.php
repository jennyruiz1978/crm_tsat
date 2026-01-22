<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    
      <main class="w-full flex-grow p-6">
        <slot></slot>
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->

        
            <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Mis Incidencias</h2>
              <!-- Inicio de la tarjeta explicativa -->
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
      <div class="md:flex">
        <div class="md:flex-shrink-0">
          <img class="h-48 w-full object-cover md:w-48" src="<?php print RUTA_URL;  ?>/public/img/card-img.jpg" alt="Mockup">
        </div>
        <div class="p-8">
          <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Mis incidencias</div>
          <a href="#" class="block mt-1 text-lg leading-tight font-medium text-black hover:underline">En esta pantalla se detallan las incidencias que el sistema asigna automaticamente a cada tecnico, en funci&oacute;n de la asignaci&oacute;n que haya hecho el Admin en el menu ajustes/t&eacute;cnicos</a>
          <p class="mt-2 text-gray-500">Las incidencias se entregan filtradas por pendientes o en curso, y es aqu&iacute; donde el t&eacute;cnico hace click en la incidencia y añade comentarios para el cliente, adem&aacute;s tambien puede hacer anotaciones que solo puede ver el usuario Admin y tecnico.</p>
          <p class="mt-2 text-gray-500"> Se incluyen botones para iniciar el tiempo de ejecuciñon del trabajo, pausar y terminar. Esta información se analiza en el menu Dashbioard que puede ver el usuario Admin</p>
          <p class="font-bold text-xs mt-3">Visible solo para los usuarios Admin y  el T&eacute;cnico que este logado en el sistema</p>
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