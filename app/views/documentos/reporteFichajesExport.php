<?php
$fechaInicioFmt = date('d/m/Y', strtotime($datos['fechaInicio']));
$fechaFinFmt = date('d/m/Y', strtotime($datos['fechaFin']));
?>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
    <div style="text-align: center; margin-bottom: 20px;">
        <h1 style="font-size: 16px; margin: 0;">Listado de Fichajes</h1>
        <h2 style="font-size: 13px; margin: 5px 0;"><?php echo $fechaInicioFmt . ' - ' . $fechaFinFmt; ?></h2>
        <?php if ($datos['empleadoNombre'] !== 'Todos los empleados'): ?>
            <h3 style="font-size: 12px; margin: 3px 0; color: #555;"><?php echo $datos['empleadoNombre']; ?></h3>
        <?php else: ?>
            <h3 style="font-size: 12px; margin: 3px 0; color: #555;">Todos los empleados</h3>
        <?php endif; ?>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #4a1a6b; color: white;">
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: left;">Fecha/Hora</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: left;">Empleado</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: center;">Tipo</th>
                <th style="border: 1px solid #4a1a6b; padding: 6px; font-size: 11px; text-align: left;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos['fichajes'] as $i => $f): ?>
                <tr style="background: <?php echo $i % 2 === 0 ? '#fff' : '#f9f9f9'; ?>;">
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 10px;"><?php echo $f->fechahora; ?></td>
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 10px;"><?php echo $f->nombreempleado ?? 'Desconocido'; ?></td>
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 10px; text-align: center;">
                        <?php echo ucfirst($f->tipofichaje); ?>
                    </td>
                    <td style="border: 1px solid #ddd; padding: 4px 6px; font-size: 10px;"><?php echo $f->observaciones ?? '-'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 8px; color: #999; text-align: center;">
        Generado el <?php echo date('d/m/Y H:i'); ?> | Documento conforme al Real Decreto-ley 8/2019
    </div>
</page>