if(window.location.pathname.includes('/CostesTecnicos')){

    urlCompleta = $('#ruta').val();

    $('#nuevoCosteTecnico').on('click', function (e) {
        e.preventDefault();

        toggleModalCostesTecnicos('crear-CosteTecnico');

    });

    $(document).on('click', '.cerrarCrearCosteTecnico', function (e) {
        e.preventDefault();        
        toggleModalCostesTecnicos('crear-CosteTecnico');    
    });

    $('#crearCostes').on('click', function (e) {

        var mesInicio = $('#mesInicio').attr('option', 'selected').val();
        var anioInicio = $('#anioInicio').attr('option', 'selected').val();
        var mesFin = $('#mesFin').attr('option', 'selected').val();
        var anioFin = $('#anioFin').attr('option', 'selected').val();

        var idTecnicoCrear = $('#idTecnicoCrear').attr('option', 'selected').val();
        
        var costeHoras = $('#costeHoras').val();
              
        if (idTecnicoCrear =='' || idTecnicoCrear == null) {
            $("#mensajeValidacion").text("Debe seleccionar un técnico.").show().fadeOut(3000);  
            e.preventDefault();
        }else if(costeHoras =='' || costeHoras ==0){
            $("#mensajeValidacion").text("Debe ingresar el coste.").show().fadeOut(3000); 
            e.preventDefault();
        }else if (parseInt(anioFin) == parseInt(anioInicio) && parseInt(mesInicio) > parseInt(mesFin)){
            $("#mensajeValidacion").text("El mes inicial no puede ser mayor que el final").show().fadeOut(3000);
            e.preventDefault();
        }else if(mesInicio ==null || mesInicio=='' || mesInicio==0){
            $("#mensajeValidacion").text("Complete mes inicio").show().fadeOut(3000);
            e.preventDefault();
        }else if(mesFin ==null || mesFin=='' || mesFin==0){
            $("#mensajeValidacion").text("Complete mes fin").show().fadeOut(3000);
            e.preventDefault();
        
        }else if(parseInt(anioInicio) > parseInt(anioFin)){
            $("#mensajeValidacion").text("El año inicial no puede ser mayor que el final").show().fadeOut(3000);
            e.preventDefault();
        }else{
            $('#bodyModalCrearCosteTecnico').submit();
        }              

    });

    $(document).on('click', '.editar' ,function (e) {
        e.preventDefault();

        fila = $(this).closest('tr');
        idCoste = parseInt(fila.find('td:eq(0)').text());    

        if (idCoste > 0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/CostesTecnicos/editarCosteTecnico',
                dataType: "json",
                data: { 'idCoste':idCoste },
            }).done(function(data){   
                
                if (data['coste']) {  
                    $('#nombreTecnico').val(data['coste'].nombre +' '+ data['coste'].apellidos);
                    $('#mesCosteHoraEditar').val(data['coste'].mes);
                    $('#anioBolsaHorasEditar').val(data['coste'].anio);
                    $('#costeHorasEditar').val(data['coste'].costehora);
                    $('#idEditCoste').val(data['coste'].id);
                    toggleModalCostesTecnicos('editar-CosteTecnico');    
                }
                
            });   
        }
        
    });

    
    $(document).on('click', '.cerrarEditarCosteTecnico', function (e) {
        e.preventDefault();        
        toggleModalCostesTecnicos('editar-CosteTecnico');   
    });
 
    

    $(document).on('click', '.eliminar' ,function (e) {
        e.preventDefault();

        fila = $(this).closest('tr');
        idCoste = parseInt(fila.find('td:eq(0)').text());
        
        nombre = fila.find('td:eq(2)').text();   
        apellidos = fila.find('td:eq(3)').text();   

        if (idCoste > 0) {
            
                if (idCoste) {  
                    $('#idCosteDel').val(idCoste);
                    $('#datosCoste').val(nombre +' '+ apellidos);
                    toggleModalCostesTecnicos('delete-costeTecnico');    
                }
               
        }
        
    });

    $(document).on('click', '.cerraModDeleteCosteTecnico', function (e) {
        e.preventDefault();        
        toggleModalCostesTecnicos('delete-costeTecnico');       
    });
 

    function toggleModalCostesTecnicos(modal_id) {
            
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("flex");
        document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

    }

    
    $(document).on('click', '.butonCerrarAlerta', function (event) {
        cerrarAlerta(event);
    })

    function cerrarAlerta(event){
        let element = event.target;
        while(element.nodeName !== "BUTTON"){
          element = element.parentNode;
        }
        element.parentNode.parentNode.removeChild(element.parentNode);
    }


}


