<?php

require '../public/librerias/PHPMailer-master/src/Exception.php';
require '../public/librerias/PHPMailer-master/src/PHPMailer.php';
require '../public/librerias/PHPMailer-master/src/SMTP.php'; 
      
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class enviarEmail {

                          
    public static function enviarEmailDestinatario($nombreRemitente, $emailRemitente, $emailDestinatario,$asunto,$message,$attachment='',$tipoDoc,$datos='') 
    {      
    //return 1;   

      try {
        
        /*
        echo"entra";
        echo"<br>nombreRemitente: <br>";
        print_r($nombreRemitente);echo"<br><br>";
        echo"<br>emailRemitente: <br>";
        print_r($emailRemitente);echo"<br><br>";
        echo"<br>emailDestinatario: <br>";
        print_r($emailDestinatario);echo"<br><br>";
        echo"<br>asunto: <br>";
        print_r($asunto);echo"<br><br>";
        echo"<br>message: <br>";
        print_r($message);echo"<br><br>";
        echo"<br>attachment: <br>";
        print_r($attachment);echo"<br><br>";
        echo"<br>tipoDoc: <br>";
        print_r($tipoDoc);echo"<br><br>";
        echo"<br>datos: <br>";
        print_r($datos='');echo"<br><br>";
        die;
        */
        
        

        $mail = new PHPMailer;  
        // Activo condificacción utf-8
        $mail->CharSet = 'UTF-8';        
        //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only       
        $mail->IsSMTP(); // enable SMTP        
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );          
          
        $mail->SMTPSecure = PROTOCOLO; // secure transfer enabled REQUIRED for Gmail 
        $mail->Host = HOST_CORREO;        
        $mail->Port = PUERTO;
        $mail->IsHTML(true);
        $mail->Username = $emailRemitente;
        
        $mail->Password = PASSWORD_CORREO;
        $mail->Sender = $emailRemitente;
        
        $mail->SetFrom($emailRemitente,$nombreRemitente);

        $mail->Encoding = 'quoted-printable';
        $mail->Subject = html_entity_decode($asunto);
        $mail->Body = html_entity_decode($message);        
        
        $emails = $emailDestinatario;
        $nombreDestinatario = '';
        if ($attachment !='') {
          $mail->AddStringAttachment($attachment, $tipoDoc.'.pdf');
        }
        
        foreach ($emails as $row) {   

          $mail->addAddress($row, $nombreDestinatario);                     
        }   
      

        if (!$mail->Send()) {

          /*  
              echo"<br>1<br><pre>";
              print_r($mail);
              die;
            */
          return 0;      
        } else {    
          /* 
          echo"<br>2<br>";
          print_r($mail);
          die;     
          */      
          return 1;
        }
       
                 
      } catch (Exception $exception) {
        return $exception->getMessage();
      }
      
    }

    public static function enviarEmailDocumento($nombreRemitente, $emailRemitente, $emailDestinatario,$asunto,$message,$attachments=[],$tipoDoc,$datos='') 
    {      
    //return 1;   

      try {
        
        /*
        echo"entra";
        echo"<br>nombreRemitente: <br>";
        print_r($nombreRemitente);echo"<br><br>";
        echo"<br>emailRemitente: <br>";
        print_r($emailRemitente);echo"<br><br>";
        echo"<br>emailDestinatario: <br>";
        print_r($emailDestinatario);echo"<br><br>";
        echo"<br>asunto: <br>";
        print_r($asunto);echo"<br><br>";
        echo"<br>message: <br>";
        print_r($message);echo"<br><br>";
        //echo"<br>attachment: <br>";
        //print_r($attachments);echo"<br><br>";
        echo"<br>tipoDoc: <br>";
        print_r($tipoDoc);echo"<br><br>";
        echo"<br>datos: <br>";
        print_r($datos='');echo"<br><br>";
        */            

        $mail = new PHPMailer;  
        // Activo condificacción utf-8
        $mail->CharSet = 'UTF-8';        
        $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only       
        $mail->IsSMTP(); // enable SMTP        
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );          
          
        $mail->SMTPSecure = PROTOCOLO; // secure transfer enabled REQUIRED for Gmail 
        $mail->Host = HOST_CORREO;        
        $mail->Port = PUERTO;
        $mail->IsHTML(true);
        $mail->Username = $emailRemitente;
        
        $mail->Password = PASSWORD_CORREO;
        $mail->Sender = $emailRemitente;
        
        $mail->SetFrom($emailRemitente,$nombreRemitente);

        //$mail->Encoding = 'quoted-printable';
        $mail->Subject = html_entity_decode($asunto);
        $mail->Body = html_entity_decode($message);        
        
        $emails = $emailDestinatario;
        $nombreDestinatario = '';
       
        
        foreach ($attachments as $attachment) {
          $mail->AddStringAttachment($attachment['documento'], $attachment['nombreDocumento']);         
        } 
        foreach ($emails as $row) {   
          $mail->addAddress($row, $nombreDestinatario);                     
        }   
      
        $mail->CharSet = 'UTF-8';
        if (!$mail->Send()) {

              /*
              echo"<br>entra al if<br><pre>";
              print_r($mail);
              die;
              */
            
          return 0;      
        } else {    
          
          /*
          echo"<br>entra al else<br>";
          print_r($mail);
          die;
          */   
              
          return 1;
        }
       
                 
      } catch (Exception $exception) {
        return $exception->getMessage();
      }
      
    }

      

  }