<?php

class ModeloAuditoria
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function registrarAccion($datos)
    {
        $this->db->query("INSERT INTO auditoria
            (idusuario, accion, entidad, identidad, datosprevios, datosnuevos, ip, useragent)
            VALUES (:idusuario, :accion, :entidad, :identidad, :datosprevios, :datosnuevos, :ip, :useragent)");
        $this->db->bind(':idusuario', $datos['idusuario']);
        $this->db->bind(':accion', $datos['accion']);
        $this->db->bind(':entidad', $datos['entidad']);
        $this->db->bind(':identidad', $datos['identidad']);
        $this->db->bind(':datosprevios', $datos['datosprevios']);
        $this->db->bind(':datosnuevos', $datos['datosnuevos']);
        $this->db->bind(':ip', $datos['ip']);
        $this->db->bind(':useragent', $datos['useragent']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function obtenerAuditoriaPorEntidad($entidad, $identidad)
    {
        $this->db->query("SELECT a.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreusuario
            FROM auditoria a
            LEFT JOIN usuarios u ON a.idusuario = u.id
            WHERE a.entidad = :entidad AND a.identidad = :identidad
            ORDER BY a.creadoen DESC");
        $this->db->bind(':entidad', $entidad);
        $this->db->bind(':identidad', $identidad);
        return $this->db->registros();
    }

    public function obtenerAuditoriaPorUsuario($idUsuario, $limite = 100)
    {
        $this->db->query("SELECT * FROM auditoria WHERE idusuario = :idusuario ORDER BY creadoen DESC LIMIT :limite");
        $this->db->bind(':idusuario', $idUsuario);
        $this->db->bind(':limite', $limite, PDO::PARAM_INT);
        return $this->db->registros();
    }

    public function obtenerAuditoriaRango($fechaInicio, $fechaFin)
    {
        $this->db->query("SELECT a.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreusuario
            FROM auditoria a
            LEFT JOIN usuarios u ON a.idusuario = u.id
            WHERE a.creadoen BETWEEN :fechainicio AND :fechafin
            ORDER BY a.creadoen DESC");
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        return $this->db->registros();
    }

    public function obtenerAuditoriaPorAccion($accion, $limite = 100)
    {
        $this->db->query("SELECT a.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreusuario
            FROM auditoria a
            LEFT JOIN usuarios u ON a.idusuario = u.id
            WHERE a.accion = :accion
            ORDER BY a.creadoen DESC
            LIMIT :limite");
        $this->db->bind(':accion', $accion);
        $this->db->bind(':limite', $limite, PDO::PARAM_INT);
        return $this->db->registros();
    }
}