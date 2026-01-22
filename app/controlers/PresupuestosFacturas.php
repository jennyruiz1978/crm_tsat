<?php

class PresupuestosFacturas extends Controlador {   

    public function __construct() {
        
        session_start();
        $this->controlPermisos();
        $this->ModelPresupuestosFacturas = $this->modelo('ModeloPresupuestosFacturas');
        $this->ModelFacturasDetalleCliente = $this->modelo('ModeloFacturasDetalleCliente');
        $this->ModelIncidencias = $this->modelo('ModeloIncidencias');
    }

   /*  
    public function construirSelectEstado() {
        
        $html = "<option disabled selected>Seleccionar</option>";
        if ($_POST['idIncidencia']) {

            $estado = $this->ModelPresupuestosFacturas->obtenerEstadoPresupuestoFacturacion($_POST['idIncidencia']);
            $todosLosEstados = $this->ModelPresupuestosFacturas->obtenerTodosLosEstadoPresupuestoFacturacion();            
            
            $idEstado = $estado->estadofactppto;              
            
            foreach ($todosLosEstados as $key) {
                if ($key->id != $idEstado) {
                    $html .= "<option value='".$key->id."'>".$key->estado."</option>";
                }
            }
        }       
        print(json_encode($html));
    }
 */
    
    public function construirSelectEstado() {
        
        $html = "<option disabled selected>Seleccionar</option>";
        if ($_POST['idIncidencia']) {

            $estado = $this->ModelPresupuestosFacturas->obtenerEstadoPresupuestoFacturacion($_POST['idIncidencia']);
            $todosLosEstados = $this->ModelPresupuestosFacturas->obtenerTodosLosEstadoPresupuestoFacturacion();            
            
            $idEstado = $estado->estadofactppto;              
            
            foreach ($todosLosEstados as $key) {
                if ($key->id != $idEstado) {
                    $html .= "<option value='".$key->id."'>".$key->estado."</option>";
                }
            }
        }       
        $estadoFirma = $this->ModelIncidencias->obtenerEstadoYFirma($_POST['idIncidencia']);
        $final = ['estadosSelect'=>$html, 'estadoFirma'=> $estadoFirma];

        print(json_encode($final));
    }

    public function construirSelectEstadoYComentario()
    {
        $html = "<option disabled selected>Seleccionar</option>";
        $comentariosHtml = "";

        if ($_POST['idIncidencia']) {

            $estado = $this->ModelPresupuestosFacturas->obtenerEstadoPresupuestoFacturacion($_POST['idIncidencia']);
            $todosLosEstados = $this->ModelPresupuestosFacturas->obtenerTodosLosEstadoPresupuestoFacturacion();            
            
            $idEstado = $estado->estadofactppto;              
            
            foreach ($todosLosEstados as $key) {
                if ($key->id != $idEstado) {
                    $html .= "<option value='".$key->id."'>".$key->estado."</option>";
                }
            }

            $comentarios = $this->ModelPresupuestosFacturas->obtenerComentariosFacturarPresupuestar($_POST['idIncidencia']);

            if (isset($comentarios) && count($comentarios) >0) {
                foreach ($comentarios as $key) {
                    $comentariosHtml .= "<div class='w-full py-2 px-3 rounded-lg border-2 border-coolGray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent'>El <span>".$key->fecha."</span> <span class='italic'>".$key->remitente." </span> cambió el estado a <span class='italic'> ".$key->estado." </span> y comentó: <span class='italic'>".$key->comentario." </span> </div>";
                                  
                }
            }            
        }
        $final = ['estadosSelect'=>$html, 'comentariosHtml'=> $comentariosHtml];
        print(json_encode($final));
    }

    public function cambiarEstadoFacturarPresupuestar()
    {        
        $retorno = ['idincidencia'=> '', 'guardar'=>0];      

        if (isset($_POST['estado']) && $_POST['estado'] != '' && isset($_POST['idIncidencia']) && $_POST['idIncidencia'] != '') {

            $lineas_facturadas = $this->ModelFacturasDetalleCliente->contarLineasPreFacturaFacturadas($_POST['idIncidencia']);

            if($lineas_facturadas > 0 && ( $_POST['estado'] != 3 && $_POST['estado'] != 7 )){
                
                $retorno = ['idincidencia'=> $_POST['idIncidencia'], 'guardar'=> 2];

            }else{                

                $idIncidencia = $_POST['idIncidencia'];
                $idEstado = $_POST['estado'];
                $comentario = '';
                if (isset($_POST['comentarioDelFacturador'])) {
                    $comentario = $_POST['comentarioDelFacturador'];
                }
                
                $nombreEstado = $this->ModelPresupuestosFacturas->obtenerNombreEstadoPresupuestoFacturacionPorIdEstado($idEstado);
    
                $upd = $this->ModelPresupuestosFacturas->actualizarEstadoFacturaPresupuesto($idIncidencia, $idEstado, $nombreEstado);
                if ($upd == 1) {
                    $this->ModelPresupuestosFacturas->insertarDatosAHistorialEstadosFacturarPresupuestar($idIncidencia, $idEstado, $_SESSION['idusuario'], $comentario);
                    if ($_POST['estado'] >= 1 && $_POST['estado'] <= 6) {
                        $datosSolicitudCrear['idIncidencia'] = $idIncidencia;  
                        $datosSolicitudCrear['comentario'] = $comentario; 
                        $datosSolicitudCrear['creacion'] = date("Y-m-d H:i:s");
                        $datosSolicitudCrear['nombreEstado'] = $nombreEstado;
                        $this->enviarEmailCambioEstadoPresupuestoALosAdministradores($datosSolicitudCrear);
                    }
    
                    $retorno = ['idincidencia'=> $idIncidencia, 'guardar'=> 1];
                }
    

            }
            
        }        
        print(json_encode($retorno));
    }

