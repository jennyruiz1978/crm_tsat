 if(window.location.pathname.includes('/Productos')){

    urlCompleta = $('#ruta').val();

        
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


    $(document).on('click', '#addProveedor', function () {            

        $.ajax({
            type: 'POST',
            url: urlCompleta + '/Productos/traerProveedores',
            dataType: "json",
            data: {}
        }).done(function(data){  

            if (data) {           

                if(data['respuesta']==1){
                    $('#tablaProveedoresPrecios tbody').append(data['fila']);
                    validatorInputKeyPressGeneral();                                    
                }else{
                    $('#tablaProveedoresPrecios tbody').html((data['fila']));
                }
                
            }else{                
                console.log('Ha ocurrido un error y no se ha realizado la consulta');
            }

        });
     
    });
 
    $(document).on('click', '#crearProducto', function (e) {

        if (document.getElementById("formAltaProducto").checkValidity()) {

            e.preventDefault();
                    
            form = $('#formAltaProducto').serializeArray();

            envioDatosProductoNuevoPorAjax(form);
        
        }

    });

    function envioDatosProductoNuevoPorAjax(form) {
        $.ajax({
            type: 'POST',
            url: urlCompleta + '/Productos/crearProducto',
            dataType: "json",
            data: { 'form': form},
        }).done(function(data){   

            
            if (data['respuesta'] == 1) {                                           
                  
                document.getElementById("idProducto").value = data['id'];

                Swal.fire({
                    title: 'Creación de nuevo artículo',
                    text: 'Se ha creado el artículo corréctamente',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });                           

                $("#mensajeValidacion").text("Se ha creado el artículo corréctamente.").show().fadeOut(3000);  

                
                setTimeout(function () {

                    window.location = urlCompleta+'/Productos';
                    
                },3000);
                
                
            }else{
                                    
                Swal.fire({
                    title: 'Error',
                    text: 'Ha ocurrido un error y no se ha podido crear el nuevo artículo.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            
                $("#mensajeValidacion").text("Ha ocurrido un error y no se ha podido crear el nuevo artículo.").show().fadeOut(3000);    
            }
            
        });   
    }
        
    $(document).on('click', '.eliminarProveedorPrecio', function (e) {                                    
        e.preventDefault();        
        filaContacto = $(this).closest('tr');                
        filaContacto.remove();
    });    
   
    //guardarProducto
    $(document).on('click', '#guardarProducto', function (e) {

        if (document.getElementById("formEditarProducto").checkValidity()) {
            e.preventDefault();                    
            form = $('#formEditarProducto').serializeArray();
            envioDatosActualizar(form);        
        }

    });

    function envioDatosActualizar(form) {
        $.ajax({
            type: 'POST',
            url: urlCompleta + '/Productos/editarProducto',
            dataType: "json",
            data: { 'form': form},
        }).done(function(data){   

            
            if (data['respuesta'] == 1) {                                                                             

                $('#ultimamodificaicon').val(data['modificacion']);
                Swal.fire({
                    title: 'Actualización de artículo',
                    text: 'Se ha actualizado el artículo corréctamente',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });                                           
                
            }else{
                                    
                Swal.fire({
                    title: 'Error',
                    text: 'Ha ocurrido un error y no se ha podido actualizar el artículo.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                           
            }
            
        });   
    }

    
    $(document).on('click', '.eliminar', function (e) {
        e.preventDefault();
        var filaarticulo = $(this).closest('tr');
        idProducto = parseInt(filaarticulo.find('td:eq(0)').text());
        nombre = filaarticulo.find('td:eq(1)').text();    
        
        $('#idProductoDel').val(idProducto);
        $('#datoProducto').html(nombre);

        if (idProducto >0) {
            toggleModalProducto('delete-articulo')       
        }        
        
    });

    
    $(document).on('click', '.checkProveedorDefault', function (e) {        

        let checks = document.querySelectorAll('.checkProveedorDefault');
        if(checks){

            checks.forEach(check =>{
                check.value = 0;
            })
        }

        let provSelected =  $(this).closest('tr').find('td:eq(0)').children('select')[0].value;                     
        this.value = (provSelected && provSelected != undefined && provSelected >0)? provSelected: 0;
    });     

    function toggleModalProducto(modal_id) {
            
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("flex");
        document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

    }
    $(document).on('click', '.cerrarModDeleteProducto', function (e) {
        e.preventDefault();
        toggleModalProducto('delete-articulo')           
        $('#idProductoDel').html('');       
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
    
    document.addEventListener('DOMContentLoaded', () => {

        const table = document.getElementById('tablaProveedoresPrecios');

        if (table) {
            // Function to calculate and update the sale price
            function updatePrecioVenta(event) {
                const row = event.target.closest('tr'); // Find the closest table row
                if (!row) return;
        
                const precioInput = row.querySelector('input[name="precio"]'); // Find the precio input in the same row
                const margenInput = row.querySelector('input[name="margen"]'); // Find the margen input in the same row
                const precioVentaInput = row.querySelector('input[name="precioventa"]'); // Find the precioventa input in the same row
        
                const precio = parseFloat(precioInput.value) || 0; // Get the value of precio input, default to 0 if NaN
                const margen = parseFloat(margenInput.value) || 0; // Get the value of margen input, default to 0 if NaN
        
                const precioVenta = precio * (1 + margen / 100); // Calculate the sale price
        
                // Format the precioVenta to 2 decimal places with a comma as the decimal separator
                const formattedPrecioVenta = precioVenta.toLocaleString('es-ES', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        
                precioVentaInput.value = formattedPrecioVenta; // Update the precioventa input with the formatted value
            }
        
            // Delegated event listener for keyup events on precio and margen inputs
            
            table.addEventListener('keyup', (event) => {
                const target = event.target;
                if (target.name === 'precio' || target.name === 'margen') {
                    updatePrecioVenta(event);
                }
            });
        }
    });
     
    


}


