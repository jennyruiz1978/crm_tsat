if(window.location.pathname.includes('/Tecnicos')){

    urlCompleta = $('#ruta').val();


    $(document).on('click', '.butonCerrarAlerta', function (event) {
        cerrarAlerta(event);
    })

    $(document).on('click', '.eliminar', function (e) {
        e.preventDefault();

        var fila = $(this).closest('tr');
        id = parseInt(fila.find('td:eq(0)').text());
        codigotecnico = parseInt(fila.find('td:eq(1)').text());
        nombre = fila.find('td:eq(2)').text();
        apellido = fila.find('td:eq(3)').text();        

        if (id >0) {                                
            $('#idTecnico').val(id);
            $('#datoSucursal').text(codigotecnico + " - " + nombre + " " + apellido);
            toggleModal('delete-tecnico');                    
        }
    });

    
    $(document).on('click', '.cerraModDeleteTecnico', function (e) {
        e.preventDefault();        
        toggleModal('delete-tecnico');    
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


    //solo ver clientes

    const checkboxEditarClientes = document.querySelector('input[name="editarClientes"]');
    const checkboxSoloVerClientes = document.querySelector('input[name="soloVerClientes"]');

    if(checkboxEditarClientes){
        checkboxEditarClientes.addEventListener('click', () => {
            if (checkboxEditarClientes.checked) {
                checkboxSoloVerClientes.checked = false;
            }
        });    
    }
    
    if(checkboxSoloVerClientes){
        checkboxSoloVerClientes.addEventListener('click', () => {
            if (checkboxSoloVerClientes.checked) {
                checkboxEditarClientes.checked = false;
            }
        });
    }


}