    public function enviarEmailCambioEstadoPresupuestoALosAdministradores($datos)
    {          
        if(EMPRESA == 'INFOMALAGA'){

            $plantilla = $this->doCurl(RUTA_URL."/public/documentos/plantillasCorreo/plantillaboton.php");

            $nombreRemitente = 'InfoMalaga';
            $emailRemitente = CUENTA_CORREO;
            $cambioEstado = $datos['nombreEstado'];
            $asunto = "Notificación de cambio de estado a ".$cambioEstado;
            $idIncidencia = $datos['idIncidencia'];
            $nombreUsuario =  $_SESSION['usuario'];
    
            $comentario = '';
            if ($datos['comentario'] != '') {
                $comentario = 'y le ha dejado el siguiente comentario: '.$datos['comentario'];
            }
                
            $idsAdministradores = [2=>CUENTA_CORREOADMINISTRACION1, 4=>CUENTA_CORREOADMINISTRACION2];        
        
            foreach ($idsAdministradores as $idUsuario => $cuenta) {
                      
                $emailsDestino = [];
                $emailsDestino[] = $cuenta;
    
                //construyo cuerpo de mensaje    
                $fecha = date('d-m-Y H:i',strtotime($datos['creacion']));
                $enlace = 'Haz click en el enlace para ir a la plataforma Infomalaga.';
                $contenido = 'Con fecha '.$fecha.', el usuario '.$nombreUsuario.' ha cambiado el estado a '.$cambioEstado.' para la solicitud Nº '.$idIncidencia.' '.$comentario;
                $info = $idIncidencia.'/'.$idUsuario;
    
                $cambiar = ['{ENLACE}','{CONTENIDO}','{INCIDENCIA}'];
                $cambio = [$enlace, $contenido, $info];
                $mensaje = str_replace($cambiar,$cambio,$plantilla);
    
                $message = html_entity_decode($mensaje);
                        
                $tipoDoc = '';
                $attachment = '';
    
                enviarEmail::enviarEmailDestinatario($nombreRemitente, $emailRemitente, $emailsDestino, $asunto, $message, $attachment, $tipoDoc);
            }             

        }
                       
    }

    public function enviarSolicitudDePresupuestoAAdmin()
    {      
        $retorno = ['enviado'=> 0];
        if (isset($_POST['idIncidencia']) && $_POST['idIncidencia'] >0 && isset($_POST['comentario']) && $_POST['comentario'] != '') {
            $datos = [];
            $datos['idIncidencia'] = $_POST['idIncidencia'];            
            $comentario = '';
            if (isset($_POST['comentario'])) {
                $comentario = $_POST['comentario'];
            }        
            $datos['comentario'] = $comentario; 
            $datos['creacion'] = date("Y-m-d H:i:s");

            $ins = $this->ModelPresupuestosFacturas->crearPresupuestoParaCliente($_POST['idIncidencia'],$_POST['comentario'],$_SESSION['idusuario']);
            if ($ins == 1) {
                $this->ModelPresupuestosFacturas->actualizarEstadoFacturaPresupuestoEnEdicion($ins,2,'presupuestar');
                $this->enviarEmailSolicitudDePresupuestoALosAdministradores($datos);
            }            
            $retorno = ['enviado'=> 1];                                                
        }
        print(json_encode($retorno));
    }

