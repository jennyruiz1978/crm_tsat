<?php

class Inicio extends Controlador {

   

    public function __construct() {
        session_start();
        $this->controlPermisos();
        $this->ModelInicio = $this->modelo('ModeloInicio');
        $this->ModeloLogin = $this->modelo('ModeloLogin');
    
    }

    public function index() {   
        
        $fechaInicio = new DateTime();
        $fechaInicio->modify('first day of this month');
        $fechaIni = $fechaInicio->format('Y-m-d');

        $fechaFinal = new DateTime();
        $fechaFinal->modify('last day of this month');
        $fechaFin = $fechaFinal->format('Y-m-d');

        $anio = date('Y');

        $anios = $this->construirArrayDeAnios();
        $contadorRangoFechas = $this->ModelInicio->contarTotalIncidenciasRangoFechas($fechaIni,$fechaFin);
        $contadorAcumAnio = $this->ModelInicio->contarTotalIncidenciasAnio($anio);
        $tiempoRangoFechas = $this->ModelInicio->tiempoTotalIncidenciasRangoFechas($fechaIni,$fechaFin);
        $tiempoAnio = $this->ModelInicio->tiempoTotalIncidenciasAnio($anio);
        $pendientesRango = $this->ModelInicio->contarTotalIncidenciasPendientesRangoFechas($fechaIni,$fechaFin);
        $pendientesAnio = $this->ModelInicio->contarTotalIncidenciasPendientesAnio($anio);
        $clientesRango = $this->ModelInicio->contarTotalClientesAtendidosRangoFechas($fechaIni,$fechaFin);
        $clientesAnio = $this->ModelInicio->contarTotalClientesAtendidosAnio($anio);
        $equiposRango = $this->ModelInicio->contarTotalEquiposAtendidosRangoFechas($fechaIni,$fechaFin);
        $equiposAnio = $this->ModelInicio->contarTotalEquiposAtendidosAnio($anio);        

        $datos = [
           "contadorRango" => $contadorRangoFechas,
           'contadorAnio' => $contadorAcumAnio,
           'tiempoRango' => $tiempoRangoFechas,
           'tiempoAnio' => $tiempoAnio,
           'pendientesRango' => $pendientesRango,
           'pendientesAnio' => $pendientesAnio,
           'clientesRango' => $clientesRango,
           'clientesAnio' => $clientesAnio,
           'equiposRango' => $equiposRango,
           'equiposAnio' => $equiposAnio,
           'anios' => $anios
        ];
       
        $this->vista('inicio/inicio',$datos);
    }

    public function construirArrayDeAnios()
    {
        $anios = $this->ModelInicio->obtnenerAniosExistentes();
        return $anios;
    }

    public function consolidadoAtencionesPorModalidadRango()
    {      
        $fechaIni = '';
        $fechaFin = '';

        if (isset($_POST['fechaIni']) && isset($_POST['fechaFin'])) {
            $fechaIni = $_POST['fechaIni'];
            $fechaFin = $_POST['fechaFin'];
        }else{
            $fechaInicio = new DateTime();
            $fechaInicio->modify('first day of this month');
            $fechaIni = $fechaInicio->format('Y-m-d');
    
            $fechaFinal = new DateTime();
            $fechaFinal->modify('last day of this month');
            $fechaFin = $fechaFinal->format('Y-m-d');
        }

        $arrayDatos = $this->ModelInicio->consolidarAtencionesPorModalidadRangoFecha($fechaIni,$fechaFin);

        $salida = [];
        if (isset($arrayDatos) && count($arrayDatos)>0) {
            foreach ($arrayDatos as $row) {              
                $salida[$row->modalidad] = $row->horas;
            }
        }        
        print json_encode($salida);        
    }  

    public function consolidadoIncidenciasPorEstadoRango()
    {
        $fechaIni = '';
        $fechaFin = '';

        if (isset($_POST['fechaIni']) && isset($_POST['fechaFin'])) {
            $fechaIni = $_POST['fechaIni'];
            $fechaFin = $_POST['fechaFin'];
        }else{
            $fechaInicio = new DateTime();
            $fechaInicio->modify('first day of this month');
            $fechaIni = $fechaInicio->format('Y-m-d');
    
            $fechaFinal = new DateTime();
            $fechaFinal->modify('last day of this month');
            $fechaFin = $fechaFinal->format('Y-m-d');
        }

        $arrayDatos = $this->ModelInicio->consolidarIncidenciasPorEstadoRangoFecha($fechaIni,$fechaFin);

        $salida = [];
        if (isset($arrayDatos) && count($arrayDatos)>0) {
            foreach ($arrayDatos as $row) {              
                $salida[$row->estado] = $row->cont;
            }
        }
        print json_encode($salida);
    }



