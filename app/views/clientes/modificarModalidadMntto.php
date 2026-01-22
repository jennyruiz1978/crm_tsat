<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="modificar-modalidadMntto">
  <div class="relative w-auto my-6 mx-auto max-w-3xl">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between p-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-sm lg:text-base font-semibold mr-2">
          Configurar mantenimiento equipo/modalidad
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarModificarModalidadMntto" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>
      <!--body-->
      <div class="relative p-2 flex-auto">      

        <span id="msgValidarModalidadMntto" class="font-bold font-bold text-pink-600"></span>         
        <input type="hidden" id="idEquipoModifMntto">
        
        <form class="flex flex-col space-y-5" id="bodyModalVerModalidadMntto">
                  
        </form>


      </div>
      
      <!--footer-->
      <?php
        if($_SESSION['nombrerol']=='admin'){
      ?>
      <div class="flex items-center justify-end p-2 border-t border-solid border-blueGray-200 rounded-b">
        <button class="w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl text-sm xl:text-base text-white px-4 py-1 mr-2 cerrarModificarModalidadMntto">Cerrar</button>
        <!--<button id="modifModalidadMntto" class="w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl text-sm xl:text-base text-white px-4 py-1">Guardar todo</button>-->
      <?php 
        }
      ?>

      </div>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="modificar-modalidadMntto-backdrop"></div>