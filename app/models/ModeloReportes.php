<?php


class ModeloReportes{

    private $db;


    public function __construct(){
        $this->db = new Base;
    } 

    public function obtenerClientesBolsaHoras($filas,$orden,$filaspagina,$tipoOrden,$cond)
    {
        $this->db->query("SELECT /*tie.idcliente,*/ cli.nombre AS 'Cliente', /*tie.creacion,*/ MONTH(tie.creacion) AS 'Mes',
                        YEAR(tie.creacion) AS 'Año',
                        moda.valor AS 'Horas contratadas', 
                        SUM(tie.tiempototal) AS 'Horas consumidas', /*tie.idtecnico, usu.nombre,*/
                        moda.preciototal AS 'Euros contratados',
                        SUM(tie.tiempototal * usu.rol) AS 'Coste total'
                        FROM incidenciastiempos tie 
                        LEFT JOIN usuarios usu ON tie.idtecnico=usu.id
                        LEFT JOIN modalidadhoras moda ON tie.idcliente=moda.idcliente AND MONTH(tie.creacion)=moda.mes AND YEAR(tie.creacion)=moda.anio
                        LEFT JOIN clientes cli ON tie.idcliente=cli.id
                        AND MONTH(tie.creacion)=moda.mes 
                        AND YEAR(tie.creacion)=moda.anio
                        WHERE tie.finalizacion >0 AND moda.id IS NOT null
                        GROUP BY YEAR(tie.creacion), MONTH(tie.creacion), tie.idcliente $cond
                        order by " . $orden . " " . $tipoOrden . " limit $filaspagina,$filas ");
                                
        $resultado = $this->db->registros();

        return $resultado;
    }

    public function totalRegistrosClientesBolsaHoras($cond)
    {
        $this->db->query("SELECT count(*) as contador
                        FROM incidenciastiempos tie 
                        LEFT JOIN usuarios usu ON tie.idtecnico=usu.id
                        LEFT JOIN modalidadhoras moda ON tie.idcliente=moda.idcliente AND MONTH(tie.creacion)=moda.mes AND YEAR(tie.creacion)=moda.anio
                        LEFT JOIN clientes cli ON tie.idcliente=cli.id
                        AND MONTH(tie.creacion)=moda.mes 
                        AND YEAR(tie.creacion)=moda.anio
                        WHERE tie.finalizacion >0 AND moda.id IS NOT null
                        GROUP BY YEAR(tie.creacion), MONTH(tie.creacion), tie.idcliente $cond ");
                
        $resultado = $this->db->registros();
        $contador = count($resultado);
        return $contador;
    }
}