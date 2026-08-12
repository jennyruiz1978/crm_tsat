<?php

class ControlHorarioHelper
{
    public static function validarTipoFichaje($ultimoFichaje, $tipoSolicitado)
    {
        if (!$ultimoFichaje) {
            return $tipoSolicitado === 'entrada';
        }
        if ($ultimoFichaje->tipofichaje === $tipoSolicitado) {
            return false;
        }
        return true;
    }

    public static function calcularHorasEntreFichajes($fichajes)
    {
        $totalSegundos = 0;
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

    public static function jornadaTieneSalidaPendiente($fichajes)
    {
        if (empty($fichajes)) {
            return false;
        }
        $ultimo = end($fichajes);
        return $ultimo->tipofichaje === 'entrada';
    }

    public static function obtenerTipoFichajeSiguiente($ultimoFichaje, $fechaReferencia = null)
    {
        if (!$ultimoFichaje) {
            return 'entrada';
        }
        if ($fechaReferencia === null) {
            $fechaReferencia = date('Y-m-d');
        }
        $fechaUltimo = date('Y-m-d', strtotime($ultimoFichaje->fechahora));
        if ($fechaUltimo < $fechaReferencia && $ultimoFichaje->tipofichaje === 'entrada') {
            return 'entrada';
        }
        return $ultimoFichaje->tipofichaje === 'entrada' ? 'salida' : 'entrada';
    }

    public static function detectarJornadasSinCerrar($jornadasAbiertas)
    {
        $hoy = new DateTime();
        $hoy->setTime(0, 0, 0);
        $pendientes = [];

        foreach ($jornadasAbiertas as $jornada) {
            $fechaJornada = new DateTime($jornada->fecha);
            if ($fechaJornada < $hoy) {
                $pendientes[] = $jornada;
            }
        }

        return $pendientes;
    }

    public static function formatearHoras($horasDecimales)
    {
        $horas = floor($horasDecimales);
        $minutos = round(($horasDecimales - $horas) * 60);
        return sprintf('%d:%02d', $horas, $minutos);
    }

    public static function obtenerIPCliente()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
    }

    public static function obtenerUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'desconocido';
    }

    public static function validarGeolocalizacion($latitud, $longitud)
    {
        if ($latitud === null || $longitud === null) {
            return false;
        }
        if (!is_numeric($latitud) || !is_numeric($longitud)) {
            return false;
        }
        $lat = floatval($latitud);
        $lon = floatval($longitud);
        if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
            return false;
        }
        return true;
    }

    public static function generarDatosAuditoria($accion, $entidad, $identidad, $datosPrevios = null, $datosNuevos = null)
    {
        return [
            'idusuario' => $_SESSION['idusuario'] ?? 0,
            'accion' => $accion,
            'entidad' => $entidad,
            'identidad' => $identidad,
            'datosprevios' => $datosPrevios ? json_encode($datosPrevios) : null,
            'datosnuevos' => $datosNuevos ? json_encode($datosNuevos) : null,
            'ip' => self::obtenerIPCliente(),
            'useragent' => self::obtenerUserAgent()
        ];
    }

    public static function calcularDiasEntreFechas($fechaInicio, $fechaFin)
    {
        $inicio = new DateTime($fechaInicio);
        $fin = new DateTime($fechaFin);
        $diff = $inicio->diff($fin);
        return $diff->days + 1;
    }

    public static function esFinDeSemana($fecha)
    {
        $dia = date('N', strtotime($fecha));
        return $dia >= 6;
    }

    public static function calcularDiasLaborables($fechaInicio, $fechaFin)
    {
        $inicio = new DateTime($fechaInicio);
        $fin = new DateTime($fechaFin);
        $diasLaborables = 0;

        while ($inicio <= $fin) {
            if (!self::esFinDeSemana($inicio->format('Y-m-d'))) {
                $diasLaborables++;
            }
            $inicio->modify('+1 day');
        }

        return $diasLaborables;
    }
}