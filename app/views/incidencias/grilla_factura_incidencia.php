<div id="productos" class="overflow-x-auto" >
    <input type="hidden" id="checkBoxesEnviar" name="checkBoxesEnviar" value="" >
    <input type="hidden" id="idFacturaEnviar" name="idFacturaEnviar" value="">
    <table class='table min-w-full leading-normal' id='tablaGrilla'>
        <thead>
            <tr class="thead-light">                    
                <th style="display:none;">Lin</th>
                <th style="display:cell;" class="text-left description_title_grilla">Artículo / Descripción</th>
                <!--<th class="text-left">Descripció</th>-->
                <th>Cant.</th>
                <th>Unidad</th>
                <th>Precio</th>  
                <th>%Dscto.</th>                
                <th>Total</th>
                <th>%Iva</th>
                <th>Facturar</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody id="tablaGrillaBody">

        <?php     
            if(isset($datos) && isset($datos['htmlPrefacturaDetalle'])){
                print($datos['htmlPrefacturaDetalle']);
            }               
        ?>
                            

        </tbody>
    </table>
</div>