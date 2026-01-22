import DB from './fecth.js';

if(window.location.pathname.includes('/Incidencias')){

    var urlCompleta = $('#ruta').val();          

    $(document).on('change', '#sucursal', function () {
            
        var idSucursal = $(this).attr('option', 'selected').val();       
        //alert(idSucursal);
        
        if (idSucursal > 0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/llenarSelectorEquiposPorSucursal',
                dataType: "json",
                data: { 'idSucursal':idSucursal },
            }).done(function(data){   
                $('#equipo').html('');     
                if (data['options']) {                                         
                    $('#equipo').html(data['options']); 
                }
            });   
        }
    });

    $(document).on('click', '.butonCerrarAlerta', function (event) {
        cerrarAlerta(event);
    });

        
    $(document).on('click', '.tab-incidencias', function (e) {              
        e.preventDefault();
        let tab = $(this).data('tab');    
        activadorTabActivoCliente(e, tab);  
        let metodo = $(this).data('metodo');
        $('.tab-apartado').html('');
        recargartablaAjax(tab,metodo);
            
    });

    $(document).on('click', '.detener', function (e) {
        
        e.preventDefault();        
        
        var inc = $(this).closest('tr');
        let id = parseInt(inc.find('td:eq(0)').text()); 
        let idAtencion = $(this).data('atencion');

        $('#idIncidenciaStop').val(idAtencion);

        if (id && id >0 && idAtencion && idAtencion >0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/detallesTiemposIncidencia',
                dataType: "json",
                data: { 'idAtencion':idAtencion },
            }).done(function(data){  

                if (data['respuesta'] == 1) {
                    $('#modAtencionDetener').val(data['modalidad']);   
                    $('#creacionInicio').val(data['creacion']);                    
                }                

                if (data['verFactPresup'] == 1) {
                    $('#apartadoFacturarPresupuestar').css('display', 'block');
                    $('#tipoAccion').html(data['optionEstados']);
                }              
                toggleModal('detener-atencion');
            });   
            
        }
        
    });

    $(document).on('click', '.cerrarDetenerAtencion', function (e) {
        e.preventDefault();
        $('#comentClienteStop').val('');
        $('#apartadoFacturarPresupuestar').css('display', 'none');
        $('#tipoAccion').html('');
        $('#comentarioParaFacturador').val('');
        toggleModal('detener-atencion');
    });


    $(document).on('click','#detenerAccion', function (e) {
        e.preventDefault();

        let idAtencion = $('#idIncidenciaStop').val();  
        let rol = $('#nombreRolUsuario').val();  
        let comentario =  $('#comentClienteStop').val();

        //datos para facturar
        let facturarPresupuestar = '';
        let facturar = $('#tipoAccion').val();
        if (facturar != null && facturar != undefined && facturar !='' ) {
            facturarPresupuestar = facturar;
        }
        let comentParaFacturador = $('#comentarioParaFacturador').val();        

        if (idAtencion && idAtencion > 0) {

            let formData = new FormData();
            formData.append('idAtencion', idAtencion);
            formData.append('comentario', comentario);
            formData.append('comentarioInterno', comentarioInterno);
            formData.append('facturarPresupuestar', facturarPresupuestar);
            formData.append('comentParaFacturador', comentParaFacturador);                
    
             // Obtener los archivos cargados
            let files = $('#ficheroDetenerAtencion').length > 0 ? $('#ficheroDetenerAtencion')[0].files : [];

            // Validar que si hay archivos cargados, el comentario no esté vacío
            if (files.length > 0 && comentario.trim() === '') {
                $('#msgDetener').text("El comentario es obligatorio si se han cargado archivos.").show().fadeOut(2500);
                return; 
            }
            
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    formData.append('ficheroDetenerAtencion[]', files[i]);
                }
            }
    
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/detenerAtencionIncidencia',
                dataType: "json",
                data: formData,
                processData: false, 
                contentType: false, 
            }).done(function(data){  

                if (data['respuesta'] == 1) {
                    toggleModal('detener-atencion');
                    
                    if (rol == 'tecnico') {
                        recargartablaAjax('tab-misincidencias','listarIncidenciasTecnico');
                        recargartablaAjax('tab-todas','listarTodasLasIncidencias');
                    }else if(rol == 'admin'){
                        recargartablaAjax('contenedorListadoAdmin','listarTodasLasIncidencias');
                    }
                    $('#msgIniciarDetener').text("Se ha detenido la acción con éxito").show().fadeOut(3500);
                    cargarSolicitPorFacturarYPresptarYAceptadasYPptosDespuesCambiarEstado();
                }else if (data['respuesta'] == 0) {
                    $('#msgDetener').text("Error!. No se ha detenido la acción.").show().fadeOut(2500);
                }else if (data['respuesta'] == 2) {
                    $('#msgDetener').text("No tiene permiso para detener la acción.").show().fadeOut(2500);
                }

            });   
        }  
    });
    

    $(document).on('click', '.iniciar', function (e) {
        e.preventDefault();

        var inc = $(this).closest('tr');
        let id = parseInt(inc.find('td:eq(0)').text());
        $('#idIncidenciaPlay').val(id);

        if (id && id >0) {
            toggleModal('iniciar-atencion');
        }        
    });

    $(document).on('click','#iniciarAccion', function (e) {
        e.preventDefault();

        let idIncidencia = $('#idIncidenciaPlay').val();
        let modalidad = $('#modAtencionInicio').attr('option', 'selected').val();       
        let rol = $('#rolUsuarioIniciar').val(); 

        if (idIncidencia && idIncidencia > 0 && modalidad > 0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/iniciarAtencionIncidencia',
                dataType: "json",
                data: { 'idIncidencia':idIncidencia, 'modalidad':modalidad  },
            }).done(function(data){  

                if (data['respuesta'] == 1) {
                    toggleModal('iniciar-atencion');

                    if (rol == 'tecnico') {
                        recargartablaAjax('tab-misincidencias','listarIncidenciasTecnico');   
                        recargartablaAjax('tab-todas','listarTodasLasIncidencias');     
                    }else if(rol == 'admin'){
                        recargartablaAjax('contenedorListadoAdmin','listarTodasLasIncidencias');    
                    }
                    
                    $('#msgIniciarDetener').text("Se ha iniciado la acción con éxito").show().fadeOut(3500);
                }else{
                    $('#msgIniciar').text("Error!. No se ha iniciado la acción.").show().fadeOut(2500);
                }                

            });   
        } else{
            $('#msgIniciar').text("Debe seleccionar una modalidad").show().fadeOut(2500);
        }        
    });

    $(document).on('click', '.cerrarIniciarAtencion', function (e) {
        e.preventDefault();
        toggleModal('iniciar-atencion');          
    });


    function cerrarAlerta(event){
        let element = event.target;
        while(element.nodeName !== "BUTTON"){
          element = element.parentNode;
        }
        element.parentNode.parentNode.removeChild(element.parentNode);
    }


    function toggleModal(modal_id) {
            
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("flex");
        document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

    }

    function toggleModalFinalizarIncidencia(modal_id) {
            
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("block");
        document.getElementById(modal_id + "-backdrop").classList.toggle("block");

    }
      
    function recargartablaAjax(tab,metodo) {
        
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
    
    function activadorTabActivoCliente(event,tabID){
        let element = event.target;
        while(element.nodeName !== "A"){
        element = element.parentNode;
        }
        let ulElement = element.parentNode.parentNode;
        let aElements = ulElement.querySelectorAll("li > a");
        let tabContents = document.getElementById("tabs-id").querySelectorAll(".tab-content > div");
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
        
    $(document).on('click', '.terminar', function (e) {
        e.preventDefault();

        var inc = $(this).closest('tr');
        let idIncidencia = parseInt(inc.find('td:eq(0)').text()); 
        let usuario = inc.find('td:eq(2)').text(); 
        let cliente = inc.find('td:eq(3)').text(); 
        let sucursal = inc.find('td:eq(4)').text(); 
        let equipo = inc.find('td:eq(5)').text(); 

        $('#idIncidenciaFin').val(idIncidencia);
        $('#solicitanteFin').val(usuario);
        $('#clienteFin').val(cliente);
        $('#sucursalFin').val(sucursal);
        $('#equipoFin').val(equipo);
        $('#tituloIdSolicitud').html('¿Está seguro de finalizar la solicitud Nº ' + idIncidencia);             
        
        if (idIncidencia >0 ) {   
                       
            
            $.ajax({
                type: "POST",          
                data: {'idIncidencia':idIncidencia},
                async: false, 
                url: urlCompleta + "/PresupuestosFacturas/construirSelectEstado",            
                dataType: "json",
                success: function (res) {                    
                    $('#estadoFactPpto').html(res.estadosSelect);
                                
                    if (res.estadoFirma && res.estadoFirma.guardada == "1") {
                        
                        console.log('res.estadoFirma.guardada=> ',res.estadoFirma.guardada);

                        $('#firmaContainer').html(`
                            <firma-incidencia                                 
                                firmaGuardada="1"                                 
                                mostrarBotonLimpiar="false" 
                                mostrarBotonGuardar="false"
                                urlFirma="${res.estadoFirma.firma}"
                            >
                            </firma-incidencia>
                        `);
                    } else {
                        
                        $('#firmaContainer').html(`
                            <firma-incidencia                                 
                                firmaGuardada="0"
                                mostrarBotonLimpiar="true" 
                                mostrarBotonGuardar="false">
                            </firma-incidencia>
                        `);
                    }
                }
            }); 
                                          
            toggleModalFinalizarIncidencia('finalizar-incidencia');
            
        }        
    });

    $(document).on('click', '.cerrarFinalizarIncidencia', function (e) {
        e.preventDefault();

        toggleModalFinalizarIncidencia('finalizar-incidencia');

        $('#idIncidenciaFin').val('');
        $('#solicitanteFin').val('');
        $('#clienteFin').val('');
        $('#sucursalFin').val('');
        $('#equipoFin').val('');
        $('#tituloIdSolicitud').html('');
        $('#msgFinalizarIncidencia').html('');
        $('#comentario').val('');
        $('#comentarioInterno').val('');
        $('#bodyModalFinalizarIncidencia')[0].reset();
        
    });    

    $(document).on('click', '#finalizarIncidencia', function (e) {

        e.preventDefault();        
        
        let idIncidencia = $('#idIncidenciaFin').val();
        let comentario =  $('#comentario').val();
        let comentarioInterno =  $('#comentarioInterno').val();
        let rol = $('#rolUsuarioFinalizar').val();
        
        //datos para facturar
        let facturarPresupuestar = '';
        let facturar = $('#estadoFactPpto').val();
        if (facturar != null && facturar != undefined && facturar !='' ) {
            facturarPresupuestar = facturar;
        }
        let comentParaFacturador = $('#comentarioParaFacturadorFinalizar').val();
        
        let formData = new FormData();
        formData.append('idIncidencia', idIncidencia);
        formData.append('comentario', comentario);
        formData.append('comentarioInterno', comentarioInterno);
        formData.append('facturarPresupuestar', facturarPresupuestar);
        formData.append('comentParaFacturador', comentParaFacturador);                

        // Agregar los archivos al FormData
        let files = $('#ficheroCrearIncidencia').length > 0 ? $('#ficheroCrearIncidencia')[0].files : [];

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                formData.append('ficheroCrearIncidencia[]', files[i]);
            }
        }

        // Obtener la firma en base64 desde el componente web
        let firmaComponente = document.querySelector("firma-incidencia");
        let firmaBase64 = firmaComponente ? firmaComponente.obtenerFirmaBase64() : null;
        if (firmaBase64) {
            formData.append('firma', firmaBase64);
        }

        if (idIncidencia >0 && $('#comentario').val().trim() !== '') {        

            toggleModal('modal-loadajax');

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/finalizarIncidencia',
                dataType: "json",
                data: formData,
                processData: false, 
                contentType: false, 
            }).done(function(data){  

                if (data['respuesta'] == 0 || data['respuesta'] == 1 || data['respuesta'] == 2 || data['respuesta'] == 3) {
                    
                    toggleModal('modal-loadajax');

                    if (data['respuesta'] == 1) {
                        toggleModalFinalizarIncidencia('finalizar-incidencia');
                                                            
                        Swal.fire({
                            title: 'Finalización de solicitud',
                            text: 'Se ha finalizado la solicitud Nº ' + idIncidencia ,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });       

                        if (rol == 'tecnico') {
                            recargartablaAjax('tab-misincidencias','listarIncidenciasTecnico');
                            recargartablaAjax('tab-todas','listarTodasLasIncidencias');    
                        }else if(rol == 'admin'){
                            recargartablaAjax('contenedorListadoAdmin','listarTodasLasIncidencias');    
                        }
                                                
                        $('#idIncidenciaFin').val('');
                        $('#solicitanteFin').val('');
                        $('#clienteFin').val('');
                        $('#sucursalFin').val('');
                        $('#equipoFin').val('');
                        $('#tituloIdSolicitud').html('');
                        $('#msgFinalizarIncidencia').html('');
                        $('#comentario').val('');
                        $('#comentarioInterno').val('');

                        cargarSolicitPorFacturarYPresptarYAceptadasYPptosDespuesCambiarEstado();

                    }else if (data['respuesta'] == 0) {
                        $('#msgFinalizarIncidencia').text("Error!. No se ha podido finalizar la incidencia.").show().fadeOut(3500);
                    }else if (data['respuesta'] == 2) {
                        $('#msgFinalizarIncidencia').text("Error!. Debe ingresar un comentario.").show().fadeOut(3500);
                    }else if (data['respuesta'] == 3) {
                        $('#msgFinalizarIncidencia').text(data['mensaje']).show().fadeOut(5500);
                    }               
                }

            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                $('#msgFinalizarIncidencia').text("Hubo un error al intentar finalizar la incidencia. Por favor, inténtelo de nuevo.").show().fadeOut(3000);
            });   

        }else{            
            $('#msgFinalizarIncidencia').text("Debe ingresar un comentario para el cliente").show().fadeOut(3000);
        }
        
    });
    
    $(document).on('click', '.validar', function (e) {
        e.preventDefault();

        var inc = $(this).closest('tr');
        idIncidencia = parseInt(inc.find('td:eq(0)').text()); 
        $('#idIncidenciaVal').val(idIncidencia);
        toggleModal('valoracion-incidencia');      
    });

    $(document).on('click', '.cerrarValoracionIncidencia', function (e) {
        e.preventDefault();
        toggleModal('valoracion-incidencia');   
           
        $('#idIncidenciaVal').val('');
        $('#comentarioCliente').val('');
        $('#valorEstrella').val('');

    }); 

    $('.inputEstrella').on('click', function () {
        valor = $(this).val();
        $('#valorEstrella').val(valor);        
    });

    $('#valorarIncidencia').on('click', function () {
        
        idIncidencia = $('#idIncidenciaVal').val();
        comentario =  $('#comentarioCliente').val();
        valoracion =  $('#valorEstrella').val();

        if (idIncidencia >0) {            

            if ( valoracion =='' || valoracion <= 0) {
                $('#msgValorarIncidencia').text("Por favor seleccione una valoración del 1 al 5").show().fadeOut(3000);
            }else if ( $('#comentarioCliente').val().trim() === ''){
                $('#msgValorarIncidencia').text("Por favor ingrese un comentario").show().fadeOut(3000);
            }else{

                toggleModal('modal-loadajax');       

                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Incidencias/valorarIncidencia',
                    dataType: "json",
                    data: { 'idIncidencia':idIncidencia, 'comentario':comentario, 'valoracion':valoracion },
                }).done(function(data){  
    
                    toggleModal('modal-loadajax');       

                    if (data['respuesta'] == 1) {
                        toggleModal('valoracion-incidencia');
                             
                        $('#idIncidenciaVal').val('');
                        $('#comentarioCliente').val('');
                        $('#valorEstrella').val('');
                                                               
                        Swal.fire({
                            title: 'Valoración de solicitud',
                            text: 'Se ha guardado tu valoración. Muchas gracias.' ,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });       
    
                        location.reload();
                    
                    }else if (data['respuesta'] == 2) {
                        $('#msgValorarIncidencia').text("Error!. Debe realizar la valoración del servicio.").show().fadeOut(3500);
                    }else {
                        $('#msgValorarIncidencia').text("Error!. No se ha podido guardar tu valoración.").show().fadeOut(3500);
                    }
    
                });   
            }            
        }
    });
    
   
    $('#desplegar').click(function (e) {
        e.preventDefault();
        if ($('#formularioSubirFicheroIncidencia').is(':visible')) {
          $('#formularioSubirFicheroIncidencia').slideUp(300);
          $('#desplegar').html('<i class="far fa-image mx-2 text-xl"></i>Agregar fichero');
        } else {
          $('#formularioSubirFicheroIncidencia').slideDown(300);
          $('#desplegar').html('<i class="fas fa-times mx-2 text-base"></i>Cerrar');
        }
    });

        
    $("#formAltaIncidencia").submit(function (event) {

        idsucursal = $('#sucursal').attr('option', 'selected').val();   
        idequipo = $('#equipo').attr('option', 'selected').val();     

        if (!idsucursal || idsucursal ==0 || idsucursal == '' ){              
            $("#msgValidarIncidencia").text("Debe seleccionar una sucursal.").show().fadeOut(3000);  
            event.preventDefault();     
        }else if (!idequipo || idequipo ==0 || idequipo == '' ){              
            $("#msgValidarIncidencia").text("Debe seleccionar un equipo.").show().fadeOut(3000);  
            event.preventDefault();
        }else if ($('#descripcion').val().trim() === '') {          
            $("#msgValidarIncidencia").text("Debe agregar una descripción.").show().fadeOut(3000);  
            event.preventDefault();
        }else if ($('#presupuestarEnCreacion').is(":checked") && $('#comentarioParaPresupuestoCrear').val().trim() === '') {
            $("#msgValidarIncidencia").text("Debe agregar un comentario si ha marcado solictar un presupuesto.").show().fadeOut(3000);  
            event.preventDefault();
        }else{
            $("#pageloader").fadeIn();
        }
                
    });
         
    $("#formAltaIncidenciaTecnico").submit(function (event) {
                        
        
        let idcliente = $('#cliente').attr('option', 'selected').val();   
        let idsucursal = $('#sucursalesTecnico').attr('option', 'selected').val();           
        let equipos = $('#equiposTecnico');
        let descripcion = $('#descripcion').val().trim();               
        
        
        if (!idcliente || idcliente ==0 || idcliente == null ){
            $("#msgValidarIncidencia").text("Debe seleccionar un cliente.").show().fadeOut(3000);  
            event.preventDefault();     
        }else if (!idsucursal || idsucursal ==0 || idsucursal == null ){              
            $("#msgValidarIncidencia").text("Debe seleccionar una sucursal.").show().fadeOut(3000);  
            event.preventDefault();     
        }else if (equipos.val().length == 0){              
            $("#msgValidarIncidencia").text("Debe seleccionar uno o más equipos.").show().fadeOut(3000);  
            event.preventDefault();
        }else if ($('#descripcion').val().trim() === '') {          
            $("#msgValidarIncidencia").text("Debe agregar una descripción.").show().fadeOut(3000);  
            event.preventDefault();     
        }else if (descripcion === '') {          
            $("#msgValidarIncidencia").text("Debe agregar una descripción.").show().fadeOut(3000);  
            event.preventDefault();              
        } else{                          
            $("#pageloader").fadeIn();
        }
                                
    });    

    $(document).on('click','.reasignar', function (e) {
        e.preventDefault();
        var inc = $(this).closest('tr');
        let idIncidencia = parseInt(inc.find('td:eq(0)').text()); 

        if (idIncidencia && idIncidencia >0) {
            $('#idIncidenciaAsign').val(idIncidencia);

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/contruirListadoTecnicosPorIncidencia',
                dataType: "json",
                data: { 'idIncidencia':idIncidencia},
            }).done(function(data){  

                if (data['respuesta'] == 1) {
                    $('#tablaTecnicosIncidencia tbody').html(''); 
                    $('#tablaTecnicosIncidencia tbody').append(data['html']);   
                    toggleModal('reasignar-tecnico');                    
                }               

            }); 

            
        }                    
    });

    $(document).on('click', '.cerrarReasignarTecnico', function (e) {
        e.preventDefault();
        toggleModal('reasignar-tecnico');      
    });    

    $(document).on('click', '.eliminarTecnico', function (e) {
        e.preventDefault();
        var filaTecnico = $(this).closest('tr');        
        filaTecnico.remove();
    });
    $(document).on('change', '#selectTecnicos', function (e) {
        e.preventDefault();
        let idTecnico = $(this).val();
        var nombre = $(this).find("option:selected").text();               

        if (idTecnico >0) {
            var nuevaFila = `<tr class="hover:bg-grey-lighter">
            <td style='width: 20%;' class="p-2 border-b border-grey-light"><input style='width: 70%;' value="${idTecnico}" name="idsTecSel[]" class="inputKeyTecnico"></td>
            <td class="p-2 border-b border-grey-light">${nombre}</td>
            <td style='width: 30%;' class="p-2 border-b border-grey-light"><a href="" class="eliminarTecnico"><i class="fas fa-user-minus" style="color:red;"></i></a></td>
            </tr>`;

            $('#tablaTecnicosIncidencia tbody').append(nuevaFila);
        }                
        $('select[name="selectTecnicos"]').val('');
        
    });

    $('#asignarNuevosTecnicos').on('click', function (e) {
        e.preventDefault();
        let idIncidenciaAsign = $('#idIncidenciaAsign').val();
        let rol = $('#nombreRolUsuarioReabrir').val();         
        
        //validacion antes de enviar a PHP
        let arrFilasArt = [];
        $('.inputKeyTecnico').each(function () {
            var filaArt = $(this).val();
            if (filaArt) {
                arrFilasArt.push(filaArt);
            }
        });       

        var x = arrFilasArt;
        var uniqs = x.filter(function(item, index, array) {
            return array.indexOf(item) === index;
        })

        var nFilas = $("#tablaTecnicosIncidencia tbody tr").length;

        if (arrFilasArt.length > uniqs.length) {            
            $("#msgValorar").text(`Existen técnicos asignados duplicados. Verifique antes de continuar`).show().fadeOut(3000);              
        }else if (nFilas == 0){
            $("#msgValorar").text(`No ha asignad ningún técnico. Verifique antes de continuar`).show().fadeOut(3000);              
        }else {                     

           toggleModal('modal-loadajax');    

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/reasignarTecnicos',
                dataType: "json",
                data: { 'idIncidencia':idIncidenciaAsign, 'nuevos':arrFilasArt},
            }).done(function(data){  

                toggleModal('modal-loadajax');      

                if (data['respuesta'] == 1) {                    
                    toggleModal('reasignar-tecnico');    
                    Swal.fire({
                        title: 'Reasignación de técnicos',
                        text: 'Se ha reasignado la solicitud corréctamente.' ,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });          
                    //recargartablaAjax('contenedorListadoAdmin','listarTodasLasIncidencias');                                                               

                    //////////////////////
                    
                    if (rol == 'tecnico') {
                        recargartablaAjax('tab-misincidencias','listarIncidenciasTecnico');
                        recargartablaAjax('tab-todas','listarTodasLasIncidencias');
                    }else if(rol == 'admin'){
                        recargartablaAjax('contenedorListadoAdmin','listarTodasLasIncidencias');
                    }

                    //////////////////////
                }else {
                    $('#msgValorar').text("Error!. No se ha podido reasignar la incidencia.").show().fadeOut(3500);
                }             

            }).fail(function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status === 302) {
                    alert('Error 302. redirect');
                } else {
                    console.log("Error:", textStatus, errorThrown, jqXHR.responseText);
                    $('#msgValorar').text("Error en la solicitud. Intente de nuevo.").show().fadeOut(3500);
                }
            });
            
        }


    });


    
    $(document).on('change', '#sucursalesTecnico', function () {
            
        var idSucursal = $(this).attr('option', 'selected').val();        
        
        if (idSucursal > 0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/llenarSelectorEquiposPorSucursal',
                dataType: "json",
                data: { 'idSucursal':idSucursal },
            }).done(function(data){

                $('#equiposTecnico').html('');     
                if (data['options'] != '') {                       
                    $('#contenedorEquipos').html(data['options']); 
                     
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

    //SLIDER IMÁGENES

    $(document).on('click','.verImagen', function (e) {
        e.preventDefault();
        idFichero = $(this).data('idfichero');
        
       
        $("#imagenIncidencia").attr("src",urlCompleta+'/public/img/load-spinner.gif');

        if (idFichero > 0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/buscarImagenIncidencia',
                dataType: "json",
                data: { 'idFichero':idFichero },
            }).done(function(data){
              
                if (data['base'] != '') {
                    $("#imagenIncidencia").attr("src",data['base']);
                }          
            });   
        }

    });

    //agregar comentarios entre técnicos
    $(document).on('click','#addComentario', function (e) {
        e.preventDefault();
        toggleModal('agregar-comentario');
    });

    $(document).on('click', '.cerrarAgregarComentario', function (e) {
        e.preventDefault();
        $('#comentarioIntAdd').val('');  
        $('input[name="comentarioTipo"][value="interno"]').prop('checked', true);
        toggleModal('agregar-comentario');         
    });

    $('#guardarComentario').on('click', function (e) {
        e.preventDefault();

        let comentario = $('#comentarioIntAdd').val();
        let idIncidencia = $('#idIncidenciaVer').val();
        let idEquipo = $('#idEquipo').val();
        let tipoComentario = $('input[name="comentarioTipo"]:checked').val();

        if ($('#comentarioIntAdd').val().trim() !== '') {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/guardarComentarioInterno',
                dataType: "json",
                data: { 'comentario':comentario, 'idIncidencia':idIncidencia, 'idEquipo':idEquipo, 'tipoComentario': tipoComentario },
            }).done(function(data){
                if (data['respuesta'] == 1) {
                    toggleModal('agregar-comentario');
                    $('#contenedorComentarios').prepend(data['html']);
                    $('#comentarioIntAdd').val('');  
                }
                
            }); 
        }
    });

    //agregar comentarios cel cliente
    $(document).on('click','#addComentarioCliente', function (e) {
        e.preventDefault();
        toggleModal('agregar-comentarioCliente');
    });
    $(document).on('click', '.cerrarAgregarComentarioCliente', function (e) {
        e.preventDefault();
        $('#comentarioIntAddCliente').val('');  
        toggleModal('agregar-comentarioCliente');         
    });
    $('#guardarComentarioCliente').on('click', function (e) {
        e.preventDefault();

        comentario = $('#comentarioIntAddCliente').val();
        idIncidencia = $('#idIncidenciaVer').val();
        idEquipo = $('#idEquipo').val();        


        if ($('#comentarioIntAddCliente').val().trim() !== '') {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/guardarComentarioDelCliente',
                dataType: "json",
                data: { 'comentario':comentario, 'idIncidencia':idIncidencia, 'idEquipo':idEquipo },
            }).done(function(data){
                if (data['respuesta'] == 1) {
                    toggleModal('agregar-comentarioCliente');
                    $('#contenedorComentarios').prepend(data['html']);
                    $('#comentarioIntAddCliente').val('');  
                }
                
            }); 
        }
    });

    var idIncidenciaDel;
    $(document).on('click', '.eliminar',function (e) {
        e.preventDefault();

        idIncidenciaDel = $(this).closest('tr');        
        let idIncidencia = parseInt(idIncidenciaDel.find('td:eq(0)').text());   
        
        $('#idIncidenciaDel').val(idIncidencia);         

        if (idIncidencia > 0) {
            toggleModal('eliminar-incidencia');      
        }

    });
    

    $(document).on('click', '.cerrarModalEliminarIncidencia', function (e) {
        e.preventDefault();
        toggleModal('eliminar-incidencia');
    });

    $(document).on('click', '#eliminarIncidencia', function (e) {
        e.preventDefault();            
        let idIncidencia = $('#idIncidenciaDel').val();
                    
        $.ajax({
            type: 'POST',
            url: urlCompleta + '/Incidencias/eliminarIncidencias',
            dataType: "json",
            data: {'idIncidencia':idIncidencia}
        }).done(function(data){

            if (data == 1) {
                idIncidenciaDel.remove();
                toggleModal('eliminar-incidencia');
                Swal.fire({
                    title: 'Eliminación incidencia',
                    text: 'Se ha eliminado la incidencia corréctamente',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }); 
            }else{
                Swal.fire({
                    title: 'Error',
                    text: 'Ha ocurrido un error y no se ha podido eliminar la incidencia.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }); 
            }
        });            
    });

    //reabrir una incidencia    
    $(document).on('click','.reabrir', function (e) {
        e.preventDefault();
        var inc = $(this).closest('tr');
        let idIncidencia = parseInt(inc.find('td:eq(0)').text()); 

        if (idIncidencia && idIncidencia >0) {
            $('#idIncidenciaAbrir').val(idIncidencia);
            $('#numIncidencia').html(idIncidencia);
            toggleModal('reabrir-incidencia');
        }                    
    });

    $(document).on('click', '.cerrarReabrirIncidencia', function (e) {
        e.preventDefault();
        toggleModal('reabrir-incidencia');      
    });

    $('#reabrirIncidencia').on('click', function (e) {
        e.preventDefault();
        toggleModal('modal-loadajax')
        let idIncidencia = $('#idIncidenciaAbrir').val();
        let rol = $('#nombreRolUsuarioReabrir').val();

        $.ajax({
            type: 'POST',
            url: urlCompleta + '/Incidencias/reabrirIncidencia',
            dataType: "json",
            data: { 'idIncidencia':idIncidencia},
        }).done(function(data){               

            if (data == 1) {   
                toggleModal('modal-loadajax');                    
                toggleModal('reabrir-incidencia');   
                Swal.fire({
                    title: 'Reabrir solicitud',
                    text: 'Se ha reabierto la solicitud corréctamente.' ,
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });          
                //recargartablaAjax('contenedorListadoAdmin','listarTodasLasIncidencias');
                                    
                if (rol == 'tecnico') {
                    recargartablaAjax('tab-misincidencias','listarIncidenciasTecnico');
                    recargartablaAjax('tab-todas','listarTodasLasIncidencias');
                }else if(rol == 'admin'){
                    recargartablaAjax('contenedorListadoAdmin','listarTodasLasIncidencias');
                }


            }else {
                toggleModal('modal-loadajax');   
                $('#msgReabrir').text("Error!. No se ha podido reabrir la solicitud.").show().fadeOut(3500);
            }             

        }); 
    });

    $(document).on('click', '.rechazarIncidencia', function (e) {
        e.preventDefault();

        let inc = $(this).closest('tr');
        let idIncidencia = parseInt(inc.find('td:eq(0)').text());        

        $('#idIncidenciaRechz').val(idIncidencia);
        $('#tituloRechazar').html('¿Está seguro de rechazar la solicitud Nº ' + idIncidencia);             
        
        if (idIncidencia >0 ) {   
                       
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/contruirSelectTecnicosDisponibles',
                dataType: "json",
                data: { 'idIncidencia':idIncidencia},
            }).done(function(data){  
                if (data['respuesta'] == 1) {                    
                    $('#selectTecnicosRechz').html(data['html']);                    
                }
                toggleModal('rechazar-incidencia');
            });                         
        }        
    });

    $(document).on('click', '.cerrarRechazarIncidencia', function (e) {
        e.preventDefault();
        $('#selectTecnicosRechz').html('');
        $('#tablaTecnicosReasignado tbody').html('');
        toggleModal('rechazar-incidencia');
    });

    $('#selectTecnicosRechz').on('change', function () {
        let idTecnico = $(this).attr('option', 'selected').val();       
        let nombreTecnico = $('select[name="selectTecnicosRechz"] option:selected').text();

        if (idTecnico != '' ) {
            html = `<tr class='hover:bg-grey-lighter'>
            <td style='width: 20%;display:none;' class='p-2 border-b border-grey-light'><input style='width: 70%;' value='${idTecnico}' id="idTecnicoNuevo"></td>
            <td class='p-2 border-b border-grey-light'>${nombreTecnico}</td>
            <td style='width: 30%;' class='p-2 border-b border-grey-light'><a cursor="pointer" class='eliminarTecnicoNuevo'><i class='fas fa-user-minus' style='color:red;'></i></a></td>
            </tr>`;

            $('#tablaTecnicosReasignado tbody').html('');
            $('#tablaTecnicosReasignado tbody').append(html);
            $(this).attr('option', 'selected').val(0);       
        }        
    });

    $(document).on('click', '.eliminarTecnicoNuevo', function () {
        let filaTecnico = $(this).closest('tr');        
        filaTecnico.remove();
    });

    $('#rechazarIncidencia').on('click', function () {        

        let nuevoTecnico = $('#idTecnicoNuevo').val();
        let idIncidencia = $('#idIncidenciaRechz').val();

        if (idIncidencia != '') {
            toggleModal('modal-loadajax2');
            
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/tecnicoRechazaIncidenciaYReasigna',
                dataType: "json",
                data: { 'idIncidencia':idIncidencia, 'nuevoTecnico': nuevoTecnico },
            }).done(function(data){  
                
                toggleModal('modal-loadajax2'); 

                if (data['respuesta'] == 1) {    
                    $('#selectTecnicosRechz').html('');
                    $('#tablaTecnicosReasignado tbody').html('');    
                    
                    recargartablaAjax('tab-misincidencias','listarIncidenciasTecnico');   
                    toggleModal('rechazar-incidencia');
                    Swal.fire({
                        title: 'Rechazo de solicitud',
                        text: 'Se ha rechazado corréctamente la solicitud '+idIncidencia,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                }else{
                    Swal.fire({
                        title: 'Error',
                        text: 'No se ha podido rechazar la solicituds.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            });           
        }
    });

    
   /*  $(document).on('click', '#boton_buscar_cliente', function () {
        // Inicializar el select2 solo si no se ha inicializado previamente
        if (!$('#selectTarea').hasClass('select2-hidden-accessible')) {
            $('#selectTarea').select2({
                allowClear: true,
                minimumInputLength: 3,
                language: {
                    inputTooShort: function () {
                        return "Por favor, ingrese 3 o más caracteres";
                    }
                },
                ajax: {
                    url: urlCompleta + '/Clientes/buscarCliente',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term // El término de búsqueda que ingresa el usuario
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return { id: obj.id, text: obj.nombre, cif: obj.cif, cuentas:obj.cuentas };
                            })
                        };
                    },
                    cache: true
                }
            });
        }
    
        // Mostrar el modal después de que el select2 se haya inicializado correctamente
        toggleModal('buscar-cliente');
    }); */

            
    function llenarSucursales(idCliente) {
        if (idCliente > 0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/llenarSelectorSucursalesParaTecnico',
                dataType: "json",
                data: { 'idCliente': idCliente },
                success: function(data) {
                    $('#sucursalesTecnico').html('');     
                    if (data['options']) {                                         
                        $('#sucursalesTecnico').html(data['options']); 
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error al obtener las sucursales:", textStatus, errorThrown);
                }
            });   
        }
    }    
    
    /* $(document).on('select2:select', '#selectTarea', function (e) {
        var data = e.params.data;     
        $('#sucursalesTecnico').html('');     
        if (data.id > 0) {
            $('#cliente').empty();
            $('#cliente').append($('<option>', {
                value: data.id,
                text: data.text
            })).trigger('change');
            llenarSucursales(data.id);               
         
        }
        $('#selectTarea').val(null).trigger('change');
        toggleModal('buscar-cliente');
    }); */


    /* $(document).on('click', '.cerrarBuscadorClientes', function (e) {
        e.preventDefault();        
        toggleModal('buscar-cliente');
    }); */

    

    //VALIDACIÓN PARA TAMAÑO DE FICHEROS
    const MAXIMO_TAMANIO_BYTES = 6000000; // 1MB = 1 millón de bytes

    // Obtener referencia al elemento
    const $miInput = document.querySelector(".inputFichero");

    if ($miInput) {
        $miInput.addEventListener("change", function () {
            // si no hay archivos, regresamos
            if (this.files.length <= 0) return;
    
            var totalSize = 0;
            const tamanioEnMb = MAXIMO_TAMANIO_BYTES / 1000000;

            for (let index = 0; index < this.files.length; index++) {       
    
                const archivo = this.files[index];
    
                var nombreFichero = archivo.name;
                var extension = (nombreFichero.substring(nombreFichero.lastIndexOf('.') + 1)).toLowerCase();
    
                var extensionesArr = new Array("jpeg", "jpg", "png", "gif", "bmp", "svg", "doc", "docx", "docm", "xlsx", "xlsm", "pptx", "pptm", "csv", "pdf", "xls", "mp3", "wav", "ogg", "mp4");             
                            
                if (archivo.size > MAXIMO_TAMANIO_BYTES) {
                    
                    $("#msgValidaFichero").text(`El tamaño máximo de cada archivo no debe superar ${tamanioEnMb} MB`).show().fadeOut(4000);  
                    
                    // Limpiar
                    $miInput.value = "";
                }else if(extensionesArr.includes(extension) == false ){                                
                    $("#msgValidaFichero").text('La extensiónx '+extension+' del fichero no está permitida, pruebe con otro fichero.').show().fadeOut(4000); 
                    // Limpiar
                    $miInput.value = ""; 
                }else {
                    // Validación pasada. Envía el formulario
                    totalSize += archivo.size;
                }
            }

            if(totalSize > MAXIMO_TAMANIO_BYTES){
                console.log('totalSize=> ', totalSize)
                $("#msgValidaFichero").text(`El tamaño máximo de todos los archivos no debe superar ${tamanioEnMb} MB`).show().fadeOut(4000);                      
                // Limpiar
                $miInput.value = "";
            }
            
        });
    
    }

    
   
    let generar_pdf = document.getElementById('exportpdpf');
    if(generar_pdf){
        generar_pdf.addEventListener('click', function () {
        
        let idIncidencia = document.getElementById('idIncidenciaVer').value;
        
        let ruta = urlCompleta+'/Incidencias/enviarIdIncidenciaGenerarPdf';       
        let params = {'id' : idIncidencia};               
        let fetch=new DB(ruta, 'POST').get(params);

        fetch.then((respuesta => {   
            if(respuesta.error == false){
                window.open(urlCompleta + "/Incidencias/exportarPdfIncidencia");
            }else{                
                Swal.fire({
                    title: 'Error',
                    text: respuesta.mensaje,
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });             
            }
        }));

        
      });       
    }
       
    
    $(document).ready(function () {

        // Inicializar el select2 directamente en el select "cliente"
        $('#cliente').select2({
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
                data: function(params) {
                    return {                                                
                        query: params.term || '',
                        cargarIniciales: !params.term 
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            return { 
                                id: obj.id, 
                                text: obj.nombre, 
                                cif: obj.cif, 
                                cuentas: obj.cuentas 
                            };
                        })
                    };
                },
                cache: true
            }
        })
        // Cuando se seleccione un cliente, llenar sucursales
        $('#cliente').on('select2:select', function (e) {
            var data = e.params.data;
            if (data.id > 0) {
                llenarSucursales(data.id); // Llamada a la función para cargar las sucursales
                 // Limpiar select de equipos
                $('#equiposTecnico').empty();
                
                // Si estás usando tail.select para equipos, destruirlo y reinicializarlo vacío
                if(tail && tail.select) {
                    tail.select('#equiposTecnico').remove();
                    tail.select('.todos',{
                        search: true,
                        locale: "es",
                        multiSelectAll: true,
                        searchMinLength: 0,
                        multiContainer: false,
                        multiple: true
                    });
                }
            }
        });

        $('#cliente').on('select2:clear', function (e) {
            // Limpiar select de sucursales
            $('#sucursalesTecnico').html('<option disabled selected>Seleccionar</option>');
            
            // Limpiar select de equipos
            $('#equiposTecnico').empty();
            
            // Si estás usando tail.select para equipos, destruirlo y reinicializarlo vacío
            if(tail && tail.select) {
                tail.select('#equiposTecnico').remove();
                tail.select('.todos',{
                    search: true,
                    locale: "es",
                    multiSelectAll: true,
                    searchMinLength: 0,
                    multiContainer: false,
                    multiple: true
                });
            }
        });
    });



}

