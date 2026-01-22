<?php include_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>

<body class="h-screen overflow-hidden flex items-center justify-center" style="background: #edf2f7;">
    <div class="flex items-center min-h-screen p-4 bg-gray-100 lg:justify-center">
      <div
        class="flex flex-col overflow-hidden bg-white rounded-md shadow-lg max md:flex-row md:flex-1 lg:max-w-screen-md"
      >
        <div
          class="p-4 py-6 text-white bg-gray-50 md:w-80 md:flex-shrink-0 md:flex md:flex-col md:items-center md:justify-evenly"
        >
          <div class="my-3 text-4xl font-bold tracking-wider text-center">
            <img src="<?php echo RUTA_URL; ?>/public/img/logo_telesat.jpg">
          </div>
          <!--<p class="xs:mt-0 mt-2 font-normal text-center text-gray-600">
            <?php //echo SLOGAN ;?>
          </p>-->
        </div>
        <div class="p-5 bg-white md:flex-1">
          <h3 class="my-4 text-2xl font-semibold text-gray-700">Login</h3>

          <form class="flex flex-col space-y-5" method="POST" action="<?php echo RUTA_URL; ?>/Login/acceder" id="formLogin">
            <span id="mensajeLogin" class="font-bold font-bold text-blue-600"></span>   
            <input type="hidden" id="rutaIni" value="<?php echo RUTA_URL;?>">
            
            <div class="flex flex-col space-y-1">
              <label for="email" class="text-sm font-semibold text-gray-500">Correo electronico</label>
              <input 
                type="email"
                id="email"
                name="mail"
                autofocus
                class="inputLogin px-4 py-2 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-600"
              />
            </div>


            <div class="flex flex-col space-y-1">
                <div class="flex items-center justify-between">
                  <label for="password" class="text-sm font-semibold text-gray-500">Contrase&ntilde;a</label>              
                </div>               
                     
                <div class="relative w-full">

                    <div class="absolute inset-y-0 right-0 flex items-center px-2">
                      <input class="hidden js-pass-toggles" id="toggles" type="checkbox" />
                      <label class="bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 font-mono cursor-pointer js-pass-label" for="toggles"><i class='far fa-eye texto-violeta-oscuro text-base xl:text-xl'></i></label>

                    </div>
                  
                    <input 
                            type="password"
                            id="password"
                            name="pass"
                            class="appearance-none w-full js-pass inputLogin px-4 py-2 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-600" autocomplete="off" />
                  
                </div>
                
            </div>


            <div>
              <button
                type="submit"
                class="w-full px-4 py-2 text-lg font-semibold text-white transition-colors duration-300 bg-violeta-oscuro hover:bg-blue-700 rounded-md shadow focus:outline-none focus:ring-sky-600 focus:ring-4"
              >
                Acceder
              </button>
              
            </div>
            <a class="text-sm font-semibold text-gray-500" href="<?php echo RUTA_URL . "/Login/solicitarRecuperarContrasenia"; ?>">¿Olvidaste tu contraseña?</a>

          </form>

        </div>
      </div>
    </div>
    <?php include_once(RUTA_APP . '/views/includes/footer.php'); ?>



     <!--<input 
                        type="password"
                        id="password"
                        name="pass"
                        class="inputLogin px-4 py-2 transition duration-300 border border-gray-300 rounded focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-600"/>-->