    public function enviarEmailSolicitudDePresupuestoALosAdministradores($datos)
    {          
        if(EMPRESA == 'INFOMALAGA'){

            $plantilla = $this->doCurl(RUTA_URL."/public/documentos/plantillasCorreo/plantillaboton.php");

            $nombreRemitente = 'InfoMalaga';
            $emailRemitente = CUENTA_CORREO;
            $asunto = "Solicitud de presupuesto";
            $idIncidencia = $datos['idIncidencia'];
            $nombreUsuario =  $_SESSION['usuario'];                                
                
                        $idsAdministradores = [2=>CUENTA_CORREOADMINISTRACION1, 4=>CUENTA_CORREOADMINISTRACION2];                
                    
                        foreach ($idsAdministradores as $idUsuario => $cuenta) {
                                $emailsDestino = [];
                                $emailsDestino[] = $cuenta;
        
                                //construyo cuerpo de mensaje    
                                $fecha = date('d-m-Y H:i',strtotime($datos['creacion']));
                                $enlace = 'Haz click en el enlace para ir a la plataforma Infomalaga.';
                                $contenido = 'El usuario '.$nombreUsuario.' ha solicitado un presupuesto en la solicitud Nº '.$idIncidencia.' con fecha '.$fecha.' y le ha dejado el siguiente comentario: '.$datos['comentario'];
                                $info = $idIncidencia.'/'.$idUsuario;
        
                                $cambiar = ['{ENLACE}','{CONTENIDO}','{INCIDENCIA}'];
                                $cambio = [$enlace, $contenido, $info];
                                $mensaje = str_replace($cambiar,$cambio,$plantilla);
                                
                                $message = html_entity_decode($mensaje);
                            
                                $tipoDoc = '';
                                $attachment = '';
                                          
                                enviarEmail::enviarEmailDestinatario($nombreRemitente, $emailRemitente, $emailsDestino, $asunto, $message, $attachment, $tipoDoc);
                        }
                           

        }
                        
    }

    public function obtenerSolicitudesFactYPresYAceptYPptos() 
    {
            $retorno = [
                'facturar' =>0,
                'presupuestar' =>0,
                'aceptados' =>0                
            ]; 
                     
            $facturar = 0;
            $presupuestar = 0;
            $aceptados = 0;           

            if ($_SESSION['nombrerol'] == 'tecnico') {            
                $facturar = $this->ModelPresupuestosFacturas->contarSolicitudesSegunEstadoFactPresupuestar($_SESSION['idusuario'],1);
                $presupuestar = $this->ModelPresupuestosFacturas->contarSolicitudesSegunEstadoFactPresupuestar($_SESSION['idusuario'],2);
                $aceptados = $this->ModelPresupuestosFacturas->contarSolicitudesSegunEstadoFactPresupuestar($_SESSION['idusuario'],5);
                
                $retorno = [
                    'facturar' =>$facturar,
                    'presupuestar' =>$presupuestar,
                    'aceptados' =>$aceptados                    
                ];

            }else if($_SESSION['nombrerol'] == 'admin'){
                $facturar = $this->ModelPresupuestosFacturas->contarTodasSolicitudesSegunEstadoFactPresupuestar(1);
                $presupuestar = $this->ModelPresupuestosFacturas->contarTodasSolicitudesSegunEstadoFactPresupuestar(2);
                $aceptados = $this->ModelpptosClientes = 0;
                $retorno = [
                    'facturar' =>$facturar,
                    'presupuestar' =>$presupuestar,
                    'aceptados' =>$aceptados                    
                ];                
            }          
            echo json_encode($retorno);    
    }

    public function mostrarListadoSolicitudesPorEstado()
    {
        $retorno = [
            'respuesta' =>0,
            'html' =>''            
        ];

        if(isset($_SESSION['idusuario']) && $_SESSION['idusuario'] >0 && isset($_POST['estado']) && $_POST['estado'] !=''){
            $idUsuario = $_SESSION['idusuario'];
            $idEstado = $_POST['estado'];

            $detalles = [];
            if ($_SESSION['nombrerol'] == 'tecnico') {                                         
                $detalles = $this->ModelPresupuestosFacturas->incidenciasPorEstadoPorUsuario($idUsuario,$idEstado);
            }else if($_SESSION['nombrerol'] == 'admin'){
                $detalles = $this->ModelPresupuestosFacturas->incidenciasTodasPorEstado($idEstado);
            }            

            $html = '';            
            if (isset($detalles) && count($detalles)>0) {
                foreach ($detalles as $key) {                    
                    $html .= "
                            <tr class='mb-2 sm:mb-0'>
                                <td class='border-grey-light border hover:bg-gray-100 p-1'>".$key->idincidencia."</td>
                                <td class='border-grey-light border hover:bg-gray-100 p-1'>".$key->nombrecliente."</td>s                                
                                <td class='border-grey-light border hover:bg-gray-100 p-1'>                                    
                                    <div class='flex-1'>
                                        <form action='".RUTA_URL."/Incidencias/editarIncidencia' method='POST' title='ver'>
                                            <input type='number' class='hidden' name='id' value='".$key->idincidencia."'>
                                            <button type='submit' class='btnActualizar'><i class='fas fa-eye mr-2 fill-current text-yellow-500  text-sm xl:text-xl'></i></button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                    ";
                }
                    
                $retorno = [
                    'respuesta' =>1,
                    'html' => $html        
                ];
            }         
        }
        echo json_encode($retorno);    
    }

    


}
