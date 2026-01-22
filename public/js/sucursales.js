$(document).ready(function() {
  
    if(window.location.pathname.includes('/Clientes')){

        //falta incluir validacion expresiones regulares

        urlCompleta = $('#ruta').val();

        var filaTablaSucursal = '';     
        var sucursalEdicion = '';


        $(document).on('click', '.editarSucursal', function (e) {
            e.preventDefault();

            sucursalEdicion = $(this).closest('tr');
            let id = parseInt(sucursalEdicion.find('td:eq(0)').text());            
            
            let correlativo = obtenerNumCorrelativo(sucursalEdicion.find('td:eq(0)').text());            

            $('#idSucursal').val(id);                     
            
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/obtenerDetalleSucursal',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){                     
                
                if (data['respuesta'] == 1) {
                    $('#tituloModalSucursal').text('Editar sucursal Nº ' + correlativo);
                    $('#bodyModalEditarSucursal').html('');                 
                    $('#bodyModalEditarSucursal').html(data['bodyModal']);                   
                    toggleModalSucursal('editar-sucursal');                   
                }else{                 
                    $("#mensajeValidacion").text("No se puede visualizar la sucursal").show().fadeOut(3000);  
                }

            });                      

        });

        function obtenerNumCorrelativo(texto)
        {            
            var partes = texto.split('-');

            var segundoElemento = partes.length > 1 ? partes[1].trim() : partes[0];

            return segundoElemento;

        }

        
        $(document).on('click', '.cerrarModalEditSucursal', function (e) {
            toggleModalSucursal('editar-sucursal')
            $('#tituloModalSucursal').text('');
            $('#bodyModalEditarSucursal').html('');      
            $('#idSucursal').val('');
        });    
        
        $(document).on('click', '.cerrarModalDelSucursal', function (e) {
            toggleModalSucursal('delete-sucursal')           
            $('#idDelSucursal').html('');   
            $('#datoSucursal').html('');         
        });    

        $(document).on('click', '.eliminarSucursal', function (e) {
            e.preventDefault();

            filaTablaSucursal = $(this).closest('tr');
            id = parseInt(filaTablaSucursal.find('td:eq(0)').text());
            nombre = filaTablaSucursal.find('td:eq(1)').text();            

            if (id >0) {                                
                $('#idDelSucursal').val(id);
                $('#datoSucursal').text(id + " - " + nombre);
                toggleModalSucursal('delete-sucursal');
            }
        });
        
    
        
        $(document).on('click', '#addSucursal', function (e) {
            e.preventDefault();             
            
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/nuevaSucursal',
                dataType: "json"                
            }).done(function(data){   
                
                    $('#tituloModalSucursal').text('Crear sucursal');
                    $('#bodyModalEditarSucursal').html('');                 
                    $('#bodyModalEditarSucursal').html(data['form']);                   
                    toggleModalSucursal('editar-sucursal');                
            });             
        });

            
        $(document).on('click', '#crearSucursalNueva', function (e) {

            if (document.getElementById("formAltaSucursales").checkValidity()) {
                e.preventDefault();
                        
                idCliente = $('#idCliEdit').val();
                form = $('#formAltaSucursales').serializeArray();
                //envia le formulario por ajax,
                if (idCliente && idCliente >0) {
                                        
                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Clientes/crearSucursalNueva',
                        dataType: "json",
                        data: { 'form': form, 'idCliente' : idCliente },
                    }).done(function(data){   

                        if (data['respuesta'] == 1) {                            
                            toggleModalSucursal('editar-sucursal');                               
                            $('#bodyModalEditarSucursal').html('');
                            //$('#tablaSucursalesBody').prepend(data['fila']);                        
                            $('#tab-settings').html('');
                            $('#tab-settings').html(data.tablarecargada);                            
                            $("#mensajeValidacion").text("Se ha creado la sucursal corréctamente.").show().fadeOut(3000);  

                        }else{                        
                            $("#mensajeValidacion").text("Ha ocurrido un error y no se ha podido crear el nuevo cliente.").show().fadeOut(3000);    
                        }
                    });    
                }           
            }

        });

        $(document).on('click', '#addContactoSucursal', function (e) {
            e.preventDefault();

            var fila = `<tr>                        
                        <td width="40%"><input class="border-2 border-pink-200 rounded-lg border-opacity-50" name="nombreContactoSuc" style="width: 100%;"></td>
                        <td width="20%"><input class="border-2 border-pink-200 rounded-lg border-opacity-50" name="mailContactoSuc" style="width: 100%;"></td>
                        <td width="20%"><input type="text" regexp="[0-9]{0,9}" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="telefonoContactoSuc" style="width: 100%;"></td>
                        <td width="10%"><a class="eliminarContactoSuc"><i class="fas fa-user-minus" style="color:red;"></i></a></td>             
                        </tr>`;
            $('#tablaContactosSucursal').append(fila);
            
        });

        $(document).on('click', '.eliminarContactoSuc', function (e) {                                    
            e.preventDefault();
            
            filaContacto = $(this).closest('tr');                
            filaContacto.remove();
        });

        $(document).on('click', '#actualizarSucursal', function (e) {        

            e.preventDefault();

            if (document.getElementById("formAltaSucursales").checkValidity()) {               
                                
                idSucursal = $('#idSucursal').val();                
                idCliente = $('#idCliEdit').val();
                
                nombre = $('#nombreSucursal').val();
                direccion = $('#direccionSucursal').val();
                poblacion = $('#poblacionSucursal').val();
                provincia = $('#provinciaSucursal').val();
                codpostal = $('#codigopostalSucursal').val();

                dirCompleta = direccion +' - '+ poblacion + ' - '+ provincia +' - '+ codpostal;                

                form = $('#formAltaSucursales').serializeArray();
                //envia le formulario por ajax,
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/actualizarSucursal',
                    dataType: "json",
                    data: { 'form': form, 'idSucursal':idSucursal, 'idCliente' : idCliente  },
                }).done(function(data){   

                    if (data['error']==false) {                        
                        //actualizo los campos de la fila de la tabla
                        $('#tablaSucursalesBody').find(sucursalEdicion).find('td:eq(1)').html(nombre);
                        $('#tablaSucursalesBody').find(sucursalEdicion).find('td:eq(2)').html(dirCompleta); 
                        Swal.fire({
                            title: 'Actualización de sucursal',
                            text: 'Se han actualizado los datos corréctamente.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }); 
                        $("#mensajeValidacion").text("Se han actualizado los datos corréctamente.").show().fadeOut(3000);  
                    }else{                        
                        Swal.fire({
                            title: 'Error',
                            text: data['mensaje'],
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                        $("#mensajeValidacion").text(data['mensaje']).show().fadeOut(3000);    
                    }
                });               
            }



        });

        $(document).on('click', '#enviarDelSucursal', function (e) {
            e.preventDefault();

            id = $('#idDelSucursal').val();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/eliminarSucursal',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){                     
                                    
                    if (data['mensaje'] == false) {
                        
                        filaTablaSucursal.remove();
                        toggleModalSucursal('delete-sucursal');
                        Swal.fire({
                            title: 'Eliminar sucursal',
                            text: 'Se ha eliminado la sucursal',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });  
                        $("#mensajeValidacion").text("Se ha eliminado la sucursal").show().fadeOut(3000);  
                        
                    }else{      
                        Swal.fire({
                            title: 'Error',
                            text: data['mensaje'],
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });                 
                        $("#mensajeValidacion").text(data['mensaje']).show().fadeOut(3000);  

                    }
            });  

            
        })          

        function toggleModalSucursal(modal_id) {
            
            document.getElementById(modal_id).classList.toggle("hidden");
            document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
            document.getElementById(modal_id).classList.toggle("flex");
            document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

        }        
   
    

    }

});