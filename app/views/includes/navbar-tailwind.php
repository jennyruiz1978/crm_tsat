<?php    

  
if (!isset($_SESSION)) {
  session_start();
}

date_default_timezone_set("Europe/Madrid");
$ahora = date("Y-n-j H:i:s");
$duracion = (strtotime($ahora)-strtotime($_SESSION['inicio']));

if($duracion >= 43200) {    
    session_destroy();
    header("location:index.html");
}

?>

<nav class="<?php print BG_NAVBAR; ?>">
  <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
    <div class="relative flex items-center justify-between h-16">
      <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
        <?php                              
                            
          $hamburguesa = 'none';
          if ($_SESSION['nombrerol']=='admin' || $_SESSION['nombrerol']=='tecnico') {
            $hamburguesa = 'block';
          }
        ?>
        <button style="display: <?php echo $hamburguesa;?>;" id="sidebarBtn" class="px-3 py-2 text-white text-2xl rounded-lg hover:bg-gray-200 hover:text-gray-700">
          <i class="fas fa-bars"></i>
        </button>
        <input id="idUser" type="hidden" value="<?php echo $_SESSION['idusuario'];?>"> 
        <input type="hidden" id="rolUsuarioFinalizar" value="<?php echo $_SESSION['nombrerol'];?>">            
        
        <form method="GET" class="w-full invisible sm:visible">
          <input type="hidden" id="ruta" value="<?php echo RUTA_URL ;?>" >
          <div class="relative text-gray-300 ml-6 px-3 pt-1">
           
          </div>
        </form>
      </div>
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

      <?php
        $verAlerta = ''; 
        if ($_SESSION['nombrerol'] == 'cliente' ) {
          $verAlerta = 'hidden';
        }
      ?>


        <?php 
          if ($_SESSION['nombrerol'] == 'admin' || $_SESSION['nombrerol']  == 'tecnico') {
        ?>

        <?php //alerta facturar ?>
        <div class="relative">
          <div>
            <button class="text-xl text-gray-300 px-2 py-2 focus:outline-none" title="Solicitudes por facturar" id="verFacturarBtn" data-estado="1">
              <i class="fas fa-euro-sign text-yellow-300 text-xl"></i>
              <span class="badge badge-warning navbar-badge text-white font-bold" id="incidenciasPorFacturar" style="position: relative;right: 5px;top: 5px;font-size: 0.95rem;">0</span>
            </button>
          </div>        

          <div id="verFacturarDiv" class="z-50 hidden origin-top-right absolute mt-2 w-64
              rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5
              focus:outline-none overflow-y-auto h-40">
              <p class="text-xs 3xl:text-sm ml-2" id="nohayporfacturar"></p>
              <p class="ml-2 text-gray-600 text-sm font-bold">Solicitudes por facturar</p>
              <table class="w-full sm:bg-white rounded-lg overflow-hidden sm:shadow-lg my-2 text-xs 3xl:text-sm" id="tablaIncidenciasPorFacturar">
                    <thead class="text-white">
                      <tr class="bg-gray-400 rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                              <th class="p-1 text-left">Sol.</th>
                              <th class="p-1 text-left">Cliente</th>     
                              <th class="p-1 text-left"></th>                         
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
              </table>
          </div>
          
        </div>
        
        <?php //alerta presupuestar ?>
        <div class="relative">
          <div>
            <button class="text-xl text-gray-300 px-2 py-2 focus:outline-none" title="Solicitudes por Presupuestar" id="verPresupuestarBtn" data-estado="2">
              <i class="fas fa-file-invoice-dollar text-yellow-300 text-xl"></i>
              <span class="badge badge-warning navbar-badge text-white font-bold" id="incidenciasPorPresupuestar" style="position: relative;right: 5px;top: 5px;font-size: 0.95rem;">0</span>
            </button>
          </div>

          <div id="verPresupuestarDiv" class="z-50 hidden origin-top-right absolute mt-2 w-64
              rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5
              focus:outline-none overflow-y-auto h-40" style="right: -100px;">
              <p class="text-xs 3xl:text-sm ml-2" id="nohayporPresupuestar"></p>
              <p class="ml-2 text-gray-600 text-sm font-bold">Solicitudes por Presupuestar</p>              
              <table class="w-full sm:bg-white rounded-lg overflow-hidden sm:shadow-lg my-2 text-xs 3xl:text-sm" id="tablaIncidenciasPorPresupuestar">
                    <thead class="text-white">
                      <tr class="bg-gray-400 rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                              <th class="p-1 text-left">Sol.</th>
                              <th class="p-1 text-left">Cliente</th>
                              <th class="p-1 text-left"></th>                   
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
              </table>
          </div>
        </div> 
        
        <?php //alerta Aceptados ?>
        <div class="relative">
          <div>
            <button class="text-xl text-gray-300 px-2 py-2 focus:outline-none" title="Presupuestos Aceptados" id="verAceptadosBtn" data-estado="5">
              <i class="fas fa-vote-yea text-yellow-300 text-xl"></i>
              <span class="badge badge-warning navbar-badge text-white font-bold" id="pptosAceptados" style="position: relative;right: 5px;top: 5px;font-size: 0.95rem;">0</span>
            </button>
          </div>

          <div id="verAceptadosDiv" class="z-50 hidden origin-top-right absolute mt-2 w-64
              rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5
              focus:outline-none overflow-y-auto h-40" style="right: -100px;">
              <p class="text-xs 3xl:text-sm ml-2" id="nohayporAceptados"></p>
              <p class="ml-2 text-gray-600 text-sm font-bold">Presupuestos Aceptados</p>              
              <table class="w-full sm:bg-white rounded-lg overflow-hidden sm:shadow-lg my-2 text-xs 3xl:text-sm" id="tablaPptosAceptados">
                    <thead class="text-white">
                      <tr class="bg-gray-400 rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                              <th class="p-1 text-left">Sol.</th>
                              <th class="p-1 text-left">Cliente</th>   
                              <th class="p-1 text-left"></th>                              
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
              </table>
          </div>
        </div>              

        



        <?php //alertas comentario del cliente no leidos ?>
        <div class="relative">
          <div>
            <button class="text-xl text-gray-300 px-2 py-2 focus:outline-none" title="Comentarios nuevos" id="comentariosBtn"><i class="far fa-comments text-yellow-300 text-xl"></i>
              <span class="badge badge-warning navbar-badge text-white font-bold	" id="comentariosNoLeidos" style="position: relative;right: 5px;top: 5px;font-size: 0.95rem;">0</span>              
            </button>
          </div>

          <div id="comentariosDiv" class="z-50 hidden origin-top-right absolute mt-2 w-64
              rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5
              focus:outline-none overflow-y-auto h-40" style="right: -28px;">
              <p class="text-xs 3xl:text-sm ml-2" id="comentariosinleer"></p>
              <p class="ml-2 text-gray-600 text-sm font-bold">Comentarios Nuevos</p>
              <table class="w-full sm:bg-white rounded-lg overflow-hidden sm:shadow-lg my-2 text-xs 3xl:text-sm" id="tablaComentariosNoLeidos">
                    <thead class="text-white">
                      <tr class="bg-gray-400 rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                              <th class="p-1 text-left">Sol.</th>
                              <th class="p-1 text-left">Fecha</th>
                              <th class="p-1 text-left">Usuario</th>
                              <th class="p-1 text-left"></th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
              </table>
          </div>
        </div>  

        <?php //campanita incidencias pendientes ?>
        <div class="relative">
          <div>
            <button class="text-xl text-gray-300 px-2 py-2 focus:outline-none" title="Incidencias pendientes" id="notificationsBtn"><i class="far fa-bell text-yellow-300 text-xl"></i>
              <span class="badge badge-warning navbar-badge text-white font-bold	" id="incidenciasPendientes" style="position: relative;right: 5px;top: 5px;font-size: 0.95rem;">0</span>
            </button>
          </div>

          <div id="notificationsDiv" class="z-50 hidden origin-top-right absolute right-0 mt-2 w-64
              rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5
              focus:outline-none overflow-y-auto h-40">
              <p id="nohaypendientes"></p>
              <p class="ml-2 text-gray-600 text-sm font-bold">Solicitudes pendientes</p>
              <table class="w-full sm:bg-white rounded-lg overflow-hidden sm:shadow-lg my-2" id="tablaIncidenciasPendientes">
                    <thead class="text-white">
                      <tr class="bg-gray-400 rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                              <th class="p-1 text-left">Nº</th>
                              <th class="p-1 text-left">Fecha</th>
                              <th class="p-1 text-left"></th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
              </table>
          </div>
        </div>    

        <?php
        }
        ?>

        <div class="relative">
          <div>
            <button type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none
                focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="profileBtn">
              <span class="sr-only">Abrir menú usuario</span>
              <?php 
                
                  echo'
                  <a class="rounded-full h-10 w-10 flex items-center justify-center  bg-violeta-oscuro text-white flex-1"><i class="fas fa-user text-xl"></i></a>
                  ';                
              ?>
            </button>
          </div>

          <div id="profileDiv" class="z-50 hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
            <!-- Active: "bg-gray-100", Not Active: "" -->
            <a href="#" class="block px-4 py-2 text-sm text-gray-700">
              <i class="fas fa-user mr-2"></i><?php echo $_SESSION['usuario'];?>
              <input type="hidden" id="nombreUsuario" value="<?php echo $_SESSION['usuario'];?>">
            </a>          
            <a href="<?php echo RUTA_URL; ?>/Login/vaciar" class="block px-4 py-2 text-sm text-gray-700">
              <i class="fas fa-sign-out-alt mr-2"></i>Salir
            </a>
          </div>

        </div>
        
      </div>
    </div>
  </div>
</nav>