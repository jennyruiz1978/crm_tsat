<?php
$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$mesNombre = $meses[$datos['mes'] - 1];
?>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
    <div style="text-align: center; margin-bottom: 20px;">
        <h1 style="font-size: 18px; margin: 0;">Reporte de Control Horario</h1>
        <h2 style="font-size: 14px; margin: 5px 0;"><?php echo $datos['empleado']->nombre . ' ' . $datos['empleado']->apellidos; ?> - <?php echo $mesNombre . ' ' . $datos['anio']; ?></h2>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px; background: #f0f0f0; font-weight: bold; width: 25%;">Días trabajados</td>
            <td style="border: 1px solid #ddd; padding: 8px; width: 25%;"><?php echo $datos['diasTrabajados']; ?></td>
            <td style="border: 1px solid #ddd; padding: 8px; background: #f0f0f0; font-weight: bold; width: 25%;">Horas totales</td>
            <td style="border: 1px solid #ddd; padding: 8px; width: 25%;"><?php echo number_format($datos['horasTotales'], 2); ?>h</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px; background: #f0f0f0; font-weight: bold;">Jornadas completas</td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $datos['jornadasCompletas']; ?></td>
            <td style="border: 1px solid #ddd; padding: 8px; background: #f0f0f0; font-weight: bold;">Jornadas incompletas</td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo $datos['jornadasIncompletas']; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px; background: #e8e0f0; font-weight: bold;">Horas esperadas</td>
            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo number_format($datos['horasEsperadas'], 2); ?>h</td>
            <td style="border: 1px solid #ddd; padding: 8px; background: <?php echo $datos['diferencia'] >= 0 ? '#d4edda' : '#f8d7da'; ?>; font-weight: bold;">Diferencia</td>
            <td style="border: 1px solid #ddd; padding: 8px; <?php echo $datos['diferencia'] >= 0 ? 'color: #155724;' : 'color: #721c24;'; ?>"><?php echo ($datos['diferencia'] >= 0 ? '+' : '') . number_format($datos['diferencia'], 2); ?>h</td>
        </tr>
    </table>

    <?php
    $estadosPdf = [
        'abierta' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'label' => 'Abierta'],
        'cerrada' => ['bg' => '#dcfce7', 'color' => '#166534', 'label' => 'Cerrada'],
        'incompleta' => ['bg' => '#fef9c3', 'color' => '#854d0e', 'label' => 'Incompleta'],
        'vacaciones' => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'label' => 'Vacaciones'],
        'baja' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'label' => 'Baja'],
        'ausencia' => ['bg' => '#fce7f3', 'color' => '#9d174d', 'label' => 'Ausencia'],
        'pendiente_vacaciones' => ['bg' => '#faf5ff', 'color' => '#7c3aed', 'label' => 'Vac. (pend.)', 'border' => '#c4b5fd'],
        'pendiente_baja' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Baja (pend.)', 'border' => '#fca5a5'],
        'pendiente_ausencia' => ['bg' => '#fdf2f8', 'color' => '#db2777', 'label' => 'Aus. (pend.)', 'border' => '#f9a8d4'],
    ];
    ?>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #4a1a6b; color: white;">
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: left;">Fecha</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: center;">Estado</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: center;">Horas</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: center;">Horas esp.</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: center;">Diferencia</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: center;">Completada</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos['resumen'] as $jornada): ?>
                <?php
                $estado = $estadosPdf[$jornada->estadojornada] ?? ['bg' => '#f3f4f6', 'color' => '#374151', 'label' => ucfirst($jornada->estadojornada)];
                $diferenciaJornada = $jornada->horastotales - $jornada->jornadatipohoras;
                $completada = $jornada->completada || in_array($jornada->estadojornada, ['vacaciones', 'baja', 'ausencia']);
                $borderStyle = isset($estado['border']) ? 'border: 2px solid ' . $estado['border'] . ';' : 'border: none;';
                ?>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px 6px; font-size: 12px;"><?php echo date('d/m/Y', strtotime($jornada->fecha)); ?></td>
                    <td style="border: 1px solid #ddd; padding: 5px 6px; font-size: 12px; text-align: center;">
                        <span style="display: inline-block; padding: 2px 6px; border-radius: 3px; background: <?php echo $estado['bg']; ?>; color: <?php echo $estado['color']; ?>; font-size: 10px; <?php echo $borderStyle; ?>"><?php echo $estado['label']; ?></span>
                    </td>
                    <td style="border: 1px solid #ddd; padding: 5px 6px; font-size: 12px; text-align: center;"><?php echo number_format($jornada->horastotales, 2); ?>h</td>
                    <td style="border: 1px solid #ddd; padding: 5px 6px; font-size: 12px; text-align: center;"><?php echo number_format($jornada->jornadatipohoras, 2); ?>h</td>
                    <td style="border: 1px solid #ddd; padding: 5px 6px; font-size: 12px; text-align: center; <?php echo $diferenciaJornada >= 0 ? 'color: #155724;' : 'color: #721c24;'; ?>"><?php echo ($diferenciaJornada >= 0 ? '+' : '') . number_format($diferenciaJornada, 2); ?>h</td>
                    <td style="border: 1px solid #ddd; padding: 5px 6px; font-size: 12px; text-align: center;"><?php echo $completada ? '&#10003;' : '&#10007;'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 8px; color: #999; text-align: center;">
        Generado el <?php echo date('d/m/Y H:i'); ?> | Documento conforme al Real Decreto-ley 8/2019
    </div>
</page>