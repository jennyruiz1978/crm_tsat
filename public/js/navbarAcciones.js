if(window.location.pathname.includes('/')){ 

    urlCompleta = $('#ruta').val();

    //ALERTAS DE INCIDENCIAS PENDIENTES
    let id = $('#idUser').val()  

    myFunction(id);
    function myFunction(id) {
        $.ajax({
            type: "POST",          
            data: {'id':id},
            //async: false, //necesario                
            url: urlCompleta + "/Incidencias/numeroNotificacionesIncidencias",            
            dataType: "json",
            success: function (res) {                                    
                $('#incidenciasPendientes').html(res);                            
            }
        });
    }    

    $('#notificationsBtn').on('click', function (e) {
        
        $.ajax({
            type: "POST",          
            data: {'id':id},
            //async: false, //necesario                
            url: urlCompleta + "/Incidencias/mostrarListadoIncidenciasPendientes",            
            dataType: "json",
            success: function (res) {                 
                if (res['respuesta'] == 1) {
                    $('#nohaypendientes').html('');
                    $('#tablaIncidenciasPendientes tbody').html('');
                    $('#tablaIncidenciasPendientes tbody').html(res['html']);    
                }else{
                    $('#nohaypendientes').html('No hay incdencias pendientes');
                }
                                            
            }
        });
    });

    //para ocultar o mostrar contraseña
    const passToggle = document.querySelector('.js-pass-toggles')

    if (passToggle) {
            
        passToggle.addEventListener('change', function() {
            
            const pass = document.querySelector('.js-pass'),
            passLabel = document.querySelector('.js-pass-label')

            if (pass.type === 'password') {
                pass.type = 'text'
                passLabel.innerHTML = "<i class='far fa-eye-slash texto-violeta-oscuro text-base xl:text-xl'></i>";
            } else {
                pass.type = 'password'
                passLabel.innerHTML = "<i class='far fa-eye texto-violeta-oscuro text-base xl:text-xl'></i>";
            }

            pass.focus()
        })
    }

    
    //ACTUALIZAR LAS VARIABLES DE SESSION
    var refreshTime = 300000; // 5min in milliseconds
    window.setInterval( function() {
        $.ajax({
            cache: false,
            type: "POST",
            url: urlCompleta + "/Inicio/refrescarSesion",  
            success: function(data) {
                
            }
        });
    }, refreshTime );
    

    //ALERTAS DE NUEVOS COMENTARIOS NO LEIDOS      
    function cargarComentariosNuevosSinLeer() {
        $.ajax({
            type: "POST",                           
            url: urlCompleta + "/Incidencias/numeroComentariosNuevosSinLeer",            
            dataType: "json",
            success: function (res) {                                    
                $('#comentariosNoLeidos').html(res);                            
            }
        });
    }    
    cargarComentariosNuevosSinLeer();

    $('#comentariosBtn').on('click', function (e) {
        
        $.ajax({
            type: "POST",                           
            url: urlCompleta + "/Incidencias/mostrarListadoComentariosNoLeidos",            
            dataType: "json",
            success: function (res) {
                if (res['respuesta'] == 1) {
                    $('#comentariosinleer').html('');
                    $('#tablaComentariosNoLeidos tbody').html('');
                    $('#tablaComentariosNoLeidos tbody').html(res['html']);    
                }else{
                    $('#comentariosinleer').html('No hay comentarios nuevos');
                }
                                            
            }
        });
    });

    var refreshTime = 300000; // 5min in milliseconds
    window.setInterval( function() {
        $.ajax({
            cache: false,
            type: "POST",
            url: urlCompleta + "/Incidencias/numeroComentariosNuevosSinLeer",  
            success: function(data) {
                $('#comentariosNoLeidos').html(data);  
            }
        });
    }, refreshTime );
    

    //ALERTAS SOLICITUDES POR FACTURAR
    function cargarSolicitPorFacturarYPresptarYAceptadasYPptos() {
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
    cargarSolicitPorFacturarYPresptarYAceptadasYPptos();

    $('#verFacturarBtn').on('click', function (e) {
        
        let estado = $(this).data('estado');
        $.ajax({
            type: "POST",                           
            url: urlCompleta + "/PresupuestosFacturas/mostrarListadoSolicitudesPorEstado",            
            dataType: "json",
            data: {'estado':estado},
            success: function (res) {
                if (res['respuesta'] == 1) {
                    $('#nohayporfacturar').html('');
                    $('#tablaIncidenciasPorFacturar tbody').html('');
                    $('#tablaIncidenciasPorFacturar tbody').html(res['html']);    
                }else{
                    $('#nohayporfacturar').html('No hay solicitudes por facturar');
                }
                                            
            }
        });
    });
    
    $('#verPresupuestarBtn').on('click', function (e) {        
        let estado = $(this).data('estado');
        $.ajax({
            type: "POST",                           
            url: urlCompleta + "/PresupuestosFacturas/mostrarListadoSolicitudesPorEstado",            
            dataType: "json",
            data: {'estado':estado},
            success: function (res) {
                if (res['respuesta'] == 1) {
                    $('#nohayporPresupuestar').html('');
                    $('#tablaIncidenciasPorPresupuestar tbody').html('');
                    $('#tablaIncidenciasPorPresupuestar tbody').html(res['html']);    
                }else{
                    $('#nohayporPresupuestar').html('No hay solicitudes por facturar');
                }
                                            
            }
        });
    });

    $('#verAceptadosBtn').on('click', function (e) {        
        let estado = $(this).data('estado');
        $.ajax({
            type: "POST",                           
            url: urlCompleta + "/PresupuestosFacturas/mostrarListadoSolicitudesPorEstado",            
            dataType: "json",
            data: {'estado':estado},
            success: function (res) {
                if (res['respuesta'] == 1) {
                    $('#nohayporAceptados').html('');
                    $('#tablaPptosAceptados tbody').html('');
                    $('#tablaPptosAceptados tbody').html(res['html']);    
                }else{
                    $('#nohayporAceptados').html('No hay solicitudes por facturar');
                }
                                            
            }
        });
    });
}