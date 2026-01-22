import DB from './fecth.js';

if(window.location.pathname.includes('/Incidencias')){

    urlCompleta = $('#ruta').val();    
     


    $('#verDetalleFactura').on('click', function (e) {
        e.preventDefault();

        if ($('#contenedorDetalleFactura').is(':visible')) {
            $('#contenedorDetalleFactura').slideUp(300);
            $('#verDetalleFactura').html('<i class="fas fa-sort-down mr-2"></i> Ver detalles a facturar ');
          } else {
            $('#contenedorDetalleFactura').slideDown(300);
            $('#verDetalleFactura').html('<i class="fas fa-sort-up mr-2"></i> Ocultar detalles a facturar ');
          }
       
    });

    
    let botones_factura_inc = document.getElementById('botones_factura_inc');   
    if(botones_factura_inc){

        botones_factura_inc.addEventListener("click", (event) => {
      
            const agregar_linea_documento = event.target;

            if (agregar_linea_documento.matches('.agregar_linea_documento')) {

                               
                let filaDinamica;                                         
                let tBody = document.getElementById('tablaGrillaBody');
                let numRows = tBody.rows.length;     
                        
                if (numRows && numRows > 0) {
                    
                    let lastRow = $(tBody).find("tr").last();
                    filaDinamica = lastRow.find('input').eq(0).val();                      
                    
                } else {                        
                    filaDinamica = 0;
                }
        
                let filaOrden = parseInt(filaDinamica) + 1;                                
                
                let ruta = urlCompleta+'/FacturasCliente/obtenerDatosParaFilaNuevaFacturaIncidencia';                
                let params = {'tipo_linea' : agregar_linea_documento.dataset.linea, 'tbody':'tablaGrillaBody', 'filaOrden':filaOrden};
                let fetch=new DB(ruta, 'POST').get(params);
        
                fetch.then((data => {
                    document.getElementById('tablaGrillaBody').insertAdjacentHTML('beforeend',data.fila);          
                    validatorInputKeyPressGeneral();
                    document.getElementById('idArticulo'+filaOrden).focus();
                }));                


            }

        });

        botones_factura_inc.addEventListener("click", (e) => {
      
            const guardar_detalle_factura = e.target;

            if (guardar_detalle_factura.matches('#guardar_detalle_factura')) {

                let ruta = urlCompleta+'/FacturasCliente/enviarProductosGuardadosSinFacturar';
                let formVerIncidencia = document.getElementById('formVerIncidencia');
                let datosForm = new FormData(formVerIncidencia);
                let fetch=new DB(ruta, 'POST').post(datosForm);
      
                fetch.then((respuesta => {           
                                                
                  if(respuesta.error==false){                                
                      document.getElementById('tablaGrillaBody').innerHTML = respuesta.htmlPrefacturaDetalle;
                  }                               
                  mostrarResultadoFetch(respuesta);
                  
                }))  
            }
        });

        botones_factura_inc.addEventListener("click", (ev) => {
      
            const facturar_desde_incidencia = ev.target;

            if (facturar_desde_incidencia.matches('#facturar_desde_incidencia')) {


                let checkboxes = document.querySelectorAll('.checkMarcaFacturar');

                let cont = 0; 
    
                let arr = [] ;
                for (var x=0; x < checkboxes.length; x++) {
                    arr.push(checkboxes[x].value);
                    if (checkboxes[x].checked) {
                        cont = cont + 1;
                        
                    }
                }
    
                let formVerIncidencia = document.getElementById('formVerIncidencia');
                
                document.getElementById('checkBoxesEnviar').value = arr;
    
    
                if(cont > 0){             
    
                    let bool = confirm("Atención: Se va a generar líneas de factura con los datos seleccionados. ¿Está seguro?");
    
                    if(bool){
                            
                        let ruta = urlCompleta+'/FacturasCliente/enviarProductosGuardadosParaFacturar';
                        
                        let datosForm = new FormData(formVerIncidencia);
                        let fetch=new DB(ruta, 'POST').post(datosForm);
            
                        fetch.then((respuesta => {           
                                                    
                            if(respuesta.error==false){                                
                                document.getElementById('tablaGrillaBody').innerHTML = respuesta.htmlPrefacturaDetalle;
                                document.getElementById('container_numfactura').innerHTML = respuesta.numfacturafields;
                                document.getElementById('botones_factura_inc').innerHTML = respuesta.botonesFacturaIncidencia;
                                
                            }                               
                            mostrarResultadoFetch(respuesta);
                            cargarLinkFactura();
                        }));
    
                    }
                       
                    
                }else{
                    alert('No ha seleccionado ninguna línea para facturar.');
                } 

            }

        });

        botones_factura_inc.addEventListener("click", (eve) => {
      
            const buscar_factura = eve.target;

            if (buscar_factura.matches('#buscar_factura')) {                       
        
                    let checkboxes = document.querySelectorAll('.checkMarcaFacturar');
        
                    let cont = 0; 
        
                    let arr = [] ;
                    for (var x=0; x < checkboxes.length; x++) {
                        arr.push(checkboxes[x].value);
                        if (checkboxes[x].checked) {
                            cont = cont + 1;                    
                        }
                    }
        
                    document.getElementById('checkBoxesEnviar').value = arr;        
        
                    if(cont > 0){                                            
                                                                                
                            let ruta = urlCompleta+'/FacturasCliente/traerFacturasClienteRelacionadas';                
                          
                            let params = {'id' : document.getElementById('idIncidenciaVer').value};
            
                            let resultado=new DB(ruta, 'POST').get(params);
                            resultado.then((data => {            
                              
                                document.getElementById('select_facs').innerHTML = data.facturas;
        
                            }))
                        
                            //levantar modal y mostrar facturas a seleccionar
                            toggleModal('seleccionar-factura');
                                           
                    }else{
                        alert('No ha seleccionado ninguna línea para facturar.');
                    }   

            }
        });

    }          

    let tablaGrilla = document.getElementById('tablaGrilla');   
    if(tablaGrilla){

        tablaGrilla.addEventListener("click", (event) => {
      
            const clickedElement = event.target;

            if (clickedElement.matches('.eliminar_fila')) { 
            
            let idFila= clickedElement.dataset.idfila;

            if(document.getElementById('idFila'+idFila)){

                let idDetalle = document.getElementById('idFila'+idFila).value;            
                let bool = confirm("Está seguro de eliminar el artículo?");
                
                if(bool){        
                
                let ruta = urlCompleta+'/FacturasCliente/eliminarFilaDetalle';
                let params = {'idFila' : idDetalle, 'idFactura' : document.getElementById('idIncidenciaVer').value};
        
                let fetch=new DB(ruta, 'POST').get(params);
                fetch.then((data => {            
                    
                    if(data.error == false){
                    const filaSelected = document.getElementById('fila_grilla_id_'+idFila);
                    filaSelected.remove();    
                    
                    document.getElementById('baseimponible_importe').innerHTML = data.datos.baseimponible;
                    document.getElementById('ivatotal_importe').innerHTML = data.datos.ivatotal;
                    document.getElementById('total_importe').innerHTML = data.datos.total;    
                                    

                    }

                    mostrarResultadoFetch(data);

                }))            
                
                }

            }else{
                const filaSelected = document.getElementById('fila_grilla_id_'+idFila);
                filaSelected.remove(); 
            }

            }
                          
        });
        
        tablaGrilla.addEventListener("change", (event) => {
                       
            const changeElement = event.target;
           
            if (changeElement.matches('.articulo')) {            

                let tagname = changeElement.tagName;
                if(tagname == 'SELECT'){

                    let idProducto= changeElement.value;

                    let idFila ='';
                    if(changeElement.dataset.idordenguardado){
                        idFila = changeElement.dataset.idordenguardado;
                    }else if(changeElement.dataset.idorden){
                        idFila = changeElement.dataset.idorden;
                    }                          
                    let ruta = urlCompleta+'/Productos/obtenerDatosProducto'; 
                    
                    let params = {'id' : idProducto};
        
                    let resultado=new DB(ruta, 'POST').get(params);
                    resultado.then((data => {            

                        document.getElementById('iva'+idFila).value = data.datosProducto.iva;
                        
                        document.getElementById('precioArticulo'+idFila).value = data.precioProvDefault; 
                                                
                        let selectArticulo = changeElement.dataset.idorden;                                
                        let selectFilaArticulo = $('#fila_grilla_id_'+selectArticulo).closest('tr');
                        selectFilaArticulo.find('td:eq(3)').text(data.datosProducto.unidad);
                        
                        document.getElementById('cantidadArticulo'+idFila).focus();

                    }))            
    

                }

            }
        });          

        tablaGrilla.addEventListener("click", (e) => {
            const checkElement = e.target;

            if (checkElement.matches('.checkMarcaFacturar')) {
                
                if(e.target.checked){            
                    e.target.value = 'si';                    
                }else{                    
                    e.target.value = 'no';                    
                }
                            
            }
        });                

    } 

    function mostrarResultadoFetch(respuesta) {
        let texto = respuesta.mensaje;    
        let confirmButtonTexto = 'Cerrar';
        if(respuesta.error == false){                                
        Swal.fire({
            title: 'Proceso correcto',
            text: texto,
            icon: 'success',
            confirmButtonText: confirmButtonTexto          
        });        
        }else{                                
        Swal.fire({
            title: 'Error',
            text: texto,
            icon: 'error',
            confirmButtonText: confirmButtonTexto
        });                  
        }
    }    
    
    cargarLinkFactura();
    function cargarLinkFactura(){

        let numFactura = document.getElementById('numFactura');
        if(numFactura){
            numFactura.addEventListener("click", (num) => {                    

                let idDocumento = num.target.dataset.idfactura;
                if(idDocumento > 0 && idDocumento != ''){
                        
                    let ruta = urlCompleta+'/FacturasCliente/enviarIdFacturaParaEditar';       
                    let params = {'id' : idDocumento};               
                    let fetch=new DB(ruta, 'POST').get(params);
            
                    fetch.then((respuesta => {   
                        if(respuesta.error == false){
                            window.location.href = urlCompleta + "/FacturasCliente/verFactura";
                        }else{                
                            Swal.fire({
                                title: 'Error',
                                text: respuesta.mensaje,
                                icon: 'error',
                                confirmButtonText: 'Cerrar'
                            });             
                        }
                    }));

                }            

        });
        }

    }                    
       
    
    let select_facs = document.getElementById('select_facs');    
    if(select_facs){
    
       
          select_facs.addEventListener('change', function () {
            
            let select_facsed = select_facs.value;  
            console.log('select_facsed', select_facsed);                      
            
            if(select_facsed != '' && select_facsed > 0){          
                
                document.getElementById('idFacturaEnviar').value = select_facsed;
    
            }
    
          });      
    }

    
    let enviarLineasConIdFactura = document.getElementById('enviarLineasConIdFactura');
    if(enviarLineasConIdFactura){
        enviarLineasConIdFactura.addEventListener("click", (event) => {          
        
            let checkboxes = document.querySelectorAll('.checkMarcaFacturar');

            let cont = 0; 

            let arr = [] ;
            for (var x=0; x < checkboxes.length; x++) {
                arr.push(checkboxes[x].value);
                if (checkboxes[x].checked) {
                    cont = cont + 1;                    
                }
            }

            document.getElementById('checkBoxesEnviar').value = arr;


            if(cont > 0 && document.getElementById('idFacturaEnviar').value > 0){                                            
                                                                        
                    let ruta = urlCompleta+'/FacturasCliente/enviarProductosGuardadosParaFacturar';                                                        
                    
                    let datosForm = new FormData(formVerIncidencia);
                    let fetch=new DB(ruta, 'POST').post(datosForm);
        
                    fetch.then((respuesta => {           
                                            
                    if(respuesta.error==false){                                
                        
                        toggleModal('seleccionar-factura');

                        document.getElementById('tablaGrillaBody').innerHTML = respuesta.htmlPrefacturaDetalle;
                        document.getElementById('container_numfactura').innerHTML = respuesta.numfacturafields;
                        document.getElementById('botones_factura_inc').innerHTML = respuesta.botonesFacturaIncidencia;
                        cargarLinkFactura();
                    }                               
                    mostrarResultadoFetch(respuesta);
                    
                    }));                    
                                   
            }else{
                alert('No ha seleccionado ninguna factura.');
            }                      
  
      });
    }
        

    $(document).on('click', '.cerrarSeleccionarFactura', function (e) {
        e.preventDefault();
        toggleModal('seleccionar-factura');          
    });
    

    
    /////////
  
    $(document).on('click', '.buscar_articulo', function () {
        
        let filaSelectedProducto = $(this).data('idfila');           
        document.getElementById('idFilaSelected').value = filaSelectedProducto;
        
        // Inicializar el select2 solo si no se ha inicializado previamente       
        if (!$('#selectProducto').hasClass('select2-hidden-accessible')) {
            $('#selectProducto').select2({
                allowClear: true,
                minimumInputLength: 3,
                language: {
                    inputTooShort: function () {
                        return "Por favor, ingrese 3 o más caracteres";
                    }
                },
                ajax: {
                    url: urlCompleta + '/Productos/buscarProducto',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term 
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return { id: obj.numero, text: obj.nombre, idproducto: obj.id, unidad: obj.unidad, iva: obj.iva, precio:obj.precio };
                            })
                        };
                    },
                    cache: true
                }
            });
        }
            
        toggleModal('buscar-producto');
    });
    
    $(document).on('select2:select', '#selectProducto', function (e) {

        let filaSelectedProducto = document.getElementById('idFilaSelected').value;         

        var data = e.params.data;      
        
        if (data.idproducto > 0) {            

            let selectArticulo = $('#idArticulo'+filaSelectedProducto);
            selectArticulo.empty();                
            selectArticulo.append($('<option>', {
                value: data.id,
                text: data.id+" - "+data.text
            }));
            
                
            let selectFilaArticulo = $('#idArticulo'+filaSelectedProducto).closest('tr');
            selectFilaArticulo.find('td:eq(3)').text(data.unidad);            

            document.getElementById('iva'+filaSelectedProducto).value = data.iva;            
            document.getElementById('precioArticulo'+filaSelectedProducto).value = data.precio; 

            document.getElementById('cantidadArticulo'+filaSelectedProducto).focus();
               
        }
        $('#selectProducto').val(null).trigger('change');
        toggleModal('buscar-producto');
    });


    $(document).on('click', '.cerrarBuscadorProductos', function (e) {
        e.preventDefault();        
        toggleModal('buscar-producto');
    });
    /////////



    function toggleModal(modal_id) {
                
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("flex");
        document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

    }
      

    
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
     

}

