<?php


class ModeloPresupuestosFacturas{

    private $db;


    public function __construct(){
        $this->db = new Base;
    } 
    
    public function obtenerEstadoPresupuestoFacturacion($idIncidencia)
    {
        $this->db->query("SELECT inc.estadofactppto, inc.nomestadofactppto FROM incidencias inc WHERE inc.id='$idIncidencia' ");            
        $fila = $this->db->registro();
        return $fila;        
    }

    public function obtenerTodosLosEstadoPresupuestoFacturacion()
    {
        $this->db->query("SELECT * FROM estadosfactppto");            
        $filas = $this->db->registros();
        return $filas;
    }

    public function obtenerComentariosFacturarPresupuestar($idIncidencia)
    {
        $this->db->query("SELECT fp.*, CONCAT(usu.nombre,' ',usu.apellidos) AS remitente,
                        DATE_FORMAT(fp.fechacreacion,'%d/%m/%Y %H:%i:%s', 'es_ES') AS fecha, fac.estado   
                        FROM facturarpresupuestar fp 
                        LEFT JOIN usuarios usu ON fp.idusuario = usu.id
                        LEFT JOIN estadosfactppto fac ON fp.idestadofactppto=fac.id
                        WHERE fp.idincidencia= '$idIncidencia'
                        ORDER BY fp.fechacreacion DESC");            
        $filas = $this->db->registros();
        return $filas;
    }

    public function actualizarEstadoFacturaPresupuesto($idIncidencia, $idEstado, $nombreEstado)
    {
   
        $this->db->query("UPDATE incidencias 
                        SET estadofactppto = '$idEstado', nomestadofactppto = '$nombreEstado'
                        WHERE id = '$idIncidencia' ");
        
        if ($this->db->execute()) {
            return 1;
        }else{
            return 0;
        }
    }

    public function actualizarEstadoFacturaPresupuestoEnEdicion($idIncidencia, $idEstado, $nombreEstado)
    {
   
        $this->db->query("UPDATE incidencias 
                        SET estadofactppto = '$idEstado', nomestadofactppto = '$nombreEstado'
                        WHERE id = '$idIncidencia' ");
        
        $this->db->execute();
    }

    public function insertarDatosAHistorialEstadosFacturarPresupuestar($idIncidencia, $idEstado, $idusuario, $comentParaFacturador)
    {        
        $this->db->query("INSERT INTO facturarpresupuestar (idincidencia, idestadofactppto, idusuario, comentario) 
        VALUES (:idincidencia, :idestadofactppto, :idusuario, :comentario)");

        $this->db->bind(':idincidencia', $idIncidencia);                        
        $this->db->bind(':idestadofactppto', $idEstado);    
        $this->db->bind(':idusuario', $idusuario);    
        $this->db->bind(':comentario', $comentParaFacturador);        
                        

        if ($this->db->execute()) {
            return 1;
        }else{
            return 0;
        }
    }

    public function obtenerNombreEstadoPresupuestoFacturacionPorIdEstado($idEstado)
    {
        $this->db->query("SELECT * FROM estadosfactppto WHERE id = '$idEstado' ");
        $fila = $this->db->registro();
        return $fila->estado;
    }

    public function crearPresupuestoParaCliente($idIncidencia,$comentario,$idusuario)
    {
        $this->db->query("INSERT INTO incidenciaspresupuestos (idincidencia, comentario, idusuario) 
                        VALUES ('$idIncidencia', '$comentario', '$idusuario')");                        

        if ($this->db->execute()) {
            return 1;
        }else{
            return 0;
        }
    }  

    public function contarSolicitudesSegunEstadoFactPresupuestar($idusuario,$estado)
    {
        $this->db->query("SELECT COUNT(*) AS contador                        
                        FROM incidencias inc 
                        WHERE JSON_SEARCH(inc.tecnicos, 'one', '$idusuario') IS NOT NULL                         
                        AND inc.estadofactppto = '$estado' ");
                       
        $fila = $this->db->registro();
        return $fila->contador;
    }

    public function contarTodasSolicitudesSegunEstadoFactPresupuestar($estado)
    {
        $this->db->query("SELECT COUNT(*) AS contador
                        FROM incidencias inc 
                        WHERE inc.estadofactppto = '$estado' ");
        $fila = $this->db->registro();
        return $fila->contador;
    }

    public function incidenciasPorEstadoPorUsuario($idUsuario,$idEstado)
    {
        $this->db->query("SELECT inc.id AS idincidencia, cli.nombre AS nombrecliente 
                        FROM incidencias inc 
                        LEFT JOIN clientes cli ON inc.idcliente=cli.id 
                        WHERE JSON_SEARCH(inc.tecnicos, 'one', '$idUsuario') IS NOT NULL                         
                        AND inc.estadofactppto = '$idEstado' ");
       
        $filas = $this->db->registros();
        return $filas;
    }

    public function incidenciasTodasPorEstado($idEstado)
    {
        $this->db->query("SELECT inc.id AS idincidencia, cli.nombre AS nombrecliente 
                        FROM incidencias inc 
                        LEFT JOIN clientes cli ON inc.idcliente=cli.id 
                        WHERE inc.estadofactppto = '$idEstado' ");
       
        $filas = $this->db->registros();
        return $filas;
    }

}