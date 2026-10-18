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

      try {
        
        $mail = new PHPMailer;  
        // Activo condificacción utf-8
        $mail->CharSet = 'UTF-8';        
        $mail->SMTPDebug = SMTP_DEBUG_NIVEL;
        $GLOBALS['smtpDebugLog'] = '';
        $mail->Debugoutput = function($str, $level) {
            $GLOBALS['smtpDebugLog'] .= $str . "\n";
        };        
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

          $logMsg = date('Y-m-d H:i:s') . " | ERROR ENVIO EMAIL | ErrorInfo: " . $mail->ErrorInfo
                  . " | Debug: " . $GLOBALS['smtpDebugLog'] . "\n";
          file_put_contents(RUTA_APP . '/logs/email_errors.log', $logMsg, FILE_APPEND);
          return 0;      
        } else {    

          return 1;
        }
        
                  
      } catch (Exception $exception) {
        $logMsg = date('Y-m-d H:i:s') . " | EXCEPCION ENVIO EMAIL | " . $exception->getMessage()
                . " | Debug: " . (isset($GLOBALS['smtpDebugLog']) ? $GLOBALS['smtpDebugLog'] : '') . "\n";
        file_put_contents(RUTA_APP . '/logs/email_errors.log', $logMsg, FILE_APPEND);
        return $exception->getMessage();
      }
      
    }

    public static function enviarEmailDocumento($nombreRemitente, $emailRemitente, $emailDestinatario,$asunto,$message,$attachments=[],$tipoDoc,$datos='') 
    {      
    //return 1;   

      try {        
       
        $mail = new PHPMailer;  
        // Activo condificacción utf-8
        $mail->CharSet = 'UTF-8';        
        $mail->SMTPDebug = SMTP_DEBUG_NIVEL;
        $GLOBALS['smtpDebugLog'] = '';
        $mail->Debugoutput = function($str, $level) {
            $GLOBALS['smtpDebugLog'] .= $str . "\n";
        };
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

          $logMsg = date('Y-m-d H:i:s') . " | ERROR ENVIO EMAIL DOCUMENTO | ErrorInfo: " . $mail->ErrorInfo
                  . " | Debug: " . $GLOBALS['smtpDebugLog'] . "\n";
          file_put_contents(RUTA_APP . '/logs/email_errors.log', $logMsg, FILE_APPEND);
          return 0;
        } else {

          return 1;
        }
       
                 
      } catch (Exception $exception) {
        $logMsg = date('Y-m-d H:i:s') . " | EXCEPCION ENVIO EMAIL DOCUMENTO | " . $exception->getMessage()
                . " | Debug: " . (isset($GLOBALS['smtpDebugLog']) ? $GLOBALS['smtpDebugLog'] : '') . "\n";
        file_put_contents(RUTA_APP . '/logs/email_errors.log', $logMsg, FILE_APPEND);
        return $exception->getMessage();
      }
      
    }

      

  }