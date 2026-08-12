<?php

class ModeloConfiguracionHorario
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerConfigPorEmpleado($idEmpleado)
    {
        $this->db->query("SELECT * FROM configuracionhorario WHERE idempleado = :idempleado AND eliminado = 0");
        $this->db->bind(':idempleado', $idEmpleado);
        return $this->db->registro();
    }

    public function crearConfig($datos)
    {
        $this->db->query("INSERT INTO configuracionhorario
            (idempleado, debeFichar, jornadatipohoras, horarioentrada, horariosalida, toleranciaminutos)
            VALUES (:idempleado, :debeFichar, :jornadatipohoras, :horarioentrada, :horariosalida, :toleranciaminutos)");
        $this->db->bind(':idempleado', $datos['idempleado']);
        $this->db->bind(':debeFichar', $datos['debeFichar']);
        $this->db->bind(':jornadatipohoras', $datos['jornadatipohoras']);
        $this->db->bind(':horarioentrada', $datos['horarioentrada']);
        $this->db->bind(':horariosalida', $datos['horariosalida']);
        $this->db->bind(':toleranciaminutos', $datos['toleranciaminutos']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function actualizarConfig($datos)
    {
        $this->db->query("UPDATE configuracionhorario
            SET debeFichar = :debeFichar, jornadatipohoras = :jornadatipohoras,
            horarioentrada = :horarioentrada, horariosalida = :horariosalida,
            toleranciaminutos = :toleranciaminutos
            WHERE idempleado = :idempleado AND eliminado = 0");
        $this->db->bind(':debeFichar', $datos['debeFichar']);
        $this->db->bind(':jornadatipohoras', $datos['jornadatipohoras']);
        $this->db->bind(':horarioentrada', $datos['horarioentrada']);
        $this->db->bind(':horariosalida', $datos['horariosalida']);
        $this->db->bind(':toleranciaminutos', $datos['toleranciaminutos']);
        $this->db->bind(':idempleado', $datos['idempleado']);
        return $this->db->execute();
    }

    public function obtenerTodosConfig()
    {
        $this->db->query("SELECT u.id AS idempleado, u.nombre, u.apellidos, u.rol,
            COALESCE(ch.id, 0) AS configId,
            COALESCE(ch.debeFichar, 0) AS debeFichar,
            COALESCE(ch.jornadatipohoras, 8.00) AS jornadatipohoras,
            COALESCE(ch.horarioentrada, '09:00:00') AS horarioentrada,
            COALESCE(ch.horariosalida, '18:00:00') AS horariosalida,
            COALESCE(ch.toleranciaminutos, 5) AS toleranciaminutos,
            CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado
            FROM usuarios u
            LEFT JOIN configuracionhorario ch ON ch.idempleado = u.id AND ch.eliminado = 0
            WHERE u.rol IN (0, 2, 3) AND u.activo = 1
            ORDER BY u.nombre ASC");
        return $this->db->registros();
    }

    public function empleadoDebeFichar($idEmpleado)
    {
        $this->db->query("SELECT debeFichar FROM configuracionhorario WHERE idempleado = :idempleado AND eliminado = 0");
        $this->db->bind(':idempleado', $idEmpleado);
        $resultado = $this->db->registro();
        if ($resultado) {
            return (int)$resultado->debeFichar === 1;
        }
        return false;
    }

    public function obtenerEmpleadosActivos()
    {
        $this->db->query("SELECT u.id AS idempleado, u.nombre, u.apellidos, u.rol,
            COALESCE(ch.id, 0) AS configId,
            COALESCE(ch.debeFichar, 0) AS debeFichar,
            COALESCE(ch.jornadatipohoras, 8.00) AS jornadatipohoras,
            COALESCE(ch.horarioentrada, '09:00:00') AS horarioentrada,
            COALESCE(ch.horariosalida, '18:00:00') AS horariosalida,
            COALESCE(ch.toleranciaminutos, 5) AS toleranciaminutos,
            CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado
            FROM usuarios u
            LEFT JOIN configuracionhorario ch ON ch.idempleado = u.id AND ch.eliminado = 0
            WHERE u.rol IN (0, 2, 3) AND u.activo = 1 AND ch.debeFichar = 1
            ORDER BY u.nombre ASC");
        return $this->db->registros();
    }
}