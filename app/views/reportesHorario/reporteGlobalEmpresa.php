<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <h2 class="text-2xl font-semibold leading-tight">Reporte Global - <?php
            $meses = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
            echo $meses[$datos['mes']] . ' ' . $datos['anio'];
        ?></h2>
            <div class="flex gap-2">
                <a href="<?php echo RUTA_URL; ?>/ReportesHorario/reportePDFGlobalEmpresa?mes=<?php echo $datos['mes']; ?>&anio=<?php echo $datos['anio']; ?>" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700">
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
                    <p class="text-2xl font-bold text-blue-600"><?php echo $datos['totalEmpleados']; ?></p>
                    <p class="text-sm text-gray-600">Empleados</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded">
                    <p class="text-2xl font-bold text-green-600"><?php echo number_format($datos['horasTotalesEmpresa'], 2); ?>h</p>
                    <p class="text-sm text-gray-600">Horas totales</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded">
                    <p class="text-2xl font-bold text-green-600"><?php echo $datos['totalJornadasCompletas']; ?></p>
                    <p class="text-sm text-gray-600">Jornadas completas</p>
                </div>
                <div class="text-center p-3 bg-yellow-50 rounded">
                    <p class="text-2xl font-bold text-yellow-600"><?php echo $datos['totalJornadasIncompletas']; ?></p>
                    <p class="text-sm text-gray-600">Jornadas incompletas</p>
                </div>
            </div>
        </div>

        <?php if (empty($datos['resumen'])): ?>
            <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-calendar-alt text-4xl mb-3"></i>
                <p>No hay datos para el periodo seleccionado</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-violeta-oscuro text-white text-sm">
                                <th class="px-4 py-3 text-left">Empleado</th>
                                <th class="px-4 py-3 text-center">Días trabajados</th>
                                <th class="px-4 py-3 text-center">Horas totales</th>
                                <th class="px-4 py-3 text-center">Jornadas completas</th>
                                <th class="px-4 py-3 text-center">Jornadas incompletas</th>
                                <th class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['resumen'] as $emp): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                                    <td class="px-4 py-3 font-medium"><?php echo $emp->nombreempleado; ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo $emp->diasstrabajados; ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo number_format($emp->totalhoras, 2); ?>h</td>
                                    <td class="px-4 py-3 text-center"><?php echo $emp->jornadascompletas; ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo $emp->jornadasincompletas; ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="<?php echo RUTA_URL; ?>/ReportesHorario/reporteMensualEmpleado/<?php echo $emp->idempleado; ?>?mes=<?php echo $datos['mes']; ?>&anio=<?php echo $datos['anio']; ?>" class="text-blue-600 hover:text-blue-800 text-xs">
                                            <i class="fas fa-eye mr-1"></i>Ver detalle
                                        </a>
                                    </td>
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