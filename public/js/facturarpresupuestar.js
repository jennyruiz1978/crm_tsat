if(window.location.pathname.includes('/Incidencias')){

    urlCompleta = $('#ruta').val();    

    $(document).on('click', '.clickestado', function (e) {
        e.preventDefault();

        let inc = $(this).closest('tr');
        id = parseInt(inc.find('td:eq(0)').text());
        $('#idIncidenciaFact').val(id);        

        if (id && id >0) {
          
            $.ajax({
                type: "POST",          
                data: {'idIncidencia':id},
                async: false, //necesario                
                url: urlCompleta + "/PresupuestosFacturas/construirSelectEstadoYComentario",            
                dataType: "json",
                success: function (res) {                                        
                    $('#selectEstadoFactPres').html(res['estadosSelect']);
                    if (res['comentariosHtml'] == '') {
                        $('#contenedorComentarioParaFacturador').html('No hay historial.');    
                    }else{
                        $('#contenedorComentarioParaFacturador').html(res['comentariosHtml']);
                    }
                    
                }
            });                        
            toggleModal('facturar-servicio');
        }        
    });      

    $(document).on('click', '.cerrarFacturarPresupuestar', function (e) {
        e.preventDefault();
        $('#comentarioDelFacturador').val('');
        $('#contenedorHistorialCambiosDeEstado').slideUp(300);
        $('#verHistorialFactPpto').html('<i class="fas fa-sort-down mr-2"></i> Ver historial ');
        toggleModal('facturar-servicio');   
    });

    $(document).on('click', '#facturarPresupuestar', function (e) {
        e.preventDefault();
        let estado = $('#selectEstadoFactPres').val();
        let idIncidencia = $('#idIncidenciaFact').val();
        let rol = $('#rolUsuarioFinalizar').val();
        let comentarioDelFacturador = $('#comentarioDelFacturador').val();

        if (estado != null && estado != undefined && estado != '' && idIncidencia != '') {            

            $.ajax({
                type: "POST",          
                data: {'estado':estado, 'idIncidencia':idIncidencia, 'comentarioDelFacturador':comentarioDelFacturador},
                async: false, //necesario                
                url: urlCompleta + "/PresupuestosFacturas/cambiarEstadoFacturarPresupuestar",            
                dataType: "json",
                success: function (res) {                   

                    if (res['guardar'] == 1 || res['guardar'] == 0 || res['guardar'] == 2) {                                                
                            
                        if (res['guardar'] == 1) {
                            
                            toggleModal('facturar-servicio');

                            Swal.fire({
                                title: 'Actualización de estado',
                                text: 'Se ha cambiado corréctamente el estado de la solicitud '+res['idincidencia'],
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }); 
                            
                            if (rol == 'tecnico') {
                                recargartablaAjax2('tab-misincidencias','listarIncidenciasTecnico');
                                recargartablaAjax2('tab-todas','listarTodasLasIncidencias');
                            }else if(rol == 'admin'){
                                recargartablaAjax2('contenedorListadoAdmin','listarTodasLasIncidencias');
                            }
                            cargarSolicitPorFacturarYPresptarYAceptadasYPptosDespuesCambiarEstado();
                            $('#comentarioDelFacturador').val('');

                        
                        }else if (res['guardar'] == 2) {
                            Swal.fire({
                                title: 'Error',
                                text: 'No se ha podido actualizar el estado por que la incidencia tiene una factura con líneas facturadas.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }else{
                            Swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error y no se han podido guardar los cambios.',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            }); 
                        }
                    }

                }
            });  

        }  else{
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar el nuevo estado antes de guardar.',
                icon: 'error',
                confirmButtonText: 'Ok'
            }); 
        }      
    });  

    
    $('#enviarSolicitudPppto').on('click', function (e) {
        e.preventDefault();
        let estado = $('#estadoFactPptoEdit').val();        
        let nombreEstado = $("#estadoFactPptoEdit").find("option:selected").text();       
        let idIncidencia = $('#idIncidenciaVer').val();
        let comentario = $('#comentarioParaFacturadorEdit').val();        
        
        const today = new Date();
        let year = today.getFullYear();
        let month = `${today.getMonth() + 1}`.padStart(2, 0);
        let day = `${today.getDate()}`.padStart(2, 0);
        let hour = `${today.getHours()}`.padStart(2, 0);
        let min = `${today.getMinutes()}`.padStart(2, 0);
        let sec = `${today.getSeconds()}`.padStart(2, 0);
        let fechaCompleta = `${day}/${month}/${year} ${hour}:${min}:${sec}`;            
        let nombreUsuario = $('#nombreUsuario').val();      

        
        if (estado != null && estado != undefined && estado != '' && idIncidencia >0) {

            toggleModal('modal-loadajax'); 

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/PresupuestosFacturas/cambiarEstadoFacturarPresupuestar',
                dataType: "json",
                data: { 'estado':estado, 'idIncidencia':idIncidencia, 'comentarioDelFacturador':comentario },
            }).done(function(data){   

                toggleModal('modal-loadajax'); 

                if (data['guardar'] == 0 || data['guardar'] == 1 || data['guardar'] == 2) {
                    
                    if (data['guardar'] == 1) {
                        Swal.fire({
                            title: 'Actualización de estado',
                            text: 'Se ha cambiado corréctamente el estado de la solicitud '+idIncidencia,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                        let comentariosHtml = `<div class='w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-pink-700 focus:border-transparent'>El <span> ${fechaCompleta} </span> <span class='italic'> ${nombreUsuario} cambió el estado a "${nombreEstado}" y comentó: </span> ${comentario} </div>`;
                        $('#contenedorHistorialCambiosDeEstado').prepend(comentariosHtml);
                        $('#comentarioParaFacturadorEdit').val('');
                        $("#estadoFactPptoEdit").val('Seleccionar');
                        cargarSolicitPorFacturarYPresptarYAceptadasYPptosDespuesCambiarEstado();

                    }else if (data['guardar'] == 2) {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se ha podido actualizar el estado por que la incidencia tiene una factura con líneas facturadas.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }else{
                        Swal.fire({
                            title: 'Error',
                            text: 'No se ha podido actualizar el estado de la solicituds.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }

                }
                
            });

        }else{
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar el nuevo estado antes de enviar.',
                icon: 'error',
                confirmButtonText: 'Ok'
            }); 
        }                
    });

    //ALERTAS SOLICITUDES POR FACTURAR
    function cargarSolicitPorFacturarYPresptarYAceptadasYPptosDespuesCambiarEstado() {
        $.ajax({
            type: "POST",                           
            url: urlCompleta + "/PresupuestosFacturas/obtenerSolicitudesFactYPresYAceptYPptos",            
            dataType: "json",
            success: function (res) {
                $('#incidenciasPorFacturar').html(res['facturar']);
                $('#incidenciasPorPresupuestar').html(res['presupuestar']);
                $('#pptosAceptados').html(res['aceptados']);                
            }
        });
    }   

    $('#verHistorialFactPpto').on('click', function (e) {
        e.preventDefault();

        if ($('#contenedorHistorialCambiosDeEstado').is(':visible')) {
            $('#contenedorHistorialCambiosDeEstado').slideUp(300);
            $('#verHistorialFactPpto').html('<i class="fas fa-sort-down mr-2"></i> Ver historial ');
          } else {
            $('#contenedorHistorialCambiosDeEstado').slideDown(300);
            $('#verHistorialFactPpto').html('<i class="fas fa-sort-up mr-2"></i> ocultar historial ');
          }

    });


    $('#clienteSolicitarPresupuesto').on('click', function (e) {
        e.preventDefault();

        if($('#marcarPresupuestar').is(":checked") && $('#comentarioParaPresupuesto').val().trim() !== '') {
            
            let idIncidencia = $('#idIncidenciaVer').val();
            let comentario = $('#comentarioParaPresupuesto').val();
            let nombreusuario = $('#idUser').val();
            let fecha = new Date();             
            let fechaCompleta = `${fecha.getDay()}-${fecha.getMonth()+1}-${fecha.getFullYear()} ${fecha.getHours()}:${fecha.getMinutes()}`;            
            
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/PresupuestosFacturas/enviarSolicitudDePresupuestoAAdmin',
                dataType: "json",
                data: { 'idIncidencia':idIncidencia, 'comentario':comentario },
            }).done(function(data){ 
                            
                if (data['enviado'] == 1) {
                    
                    Swal.fire({
                        title: 'Solicitud de presupuesto',
                        text: 'Se ha enviado corréctamente solicitud de presupuesto',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                    
                    $('#contenedorClienteSolictarPpto').html('');
                    let pptoSolicitado = `<label class="flex-1 uppercase text-sm xl:text-base text-gray-500 text-light font-semibold">PRESUPUESTO SOLICITADO</label><div class='w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-pink-700 focus:border-transparent'>Presupuesto solicitado por el usuario <span class='italic'> ${nombreusuario} </span> con fecha <span class='italic'> ${fechaCompleta} </span>. Comentario: <span class='italic'> ${comentario}  </span>  </div>`;
                    $('#contenedorClienteSolictarPpto').html(pptoSolicitado);

                }
            });
        }else{
            Swal.fire({
                title: 'Error',
                text: 'Debe marcar la casilla solicitar presupuesto.',
                icon: 'error',
                confirmButtonText: 'Ok'
            }); 
        }  

    });



    function recargartablaAjax2(tab,metodo) {
        
        $.ajax({
          type: "POST",
          url:urlCompleta + '/Incidencias/'+metodo,
          success: function (data) {            
            if (data != '') {
              data = JSON.parse(data);              
              $('#'+tab).html(data);  
            }            
          }
        }); 
    }   

    function toggleModal(modal_id) {            
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("block");
        document.getElementById(modal_id + "-backdrop").classList.toggle("block");
    }   
      
     

}

