<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>


<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-2">
       
    <div class="container mx-auto px-1 xl:px-2">
            <span id="mensajeValidacion" class="text-xl font-bold text-pink-600 mx-4"></span>
            
            <?php
             

                if ($_SESSION['nombrerol'] == 'admin' || $datos['tecnicoConPermiso'] == 1) {                 
            ?>
            <div class="py-1">                    

                    <div class="grid grid-cols-1 bg-white rounded-lg shadow-xl w-100 mb-5" id="contenedorNuevoProveedor" style="display: none;">
                    </div>

                    <div class="inline-flex">
                        <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Proveedores</h2>
                        <a href="#" id="nuevoProveedor" class="rounded-full h-10 w-10 flex items-center justify-center bg-violeta-oscuro text-white flex-1"><i class="fas fa-user-plus"></i></a>
                    </div>               

                    

                    <?php //MONTAJE DEL BUSCADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                    <div class="my-2 flex sm:flex-row flex-col">
                        <div class="flex flex-row mb-1 sm:mb-0">
                            <div class="relative flex" id="buscador">                            
                            </div>                            
                        </div>                        
                    </div>

                    <?php //MONTAJE DE LA TABLA QUE VIENE DE LA CLASE JS TABLACLASS ?>
                    <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                        <div class="inline-block min-w-full shadow rounded-lg overflow-hidden" id="contenedorDestino">
                            <div id="destinoproveedoresajax"></div>                            
                            <script type="module">
                            import arrancar from "<?php print RUTA_URL;  ?>/public/js/tablaClass/tablaClass.js"
                            arrancar("tablaproveedores","Proveedores/crearTabla", "destinoproveedoresajax", "nombre", "ASC", 0, "buscador","Proveedores/totalRegistros", [10, 20, 30],"min-w-full leading-normal","paginador",["editar","eliminar"],"","");
                        
                            </script>

                        </div>
                    </div>        

                    <?php //MONTAJE DEL PAGINADOR QUE VIENE DE LA CLASE JS TABLACLASS ?>
                    <div id="paginador"></div>
                    <!-- ========= End  ajax puro =================== -->
                                                
            </div>
            <?php
                }
            ?>
        </div>    



    </main>
</div>

</div>

</main>

<!--Esta etiqueta Main es el fin del sidebar -->
<?php require_once(RUTA_APP . '/views/proveedores/eliminarProveedor.php'); ?>
<?php require_once(RUTA_APP . '/views/proveedores/eliminarAlmacen.php'); ?>
<?php require_once(RUTA_APP . '/views/proveedores/editarAlmacen.php'); ?>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>
