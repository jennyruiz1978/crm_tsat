$(document).ready(function() {
  
    if(window.location.pathname.includes('/Clientes')){

        urlCompleta = $('#ruta').val();

        $(document).on('change', '#sucursalSelect', function () {
            
            var idSucursal = $(this).attr('option', 'selected').val();         
            if (idSucursal > 0) {
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/verEquiposPorSucursal',
                    dataType: "json",
                    data: { 'idSucursal':idSucursal },
                }).done(function(data){   
                    $('#tablaEquiposPorSucursal').remove();
                    if (data['respuesta'] == 1) {                         
                        
                    }else{                 
                         $('#mensajeValidacionEquipo').text("No existen equipos para esta sucursal").show().fadeOut(2500);  
                    }
                    $('#tab-options').append(data['tabla']); 
                });   
            }
        });

        var equipoEdicion = '';

        
        $(document).on('click', '#addEquipo', function (e) {
            e.preventDefault();            
            
            var idsucursal = $('#sucursalSelect').attr('option','selected').val();
            var nombresucursal = $('#sucursalSelect option:selected').html(); 
            var idCliente = $('#idCliEdit').val();  

            if (idsucursal && idsucursal>0) {

                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/nuevoEquipo',
                    dataType: "json",
                    data: {'idsucursal':idsucursal, 'nombresucursal': nombresucursal, 'idCliente':idCliente}
                }).done(function(data){      
                    //data = JSON.parse(data);                               
                    $('#tituloModalEquipo').text('Crear equipo para la sucursal '+ nombresucursal);
                    $('#bodyModalEditarEquipo').html('');                 
                    $('#bodyModalEditarEquipo').html(data['form']);                   
                    toggleModalEquipo('editar-equipo');

                     
                });                                 
            }else{
                $('#mensajeValidacionEquipo').text("Debe seleccionar una sucursal").show().fadeOut(5000);  
            }                                  
        });

                
        $(document).on('click', '.cerrarModalEditEquipo', function (e) {
            e.preventDefault();
            toggleModalEquipo('editar-equipo')
            $('#tituloModalEquipo').text('');
            $('#bodyModalEditarEquipo').html('');            
        });

        $(document).on('click', '#asignarUsuarioEquipo', function (e) {
            e.preventDefault();            

            var fila = `<tr class="text-sm">                
                            <td width="20%"><input type="hidden" name="idUsuarioNew" value="0"><input class="border-2 border-pink-200 rounded-lg border-opacity-50" name="nombreUsuarioNew" style="width: 100%;"></td>
                            <td width="20%"><input class="border-2 border-pink-200 rounded-lg border-opacity-50" name="apellidosUsuarioNew" style="width: 100%;"></td>
                            <td width="40%"><input class="border-2 border-pink-200 rounded-lg border-opacity-50" name="emailUsuarioNew" style="width: 100%;"></td>
                            <td width="10%">
                                <select class="border-2 border-pink-200 rounded-lg border-opacity-50" name="clienteTipoNew" style="width: 100%;">
                                    <option value="usuario">Usuario</option>
                                    <option value="supervisor">Supervisor</option>
                                </select>
                            </td>
                            <td width="5%"><a class="eliminarUsuario"><i class="fas fa-user-minus" style="color:red;"></i></a></td>
                        </tr>`;
            $('#tablaUsuariosEquipos').append(fila);
            
        });

        $(document).on('change', '#buscarUsuario', function (e) {
            e.preventDefault();            
            var idUsuario = $(this).val();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/nuevaFilaUsuarioExistente',
                dataType: "json",
                data: {'idUsuario':idUsuario }
            }).done(function(data){      
                if (data['respuesta'] == 1) {
                    $('#tablaUsuariosEquipos').append(data['fila']);
                }
            });              
        });
  
        $(document).on('click', '.eliminarUsuario', function (e) {
            e.preventDefault();
                        
            filaUsuario = $(this).closest('tr');                
            filaUsuario.remove();
            
        });

        $(document).on('click', '.editarEquipo', function (e) {        
            e.preventDefault();

            equipoEdicion = $(this).closest('tr');
            id = parseInt(equipoEdicion.find('td:eq(0)').text());
            $('#idEquipo').val(id);                      
            obtenerDetalleEquipo(id);
           
             
        });  
        
        function obtenerDetalleEquipo(id) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/obtenerDetalleEquipo',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){

                if (data['respuesta'] == 1) {
                    $('#tituloModalEquipo').text('Editar Equipo Nº ' + id);
                    $('#bodyModalEditarEquipo').html('');                 
                    $('#bodyModalEditarEquipo').html(data['bodyModal']);                   
                    toggleModalEquipo('editar-equipo');                    
                }else{                 
                    $("#mensajeValidacion").text("No se puede visualizar el equipo.").show().fadeOut(5000);  
                }

            }); 
        }

        $(document).on('click','#actualizarEquipo', function (e) {
            e.preventDefault();
            idEquipo = $('#idEquipo').val();
            nombreEquipo = $('#nombreEquipo').val();

            //if (document.getElementById("formAltaEquipos").checkValidity()) {               
            if (idEquipo !='' && idEquipo >0 && nombreEquipo !='') {

                serie = $('#serie').val();
                marca = $('#marca').val();
                ip = $('#ip').val();
            
                form = $('#formAltaEquipos').serializeArray();

                var archivos = $('#ficheroCrearEquipo')[0];                                

                mostrarCargando();
                
                //envia le formulario por ajax,
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/actualizarEquipo',
                    dataType: "json",
                    data: { 'form': form, 'idEquipo':idEquipo, 'nombreEquipo':nombreEquipo  },
                }).done(function(data){   

                    if (data['error']==false) {                        

                        agregarImagenEquipoEditar(idEquipo, archivos); 

                        //actualizo los campos de la fila de la tabla
                        $('#tablaEquiposBody').find(equipoEdicion).find('td:eq(1)').html(nombreEquipo);
                        $('#tablaEquiposBody').find(equipoEdicion).find('td:eq(2)').html(serie); 
                        $('#tablaEquiposBody').find(equipoEdicion).find('td:eq(3)').html(marca);
                        $('#tablaEquiposBody').find(equipoEdicion).find('td:eq(4)').html(ip);                        

                    }else{         
                        ocultarCargando(); 
                        toggleModalEquipo('editar-equipo');      
                        Swal.fire({
                            title: 'Error',
                            text: data['mensaje'],
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                        
                    }
                });               
            }            
        });


        var filaTablaEquipo = '';
        $(document).on('click', '.eliminarEquipo', function (e) {
            e.preventDefault();

            filaTablaEquipo = $(this).closest('tr');
            id = parseInt(filaTablaEquipo.find('td:eq(0)').text());
            nombre = filaTablaEquipo.find('td:eq(1)').text();            

            if (id >0) {                                
                $('#idDelEquipo').val(id);
                $('#datoEquipo').text(id + " - " + nombre);
                toggleModalEquipo('delete-equipo');
            }
        });

        
        $(document).on('click', '#enviarDelEquipo', function (e) {
            e.preventDefault();

            id = $('#idDelEquipo').val();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/eliminarEquipo',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){                     
                    
                    if (data['error'] ==0) {
                        
                        filaTablaEquipo.remove();
                        toggleModalEquipo('delete-equipo');
                        Swal.fire({
                            title: 'Eliminar equipo',
                            text: 'Se ha eliminado el equipo',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });  
                        $("#mensajeValidacion").text("Se ha eliminado el equipo").show().fadeOut(3000); 
                    }else if(data['error'] == 2){ 
                        Swal.fire({
                            title: 'Alerta',
                            text: 'Existen solicitudes registradas para ese equipo. No se ha podido eliminar el equipo.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });                 
                        $("#mensajeValidacion").text("Existen solicitudes registradas para ese equipo. No se ha podido eliminar el equipo.").show().fadeOut(3000);  

                    }else{      
                        toggleModalEquipo('delete-equipo');
                        Swal.fire({
                            title: 'Error',
                            //text: 'Ha ocurrido un error y no se ha podido eliminar el equipo.',
                            text: data['mensaje'],
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });                 
                        $("#mensajeValidacion").text("No se puede eliminar el equipo").show().fadeOut(3000);  

                    }
            });  

            
        })

        $(document).on('click', '.cerrarModalDelEquipo', function (e) {
            e.preventDefault();
            toggleModalEquipo('delete-equipo')           
            $('#idDelEquipo').html('');   
            $('#datoEquipo').html('');         
        });    


        function toggleModalEquipo(modal_id) {
            
            document.getElementById(modal_id).classList.toggle("hidden");
            document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
            document.getElementById(modal_id).classList.toggle("flex");
            document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

        }

        $(document).on('click', '#addFileEquipo', function (e) {    
            e.preventDefault();
            if ($('#formularioSubirFicheroEquipo').is(':visible')) {
            $('#formularioSubirFicheroEquipo').slideUp(300);
            $('#addFileEquipo').html('<i class="far fa-image mx-2 text-xl"></i>Agregar fichero');
            } else {
            $('#formularioSubirFicheroEquipo').slideDown(300);
            $('#addFileEquipo').html('<i class="fas fa-times mx-2 text-base"></i>Cerrar');
            }
        });  
        
        $(document).on('click','#crearEquipoNuevo', function (e) {
            e.preventDefault();

            nombreEquipo = $('#nombreEquipo').val();
            idCliente = $('#idCliEdit').val();                  
            idSucursal = $('#sucursalSelect').attr('option', 'selected').val();

            if (nombreEquipo !='' && idCliente > 0 && idSucursal > 0) {                                        
                
                    form = $('#formAltaEquipos').serializeArray();
                               
                    var archivos = $('#ficheroCrearEquipo')[0];                                

                    mostrarCargando();

                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Clientes/crearEquipoNuevo',
                        dataType: "json",
                        data: { 'form': form, 'idCliente' : idCliente, 'idSucursal':idSucursal, 'nombreEquipo':nombreEquipo },
                    }).done(function(data){                           
                        
                        if (data['respuesta'] == 1) {
                            agregarFilaImagenEquipo(data['fila']);                            
                            agregarImagenEquipo(data['idequipo'], archivos);                            

                        }else{         
                            ocultarCargando();
                            Swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error y no se ha podido crear el nuevo equipo.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });                                           
                        }
                    });                    
                       
            }

        });

        
        function agregarFilaImagenEquipo(fila)
        {
            console.log('fila=>', fila);          
            //$('#tablaEquiposBody').prepend(fila);           
            var tablaEquiposBody = document.getElementById('tablaEquiposBody');
            tablaEquiposBody.insertAdjacentHTML('beforeend', fila);
        }

        function mostrarMensajeExito()
        {                    
            $('#bodyModalEditarEquipo').html('');                     
            Swal.fire({
                title: 'Creación de equipo',
                text: 'Se ha creado el equipo corréctamente',
                icon: 'success',
                confirmButtonText: 'Ok'
            }); 
        }

        function mostrarMensajeExitoEditar()
        {                 
            Swal.fire({
                title: 'Actualización de equipo',
                text: 'Se han actualizado los datos del equipo corréctamente',
                icon: 'success',
                confirmButtonText: 'Ok'
            }); 
        }

        function mostrarCargando() {
            $('#formAltaEquipos').html('<div class="loading grid justify-items-center"><img src="'+urlCompleta+'/public/img/load-spinner.gif" alt="loading" /><br/>Un momento, por favor...</div>');
        }
        function ocultarCargando() {
            $('.loading').remove();
        }
        
        
        function agregarImagenEquipo(idequipo, archivos) {
                        
            if (archivos && archivos.files && archivos.files.length > 0) {

                    var formData = new FormData();

                    $.each(archivos.files, function(index, archivo) {
                        formData.append('ficheroCrearEquipo[]', archivo);
                    });

                    formData.append('idequipo', idequipo);        
                    
                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Clientes/agregarImagenEquipoNuevo',
                        dataType: "json",
                        processData: false,
                        contentType: false,
                        data: formData
                    }).done(function(data){   
                                         
                        ocultarCargando(); 
                        toggleModalEquipo('editar-equipo');                                                                           
                        
                        if (data['error'] == true) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Se ha creado el equipo, '+mensaje,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            }); 
                        }else{
                            mostrarMensajeExito();
                        }
                                                                      
                    });
                
            } else {
                console.log("No hay archivos seleccionados.");
                ocultarCargando();
                toggleModalEquipo('editar-equipo');     
            }
        }

        function agregarImagenEquipoEditar(idequipo, archivos) {
                        
            if (archivos && archivos.files && archivos.files.length > 0) {

                    var formData = new FormData();

                    $.each(archivos.files, function(index, archivo) {
                        formData.append('ficheroCrearEquipo[]', archivo);
                    });

                    formData.append('idequipo', idequipo);        
                    
                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Clientes/agregarImagenEquipoNuevo',
                        dataType: "json",
                        processData: false,
                        contentType: false,
                        data: formData
                    }).done(function(data){   
                                         
                        ocultarCargando(); 
                        toggleModalEquipo('editar-equipo');
                        
                        if (data['error'] == true) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Se ha actualizado el equipo, '+mensaje,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            }); 
                        }else{                            
                            obtenerDetalleEquipo(idequipo);
                            mostrarMensajeExitoEditar();
                        }
                                                                      
                    });
                
            } else {
                console.log("No hay archivos seleccionados.");
                ocultarCargando();
                toggleModalEquipo('editar-equipo');     
                obtenerDetalleEquipo(idequipo);
            }
        }



        $(document).on('click', '.delete_file_equipo', function () {
                
            let idimagen = $(this)[0].dataset.idimagen;
            if(idimagen > 0){
    
                let bool = confirm('¿Está seguro(a) de eliminar el archivo?');
    
                if(bool){
                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Clientes/eliminarImagenEquipo',
                        dataType: "json",
                        data: { 'idimagen':idimagen},
                    }).done(function(data){                           
                        
                        if (data['error'] == false) {
                            
                            $('#imagen_equipo_'+idimagen).remove();
    
                        }else{                                 
                            Swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error y no se ha podido eliminar la imagen seleccionada.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });                                           
                        }
                    });  
                }
            }
            
    
        });

        $(document).on('click','.verImagen', function (e) {
            e.preventDefault();
            let idFichero = $(this).data('idfichero');                        
           
            $("#imagenEquipo").attr("src",urlCompleta+'/public/img/load-spinner.gif');
            toggleModalEquipo('ver-imagenequipo');    
    
            if (idFichero > 0) {
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/buscarImagenEquipo',
                    dataType: "json",
                    data: { 'idFichero':idFichero },
                }).done(function(data){
                  
                    if (data['imgsrc'] != '') {
                        
                        /*
                        $("#imagenEquipo").attr("src",data['imgsrc']);
                        $('#tituloModalImagenEquipo').text(data['nombre']);
                        */
                        const extensionesAbrirEnOtraPestana = ['doc', 'docx', 'xls', 'xlsx', 'pdf'];
                        const extension = data['extension'];
        
                        if (extensionesAbrirEnOtraPestana.includes(extension)) {
                            toggleModalEquipo('ver-imagenequipo'); 
                            window.open(data['imgsrc'], '_blank');
                        } else {
                            $("#imagenEquipo").attr("src", data['imgsrc']);
                            $('#tituloModalImagenEquipo').text(data['nombre']);
                        }
                          
                    } else {
                        $("#imagenEquipo").attr("src",urlCompleta+'/public/img/imagen-no-disponible.png');                        
                    }          
                });   
            }
    
        });

                   
        $(document).on('click', '.cerrarModalVerImagenEquipo', function (e) {
            e.preventDefault();
            toggleModalEquipo('ver-imagenequipo')
            $('#tituloModalImagenEquipo').text('');
            $("#imagenEquipo").attr("src","");    
        });






    }   
});