    public function recargarPastillasDashboardGeneral()
    {
        $fechaIni = '';
        $fechaFin = '';

        if (isset($_POST['fechaIni']) && isset($_POST['fechaFin'])) {
            $fechaIni = $_POST['fechaIni'];
            $fechaFin = $_POST['fechaFin'];
        }else{
            $fechaInicio = new DateTime();
            $fechaInicio->modify('first day of this month');
            $fechaIni = $fechaInicio->format('Y-m-d');
    
            $fechaFinal = new DateTime();
            $fechaFinal->modify('last day of this month');
            $fechaFin = $fechaFinal->format('Y-m-d');
        }

        $contadorRangoFechas = $this->ModelInicio->contarTotalIncidenciasRangoFechas($fechaIni,$fechaFin);
        $tiempoRangoFechas = $this->ModelInicio->tiempoTotalIncidenciasRangoFechas($fechaIni,$fechaFin);
        $pendientesRango = $this->ModelInicio->contarTotalIncidenciasPendientesRangoFechas($fechaIni,$fechaFin);
        $clientesRango = $this->ModelInicio->contarTotalClientesAtendidosRangoFechas($fechaIni,$fechaFin);
        $equiposRango = $this->ModelInicio->contarTotalEquiposAtendidosRangoFechas($fechaIni,$fechaFin);      
        
        $salida = [
            "incidenciasTotales" => $contadorRangoFechas->contador,
            'tiemposRealizados' => $tiempoRangoFechas->tiempototal,
            'incidenciasPendientes' => $pendientesRango->contador,
            'clientesAtendidos' => $clientesRango->contador,
            'equiposAtendidos' => $equiposRango->contador
        ];
        print json_encode($salida);
        
    }

    public function horasUtilizadasClientesBolsaHoras($anio)
    {     
        $datos = $this->ModelInicio->obtenerCosteHorasRealizadasClientesConBolsa($anio);               
        $meses = ["Ene"=>"1","Feb"=>"2","Mar"=>"3","Abr"=>"4","May"=>"5","Jun"=>"6","Jul"=>"7","Ago"=>"8","Set"=>"9","Oct"=>"10","Nov"=>"11","Dic"=>"12"];
        
        $salida = [];
        $tmp1 = [];
        $tmp2 = [];
        $tmp3 = [];
        $tmp4 = [];

        if (isset($datos) && count($datos)>0) {
            
            foreach ($datos as $key) {
                foreach ($meses as $mes => $ord) {
                    if ($key->mes == $ord) {                    
                        $tmp1[] = $key->rendimiento;
                        $tmp2[] = $key->tiemposrealizados;
                        $tmp3[] = $key->horascontratadas;
                        $tmp4[] = $anio.'-'.$key->mes.'-01';
                        $salida['Rend.(%)'] = $tmp1;
                        $salida['H. Realiz.'] = $tmp2;
                        $salida['H. Contrat.'] = $tmp3;
                        $salida['meses'] = $tmp4;
                    }
                }
                
            }     

        }

       return $salida;
    }

    public function horasUtilizadasClientesPrecioFijo($anio)
    {     
        $datos = $this->ModelInicio->obtenerCosteHorasRealizadasClientesPrecioFijo($anio);               
        $meses = ["Ene"=>"1","Feb"=>"2","Mar"=>"3","Abr"=>"4","May"=>"5","Jun"=>"6","Jul"=>"7","Ago"=>"8","Set"=>"9","Oct"=>"10","Nov"=>"11","Dic"=>"12"];
        
        $salida = [];
        $tmp1 = [];
        $tmp2 = [];
        $tmp3 = [];
        $tmp4 = [];

        
        if (isset($datos) && count($datos)>0) {
        
            foreach ($datos as $key) {
                foreach ($meses as $mes => $ord) {
                    if ($key->mes == $ord) {                    
                        $tmp1[] = $key->rendimiento;
                        $tmp2[] = $key->costeatenciones;
                        $tmp3[] = $key->totalcontratado;
                        $tmp4[] = $anio.'-'.$key->mes.'-01';
                        $salida['Rend.(%)'] = $tmp1;
                        $salida['Coste(€)'] = $tmp2;
                        $salida['Contrat.(€)'] = $tmp3;
                        $salida['meses'] = $tmp4;
                    }
                }
                
            }     
        }
       return $salida;
    }

