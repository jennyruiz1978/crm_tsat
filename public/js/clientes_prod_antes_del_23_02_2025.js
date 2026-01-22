$(document).ready(function() {
  
    if(window.location.pathname.includes('/Clientes')){

        
		function validatorInputKeyPressGeneral() {	//veerificar no está funcionando para email				
			//Validaciones de los campos del fomulario de creación/edición				
			var UXAPP = UXAPP || {};
	
			// paquete de validaciones
			UXAPP.validador = {};
	
			// método que inicia el validador con restriccion de caracteres
			UXAPP.validador.init = function () {
				// busca los elementos que contengan el atributo regexp definido
				$("input[regexp]").each(function(){
					// por cada elemento encontrado setea un listener del keypress
					$(this).keypress(function(event){
						// extrae la cadena que define la expresión regular y creo un objeto RegExp 
						// mas info en https://goo.gl/JEQTcK
						var regexp = new RegExp( "^" + $(this).attr("regexp") + "$" , "g");
						// evalua si el contenido del campo se ajusta al patrón REGEXP
						if ( ! regexp.test( $(this).val() + String.fromCharCode(event.which) ) )
							event.preventDefault();		
					});
				});	
			}
	
			// Arranca el validador al término de la carga del DOM
			$(document).ready( UXAPP.validador.init );
	
		}
	
        

        urlCompleta = $('#ruta').val();

        $('#nuevoCliente').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/nuevoCliente',
            }).done(function(data){   
                data = JSON.parse(data);                   

                if($('#contenedorNuevoCliente').is(':visible')){
                    $('#contenedorNuevoCliente').slideUp(300);
                    $('#contenedorNuevoCliente').html('');    
                }else{
                    $('#contenedorNuevoCliente').slideDown(300);
                    $('#contenedorNuevoCliente').html(data['form']);
                    $('#selectCuenta').select2();                    
                    validatorInputKeyPressGeneral();
                }
            });

        });

        $(document).on('click', '.cancelarCerrar', function () {        
            
            if($('#contenedorNuevoCliente').is(':visible')){

                $('#contenedorNuevoCliente').slideUp(300);      
                $('#contenedorNuevoCliente').html('');                 
            }

        });      

            
        $(document).on('click', '#crearClienteNuevo', function (e) {

            if (document.getElementById("formAltaClientes").checkValidity()) {
                e.preventDefault();
                        
                form = $('#formAltaClientes').serializeArray();
                var tipo = 'agregarycerrar';
                //envia le formulario por ajax,
                envioDatosClienteNuevoPorAjax(form,tipo);
            
            }

        });

        $(document).on('click', '#crearClienteYSeguir', function (e) {

            if (document.getElementById("formAltaClientes").checkValidity()) {
                e.preventDefault();
                        
                form = $('#formAltaClientes').serializeArray();
                let tipo = 'agregarycontinuar'
                
                let sucursalDefault = 0;
                if ($('#sucursalDefault').prop('checked')) {
                    sucursalDefault = 1
                }
                //envia le formulario por ajax,
                envioDatosClienteNuevoPorAjax(form,tipo,sucursalDefault);
            }
        });


        function envioDatosClienteNuevoPorAjax(form,tipo,sucursalDefault) {
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/agregarClienteNuevo',
                    dataType: "json",
                    data: { 'form': form, 'tipo':tipo, 'sucursalDefault':sucursalDefault },
                }).done(function(data){   

                    if (data['respuesta'] == 1) {

                        $('#contenedorNuevoCliente').html('');

                        if (tipo = 'agregarycontinuar') {                            
                            $('#contenedorNuevoCliente').html(data['formLleno']);
                        }else{
                            $('#contenedorNuevoCliente').slideUp(300);                            
                        }
                        
                        $('#selectCuenta').select2();
                        if(data['cuentasBancarias'] && data['cuentasBancarias'].length > 0){
                            $('#selectCuenta').val(data['cuentasBancarias']).trigger('change');             
                        }
                        
                        document.getElementById('contenedorDestino').innerHTML = '';
                        $('#contenedorDestino').prepend(data['clasetabla']);


                                                             
                        Swal.fire({
                            title: 'Creación de nuevo cliente',
                            text: 'Se ha creado el cliente corréctamente',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });                           

                        $("#mensajeValidacion").text("Se ha creado el cliente corréctamente.").show().fadeOut(3000);  

                    }else{
                                            
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error y no se ha podido crear el nuevo cliente.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    
                        $("#mensajeValidacion").text("Ha ocurrido un error y no se ha podido crear el nuevo cliente.").show().fadeOut(3000);    
                    }
                });   
        }

        function scrollbody() {           
            $("html").animate({ scrollTop: 0 }, "slow");
            return false;
        }


        var clienteEdicion = '';
        $(document).on('click', '.editar', function (e) {

            e.preventDefault();
                       
            clienteEdicion = $(this).closest('tr');
            id = parseInt(clienteEdicion.find('td:eq(0)').text());
            $('#idCliEdit').val(id);

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/obtenerDetalleCliente',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){                     

                    if (data['respuesta'] == 1) {                        
                        $('#contenedorNuevoCliente').html('');                        
                        $('#contenedorNuevoCliente').html(data['fila']);   
                        $('#selectCuenta').select2();
                        if(data['cuentasBancarias'] && data['cuentasBancarias'].length > 0){
                            //$('#selectCuenta').val(['2', '3']).trigger('change');             
                            $('#selectCuenta').val(data['cuentasBancarias']).trigger('change');             
                        }
                                 
                        $('#contenedorNuevoCliente').slideDown(300);                                                
                        scrollbody();

                    }else{                       
                        $("#mensajeValidacion").text("No se puede visualizar el cliente").show().fadeOut(3000);  
                    }

            });  

           

        });

       
        var filaTablaCliente = ''; 
        //var modalActivo = ''; 

        //$(document).on('click', '.eliminar', function (e) {            
        $(document).on('click', '#destinoclientesajax .eliminar', function (e) {
        e.preventDefault();

            filaTablaCliente = $(this).closest('tr');
            id = parseInt(filaTablaCliente.find('td:eq(0)').text());
            nombre = filaTablaCliente.find('td:eq(1)').text();
            //modalActivo = 'modal-id';

            if (id >0) {                                
                $('#idCliEliminar').val(id);
                $('#datoCliente').text(id + " - " + nombre);
                toggleModal('modal-id');                    
            }
        });

        $(document).on('click', '.cerrarModal', function (e) {
            toggleModal('modal-id')
            $('#idCliEliminar').val('');
            $('#datoCliente').text('');
        });

        $(document).on('click', '#enviarEliminar', function (e) {
            e.preventDefault();
            id = $('#idCliEliminar').val();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/eliminarCliente',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){

                    if (data == 1) {                    
                        toggleModal('modal-id');
                        filaTablaCliente.remove();
                        $("#mensajeValidacion").text("Se ha eliminado el cliente").show().fadeOut(3000);  
                        
                    }else{                       
                        $("#mensajeValidacion").text("No se puede eliminar el cliente").show().fadeOut(3000);  

                    }
            });  

        });

        $(document).on('click', '#actualizarCliente', function (e) {        

            e.preventDefault();

            if (document.getElementById("formAltaClientes").checkValidity()) {               
                
                id = $('#idCliEdit').val();
                nombre = $('#nombre').val();
                cif = $('#cif').val();
                poblacion = $('#poblacion').val();
                provincia = $('#provincia').val();
                                
                form = $('#formAltaClientes').serializeArray();
                //envia le formulario por ajax,
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/actualizarCliente',
                    dataType: "json",
                    data: { 'form': form, 'id':id },
                }).done(function(data){   

                    if (data['error'] == false) {                        
                        $('#tabla1tbody').find(clienteEdicion).find('td:eq(1)').html(nombre);
                        $('#tabla1tbody').find(clienteEdicion).find('td:eq(2)').html(cif);
                        $('#tabla1tbody').find(clienteEdicion).find('td:eq(3)').html(poblacion);
                        $('#tabla1tbody').find(clienteEdicion).find('td:eq(4)').html(provincia);
                        Swal.fire({
                            title: 'Actualización de nuevo cliente',
                            text: 'Se ha actualizado el cliente corréctamente',
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

        $(document).on('change', '#tecnicos', function () {
            
            
            var id = $(this).attr('option', 'selected').val();            
            nombre = $('#tecnicos option:selected').html();   
            idCliente = $('#idCliEdit').val();         

            if (id >0) {          
                
                var fila = `<tr>
                                <td width="20%"><input name="codigoTecnico" value="${id}" style="width: 100%;"></td>
                                <td width="60%">${nombre}</td>
                                <td width="20%"><a href="" class="eliminarTecnico"><i class="fas fa-user-minus" style="color:red;"></i></a></td>             
                            </tr>`;
                $('#tablaTecnicosCliente').append(fila); 
            }            
        });

        $(document).on('click', '.eliminarTecnico', function (e) {
            
            e.preventDefault();
            
            filaTecnico = $(this).closest('tr');                
            filaTecnico.remove();

        });

        $(document).on('click', '#addContacto', function () {            
                               
                var fila = `<tr>                                
                                <td width="40%"><input class="border-2 border-gray-200 rounded-lg border-opacity-50" name="nombreContacto" style="width: 100%;"></td>
                                <td width="20%"><input type="email" class="border-2 border-gray-200 rounded-lg border-opacity-50" name="mailContacto" style="width: 100%;"></td>
                                <td width="20%"><input type="text" regexp="[0-9]{0,9}"  class="border-2 border-gray-200 rounded-lg border-opacity-50" name="telefonoContacto" style="width: 100%;"></td>
                                <td width="10%"><a class="eliminarContactoCli"><i class="fas fa-user-minus" style="color:red;"></i></a></td>             
                            </tr>`;
                $('#tablaContactosCliente').append(fila);
                validatorInputKeyPressGeneral();
    
        });

        $(document).on('click', '.eliminarContactoCli', function (e) {                                    
            e.preventDefault();
            
            filaContacto = $(this).closest('tr');                
            filaContacto.remove();
        });

        $(document).on('click', '.tab-clientes', function (e) {              
            e.preventDefault();
            idCliente = $('#idCliEdit').val();
            tab = $(this).data('tab');        
            metodo = $(this).data('metodo');

            if (idCliente && idCliente >0) {
                
                activadorTabActivoCliente(e, tab);  

                if (tab != 'tab-profile') {                                        
                    //envia le formulario por ajax
                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Clientes/'+metodo,
                        dataType: "json",
                        data: { 'id':idCliente },
                    }).done(function(data){   
                        if (data['respuesta'] == 1) {  
                            $('.pastilla').html('');                                           
                            $('#'+tab).html(data['tabla']);                                                
                        }else{                       
                            
                        }
                    });   
                }

            }else{
                $("#mensajeValidacion").text("Debe completar y guardar los datos cliente antes de continuar.").show().fadeOut(3000);
            }
            

        });



        //modificado
        $(document).on('click', '.modificar', function (e) {
            e.preventDefault();
            var modif = $(this).closest('tr');
            id = parseInt(modif.find('td:eq(0)').text());
            $('#idEquipoModif').val(id);
            $('#idEquipoModifMntto').val(id);
                        
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/verEditorParaModificarModalidad',
                dataType: "json",
                data: {'id':id}
            }).done(function(data){               
                         
                if(data.empresa=='INFOMALAGA'){
                    $('#bodyModalVerModalidad').html('');                 
                    $('#bodyModalVerModalidad').html(data['html']);       
                    toggleModal('modificar-modalidad');
                }else{                    
                    $('#bodyModalVerModalidadMntto').html('');                 
                    $('#bodyModalVerModalidadMntto').html(data['html']);       
                    toggleModal('modificar-modalidadMntto');
                }
            });
            

        });

        $(document).on('click', '.cerrarModificarModalidad', function (e) {
            toggleModal('modificar-modalidad')            
        });
        
        $(document).on('click', '#modifModalidad', function (e) {
            
            modalidad = $('#modalidadActual').attr('option', 'selected').val();
            contratado = $('#contratado').val();
            
            idEquipo = $('#idEquipoModif').val();
            idCliente = $('#idCliEdit').val();
           
            
            var mesInicio = $('#mesInicio').attr('option', 'selected').val();
            var anioInicio = $('#anioInicio').attr('option', 'selected').val();
            var mesFin = $('#mesFin').attr('option', 'selected').val();
            var anioFin = $('#anioFin').attr('option', 'selected').val();       
            
          
                    
            if (idEquipo =='' || idEquipo == null || idEquipo ==0) {
                $("#msgValidarModalidad").text("El equipo no existe").show().fadeOut(3000);  
            }else if(contratado =='' || contratado ==0){
                $("#msgValidarModalidad").text("Debe ingresar el precio.").show().fadeOut(3000); 
            }else if (parseInt(anioFin) == parseInt(anioInicio) && parseInt(mesInicio) > parseInt(mesFin)){
                $("#msgValidarModalidad").text("El mes inicial no puede ser mayor que el final").show().fadeOut(3000);
            }else if(mesInicio ==null || mesInicio=='' || mesInicio==0){
                $("#msgValidarModalidad").text("Complete mes inicio").show().fadeOut(3000);
                e.preventDefault();
            }else if(mesFin ==null || mesFin=='' || mesFin==0){
                $("#msgValidarModalidad").text("Complete mes fin").show().fadeOut(3000);
                e.preventDefault();                        
            }else if(parseInt(anioInicio) > parseInt(anioFin)){
                $("#msgValidarModalidad").text("El año inicial no puede ser mayor que el final").show().fadeOut(3000);
                e.preventDefault();
            }else{
                                
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/actualizarModalidadDePago',
                    dataType: "json",
                    data: {'modalidad':modalidad,'contratado':contratado,'mesInicio':mesInicio,'anioInicio':anioInicio,'mesFin':mesFin,'anioFin':anioFin, 'idEquipo':idEquipo, 'idCliente':idCliente}
                }).done(function(data){
                    if (data == 1) {                        
                        toggleModal('modificar-modalidad');
                        $("#msgModalidad").text("Se han actualizado los cambios corréctamente").show().fadeOut(2500);
                    }else{
                        $("#msgValidarModalidad").text("No se han podido actualizar los cambios").show().fadeOut(2500);                        
                    }                    
                });
            }          


        });
        
        $(document).on('click', '#destinoequiposmodospagoajax .historial', function (e) {
            e.preventDefault();

            var modif = $(this).closest('tr');
            id = parseInt(modif.find('td:eq(0)').text());

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Clientes/historialModalidadDepago',
                dataType: "json",
                data: {'id':id }
            }).done(function(data){
                if (data['respuesta'] == 1) {                                                 
                    $('#tablaHistorialModalidad tbody').html('');       
                    $('#tablaHistorialModalidad tbody').html(data['html']);
                }else{
                    $('#tablaHistorialModalidad tbody').html('');       
                    $('#tablaHistorialModalidad tbody').html('<p>no hay historial para mostrar</p>');
                }                    
            });
            toggleModal('historial-modalidad');
        });        

        $(document).on('click', '.cerrarHistorialModalidad', function (e) {
            toggleModal('historial-modalidad')            
        });

        function toggleModal(modal_id) {            
            document.getElementById(modal_id).classList.toggle("hidden");
            document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
            document.getElementById(modal_id).classList.toggle("flex");
            document.getElementById(modal_id + "-backdrop").classList.toggle("flex");
        }
                
        function activadorTabActivoCliente(event,tabID){
            let element = event.target;
            while(element.nodeName !== "A"){
            element = element.parentNode;
            }
            ulElement = element.parentNode.parentNode;
            aElements = ulElement.querySelectorAll("li > a");
            tabContents = document.getElementById("tabs-id").querySelectorAll(".tab-content > div");
            for(let i = 0 ; i < aElements.length; i++){
            aElements[i].classList.remove("text-white");
            aElements[i].classList.remove("bg-violeta-oscuro");
            aElements[i].classList.add("texto-violeta-oscuro");
            aElements[i].classList.add("bg-white");
            tabContents[i].classList.add("hidden");
            tabContents[i].classList.remove("block");
            }
            element.classList.remove("texto-violeta-oscuro");
            element.classList.remove("bg-white");
            element.classList.add("text-white");
            element.classList.add("bg-violeta-oscuro");
            document.getElementById(tabID).classList.remove("hidden");
            document.getElementById(tabID).classList.add("block");
        }

         
        $("#formAltaUsuario").submit(function (event) {
                        
            rol = $('select[name="rol"] option:selected').val();
            idcliente = $('#idcliente').attr('option', 'selected').val();   
                
            if (rol == 1 && (idcliente == null || idcliente=='') ) {            
                $("#msgValidaCliente").text("Debe seleccionar un cliente.").show().fadeOut(3000);  
                event.preventDefault();     
            }        
        });

        //nuevos
        $(document).on('click', '#modifModalidadMntto', function (e) {
            
            let modalidad = $('#modalidadMntto').attr('option', 'selected').val();
            let contratado = $('#contratadoMntto').val();            
            let idEquipo = $('#idEquipoModifMntto').val();
            let idCliente = $('#idCliEdit').val();                    
            let fechaInicio = $('#fechaInicio').val();
            let comentarios = $('#comentarios').val();                      
                    
            if (idEquipo =='' || idEquipo == null || idEquipo ==0) {
                $("#msgValidarModalidadMntto").text("El equipo no existe").show().fadeOut(3000);  
            }else if(contratado =='' || contratado ==0){
                $("#msgValidarModalidadMntto").text("Debe ingresar el precio.").show().fadeOut(3000);             
            }else if(fechaInicio ==null || fechaInicio=='' || fechaInicio==0){
                $("#msgValidarModalidadMntto").text("Debe ingresar la fecha de inicio").show().fadeOut(3000);
                e.preventDefault();            
            }else{
                                
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/actualizarModalidadDePago',
                    dataType: "json",
                    data: {'modalidad':modalidad,'contratado':contratado,'fechaInicio':fechaInicio, 'idEquipo':idEquipo, 'idCliente':idCliente, 'comentarios':comentarios, 'empresa':'telesat'}
                }).done(function(data){
                    if (data.error == false) {                                            
                        $("#msgModalidad").text("Se han actualizado los cambios corréctamente").show().fadeOut(2500);
                        $('#tablaHistorialModalidadMntto tbody').html(data.tablabody);      
                        
                        $('#modalidadMntto').prop('selectedIndex', 0);
                        $('#contratadoMntto').val('');                          
                        $('#fechaInicio').val('');   
                        
                    }else{                        
                        Swal.fire({
                            title: 'Error',
                            text: 'No se han guardado los datos',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });              
                    }                    
                });
            }          
        });

        $(document).on('click', '.cerrarModificarModalidadMntto', function (e) {
            toggleModal('modificar-modalidadMntto')            
        });

        $(document).on('click', '#modificar-modalidadMntto .eliminar-mod-mntto', function (e) {
                e.preventDefault();

                let idMod = $(this).data('mod');                
               
                let ask = confirm('Está seguro de eliminar este registro?');
                if(ask){
                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Clientes/eliminarMantenimientoEquipo',
                        dataType: "json",
                        data: {'idMod':idMod}
                    }).done(function(data){
                        if (data == true) {         
                            $('#fila_mod_'+idMod).remove();
                            Swal.fire({
                                title: 'Registro eliminado',
                                text: 'Se ha eliminado el registro seleccionado.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });                     
                        }else{                         
                            Swal.fire({
                                title: 'Error',
                                text: 'No se puede eliminar el registro',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }                    
                    });
                }                                       
        });
    
        $(document).on('click','#guardarComentariosEquipo', function () {
            

                let idEquipo = $('#idEquipoModifMntto').val();
                let comentarios = $('#comentarios').val();    

                console.log(idEquipo, comentarios);
                
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Clientes/actualizarComentarioEquipoMntto',
                    dataType: "json",
                    data: {'idEquipo':idEquipo, 'comentarios':comentarios}
                }).done(function(data){
                    if (data == true) {                                                
                        Swal.fire({
                            title: 'Comentario guardado',
                            text: 'Se ha guardado el registro corréctamente.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });  

                    }else{                        
                        Swal.fire({
                            title: 'Error',
                            text: 'No se han guardado los datos',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });              
                    }                    
                });    
                
        });
      






    }

   

});