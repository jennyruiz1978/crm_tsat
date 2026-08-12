<?php

class Vacaciones extends Controlador
{
    private $ModelVacaciones;
    private $ModelConfigHorario;
    private $ModelAuditoria;
    private $ModelControlHorario;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['idusuario'])) {
            redireccionar('/Login');
            return;
        }
        $this->controlPermisos();
        if (isset($_SESSION['debeFichar']) && $_SESSION['debeFichar'] == 0) {
            redireccionar('/Inicio');
            return;
        }
        $this->ModelVacaciones = $this->modelo('ModeloVacaciones');
        $this->ModelConfigHorario = $this->modelo('ModeloConfiguracionHorario');
        $this->ModelAuditoria = $this->modelo('ModeloAuditoria');
        $this->ModelControlHorario = $this->modelo('ModeloControlHorario');
    }

    public function index()
    {
        $this->misVacaciones();
    }

    public function solicitarVacacion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idEmpleado = $_SESSION['idusuario'];
            $fechaInicio = $_POST['fechainicio'];
            $fechaFin = $_POST['fechafin'];
            $tipoVacacion = $_POST['tipovacacion'];
            $motivo = $_POST['motivo'];

            $fechaInicioObj = new DateTime($fechaInicio);
            $fechaFinObj = new DateTime($fechaFin);
            $dias = $fechaFinObj->diff($fechaInicioObj)->days + 1;

            $datos = [
                'idempleado' => $idEmpleado,
                'tipovacacion' => $tipoVacacion,
                'fechainicio' => $fechaInicio,
                'fechafin' => $fechaFin,
                'dias' => $dias,
                'motivo' => $motivo,
                'ficherojustificante' => null
            ];

            if (isset($_FILES['justificante']) && $_FILES['justificante']['error'] === UPLOAD_ERR_OK) {
                $ficheroSubido = $this->procesarArchivoJustificante($_FILES['justificante'], $idEmpleado);
                if ($ficheroSubido) {
                    $datos['ficherojustificante'] = $ficheroSubido;
                }
            }

            if ($tipoVacacion === 'baja' && empty($datos['ficherojustificante'])) {
                $datos = [];
                $datos['errorSolapamiento'] = 'El justificante médico es obligatorio para bajas médicas.';
                $datos['fechainicioPost'] = $fechaInicio;
                $datos['fechafinPost'] = $fechaFin;
                $datos['tipovacacionPost'] = $tipoVacacion;
                $datos['motivoPost'] = $motivo;
                $this->vista('vacaciones/solicitarVacacion', $datos);
                return;
            }

            $solapadas = $this->ModelVacaciones->obtenerVacacionesSolapadas($idEmpleado, $fechaInicio, $fechaFin);
            if (!empty($solapadas)) {
                $fechasConflictivas = [];
                foreach ($solapadas as $s) {
                    $fechasConflictivas[] = date('d/m/Y', strtotime($s->fechainicio)) . ' - ' . date('d/m/Y', strtotime($s->fechafin)) . ' (' . $s->estado . ')';
                }
                $datos = [];
                $datos['errorSolapamiento'] = 'Ya tiene vacaciones en ese periodo: ' . implode(', ', $fechasConflictivas);
                $datos['fechainicioPost'] = $fechaInicio;
                $datos['fechafinPost'] = $fechaFin;
                $datos['tipovacacionPost'] = $tipoVacacion;
                $datos['motivoPost'] = $motivo;
                $this->vista('vacaciones/solicitarVacacion', $datos);
                return;
            }

            $fichajesEnRango = $this->ModelControlHorario->obtenerFichajesPorEmpleadoRangoFechas($idEmpleado, $fechaInicio, $fechaFin);
            if (!empty($fichajesEnRango)) {
                $fechasFichajes = [];
                foreach ($fichajesEnRango as $f) {
                    $fechaFichaje = date('d/m/Y', strtotime($f->fechajornada));
                    if (!in_array($fechaFichaje, $fechasFichajes)) {
                        $fechasFichajes[] = $fechaFichaje;
                    }
                }
                $datos = [];
                $datos['errorSolapamiento'] = 'No puede solicitar vacaciones en fechas donde ya tiene fichajes: ' . implode(', ', $fechasFichajes) . '. Solicite una modificación de fichaje primero.';
                $datos['fechainicioPost'] = $fechaInicio;
                $datos['fechafinPost'] = $fechaFin;
                $datos['tipovacacionPost'] = $tipoVacacion;
                $datos['motivoPost'] = $motivo;
                $this->vista('vacaciones/solicitarVacacion', $datos);
                return;
            }

            $idVacacion = $this->ModelVacaciones->crearSolicitud($datos);

            if ($idVacacion) {
                require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');
                $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                    'solicitud_vacacion',
                    'vacaciones',
                    $idVacacion,
                    null,
                    $datos
                );
                $this->ModelAuditoria->registrarAccion($datosAuditoria);

                if ($tipoVacacion === 'vacaciones') {
                    $this->crearJornadasVacaciones($idEmpleado, $fechaInicio, $fechaFin);
                } elseif ($tipoVacacion === 'baja') {
                    $this->crearJornadasBaja($idEmpleado, $fechaInicio, $fechaFin);
                } elseif ($tipoVacacion === 'ausencia') {
                    $this->crearJornadasAusencia($idEmpleado, $fechaInicio, $fechaFin);
                }

                header('Location: ' . RUTA_URL . '/Vacaciones/misVacaciones');
                return;
            }
        }

        $this->vista('vacaciones/solicitarVacacion');
    }

    public function misVacaciones()
    {
        $idEmpleado = $_SESSION['idusuario'];
        $vacaciones = $this->ModelVacaciones->obtenerVacacionesPorEmpleado($idEmpleado);

        $datos = [
            'vacaciones' => $vacaciones,
            'esAdmin' => $_SESSION['nombrerol'] === 'admin'
        ];

        $this->vista('vacaciones/misVacaciones', $datos);
    }

    public function listadoVacaciones()
    {
        $vacaciones = $this->ModelVacaciones->obtenerTodasVacaciones();
        $pendientes = $this->ModelVacaciones->obtenerVacacionesPendientes();

        $datos = [
            'vacaciones' => $vacaciones,
            'pendientes' => $pendientes
        ];

        $this->vista('vacaciones/listadoVacaciones', $datos);
    }

    public function resolverVacacion($idVacacion = null)
    {
        if ($idVacacion === null) {
            header('Location: ' . RUTA_URL . '/Vacaciones/listadoVacaciones');
            return;
        }

        $vacacion = $this->ModelVacaciones->obtenerVacacionPorId($idVacacion);

        if (!$vacacion || !in_array($vacacion->estado, ['pendiente', 'aprobada'])) {
            header('Location: ' . RUTA_URL . '/Vacaciones/listadoVacaciones');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'];
            $respuesta = $_POST['respuestaadmin'] ?? '';
            $idAdmin = $_SESSION['idusuario'];

            require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');

            if ($accion === 'aprobar') {
                $solapadas = $this->ModelVacaciones->obtenerVacacionesSolapadas($vacacion->idempleado, $vacacion->fechainicio, $vacacion->fechafin, $idVacacion);
                $aprobadasSolapadas = array_filter($solapadas, function($s) { return $s->estado === 'aprobada'; });
                if (!empty($aprobadasSolapadas)) {
                    $fechasConflictivas = [];
                    foreach ($aprobadasSolapadas as $s) {
                        $fechasConflictivas[] = date('d/m/Y', strtotime($s->fechainicio)) . ' - ' . date('d/m/Y', strtotime($s->fechafin));
                    }
                    $datos = ['vacacion' => $vacacion, 'errorSolapamiento' => 'No se puede aprobar: ya existen vacaciones aprobadas en ese periodo (' . implode(', ', $fechasConflictivas) . ')'];
                    $this->vista('vacaciones/resolverVacacion', $datos);
                    return;
                }

                $fichajesEnRango = $this->ModelControlHorario->obtenerFichajesPorEmpleadoRangoFechas($vacacion->idempleado, $vacacion->fechainicio, $vacacion->fechafin);
                if (!empty($fichajesEnRango)) {
                    $fechasFichajes = [];
                    foreach ($fichajesEnRango as $f) {
                        $fechaFichaje = date('d/m/Y', strtotime($f->fechajornada));
                        if (!in_array($fechaFichaje, $fechasFichajes)) {
                            $fechasFichajes[] = $fechaFichaje;
                        }
                    }
                    $datos = ['vacacion' => $vacacion, 'errorSolapamiento' => 'No se puede aprobar: el empleado ya tiene fichajes registrados en las fechas ' . implode(', ', $fechasFichajes) . '.'];
                    $this->vista('vacaciones/resolverVacacion', $datos);
                    return;
                }

                $resultado = $this->ModelVacaciones->aprobarVacacion($idVacacion, $idAdmin, $respuesta);

                if ($resultado) {
                    $this->actualizarJornadasVacaciones($vacacion);

                    $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                        'aprobacion_vacacion',
                        'vacaciones',
                        $idVacacion,
                        (array)$vacacion,
                        ['estado' => 'aprobada']
                    );
                    $this->ModelAuditoria->registrarAccion($datosAuditoria);
                }
            } elseif ($accion === 'rechazar') {
                $resultado = $this->ModelVacaciones->rechazarVacacion($idVacacion, $idAdmin, $respuesta);

                if ($resultado) {
                    $this->eliminarJornadasPendientes($vacacion);

                    $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                        'rechazo_vacacion',
                        'vacaciones',
                        $idVacacion,
                        (array)$vacacion,
                        ['estado' => 'rechazada']
                    );
                    $this->ModelAuditoria->registrarAccion($datosAuditoria);
                }
            } elseif ($accion === 'cancelar') {
                $resultado = $this->ModelVacaciones->cancelarVacacion($idVacacion, $idAdmin, $respuesta);

                if ($resultado) {
                    $this->revertirJornadasCanceladas($vacacion);

                    $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                        'cancelacion_vacacion',
                        'vacaciones',
                        $idVacacion,
                        (array)$vacacion,
                        ['estado' => 'cancelada']
                    );
                    $this->ModelAuditoria->registrarAccion($datosAuditoria);
                }
            }

            header('Location: ' . RUTA_URL . '/Vacaciones/listadoVacaciones');
            return;
        }

        $datos = [
            'vacacion' => $vacacion
        ];

        $this->vista('vacaciones/resolverVacacion', $datos);
    }

    public function subirJustificante($idVacacion = null)
    {
        if ($idVacacion === null) {
            header('Location: ' . RUTA_URL . '/Vacaciones/misVacaciones');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['justificante'])) {
            $idEmpleado = $_SESSION['idusuario'];
            $ficheroSubido = $this->procesarArchivoJustificante($_FILES['justificante'], $idEmpleado);

            if ($ficheroSubido) {
                $this->ModelVacaciones->actualizarJustificante($idVacacion, $ficheroSubido);

                require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');
                $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                    'subida_justificante_vacacion',
                    'vacaciones',
                    $idVacacion,
                    null,
                    ['fichero' => $ficheroSubido]
                );
                $this->ModelAuditoria->registrarAccion($datosAuditoria);
            }

            header('Location: ' . RUTA_URL . '/Vacaciones/misVacaciones');
            return;
        }

        $vacacion = $this->ModelVacaciones->obtenerVacacionPorId($idVacacion);

        $datos = [
            'vacacion' => $vacacion
        ];

        $this->vista('vacaciones/uploadJustificante', $datos);
    }

    private function procesarArchivoJustificante($archivo, $idEmpleado)
    {
        $directorio = RUTA_APP . '/../public/documentos/Vacaciones/';

        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $extensionesPermitidas = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            return false;
        }

        $nombreArchivo = 'justificante_' . $idEmpleado . '_' . time() . '.' . $extension;
        $rutaDestino = $directorio . $nombreArchivo;

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            return RUTA_URL . '/public/documentos/Vacaciones/' . $nombreArchivo;
        }

        return false;
    }

    private function crearJornadasVacaciones($idEmpleado, $fechaInicio, $fechaFin)
    {
        $modeloCH = $this->modelo('ModeloControlHorario');
        $fecha = new DateTime($fechaInicio);
        $fechaFinObj = new DateTime($fechaFin);

        while ($fecha <= $fechaFinObj) {
            $fechaStr = $fecha->format('Y-m-d');
            $jornadaExistente = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
            if ($jornadaExistente) {
                $jornada = $jornadaExistente;
            } else {
                $jornadaEliminada = $modeloCH->obtenerJornadaPorEmpleadoYFechaTodo($idEmpleado, $fechaStr);
                if ($jornadaEliminada) {
                    $modeloCH->restaurarJornada($jornadaEliminada->id, 'vacaciones');
                    $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
                } else {
                    $modeloCH->crearJornada($idEmpleado, $fechaStr);
                    $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
                }
            }

            if (isset($jornada) && $jornada) {
                $this->db = new Base;
                $this->db->query("UPDATE jornadas SET estadojornada = 'vacaciones' WHERE id = :id");
                $this->db->bind(':id', $jornada->id);
                $this->db->execute();
            }
            $fecha->modify('+1 day');
        }
    }

    private function crearJornadasBaja($idEmpleado, $fechaInicio, $fechaFin)
    {
        $modeloCH = $this->modelo('ModeloControlHorario');
        $fecha = new DateTime($fechaInicio);
        $fechaFinObj = new DateTime($fechaFin);

        while ($fecha <= $fechaFinObj) {
            $fechaStr = $fecha->format('Y-m-d');
            $jornadaExistente = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
            if ($jornadaExistente) {
                $jornada = $jornadaExistente;
            } else {
                $jornadaEliminada = $modeloCH->obtenerJornadaPorEmpleadoYFechaTodo($idEmpleado, $fechaStr);
                if ($jornadaEliminada) {
                    $modeloCH->restaurarJornada($jornadaEliminada->id, 'baja');
                    $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
                } else {
                    $modeloCH->crearJornada($idEmpleado, $fechaStr);
                    $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
                }
            }

            if (isset($jornada) && $jornada) {
                $this->db = new Base;
                $this->db->query("UPDATE jornadas SET estadojornada = 'baja' WHERE id = :id");
                $this->db->bind(':id', $jornada->id);
                $this->db->execute();
            }
            $fecha->modify('+1 day');
        }
    }

    private function crearJornadasAusencia($idEmpleado, $fechaInicio, $fechaFin)
    {
        $modeloCH = $this->modelo('ModeloControlHorario');
        $fecha = new DateTime($fechaInicio);
        $fechaFinObj = new DateTime($fechaFin);

        while ($fecha <= $fechaFinObj) {
            $fechaStr = $fecha->format('Y-m-d');
            $jornadaExistente = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
            if ($jornadaExistente) {
                $jornada = $jornadaExistente;
            } else {
                $jornadaEliminada = $modeloCH->obtenerJornadaPorEmpleadoYFechaTodo($idEmpleado, $fechaStr);
                if ($jornadaEliminada) {
                    $modeloCH->restaurarJornada($jornadaEliminada->id, 'ausencia');
                    $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
                } else {
                    $modeloCH->crearJornada($idEmpleado, $fechaStr);
                    $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaStr);
                }
            }

            if (isset($jornada) && $jornada) {
                $this->db = new Base;
                $this->db->query("UPDATE jornadas SET estadojornada = 'ausencia' WHERE id = :id");
                $this->db->bind(':id', $jornada->id);
                $this->db->execute();
            }
            $fecha->modify('+1 day');
        }
    }

    private function actualizarJornadasVacaciones($vacacion)
    {
        $modeloCH = $this->modelo('ModeloControlHorario');
        $fecha = new DateTime($vacacion->fechainicio);
        $fechaFin = new DateTime($vacacion->fechafin);

        while ($fecha <= $fechaFin) {
            $fechaStr = $fecha->format('Y-m-d');
            $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($vacacion->idempleado, $fechaStr);

            if ($jornada) {
                $estado = $vacacion->tipovacacion === 'baja' ? 'baja' : ($vacacion->tipovacacion === 'ausencia' ? 'ausencia' : 'vacaciones');
                $this->db = new Base;
                $this->db->query("UPDATE jornadas SET estadojornada = :estado WHERE id = :id");
                $this->db->bind(':estado', $estado);
                $this->db->bind(':id', $jornada->id);
                $this->db->execute();
            }
            $fecha->modify('+1 day');
        }
    }

    private function eliminarJornadasPendientes($vacacion)
    {
        $modeloCH = $this->modelo('ModeloControlHorario');
        $fecha = new DateTime($vacacion->fechainicio);
        $fechaFin = new DateTime($vacacion->fechafin);

        while ($fecha <= $fechaFin) {
            $fechaStr = $fecha->format('Y-m-d');
            $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($vacacion->idempleado, $fechaStr);

            if ($jornada && ($jornada->estadojornada === 'vacaciones' || $jornada->estadojornada === 'baja' || $jornada->estadojornada === 'ausencia')) {
                $this->db = new Base;
                $this->db->query("UPDATE jornadas SET estadojornada = 'abierta', horastotales = 0, completada = 0 WHERE id = :id");
                $this->db->bind(':id', $jornada->id);
                $this->db->execute();
            }
            $fecha->modify('+1 day');
        }
    }

    private function revertirJornadasCanceladas($vacacion)
    {
        $modeloCH = $this->modelo('ModeloControlHorario');
        $fecha = new DateTime($vacacion->fechainicio);
        $fechaFin = new DateTime($vacacion->fechafin);

        while ($fecha <= $fechaFin) {
            $fechaStr = $fecha->format('Y-m-d');
            $jornada = $modeloCH->obtenerJornadaPorEmpleadoYFecha($vacacion->idempleado, $fechaStr);

            if ($jornada && ($jornada->estadojornada === 'vacaciones' || $jornada->estadojornada === 'baja' || $jornada->estadojornada === 'ausencia')) {
                $this->db = new Base;
                $this->db->query("UPDATE jornadas SET estadojornada = 'abierta', horastotales = 0, completada = 0 WHERE id = :id");
                $this->db->bind(':id', $jornada->id);
                $this->db->execute();
            }
            $fecha->modify('+1 day');
        }
    }
}