    public function ingresosNetosMensualesAnio($anio) 
    {     
        $datos = $this->ModelInicio->obtenerIngresosNetosMensuales($anio);               
        $meses = ["Ene"=>"1","Feb"=>"2","Mar"=>"3","Abr"=>"4","May"=>"5","Jun"=>"6","Jul"=>"7","Ago"=>"8","Set"=>"9","Oct"=>"10","Nov"=>"11","Dic"=>"12"];
        
        $salida = [];
        $tmp1 = [];
        $tmp2 = [];
        $tmp3 = [];
        $tmp4 = [];

        
        if (isset($datos) && count($datos)>0) {
            
            foreach ($datos as $key) {
                foreach ($meses as $mes => $ord) {
                    if ($key->mes == $ord) {                    
                        $tmp1[] = $key->coste;
                        $tmp2[] = $key->totalingresos;
                        $tmp3[] = $key->ingresoneto;
                        $tmp4[] = $anio.'-'.$key->mes.'-01';
                        $salida['coste(€)'] = $tmp1;
                        $salida['Contrat.(€)'] = $tmp2;
                        $salida['Rentab.(€)'] = $tmp3;
                        $salida['meses'] = $tmp4;
                    }
                }
                
            }
        }
       return $salida;
    }

    public function datosGraficosApartadoRentabilidad()
    {
        $anio = date('Y');
        if (isset($_POST['anio']) && $_POST['anio'] >0) {
            $anio = $_POST['anio'];
        }
        $rentabilidadClientesBolsa = $this->horasUtilizadasClientesBolsaHoras($anio);
        $rentabilidadClientesPrecioFijo = $this->horasUtilizadasClientesPrecioFijo($anio);
        $ingresosNetosMensuales = $this->ingresosNetosMensualesAnio($anio);


        $final = [$rentabilidadClientesBolsa,$rentabilidadClientesPrecioFijo,$ingresosNetosMensuales];
        print json_encode($final);
    }

    public function construirDatosTablaIngresosClientesConBolsa($anio)
    {   
        $datos = $this->ModelInicio->obtenerCosteHorasRealizadasClientesConBolsa($anio);               
        $meses = ["Ene"=>"1","Feb"=>"2","Mar"=>"3","Abr"=>"4","May"=>"5","Jun"=>"6","Jul"=>"7","Ago"=>"8","Set"=>"9","Oct"=>"10","Nov"=>"11","Dic"=>"12"];

        $html = '';

        
        if (isset($datos) && count($datos)>0) {
        
            foreach ($datos as $key) {
                foreach ($meses as $mes => $ord) {
                    if ($key->mes == $ord) { 
                        $html .= "
                            <tr class='text-sm text-gray-700'>
                                <td class='px-2 py-1'>".$mes."</td>
                                <td class='px-2 py-1 text-center'>".$key->costeatencion."</td>
                                <td class='px-2 py-1 text-center'>".$key->totalEuros."</td>
                                <td class='px-2 py-1 text-center'>".$key->ingresado."</td>
                            <tr> 
                        ";     
                    }
                }            
            }

        }
        return $html;                         
    }

    public function construirDatosTablaIngresosClientesConPrecioFijo($anio)
    {   
        $datos = $this->ModelInicio->obtenerCosteHorasRealizadasClientesPrecioFijo($anio);               
        $meses = ["Ene"=>"1","Feb"=>"2","Mar"=>"3","Abr"=>"4","May"=>"5","Jun"=>"6","Jul"=>"7","Ago"=>"8","Set"=>"9","Oct"=>"10","Nov"=>"11","Dic"=>"12"];

        $html = '';

        
        if (isset($datos) && count($datos)>0) {
        
            foreach ($datos as $key) {
                foreach ($meses as $mes => $ord) {
                    if ($key->mes == $ord) { 
                        $html .= "
                            <tr class='text-sm text-gray-700'>
                                <td class='px-2 py-1'>".$mes."</td>
                                <td class='px-2 py-1 text-center'>".$key->costeatenciones."</td>
                                <td class='px-2 py-1 text-center'>".$key->totalcontratado."</td>
                                <td class='px-2 py-1 text-center'>".$key->beneficio."</td>
                            <tr> 
                        ";     
                    }
                }            
            }

        }
        return $html;                         
    }    

    public function construirDatosTablasApartadoRendimientos()
    {
        $anio = date('Y');
        if (isset($_POST['anio']) && $_POST['anio'] >0) {
            $anio = $_POST['anio'];
        }
       
        $tablaClientesConBolsa = $this->construirDatosTablaIngresosClientesConBolsa($anio);
        $tablaClientesPrecioFijo = $this->construirDatosTablaIngresosClientesConPrecioFijo($anio);

        $retorno = [         
            'clientesbolsa' => $tablaClientesConBolsa,
            'clientesfijo' => $tablaClientesPrecioFijo
        ];

        print json_encode($retorno);     
    }

