import DB from './fecth.js';

$(document).ready(function() {

    if(window.location.pathname.includes('/Incidencias/editarIncidencia')){

        var urlCompleta = $('#ruta').val();              
    
        $(document).on('click', '.edit_field', function() {

            let nameField = $(this).data('field');
            let $fieldElement = $('[name="' + nameField + '"]');
            let isValid = validateField($fieldElement, nameField);

            let nameFieldFinal = nameField;        
            let textResponse = 'Se ha actualizado el campo '+nameFieldFinal;
            if(nameField=='equiposTecnico'){
                nameFieldFinal = 'sucursal y equipo';
                textResponse = 'Se han actualizado los campos '+nameFieldFinal;
            }
            
            
            if (isValid) {
                    
                Swal.fire({
                    title: 'Actualización de '+nameFieldFinal,
                    text: '¿Quiere guardar los cambios?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (result.isConfirmed) {
                        let idIncidencia = $('#idIncidenciaVer').val();            
                        let fields = {};

                        if(nameField=='equiposTecnico'){
                        
                            let idequipo = $('#equiposTecnico').val();
                            let sucursal = $('#sucursalEdit').val();                                               
                            let idcliente = $('#cliente_editar').val();
                            fields['idequipo'] = idequipo;
                            fields['sucursal'] = sucursal;
                            fields['idcliente'] = idcliente;

                        }else{

                            let descripcion = $('[name="'+nameField+'"]').val();                        
                            fields[nameField] = descripcion;

                        }                    
            
                        $.ajax({
                            type: 'POST',
                            url: urlCompleta + '/Incidencias/modificarCampoIncidencia',
                            dataType: "json",
                            data: { 'idIncidencia': idIncidencia, 'fields': fields }
                        }).done(function(data){                          
            
                            if (data.success) {              
                                
                                if(data.tecnicos){
                                    $('input[name="tecnicos"]').val(data.tecnicos); 
                                }

                                Swal.fire({
                                    title: 'Actualización correcta',
                                    text: textResponse,
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });                                 
                            }else {
                                Swal.fire({
                                    title: 'Error de actualización',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                });
                            }    
                        })

                    }
                });
            }
        });
        


        function validateField($fieldElement, nameField) {
            let fieldValue = $fieldElement.val();
            let fieldType = $fieldElement.prop('tagName').toLowerCase();
        
            if (fieldType === 'textarea' || ($fieldElement.attr('type') === 'text' && fieldType === 'input')) {
                if (!fieldValue.trim()) {
                    Swal.fire({
                        title: 'Campo ' + nameField + ' vacío',
                        text: 'El campo ' + nameField + ' no puede estar vacío.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    return false;
                }
            } else if (fieldType === 'select') {
                if (!fieldValue || fieldValue === "0" || fieldValue === "undefined") {

                    let nameFieldFinal = nameField;
                    if(nameField=='equiposTecnico'){
                        nameFieldFinal='Equipo implicado';
                    }

                    Swal.fire({
                        title: 'Campo ' + nameFieldFinal + ' vacío',
                        text: 'Por favor, selecciona una opción válida para el campo ' + nameFieldFinal,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    return false;
                }
            }
        
            return true;
        }    

      

        $(document).on('click', '.eliminarComentario', function (e) {
            e.preventDefault();
        
            
            if (confirm('¿Estás seguro de que deseas eliminar este comentario?')) {
                
                let idComentario = $(this).data('idcomentario');  
                let idIncidencia = $('#idIncidenciaVer').val();  

                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Incidencias/eliminarComentario',  
                    dataType: "json",               
                    data: { 'idcomentario': idComentario, 'idincidencia':idIncidencia },
                    success: function(response) {
                        
                        console.log('response', response);
                        if (response) {
                            
                            $('#comentario_id_' + idComentario).remove();
                        } else {
                            alert('No se pudo eliminar el comentario.');
                        }
                    },
                    error: function() {
                        alert('Ocurrió un error al intentar eliminar el comentario.');
                    }
                });
            }
        });
        
        $(document).on('click', '.eliminarFicheroInc', function (e) {
            e.preventDefault();
        
            
            if (confirm('¿Estás seguro de que deseas eliminar el fichero?')) {
                
                let idfichero = $(this).data('idfichero');  
                let idIncidencia = $('#idIncidenciaVer').val();  

                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Incidencias/eliminarFicheroIncidencia',  
                    dataType: "json",               
                    data: { 'idfichero': idfichero, 'idincidencia':idIncidencia },
                    success: function(response) {
                        
                        console.log('response', response);
                        if (response) {
                            
                            $('#contenedor_fichero_' + idfichero).remove();
                        } else {
                            alert('No se pudo eliminar el fichero.');
                        }
                    },
                    error: function() {
                        alert('Ocurrió un error al intentar eliminar el fichero.');
                    }
                });
            }
        });
        

        $(document).on('click', '#agregarFicheroIncidenciaEdit', function (e) {

            e.preventDefault();        
            
            let idIncidencia = $('#idIncidenciaVer').val();        
            
            let formData = new FormData();
            formData.append('idIncidencia', idIncidencia);             
                    
            let files = $('.fichero-input')[0].files;

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    formData.append('formularioSubirFicheroIncidencia[]', files[i]);
                }
            }               

            if (idIncidencia >0) {        

                toggleModal('modal-loadajax');

                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Incidencias/agregarFicheroIncidenciaEditar',
                    dataType: "json",
                    data: formData,
                    processData: false, 
                    contentType: false, 
                }).done(function(data){  
                    
                    toggleModal('modal-loadajax');

                    if (data['html'] != ''){                                                                          
                        
                        $('#container_ficheros_editar').append(data['html']);

                        $('input[name="ficheroEditarIncidencia[]"]').val(''); 

                        if ($('#formularioSubirFicheroIncidencia').is(':visible')) { 
                            $('#formularioSubirFicheroIncidencia').slideUp(300);
                            $('#desplegar').html('<i class="far fa-image mx-2 text-base"></i>Agregar fichero');                     
                        }

                        Swal.fire({
                            title: 'Proceso finalizado',
                            text: 'Se han agregado los ficheros',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });  
                                
                    } else {
                        $('#msgValidaFichero').text("Error!. No se ha podido agregar el fichero.").show().fadeOut(3500);
                    }

                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                    $('#msgValidaFichero').text("Hubo un error al intentar agregar el fichero. Por favor, inténtelo de nuevo.").show().fadeOut(3000);
                });   

            }
            
        });
        

        //Inicio código para enviar email parte incidencia
            
        let enviar_email_parte = document.getElementById('enviar_email_parte');
        if(enviar_email_parte){
            enviar_email_parte.addEventListener('click', function () {
            
            let idDocumento = document.getElementById('idIncidenciaVer').value;                        
            
            let ruta = urlCompleta+'/Clientes/traerEmailsContactos';
            let params = {'id' : idDocumento, 'tipodoc': 'parte'};               
            let fetch=new DB(ruta, 'POST').get(params);

            fetch.then((respuesta => {   
                
                document.getElementById('emails_contactos_parte').innerHTML = respuesta;             
                document.getElementById('emailMensaje').value = "Buenos días: \nA continuación, le adjuntamos el parte de la solitud N° "+idDocumento;
                toggleModal('enviar-parte');

            }));
            
        });       
        }        

        $(document).on('click', '.cerrarEnviarParte', function (e) {
            e.preventDefault();
            toggleModal('enviar-parte');          
        });
        
        const agregarEmailInputParte = document.getElementById('agregarEmailInputParte');    
        if(agregarEmailInputParte){
        
            agregarEmailInputParte.addEventListener('click', function () {
                            
                let emailDestinatario = document.getElementById('emailDestinatario').value;
                
                
                if(emailDestinatario.trim() != ''){                          
                    let ch = `<div class="container_email flex text-sm gap-2 px-2 py-1" style="border:1px solid #ededed !important;border-radius:6px;"><input class="inputEmailSelected" type="hidden" name="inputEmailSelected[]" value="${emailDestinatario}"><span class="emailSelected text-gray-500">${emailDestinatario}</span><span class="equisEmail text-red-500 font-bold cursor-pointer">x</span></div>`;
                    document.getElementById('emails_selected_to_send_parte').insertAdjacentHTML('beforeend',ch); 
                    document.getElementById('emailDestinatario').value = "";
                }else{
                    alert('Debe escribir un email válido');
                }
                
        
            });      
        }


        const emails_contactos_parte = document.getElementById('emails_contactos_parte');    
        if(emails_contactos_parte){
        
            emails_contactos_parte.addEventListener('change', function () {
                
                let contact_selected = emails_contactos_parte.value;                        
                
                if(contact_selected != ''){                          
                    let ch = `<div class="container_email flex text-sm gap-2 px-2 py-1" style="border:1px solid #ededed !important;border-radius:6px;"><input class="inputEmailSelected" type="hidden" name="inputEmailSelected[]" value="${contact_selected}"><span class="emailSelected text-gray-500">${contact_selected}</span><span class="equisEmail text-red-500 font-bold cursor-pointer">x</span></div>`;
                    document.getElementById('emails_selected_to_send_parte').insertAdjacentHTML('beforeend',ch);   
                    document.getElementById('emails_contactos_parte').value = ""; 
                }
        
            });      
        }

        let emailsToSendPart = document.getElementById('emails_selected_to_send_parte');   
        if(emailsToSendPart){
            emailsToSendPart.addEventListener("click", (event) => {
            
                const clickedElement = event.target;

                if (clickedElement.matches('.equisEmail')) {                       
                    let deletefila = clickedElement.closest(".container_email");
                    deletefila.remove();
                }
                                
            });
        }


        let formSendEmailPart = document.getElementById('formSendEmailPart');
        if(formSendEmailPart){
            formSendEmailPart.addEventListener('submit', function(e) {                
            
                e.preventDefault();            

                document.getElementById('idIncidenciaEnviar').value = document.getElementById('idIncidenciaVer').value;            

                if(document.querySelectorAll('.inputEmailSelected').length > 0){
                    if(document.getElementById('emailAsunto').value.trim() != ''){
                        if(document.getElementById('emailMensaje').value.trim() != ''){              

                            
                            const spinner = document.getElementById("spinner");                        
                            spinner.innerHTML = '<div>Enviando...</div>';
                            spinner.classList.add('spinnerShow');                        
                                            
                            let ruta = urlCompleta+'/Incidencias/enviarEmailParteIncidencia';
                            let datosForm = new FormData(formSendEmailPart);
                            
                            let fetch=new DB(ruta, 'POST').postSend(datosForm);
                            
                            
                            fetch
                                .then((respuesta => {               
                                    
                                    
                                    if(respuesta.error==false){
                                        resetearFormularioEnvioEmail();
                                        toggleModal('enviar-parte');

                                        document.getElementById('contenedorHistorialEmailsPartesEnviados').innerHTML = respuesta.html;

                                        Swal.fire({
                                            title: 'Proceso correcto',
                                            text: respuesta.mensaje,
                                            icon: 'success',
                                            confirmButtonText: 'Ok'          
                                        }); 

                                    }else{
                                        Swal.fire({
                                            title: 'Error',
                                            text: respuesta.mensaje,
                                            icon: 'error',
                                            confirmButtonText: 'Ok'          
                                        });  
                                    }                            
                                        
                                    
                                }))    
                                    

                        }else{
                            alert('Debe agregar el mensaje');    
                        }

                    }else{
                        alert('Debe agregar el asunto');    
                    }

                }else{
                    alert('Debe agregar al menos un destinatario');
                }
                
                
            });
        }
        
        function resetearFormularioEnvioEmail(){
            $('#formSendEmailPart').trigger("reset");			
           
        }

        //Fin código para enviar email parte incidencia


        
        //para el editar incidencia

        // Inicializar select2 en cliente_editar
        $('#cliente_editar').select2({
            width: '100%',
            placeholder: "Buscar cliente...",
            allowClear: true,
            dropdownParent: $('body'),
            minimumResultsForSearch: 0,
            language: {
                inputTooShort: function () {
                    return "Por favor, ingrese 3 o más caracteres";
                },
            },
            ajax: {
                url: urlCompleta + '/Clientes/buscarCliente',
                type: 'POST',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        query: params.term || '',
                        cargarIniciales: !params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.nombre,
                                cif: obj.cif,
                                cuentas: obj.cuentas,
                            };
                        }),
                    };
                },
                cache: true,
            },
        });

        // Manejar evento select
        $('#cliente_editar').on('select2:select', function (e) {
            var data = e.params.data;
            if (data.id > 0) {
                llenarSucursales(data.id); // Llamada a la función para cargar las sucursales
                
                // Limpiar select de equipos
                $('#equiposTecnico').empty();

                // Si estás usando tail.select para equipos, destruirlo y reinicializarlo vacío
                if (tail && tail.select) {
                    tail.select('#equiposTecnico').remove();
                    tail.select('.todos', {
                        search: true,
                        locale: "es",
                        multiSelectAll: true,
                        searchMinLength: 0,
                        multiContainer: false,
                        multiple: true,
                    });
                }
            }
        });

        // Manejar evento clear
        $('#cliente_editar').on('select2:clear', function (e) {
            // Limpiar select de sucursales
            $('#sucursalEdit').html('<option disabled selected>Seleccionar</option>');
           
            // Limpiar select de equipos
            $('#equiposTecnico').empty();

            // Si estás usando tail.select para equipos, destruirlo y reinicializarlo vacío
            if (tail && tail.select) {
                    tail.select('#equiposTecnico').remove();
                    tail.select('.todos', {
                    search: true,
                    locale: "es",
                    multiSelectAll: true,
                    searchMinLength: 0,
                    multiContainer: false,
                    multiple: true,
                });
            }
        });    

                   
        function llenarSucursales(idCliente) {
            if (idCliente > 0) {
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Incidencias/llenarSelectorSucursalesParaTecnico',
                    dataType: "json",
                    data: { 'idCliente': idCliente },
                    success: function(data) {
                        $('#sucursalEdit').html('');     
                        if (data['options']) {                                         
                            $('#sucursalEdit').html(data['options']); 
                        }
                       
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error al obtener las sucursales:", textStatus, errorThrown);
                    }
                });   
            }
        }                
        
        $(document).on('change', '#sucursalEdit', function () {
                
            var idSucursal = $(this).attr('option', 'selected').val();        
            
            if (idSucursal > 0) {
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Incidencias/llenarSelectorEquiposPorSucursal',
                    dataType: "json",
                    data: { 'idSucursal':idSucursal, 'edit':1 },
                }).done(function(data){

                    $('#equiposTecnico').html('');     
                    if (data['options'] != '') {                       
                        $('#contenedorEquiposEdit').html(data['options']); 
                        
                        tail.select('.todos',{
                            search: true,
                            locale: "es",
                            multiSelectAll: true,
                            searchMinLength: 0,
                            multiContainer: false,
                        });
                        
                    }
                    
                });   
            }
        });

      
       
    
    }

});