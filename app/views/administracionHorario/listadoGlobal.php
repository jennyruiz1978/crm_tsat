<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <h2 class="text-2xl font-semibold leading-tight">Administración Horario</h2>
            <div class="flex gap-2 flex-wrap">
                <?php if ($datos['solicitudesPendientes'] > 0): ?>
                    <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/solicitudesModificacion" class="bg-yellow-500 text-white px-3 py-2 rounded text-sm hover:bg-yellow-600">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Solicitudes pendientes (<?php echo $datos['solicitudesPendientes']; ?>)
                    </a>
                <?php endif; ?>
                <?php if ($datos['vacacionesPendientes'] > 0): ?>
                    <a href="<?php echo RUTA_URL; ?>/Vacaciones/listadoVacaciones" class="bg-purple-500 text-white px-3 py-2 rounded text-sm hover:bg-purple-600">
                        <i class="fas fa-umbrella-beach mr-1"></i> Permisos pendientes (<?php echo $datos['vacacionesPendientes']; ?>)
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <form method="GET" action="<?php echo RUTA_URL; ?>/AdministracionHorario/listadoGlobal" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                    <input type="date" name="fechainicio" value="<?php echo $datos['fechaInicio']; ?>" class="border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                    <input type="date" name="fechafin" value="<?php echo $datos['fechaFin']; ?>" class="border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
                    <select name="idempleado" class="border rounded px-3 py-2 text-sm">
                        <option value="">Todos</option>
                        <?php foreach ($datos['empleados'] as $emp): ?>
                            <option value="<?php echo $emp->id; ?>" <?php echo $datos['idEmpleado'] == $emp->id ? 'selected' : ''; ?>>
                                <?php echo $emp->nombre . ' ' . $emp->apellidos; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-violeta-oscuro text-white px-4 py-2 rounded text-sm hover:bg-purple-800">
                    <i class="fas fa-search mr-1"></i> Filtrar
                </button>
            </form>
        </div>

        <?php if (empty($datos['jornadas'])): ?>
            <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-calendar-alt text-4xl mb-3"></i>
                <p>No hay registros para el periodo seleccionado</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-violeta-oscuro text-white text-sm">
                                <th class="px-4 py-3 text-left">Empleado</th>
                                <th class="px-4 py-3 text-left">Fecha</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Horas</th>
                                <th class="px-4 py-3 text-left">Completada</th>
                                <th class="px-4 py-3 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['jornadas'] as $jornada): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                                    <td class="px-4 py-3"><?php echo $jornada->nombreempleado ?? 'Desconocido'; ?></td>
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
                                    <td class="px-4 py-3"><?php echo $jornada->horastotales; ?>h</td>
                                    <td class="px-4 py-3">
                                        <?php echo $jornada->completada ? '<i class="fas fa-check text-green-500"></i>' : '<i class="fas fa-times text-red-500"></i>'; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/detalleEmpleado/<?php echo $jornada->idempleado; ?>?mes=<?php echo date('m', strtotime($jornada->fecha)); ?>&anio=<?php echo date('Y', strtotime($jornada->fecha)); ?>" class="text-blue-600 hover:text-blue-800 text-xs">
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