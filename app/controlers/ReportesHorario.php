<?php

class ReportesHorario extends Controlador
{
    private $ModelReportes;
    private $ModelControlHorario;
    private $ModelConfigHorario;
    private $ModelAuditoria;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['idusuario'])) {
            redireccionar('/Login');
            return;
        }
        $this->controlPermisos();
        $this->ModelReportes = $this->modelo('ModeloReportesHorario');
        $this->ModelControlHorario = $this->modelo('ModeloControlHorario');
        $this->ModelConfigHorario = $this->modelo('ModeloConfiguracionHorario');
        $this->ModelAuditoria = $this->modelo('ModeloAuditoria');
    }

    public function index()
    {
        $this->generarReportes();
    }

    public function generarReportes()
    {
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');
        $idEmpleado = isset($_GET['idempleado']) && $_GET['idempleado'] !== '' ? (int)$_GET['idempleado'] : null;

        $empleados = $this->ModelReportes->obtenerTodosEmpleadosNoCliente();

        $datos = [
            'mes' => $mes,
            'anio' => $anio,
            'idEmpleado' => $idEmpleado,
            'empleados' => $empleados
        ];

        $this->vista('reportesHorario/selectorReportes', $datos);
    }

    public function exportarCSV()
    {
        $fechaInicio = isset($_GET['fechainicio']) ? $_GET['fechainicio'] : date('Y-m-01');
        $fechaFin = isset($_GET['fechafin']) ? $_GET['fechafin'] : date('Y-m-t');
        $idEmpleado = isset($_GET['idempleado']) && $_GET['idempleado'] !== '' ? (int)$_GET['idempleado'] : null;

        $fichajes = $this->ModelReportes->obtenerFichajesParaCSV($fechaInicio, $fechaFin, $idEmpleado);

        require_once(RUTA_APP . '/helpers/ControlHorarioHelper.php');

        $datosAuditoria = ControlHorarioHelper::generarDatosAuditoria(
            'exportar_csv_fichajes',
            'reportes',
            null,
            null,
            ['fechainicio' => $fechaInicio, 'fechafin' => $fechaFin, 'idempleado' => $idEmpleado]
        );
        $this->ModelAuditoria->registrarAccion($datosAuditoria);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=fichajes_' . $fechaInicio . '_' . $fechaFin . '.csv');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['Fecha/Hora', 'Empleado', 'Tipo', 'Observaciones'], ';');

        foreach ($fichajes as $f) {
            fputcsv($output, [
                $f->fechahora,
                $f->nombreempleado ?? 'Desconocido',
                ucfirst($f->tipofichaje),
                $f->observaciones ?? ''
            ], ';');
        }

        fclose($output);
        exit;
    }

    public function reporteMensualEmpleado($idEmpleado = null)
    {
        if ($idEmpleado === null) {
            header('Location: ' . RUTA_URL . '/ReportesHorario/generarReportes');
            return;
        }

        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

        require_once(RUTA_APP . '/models/ModeloLogin.php');
        $modeloLogin = $this->modelo('ModeloLogin');
        $empleado = $modeloLogin->datosUsuarioPorId($idEmpleado);

        $resumen = $this->ModelReportes->obtenerResumenMensualEmpleado($idEmpleado, $mes, $anio);
        $fichajes = $this->ModelReportes->obtenerDetalleFichajesMensual($idEmpleado, $mes, $anio);

        $horasTotales = 0;
        $diasTrabajados = count($resumen);
        $jornadasCompletas = 0;
        $jornadasIncompletas = 0;

        foreach ($resumen as $jornada) {
            $horasTotales += $jornada->horastotales;
            if ($jornada->completada || in_array($jornada->estadojornada, ['vacaciones', 'baja', 'ausencia'])) {
                $jornadasCompletas++;
            } else {
                $jornadasIncompletas++;
            }
        }

        $datos = [
            'empleado' => $empleado,
            'resumen' => $resumen,
            'fichajes' => $fichajes,
            'mes' => $mes,
            'anio' => $anio,
            'horasTotales' => $horasTotales,
            'diasTrabajados' => $diasTrabajados,
            'jornadasCompletas' => $jornadasCompletas,
            'jornadasIncompletas' => $jornadasIncompletas,
            'idEmpleado' => $idEmpleado
        ];

        $this->vista('reportesHorario/reporteMensualEmpleado', $datos);
    }

    public function reportePDFMensualEmpleado($idEmpleado = null)
    {
        if ($idEmpleado === null) {
            header('Location: ' . RUTA_URL . '/ReportesHorario/generarReportes');
            return;
        }

        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

        require_once(RUTA_APP . '/models/ModeloLogin.php');
        $modeloLogin = $this->modelo('ModeloLogin');
        $empleado = $modeloLogin->datosUsuarioPorId($idEmpleado);

        $resumen = $this->ModelReportes->obtenerResumenMensualEmpleado($idEmpleado, $mes, $anio);
        $fichajes = $this->ModelReportes->obtenerDetalleFichajesMensual($idEmpleado, $mes, $anio);

        $horasTotales = 0;
        $diasTrabajados = count($resumen);
        $jornadasCompletas = 0;
        $jornadasIncompletas = 0;

        foreach ($resumen as $jornada) {
            $horasTotales += $jornada->horastotales;
            if ($jornada->completada || in_array($jornada->estadojornada, ['vacaciones', 'baja', 'ausencia'])) {
                $jornadasCompletas++;
            } else {
                $jornadasIncompletas++;
            }
        }

        $datos = [
            'empleado' => $empleado,
            'resumen' => $resumen,
            'fichajes' => $fichajes,
            'mes' => $mes,
            'anio' => $anio,
            'horasTotales' => $horasTotales,
            'diasTrabajados' => $diasTrabajados,
            'jornadasCompletas' => $jornadasCompletas,
            'jornadasIncompletas' => $jornadasIncompletas,
            'idEmpleado' => $idEmpleado
        ];

        $datosPdf = $datos;

        generarPdf::documentoPDFExportar(
            'P', 'A4', 'es', true, 'UTF-8',
            array(5, 5, 5, 5),
            true,
            'documentos',
            'reporteHorarioEmpleado.php',
            $datosPdf
        );
    }

    public function reporteGlobalEmpresa()
    {
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

        $resumen = $this->ModelReportes->obtenerResumenMensualGlobal($mes, $anio);

        $horasTotalesEmpresa = 0;
        $totalJornadasCompletas = 0;
        $totalJornadasIncompletas = 0;
        $totalEmpleados = count($resumen);

        foreach ($resumen as $emp) {
            $horasTotalesEmpresa += $emp->totalhoras;
            $totalJornadasCompletas += $emp->jornadascompletas;
            $totalJornadasIncompletas += $emp->jornadasincompletas;
        }

        $datos = [
            'resumen' => $resumen,
            'mes' => $mes,
            'anio' => $anio,
            'horasTotalesEmpresa' => $horasTotalesEmpresa,
            'totalJornadasCompletas' => $totalJornadasCompletas,
            'totalJornadasIncompletas' => $totalJornadasIncompletas,
            'totalEmpleados' => $totalEmpleados
        ];

        $this->vista('reportesHorario/reporteGlobalEmpresa', $datos);
    }

    public function reportePDFGlobalEmpresa()
    {
        $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
        $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

        $resumen = $this->ModelReportes->obtenerResumenMensualGlobal($mes, $anio);

        $horasTotalesEmpresa = 0;
        $totalJornadasCompletas = 0;
        $totalJornadasIncompletas = 0;
        $totalEmpleados = count($resumen);

        foreach ($resumen as $emp) {
            $horasTotalesEmpresa += $emp->totalhoras;
            $totalJornadasCompletas += $emp->jornadascompletas;
            $totalJornadasIncompletas += $emp->jornadasincompletas;
        }

        $datos = [
            'resumen' => $resumen,
            'mes' => $mes,
            'anio' => $anio,
            'horasTotalesEmpresa' => $horasTotalesEmpresa,
            'totalJornadasCompletas' => $totalJornadasCompletas,
            'totalJornadasIncompletas' => $totalJornadasIncompletas,
            'totalEmpleados' => $totalEmpleados
        ];

        generarPdf::documentoPDFExportar(
            'L', 'A4', 'es', true, 'UTF-8',
            array(5, 5, 5, 5),
            true,
            'documentos',
            'reporteHorarioGlobal.php',
            $datos
        );
    }
}