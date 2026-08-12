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
    </table>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #4a1a6b; color: white;">
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: left;">Fecha</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: left;">Hora</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: center;">Tipo</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: center;">Corregido</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 10px; text-align: left;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $fechaAnterior = ''; ?>
            <?php foreach ($datos['fichajes'] as $f): ?>
                <?php $fechaActual = date('d/m/Y', strtotime($f->fechahora)); ?>
                <?php if ($fechaActual !== $fechaAnterior): ?>
                    <?php if ($fechaAnterior !== ''): ?>
                        <tr><td colspan="5" style="padding: 4px 0;"></td></tr>
                    <?php endif; ?>
                    <tr style="background: #e8e0f0;">
                        <td colspan="5" style="border: 1px solid #ddd; padding: 4px 6px; font-weight: bold; font-size: 10px;"><?php echo $fechaActual; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 9px;"><?php echo $fechaActual; ?></td>
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 9px;"><?php echo date('H:i', strtotime($f->fechahora)); ?></td>
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 9px; text-align: center;"><?php echo ucfirst($f->tipofichaje); ?></td>
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 9px; text-align: center;"><?php echo $f->corregido ? 'Sí' : 'No'; ?></td>
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 9px;"><?php echo $f->observaciones ?? '-'; ?></td>
                </tr>
                <?php $fechaAnterior = $fechaActual; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 8px; color: #999; text-align: center;">
        Generado el <?php echo date('d/m/Y H:i'); ?> | Documento conforme al Real Decreto-ley 8/2019
    </div>
</page>