<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <h2 class="text-2xl font-semibold leading-tight">
                Detalle de <?php echo $datos['empleado']->nombre . ' ' . $datos['empleado']->apellidos; ?>
            </h2>
            <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/listadoGlobal" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <form method="GET" action="<?php echo RUTA_URL; ?>/AdministracionHorario/detalleEmpleado/<?php echo $datos['idEmpleado']; ?>" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                    <select name="mes" class="border rounded px-3 py-2 text-sm">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo $datos['mes'] == $m ? 'selected' : ''; ?>>
                                <?php
                                $meses = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
                                echo $meses[$m];
                                ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                    <select name="anio" class="border rounded px-3 py-2 text-sm">
                        <?php for ($y = date('Y'); $y >= date('Y') - 2; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo $datos['anio'] == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="bg-violeta-oscuro text-white px-4 py-2 rounded text-sm hover:bg-purple-800">
                    <i class="fas fa-search mr-1"></i> Consultar
                </button>
            </form>
        </div>

        <?php if (empty($datos['jornadas'])): ?>
            <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-calendar-alt text-4xl mb-3"></i>
                <p>No hay registros para el periodo seleccionado</p>
            </div>
        <?php else: ?>
            <?php foreach ($datos['jornadas'] as $jornada): ?>
                <div class="bg-white rounded-lg shadow-lg p-6 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-lg font-semibold"><?php echo date('d/m/Y', strtotime($jornada->fecha)); ?></h3>
                            <div class="flex gap-2 mt-1">
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
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded"><?php echo $jornada->horastotales; ?>h</span>
                            </div>
                        </div>
                        <?php if ($jornada->completada): ?>
                            <span class="text-green-500 text-sm"><i class="fas fa-check-circle mr-1"></i>Completada</span>
                        <?php else: ?>
                            <span class="text-yellow-500 text-sm"><i class="fas fa-exclamation-circle mr-1"></i>Incompleta</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($datos['fichajesPorJornada'][$jornada->id])): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-3 py-2 text-left">Hora</th>
                                        <th class="px-3 py-2 text-left">Tipo</th>
                                        <th class="px-3 py-2 text-left">Observaciones</th>
                                        <th class="px-3 py-2 text-left">Corregido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datos['fichajesPorJornada'][$jornada->id] as $fichaje): ?>
                                        <tr class="border-b border-gray-100 <?php echo !empty($fichaje->corregido) ? 'bg-yellow-50 opacity-60' : ''; ?>">
                                            <td class="px-3 py-2 <?php echo !empty($fichaje->corregido) ? 'line-through' : ''; ?>"><?php echo date('H:i', strtotime($fichaje->fechahora)); ?></td>
                                            <td class="px-3 py-2">
                                                <?php if ($fichaje->tipofichaje === 'entrada'): ?>
                                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Entrada</span>
                                                <?php else: ?>
                                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Salida</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-3 py-2 text-gray-600 text-xs"><?php echo $fichaje->observaciones ?? '-'; ?></td>
                                            <td class="px-3 py-2">
                                                <?php if (!empty($fichaje->corregido)): ?>
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
                    <?php else: ?>
                        <p class="text-gray-400 text-sm italic">Sin fichajes registrados</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>