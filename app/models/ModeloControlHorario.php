<?php

class ModeloControlHorario
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fecha)
    {
        $this->db->query("SELECT * FROM jornadas WHERE idempleado = :idempleado AND fecha = :fecha AND eliminado = 0");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fecha', $fecha);
        return $this->db->registro();
    }

    public function crearJornada($idEmpleado, $fecha)
    {
        $this->db->query("INSERT INTO jornadas (idempleado, fecha, estadojornada) VALUES (:idempleado, :fecha, 'abierta')");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fecha', $fecha);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function cerrarJornada($idJornada, $horasTotales)
    {
        $this->db->query("UPDATE jornadas SET estadojornada = 'cerrada', completada = 1, horastotales = :horastotales WHERE id = :id");
        $this->db->bind(':horastotales', $horasTotales);
        $this->db->bind(':id', $idJornada);
        return $this->db->execute();
    }

    public function marcarJornadaIncompleta($idJornada)
    {
        $this->db->query("UPDATE jornadas SET estadojornada = 'incompleta', completada = 0 WHERE id = :id");
        $this->db->bind(':id', $idJornada);
        return $this->db->execute();
    }

    public function insertarFichaje($datos)
    {
        $this->db->query("INSERT INTO fichajes (idempleado, tipofichaje, fechahora, latitud, longitud, ipregistro, useragent, observaciones, idjornada)
            VALUES (:idempleado, :tipofichaje, NOW(), :latitud, :longitud, :ipregistro, :useragent, :observaciones, :idjornada)");
        $this->db->bind(':idempleado', $datos['idempleado']);
        $this->db->bind(':tipofichaje', $datos['tipofichaje']);
        $this->db->bind(':latitud', $datos['latitud']);
        $this->db->bind(':longitud', $datos['longitud']);
        $this->db->bind(':ipregistro', $datos['ipregistro']);
        $this->db->bind(':useragent', $datos['useragent']);
        $this->db->bind(':observaciones', $datos['observaciones']);
        $this->db->bind(':idjornada', $datos['idjornada']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function obtenerUltimoFichajeEmpleado($idEmpleado)
    {
        $this->db->query("SELECT * FROM fichajes WHERE idempleado = :idempleado AND eliminado = 0 ORDER BY fechahora DESC LIMIT 1");
        $this->db->bind(':idempleado', $idEmpleado);
        return $this->db->registro();
    }

    public function obtenerFichajesJornada($idJornada)
    {
        $this->db->query("SELECT * FROM fichajes WHERE idjornada = :idjornada AND eliminado = 0 ORDER BY fechahora ASC");
        $this->db->bind(':idjornada', $idJornada);
        return $this->db->registros();
    }

    public function obtenerFichajesJornadaActivos($idJornada)
    {
        $this->db->query("SELECT * FROM fichajes WHERE idjornada = :idjornada AND eliminado = 0 AND corregido = 0 ORDER BY fechahora ASC");
        $this->db->bind(':idjornada', $idJornada);
        return $this->db->registros();
    }

    public function recalcularEstadoJornada($idJornada)
    {
        require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');

        $jornada = $this->obtenerJornadaPorId($idJornada);
        if (!$jornada) return;

        if (in_array($jornada->estadojornada, ['vacaciones', 'baja', 'ausencia'])) return;

        $fichajes = $this->obtenerFichajesJornadaActivos($idJornada);
        $fechaHoy = date('Y-m-d');
        $horasTotales = ControlHorarioHelper::calcularHorasEntreFichajes($fichajes);

        if (empty($fichajes)) {
            if ($jornada->fecha < $fechaHoy) {
                $this->db->query("UPDATE jornadas SET estadojornada = 'incompleta', completada = 0, horastotales = 0 WHERE id = :id");
            } else {
                $this->db->query("UPDATE jornadas SET estadojornada = 'abierta', completada = 0, horastotales = 0 WHERE id = :id");
            }
            $this->db->bind(':id', $idJornada);
            $this->db->execute();
            return;
        }

        $ultimoFichaje = end($fichajes);

        if ($jornada->fecha < $fechaHoy) {
            if ($ultimoFichaje->tipofichaje === 'salida') {
                $this->db->query("UPDATE jornadas SET estadojornada = 'cerrada', completada = 1, horastotales = :horastotales WHERE id = :id");
                $this->db->bind(':horastotales', $horasTotales);
            } else {
                $this->db->query("UPDATE jornadas SET estadojornada = 'incompleta', completada = 0, horastotales = :horastotales WHERE id = :id");
                $this->db->bind(':horastotales', $horasTotales);
            }
        } else {
            $this->db->query("UPDATE jornadas SET estadojornada = 'abierta', completada = 0, horastotales = :horastotales WHERE id = :id");
            $this->db->bind(':horastotales', $horasTotales);
        }
        $this->db->bind(':id', $idJornada);
        $this->db->execute();
    }

    public function obtenerFichajesPorEmpleadoYFecha($idEmpleado, $fecha)
    {
        $this->db->query("SELECT * FROM fichajes WHERE idempleado = :idempleado AND DATE(fechahora) = :fecha AND eliminado = 0 AND corregido = 0 ORDER BY fechahora ASC");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fecha', $fecha);
        return $this->db->registros();
    }

    public function marcarFichajeCorregido($idFichaje)
    {
        $this->db->query("UPDATE fichajes SET corregido = 1 WHERE id = :id");
        $this->db->bind(':id', $idFichaje);
        return $this->db->execute();
    }

    public function eliminarFichaje($idFichaje)
    {
        $this->db->query("UPDATE fichajes SET eliminado = 1 WHERE id = :id");
        $this->db->bind(':id', $idFichaje);
        return $this->db->execute();
    }

    public function obtenerJornadasPorEmpleado($idEmpleado, $limite = 30)
    {
        $this->db->query("SELECT * FROM jornadas WHERE idempleado = :idempleado AND eliminado = 0 ORDER BY fecha DESC LIMIT :limite");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':limite', $limite, PDO::PARAM_INT);
        return $this->db->registros();
    }

    public function obtenerJornadasPorEmpleadoRango($idEmpleado, $fechaInicio, $fechaFin)
    {
        $this->db->query("SELECT * FROM jornadas WHERE idempleado = :idempleado AND fecha BETWEEN :fechainicio AND :fechafin AND eliminado = 0 ORDER BY fecha DESC");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        return $this->db->registros();
    }

    public function obtenerTodasJornadasRango($fechaInicio, $fechaFin)
    {
        $this->db->query("SELECT j.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado
            FROM jornadas j
            LEFT JOIN usuarios u ON j.idempleado = u.id
            WHERE j.fecha BETWEEN :fechainicio AND :fechafin AND j.eliminado = 0
            ORDER BY j.fecha DESC, u.nombre ASC");
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        return $this->db->registros();
    }

    public function obtenerTodasJornadasRangoFiltrado($fechaInicio, $fechaFin, $idEmpleado = null)
    {
        if ($idEmpleado !== null) {
            $this->db->query("SELECT j.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado
                FROM jornadas j
                LEFT JOIN usuarios u ON j.idempleado = u.id
                WHERE j.fecha BETWEEN :fechainicio AND :fechafin AND j.idempleado = :idempleado AND j.eliminado = 0
                ORDER BY j.fecha DESC, u.nombre ASC");
            $this->db->bind(':idempleado', $idEmpleado);
        } else {
            $this->db->query("SELECT j.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado
                FROM jornadas j
                LEFT JOIN usuarios u ON j.idempleado = u.id
                WHERE j.fecha BETWEEN :fechainicio AND :fechafin AND j.eliminado = 0
                ORDER BY j.fecha DESC, u.nombre ASC");
        }
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        return $this->db->registros();
    }

    public function obtenerFichajesConEmpleadoRango($fechaInicio, $fechaFin, $idEmpleado = null)
    {
        if ($idEmpleado !== null) {
            $this->db->query("SELECT f.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado, j.fecha AS fechajornada
                FROM fichajes f
                LEFT JOIN usuarios u ON f.idempleado = u.id
                LEFT JOIN jornadas j ON f.idjornada = j.id
                WHERE f.fechahora BETWEEN :fechainicio AND :fechafin
                AND f.idempleado = :idempleado AND f.eliminado = 0
                ORDER BY f.fechahora DESC");
            $this->db->bind(':idempleado', $idEmpleado);
        } else {
            $this->db->query("SELECT f.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado, j.fecha AS fechajornada
                FROM fichajes f
                LEFT JOIN usuarios u ON f.idempleado = u.id
                LEFT JOIN jornadas j ON f.idjornada = j.id
                WHERE f.fechahora BETWEEN :fechainicio AND :fechafin
                AND f.eliminado = 0
                ORDER BY f.fechahora DESC");
        }
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        return $this->db->registros();
    }

    public function obtenerEmpleadosQueFichan()
    {
        $this->db->query("SELECT u.id, u.nombre, u.apellidos, u.rol, ch.debeFichar
            FROM usuarios u
            LEFT JOIN configuracionhorario ch ON u.id = ch.idempleado AND ch.eliminado = 0
            WHERE u.rol IN (0, 2, 3) AND u.activo = 1
            ORDER BY u.nombre ASC");
        return $this->db->registros();
    }

    public function calcularHorasJornada($idJornada)
    {
        $fichajes = $this->obtenerFichajesJornada($idJornada);
        $totalSegundos = 0;
        $pares = [];
        $entrada = null;

        foreach ($fichajes as $f) {
            if (isset($f->corregido) && $f->corregido) {
                continue;
            }
            if ($f->tipofichaje === 'entrada') {
                $entrada = new DateTime($f->fechahora);
            } elseif ($f->tipofichaje === 'salida' && $entrada !== null) {
                $salida = new DateTime($f->fechahora);
                $diff = $entrada->diff($salida);
                $totalSegundos += ($diff->h * 3600) + ($diff->i * 60) + $diff->s;
                $entrada = null;
            }
        }

        return round($totalSegundos / 3600, 2);
    }

    public function buscarJornadasAbiertasAnteriores($idEmpleado, $fechaHoy)
    {
        $this->db->query("SELECT * FROM jornadas WHERE idempleado = :idempleado AND fecha < :fecha AND estadojornada = 'abierta' AND eliminado = 0");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fecha', $fechaHoy);
        return $this->db->registros();
    }

    public function buscarJornadasIncompletasAnteriores($idEmpleado, $fechaHoy)
    {
        $this->db->query("SELECT * FROM jornadas WHERE idempleado = :idempleado AND fecha < :fecha AND estadojornada = 'incompleta' AND eliminado = 0 ORDER BY fecha ASC");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fecha', $fechaHoy);
        return $this->db->registros();
    }

    public function obtenerJornadaPorId($idJornada)
    {
        $this->db->query("SELECT j.*, CONCAT(u.nombre, ' ', u.apellidos) AS nombreempleado
            FROM jornadas j
            LEFT JOIN usuarios u ON j.idempleado = u.id
            WHERE j.id = :id AND j.eliminado = 0");
        $this->db->bind(':id', $idJornada);
        return $this->db->registro();
    }

    public function obtenerFichajePorId($idFichaje)
    {
        $this->db->query("SELECT * FROM fichajes WHERE id = :id AND eliminado = 0");
        $this->db->bind(':id', $idFichaje);
        return $this->db->registro();
    }

    public function insertarFichajeConFecha($datos)
    {
        $this->db->query("INSERT INTO fichajes (idempleado, tipofichaje, fechahora, latitud, longitud, ipregistro, useragent, observaciones, idjornada)
            VALUES (:idempleado, :tipofichaje, :fechahora, :latitud, :longitud, :ipregistro, :useragent, :observaciones, :idjornada)");
        $this->db->bind(':idempleado', $datos['idempleado']);
        $this->db->bind(':tipofichaje', $datos['tipofichaje']);
        $this->db->bind(':fechahora', $datos['fechahora']);
        $this->db->bind(':latitud', $datos['latitud']);
        $this->db->bind(':longitud', $datos['longitud']);
        $this->db->bind(':ipregistro', $datos['ipregistro']);
        $this->db->bind(':useragent', $datos['useragent']);
        $this->db->bind(':observaciones', $datos['observaciones']);
        $this->db->bind(':idjornada', $datos['idjornada']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function actualizarHorasJornada($idJornada, $horasTotales)
    {
        $this->db->query("UPDATE jornadas SET horastotales = :horastotales WHERE id = :id");
        $this->db->bind(':horastotales', $horasTotales);
        $this->db->bind(':id', $idJornada);
        return $this->db->execute();
    }

    public function fichajeRecienteExiste($idEmpleado, $segundos = 5)
    {
        $this->db->query("SELECT COUNT(*) as total FROM fichajes WHERE idempleado = :idempleado AND fechahora >= NOW() - INTERVAL :segundos SECOND AND eliminado = 0");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':segundos', $segundos, PDO::PARAM_INT);
        $resultado = $this->db->registro();
        return $resultado && $resultado->total > 0;
    }

    public function obtenerFichajesPorEmpleadoRangoFechas($idEmpleado, $fechaInicio, $fechaFin)
    {
        $this->db->query("SELECT f.*, j.fecha AS fechajornada
            FROM fichajes f
            LEFT JOIN jornadas j ON f.idjornada = j.id
            WHERE f.idempleado = :idempleado AND f.eliminado = 0 AND f.corregido = 0
            AND j.fecha BETWEEN :fechainicio AND :fechafin
            ORDER BY j.fecha ASC, f.fechahora ASC");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fechainicio', $fechaInicio);
        $this->db->bind(':fechafin', $fechaFin);
        return $this->db->registros();
    }

    public function obtenerJornadaPorEmpleadoYFechaTodo($idEmpleado, $fecha)
    {
        $this->db->query("SELECT * FROM jornadas WHERE idempleado = :idempleado AND fecha = :fecha LIMIT 1");
        $this->db->bind(':idempleado', $idEmpleado);
        $this->db->bind(':fecha', $fecha);
        return $this->db->registro();
    }

    public function restaurarJornada($idJornada, $estadoJornada = 'abierta')
    {
        $this->db->query("UPDATE jornadas SET eliminado = 0, estadojornada = :estado, completada = 0, horastotales = 0 WHERE id = :id");
        $this->db->bind(':estado', $estadoJornada);
        $this->db->bind(':id', $idJornada);
        return $this->db->execute();
    }
}