$(document).ready(function() {
  
    if(window.location.pathname.includes('/Proveedores')){

        
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

        $('#nuevoProveedor').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Proveedores/nuevoProveedor',
            }).done(function(data){   
                data = JSON.parse(data);                   

                if($('#contenedorNuevoProveedor').is(':visible')){
                    $('#contenedorNuevoProveedor').slideUp(300);
                    $('#contenedorNuevoProveedor').html('');    
                }else{
                    $('#contenedorNuevoProveedor').slideDown(300);
                    $('#contenedorNuevoProveedor').html(data['form']);
                    validatorInputKeyPressGeneral();
                }
            });

        });

        $(document).on('click', '.cancelarCerrar', function () {        
            
            if($('#contenedorNuevoProveedor').is(':visible')){

                $('#contenedorNuevoProveedor').slideUp(300);      
                $('#contenedorNuevoProveedor').html('');                 
            }

        });      

            
        $(document).on('click', '#crearProveedorNuevo', function (e) {

            //console.log('check',document.getElementById("formAltaProveedores").checkValidity());
            
            if (document.getElementById("formAltaProveedores").checkValidity()) {
                e.preventDefault();
                        
                form = $('#formAltaProveedores').serializeArray();
                var tipo = 'agregarycerrar';
                //envia le formulario por ajax,
                envioDatosProveedorNuevoPorAjax(form,tipo);
            
            }

        });

        $(document).on('click', '#crearProveedorYSeguir', function (e) {

            if (document.getElementById("formAltaProveedores").checkValidity()) {
                e.preventDefault();
                        
                form = $('#formAltaProveedores').serializeArray();
                let tipo = 'agregarycontinuar'
                
                let sucursalDefault = 0;
                if ($('#sucursalDefault').prop('checked')) {
                    sucursalDefault = 1
                }
                //envia le formulario por ajax,
                envioDatosProveedorNuevoPorAjax(form,tipo,sucursalDefault);
            }
        });


        function envioDatosProveedorNuevoPorAjax(form,tipo,sucursalDefault) {
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Proveedores/agregarProveedorNuevo',
                    dataType: "json",
                    data: { 'form': form, 'tipo':tipo, 'sucursalDefault':sucursalDefault },
                }).done(function(data){   

                    if (data['respuesta'] == 1) {

                        $('#contenedorNuevoProveedor').html('');

                        if (tipo = 'agregarycontinuar') {                            
                            $('#contenedorNuevoProveedor').html(data['formLleno']);
                        }else{
                            $('#contenedorNuevoProveedor').slideUp(300);                            
                        }
                        
                        
                        document.getElementById('contenedorDestino').innerHTML = '';
                        $('#contenedorDestino').prepend(data['clasetabla']);
                                                             
                        Swal.fire({
                            title: 'Creación de nuevo proveedor',
                            text: 'Se ha creado el proveedor corréctamente',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });                           

                        $("#mensajeValidacion").text("Se ha creado el proveedor corréctamente.").show().fadeOut(3000);  

                    }else{
                                            
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error y no se ha podido crear el nuevo proveedor.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    
                        $("#mensajeValidacion").text("Ha ocurrido un error y no se ha podido crear el nuevo proveedor.").show().fadeOut(3000);    
                    }
                });   
        }

        function scrollbody() {           
            $("html").animate({ scrollTop: 0 }, "slow");
            return false;
        }


        var proveedorEdicion = '';
        $(document).on('click', '.editar', function (e) {

            e.preventDefault();
                       
            proveedorEdicion = $(this).closest('tr');
            id = parseInt(proveedorEdicion.find('td:eq(0)').text());
            $('#idProvEdit').val(id);

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Proveedores/obtenerDetalleProveedor',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){                     

                    if (data['respuesta'] == 1) {
                        $('#contenedorNuevoProveedor').html('');                        
                        $('#contenedorNuevoProveedor').html(data['fila']);                        
                        $('#contenedorNuevoProveedor').slideDown(300);                                                
                        scrollbody();

                    }else{                       
                        $("#mensajeValidacion").text("No se puede visualizar el proveedor").show().fadeOut(3000);  
                    }

            });  

           

        });

       
        var filaTablaProveedor = ''; 
        //var modalActivo = ''; 

        //$(document).on('click', '.eliminar', function (e) {            
        $(document).on('click', '#destinoproveedoresajax .eliminar', function (e) {
        e.preventDefault();

            filaTablaProveedor = $(this).closest('tr');
            id = parseInt(filaTablaProveedor.find('td:eq(0)').text());
            nombre = filaTablaProveedor.find('td:eq(1)').text();
            //modalActivo = 'modal-id';

            if (id >0) {                                
                $('#idProvEliminar').val(id);
                $('#datoProveedor').text(id + " - " + nombre);
                toggleModal('modal-id');                    
            }
        });

        $(document).on('click', '.cerrarModal', function (e) {
            toggleModal('modal-id')
            $('#idProvEliminar').val('');
            $('#datoProveedor').text('');
        });

        $(document).on('click', '#enviarEliminar', function (e) {
            e.preventDefault();
            id = $('#idProvEliminar').val();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Proveedores/eliminarProveedor',
                dataType: "json",
                data: { 'id' : id  },
            }).done(function(data){

                    if (data == 1) {                    
                        toggleModal('modal-id');
                        filaTablaProveedor.remove();
                        $("#mensajeValidacion").text("Se ha eliminado el proveedor").show().fadeOut(3000);  
                        
                    }else{                       
                        $("#mensajeValidacion").text("No se puede eliminar el proveedor").show().fadeOut(3000);  

                    }
            });  

        });

        $(document).on('click', '#actualizarProveedor', function (e) {        

            e.preventDefault();

            if (document.getElementById("formAltaProveedores").checkValidity()) {               
                
                id = $('#idProvEdit').val();
                nombre = $('#nombre').val();
                cif = $('#cif').val();
                poblacion = $('#poblacion').val();
                provincia = $('#provincia').val();
                                
                form = $('#formAltaProveedores').serializeArray();
                //envia le formulario por ajax,
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Proveedores/actualizarProveedor',
                    dataType: "json",
                    data: { 'form': form, 'id':id },
                }).done(function(data){   

                    if (data == 1) {                        
                        $('#tabla1tbody').find(proveedorEdicion).find('td:eq(1)').html(nombre);
                        $('#tabla1tbody').find(proveedorEdicion).find('td:eq(2)').html(cif);
                        $('#tabla1tbody').find(proveedorEdicion).find('td:eq(3)').html(poblacion);
                        $('#tabla1tbody').find(proveedorEdicion).find('td:eq(4)').html(provincia);
                        Swal.fire({
                            title: 'Actualización de nuevo proveedor',
                            text: 'Se ha actualizado el proveedor corréctamente',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }); 
                        $("#mensajeValidacion").text("Se han actualizado los datos corréctamente.").show().fadeOut(3000);  
                    }else{   
                                              
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error y no se ha podido actualiza el proveedor.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });                     
                        $("#mensajeValidacion").text("Ha ocurrido un error y no se ha podido crear el nuevo proveedor.").show().fadeOut(3000);    
                    }
                });               
            }



        });


        $(document).on('click', '#addContacto', function () {            
                               
                var fila = `<tr>                                
                                <td width="40%"><input class="border-2 border-pink-200 rounded-lg border-opacity-50" name="nombreContacto" style="width: 100%;"></td>
                                <td width="20%"><input type="email" class="border-2 border-pink-200 rounded-lg border-opacity-50" name="mailContacto" style="width: 100%;"></td>
                                <td width="20%"><input type="text" regexp="[0-9]{0,9}"  class="border-2 border-pink-200 rounded-lg border-opacity-50" name="telefonoContacto" style="width: 100%;"></td>
                                <td width="10%"><a class="eliminarContactoProv"><i class="fas fa-user-minus" style="color:red;"></i></a></td>             
                            </tr>`;
                $('#tablaContactosProveedor').append(fila);
                validatorInputKeyPressGeneral();
    
        });

        $(document).on('click', '.eliminarContactoProv', function (e) {                                    
            e.preventDefault();
            
            filaContacto = $(this).closest('tr');                
            filaContacto.remove();
        });

        $(document).on('click', '.tab-proveedores', function (e) {              
            e.preventDefault();
            let idProveedor = $('#idProvEdit').val();
            tab = $(this).data('tab');        
            metodo = $(this).data('metodo');

            if (idProveedor && idProveedor >0) {
                
                activadorTabActivoProveedor(e, tab);  

                if (tab != 'tab-profile') {                                        
                    //envia le formulario por ajax
                    $.ajax({
                        type: 'POST',
                        url: urlCompleta + '/Proveedores/'+metodo,
                        dataType: "json",
                        data: { 'id':idProveedor },
                    }).done(function(data){   
                        if (data['respuesta'] == 1) {  
                            $('.pastilla').html('');                                           
                            $('#'+tab).html(data['tabla']);                                                
                        }else{                       
                            
                        }
                    });   
                }

            }else{
                $("#mensajeValidacion").text("Debe completar y guardar los datos proveedor antes de continuar.").show().fadeOut(3000);
            }
            

        });


        function toggleModal(modal_id) {            
            document.getElementById(modal_id).classList.toggle("hidden");
            document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
            document.getElementById(modal_id).classList.toggle("flex");
            document.getElementById(modal_id + "-backdrop").classList.toggle("flex");
        }
                
        function activadorTabActivoProveedor(event,tabID){
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

    }


});