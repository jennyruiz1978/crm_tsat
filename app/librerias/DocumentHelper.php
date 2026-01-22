<?php

class DocumentHelper {       

    public static function buildNumberDocumentAntes($currentInternalNumber, $fecha)
    {                                        
        $post['numerointerno'] = $currentInternalNumber;                
        $post['numero'] = $currentInternalNumber."/".date("Y",strtotime($fecha));
        return $post;
    }

    public static function buildNumberDocument($currentInternalNumber, $fecha, $rectificativa = 0)
    {                                        
        $post['numerointerno'] = $currentInternalNumber;

        $numeroBase = $currentInternalNumber . "/" . date("Y", strtotime($fecha));

        // Si es rectificativa, añadimos "R"
        if ($rectificativa == 1) {
            $numeroBase .= "R";
        }

        $post['numero'] = $numeroBase;

        return $post;
    }

}