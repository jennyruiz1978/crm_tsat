<?php

class AdministracionHorario extends Controlador
{
    private $ModelControlHorario;
    private $ModelSolicitudes;
    private $ModelConfigHorario;
    private $ModelVacaciones;
    private $ModelReportes;
    private $ModelAuditoria;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['idusuario'])) {
            redireccionar('/Login');
            return;
        }
        $this->controlPermisos();
        $this->ModelControlHorario = $this->modelo('ModeloControlHorario');
        $this->ModelSolicitudes = $this->modelo('ModeloSolicitudesModificacion');
        $this->ModelConfigHorario = $this->modelo('ModeloConfiguracionHorario');
        $this->ModelVacaciones = $this->modelo('ModeloVacaciones');
        $this->ModelReportes = $this->modelo('ModeloReportesHorario');
        $this->ModelAuditoria = $this->modelo('ModeloAuditoria');
    }

    public function index()
    {
        $this->listadoGlobal();
    }

    public function listadoGlobal()
    {
        $fechaInicio = isset($_GET['fechainicio']) ? $_GET['fechainicio'] : date('Y-m-01');
        $fechaFin = isset($_GET['fechafin']) ? $_GET['fechafin'] : date('Y-m-t');
        $idEmpleado = isset($_GET['idempleado']) && $_GET['idempleado'] !== '' ? (int)$_GET['idempleado'] : null;

        $jornadas = $this->ModelControlHorario->obtenerTodasJornadasRangoFiltrado($fechaInicio, $fechaFin, $idEmpleado);
        $empleados = $this->ModelControlHorario->obtenerEmpleadosQueFichan();
        $solicitudesPendientes = $this->ModelSolicitudes->contarSolicitudesPendientes();
        $vacacionesPendientes = $this->ModelVacaciones->contarVacacionesPendientes();

        $datos = [
            'jornadas' => $jornadas,
            'empleados' => $empleados,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'idEmpleado' => $idEmpleado,
            'solicitudesPendientes' => $solicitudesPendientes,
            'vacacionesPendientes' => $vacacionesPendientes
        ];

        $this->vista('administracionHorario/listadoGlobal', $datos);
    }

    public function detalleEmpleado($idEmpleado = null)
    {
        if ($idEmpleado === null) {
            header('Location: ' . RUTA_URL . '/AdministracionHorario/listadoGlobal');
            return;
        }

        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

        $jornadas = $this->ModelControlHorario->obtenerJornadasPorEmpleadoRango(
            $idEmpleado,
            $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-01',
            $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, $anio)
        );

        require_once(RUTA_APP . '/models/ModeloLogin.php');
        $modeloLogin = $this->modelo('ModeloLogin');
        $empleado = $modeloLogin->datosUsuarioPorId($idEmpleado);

        $fichajesPorJornada = [];
        foreach ($jornadas as $jornada) {
            $fichajesPorJornada[$jornada->id] = $this->ModelControlHorario->obtenerFichajesJornada($jornada->id);
        }

        $datos = [
            'empleado' => $empleado,
            'jornadas' => $jornadas,
            'fichajesPorJornada' => $fichajesPorJornada,
            'mes' => $mes,
            'anio' => $anio,
            'idEmpleado' => $idEmpleado
        ];

        $this->vista('administracionHorario/detalleEmpleado', $datos);
    }

    public function solicitudesModificacion()
    {
        $pendientes = $this->ModelSolicitudes->obtenerSolicitudesPendientes();
        $todas = $this->ModelSolicitudes->obtenerTodasSolicitudes();

        $datos = [
            'pendientes' => $pendientes,
            'todas' => $todas
        ];

        $this->vista('administracionHorario/solicitudesModificacion', $datos);
    }

    public function resolverSolicitud($idSolicitud = null)
    {
        if ($idSolicitud === null) {
            header('Location: ' . RUTA_URL . '/AdministracionHorario/solicitudesModificacion');
            return;
        }

        $solicitud = $this->ModelSolicitudes->obtenerSolicitudPorId($idSolicitud);

        if (!$solicitud) {
            header('Location: ' . RUTA_URL . '/AdministracionHorario/solicitudesModificacion');
            return;
        }

        if ($solicitud->estado !== 'pendiente') {
            header('Location: ' . RUTA_URL . '/AdministracionHorario/solicitudesModificacion');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            $respuesta = $_POST['respuestaadmin'] ?? '';
            $idAdmin = $_SESSION['idusuario'];

            if (!in_array($accion, ['aprobar', 'rechazar'])) {
                header('Location: ' . RUTA_URL . '/AdministracionHorario/solicitudesModificacion');
                return;
            }

            require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');

            if ($accion === 'aprobar') {
                $resultado = $this->ModelSolicitudes->aprobarSolicitud($idSolicitud, $idAdmin, $respuesta);

                if ($resultado) {
                    $this->aplicarSolicitudAprobada($solicitud, $idSolicitud);

                    $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                        'aprobacion_mod',
                        'solicitudesmodificacion',
                        $idSolicitud,
                        (array)$solicitud,
                        ['estado' => 'aprobada', 'respuesta' => $respuesta]
                    );
                    $this->ModelAuditoria->registrarAccion($datosAuditoria);
                }
            } elseif ($accion === 'rechazar') {
                $resultado = $this->ModelSolicitudes->rechazarSolicitud($idSolicitud, $idAdmin, $respuesta);

                if ($resultado) {
                    $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                        'rechazo_mod',
                        'solicitudesmodificacion',
                        $idSolicitud,
                        (array)$solicitud,
                        ['estado' => 'rechazada', 'respuesta' => $respuesta]
                    );
                    $this->ModelAuditoria->registrarAccion($datosAuditoria);
                }
            }

            header('Location: ' . RUTA_URL . '/AdministracionHorario/solicitudesModificacion');
            return;
        }

        $fichajesJornada = [];
        if (!empty($solicitud->idjornadaoriginal)) {
            $fichajesJornada = $this->ModelControlHorario->obtenerFichajesJornada($solicitud->idjornadaoriginal);
        }

        $datos = [
            'solicitud' => $solicitud,
            'fichajesJornada' => $fichajesJornada
        ];

        $this->vista('administracionHorario/resolverSolicitud', $datos);
    }

    private function aplicarSolicitudAprobada($solicitud, $idSolicitud)
    {
        require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');

        if ($solicitud->tipomodificacion === 'insertar') {
            $fechaFichaje = $solicitud->nuevafechahora ? date('Y-m-d', strtotime($solicitud->nuevafechahora)) : date('Y-m-d');
            if (!$solicitud->nuevafechahora || strtotime($solicitud->nuevafechahora) === false) {
                $fechaFichaje = date('Y-m-d');
            }

            $jornada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFecha(
                $solicitud->idempleado,
                $fechaFichaje
            );

            if (!$jornada) {
                $jornadaEliminada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFechaTodo(
                    $solicitud->idempleado,
                    $fechaFichaje
                );
                if ($jornadaEliminada) {
                    $this->ModelControlHorario->restaurarJornada($jornadaEliminada->id, 'abierta');
                    $jornada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFecha(
                        $solicitud->idempleado,
                        $fechaFichaje
                    );
                } else {
                    $this->ModelControlHorario->crearJornada(
                        $solicitud->idempleado,
                        $fechaFichaje
                    );
                    $jornada = $this->ModelControlHorario->obtenerJornadaPorEmpleadoYFecha(
                        $solicitud->idempleado,
                        $fechaFichaje
                    );
                }
            }
            $idJornada = $jornada->id;

            $datosFichajeMod = [
                'idsolicitud' => $idSolicitud,
                'idfichajeoriginal' => null,
                'idempleado' => $solicitud->idempleado,
                'tipofichaje' => $solicitud->nuevotipofichaje,
                'fechahora' => $solicitud->nuevafechahora,
                'observaciones' => 'Fichaje insertado por aprobación de solicitud #' . $idSolicitud,
                'idjornada' => $idJornada
            ];
            $this->ModelSolicitudes->insertarFichajeModificado($datosFichajeMod);

            $datosFichaje = [
                'idempleado' => $solicitud->idempleado,
                'tipofichaje' => $solicitud->nuevotipofichaje,
                'fechahora' => $solicitud->nuevafechahora,
                'latitud' => null,
                'longitud' => null,
                'ipregistro' => ControlHorarioHelper::obtenerIPCliente(),
                'useragent' => ControlHorarioHelper::obtenerUserAgent(),
                'observaciones' => 'Fichaje insertado por aprobación de solicitud #' . $idSolicitud,
                'idjornada' => $idJornada
            ];

            $this->ModelControlHorario->insertarFichajeConFecha($datosFichaje);

            $this->recalcularJornada($idJornada);

        } elseif ($solicitud->tipomodificacion === 'modificar') {
            $idJornada = $solicitud->idjornadaoriginal;
            if (!$idJornada && $solicitud->idfichajeoriginal) {
                $fichajeOriginal = $this->ModelControlHorario->obtenerFichajePorId($solicitud->idfichajeoriginal);
                if ($fichajeOriginal) {
                    $idJornada = $fichajeOriginal->idjornada;
                }
            }

            $datosFichajeMod = [
                'idsolicitud' => $idSolicitud,
                'idfichajeoriginal' => $solicitud->idfichajeoriginal,
                'idempleado' => $solicitud->idempleado,
                'tipofichaje' => $solicitud->nuevotipofichaje ?? $solicitud->tipofichajeoriginal ?? 'entrada',
                'fechahora' => $solicitud->nuevafechahora,
                'observaciones' => 'Modificación por solicitud #' . $idSolicitud . '. Original: ' . $solicitud->fechahoraoriginal,
                'idjornada' => $idJornada
            ];
            $this->ModelSolicitudes->insertarFichajeModificado($datosFichajeMod);

            $this->ModelControlHorario->marcarFichajeCorregido($solicitud->idfichajeoriginal);

            $datosNuevoFichaje = [
                'idempleado' => $solicitud->idempleado,
                'tipofichaje' => $solicitud->nuevotipofichaje ?? $solicitud->tipofichajeoriginal,
                'fechahora' => $solicitud->nuevafechahora,
                'latitud' => null,
                'longitud' => null,
                'ipregistro' => ControlHorarioHelper::obtenerIPCliente(),
                'useragent' => ControlHorarioHelper::obtenerUserAgent(),
                'observaciones' => 'Fichaje modificado por solicitud #' . $idSolicitud,
                'idjornada' => $idJornada
            ];
            $this->ModelControlHorario->insertarFichajeConFecha($datosNuevoFichaje);

            if ($idJornada) {
                $this->recalcularJornada($idJornada);
            }

        } elseif ($solicitud->tipomodificacion === 'omitir') {
            $idJornada = $solicitud->idjornadaoriginal;
            if (!$idJornada && $solicitud->idfichajeoriginal) {
                $fichajeOriginal = $this->ModelControlHorario->obtenerFichajePorId($solicitud->idfichajeoriginal);
                if ($fichajeOriginal) {
                    $idJornada = $fichajeOriginal->idjornada;
                }
            }

            $datosFichajeMod = [
                'idsolicitud' => $idSolicitud,
                'idfichajeoriginal' => $solicitud->idfichajeoriginal,
                'idempleado' => $solicitud->idempleado,
                'tipofichaje' => '',
                'fechahora' => $solicitud->fechahoraoriginal,
                'observaciones' => 'Fichaje omitido por solicitud #' . $idSolicitud,
                'idjornada' => $idJornada
            ];
            $this->ModelSolicitudes->insertarFichajeModificado($datosFichajeMod);

            $this->ModelControlHorario->eliminarFichaje($solicitud->idfichajeoriginal);

            if ($idJornada) {
                $this->recalcularJornada($idJornada);
            }
        }
    }

    private function recalcularJornada($idJornada)
    {
        $fichajes = $this->ModelControlHorario->obtenerFichajesJornada($idJornada);
        $horasTotales = ControlHorarioHelper::calcularHorasEntreFichajes($fichajes);
        $this->ModelControlHorario->actualizarHorasJornada($idJornada, $horasTotales);

        $jornada = $this->ModelControlHorario->obtenerJornadaPorId($idJornada);
        $fechaHoy = date('Y-m-d');
        if ($jornada && $jornada->fecha < $fechaHoy) {
            if (!empty($fichajes)) {
                $ultimoFichaje = end($fichajes);
                if ($ultimoFichaje->tipofichaje === 'salida') {
                    $this->ModelControlHorario->cerrarJornada($idJornada, $horasTotales);
                } else {
                    $this->ModelControlHorario->marcarJornadaIncompleta($idJornada);
                }
            } else {
                $this->ModelControlHorario->marcarJornadaIncompleta($idJornada);
            }
        }
    }

    public function configuracionHorario()
    {
        $empleadosConfig = $this->ModelConfigHorario->obtenerTodosConfig();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idEmpleado = (int)$_POST['idempleado'];
            $debeFichar = isset($_POST['debeFichar']) ? 1 : 0;
            $jornadaHoras = $_POST['jornadatipohoras'] ?? 8.00;
            $horarioEntrada = $_POST['horarioentrada'] ?? null;
            $horarioSalida = $_POST['horariosalida'] ?? null;
            $tolerancia = $_POST['toleranciaminutos'] ?? 5;

            $configExistente = $this->ModelConfigHorario->obtenerConfigPorEmpleado($idEmpleado);

            $datos = [
                'idempleado' => $idEmpleado,
                'debeFichar' => $debeFichar,
                'jornadatipohoras' => $jornadaHoras,
                'horarioentrada' => $horarioEntrada,
                'horariosalida' => $horarioSalida,
                'toleranciaminutos' => $tolerancia
            ];

            require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');

            if ($configExistente) {
                $resultado = $this->ModelConfigHorario->actualizarConfig($datos);
            } else {
                $resultado = $this->ModelConfigHorario->crearConfig($datos);
            }

            if ($resultado) {
                $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
                    'configuracion_horario',
                    'configuracionhorario',
                    $idEmpleado,
                    $configExistente ? (array)$configExistente : null,
                    $datos
                );
                $this->ModelAuditoria->registrarAccion($datosAuditoria);
            }

            $empleadosConfig = $this->ModelConfigHorario->obtenerTodosConfig();
        }

        $datos = [
            'empleadosConfig' => $empleadosConfig
        ];

        $this->vista('administracionHorario/configuracionHorario', $datos);
    }

    public function calendarioJornadas()
    {
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');
        $idEmpleado = isset($_GET['idempleado']) && $_GET['idempleado'] !== '' ? (int)$_GET['idempleado'] : null;

        $empleados = $this->ModelControlHorario->obtenerEmpleadosQueFichan();

        $fechaInicio = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-01';
        $fechaFin = $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        $jornadas = [];
        if ($idEmpleado) {
            $jornadas = $this->ModelControlHorario->obtenerJornadasPorEmpleadoRango($idEmpleado, $fechaInicio, $fechaFin);
        }

        $datos = [
            'jornadas' => $jornadas,
            'empleados' => $empleados,
            'mes' => $mes,
            'anio' => $anio,
            'idEmpleado' => $idEmpleado
        ];

        $this->vista('administracionHorario/calendarioJornadas', $datos);
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

    public function migrarConfigHorario()
    {
        $modeloUsuarios = $this->modelo('Usuario');

        $this->db = new Base;
        $this->db->query("SELECT id, nombre, apellidos, rol FROM usuarios WHERE rol != 1 AND activo = 1 ORDER BY id");
        $usuarios = $this->db->registros();

        $creados = [];
        $omitidos = [];

        foreach ($usuarios as $usuario) {
            $existente = $this->ModelConfigHorario->obtenerConfigPorEmpleado($usuario->id);
            if ($existente) {
                $omitidos[] = $usuario;
            } else {
                $datosConfig = [
                    'idempleado' => $usuario->id,
                    'debeFichar' => 0,
                    'jornadatipohoras' => 8.00,
                    'horarioentrada' => '09:00:00',
                    'horariosalida' => '18:00:00',
                    'toleranciaminutos' => 5
                ];
                $this->ModelConfigHorario->crearConfig($datosConfig);
                $creados[] = $usuario;
            }
        }

        $datos = [
            'creados' => $creados,
            'omitidos' => $omitidos
        ];
        $this->vista('administracionHorario/migrarResultado', $datos);
    }
}