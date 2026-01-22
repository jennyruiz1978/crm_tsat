<?php
if(in_array($_SERVER['REQUEST_URI'], $_SESSION['controlLinksUsuario']) == False){
        session_unset();
        session_destroy();
        if(headers_sent()){
        return "<script>window.location.href=" . RUTA_URL . "</script>";    
        } else {
        redireccionar('/login');    
        }
}

?>