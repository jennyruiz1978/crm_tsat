<?php

class Login extends Controlador {

    public function __construct() {
        $this->ModeloLogin = $this->modelo('ModeloLogin');
    }

    public function index() {                       
        $this->vista('login/login');
    }
    

    public function acceder() {

       
        if ($_SERVER['REQUEST_METHOD'] ) {
            $mail = $_POST['mail'];
            $pass = $_POST['pass'];
        }

      
        $validacion = $this->ModeloLogin->comprobarLogin($mail, $pass);
        
        if ($validacion == false) {
              redireccionar('/Login');              
        } else {

            $idUsuario = $this->ModeloLogin->identificadorUsuario($mail, $pass);

            //verificar si debe cambiar constraseña
            $datosUser = $this->ModeloLogin->verificarSiExigeCambioPassword($idUsuario->id);
            
            if ($datosUser->cambiar == 1) {      //debe cambiar la contraseña          
                $variable = '<script type="text/javascript">window.location.href="'.RUTA_URL."/Login/cambioContrasenia/2/".$idUsuario->id.'"</script>';                
                echo $variable;
                               
            }else{

              

                $control = [
                    "usuario" => $idUsuario
                ];
                //print_r($idUsuario);
                //echo "<br><br>";
                $permisos = $this->ModeloLogin->consultaPermisos($control['usuario']->rol);
                $permisosUsuario = json_decode($permisos);
                
              

                $linksUsuario = [];
                foreach($permisosUsuario as $links){
                    if(isset($links[3])){
                        for($i=0;$i<count($links[3]);$i++){
                            array_push($linksUsuario,RUTA_PERMISOS . $links[3][$i][0]);
                            if(is_array($links[3][$i]) == true && count($links[3][$i]) > 2){
                                for($j=2;$j<(count($links[3][$i]));$j++){
                                    array_push($linksUsuario,RUTA_PERMISOS . $links[3][$i][$j][0]);
                                }
                            }
                        }  
                    
                    
                    }   
                }
                //echo "<br><br>";
                //print_r($linksUsuario);
                //die;

                
                //para que la session persista:
                //session_set_cookie_params(60*60*12); //12horas

                session_start();
                $_SESSION['usuario'] = $validacion;
                $_SESSION['nombrerol'] = $datosUser->nombrerol;
                $_SESSION['idusuario'] = $datosUser->id;
                $_SESSION['token_control'] = 1;
                $_SESSION['permisos'] = $permisosUsuario;
                $_SESSION['controlLinksUsuario'] = $linksUsuario;
                $_SESSION['inicio'] = date("Y-n-j H:i:s"); 

                if ($idUsuario->rol == 1 || $idUsuario->rol ==2 ) { //vista cliente ó técnico                   
                    redireccionar('/Incidencias');
                    
                }else{
                    redireccionar('/Inicio'); //vista Admin
                }
                

            }
        }
    } 


    public function vaciar(){
        session_start();
        session_unset();
        session_destroy();
        if(headers_sent()){
        return "<script>window.location.href=" . RUTA_URL . "</script>";    
        } else {
        redireccionar('/Login');    
        }
        
    }

    public function solicitarRecuperarContrasenia()
    {
        $this->vista('login/recuperarContrasenia');
    }
      
    public function validaEmailParaRecuperarContrasenia()
    {     

        $retorno = 0;

        if ($_POST['email']) {
            
            $email = trim($_POST['email']);

            $datosUser = $this->ModeloLogin->datosUsuarioPorEmail($email);           
                        
            if ($datosUser && $datosUser->id >0) {                            

                    //contruyo array con datos de envío:
                    $nombreRemitente = 'InfoMalaga';
                    $emailRemitente = CUENTA_CORREO;
                    $asunto = "Recuperar contraseña";
                    $emailsDestino = [$email];
                    $plantilla = $this->doCurl(RUTA_URL."/public/documentos/plantillasCorreo/plantillabotonContrasenia.php");
                           
                    date_default_timezone_set("Europe/Madrid");
                    $fechaActual = date("d-m-Y H:i");        
                    $diaSiguiente = date("d-m-Y H:i",strtotime($fechaActual."+ 1 days"));   
                    
                    //construyo cuerpo de mensaje
                    $enlace = 'Haz click en el enlace para cambiar la contraseña.';
                    $contenido = 'Has solicitado reestablecer la contraseña de tu cuenta para acceder a la plataforma de Infomálaga. Este enlace estará caducará el '.$diaSiguiente;
                                                           
                    $info = RUTA_URL."/Login/cambioContrasenia/1/".$datosUser->id;
        
                    $cambiar = ['{ENLACE}','{CONTENIDO}','{RUTAWEB}'];
                    $cambio = [$enlace, $contenido, $info];
                    $mensaje = str_replace($cambiar,$cambio,$plantilla);
                    
                    $message = html_entity_decode($mensaje);

                    $tipoDoc = '';
                    $attachment = '';

                    $envioEmail = enviarEmail::enviarEmailDestinatario($nombreRemitente, $emailRemitente, $emailsDestino, $asunto, $message, $attachment, $tipoDoc);                

                    if ($envioEmail) {
                        $retorno = 1;
                        date_default_timezone_set("Europe/Madrid");
                        $caducidadEnlace = date('Y-m-d H:i:s');
                        $this->ModeloLogin->modificarCampoCambiarYCaducidadEnlace($datosUser->id,$caducidadEnlace);
                    }else{
                        $retorno = 0;
                    }
                
            }else{                
                $retorno = 0;
            }
        }else{
            $retorno = 0;
        }
        
                
        echo json_encode($retorno);
        
    }

