<?php

class ControlHorario extends Controlador
{
    private $ModelControlHorario;
    private $ModelSolicitudes;
    private $ModelConfigHorario;
    private $ModelAuditoria;
    private $ModelVacaciones;

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
        $this->ModelControlHorario = $this->modelo('ModeloControlHorario');
        $this->ModelSolicitudes = $this->modelo('ModeloSolicitudesModificacion');
        $this->ModelConfigHorario = $this->modelo('ModeloConfiguracionHorario');
        $this->ModelAuditoria = $this->modelo('ModeloAuditoria');
        $this->ModelVacaciones = $this->modelo('ModeloVacaciones');
    }

    public function index()
    {
        $this->fichar();
    }

    public function fichar()
    {
        $idEmpleado = $_SESSION['idusuario'];
        $debeFichar = $this->ModelConfigHorario->empleadoDebeFichar($idEmpleado);

        if (!$debeFichar) {
            $datos = [
                'mensaje' => 'Su usuario no está configurado para fichar. Contacte con el administrador.',
                'puedeFichar' => false
            ];
            $this->vista('controlHorario/fichar', $datos);
            return;
        }

        $fechaHoy = date('Y-m-d');
        $vacacionHoy = $this->ModelVacaciones->obtenerVacacionActivaPorFecha($idEmpleado, $fechaHoy);

        if ($vacacionHoy) {
            $tipoVacacion = ucfirst($vacacionHoy->tipovacacion);
            $estadoTexto = $vacacionHoy->estado === 'pendiente' ? 'pendiente' : 'aprobada';
            $datos = [
                'mensaje' => 'Tiene ' . $tipoVacacion . ' ' . $estadoTexto . '(s) para hoy (' . date('d/m/Y', strtotime($vacacionHoy->fechainicio)) . ' - ' . date('d/m/Y', strtotime($vacacionHoy->fechafin)) . '). Si necesita fichar, contacte con el administrador.',
                'puedeFichar' => false,
                'vacacionBloqueo' => true
            ];
            $this->vista('controlHorario/fichar', $datos);
            return;
        }

        $jornada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaHoy);
        $ultimoFichaje = $this->ModelControlHorario->obtenerUltimoFichajeEmpleado($idEmpleado);

        $jornadasAbiertas = $this->ModelControlHorario->buscarJornadasAbiertasAnteriores($idEmpleado, $fechaHoy);

        require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');

        foreach ($jornadasAbiertas as $jornadaAbierta) {
            $fichajes = $this->ModelControlHorario->obtenerFichajesJornadaActivos($jornadaAbierta->id);
            if (ControlHorarioHelper::jornadaTieneSalidaPendiente($fichajes)) {
                $this->ModelControlHorario->marcarJornadaIncompleta($jornadaAbierta->id);
            } else {
                $horas = ControlHorarioHelper::calcularHorasEntreFichajes($fichajes);
                $this->ModelControlHorario->cerrarJornada($jornadaAbierta->id, $horas);
            }
        }

        $jornadasAbiertas = $this->ModelControlHorario->buscarJornadasAbiertasAnteriores($idEmpleado, $fechaHoy);

        foreach ($jornadasAbiertas as $jornadaAbierta) {
            $fichajes = $this->ModelControlHorario->obtenerFichajesJornadaActivos($jornadaAbierta->id);
            if (ControlHorarioHelper::jornadaTieneSalidaPendiente($fichajes)) {
                $this->ModelControlHorario->marcarJornadaIncompleta($jornadaAbierta->id);
            } else {
                $horas = ControlHorarioHelper::calcularHorasEntreFichajes($fichajes);
                $this->ModelControlHorario->cerrarJornada($jornadaAbierta->id, $horas);
            }
        }

        $jornadasIncompletas = $this->ModelControlHorario->buscarJornadasIncompletasAnteriores($idEmpleado, $fechaHoy);

        $tipoSiguiente = 'entrada';
        $estadoTexto = 'Fuera';
        $claseEstado = 'bg-red-500';

        if ($ultimoFichaje) {
            $tipoSiguiente = ControlHorarioHelper::obtenerTipoFichajeSiguiente($ultimoFichaje, $fechaHoy);
            if ($tipoSiguiente === 'salida') {
                $estadoTexto = 'Dentro';
                $claseEstado = 'bg-green-500';
            }
        }

        $fichajesHoy = [];
        if ($jornada) {
            $fichajesHoy = $this->ModelControlHorario->obtenerFichajesJornada($jornada->id);
        }

        $datos = [
            'puedeFichar' => true,
            'tipoSiguiente' => $tipoSiguiente,
            'estadoTexto' => $estadoTexto,
            'claseEstado' => $claseEstado,
            'ultimoFichaje' => $ultimoFichaje,
            'jornada' => $jornada,
            'fichajesHoy' => $fichajesHoy,
            'jornadasIncompletas' => $jornadasIncompletas,
            'idEmpleado' => $idEmpleado
        ];

        $this->vista('controlHorario/fichar', $datos);
    }

    public function registrarFichaje()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . RUTA_URL . '/ControlHorario/fichar');
            return;
        }

        header('Content-Type: application/json');

        $idEmpleado = $_SESSION['idusuario'];

        $cooldownKey = 'ultimoFichaje_' . $idEmpleado;
        $cooldownSegundos = 5;
        if (isset($_SESSION[$cooldownKey]) && (time() - $_SESSION[$cooldownKey]) < $cooldownSegundos) {
            $segundosRestantes = $cooldownSegundos - (time() - $_SESSION[$cooldownKey]);
            echo json_encode(['error' => true, 'mensaje' => 'Debe esperar ' . $segundosRestantes . ' segundos antes de volver a fichar.']);
            return;
        }

        $debeFichar = $this->ModelConfigHorario->empleadoDebeFichar($idEmpleado);

        if (!$debeFichar) {
            echo json_encode(['error' => true, 'mensaje' => 'Su usuario no está configurado para fichar.']);
            return;
        }

        if ($this->ModelControlHorario->fichajeRecienteExiste($idEmpleado, 5)) {
            echo json_encode(['error' => true, 'mensaje' => 'Acaba de registrar un fichaje. Espere unos segundos.']);
            return;
        }

        $fechaHoy = date('Y-m-d');
        $vacacionHoy = $this->ModelVacaciones->obtenerVacacionActivaPorFecha($idEmpleado, $fechaHoy);
        if ($vacacionHoy) {
            $tipoVacacion = ucfirst($vacacionHoy->tipovacacion);
            $estadoTexto = $vacacionHoy->estado === 'pendiente' ? 'pendiente' : 'aprobada';
            echo json_encode(['error' => true, 'mensaje' => 'No puede fichar: tiene ' . $tipoVacacion . ' ' . $estadoTexto . '(s) para hoy. Contacte con el administrador.']);
            return;
        }

        $latitud = isset($_POST['latitud']) ? $_POST['latitud'] : null;
        $longitud = isset($_POST['longitud']) ? $_POST['longitud'] : null;

        require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');

        if ($latitud !== null && $longitud !== null && !ControlHorarioHelper::validarGeolocalizacion($latitud, $longitud)) {
            $latitud = null;
            $longitud = null;
        }

        $ultimoFichaje = $this->ModelControlHorario->obtenerUltimoFichajeEmpleado($idEmpleado);
        $tipoSiguiente = ControlHorarioHelper::obtenerTipoFichajeSiguiente($ultimoFichaje, $fechaHoy);

        $jornadasAbiertas = $this->ModelControlHorario->buscarJornadasAbiertasAnteriores($idEmpleado, $fechaHoy);
        foreach ($jornadasAbiertas as $jornadaAbierta) {
            $fichajes = $this->ModelControlHorario->obtenerFichajesJornadaActivos($jornadaAbierta->id);
            if (ControlHorarioHelper::jornadaTieneSalidaPendiente($fichajes)) {
                $this->ModelControlHorario->marcarJornadaIncompleta($jornadaAbierta->id);
            } else {
                $horas = ControlHorarioHelper::calcularHorasEntreFichajes($fichajes);
                $this->ModelControlHorario->cerrarJornada($jornadaAbierta->id, $horas);
            }
        }

        $tipoFichaje = $tipoSiguiente;

        $valido = ControlHorarioHelper::validarTipoFichaje($ultimoFichaje, $tipoFichaje);
        if (!$valido) {
            $fechaUltimo = $ultimoFichaje ? date('Y-m-d', strtotime($ultimoFichaje->fechahora)) : null;
            if ($fechaUltimo && $fechaUltimo < $fechaHoy && $ultimoFichaje->tipofichaje === 'entrada') {
                $tipoFichaje = 'entrada';
            } else {
                echo json_encode(['error' => true, 'mensaje' => 'No puede fichar ' . $tipoFichaje . ' dos veces seguidas.']);
                return;
            }
        }

        $jornada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaHoy);

        if (!$jornada) {
            $jornadaEliminada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFechaTodo($idEmpleado, $fechaHoy);
            if ($jornadaEliminada) {
                $this->ModelControlHorario->restaurarJornada($jornadaEliminada->id, 'abierta');
                $jornada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaHoy);
            } else {
                $idJornada = $this->ModelControlHorario->crearJornada($idEmpleado, $fechaHoy);
                $jornada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFecha($idEmpleado, $fechaHoy);
            }
        }

        $idJornada = $jornada->id;

        $datosFichaje = [
            'idempleado' => $idEmpleado,
            'tipofichaje' => $tipoFichaje,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'ipregistro' => ControlHorarioHelper::obtenerIPCliente(),
            'useragent' => ControlHorarioHelper::obtenerUserAgent(),
            'observaciones' => '',
            'idjornada' => $idJornada
        ];

        $idFichaje = $this->ModelControlHorario->insertarFichaje($datosFichaje);

        if (!$idFichaje) {
            echo json_encode(['error' => true, 'mensaje' => 'Error al registrar el fichaje. Intente de nuevo.']);
            return;
        }

        if ($tipoFichaje === 'salida') {
            $fichajes = $this->ModelControlHorario->obtenerFichajesJornadaActivos($idJornada);
            $horas = ControlHorarioHelper::calcularHorasEntreFichajes($fichajes);
            $this->ModelControlHorario->actualizarHorasJornada($idJornada, $horas);
        }

        $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
            $tipoFichaje === 'entrada' ? 'fichaje_entrada' : 'fichaje_salida',
            'fichajes',
            $idFichaje,
            null,
            $datosFichaje
        );
        $this->ModelAuditoria->registrarAccion($datosAuditoria);

        $nuevoUltimo = $this->ModelControlHorario->obtenerUltimoFichajeEmpleado($idEmpleado);
        $nuevoTipoSiguiente = ControlHorarioHelper::obtenerTipoFichajeSiguiente($nuevoUltimo, $fechaHoy);

        $_SESSION['ultimoFichaje_' . $idEmpleado] = time();

        $mensaje = $tipoFichaje === 'entrada' ? 'Entrada registrada correctamente' : 'Salida registrada correctamente';

        $jornadasIncompletasRestantes = $this->ModelControlHorario->buscarJornadasIncompletasAnteriores($idEmpleado, $fechaHoy);
        if (count($jornadasIncompletasRestantes) > 0) {
            $mensaje .= ' Tiene jornada(s) sin cerrar. Solicite una corrección desde "Mis Solicitudes".';
        }

        echo json_encode([
            'error' => false,
            'mensaje' => $mensaje,
            'tipoFichaje' => $tipoFichaje,
            'fechahora' => $nuevoUltimo->fechahora,
            'tipoSiguiente' => $nuevoTipoSiguiente,
            'estadoTexto' => $nuevoTipoSiguiente === 'entrada' ? 'Fuera' : 'Dentro',
            'claseEstado' => $nuevoTipoSiguiente === 'entrada' ? 'bg-red-500' : 'bg-green-500',
            'horastotales' => ($tipoFichaje === 'salida') ? $horas : null
        ]);
    }

    public function obtenerEstadoActual()
    {
        header('Content-Type: application/json');

        $idEmpleado = $_SESSION['idusuario'];
        $ultimoFichaje = $this->ModelControlHorario->obtenerUltimoFichajeEmpleado($idEmpleado);
        $fechaHoy = date('Y-m-d');

        require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');
        $tipoSiguiente = ControlHorarioHelper::obtenerTipoFichajeSiguiente($ultimoFichaje, $fechaHoy);

        $respuesta = [
            'tipoSiguiente' => $tipoSiguiente,
            'estadoTexto' => $tipoSiguiente === 'entrada' ? 'Fuera' : 'Dentro',
            'claseEstado' => $tipoSiguiente === 'entrada' ? 'bg-red-500' : 'bg-green-500'
        ];

        if ($ultimoFichaje) {
            $respuesta['ultimoFichaje'] = [
                'tipo' => $ultimoFichaje->tipofichaje,
                'fechahora' => $ultimoFichaje->fechahora
            ];
        }

        echo json_encode($respuesta);
    }

    public function miHistorial()
    {
        $idEmpleado = $_SESSION['idusuario'];
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

        $jornadas = $this->ModelControlHorario->obtenerJornadasPorEmpleadoRango(
            $idEmpleado,
            $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-01',
            $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, $anio)
        );

        $datos = [
            'jornadas' => $jornadas,
            'mes' => $mes,
            'anio' => $anio
        ];

        $this->vista('controlHorario/historialEmpleado', $datos);
    }

    public function misSolicitudes()
    {
        $idEmpleado = $_SESSION['idusuario'];
        $solicitudes = $this->ModelSolicitudes->obtenerSolicitudesPorEmpleado($idEmpleado);

        $datos = [
            'solicitudes' => $solicitudes
        ];

        $this->vista('controlHorario/misSolicitudes', $datos);
    }

    public function solicitarModificacion()
    {
        $idEmpleado = $_SESSION['idusuario'];
        $fechaHoy = date('Y-m-d');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idfichajeoriginal = isset($_POST['idfichajeoriginal']) && $_POST['idfichajeoriginal'] !== '' ? (int)$_POST['idfichajeoriginal'] : null;
            $idjornadaoriginal = isset($_POST['idjornadaoriginal']) && $_POST['idjornadaoriginal'] !== '' ? (int)$_POST['idjornadaoriginal'] : null;

            if ($idfichajeoriginal && !$idjornadaoriginal) {
                $fichajeOriginal = $this->ModelControlHorario->obtenerFichajePorId($idfichajeoriginal);
                if ($fichajeOriginal) {
                    $idjornadaoriginal = $fichajeOriginal->idjornada;
                }
            }

            $nuevafechahora = isset($_POST['nuevafechahora']) ? $_POST['nuevafechahora'] : null;
            $tipomodificacion = $_POST['tipomodificacion'];

            if ($tipomodificacion === 'modificar' && $nuevafechahora && $idfichajeoriginal) {
                $fichajeOriginal = $this->ModelControlHorario->obtenerFichajePorId($idfichajeoriginal);
                if ($fichajeOriginal) {
                    $fechaOriginal = date('Y-m-d', strtotime($fichajeOriginal->fechahora));
                    if (strlen($nuevafechahora) <= 5) {
                        $nuevafechahora = $fechaOriginal . ' ' . $nuevafechahora . ':00';
                    }
                }
            }

            $datos = [
                'idfichajeoriginal' => $idfichajeoriginal,
                'idjornadaoriginal' => $idjornadaoriginal ?? 0,
                'idempleado' => $idEmpleado,
                'tipomodificacion' => $tipomodificacion,
                'nuevotipofichaje' => isset($_POST['nuevotipofichaje']) ? $_POST['nuevotipofichaje'] : null,
                'nuevafechahora' => $nuevafechahora,
                'nuevaobservaciones' => isset($_POST['nuevaobservaciones']) ? $_POST['nuevaobservaciones'] : null,
                'motivo' => $_POST['motivo']
            ];

            $idSolicitud = $this->ModelSolicitudes->crearSolicitud($datos);

            if ($idSolicitud) {
                require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');
                $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                    'solicitud_modificacion',
                    'solicitudesmodificacion',
                    $idSolicitud,
                    null,
                    $datos
                );
                $this->ModelAuditoria->registrarAccion($datosAuditoria);

                header('Location: ' . RUTA_URL . '/ControlHorario/misSolicitudes');
                return;
            }
        }

        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');
        $fechaInicio = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-01';
        $fechaFin = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        $jornadas = $this->ModelControlHorario->obtenerJornadasPorEmpleadoRango(
            $idEmpleado,
            $fechaInicio,
            $fechaFin
        );

        $fichajesRecientes = [];
        foreach ($jornadas as $jornada) {
            $fichajes = $this->ModelControlHorario->obtenerFichajesJornada($jornada->id);
            foreach ($fichajes as $f) {
                if (!isset($f->corregido) || !$f->corregido) {
                    $fichajesRecientes[] = $f;
                }
            }
        }

        $datos = [
            'fichajesRecientes' => $fichajesRecientes,
            'jornadas' => $jornadas,
            'mes' => $mes,
            'anio' => $anio
        ];

        $this->vista('controlHorario/solicitarModificacion', $datos);
    }

    public function detalleJornada()
    {
        $idJornada = null;

        if (isset($_POST['id']) && (int)$_POST['id'] > 0) {
            $idJornada = (int)$_POST['id'];
        } elseif (func_get_args() && (int)func_get_arg(0) > 0) {
            $idJornada = (int)func_get_arg(0);
        }

        if (!$idJornada) {
            redireccionar('/ControlHorario/miHistorial');
            return;
        }

        $jornada = $this->ModelControlHorario->obtenerJornadaPorId($idJornada);

        if (!$jornada) {
            redireccionar('/ControlHorario/miHistorial');
            return;
        }

        $idEmpleado = $_SESSION['idusuario'];
        $esAdmin = ($_SESSION['nombrerol'] === 'admin');

        if ($jornada->idempleado != $idEmpleado && !$esAdmin) {
            redireccionar('/ControlHorario/miHistorial');
            return;
        }

        $fichajes = $this->ModelControlHorario->obtenerFichajesJornada($idJornada);
        $fichajesActivos = $this->ModelControlHorario->obtenerFichajesJornadaActivos($idJornada);

        require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');
        $horasTotales = ControlHorarioHelper::calcularHorasEntreFichajes($fichajesActivos);

        $datos = [
            'jornada' => $jornada,
            'fichajes' => $fichajes,
            'horasTotales' => $horasTotales
        ];

        $this->vista('controlHorario/detalleJornada', $datos);
    }

    public function miCalendario()
    {
        $idEmpleado = $_SESSION['idusuario'];
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

        $fechaInicio = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-01';
        $fechaFin = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        $jornadas = $this->ModelControlHorario->obtenerJornadasPorEmpleadoRango($idEmpleado, $fechaInicio, $fechaFin);

        $datos = [
            'jornadas' => $jornadas,
            'mes' => $mes,
            'anio' => $anio
        ];

        $this->vista('controlHorario/miCalendario', $datos);
    }
}