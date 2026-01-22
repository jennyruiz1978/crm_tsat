if(window.location.pathname.includes('/FormasDePago')){   
    
    function toggleModalIva(modal_id) {
            
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("flex");
        document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

    }

    validatorInputKeyPressGeneral();
    function validatorInputKeyPressGeneral() {			
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


    $(document).on('click', '#nuevaFormaDePago', function (e) {
        e.preventDefault();
        toggleModalIva('crear-formadepago');        
    });

    $(document).on('click', '.cerrarModalCrearFormaPago', function (e) {
        e.preventDefault();
        $('#formadepago').val('');
        toggleModalIva('crear-formadepago');
    });    



    $(document).on('click', '.editar', function (e) {
        e.preventDefault();
        var filaTabla = $(this).closest('tr');        
        let id = parseInt(filaTabla.find('td:eq(0)').text()); 
        let formadepago = filaTabla.find('td:eq(1)').text();         
        let txtactivo = filaTabla.find('td:eq(2)').text();                 
     
        $('#formEditarFormaDePago #id').val(id);
        $('#formEditarFormaDePago #formadepago').val(formadepago);         
        if(txtactivo== 'activo'){
            document.querySelector('#formEditarFormaDePago #activo').checked = true;
            document.querySelector('#formEditarFormaDePago #activo').value = 1;
        }        
        toggleModalIva('editar-formapago');
    });

    $(document).on('click', '.cerrarModalEditarFormaPago', function (e) {
        e.preventDefault();
        
        $('#formEditarFormaDePago #id').val('');
        $('#formEditarFormaDePago #formadepago').val('');         
        document.querySelector('#formEditarFormaDePago #activo').checked = false;
        document.querySelector('#formEditarFormaDePago #activo').value = 0;
        
        toggleModalIva('editar-formapago');
    });



    $(document).on('click', '.eliminar', function (e) {
        e.preventDefault();
        var filaTabla = $(this).closest('tr');        
        id = parseInt(filaTabla.find('td:eq(0)').text()); 
        formadepago = filaTabla.find('td:eq(1)').text(); 

        $('#formEliminarFormaDePago #id').val(id);
        $('#preguntaEliminar').html('¿Está seguro(a) de eliminar la forma de pago '+ formadepago +' ?');
        toggleModalIva('eliminar-formapago');
    });

    $(document).on('click', '.cerrarModalEliminarFormaPago', function (e) {     
        e.preventDefault();  
        $('#formEliminarFormaDePago #id').val('');
        $('#preguntaEliminar').html('');
        toggleModalIva('eliminar-formapago');
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


