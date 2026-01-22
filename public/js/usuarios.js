 if(window.location.pathname.includes('/Usuarios')){

    urlCompleta = $('#ruta').val();

    //Cuando el usuario es admin que no esté obligado a seleccionar cliente (deshabilitar select cliente)
    $('#rol').on('change', function () {
        console.log($(this).val());
        if ($(this).val() == 0) {
            $('select[name="idcliente"]').val('');
            $('#idcliente').prop('disabled', 'disabled');  
            if ($('#contenedorSoloClientes').is(':visible')) {
               
                $('#contenedorSoloClientes').slideUp(300);   
                $('#contenedorClienteSucursales').html('');
                $('#contenedorClienteEquipos').html('');
                $('#contenedorClienteTipo').html('');             
            }          
        }else if($(this).val() == 1){
            $('#idcliente').prop('disabled', false);           
        }

    });

    //al hacer click en los radios
    $(document).on('click', '.radioType', function () {
       
        var tipo = $(this).val();
        var idCliente = $('#idcliente').val();
        var idUsuario = $('#id').val();

        if (tipo == 'administrador' || tipo == 'usuario') {
            if($('#checkVerTodos').is(":checked")) {
                $('#checkVerTodos').prop("checked", false);
            }            
            $('#checkVerTodos').prop('disabled', 'disabled');
        }else if (tipo == 'supervisor'){
            $('#checkVerTodos').prop('disabled', false);
        }

       
            if (tipo !='' && idCliente !='' && idCliente >0) {
                                
                $.ajax({
                    type: 'POST',
                    url: urlCompleta + '/Usuarios/construirContenedoresParaCliente',
                    dataType: "json",
                    data: { 'tipo':tipo, 'idCliente':idCliente },
                }).done(function(data){  

                    if (data['respuesta'] == 1) {
                        $('#contenedorClienteSucursales').html('');
                        $('#contenedorClienteSucursales').html(data['sucursales']);
                        $('#contenedorClienteEquipos').html('');
                        $('#contenedorClienteEquipos').html(data['equipos']);
                        $('#contenedorEquiposAsignados').html('');
                        $('#contenedorEquiposAsignados').html(data['tablaEquipos']);

                    
                    } 
                });               

            }
        
        
    });

     //al hacer click el checkbox "ver todos"
     $(document).on('click', '#checkVerTodos', function () {

        
        var tipo = 'administrador';
        var idCliente = $('#idcliente').val();

        if (tipo !='' && idCliente !='' && idCliente >0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Usuarios/construirContenedoresParaCliente',
                dataType: "json",
                data: { 'tipo':tipo, 'idCliente':idCliente },
            }).done(function(data){  

                if (data['respuesta'] == 1) {
                    $('#contenedorClienteSucursales').html('');
                    $('#contenedorClienteSucursales').html(data['sucursales']);
                    $('#contenedorClienteEquipos').html('');
                    $('#contenedorClienteEquipos').html(data['equipos']);
                    $('#contenedorEquiposAsignados').html('');                    
                } 
            });       
        }
     });

     //en el change del idCliente, volver a mostrar el contenedor de tipos    
    //cuando se selecciona el cliente recien se muestra el apartado de cliente
    $('#idcliente').on('change', function () {        

        idCliente = $(this).val();
        idRol = $('#rol').val();         
        
        if (idRol !='' && idRol ==1 && $(this).val() >0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Usuarios/construirContenedoresClienteTipos',
                dataType: "json",
            }).done(function(data){  

                if (data['respuesta'] == 1) {
                    $('#contenedorClienteTipo').html('');
                    $('#contenedorClienteTipo').html(data['tipos']);       
                    $('#contenedorClienteSucursales').html('');
                    $('#contenedorClienteSucursales').html(data['sucursales']);             
                    $('#contenedorClienteEquipos').html('');
                    $('#contenedorEquiposAsignados').html('');                    
                } 
            });                   
            $('#contenedorSoloClientes').slideDown(300);            
        
        }


    
    });


     //llenar select de equipos en el evento change del select de sucursales
     $(document).on('change', '#idSucursalCli', function () {        
        var idSucursal = $(this).val();

        if (idSucursal !='' && idSucursal >0 ) {
            
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Usuarios/llenarSelectOptionsDeSucursales',
                dataType: "json",
                data: {'idSucursal': idSucursal}
            }).done(function(data){  

                if (data['respuesta'] == 1) {
                    $('#contenedorClienteEquipos').html('');
                    $('#contenedorClienteEquipos').html(data['options']);
                    
                    tail.select('.todos',{
                        search: true,
                        locale: "es",
                        multiSelectAll: true,
                        searchMinLength: 0,
                        multiContainer: true,
                    });
                } 
            });
        }
     });

     $(document).on('click', '#btnAgregarEquipo', function (e) {
        e.preventDefault();
        equipos = $('#idEquipoCli').val();       

        if (equipos.length >0) {
            $.ajax({
                type: 'POST',
                url: urlCompleta + '/Usuarios/traerFilasEquiposSeleccionados',
                dataType: "json",
                data: {'equipos': equipos}
            }).done(function(data){  

                if (data['respuesta'] == 1) {                    
                    $('#tablaListadoEquiposAsignados tbody').append(data['filas']);
                    //limpiar tailselect                    
                } 
            });
        }
        
     });

    $(document).on('click', '.eliminarEquipo', function (e) {
        e.preventDefault();
        filaEquipo = $(this).closest('tr');                
        filaEquipo.remove();

    });

    $(document).on('click', '.eliminar', function (e) {
        e.preventDefault();
        var filauser = $(this).closest('tr');
        idUsuario = parseInt(filauser.find('td:eq(0)').text());
        nombre = filauser.find('td:eq(1)').text();
        apellidos = filauser.find('td:eq(2)').text();
        
        $('#idUsuarioDel').val(idUsuario);
        $('#datoUsuario').html(nombre + ' ' + apellidos);

        if (idUsuario >0) {
            toggleModalUsuario('delete-usuario')       
        }        
        
    });

    $(document).on('click', '.cerraModDeleteUsuario', function (e) {
        e.preventDefault();
        toggleModalUsuario('delete-usuario')           
        $('#idUsuarioDel').html('');       
    });

    function toggleModalUsuario(modal_id) {
            
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


