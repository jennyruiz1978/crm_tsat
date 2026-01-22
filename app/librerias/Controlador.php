<?php

// clase controlador principal
// se encarga de poder cargra los modelos y las vistas
class Controlador
{

    // cargar el modelo
    public function modelo($modelo)
    {
        // carga modelo
        require_once('../app/models/' . $modelo . '.php');
        // instanciamos el modelo
        return new $modelo();
    }

    // cargar vista
    public function vista($vista, $datos = [])
    {

        // chequear si el archivo vista existe
        if (file_exists('../app/views/' . $vista . '.php')) {
            require_once('../app/views/' . $vista . '.php');
        } else {
            // si no existe el archivo nos da un mensaje
            die("la vista no existe");
        }
    }




    function eliminar_tildes($cadena)
    {


        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena
        );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena
        );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena
        );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena
        );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        return $cadena;
    }

    public function limpiar_string($a)
    {

        $salida = $this->eliminar_tildes($a);
        $salida = strtolower(str_replace('&', '', $salida));
        $salida = str_replace('.', '', $salida);
        $salida = str_replace(' ', '', $salida);
        $salida = str_replace('¿', '', $salida);
        $salida = str_replace('?', '', $salida);
        $salida = str_replace('(', '', $salida);
        $salida = str_replace(')', '', $salida);
        $salida = str_replace('-', '', $salida);
        $salida = str_replace('_', '', $salida);
        $salida = str_replace('/', '', $salida);

        return $salida;
    }

    public function controlPermisos()
    {
        if (in_array($_SERVER['REQUEST_URI'], $_SESSION['controlLinksUsuario']) == False) {
            session_unset();
            session_destroy();
            if (headers_sent()) {
                return "<script>window.location.href=" . RUTA_URL . "</script>";
            } else {
                redireccionar('/Login');
            }
        }
    }

    public function doCurl($url){
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'CRM Infomalaga');
        $plantilla = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $plantilla;
    }
    

}