    public function tiempos(){
        $this->vista('tiempos/tiempos');
    }

    public function datosGraficosApartadoClientes()
    {
        $anio = date('Y');
        if (isset($_POST['anio']) && $_POST['anio'] >0) {
            $anio = $_POST['anio'];
        }
        $numeroincidenciasmnesuales = $this->numeroDeIncidenciasMensuales($anio);
        $horasConsumidasClientesBolsaHoras = $this->numeroHorasConsumidasClientesBolsaMes($anio);
        $horasConsumidasEqMantenimiento = $this->numeroHorasConsumidasEquiposMantenimiento($anio);
        $tablaHorasClientes = $this->construirTablaHorasClientes($anio);

        $final = [$numeroincidenciasmnesuales,$horasConsumidasClientesBolsaHoras,$horasConsumidasEqMantenimiento,$tablaHorasClientes];
        print json_encode($final);
    }
   
   
    public function numeroDeIncidenciasMensuales($anio)
    {     
        $datos = $this->ModelInicio->obtenerNumeroIncidenciasMensuales($anio);
        $clientes = $this->ModelInicio->numeroClientesDiferentes($anio);
        
        $salida = [];
        $tmp1 = [];
        $tmp2 = [];
        $tmp3 = [];

        if (isset($datos) && count($datos)>0) {
            
            for ($i=0; $i < count($datos) ; $i++) { 
                $tmp1[] = $datos[$i]->numincidencias;                
                $tmp2[] = $clientes[$i]->numclientesdiferentes;
                $tmp3[] = $anio.'-'.$datos[$i]->mes.'-01';       
                $salida['Incidenc.'] =  $tmp1;
                $salida['Clientes'] =  $tmp2;             
                $salida['meses'] = $tmp3;           
            }          
        }        
        
        return $salida;

        //Al poner el año completo se distorsionan las graficas c3.js en dispositivos pequeños y medianos
       /*
        $salida = ['Incidenc.',0,0,0,0,0,0,0,0,0,0,0,0];

        for ($i=0; $i <count($datos) ; $i++) {             
            $salida[$datos[$i]->mes] = (int) $datos[$i]->numincidencias;                     
        }
                      
        $salida2 = ['Clientes',0,0,0,0,0,0,0,0,0,0,0,0];
        for ($j=0; $j <count($clientes) ; $j++) { 
            $salida2[$clientes[$j]->mes] = (int) $clientes[$j]->numclientesdiferentes;            
        }
                       
        $final['incidencias'] = $salida;
        $final['clientes'] = $salida2;         
        return $final;
        */
    }

    public function numeroHorasConsumidasEquiposMantenimiento($anio)
    {     
        $datos = $this->ModelInicio->obtenerNumeroHorasConsumidasEquipoMantenimiento($anio);        

        
        $salida = [];
        $tmp1 = [];        
        $tmp3 = [];

        if (isset($datos) && count($datos)>0) {
            
            for ($i=0; $i < count($datos) ; $i++) { 
                $tmp1[] = $datos[$i]->totalhorasrealizadas;                
                $tmp3[] = $anio.'-'.$datos[$i]->mes.'-01';       
                $salida['Hrs. Cons.'] =  $tmp1;                
                $salida['meses'] = $tmp3;          
            }          
        }        
        
        return $salida;


        /*
        $final = [];
        $salida = ['Hrs. Cons.',0,0,0,0,0,0,0,0,0,0,0,0];

        for ($i=0; $i <count($datos) ; $i++) { 
            $salida[$datos[$i]->mes] = (int) $datos[$i]->totalhorasrealizadas;            
        }
        $final['horas'] = $salida;        
       
        return $final;*/
    }
    
    public function numeroHorasConsumidasClientesBolsaMes($anio)
    {
        $datos = $this->ModelInicio->obtenerNumeroHorasConsumidasClientesBolsaMes($anio);

        $salida = [];
        $tmp1 = [];        
        $tmp3 = [];

        if (isset($datos) && count($datos)>0) {
            
            for ($i=0; $i < count($datos) ; $i++) { 
                $tmp1[] = $datos[$i]->tiemposrealizados;                
                $tmp3[] = $anio.'-'.$datos[$i]->mes.'-01';       
                $salida['Hrs. Cons.'] =  $tmp1;                
                $salida['meses'] = $tmp3;          
            }          
        }        
        
        return $salida;


        /*
        $final = [];
        $salida = ["Hrs. Cons.",0,0,0,0,0,0,0,0,0,0,0,0];

        for ($i=0; $i <count($datos) ; $i++) { 
            $salida[$datos[$i]->mes] = (int) $datos[$i]->tiemposrealizados;            
        }
        $final['horas'] = $salida;        
        return $final;
        */
    }

