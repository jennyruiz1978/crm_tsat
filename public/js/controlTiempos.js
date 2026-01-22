if(window.location.pathname.includes('/Incidencias')){   
    
    

    function toggleModalControl(modal_id) {
            
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("flex");
        document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

    }


    $(document).on('click', '.historial', function (e) {
        e.preventDefault();        
        
        var filaInc = $(this).closest('tr');        
        idIncidencia = parseInt(filaInc.find('td:eq(0)').text()); 
        equipo = filaInc.find('td:eq(5)').text();  
        
        
        if (idIncidencia >0) {
            
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/contruirHistorialTiempoPorIncidencia',
                dataType: "json",
                data: { 'idIncidencia':idIncidencia},
            }).done(function(data){  

                if (data['respuesta'] == 1 && data['html'] !='') {
                    $('#tablaHistorialTiempos').html(''); 
                    $('#tablaHistorialTiempos').html(data['html']);                         
                }else{
                    $('#tablaHistorialTiempos').html(''); 
                    $('#tablaHistorialTiempos').html('<div>No hay datos para mostrar</div>');
                }
                toggleModalControl('historial-tiempos');    
            }); 
        }
    });

    $(document).on('click', '.cerrarHistorialTiempos', function (e) {
        e.preventDefault();
        $('#tablaHistorialTiempos tbody').html('');        
        toggleModalControl('historial-tiempos');
    });   

    var filaAtencionDel;

    $(document).on('click','.eliminarTiempo',function (e) {
        e.preventDefault();

        var idAtencion = $(this).data('keydel');
        filaAtencionDel = $(this).closest('tr');        

        if (idAtencion >0) {
            $('#idAtencionDel').val(idAtencion);
            toggleModalControl('eliminar-atencion');
        }

    });

    $(document).on('click', '#enviarParaEliminar', function (e) {
       
            let rol = $('#rolUsuarioDelAtt').val(); 
            let idAtencion = $('#idAtencionDel').val();

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/eliminarAtencion',
                dataType: "json",
                data: { 'idAtencion':idAtencion},
            }).done(function(data){  

                toggleModalControl('eliminar-atencion');

                if (data['respuesta'] == 1) {
                    
                    filaAtencionDel.remove();
                    
                    if (rol == 'tecnico') {
                        recargartablaAjaxCT('tab-misincidencias','listarIncidenciasTecnico');    
                    }else if(rol == 'admin'){
                        recargartablaAjaxCT('contenedorListadoAdmin','listarTodasLasIncidencias');    
                    }
                    
                    Swal.fire({
                        title: 'Eliminación de tiempos',
                        text: 'Se han eliminado los datos corréctamente',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });                 
                                        
                }else{          
 
                    Swal.fire({
                        title: 'Error',
                        text: 'No se puede eliminar el registro.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }); 
                }                                                
            });               
    });

    function recargartablaAjaxCT(tab,metodo) {
        
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

    $(document).on('click', '.cerrarEliminarAtencion', function (e) {
        e.preventDefault();        
        toggleModalControl('eliminar-atencion');     
    });


    $(document).on('click', '.actualizarTiempo', function (e) {
        e.preventDefault();
        idAtencion = $(this).data('keyupd');

        if (idAtencion >0) {
            fechaInicio = $('#fechaIni_'+idAtencion).val();
            horaInicio = $('#horaIni_'+idAtencion).val();
            fechaFin = $('#fechaFin_'+idAtencion).val();
            horaFin = $('#horaFin_'+idAtencion).val();        

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Incidencias/actualizarFechasYHorasAtencion',
                dataType: "json",
                data: { 'idAtencion':idAtencion, 'fechaInicio':fechaInicio, 'horaInicio':horaInicio, 'fechaFin':fechaFin, 'horaFin':horaFin},
            }).done(function(data){
                if (data['respuesta'] == 1) {
                    
                    Swal.fire({
                        title: 'Actualización de tiempos',
                        text: 'Se han actualizado los datos corréctamente',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });                 
                    
                    $('#tablaHistorialTiempos').html('');                        
                    $('#tablaHistorialTiempos').html(data['html']);     

                }else if(data['respuesta'] == 2){                    
                    Swal.fire({
                        title: 'Error',
                        text: 'No tiene permiso para modifcar esta atención.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });                      
                }else{          
                    $('#tablaHistorialTiempos').html('');                        
                    $('#tablaHistorialTiempos').html(data['html']);              
                    Swal.fire({
                        title: 'Error',
                        text: 'No se puede actualizar el registro.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }); 
                }                 

            }); 

        }else{
            Swal.fire({
                title: 'Error',
                text: 'No se puede actualizar el registro.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });             
        }
        


    });   
        
    $(document).on('click', '#verBolsaHoras', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: urlCompleta + '/Incidencias/verHistorialHorasContratadas',
            dataType: "json",                
        }).done(function(data){
            if (data['respuesta'] == 1) {
                toggleModalControl('bolsa-horas');
                         
                $('#contenedorTablaBolsaHoras').html('');                        
                $('#contenedorTablaBolsaHoras').html(data['tabla']);                    
            }else{
                
            }                    
        });      

    });

    $(document).on('click', '.cerrarVerBolsaHoras', function (e) {
        e.preventDefault();
        
        $('#contenedorTablaBolsaHoras').html('');        
        toggleModalControl('bolsa-horas');
    });

       
         
        
}