    public function cambioContrasenia($cambiar, $id)
    {    
    
        if($cambiar == 1 && $id >0){
            $existeUser = $this->ModeloLogin->datosUsuarioPorId($id);            
            $fechaInicio = $existeUser->caducidadenlace;
            $fechaFinalizacion = date('Y-m-d H:i:s');
           
            
            $horas = $this->calculoDeTiempoEnHoras($fechaInicio,$fechaFinalizacion);
          

            $datos = [
                'mail' => $existeUser->correo,
                'id' => $existeUser->id,
                'horas' => round($horas)
            ];

            if ($existeUser && $existeUser->id >0) {
                $this->vista('login/cambioContrasenia',$datos);        
            }else{
                redireccionar('/Login');
            }

        }else if($cambiar == 2 && $id >0){
            $existeUser = $this->ModeloLogin->datosUsuarioPorId($id);          

            $datos = [
                'mail' => $existeUser->correo,
                'id' => $existeUser->id                
            ];

            if ($existeUser && $existeUser->id >0) {
                $this->vista('login/cambioContrasenia',$datos);        
            }else{
                redireccionar('/Login');
            }
        }else{
            redireccionar('/Login');
        }
        
    }



    public function calculoDeTiempoEnHoras($fechaInicio,$fechaFinalizacion)
    {
        $firstDate  = new DateTime($fechaInicio);
        $secondDate = new DateTime($fechaFinalizacion);
        $intvl = $firstDate->diff($secondDate);

        $dias = ($intvl->d) *24 ;   
        $horas = ($intvl->h);
        $minutos =  ($intvl->i) / 60;
        $segundos =  ($intvl->s) / 3600;
        $totalHoras = $dias + $horas + $minutos + $segundos;
        return $totalHoras;
    }

    public function ejecutarCambioContrasenia()
    {        
        $retorno = 0;
        
        /*print_r(base64_decode($_POST['password'])); //aqui me quedé terminar eesto
        echo"<br><br>";*/

        if ($_POST['id'] && $_POST['password'] && $_POST['repite'] && ($_POST['password'] == $_POST['repite']) ) {
            
            $datos = [
                'password' => base64_decode($_POST['password']),                
                'id' => $_POST['id']
            ];

            $update = $this->ModeloLogin->actualizarContraseniaUsuario($datos);
            
            if ($update) {                
                $retorno = 1;                             
            }else{
                $retorno = 0;
            }
        }else{
            $retorno = 0;
        }
        echo json_encode($retorno);        
    }

    public function validarCorreoYPasword()
    {        
        $retorno = ['respuesta'=>0];

        if ($_POST['email'] && $_POST['password']) {
            $email = $this->ModeloLogin->datosUsuarioPorEmail($_POST['email']);

            if (!$email) {
                $retorno = ['respuesta'=>2]; //correo no válido
            }else{
                $pass = $this->ModeloLogin->verificarEmailYPassword($_POST['email'], $_POST['password']);
                if ($pass) {
                    $retorno = ['respuesta'=>1]; //ambos son válidos
                }else{
                    $retorno = ['respuesta'=>3]; //password no válido
                }
            }
        }        
        echo json_encode($retorno);
    }

    public function verIncidencia($idIncidencia=false, $idUsuario=false)
    {
       

        if ($idIncidencia == false || $idUsuario == false) {           
            redireccionar('/Login');
        }else{           
            $email = $this->ModeloLogin->obtenerCorreoPorIdusuario($idUsuario);
			
            if (isset($email) && $email != '') {
                $datos = [
                    'idIncidencia' => $idIncidencia,
                    'idUsuario' => $idUsuario,
                    'email' => $email
                ];
                $this->vista('login/loginVerIncidencia',$datos);
            }else{
                redireccionar('/Login');
            }

            
        }
    }



}
