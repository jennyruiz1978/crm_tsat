
<a id='verDetalleFactura' class='w-auto bg-red-400 hover:bg-red-600 rounded-lg text-sm 2xl:text-base text-white px-2 py-2 mt-1 mr-3'><i class="fas fa-sort-down mr-2"></i> Ver detalles a facturar </a>

<br>
<br>

<div id="contenedorDetalleFactura" class="my-4"  style="display: none;">
    




    <div class="grid grid-cols-1 gap-2 md:gap-4 m-2" >
                                          
        <div class="flex flex-wrap gap-1 items-center justify-start pt-3" id="botones_factura_inc">
            <?php
                echo $datos['botonesFacturaIncidencia'];
            ?>
        </div>

        <div class="flex items-center justify-start pt-3 gap-2 text-red-500" id="container_numfactura">
            <?php
                if(isset($detalles->idfactura) && $detalles->idfactura > 0){
            ?>
            <span>Factura N°</span>
            <a id="numFactura" data-idfactura="<?php echo $detalles->idfactura;?>" class="cursor-pointer"><?php echo $datos['numfactura'];?></a>
            <?php
                }else{
                    '<span>No tiene factura asociada</span>';
                }
            ?>
        </div>

        <div class="row container_products">
            <?php require(RUTA_APP . '/views/incidencias/grilla_factura_incidencia.php'); ?>
        </div>  

    </div>





</div>
