<?php
$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$mesNombre = $meses[$datos['mes'] - 1];
?>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm" orientation="landscape">
    <div style="text-align: center; margin-bottom: 15px;">
        <h1 style="font-size: 16px; margin: 0;">Reporte Global de Control Horario</h1>
        <h2 style="font-size: 13px; margin: 5px 0;"><?php echo $mesNombre . ' ' . $datos['anio']; ?> - <?php echo NOMBRE_SITIO; ?></h2>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td style="border: 1px solid #ddd; padding: 6px; background: #f0f0f0; font-weight: bold;">Empleados</td>
            <td style="border: 1px solid #ddd; padding: 6px; text-align: center;"><?php echo $datos['totalEmpleados'] ?? count($datos['resumen']); ?></td>
            <td style="border: 1px solid #ddd; padding: 6px; background: #f0f0f0; font-weight: bold;">Horas totales</td>
            <td style="border: 1px solid #ddd; padding: 6px; text-align: center;"><?php echo number_format($datos['horasTotalesEmpresa'], 2); ?>h</td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #4a1a6b; color: white;">
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: left;">Empleado</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: center;">Días trabajados</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: center;">Horas totales</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: center;">Jornadas completas</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: center;">Jornadas incompletas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos['resumen'] as $i => $emp): ?>
                <tr style="background: <?php echo $i % 2 === 0 ? '#fff' : '#f9f9f9'; ?>;">
                    <td style="border: 1px solid #ddd; padding: 6px; font-size: 9px;"><?php echo $emp->nombreempleado; ?></td>
                    <td style="border: 1px solid #ddd; padding: 6px; font-size: 9px; text-align: center;"><?php echo $emp->diasstrabajados; ?></td>
                    <td style="border: 1px solid #ddd; padding: 6px; font-size: 9px; text-align: center;"><?php echo number_format($emp->totalhoras, 2); ?>h</td>
                    <td style="border: 1px solid #ddd; padding: 6px; font-size: 9px; text-align: center;"><?php echo $emp->jornadascompletas; ?></td>
                    <td style="border: 1px solid #ddd; padding: 6px; font-size: 9px; text-align: center;"><?php echo $emp->jornadasincompletas; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 8px; color: #999; text-align: center;">
        Generado el <?php echo date('d/m/Y H:i'); ?> | Documento conforme al Real Decreto-ley 8/2019
    </div>
</page>