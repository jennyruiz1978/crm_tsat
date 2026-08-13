<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold leading-tight">Detalle de Jornada</h2>
            <a href="<?php echo RUTA_URL; ?>/ControlHorario/miHistorial" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-1"></i> Volver al historial
            </a>
        </div>

        <div class="max-w-3xl mx-auto">

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Empleado</p>
                        <p class="font-medium text-lg"><?php echo $datos['jornada']->nombreempleado ?? 'Desconocido'; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fecha</p>
                        <p class="font-medium text-lg"><?php echo date('d/m/Y', strtotime($datos['jornada']->fecha)); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Estado</p>
                        <p class="font-medium text-lg">
                            <?php
                            $estados = [
                                'abierta' => '<span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">Abierta</span>',
                                'cerrada' => '<span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">Cerrada</span>',
                                'incompleta' => '<span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">Incompleta</span>',
                                'vacaciones' => '<span class="bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded-full">Vacaciones</span>',
                                'baja' => '<span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">Baja</span>',
                                'ausencia' => '<span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">Ausencia</span>'
                            ];
                            echo $estados[$datos['jornada']->estadojornada] ?? $datos['jornada']->estadojornada;
                            ?>
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200">
                    <div>
                        <p class="text-sm text-gray-500">Horas totales</p>
                        <p class="font-bold text-xl text-violeta-oscuro"><?php echo $datos['horasTotales']; ?>h</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Completada</p>
                        <p class="font-medium text-lg">
                            <?php echo $datos['jornada']->completada ? '<i class="fas fa-check-circle text-green-500 text-xl"></i> Sí' : '<i class="fas fa-times-circle text-red-500 text-xl"></i> No'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Fichajes del día</h3>

                <?php if (empty($datos['fichajes'])): ?>
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-clock text-4xl mb-3"></i>
                        <p>No hay fichajes registrados para esta jornada</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr class="bg-violeta-oscuro text-white text-sm">
                                    <th class="px-4 py-3 text-left">Tipo</th>
                                    <th class="px-4 py-3 text-left">Hora</th>
                                    <th class="px-4 py-3 text-left">Observaciones</th>
                                    <th class="px-4 py-3 text-left">Solicitud</th>
                                    <th class="px-4 py-3 text-left">IP</th>
                                    <th class="px-4 py-3 text-left">Corregido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $entradaTimestamp = null;
                                foreach ($datos['fichajes'] as $i => $f):
                                    $hora = date('H:i:s', strtotime($f->fechahora));
                                    $duracion = '';
                                    $esCorregido = !empty($f->corregido);
                                    if ($esCorregido) {
                                        $entradaTimestamp = null;
                                    } else {
                                        if ($f->tipofichaje === 'salida' && $entradaTimestamp !== null) {
                                            $diff = strtotime($f->fechahora) - $entradaTimestamp;
                                            $horas = floor($diff / 3600);
                                            $minutos = floor(($diff % 3600) / 60);
                                            $duracion = '(' . $horas . 'h ' . $minutos . 'min)';
                                        }
                                        if ($f->tipofichaje === 'entrada') {
                                            $entradaTimestamp = strtotime($f->fechahora);
                                        }
                                    }
                                ?>
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm <?php echo $esCorregido ? 'bg-yellow-50 opacity-60' : ''; ?>">
                                        <td class="px-4 py-3">
                                            <?php if ($f->tipofichaje === 'entrada'): ?>
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                                    <i class="fas fa-sign-in-alt mr-1"></i>Entrada
                                                </span>
                                            <?php else: ?>
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                                    <i class="fas fa-sign-out-alt mr-1"></i>Salida <?php echo $duracion; ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 font-mono <?php echo $esCorregido ? 'line-through' : ''; ?>"><?php echo $hora; ?></td>
                                        <td class="px-4 py-3 text-gray-600 text-xs"><?php
                                            $obs = $f->observaciones ?: '-';
                                            $obs = preg_replace('/\s*\(?\s*por (aprobaci[oó]n|modificaci[oó]n) de solicitud #\d+\s*\)?\s*/i', '', $obs);
                                            $obs = preg_replace('/\s*\(?\s*Fichaje (insertado|modificado) por (aprobaci[oó]n|modificaci[oó]n) de solicitud #\d+\s*\)?\s*/i', '', $obs);
                                            echo trim($obs) ?: '-';
                                        ?></td>
                                        <td class="px-4 py-3 text-xs">
                                            <?php
                                            $numSolicitud = null;
                                            if ($f->observaciones && preg_match('/solicitud\s*#(\d+)/i', $f->observaciones, $m)) {
                                                $numSolicitud = $m[1];
                                            }
                                            echo $numSolicitud ? '<a href="' . RUTA_URL . '/AdministracionHorario/resolverSolicitud/' . $numSolicitud . '" class="text-blue-600 hover:text-blue-800">#' . $numSolicitud . '</a>' : '<span class="text-gray-400">-</span>';
                                            ?>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-xs"><?php echo $f->ipregistro ?: '-'; ?></td>
                                        <td class="px-4 py-3">
                                            <?php if ($esCorregido): ?>
                                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded"><i class="fas fa-exclamation-triangle mr-1"></i>Corregido</span>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-xs">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Total fichajes:</span>
                            <span class="font-bold"><?php echo count($datos['fichajes']); ?></span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="font-semibold text-gray-700">Horas totales:</span>
                            <span class="font-bold text-violeta-oscuro text-lg"><?php echo $datos['horasTotales']; ?>h</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex gap-3">
                <a href="<?php echo RUTA_URL; ?>/ControlHorario/solicitarModificacion" class="bg-yellow-500 text-white px-4 py-2 rounded text-sm hover:bg-yellow-600">
                    <i class="fas fa-edit mr-1"></i> Solicitar corrección
                </a>
            </div>

        </div>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>