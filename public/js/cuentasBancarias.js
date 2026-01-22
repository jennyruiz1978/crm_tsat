if(window.location.pathname.includes('/CuentasBancarias')){   
    
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


    $(document).on('click', '#nuevaCuentaBancaria', function (e) {
        e.preventDefault();
        toggleModalIva('crear-cta');        
    });

    $(document).on('click', '.cerrarModalCrearCuenta', function (e) {
        e.preventDefault();
        $('#numerocuenta').val('');
        toggleModalIva('crear-cta');
    });    



    $(document).on('click', '.editar', function (e) {
        e.preventDefault();
        var filaCuenta = $(this).closest('tr');        
        let id = parseInt(filaCuenta.find('td:eq(0)').text()); 
        let numcuenta = filaCuenta.find('td:eq(1)').text(); 
        let banco = filaCuenta.find('td:eq(2)').text(); 
        let txtactivo = filaCuenta.find('td:eq(3)').text();                 
     
        $('#formEditarCuentaBancaria #id').val(id);
        $('#formEditarCuentaBancaria #numerocuenta').val(numcuenta);
        $('#formEditarCuentaBancaria #banco').val(banco);        
        if(txtactivo== 'activo'){
            document.querySelector('#formEditarCuentaBancaria #activo').checked = true;
            document.querySelector('#formEditarCuentaBancaria #activo').value = 1;
        }        
        toggleModalIva('editar-cuenta');
    });

    $(document).on('click', '.cerrarModalEditarCuenta', function (e) {
        e.preventDefault();
        
        $('#formEditarCuentaBancaria #id').val('');
        $('#formEditarCuentaBancaria #numerocuenta').val('');
        $('#formEditarCuentaBancaria #banco').val('');   
        document.querySelector('#formEditarCuentaBancaria #activo').checked = false;
        document.querySelector('#formEditarCuentaBancaria #activo').value = 0;
        
        toggleModalIva('editar-cuenta');
    });



    $(document).on('click', '.eliminar', function (e) {
        e.preventDefault();
        var filaCuenta = $(this).closest('tr');        
        id = parseInt(filaCuenta.find('td:eq(0)').text()); 
        numerocuenta = filaCuenta.find('td:eq(1)').text(); 

        $('#formEliminarCuenta #id').val(id);
        $('#preguntaEliminar').html('¿Está seguro(a) de eliminar la cuenta bancaria '+ numerocuenta +' ?');
        toggleModalIva('eliminar-cuenta');
    });

    $(document).on('click', '.cerrarModalEliminarrModalidad', function (e) {     
        e.preventDefault();  
        $('#formEliminarCuenta #id').val('');
        $('#preguntaEliminar').html('');
        toggleModalIva('eliminar-cuenta');
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