    public function construirTablaHorasClientes($anio)
    {                    
        $html = '
        <div id="destinohorasclientesdashboardsajax"></div>                        
        <script  type="module">        
        import arrancar from "'.RUTA_URL.'/public/js/tablaClass/tablaClass.js" 
        arrancar("tablaClientesDashboard","Inicio/crearTablaClientesDashboard", "destinohorasclientesdashboardsajax", "cliente", "ASC", 0, "buscador","Inicio/totalRegistrosClientesDashboard", [10],"min-w-full leading-normal","paginador",[""],"","","'.$anio.'");
        </script>
        ';
        return $html;
    }
    
    public function crearTablaClientesDashboard()
    {       
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $buscar = $_POST['busqueda'];
            $filas = $_POST['filas'];
            $pagina = $_POST['pagina'];
            $orden = $_POST['orden'];
            $tipoOrden = $_POST['tipoOrden'];                
        }
        
        $cond = '';
        $anio = date('Y');
        if ($_POST['anio'] != ''){
            $anio = $_POST['anio'];
        }
        $cond .= 'YEAR(tim.creacion)='.$anio;   
       
        //$filaspagina = $filas * $pagina;
        
        $having = '';    
        if ($buscar != "") {            
            $datos = (array) json_decode($buscar);            
            $cond .= $this->construirCondicionesBuscarClientes($datos);              
                                   
            if (isset($datos['horas']) && $datos['horas'] != '') {
                $having = "HAVING CONCAT(MINUTE(SEC_TO_TIME(SUM(tim.tiempototal)*60)),' h ',ROUND((SUM(tim.tiempototal) - (MINUTE(SEC_TO_TIME(SUM(tim.tiempototal)*60))))*60),' min ') LIKE '%".$datos['horas']."%' ";
            }                   
        }

        //$clientes = $this->ModelInicio->obtenerHorasClientesPorAnio($filas,$orden,$filaspagina,$tipoOrden,$cond,$having);
        $clientes = $this->ModelInicio->obtenerHorasClientesPorAnio($filas,$orden,$pagina,$tipoOrden,$cond,$having);

