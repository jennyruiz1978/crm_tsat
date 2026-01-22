import DB from './fecth.js';

if(window.location.pathname.includes('/FacturasCliente')){

    var urlCompleta = $('#ruta').val();    

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
    
    validatorInputKeyPressGeneral();

              
    
    let formAltaFactura = document.getElementById('formAltaFactura');
    if(formAltaFactura){

        /* formAltaFactura.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && event.target.tagName === 'INPUT') {
                event.preventDefault();
            }
        }); */

        formAltaFactura.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Previene el envío al presionar "Enter" en cualquier parte del formulario
            }
        });


      formAltaFactura.addEventListener('submit', function(e) {        
          e.preventDefault();

            const submitButton = formAltaFactura.querySelector('button[type="submit"]');
            submitButton.disabled = true;
          
          let ruta = urlCompleta+'/FacturasCliente/registrarFactura';
          let datosForm = new FormData(formAltaFactura);                  
          let fetch=new DB(ruta, 'POST').post(datosForm);

          fetch.then((respuesta => {                       

            limpiarMensajesCamposError();
            
            let url = '';
            if(respuesta.error==false){
                
                url = urlCompleta+'/FacturasCliente/verFactura';          

            }else{
              if(respuesta.fieldsValidate && respuesta.fieldsValidate.length > 0){                
                for (let index = 0; index < respuesta.fieldsValidate.length; index++) {   
                                   
                    if(document.getElementById('error_'+respuesta.fieldsValidate[index])){
                        if(respuesta.fieldsValidate[index]=='cif'){
                            document.getElementById('error_'+respuesta.fieldsValidate[index]).innerHTML = 'El cliente no tiene nif';
                        }else{
                            document.getElementById('error_'+respuesta.fieldsValidate[index]).innerHTML = 'Este campo es obligatorio';
                        }
                        
                    }
                  
                }
              }
            }       
            
            mostrarResultadoFetchEliminar(respuesta, 0, url);

            submitButton.disabled = false;
            
          })).catch(error => {
            // En caso de error, rehabilitar el botón para permitir reintentos
            console.error("Error en el envío:", error);
            submitButton.disabled = false;
        });         
      });
    }         
        
    function limpiarMensajesCamposError(){
            let spans = document.querySelectorAll('.mensaje_required');     
            spans.forEach(function(elemento, index, arreglo) {        
            elemento.innerHTML = '';
            });              
    }

    function mostrarResultadoFetchEliminar(respuesta, eliminarFila=false, url=false) {
            let texto = respuesta.mensaje;    
            let confirmButtonTexto = 'Cerrar';
            if(respuesta.error == false){                                
              Swal.fire({
                title: 'Proceso correcto',
                text: texto,
                icon: 'success',
                confirmButtonText: confirmButtonTexto          
              });
              if(eliminarFila==0){
                setTimeout(function () {
                  window.location = url;
                },2000);
              }
            }else{                                
              Swal.fire({
                title: 'Error',
                text: texto,
                icon: 'error',
                confirmButtonText: confirmButtonTexto
              });                  
            }
    }        

    /*
    const cliente_select = document.getElementById('idcliente');    
    if(cliente_select){
    
          cliente_select.addEventListener('change', function () {
            
            let cliente_selected = cliente_select.value;   
            
            let cifcli = document.getElementById('cif');
            cifcli.value = '';
            
            if(cliente_selected != ''){          
    
              let ruta = urlCompleta+'/FacturasCliente/obtenerCliente'; 
              
              let params = {'id' : cliente_selected};
    
              let resultado=new DB(ruta, 'POST').get(params);
              resultado.then((data => {            

                
                cifcli.value = data.datos.cif;
                if(data.datos.cif != null && data.datos.cif !=''){
                    cifcli.style.backgroundColor = '#e1d9d9';
                    cifcli.readOnly = true;
                    document.getElementById('cifguardar').value= 0;
                    document.getElementById('fecha').focus();
                }else{                                                       
                    cifcli.style.backgroundColor = 'white';
                    cifcli.readOnly = false;
                    document.getElementById('cifguardar').value= 1;
                    cifcli.focus();
                }

              }))
    
            }
    
          });      
    }
    */        
          
    let formVerFactura = document.getElementById('formVerFactura');
    if(formVerFactura){
      formVerFactura.addEventListener('submit', function(e) {        
          e.preventDefault();
          
          let ruta = urlCompleta+'/FacturasCliente/actualizarFactura';
          let datosForm = new FormData(formVerFactura);                  
          let fetch=new DB(ruta, 'POST').post(datosForm);

          fetch.then((respuesta => {                       

            limpiarMensajesCamposError();
            
            let url = '';
            if(respuesta.error==false){

                //console.log('nuevo id', respuesta.idfactura);
                url = urlCompleta+'/FacturasCliente/verFactura';
                /*
              document.getElementById('numero').value = respuesta.numero;


              let tableId = document.getElementById('tablaGrilla');                        
              let tBody = tableId.getElementsByTagName('tbody')[0];            
              tBody.innerHTML = respuesta.html;
  
              document.getElementById('baseimponible_importe').innerHTML = respuesta.baseimponible;
              document.getElementById('ivatotal_importe').innerHTML = respuesta.ivatotal;
              document.getElementById('total_importe').innerHTML = respuesta.total;  
                */

            }else{
              if(respuesta.fieldsValidate && respuesta.fieldsValidate.length > 0){                
                for (let index = 0; index < respuesta.fieldsValidate.length; index++) {   
                    //console.log('field', respuesta.fieldsValidate[index]);                 
                    if(document.getElementById('error_'+respuesta.fieldsValidate[index])){
                        if(respuesta.fieldsValidate[index]=='cif'){
                            document.getElementById('error_'+respuesta.fieldsValidate[index]).innerHTML = 'El cliente no tiene nif';
                        }else{
                            document.getElementById('error_'+respuesta.fieldsValidate[index]).innerHTML = 'Este campo es obligatorio';
                        }
                        
                    }
                  
                }
              }
            }       
            
            mostrarResultadoFetchEliminar(respuesta, 0, url);

          }))          
      });
    }   


   
    let buttons = document.querySelectorAll('.agregar_linea_documento');
    if(buttons){

        buttons.forEach(button =>{
            button.addEventListener('click', function (event) {                         
                               
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
                
                let ruta = urlCompleta+'/FacturasCliente/obtenerDatosParaFilaNueva';                
                let params = {'tipo_linea' : event.target.dataset.linea, 'tbody':'tablaGrillaBody', 'filaOrden':filaOrden};
                let fetch=new DB(ruta, 'POST').get(params);
        
                fetch.then((data => {
                    document.getElementById('tablaGrillaBody').insertAdjacentHTML('beforeend',data.fila);          
                    validatorInputKeyPressGeneral();                    
                    document.getElementById('idArticulo'+filaOrden).focus();
                    
                }));                

            })
        })
      
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
              let params = {'idFila' : idDetalle, 'idFactura' : document.getElementById('id').value};
    
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
                  document.getElementById('cantidadArticulo'+idFila).focus();
                }))                      

            }            

        }
      });      
    }     

    
    let generar_pdf = document.getElementById('generar_pdf');
    if(generar_pdf){
        generar_pdf.addEventListener('click', function () {
        
        let idDocumento = document.getElementById('id').value;
        
        let ruta = urlCompleta+'/FacturasCliente/enviarIdFacturaGenerarPdf';       
        let params = {'id' : idDocumento};               
        let fetch=new DB(ruta, 'POST').get(params);

        fetch.then((respuesta => {   
            if(respuesta.error == false){
                window.open(urlCompleta + "/FacturasCliente/exportarPdfFactura");
            }else{                
                Swal.fire({
                    title: 'Error',
                    text: respuesta.mensaje,
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });             
            }
        }));

        
      });       
    }
        
    $(document).on('click', '.pdffila',function (e) {
        e.preventDefault();

        let idFilaFactura = $(this).closest('tr');
        let idFactura = parseInt(idFilaFactura.find('td:eq(0)').text());

        if (idFactura > 0) {

            let ruta = urlCompleta+'/FacturasCliente/enviarIdFacturaGenerarPdf';       
            let params = {'id' : idFactura};               
            let fetch=new DB(ruta, 'POST').get(params);
    
            fetch.then((respuesta => {   
                if(respuesta.error == false){
                    window.open(urlCompleta + "/FacturasCliente/exportarPdfFactura");
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

    
    $(document).on('click', '.tab-factura', function (e) {              
        e.preventDefault();
  
        let tab = $(this).data('tab');                
            
        activadorTabActivoDashboard(e, tab);  
  
        //detecto la pastilla activa      
        var pastillaActiva = $('.pastillaTabsFactura.block');
       
        let idPastilla = pastillaActiva.attr('id');
        

        if (idPastilla == 'tab-settings') {
            let ruta = urlCompleta+'/FacturasCliente/obtenerListadoEnviosFactura';    
            let id = document.getElementById('id').value;   
            let params = {'id' : id};               
            let fetch=new DB(ruta, 'POST').get(params);
    
            fetch.then((respuesta => {   
                if(respuesta.error == false){
                    document.getElementById('tab-settings').innerHTML = respuesta.html;
                }else{                
                    document.getElementById('tab-settings').innerHTML = respuesta.mensaje;
                }
            }));    
        }
                
      });

      function activadorTabActivoDashboard(event, tabID) {
        let element = event.target;
        while (element.nodeName !== "A") {
            element = element.parentNode;
        }
        let ulElement = element.parentNode.parentNode; // Declaración de ulElement
        let aElements = ulElement.querySelectorAll("li > a");
        let tabContents = document.getElementById("tabs-id").querySelectorAll(".tab-content > div"); // Ajuste de ID
    
        for (let i = 0; i < aElements.length; i++) {
            aElements[i].classList.remove("text-white");
            aElements[i].classList.remove("bg-violeta-oscuro");
            aElements[i].classList.add("texto-violeta-oscuro");
            aElements[i].classList.add("bg-white");
            tabContents[i].classList.add("hidden");
            tabContents[i].classList.remove("block");
        }
        element.classList.remove("texto-violeta-oscuro");
        element.classList.remove("bg-white");
        element.classList.add("text-white");
        element.classList.add("bg-violeta-oscuro");
        document.getElementById(tabID).classList.remove("hidden");
        document.getElementById(tabID).classList.add("block");
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

    var idFacturaCambiar;
    $(document).on('click', '.cambiarestadofactura',function (e) {
        e.preventDefault();

        idFacturaCambiar = $(this).closest('tr');        
        //console.log('idFacturaCambiar1=> ', idFacturaCambiar);
        let idFactura = parseInt(idFacturaCambiar.find('td:eq(0)').text());      
        $('#idFacturaCambiar').val(idFactura);   

        if (idFactura > 0) {

            let ruta = urlCompleta+'/FacturasCliente/consultarEstadoPagoFactura';       
            let params = {'id' : idFactura};               
            let fetch=new DB(ruta, 'POST').get(params);
    
            fetch.then((respuesta => {   
                if(respuesta.estadopago){
                    document.getElementById('estadopagotexto').innerHTML = `¿ Está seguro de cambiar el estado de pago de la factura a ${respuesta.estadopago} ?`;
                    document.getElementById('estadopago').value = respuesta.estadopago;
                    toggleModal('cambiar-estadofactura');
                }else{
                    alert('No existe a factura.');
                }
                
            }));               
            
        }

    });

    $(document).on('click', '.cerrarModalCambiarEstadoFactura', function (e) {
        e.preventDefault();
        document.getElementById("estadopagotexto").innerHTML= "";
        document.getElementById("idFacturaCambiar").value= "";
        document.getElementById("estadopago").value= "";
        toggleModal('cambiar-estadofactura');
    });

        
    const cambiarEstadoPago = document.getElementById('cambiarEstadoPago');    
    if(cambiarEstadoPago){
    
        cambiarEstadoPago.addEventListener('click', function () {
            
            let idFactura = document.getElementById('idFacturaCambiar').value;
            let estadopago = document.getElementById('estadopago').value;

            let ruta = urlCompleta+'/FacturasCliente/cambiarEstadoPagoFactura';       
            let params = {'id' : idFactura, 'estadopago' : estadopago};               
            let fetch=new DB(ruta, 'POST').get(params);
    
            fetch.then((respuesta => {            
                
                document.getElementById("estadopagotexto").innerHTML= "";
                document.getElementById("idFacturaCambiar").value= "";
                document.getElementById("estadopago").value= "";
               
                let mensaje = '';
                let titulo= '';
                let icono = '';
                if(respuesta.error){                    
                    titulo = 'Error';
                    icono = 'error';
                                    
                }else{                    
                    idFacturaCambiar.find('td:eq(6)').text(estadopago);   
                                    
                    titulo = 'Proceso correcto';
                    icono = 'success';
                }

                toggleModal('cambiar-estadofactura');

                Swal.fire({
                    title: titulo,
                    text: mensaje,
                    icon: icono,
                    confirmButtonText: 'Ok'          
                    });  
                
            }));      
            
    
        });      
    }    

        
    let enviar_email = document.getElementById('enviar_email');
    if(enviar_email){
        enviar_email.addEventListener('click', function () {
        
        let idDocumento = document.getElementById('id').value;                        
        
        let ruta = urlCompleta+'/Clientes/traerEmailsContactos';
        let params = {'id' : idDocumento};               
        let fetch=new DB(ruta, 'POST').get(params);

        fetch.then((respuesta => {   
            
            document.getElementById('emails_contactos').innerHTML = respuesta;
            toggleModal('enviar-factura');

        }));
        
      });       
    }        

    $(document).on('click', '.cerrarEnviarFactura', function (e) {
        e.preventDefault();
        toggleModal('enviar-factura');          
    });
    
    const agregarEmailInput = document.getElementById('agregarEmailInput');    
    if(agregarEmailInput){
    
        agregarEmailInput.addEventListener('click', function () {
                        
            let emailDestinatario = document.getElementById('emailDestinatario').value;
            
            
            if(emailDestinatario.trim() != ''){                          
                let ch = `<div class="container_email flex text-sm gap-2 px-2 py-1" style="border:1px solid #ededed !important;border-radius:6px;"><input class="inputEmailSelected" type="hidden" name="inputEmailSelected[]" value="${emailDestinatario}"><span class="emailSelected text-gray-500">${emailDestinatario}</span><span class="equisEmail text-red-500 font-bold cursor-pointer">x</span></div>`;
                document.getElementById('emails_selected_to_send').insertAdjacentHTML('beforeend',ch); 
                document.getElementById('emailDestinatario').value = "";
            }else{
                alert('Debe escribir un email válido');
            }
            
    
        });      
    }


    const emails_contactos = document.getElementById('emails_contactos');    
    if(emails_contactos){
    
        emails_contactos.addEventListener('change', function () {
            
            let contact_selected = emails_contactos.value;                        
            
            if(contact_selected != ''){                          
                let ch = `<div class="container_email flex text-sm gap-2 px-2 py-1" style="border:1px solid #ededed !important;border-radius:6px;"><input class="inputEmailSelected" type="hidden" name="inputEmailSelected[]" value="${contact_selected}"><span class="emailSelected text-gray-500">${contact_selected}</span><span class="equisEmail text-red-500 font-bold cursor-pointer">x</span></div>`;
                document.getElementById('emails_selected_to_send').insertAdjacentHTML('beforeend',ch);   
                document.getElementById('emails_contactos').value = ""; 
            }
    
        });      
    }

    let emailsToSend = document.getElementById('emails_selected_to_send');   
    if(emailsToSend){
        emailsToSend.addEventListener("click", (event) => {
        
            const clickedElement = event.target;

            if (clickedElement.matches('.equisEmail')) {                       
                let deletefila = clickedElement.closest(".container_email");
                deletefila.remove();
            }
                            
        });
    }


    let formSendEmailInvoice = document.getElementById('formSendEmailInvoice');
    if(formSendEmailInvoice){
        formSendEmailInvoice.addEventListener('submit', function(e) {                
        
            e.preventDefault();
            

            document.getElementById('idFacturaEnviar').value = document.getElementById('id').value;            

            if(document.querySelectorAll('.inputEmailSelected').length > 0){
                if(document.getElementById('emailAsunto').value.trim() != ''){
                    if(document.getElementById('emailMensaje').value.trim() != ''){              

                        
                        const spinner = document.getElementById("spinner");
                        //spinner.innerHTML = `<div class="border-2 border-black-500 text-black-500" role="status"><span class="sr-only">Loading...</span></div>`;
                        spinner.innerHTML = '<div>Enviando...</div>';
                        spinner.classList.add('spinnerShow');
                        
                                        
                        let ruta = urlCompleta+'/FacturasCliente/enviarEmailFactura';
                        let datosForm = new FormData(formSendEmailInvoice);
                        
                        let fetch=new DB(ruta, 'POST').postSend(datosForm);
                        
                        
                        fetch
                            .then((respuesta => {               
                                
                                
                                if(respuesta.error==false){
                                    resetearFormularioEnvioEmail();
                                    toggleModal('enviar-factura');

                                    document.getElementById('tab-settings').innerHTML = respuesta.html;

                                    Swal.fire({
                                        title: 'Proceso correcto',
                                        text: respuesta.mensaje,
                                        icon: 'success',
                                        confirmButtonText: 'Ok'          
                                    }); 

                                }else{
                                    Swal.fire({
                                        title: 'Error',
                                        text: respuesta.mensaje,
                                        icon: 'error',
                                        confirmButtonText: 'Ok'          
                                    });  
                                }                            
                                    
                                
                            }))    
                                

                    }else{
                        alert('Debe agregar el mensaje');    
                    }

                }else{
                    alert('Debe agregar el asunto');    
                }

            }else{
                alert('Debe agregar al menos un destinatario');
            }
            
            
        });
    }
    
    function resetearFormularioEnvioEmail(){
        $('#formSendEmailInvoice').trigger("reset");			
        let correos = document.getElementsByClassName('container_email');
        $(correos).remove();
    }

    //eventos change y keyup para cálculo de días de vencimiento y fecha de vencimiento
    const dias = document.getElementById('diascobro');    
    if(dias){

        dias.addEventListener('keyup', function () {                        
            
            let diasCobro = 0;
            if(this.value.trim() != ''){
                diasCobro = this.value.trim();
            }                        
            let fechaFactura = document.getElementById('fecha').value;

            let ruta = urlCompleta+'/FacturasCliente/calcularFechaVencimientoFacturaCliente';         
            let params = {'dias' : diasCobro, 'fecha' : fechaFactura};

            let fetch=new DB(ruta, 'POST').get(params);
            fetch.then((data => {                       
                document.getElementById('vencimiento').value = data.fechaVecimiento;       
            }))

            
        });      
    }        

    const fecha_vencimiento_cliente = document.getElementById('vencimiento');    
    if(fecha_vencimiento_cliente){
           
      fecha_vencimiento_cliente.addEventListener('change', function () {                        
        
        let fecha_vencimiento_cliente = '';
        if(this.value.trim() != ''){
          fecha_vencimiento_cliente = this.value.trim();
        }                                  
        let fecha_factura_cliente = document.getElementById('fecha').value;
        

        let ruta = urlCompleta+'/FacturasCliente/calcularDiasCobroFacturaCliente';         
        let params = {'vencimiento' : fecha_vencimiento_cliente, 'fecha_factura_cliente':fecha_factura_cliente};

        let fetch=new DB(ruta, 'POST').get(params);
        fetch.then((data => {                       
          document.getElementById('diascobro').value = data.dias_albaran_cliente;       
        }))        
      }); 

    }    
    
    const idformacobro = document.getElementById('idformacobro');    
    if(idformacobro){
    
        idformacobro.addEventListener('change', function () {
                                                
            let diascobro = document.getElementById('diascobro');
            let vencimiento = document.getElementById('vencimiento');
            if(this.value == 1){          
                diascobro.disabled = true;
                diascobro.style.backgroundColor = '#e1d9d9';
                vencimiento.disabled = true;
                vencimiento.style.backgroundColor = '#e1d9d9';
                diascobro.value = '';
                vencimiento.value = null;
            }else{
                diascobro.disabled = false;
                diascobro.style.backgroundColor = 'white';
                vencimiento.disabled = false;
                vencimiento.style.backgroundColor = 'white';                    
            }
    
        });      
    }

    $(document).on('click', '#boton_buscar_cliente', function () {
        // Inicializar el select2 solo si no se ha inicializado previamente
        
    
        if (!$('#selectTarea').hasClass('select2-hidden-accessible')) {
            $('#selectTarea').select2({
                allowClear: true,
                minimumInputLength: 3,
                language: {
                    inputTooShort: function () {
                        return "Por favor, ingrese 3 o más caracteres";
                    }
                },
                ajax: {
                    url: urlCompleta + '/Clientes/buscarCliente',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term // El término de búsqueda que ingresa el usuario
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return { id: obj.id, text: obj.nombre, cif: obj.cif, cuentas:obj.cuentas };
                            })
                        };
                    },
                    cache: true
                }
            });
        }
    
        // Mostrar el modal después de que el select2 se haya inicializado correctamente
        toggleModal('buscar-cliente');
    });
    
    $(document).on('select2:select', '#selectTarea', function (e) {
        var data = e.params.data;
        let cifcli = document.getElementById('cif');
        cifcli.value = '';
    
        if (data.id > 0) {
            $('#idcliente').empty();
            $('#idcliente').append($('<option>', {
                value: data.id,
                text: data.text
            }));
    
            cifcli.value = data.cif;
            if (data.cif != null && data.cif != '') {
                cifcli.style.backgroundColor = '#e1d9d9';
                cifcli.readOnly = true;
                document.getElementById('cifguardar').value = 0;
                document.getElementById('fecha').focus();
            } else {
                cifcli.style.backgroundColor = 'white';
                cifcli.readOnly = false;
                document.getElementById('cifguardar').value = 1;
                cifcli.focus();
            }
         
        }
        $('#selectTarea').val(null).trigger('change');
        toggleModal('buscar-cliente');
    });


    $(document).on('click', '.cerrarBuscadorClientes', function (e) {
        e.preventDefault();        
        toggleModal('buscar-cliente');
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
                            query: params.term // El término de búsqueda que ingresa el usuario
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
    
        // Mostrar el modal después de que el select2 se haya inicializado correctamente
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
    

    function toggleModal(modal_id) {
                
        document.getElementById(modal_id).classList.toggle("hidden");
        document.getElementById(modal_id + "-backdrop").classList.toggle("hidden");
        document.getElementById(modal_id).classList.toggle("flex");
        document.getElementById(modal_id + "-backdrop").classList.toggle("flex");

    }

    
    //eliminar factura
    $(document).on('click', '.eliminar', function(event) {
        event.preventDefault(); 
    
        
        const id = $(this).data('eliminar');

        const fila = $(this).closest('tr');
    
        
        if (confirm("¿Estás seguro de que deseas eliminar este elemento?")) {          
                        
            let datos = new FormData();
            datos.append('id', id);                     
            
            toggleModal('modal-loadajax');

            $.ajax({
                type: 'POST',
                url: urlCompleta + '/FacturasCliente/eliminarFactura',
                dataType: "json",
                data: datos,
                processData: false, 
                contentType: false, 
            }).done(function(respuesta){  

                toggleModal('modal-loadajax');
                                                              
                    if (respuesta.error === false) {
                        fila.remove();
                        Swal.fire({
                            title: 'Proceso correcto',
                            text: respuesta.mensaje,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                                            
                    } else {
                        
                        Swal.fire({
                            title: 'Error',
                            text: respuesta.mensaje,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }                             

            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud:", error);
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo completar la eliminación.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                
                toggleModal('modal-loadajax');  
                
            });   
          
        }
    });
    

}
