<?php

class ModeloVacaciones
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function crearSolicitud($datos)
    {
        $this->db->query("INSERT INTO vacaciones
            (idempleado, tipovacacion, fechainicio, fechafin, dias, motivo, ficherojustificante)
            VALUES (:idempleado, :tipovacacion, :fechainicio, :fechafin, :dias, :motivo, :ficherojustificante)");
        $this->db->bind(':idempleado', $datos['idempleado']);
        $this->db->bind(':tipovacacion', $datos['tipovacacion']);
        $this->db->bind(':fechainicio', $datos['fechainicio']);
        $this->db->bind(':fechafin', $datos['fechafin']);
        $this->db->bind(':dias', $datos['dias']);
        $this->db->bind(':motivo', $datos['motivo']);
        $this->db->bind(':ficherojustificante', $datos['ficherojustificante']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function obtenerVacacionesPorEmpleado($idEmpleado)
    {
        $this->db->query("SELECT * FROM vacaciones WHERE idempleado = :idempleado AND eliminado = 0 ORDER BY fechainicio DESC");
        $this->db->bind(':idempleado', $idEmpleado);
        return $this->db->registros();
    }

    public function obtenerTodasVacaciones()
    {
        $this->db->query("SELECT v.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado,
            CONCAT(a.nombre, ' ', a.apellidos) AS nombreadmin
            FROM vacaciones v
            LEFT JOIN usuarios u ON v.idempleado = u.id
            LEFT JOIN usuarios a ON v.idadminresuelve = a.id
            WHERE v.eliminado = 0
            ORDER BY v.creadoen DESC");
        return $this->db->registros();
    }

    public function obtenerVacacionesPendientes()
    {
        $this->db->query("SELECT v.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado
            FROM vacaciones v
            LEFT JOIN usuarios u ON v.idempleado = u.id
            WHERE v.estado = 'pendiente' AND v.eliminado = 0
            ORDER BY v.creadoen ASC");
        return $this->db->registros();
    }

    public function obtenerVacacionPorId($id)
    {
        $this->db->query("SELECT v.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado
            FROM vacaciones v
            LEFT JOIN usuarios u ON v.idempleado = u.id
            WHERE v.id = :id AND v.eliminado = 0");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function aprobarVacacion($id, $idAdmin, $respuesta)
    {
        $this->db->query("UPDATE vacaciones
            SET estado = 'aprobada', idadminresuelve = :idadmin, fecharesolucion = NOW(), respuestaadmin = :respuesta
            WHERE id = :id");
        $this->db->bind(':idadmin', $idAdmin);
        $this->db->bind(':respuesta', $respuesta);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function rechazarVacacion($id, $idAdmin, $respuesta)
    {
        $this->db->query("UPDATE vacaciones
            SET estado = 'rechazada', idadminresuelve = :idadmin, fecharesolucion = NOW(), respuestaadmin = :respuesta
            WHERE id = :id");
        $this->db->bind(':idadmin', $idAdmin);
        $this->db->bind(':respuesta', $respuesta);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function actualizarJustificante($id, $fichero)
    {
        $this->db->query("UPDATE vacaciones SET ficherojustificante = :fichero WHERE id = :id");
        $this->db->bind(':fichero', $fichero);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function obtenerVacacionesAprobadasPorEmpleadoRango($idEmpleado, $fechaInicio, $fechaFin)
    {
        $this->db->query("SELECT * FROM vacaciones
            WHERE idempleado = :idempleado AND estado = 'aprobada' AND eliminado = 0
            AND ((fechainicio BETWEEN :fechainicio AND :fechafin)
            OR (fechafin BETWEEN :fechainicio AND :fechafin)
            OR (fechainicio <= :fechainicio AND fechafin >= :fechafin))
            ORDER BY fechainicio ASC");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        return $this->db->registros();
    }

    public function obtenerVacacionesSolapadas($idEmpleado, $fechaInicio, $fechaFin, $idExcluir = null)
    {
        $sql = "SELECT * FROM vacaciones
            WHERE idempleado = :idempleado AND estado IN ('aprobada', 'pendiente') AND eliminado = 0
            AND ((fechainicio BETWEEN :fechainicio AND :fechafin)
            OR (fechafin BETWEEN :fechainicio AND :fechafin)
            OR (fechainicio <= :fechainicio AND fechafin >= :fechafin))";
        if ($idExcluir) {
            $sql .= " AND id != :idexcluir";
        }
        $sql .= " ORDER BY fechainicio ASC";
        $this->db->query($sql);
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        if ($idExcluir) {
            $this->db->bind(':idexcluir', $idExcluir);
        }
        return $this->db->registros();
    }

    public function contarVacacionesPendientes()
    {
        $this->db->query("SELECT COUNT(*) AS total FROM vacaciones WHERE estado = 'pendiente' AND eliminado = 0");
        $resultado = $this->db->registro();
        return $resultado->total ?? 0;
    }

    public function obtenerVacacionAprobadaPorFecha($idEmpleado, $fecha)
    {
        $this->db->query("SELECT * FROM vacaciones
            WHERE idempleado = :idempleado AND estado = 'aprobada' AND eliminado = 0
            AND :fecha BETWEEN fechainicio AND fechafin
            ORDER BY fechainicio ASC LIMIT 1");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fecha', $fecha);
        return $this->db->registro();
    }

    public function obtenerVacacionActivaPorFecha($idEmpleado, $fecha)
    {
        $this->db->query("SELECT * FROM vacaciones
            WHERE idempleado = :idempleado AND estado IN ('pendiente', 'aprobada') AND eliminado = 0
            AND :fecha BETWEEN fechainicio AND fechafin
            ORDER BY fechainicio ASC LIMIT 1");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fecha', $fecha);
        return $this->db->registro();
    }

    public function cancelarVacacion($id, $idAdmin, $respuesta)
    {
        $this->db->query("UPDATE vacaciones
            SET estado = 'cancelada', idadminresuelve = :idadmin, fecharesolucion = NOW(), respuestaadmin = :respuesta
            WHERE id = :id AND estado = 'aprobada'");
        $this->db->bind(':idadmin', $idAdmin);
        $this->db->bind(':respuesta', $respuesta);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function calcularDiasVacacionesUsados($idEmpleado, $anio)
    {
        $this->db->query("SELECT COALESCE(SUM(dias), 0) AS total
            FROM vacaciones
            WHERE idempleado = :idempleado AND estado = 'aprobada' AND eliminado = 0
            AND tipovacacion = 'vacaciones'
            AND YEAR(fechainicio) = :anio");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':anio', $anio);
        $resultado = $this->db->registro();
        return $resultado->total ?? 0;
    }
}