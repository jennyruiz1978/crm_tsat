<?php


class ModeloInicio{

    private $db;


    public function __construct(){
        $this->db = new Base;
    }


    public function contarTotalIncidenciasRangoFechas($fechaIni,$fechaFin){
        $this->db->query("SELECT COUNT(*) AS contador FROM incidencias inc WHERE inc.creacion BETWEEN '$fechaIni' AND '$fechaFin'");
        $resultado = $this->db->registro();
        return $resultado;
    } 

    public function contarTotalIncidenciasAnio($anio)
    {
        $this->db->query("SELECT COUNT(*) AS contador FROM incidencias inc WHERE YEAR(inc.creacion) = '$anio' ");
        $resultado = $this->db->registro();
        return $resultado;
    }
    
    public function contarTotalIncidenciasPendientesRangoFechas($fechaIni,$fechaFin){
        $this->db->query("SELECT COUNT(*) AS contador FROM incidencias inc WHERE inc.creacion BETWEEN '$fechaIni' AND '$fechaFin' AND inc.estado=1");
        $resultado = $this->db->registro();
        return $resultado;
    } 

    public function contarTotalIncidenciasPendientesAnio($anio)
    {
        $this->db->query("SELECT COUNT(*) AS contador FROM incidencias inc WHERE YEAR(inc.creacion) = '$anio' AND inc.estado=1");
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function contarTotalClientesAtendidosRangoFechas($fechaIni,$fechaFin)
    {
        $this->db->query("SELECT COUNT(DISTINCT(inc.idcliente)) AS contador 
                        FROM incidencias inc 
                        WHERE inc.creacion BETWEEN '$fechaIni' AND '$fechaFin' ");
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function contarTotalClientesAtendidosAnio($anio)
    {
        $this->db->query("SELECT COUNT(DISTINCT(inc.idcliente)) AS contador FROM incidencias inc 
                        WHERE YEAR(inc.creacion) = '$anio' ");
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function tiempoTotalIncidenciasRangoFechas($fechaIni,$fechaFin)
    {
        $this->db->query("SELECT SUM(tim.tiempototal) AS tiempototal FROM incidenciastiempos tim WHERE tim.creacion BETWEEN '$fechaIni' AND '$fechaFin'");
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function tiempoTotalIncidenciasAnio($anio)
    {
        $this->db->query("SELECT SUM(tim.tiempototal) AS tiempototal FROM incidenciastiempos tim WHERE YEAR(tim.creacion) = '$anio'");
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function contarTotalEquiposAtendidosRangoFechas($fechaIni,$fechaFin)
    {
        $this->db->query("SELECT COUNT(DISTINCT(inc.idequipo)) AS contador 
                        FROM incidencias inc 
                        WHERE inc.creacion BETWEEN '$fechaIni' AND '$fechaFin' ");
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function contarTotalEquiposAtendidosAnio($anio)
    {
        $this->db->query("SELECT COUNT(DISTINCT(inc.idequipo)) AS contador FROM incidencias inc 
                        WHERE YEAR(inc.creacion) = '$anio' ");
        $resultado = $this->db->registro();
        return $resultado;
    }

    public function consolidarAtencionesPorModalidadRangoFecha($fechaIni,$fechaFin)
    {
        $this->db->query("SELECT tim.modalidadtecnico, moda.modalidad ,COUNT(tim.modalidadtecnico) AS cont, SUM(tim.tiempototal) AS horas
                        FROM incidenciastiempos tim 
                        LEFT JOIN modalidadtecnico moda ON tim.modalidadtecnico=moda.id
                        WHERE tim.creacion BETWEEN '$fechaIni' AND '$fechaFin'
                        GROUP BY tim.modalidadtecnico ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function consolidarIncidenciasPorEstadoRangoFecha($fechaIni,$fechaFin)
    {
        $this->db->query("SELECT inc.estado, est.estado, COUNT(inc.estado) AS cont 
                        FROM incidencias inc
                        LEFT JOIN estadoincidencias est ON inc.estado=est.id
                        WHERE inc.creacion BETWEEN '$fechaIni' AND '$fechaFin'
                        GROUP BY inc.estado ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerCosteHorasRealizadasClientesConBolsa($anio)
    {
        $this->db->query("SELECT cost.*, tot.horascontratadas, tot.totalEuros,
                        ROUND(((tot.horascontratadas - cost.tiemposrealizados)/tot.horascontratadas * 100),2) AS rendimiento,
                        (tot.totalEuros - cost.costeatencion) AS ingresado
                        FROM totalhorascontratadas tot
                        LEFT JOIN costehorasclibolsa cost ON tot.anio=cost.anio AND tot.mes=cost.mes
                        WHERE cost.anio = '$anio' ");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerCosteHorasRealizadasClientesPrecioFijo($anio)
    {
        $this->db->query("SELECT *, ROUND(( (tot.totalcontratado - coste.costeatenciones) / tot.totalcontratado )*100,2) AS rendimiento,
                        ROUND((tot.totalcontratado - coste.costeatenciones ),2) AS beneficio
                        FROM costehorasclifijo coste 
                        LEFT JOIN totalhorascontratfijo tot ON tot.anio=coste.anio AND tot.mes=coste.mes
                        WHERE coste.anio = '$anio'
                        ORDER BY coste.mesreferencia ASC ");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerIngresosNetosMensuales($anio)
    {
        $this->db->query("SELECT *, (contratado.totalingresos - costes.coste) AS ingresoneto
                        FROM todoloscostes costes 
                        LEFT JOIN todoslosingresos contratado ON contratado.aniof = costes.anio AND contratado.mesf=costes.mes
                        WHERE aniof='$anio'
                        ORDER BY mes ASC ");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtnenerAniosExistentes()
    {
        $this->db->query("SELECT DISTINCT(YEAR(creacion)) AS anio FROM incidencias ORDER BY anio ASC");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerNumeroIncidenciasMensuales($anio) //se puede mejorar incluyendo el cliente
    {
        $this->db->query("SELECT YEAR(inc.creacion) AS anio, MONTH(inc.creacion) AS mes, inc.creacion, COUNT(inc.id) numincidencias 
                        FROM incidencias inc 
                        WHERE /*inc.idcliente=2 AND*/ YEAR(inc.creacion ) = '$anio'
                        GROUP BY YEAR(inc.creacion), MONTH(inc.creacion) ");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerNumeroHorasConsumidasClientesBolsaMes($anio)
    {
        $this->db->query("SELECT * FROM costehorasclibolsa coste WHERE coste.anio='$anio' ORDER BY mes ASC ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerNumeroHorasConsumidasEquipoMantenimiento($anio)
    {
        $this->db->query("SELECT * FROM costehorasclifijo fijo WHERE fijo.anio='$anio' ORDER BY mes ASC ");
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerHorasRealizadasTodosLosClientes($anio)
    {
        $this->db->query("SELECT 
                        IF(bolsa.anio IS NULL,fijo.anio,bolsa.anio) AS anio,
                        IF(bolsa.mes IS NULL,fijo.mes,bolsa.mes) AS mes,
                        IF(bolsa.tiemposrealizados IS NULL,0,bolsa.tiemposrealizados) AS horasbolsa,
                        IF(fijo.totalhorasrealizadas IS NULL,0,fijo.totalhorasrealizadas) AS horasfijo
                        FROM costehorasclibolsa bolsa
                        LEFT JOIN costehorasclifijo fijo ON bolsa.anio=fijo.anio AND bolsa.mes=fijo.mes
                        WHERE bolsa.anio = '$anio' OR fijo.anio = '$anio' ");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function numeroClientesDiferentes($anio)
    {
        $this->db->query("SELECT MONTH(inc.creacion) AS mes, YEAR(inc.creacion) AS anio,
                        COUNT(DISTINCT(inc.idcliente)) AS numclientesdiferentes
                        FROM incidencias inc 
                        WHERE YEAR(inc.creacion) = '$anio'
                        GROUP BY YEAR(inc.creacion), MONTH(inc.creacion)
                        ORDER BY MONTH(inc.creacion) ASC ");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function horasSegunModalidadadContratadaPorEstadoRangoFecha($fechaIni,$fechaFin)
    {
        $this->db->query("SELECT 'Bolsa horas' AS tipo,
                        SUM(tie.tiempototal) AS horasconsumidas
                        FROM incidenciastiempos tie 
                        LEFT JOIN modalidadhoras moda ON tie.idcliente=moda.idcliente AND MONTH(tie.creacion)=moda.mes AND YEAR(tie.creacion)=moda.anio
                        WHERE tie.finalizacion >0  AND moda.modalidad = 'horas' AND tie.creacion BETWEEN '$fechaIni' AND '$fechaFin'
                        UNION ALL 
                        SELECT 'Mantenimiento' AS tipo, SUM(tie.tiempototal) AS horasconsumidas
                        FROM incidenciastiempos tie 
                        LEFT JOIN modalidadcostefijo fijo 
                        ON tie.idequipo=fijo.idequipo 
                        AND YEAR(tie.creacion)=fijo.anio 
                        AND MONTH(tie.creacion)=fijo.mes
                        WHERE tie.finalizacion >0 AND fijo.modalidad = 'fijo' AND tie.creacion BETWEEN '$fechaIni' AND '$fechaFin' ");
                      
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerListadoTecnicosActivos()
    {
        $this->db->query("SELECT id, nombre, apellidos  FROM usuarios WHERE rol=2 and activo=1 ");
      
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function incidenciasPorTecnicoAnio($anio, $idTecnico)
    {
        $this->db->query("SELECT 
                        DISTINCT YEAR(inci.creacion) AS anio, MONTH(inci.creacion) AS mes,
                        
                        /*traer estos datos*/
                        IF(resumen.contador IS NULL,0,resumen.contador) AS cont1
                        
                        
                        FROM usuarios usus 
                        LEFT JOIN incidencias inci ON JSON_SEARCH(inci.tecnicos, 'one', usus.id) IS NOT NULL 
                                        
                        /*otra consulta*/
                        LEFT JOIN 
                        (SELECT COUNT(*) AS contador,
                        YEAR(inc.creacion) AS anio, MONTH(inc.creacion) AS mes,
                        usu.id AS idtecnico, usu.nombre, usu.apellidos
                        FROM usuarios usu 
                        LEFT JOIN incidencias inc ON JSON_SEARCH(inc.tecnicos, 'one', usu.id) IS NOT NULL 
                        WHERE usu.rol= 2 AND usu.id='$idTecnico' AND YEAR(inc.creacion) ='$anio'
                        GROUP BY YEAR(inc.creacion), MONTH(inc.creacion), usu.id
                        ORDER BY inc.creacion ASC) AS resumen ON YEAR(inci.creacion)= resumen.anio AND MONTH(inci.creacion)=resumen.mes
                        
                        
                        WHERE usus.rol=2 AND YEAR(inci.creacion) ='$anio'
                        ORDER BY inci.creacion ASC ");
                    
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function horasAcumuladasPorTecnicoMesAnio($anio, $idTecnico)
    {
        $this->db->query("SELECT DISTINCT YEAR(tie.creacion) AS anio, MONTH(tie.creacion) AS mes,
                        IF(resumen.sumatiempos IS NULL, 0, resumen.sumatiempos) AS sumatiempos
                        FROM incidenciastiempos tie
                        /**/
                        LEFT JOIN (SELECT YEAR(tiempo.creacion) AS anio, MONTH(tiempo.creacion) AS mes, SUM(tiempo.tiempototal) AS sumatiempos                    
                        FROM incidenciastiempos tiempo
                        WHERE YEAR(tiempo.creacion) = '$anio' AND tiempo.idtecnico = '$idTecnico'
                        GROUP BY YEAR(tiempo.creacion), MONTH(tiempo.creacion), tiempo.idtecnico
                        ORDER BY tiempo.creacion ASC) AS resumen ON YEAR(tie.creacion) = resumen.anio AND MONTH(tie.creacion)=resumen.mes
                        /**/
                        WHERE YEAR(tie.creacion) = '$anio'
                        ORDER BY tie.creacion ASC ");
                    
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function costeAcumuladoPorTecnicoMesAnio($anio, $idTecnico)
    {
        $this->db->query("SELECT DISTINCT YEAR(tie.creacion) AS anio, MONTH(tie.creacion) AS mes,
                        IF(resumen.costetiempos IS NULL, 0, ROUND(resumen.costetiempos,2)) AS costetiempos
                        FROM incidenciastiempos tie    
                        LEFT JOIN (                
                                SELECT YEAR(tiempo.creacion) AS anio, MONTH(tiempo.creacion) AS mes, tiempo.tiempototal, 
                                SUM(tiempo.tiempototal * costehora) AS costetiempos                                            
                                FROM incidenciastiempos tiempo
                                LEFT JOIN costestecnicos coste ON YEAR(tiempo.creacion) = coste.anio 
                                AND MONTH(tiempo.creacion)=coste.mes AND tiempo.idtecnico=coste.idtecnico                        
                                WHERE YEAR(tiempo.creacion) = '$anio' AND tiempo.idtecnico = '$idTecnico'                        
                                GROUP BY YEAR(tiempo.creacion), MONTH(tiempo.creacion), tiempo.idtecnico                        
                                ORDER BY tiempo.creacion ASC) AS resumen 								
                                ON YEAR(tie.creacion) = resumen.anio AND MONTH(tie.creacion)=resumen.mes    
                        WHERE YEAR(tie.creacion) = '$anio'
                        ORDER BY tie.creacion ASC ");
                    
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerNumeroEquiposAtendidosMes($anio)
    {
        $this->db->query("SELECT YEAR(inc.creacion) AS anio, MONTH(inc.creacion) AS mes,
                        COUNT(DISTINCT inc.idequipo) AS contador                        
                        FROM incidencias inc                         
                        WHERE YEAR(inc.creacion)='$anio'                        
                        GROUP BY YEAR(inc.creacion), MONTH(inc.creacion)                        
                        ORDER BY YEAR(inc.creacion) ASC , MONTH(inc.creacion) ASC");
    
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerNumeroEquiposModoContrato($anio)
    {
        $this->db->query("SELECT incid.mes, incid.anio, 
                        IF(modohoras.contadorequiposhoras IS NULL,0,modohoras.contadorequiposhoras) AS contadorequiposhoras,  
                        IF(modofijo.contadorpreciofijo IS NULL,0,modofijo.contadorpreciofijo) AS contadorpreciofijo                        
                        FROM mesesaniosincidencias incid                        
                        LEFT JOIN equiposmodohoras modohoras ON incid.anio=modohoras.anio AND incid.mes=modohoras.mes                        
                        LEFT JOIN equiposmodofijo modofijo ON incid.anio=modofijo.anio AND incid.mes=modofijo.mes                                                
                        WHERE incid.anio ='$anio' 
                        ORDER BY incid.anio ASC , incid.mes ASC ");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function rentabilidadDeTodosLosEquipos($anio)
    {
        $this->db->query("SELECT 
                        resumen.idequipo, eq.nombre AS nombreequipo, resumen.idcliente, cli.nombre AS nombrecliente,
                        ROUND(SUM(resumen.sumacostemes),2) AS rescoste, 
                        ROUND(SUM(resumen.preciocontratado),2) AS resprecio, 
                        ROUND(SUM(resumen.preciocontratado - resumen.sumacostemes),2) AS resutilidad                        
                        FROM equiposcostes AS resumen                         
                        LEFT JOIN clientes cli ON resumen.idcliente = cli.id
                        LEFT JOIN equipos eq ON eq.id=resumen.idequipo                        
                        WHERE resumen.anio='$anio' /*AND  resumen.mes ='9'*/                                                
                        GROUP BY resumen.idequipo
                        ORDER BY resutilidad ASC 
                        LIMIT 10");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function rentabilidadDeTodosLosEquipos2($filas,$orden,$filaspagina,$tipoOrden,$cond)
    {
        
        $this->db->query("SELECT 
                        eq.nombre AS equipo, cli.nombre AS cliente,
                        ROUND(SUM(resumen.sumacostemes),2) AS coste, 
                        ROUND(SUM(resumen.preciocontratado),2) AS ingreso,                        
                        ROUND(SUM(resumen.preciocontratado - resumen.sumacostemes),2) AS rentabilidad 
                        FROM equiposcostes AS resumen                         
                        LEFT JOIN clientes cli ON resumen.idcliente = cli.id
                        LEFT JOIN equipos eq ON eq.id=resumen.idequipo
                        WHERE $cond
                        GROUP BY resumen.idequipo                        
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas");
       
        $resultado = $this->db->registros();
        return $resultado;
    }

    public function totalRegistrosEquiposDashboard($cond)
    {
        $this->db->query("SELECT    
                        eq.nombre AS equipo, cli.nombre AS cliente,                   
                        ROUND(SUM(resumen.sumacostemes),2) AS rescoste, 
                        ROUND(SUM(resumen.preciocontratado),2) AS resprecio, 
                        ROUND(SUM(resumen.preciocontratado - resumen.sumacostemes),2) AS resutilidad                        
                        FROM equiposcostes AS resumen                         
                        LEFT JOIN clientes cli ON resumen.idcliente = cli.id
                        LEFT JOIN equipos eq ON eq.id=resumen.idequipo                        
                        WHERE $cond                          
                        GROUP BY resumen.idequipo ");

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function obtenerHorasClientesPorAnio($filas,$orden,$pagina/*$filaspagina*/,$tipoOrden,$cond,$having)
    {
        
        /*
        echo"<br>filas<br>";
        print_r($filas);

        echo"<br>pagina<br>";
        print_r($pagina);*/

        /*
        if ($pagina == 0) {
            $page = ($filas * intval($pagina));
        }else{
            $page = ($filas * intval($pagina)) + 1;
        }*/
        $page = ($filas * intval($pagina));

        /*
        echo"<br>page<br>";
        print_r($page);*/

        
        $this->db->query("SELECT cli.nombre as cliente,
                        MONTH(tim.creacion) AS mes, YEAR(tim.creacion) AS 'año',
                        /*MINUTE(SEC_TO_TIME(SUM(tim.tiempototal)*60)) solohoras,
                        ROUND((SUM(tim.tiempototal) - (MINUTE(SEC_TO_TIME(SUM(tim.tiempototal)*60))))*60) AS solominutos,
                        */
                        CONCAT(MINUTE(SEC_TO_TIME(SUM(tim.tiempototal)*60)),' h ',ROUND((SUM(tim.tiempototal) - (MINUTE(SEC_TO_TIME(SUM(tim.tiempototal)*60))))*60),' min ') AS horas                        
                        FROM incidenciastiempos tim
                        LEFT JOIN clientes cli ON tim.idcliente = cli.id
                        WHERE $cond
                        GROUP BY tim.idcliente, MONTH(tim.creacion), YEAR(tim.creacion)             
                        $having                           
                        order by " . $orden . " " . $tipoOrden . " LIMIT $page , $filas ");
       
       /*order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas");*/

       /*
       echo"<br>query<br>";
       print_r($this);
       die;*/

        $resultado = $this->db->registros();
        return $resultado;
    }

    public function totalRegistrosClientesHorasDashboard($cond,$having)
    {
        $this->db->query("SELECT * FROM incidenciastiempos tim
                        LEFT JOIN clientes cli ON tim.idcliente = cli.id
                        WHERE $cond                 
                        GROUP BY tim.idcliente, MONTH(tim.creacion), YEAR(tim.creacion) 
                        $having ");

        $resultado = $this->db->registros();
        return $resultado;
    }

}