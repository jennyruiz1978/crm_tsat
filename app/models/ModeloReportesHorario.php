<?php

class ModeloReportesHorario
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerResumenMensualEmpleado($idEmpleado, $mes, $anio)
    {
        $this->db->query("SELECT j.fecha, j.estadojornada, j.horastotales, j.completada,
            (SELECT COUNT(*) FROM fichajes f WHERE f.idjornada = j.id AND f.eliminado = 0) AS numerofichajes
            FROM jornadas j
            WHERE j.idempleado = :idempleado AND MONTH(j.fecha) = :mes AND YEAR(j.fecha) = :anio AND j.eliminado = 0
            ORDER BY j.fecha ASC");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':mes', $mes);
        $this->db->bind(':anio', $anio);
        return $this->db->registros();
    }

    public function obtenerResumenMensualGlobal($mes, $anio)
    {
        $this->db->query("SELECT j.idempleado, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado,
            COUNT(j.id) AS diasstrabajados,
            SUM(j.horastotales) AS totalhoras,
            SUM(CASE WHEN j.completada = 1 THEN 1 ELSE 0 END) AS jornadascompletas,
            SUM(CASE WHEN j.completada = 0 THEN 1 ELSE 0 END) AS jornadasincompletas
            FROM jornadas j
            LEFT JOIN usuarios u ON j.idempleado = u.id
            WHERE MONTH(j.fecha) = :mes AND YEAR(j.fecha) = :anio AND j.eliminado = 0
            GROUP BY j.idempleado, u.nombre, u.apellidos
            ORDER BY u.nombre ASC");
        $this->db->bind(':mes', $mes);
        $this->db->bind(':anio', $anio);
        return $this->db->registros();
    }

    public function obtenerDetalleFichajesMensual($idEmpleado, $mes, $anio)
    {
        $this->db->query("SELECT f.idjornada, j.fecha AS fechajornada, f.tipofichaje, f.fechahora,
            f.observaciones, f.corregido, f.latitud, f.longitud, f.ipregistro
            FROM fichajes f
            LEFT JOIN jornadas j ON f.idjornada = j.id
            WHERE f.idempleado = :idempleado AND MONTH(f.fechahora) = :mes AND YEAR(f.fechahora) = :anio AND f.eliminado = 0
            ORDER BY f.fechahora ASC");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':mes', $mes);
        $this->db->bind(':anio', $anio);
        return $this->db->registros();
    }

    public function obtenerFichajesParaCSV($fechaInicio, $fechaFin, $idEmpleado = null)
    {
        if ($idEmpleado !== null) {
            $this->db->query("SELECT f.fechahora, f.tipofichaje, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado,
                f.observaciones
                FROM fichajes f
                LEFT JOIN usuarios u ON f.idempleado = u.id
                WHERE f.fechahora BETWEEN :fechainicio AND :fechafin
                AND f.idempleado = :idempleado AND f.eliminado = 0 AND f.corregido = 0
                ORDER BY f.fechahora ASC");
            $this->db->bind(':idempleado', $idEmpleado);
        } else {
            $this->db->query("SELECT f.fechahora, f.tipofichaje, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado,
                f.observaciones
                FROM fichajes f
                LEFT JOIN usuarios u ON f.idempleado = u.id
                WHERE f.fechahora BETWEEN :fechainicio AND :fechafin AND f.eliminado = 0 AND f.corregido = 0
                ORDER BY f.fechahora ASC");
        }
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        return $this->db->registros();
    }

    public function obtenerResumenAnualEmpleado($idEmpleado, $anio)
    {
        $this->db->query("SELECT MONTH(j.fecha) AS mes, COUNT(j.id) AS diasstrabajados,
            SUM(j.horastotales) AS totalhoras,
            SUM(CASE WHEN j.completada = 1 THEN 1 ELSE 0 END) AS completas,
            SUM(CASE WHEN j.completada = 0 THEN 1 ELSE 0 END) AS incompletas
            FROM jornadas j
            WHERE j.idempleado = :idempleado AND YEAR(j.fecha) = :anio AND j.eliminado = 0
            GROUP BY MONTH(j.fecha)
            ORDER BY mes ASC");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':anio', $anio);
        return $this->db->registros();
    }

    public function obtenerTodosEmpleadosNoCliente()
    {
        $this->db->query("SELECT u.id, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado, u.rol, u.activo
            FROM usuarios u
            WHERE u.rol IN (0, 2, 3)
            ORDER BY u.activo DESC, u.nombre ASC");
        return $this->db->registros();
    }

    public function obtenerListadoEmpleadosConFichaje()
    {
        $this->db->query("SELECT u.id, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado, u.rol
            FROM usuarios u
            INNER JOIN configuracionhorario ch ON u.id = ch.idempleado AND ch.debeFichar = 1 AND ch.eliminado = 0
            WHERE u.activo = 1
            ORDER BY u.nombre ASC");
        return $this->db->registros();
    }
}