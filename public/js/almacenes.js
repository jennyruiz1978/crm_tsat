$(document).ready(function() {
  
    if(window.location.pathname.includes('/Proveedores')){

        //falta incluir validacion expresiones regulares

        urlCompleta = $('#ruta').val();

        var filaTablaAlmacen = '';     
        var almacenEdicion = '';


        $(document).on('click', '.editarAlmacen', function (e) {
            e.preventDefault();

            almacenEdicion = $(this).closest('tr');
            id = parseInt(almacenEdicion.find('td:eq(0)').text());
            $('#idAlmacen').val(id);                     
            
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Proveedores/obtenerDetalleAlmacen',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){                     
                
                if (data['respuesta'] == 1) {
                    $('#tituloModalSucursal').text('Editar almacén Nº ' + id);
                    $('#bodyModalEditarSucursal').html('');                 
                    $('#bodyModalEditarSucursal').html(data['bodyModal']);                   
                    toggleModalSucursal('editar-sucursal');                   
                }else{                 
                    $("#mensajeValidacion").text("No se puede visualizar el almacén").show().fadeOut(3000);  
                }

            });         
             

        });

        
        $(document).on('click', '.cerrarModalEditSucursal', function (e) {
            toggleModalSucursal('editar-sucursal')
            $('#tituloModalSucursal').text('');
            $('#bodyModalEditarSucursal').html('');      
            $('#idAlmacen').val('');
        });    
        
        $(document).on('click', '.cerrarModalDelSucursal', function (e) {
            toggleModalSucursal('delete-sucursal')           
            $('#idDelAlmacen').html('');   
            $('#datoSucursal').html('');         
        });    

        $(document).on('click', '.eliminarAlmacen', function (e) {
            e.preventDefault();

            filaTablaAlmacen = $(this).closest('tr');
            id = parseInt(filaTablaAlmacen.find('td:eq(0)').text());
            nombre = filaTablaAlmacen.find('td:eq(1)').text();            

            if (id >0) {                                
                $('#idDelAlmacen').val(id);
                $('#datoSucursal').text(id + " - " + nombre);
                toggleModalSucursal('delete-sucursal');
            }
        });
        
    
        
        $(document).on('click', '#addSucursal', function (e) {
            e.preventDefault();             
            
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Proveedores/nuevoAlmacen',
                dataType: "json"                
            }).done(function(data){   
                
                    $('#tituloModalSucursal').text('Crear almacén');
                    $('#bodyModalEditarSucursal').html('');                 
                    $('#bodyModalEditarSucursal').html(data['form']);                   
                    toggleModalSucursal('editar-sucursal');                
            });             
        });

            
        $(document).on('click', '#crearAlmacenNuevo', function (e) {

            if (document.getElementById("formAltaSucursales").checkValidity()) {
                e.preventDefault();
                        
                let idProveedor = $('#idProvEdit').val();
                form = $('#formAltaSucursales').serializeArray();
                //envia le formulario por ajax,
                if (idProveedor && idProveedor >0) {
                                        
                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Proveedores/crearAlmacenNuevo',
                        dataType: "json",
                        data: { 'form': form, 'idProveedor' : idProveedor },
                    }).done(function(data){   

                        if (data['respuesta'] == 1) {                            
                            toggleModalSucursal('editar-sucursal');                               
                            $('#bodyModalEditarSucursal').html('');
                            $('#tablaSucursalesBody').prepend(data['fila']);
                            $("#mensajeValidacion").text("Se ha creado el almacén corréctamente.").show().fadeOut(3000);  

                        }else{                        
                            $("#mensajeValidacion").text("Ha ocurrido un error y no se ha podido crear el nuevo proveedor.").show().fadeOut(3000);    
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

        $(document).on('click', '#actualizarAlmacen', function (e) {        

            e.preventDefault();

            if (document.getElementById("formAltaSucursales").checkValidity()) {               
                                
                idAlmacen = $('#idAlmacen').val();                
                let idProveedor = $('#idProvEdit').val();
                
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
                    url: urlCompleta + '/Proveedores/actualizarAlmacen',
                    dataType: "json",
                    data: { 'form': form, 'idAlmacen':idAlmacen, 'idProveedor' : idProveedor  },
                }).done(function(data){   

                    if (data == 1) {                        
                        //actualizo los campos de la fila de la tabla
                        $('#tablaSucursalesBody').find(almacenEdicion).find('td:eq(1)').html(nombre);
                        $('#tablaSucursalesBody').find(almacenEdicion).find('td:eq(2)').html(dirCompleta); 
                        Swal.fire({
                            title: 'Actualización de almacén',
                            text: 'Se han actualizado los datos corréctamente.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }); 
                        $("#mensajeValidacion").text("Se han actualizado los datos corréctamente.").show().fadeOut(3000);  
                    }else{                        
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error y no se ha podido crear el nuevo almacén.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                        $("#mensajeValidacion").text("Ha ocurrido un error y no se ha podido crear el nuevo almacén.").show().fadeOut(3000);    
                    }
                });               
            }



        });

        $(document).on('click', '#enviarDelSucursal', function (e) {
            e.preventDefault();

            id = $('#idDelAlmacen').val();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Proveedores/eliminarAlmacen',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){                     

                    console.log(data);
                    if (data == 1) {
                        
                        filaTablaAlmacen.remove();
                        toggleModalSucursal('delete-sucursal');
                        Swal.fire({
                            title: 'Eliminar almacén',
                            text: 'Se ha eliminado el almacén',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });  
                        $("#mensajeValidacion").text("Se ha eliminado el almacén").show().fadeOut(3000);  
                        
                    }else{      
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error y no se ha podido eliminar el almacén.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });                 
                        $("#mensajeValidacion").text("No se puede eliminar el almacén").show().fadeOut(3000);  

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

