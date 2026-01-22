if(window.location.pathname.includes('/TiposIva')){   
    
    function toggleModalIva(modal_id) {
            
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("flex");
        document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

    }

    $(document).on('click', '#nuevoTipoIva', function (e) {
        e.preventDefault();
        toggleModalIva('crear-tipoiva');        
    });

    $(document).on('click', '.cerrarModalCrearTipoIva', function (e) {
        e.preventDefault();
        $('#modalidad').val('');
        toggleModalIva('crear-tipoiva');
    });    

    $(document).on('click', '.editar', function (e) {
        e.preventDefault();
        var filaTipoIva = $(this).closest('tr');        
        id = parseInt(filaTipoIva.find('td:eq(0)').text()); 
        tipoiva = filaTipoIva.find('td:eq(1)').text(); 

        //$('#idTipoIvaEdit').val(id);
        $('#formEditarTipoIva #id').val(id);
        $('#tipoIvaEdit').val(tipoiva);
        toggleModalIva('editar-modalidad');
    });

    $(document).on('click', '.cerrarModalEditarModalidad', function (e) {
        e.preventDefault();
        //$('#idTipoIvaEdit').val('');
        $('#formEditarTipoIva #id').val('');
        $('#tipoIvaEdit').val('');
        toggleModalIva('editar-modalidad');
    });



    $(document).on('click', '.eliminar', function (e) {
        e.preventDefault();
        var filaTipoIva = $(this).closest('tr');        
        id = parseInt(filaTipoIva.find('td:eq(0)').text()); 
        tipoIva = filaTipoIva.find('td:eq(1)').text(); 

        $('#formEliminarTipoIva #id').val(id);
        $('#preguntaEliminar').html('¿Está seguro(a) de eliminar el tipo IVA '+ tipoIva +'% ?');
        toggleModalIva('eliminar-modalidad');
    });

    $(document).on('click', '.cerrarModalEliminarrModalidad', function (e) {     
        e.preventDefault();  
        $('#formEliminarTipoIva #id').val('');
        $('#preguntaEliminar').html('');
        toggleModalIva('eliminar-modalidad');
    });







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