        print(json_encode($clientes));  
    }

    public function construirCondicionesBuscarClientes($datos)
    {   
        $tamanio = count($datos);

        /*echo"<br>tamanio<br>";
        print_r($tamanio);*/

        $cond = '';
        if ($tamanio > 0) {                                
            $cont = 0;
            $cond = " AND  (";
            foreach ($datos as $key => $value) {

                $cont++;  
                
                /*echo"<br>cont<br>";
                print_r($cont);*/

                if ($key != 'horas') {
                    if ($cont < ($tamanio) && $cont != 3) {     
                        $y =  " LIKE " . "'%$value%'" . " AND ";                    
                    } else {                    
                        $y =  " LIKE " . "'%$value%'" . ") ";
                    }                
                    if ($key == 'cliente') {
                        $cond .= "cli.nombre" . $y;
                    }               
                    if ($key == 'mes') {
                        $cond .= "MONTH(tim.creacion)" . $y;
                    }
                    if ($key == 'año') {
                        $cond .= "YEAR(tim.creacion)" . $y;
                    }               
                }else{
                    if ($value != '') {
                        $cond .=  " 1)";
                    }else{
                        $cond .=  " ) ";
                    }
                    
                }
            }                                        
        }
        /*echo"<br>cond<br>";
        print_r($cond);*/
        return $cond;
    }

    public function totalRegistrosClientesDashboard()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $buscar = $_POST['busqueda'];            
        }       

        $cond = '';
        $anio = date('Y');
        if ($_POST['anio'] != ''){
            $anio = $_POST['anio'];
        }
        $cond .= 'YEAR(tim.creacion)='.$anio;   

        $having = '';    
        if ($buscar != "") {            
            $datos = (array) json_decode($buscar);            
            $cond .= $this->construirCondicionesBuscarClientes($datos);              
            
                    
            if (isset($datos['horas']) && $datos['horas'] != '') {
                $having = "HAVING CONCAT(MINUTE(SEC_TO_TIME(SUM(tim.tiempototal)*60)),' h ',ROUND((SUM(tim.tiempototal) - (MINUTE(SEC_TO_TIME(SUM(tim.tiempototal)*60))))*60),' min ') LIKE '%".$datos['horas']."%' ";
            }                   
        }
        $contador = $this->ModelInicio->totalRegistrosClientesHorasDashboard($cond,$having);

        if (isset($contador) && count($contador)>0) {
            $contador = count($contador);
        }
        $cont = $contador;   
        print_r($cont);
    }  

    public function construirDatosTablasApartadoClientes()
    {
        $anio = date('Y');
        if (isset($_POST['anio']) && $_POST['anio'] >0) {
            $anio = $_POST['anio'];
        }
       
        $tablaHoras = $this->construirDatosTablaHorasAmbasModalidadesContrato($anio);        

        $retorno = [         
            'horas' => $tablaHoras            
        ];

        print json_encode($retorno);     
    }

    public function construirDatosTablaHorasAmbasModalidadesContrato($anio)
    {   
        $datos = $this->ModelInicio->obtenerHorasRealizadasTodosLosClientes($anio);      

        
        $html = '';
        if (isset($datos) && count($datos)>0) {        
            foreach ($datos as $key) {    
                
                    
                setlocale(LC_TIME, 'es_ES');
                $numero = $key->mes + 1;
                $fecha = DateTime::createFromFormat('!m', $numero);
                $mes = strftime("%b", $fecha->getTimestamp());
                
                $html .= "
                    <tr class='text-sm text-gray-700'>
                        <td class='px-2 py-1'>".$mes."</td>
                        <td class='px-2 py-1 xs:text-right lg:text-center'>".$key->horasbolsa."</td>
                        <td class='px-2 py-1 xs:text-right lg:text-center'>".$key->horasfijo."</td>                    
                    <tr> 
                    ";                  
            }
        }
        return $html;
        

        /*
        $html = '';

        $salida = [[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0],[0,0]];

        for ($i=0; $i <count($datos) ; $i++) { 
            $salida[$datos[$i]->mes]= [(int) $datos[$i]->horasbolsa,(int) $datos[$i]->horasfijo];
        }
        
        foreach ($salida as $key => $value) {                    
            setlocale(LC_TIME, 'es_ES');
            $numero = $key + 1;
            $fecha = DateTime::createFromFormat('!m', $numero);
            $mes = strftime("%b", $fecha->getTimestamp());

            $html .= "
            <tr class='text-sm text-gray-700'>
                <td class='px-2 py-1'>".$mes."</td>
                <td class='px-2 py-1 text-center'>".$value[0]."</td>
                <td class='px-2 py-1 text-center'>".$value[1]."</td>                            
            <tr> 
        ";  
        }       
        return $html;
        */
        
    }
    
    public function horasSegunModalidadadContratada()
    {
        $fechaIni = '';
        $fechaFin = '';

        if (isset($_POST['fechaIni']) && isset($_POST['fechaFin'])) {
            $fechaIni = $_POST['fechaIni'];
            $fechaFin = $_POST['fechaFin'];
        }else{
            $fechaInicio = new DateTime();
            $fechaInicio->modify('first day of this month');
            $fechaIni = $fechaInicio->format('Y-m-d');
    
            $fechaFinal = new DateTime();
            $fechaFinal->modify('last day of this month');
            $fechaFin = $fechaFinal->format('Y-m-d');
        }

        $arrayDatos = $this->ModelInicio->horasSegunModalidadadContratadaPorEstadoRangoFecha($fechaIni,$fechaFin);

        $salida = [];
        if (isset($arrayDatos) && count($arrayDatos)>0) {
            foreach ($arrayDatos as $row) {              
                $salida[$row->tipo] = $row->horasconsumidas;
            }
        }
        print json_encode($salida);
    }

    public function datosGraficosApartadoTecnicos()
    {
        $anio = date('Y');
        if (isset($_POST['anio']) && $_POST['anio'] >0) {
            $anio = $_POST['anio'];
        }
        $numeroIncidenciasTecnicos = $this->numeroDeIncidenciasPorTecnico($anio);
        $numeroHorasTecnicos = $this->numeroDeHorasPorTecnico($anio);
        $costeHorasTecnicos = $this->costeHorasPorTecnico($anio);

        $final = [$numeroIncidenciasTecnicos,$numeroHorasTecnicos,$costeHorasTecnicos];
        print json_encode($final);
    }
       

    public function numeroDeIncidenciasPorTecnico($anio) 
    {
        $tecnicos = $this->ModelInicio->obtenerListadoTecnicosActivos();                       
       
        $salida = [];
        
        foreach ($tecnicos as $key) {
            
            $datos = $this->ModelInicio->incidenciasPorTecnicoAnio($anio, $key->id);
            
            $tmp = [];
            $tmp2 = [];

            $nombre = '';
            if (isset($key->nombre) && $key->nombre !='') {
                $nombre = strtoupper(substr($key->nombre,0,1)) ;
            }
            $tecnico = $nombre.'. '.$key->apellidos;

            
            if (isset($datos) && count($datos)>0) {
        
                
                foreach ($datos as $dat) {
                    $tmp[] = $dat->cont1; 
                    $tmp2[] = $anio.'-'.$dat->mes.'-01';                      
                }

            }
            $salida[$tecnico] =  $tmp;
           

        }
        $salida['meses'] = $tmp2;     
        
         
        return $salida;
    }

    
    public function numeroDeHorasPorTecnico($anio)
    {
        $tecnicos = $this->ModelInicio->obtenerListadoTecnicosActivos();
        
        $salida = [];
        
        foreach ($tecnicos as $key) {
            
            $datos = $this->ModelInicio->horasAcumuladasPorTecnicoMesAnio($anio, $key->id);
            
            $tmp = [];
            $tmp2 = [];

            $nombre = '';
            if (isset($key->nombre) && $key->nombre !='') {
                $nombre = strtoupper(substr($key->nombre,0,1)) ;
            }
            $tecnico = $nombre.'. '.$key->apellidos;

            
            if (isset($datos) && count($datos)>0) {
        
                foreach ($datos as $dat) {
                    $tmp[] = $dat->sumatiempos; 
                    $tmp2[] = $anio.'-'.$dat->mes.'-01';                      
                }
            }
            $salida[$tecnico] =  $tmp;
        }
        $salida['meses'] = $tmp2;        
        
        return $salida;
    }

    public function costeHorasPorTecnico($anio)
    {
        $tecnicos = $this->ModelInicio->obtenerListadoTecnicosActivos();
        
        $salida = [];
        
        foreach ($tecnicos as $key) {
            
            $datos = $this->ModelInicio->costeAcumuladoPorTecnicoMesAnio($anio, $key->id);
            
            $tmp = [];
            $tmp2 = [];

            $nombre = '';
            if (isset($key->nombre) && $key->nombre !='') {
                $nombre = strtoupper(substr($key->nombre,0,1)) ;
            }
            $tecnico = $nombre.'. '.$key->apellidos;

            
            if (isset($datos) && count($datos)>0) {
        
                foreach ($datos as $dat) {
                    $tmp[] = $dat->costetiempos; 
                    $tmp2[] = $anio.'-'.$dat->mes.'-01';                      
                }
            }
            $salida[$tecnico] =  $tmp;
        }
        $salida['meses'] = $tmp2;        
        
        return $salida;                
    }

    public function datosGraficosApartadoEquipos()
    {
        $anio = date('Y');
        if (isset($_POST['anio']) && $_POST['anio'] >0) {
            $anio = $_POST['anio'];
        }
        $numeroEquiposAtendidos = $this->numeroEquiposAtendidos($anio);
        $numeroEquiposModoContrato = $this->numeroEquiposModoContrato($anio);
        $tablaEquiposMenosRentables = $this->construirTablaClaseEquipos($anio);

        $final = [$numeroEquiposAtendidos,$numeroEquiposModoContrato,$tablaEquiposMenosRentables];
        print json_encode($final);
    }

    public function numeroEquiposAtendidos($anio)
    {
        $equipos = $this->ModelInicio->obtenerNumeroEquiposAtendidosMes($anio);
        
        $salida = [];
        $tmp1 = [];
        $tmp2 = [];

        
        if (isset($equipos) && count($equipos)>0) {
            
            foreach ($equipos as $key) {
                                    
                    $tmp1[] = $key->contador; 
                    $tmp2[] = $anio.'-'.$key->mes.'-01';       
                    $salida['equipos'] =  $tmp1;               
                    $salida['meses'] = $tmp2;               
            }
        }
        return $salida;
    }

    public function numeroEquiposModoContrato($anio)
    {
        $equipos = $this->ModelInicio->obtenerNumeroEquiposModoContrato($anio);
        
        $salida = [];
        $tmp1 = [];
        $tmp2 = [];
        $tmp3 = [];

        if (isset($equipos) && count($equipos)>0) {
                        
            foreach ($equipos as $key) {
                                    
                    $tmp1[] = $key->contadorequiposhoras;
                    $tmp2[] = $key->contadorpreciofijo;
                    $tmp3[] = $anio.'-'.$key->mes.'-01';       
                    $salida['Bolsa Hrs.'] =  $tmp1;
                    $salida['Mantenim.'] =  $tmp2;
                    $salida['meses'] = $tmp3;           
            }
        }        
        
        return $salida;
    }

    public function tablaEquiposMenosRentables($anio)
    {
        $datos = $this->ModelInicio->rentabilidadDeTodosLosEquipos($anio);        

        $html = '';
        if (isset($datos) && count($datos)>0) {        
            foreach ($datos as $key) {                 
                    $html .= "
                        <tr class='text-sm text-gray-700'>
                            <td class='px-2 py-1'>".$key->nombreequipo."</td>    
                            <td class='px-2 py-1 xs:text-right lg:text-center'>".$key->nombrecliente."</td>
                            <td class='px-2 py-1 xs:text-right lg:text-center'>".number_format($key->rescoste,2,",",".")."</td>
                            <td class='px-2 py-1 xs:text-right lg:text-center'>".number_format($key->resprecio,2,",",".")."</td>
                            <td class='px-2 py-1 xs:text-right lg:text-center'>".number_format($key->resutilidad,2,",",".")."</td>                            
                        <tr> 
                        ";                  
            }
        }
        return $html;
    }

    public function crearTablaEquiposDashboard()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $buscar = $_POST['busqueda'];
            $filas = $_POST['filas'];
            $pagina = $_POST['pagina'];
            $orden = $_POST['orden'];
            $tipoOrden = $_POST['tipoOrden'];                
        }
     
        $cond = '';
        $anio = date('Y');
        if ($_POST['anio'] != ''){
            $anio = $_POST['anio'];
        }
        $cond .= 'resumen.anio='.$anio;   
       
        $filaspagina = $filas * $pagina;
    
        if ($buscar != "") {            
            $datos = json_decode($buscar);            
            $cond .= $this->construirCondicionesBuscar($datos);   
        }
            
        $equipos = $this->ModelInicio->rentabilidadDeTodosLosEquipos2($filas,$orden,$filaspagina,$tipoOrden,$cond);      
        print(json_encode($equipos));  
    }

    public function totalRegistrosEquiposDashboard()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $buscar = $_POST['busqueda'];            
        }       

        $cond = '';
        $anio = date('Y');
        if ($_POST['anio'] != ''){
            $anio = $_POST['anio'];
        }
        $cond .= 'resumen.anio='.$anio;  

        if ($buscar != "") {            
            $datos = json_decode($buscar);
            $cond .= $this->construirCondicionesBuscar($datos);    
        }
        $contador = $this->ModelInicio->totalRegistrosEquiposDashboard($cond);

        if (isset($contador) && count($contador)>0) {
            $contador = count($contador);
        }
        $cont = $contador;   
        print_r($cont);
    }  

    public function construirTablaClaseEquipos($anio)
    {
        
        $html = '
        <div id="destinoequipodashboardsajax"></div>                        
        <script  type="module">        
        import arrancar from "'.RUTA_URL.'/public/js/tablaClass/tablaClass.js" 
        arrancar("tablaequiposdashboard","Inicio/crearTablaEquiposDashboard", "destinoequipodashboardsajax", "rentabilidad", "ASC", 0, "buscador","Inicio/totalRegistrosEquiposDashboard", [10, 20, 30],"min-w-full leading-normal","paginador",[""],"","","'.$anio.'");
        </script>
        ';
        return $html;
    }

    public function construirCondicionesBuscar($datos)
    {
        $tamanio = count((array) $datos);
        $cond = '';
        if ($tamanio > 0) {                                
            $cont = 0;
            $cond = " AND  (";
            foreach ($datos as $key => $value) {

                $cont++;                   
                
                if ($cont < ($tamanio) ) {                    
                    $y =  " LIKE " . "'%$value%'" . " AND ";
                } else {                    
                    $y =  " LIKE " . "'%$value%'" . ") ";
                }                
                if ($key == 'cliente') {
                    $cond .= "cli.nombre" . $y;
                }               
                if ($key == 'equipo') {
                    $cond .= "eq.nombre" . $y;
                }               
            }                                        
        }

        return $cond;
    }

    public function refrescarSesion()
    {  
            $idUsuario = $_SESSION['idusuario'];

            
            $datosUser = $this->ModeloLogin->verificarSiExigeCambioPassword($idUsuario); 

            $validacion = $datosUser->nombre . " " . $datosUser->apellidos;

            $rolUsuario = $datosUser->rol;

            $permisos = $this->ModeloLogin->consultaPermisos($rolUsuario);
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

            //session_start();
            $_SESSION['usuario'] = $validacion;
            $_SESSION['nombrerol'] = $datosUser->nombrerol;
            $_SESSION['idusuario'] = $idUsuario;
            $_SESSION['token_control'] = 1;
            $_SESSION['permisos'] = $permisosUsuario;
            $_SESSION['controlLinksUsuario'] = $linksUsuario;
            $_SESSION['inicio'] = date("Y-n-j H:i:s");
            

            //echo json_encode($_SESSION); 
    }
}


