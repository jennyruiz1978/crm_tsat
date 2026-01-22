$(document).ready(function() {
  
    if(window.location.pathname.includes('/Clientes')){

        urlCompleta = $('#ruta').val();

        
        $(document).on('click', '#addNuevoMes', function (e) {
            e.preventDefault();
            
            idCliente = $('#idCliEdit').val();
            toggleModalBolsaHoras('crear-BolsaHoras');

        });

        $(document).on('click', '.cerrarCrearBolsaHoras', function (e) {
            e.preventDefault();
            toggleModalBolsaHoras('crear-BolsaHoras');            
            $('#contratadoHorasCrear').val('');
            $('#mesBolsaHorasCrear').val('');
            $('#anioBolsaHorasCrear').val('');                      
        });

        $(document).on('click', '#crearBolsaHoras', function (e) {
            
        
            modalidad = 'horas';
            idCliente = $('#idCliEdit').val();
            contratado = $('#contratadoHorasCrear').val();
            contratadoPrecio = $('#contratadoEurosCrear').val();

            var mesInicio = $('#mesInicio').attr('option', 'selected').val();
            var anioInicio = $('#anioInicio').attr('option', 'selected').val();
            var mesFin = $('#mesFin').attr('option', 'selected').val();
            var anioFin = $('#anioFin').attr('option', 'selected').val();            
            
                               
            if (idCliente =='' || idCliente == null || idCliente ==0) {
                $("#msgValidaFormBolsa").text("El equipo no existe").show().fadeOut(3000);  
                e.preventDefault();
            }else if(contratado =='' || contratado ==0){
                $("#msgValidaFormBolsa").text("Debe ingresar las horas contratadas.").show().fadeOut(3000); 
                e.preventDefault();
            }else if(contratadoPrecio =='' || contratadoPrecio ==0){
                $("#msgValidaFormBolsa").text("Debe ingresar el precio.").show().fadeOut(3000); 
                e.preventDefault();
            }else if (parseInt(anioFin) ==parseInt(anioInicio) && parseInt(mesInicio) > parseInt(mesFin)){
                $("#msgValidaFormBolsa").text("El mes inicial no puede ser mayor que el final").show().fadeOut(3000);
                e.preventDefault();
            }else if(mesInicio ==null || mesInicio=='' || mesInicio==0){
                $("#msgValidaFormBolsa").text("Complete mes inicio").show().fadeOut(3000);
                e.preventDefault();
            }else if(mesFin ==null || mesFin=='' || mesFin==0){
                $("#msgValidaFormBolsa").text("Complete mes fin").show().fadeOut(3000);
                e.preventDefault();                        
            }else if(parseInt(anioInicio) > parseInt(anioFin)){
                $("#msgValidaFormBolsa").text("El año inicial no puede ser mayor que el final").show().fadeOut(3000);
                e.preventDefault();
            }else{
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/crearBolsaHoras',
                    dataType: "json",
                    data: {'modalidad':modalidad,'contratado':contratado,'mesInicio':mesInicio,'anioInicio':anioInicio, 'mesFin':mesFin,'anioFin':anioFin,'idCliente':idCliente, 'contratadoPrecio':contratadoPrecio}
                }).done(function(data){
                    if (data['respuesta'] == 1) {                        
                        toggleModalBolsaHoras('crear-BolsaHoras');
                        
                        $('#tab-bolsahoras').html('');                        
                        $('#tab-bolsahoras').html(data['tabla']);            
                        $('#contratadoHorasCrear').val('');
                        $('#mesBolsaHorasCrear').val('');
                        $('#anioBolsaHorasCrear').val('');  
                        $('#contratadoEurosCrear').val(''); 

                        Swal.fire({
                            title: 'Creación de Bolsa de horas',
                            text: 'Se ha creado la nueva bolsa de horas corréctamente',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });  
                        
                    }else{
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error y no se ha podido crear la bolsa de horas.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });                        
                    }                    
                });        
            }          



            // fin editand0 ======




            /*
            if (idCliente >0 && modalidad !='' && contratado >0 && contratado !='' && mes >0 && anio>0 && contratadoPrecio>0) {
                
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/crearBolsaHoras',
                    dataType: "json",
                    data: {'modalidad':modalidad,'contratado':contratado,'mes':mes,'anio':anio, 'idCliente':idCliente, 'contratadoPrecio':contratadoPrecio}
                }).done(function(data){
                    if (data['respuesta'] == 1) {                        
                        toggleModalBolsaHoras('crear-BolsaHoras');
                        
                        $('#tab-bolsahoras').html('');                        
                        $('#tab-bolsahoras').html(data['tabla']);            
                        $('#contratadoHorasCrear').val('');
                        $('#mesBolsaHorasCrear').val('');
                        $('#anioBolsaHorasCrear').val('');  
                        $('#contratadoEurosCrear').val(''); 

                        Swal.fire({
                            title: 'Creación de Bolsa de horas',
                            text: 'Se ha creado la nueva bolsa de horas corréctamente',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });  
                        
                    }else{
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error y no se ha podido crear la bolsa de horas.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });                        
                    }                    
                });

            }else{
                $("#msgValidarBolsaHoras").text("Debe completar todos los datos del formulario").show().fadeOut(2500);                
            }*/
        });                    

        $(document).on('click', '.modificarBolsa', function (e) {
            e.preventDefault();
            var modif = $(this).closest('tr');
            id = parseInt(modif.find('td:eq(0)').text());
            $('#idCliHorasModif').val(id);
                        
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/verEditorParaModificarBolsaHoras',
                dataType: "json",
                data: {'id':id}
            }).done(function(data){
                $('#bodyModalBolsaHoras').html('');                 
                $('#bodyModalBolsaHoras').html(data['html']);                
            });
            toggleModalBolsaHoras('modificar-BolsaHoras');

        });

        $(document).on('click', '.cerrarModificarBolsaHoras', function (e) {
            e.preventDefault();
            toggleModalBolsaHoras('modificar-BolsaHoras')            
        });
        
        $(document).on('click', '#modifBolsaHoras', function (e) {
            e.preventDefault();

            modalidad = 'horas';
            contratado = $('#contratadoHoras').val();
            contratadoEuros = $('#contratadoEuros').val();
            mes = $('#mesBolsaHoras').attr('option', 'selected').val();
            anio = $('#anioBolsaHoras').attr('option', 'selected').val();
            idBolsaMes = $('#idCliHorasModif').val();
            idCliente = $('#idCliEdit').val();
            
            if (idBolsaMes >0 && modalidad !='' && contratado >0 && contratado !='' && mes >0 && anio>0 && contratadoEuros >0 && contratadoEuros !='') {
                
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/actualizarBolsaHoras',
                    dataType: "json",
                    data: {'contratado':contratado,'mes':mes,'anio':anio, 'idBolsaMes':idBolsaMes, 'idCliente':idCliente, 'contratadoEuros': contratadoEuros}
                }).done(function(data){
                    if (data['respuesta'] == 1) {
                        toggleModalBolsaHoras('modificar-BolsaHoras');
                                 
                        $('#tab-bolsahoras').html('');                        
                        $('#tab-bolsahoras').html(data['tabla']);            

                        Swal.fire({
                            title: 'Actualización de Bolsa de horas',
                            text: 'Se han actualizado los cambios corréctamente.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });  
                        
                    }else{
                        Swal.fire({
                            title: 'Error',
                            text: 'No se han podido actualizar los cambios.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });                                                
                    }                    
                });

            }else{

                $("#msgValidarBolsaHoras").text("Debe completar todos los datos del formulario").show().fadeOut(2500);                
            }
        });
             
        

        function toggleModalBolsaHoras(modal_id) {            
            document.getElementById(modal_id).classList.toggle("hidden");
            document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
            document.getElementById(modal_id).classList.toggle("flex");
            document.getElementById(modal_id + "-backdrop").classList.toggle("flex");
        }

        var idBolsaDel;
        $(document).on('click', '#destinomodalidadhorasajax .eliminar',function (e) {
            e.preventDefault();

            idBolsaDel = $(this).closest('tr');
            id = parseInt(idBolsaDel.find('td:eq(0)').text());             
            $('#idBolsaHoras').val(id);         

            if (id >0) {                
                toggleModalBolsaHoras('eliminar-bolsa');      
            }

        });

        $(document).on('click', '#eliminarBolsa', function (e) {
            e.preventDefault();            
            idBolsaHoras = $('#idBolsaHoras').val();
                        
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/eliminarBolsaHoras',
                dataType: "json",
                data: {'idBolsaHoras':idBolsaHoras}
            }).done(function(data){
                if (data == 1) {
                    idBolsaDel.remove();
                    toggleModalBolsaHoras('eliminar-bolsa');
                    Swal.fire({
                        title: 'Eliminación de modalidad',
                        text: 'Se ha eliminado el registro corréctamente',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }); 
                }else{
                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error y no se ha podido eliminar el registro seleccionado.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }); 
                }
            });            
        });



        $(document).on('click', '.cerrarModalEliminarBolsaHoras', function (e) {
            e.preventDefault();
            toggleModalBolsaHoras('eliminar-bolsa')            
        });
        

    }

    



});