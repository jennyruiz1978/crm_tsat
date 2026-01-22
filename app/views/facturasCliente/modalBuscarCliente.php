<div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="buscar-cliente">
  <div class="relative w-full sm:w-full md:w-1/2 lg:w-1/2 3xl:w-1/2 my-6 mx-auto">
    <!--content-->
    <div class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
      <!--header-->
      <div class="flex items-start justify-between px-5 py-2 border-b border-solid border-blueGray-200 rounded-t">
        <h3 class="text-sm xl:text-base font-semibold mr-2">
          Buscar cliente
        </h3>
        <button class="ml-auto bg-transparent border-0 text-black opacity-50 float-right text-3xl leading-none font-semibold outline-none focus:outline-none cerrarBuscadorClientes" >
          <span class="bg-transparent text-black opacity-1 h-6 w-6 text-2xl block outline-none focus:outline-none">
            ×
          </span>
        </button>
      </div>      

      <form id="formBuscarClientes">
        
        <!--body-->
        <div class="relative p-6 flex-auto">
                    
            <div class="grid grid-cols-1 gap-2 md:gap-4 m-2">

              <div class="grid grid-cols-1 lg:col-span-1">
                  <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Escribir un cliente</label>                  

                  <div class="flex gap-1 items-center justify-start" >
                    <select class="py-1 px-2 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"  name="selectTarea" id="selectTarea"></select>
                  </div>

              </div>

            </div>          
                       
        </div>

      
      </form>

    </div>
  </div>
</div>
<div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="buscar-cliente-backdrop"></div>