<?php require_once(RUTA_APP . '/views/includes/pie.php'); ?>


<!-- Optional JavaScript -->


 
<script src="<?php echo RUTA_URL; ?>/public/vendor/fortawesome/font-awesome/js/all.min.js"></script>
<script src="<?php echo RUTA_URL; ?>/public/vendor/components/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>



<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    
<script>

$(document).ready(function () {  
   

    $('.select2').select2({
      
      placeholder: "Seleccionar",
      allowClear: true,
      width: '100%',
    });


  tail.select('.todos',{// clase para cualquier select multiselect
        search: true,
        locale: "es",
        multiSelectAll: true,
        searchMinLength: 0,
        multiContainer: true,
  });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- tail.select -->
<script src='<?php echo RUTA_URL ?>/public/librerias/tailSelect/js/tail.select-full.min.js'></script>
<script src='<?php echo RUTA_URL ?>/public/librerias/tailSelect/langs/tail.select-es.js'></script>

<!-- Load d3.js and c3.js -->
<script src="<?php echo RUTA_URL ?>/public/librerias/d3/d3.js" charset="utf-8"></script>
<script src="<?php echo RUTA_URL ?>/public/librerias/c3-0.7.20/c3.js"></script>



<!-- jvascript del navbar y sidebar -->

<script src="<?php echo RUTA_URL; ?>/public/js/navbarSidebar/navbarSidebar.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/navbarSidebar/submenu.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/clientes.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/sucursales.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/equipos.js?v=<?php echo(rand()); ?>"></script>

<script type="module" src="<?php echo RUTA_URL; ?>/public/js/incidencias.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/tecnicos.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/bolsaHoras.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/login.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/usuarios.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/modalidades.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/controlTiempos.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/navbarAcciones.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/dashboard.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/costesTecnicos.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/facturarpresupuestar.js?v=<?php echo(rand()); ?>"></script>





<script src="<?php echo RUTA_URL; ?>/public/js/proveedores.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/almacenes.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/productos.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/tiposIva.js?v=<?php echo(rand()); ?>"></script>

<script type="module" src="<?php echo RUTA_URL; ?>/public/js/facturasCliente.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/cuentasBancarias.js?v=<?php echo(rand()); ?>"></script>

<script src="<?php echo RUTA_URL; ?>/public/js/formasdepago.js?v=<?php echo(rand()); ?>"></script>

<script type="module" src="<?php echo RUTA_URL; ?>/public/js/detalleFacturacion.js?v=<?php echo(rand()); ?>"></script>

<script type="module" src="<?php echo RUTA_URL; ?>/public/js/editIncidencia.js?v=<?php echo(rand()); ?>"></script>


</body>

</html>