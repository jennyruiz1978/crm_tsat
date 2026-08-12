<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <h2 class="text-2xl font-semibold leading-tight">
                Reporte Mensual: <?php echo $datos['empleado']->nombre . ' ' . $datos['empleado']->apellidos; ?>
            </h2>
            <div class="flex gap-2">
                <a href="<?php echo RUTA_URL; ?>/ReportesHorario/reportePDFMensualEmpleado/<?php echo $datos['idEmpleado']; ?>?mes=<?php echo $datos['mes']; ?>&anio=<?php echo $datos['anio']; ?>" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                </a>
                <a href="<?php echo RUTA_URL; ?>/ReportesHorario/generarReportes" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-3 bg-blue-50 rounded">
                    <p class="text-2xl font-bold text-blue-600"><?php echo $datos['diasTrabajados']; ?></p>
                    <p class="text-sm text-gray-600">Días trabajados</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded">
                    <p class="text-2xl font-bold text-green-600"><?php echo number_format($datos['horasTotales'], 2); ?>h</p>
                    <p class="text-sm text-gray-600">Horas totales</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded">
                    <p class="text-2xl font-bold text-green-600"><?php echo $datos['jornadasCompletas']; ?></p>
                    <p class="text-sm text-gray-600">Jornadas completas</p>
                </div>
                <div class="text-center p-3 bg-yellow-50 rounded">
                    <p class="text-2xl font-bold text-yellow-600"><?php echo $datos['jornadasIncompletas']; ?></p>
                    <p class="text-sm text-gray-600">Jornadas incompletas</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-3">Resumen por día</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-violeta-oscuro text-white text-sm">
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Estado</th>
                            <th class="px-4 py-3 text-center">Horas</th>
                            <th class="px-4 py-3 text-center">Completada</th>
                            <th class="px-4 py-3 text-center">Fichajes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos['resumen'] as $jornada): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                                <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($jornada->fecha)); ?></td>
                                <td class="px-4 py-3">
                                    <?php
                                    $estados = [
                                        'abierta' => '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Abierta</span>',
                                        'cerrada' => '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Cerrada</span>',
                                        'incompleta' => '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Incompleta</span>',
                                        'vacaciones' => '<span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">Vacaciones</span>',
                                        'baja' => '<span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Baja</span>',
                                        'ausencia' => '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Ausencia</span>'
                                    ];
                                    echo $estados[$jornada->estadojornada] ?? $jornada->estadojornada;
                                    ?>
                                </td>
                                <td class="px-4 py-3 text-center"><?php echo $jornada->horastotales; ?>h</td>
                                <td class="px-4 py-3 text-center"><?php echo $jornada->completada ? '<i class="fas fa-check text-green-500"></i>' : '<i class="fas fa-times text-red-500"></i>'; ?></td>
                                <td class="px-4 py-3 text-center"><?php echo $jornada->numerofichajes; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (!empty($datos['fichajes'])): ?>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-3">Detalle de fichajes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-100 text-sm">
                                <th class="px-4 py-2 text-left">Fecha/Hora</th>
                                <th class="px-4 py-2 text-left">Tipo</th>
                                <th class="px-4 py-2 text-center">Corregido</th>
                                <th class="px-4 py-2 text-left">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['fichajes'] as $f): ?>
                                <tr class="border-b border-gray-100 text-sm">
                                    <td class="px-4 py-2"><?php echo date('d/m/Y H:i', strtotime($f->fechahora)); ?></td>
                                    <td class="px-4 py-2">
                                        <?php echo $f->tipofichaje === 'entrada' ? '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Entrada</span>' : '<span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Salida</span>'; ?>
                                    </td>
                                    <td class="px-4 py-2 text-center"><?php echo $f->corregido ? '<span class="text-yellow-500 text-xs"><i class="fas fa-exclamation-triangle"></i></span>' : '-'; ?></td>
                                    <td class="px-4 py-2 text-xs text-gray-600"><?php echo $f->observaciones ?? '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>