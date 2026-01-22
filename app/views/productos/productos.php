<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">

        <main class="w-full flex-grow p-6">
            
            <div class="container mx-auto px-1 xl:px-2">
                
                <?php            

                    $color ='';
                    if(isset($_SESSION['message'])){
                        if( strpos( $_SESSION['message'], 'corréctamente' ) != false ){
                        $color = 'bg-green-700';
                        }else{
                        $color = 'bg-blue-700';
                        }
                ?>
                <div class="text-white px-6 py-4 border-0 rounded relative mb-4 <?php echo $color;?>">
                    <span class="text-xl inline-block mr-5 align-middle">
                    <i class="fas fa-bell"></i>
                    </span>
                    <span class="inline-block align-middle mr-8">
                    <b><?php echo $_SESSION['message'];?></b>
                    </span>
                    <button class="butonCerrarAlerta absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none">
                    <span>×</span>
                    </button>
                </div>
                <?php
                        unset($_SESSION['message']);
                    }
                ?>                

                <div class="py-4">

                        <div class="inline-flex">
                            <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Artículos</h2>
                        
                            <a href="<?php echo RUTA_URL;  ?>/Productos/altaProductos" class="rounded-full h-10 w-10 flex items-center justify-center bg-violeta-oscuro text-white flex-1"><i class="fas fa-user-plus"></i></a>
                        </div>                                                     

                        
                        <div class="my-2 flex sm:flex-row flex-col">
                            <div class="flex flex-row mb-1 sm:mb-0">
                                <div class="relative flex" id="buscador">                            
                                </div>                            
                            </div>                        
                        </div>
                        
                        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                <div id="destinoproductosajax"></div>
                            </div>
                        </div>        
                        
                        <div id="paginador"></div>                   
                    
                                    
                </div>

            </div>    

                <script  type="module">

                    import arrancar from "<?php print RUTA_URL;  ?>/public/js/tablaClass/tablaClass.js" 
                    arrancar("tablaproductos","Productos/crearTablaProductos", "destinoproductosajax", "pro.numero", "ASC", 0, "buscador","Productos/totalRegistrosProductos", [10, 20, 30],"min-w-full leading-normal","paginador",["verEditar","eliminar"],"<?php echo RUTA_URL.'/Productos/actualizarProducto';?>","","");

                </script>

            <?php require_once(RUTA_APP . '/views/productos/eliminarProducto.php'); ?>
            
        </main>
</div>

</div>

</main>


<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>

