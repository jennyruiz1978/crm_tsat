<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold leading-tight">Resolver Solicitud #<?php echo $datos['solicitud']->id; ?></h2>
            <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/solicitudesModificacion" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <div class="max-w-3xl mx-auto">

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Datos de la Solicitud</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Empleado</p>
                        <p class="font-medium"><?php echo $datos['solicitud']->nombreempleado; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fecha de solicitud</p>
                        <p class="font-medium"><?php echo date('d/m/Y H:i', strtotime($datos['solicitud']->creadoen)); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tipo de modificación</p>
                        <p class="font-medium">
                            <?php
                            $tipos = ['insertar' => 'Insertar fichaje olvidado', 'modificar' => 'Modificar hora y/o tipo de fichaje', 'omitir' => 'Marcar fichaje como erróneo (eliminar)'];
                            echo $tipos[$datos['solicitud']->tipomodificacion] ?? $datos['solicitud']->tipomodificacion;
                            ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Motivo</p>
                        <p class="font-medium"><?php echo $datos['solicitud']->motivo; ?></p>
                    </div>

                    <?php if ($datos['solicitud']->fechahoraoriginal): ?>
                        <div>
                            <p class="text-sm text-gray-500">Fichaje original</p>
                            <p class="font-medium"><?php echo ucfirst($datos['solicitud']->tipofichajeoriginal); ?> - <?php echo date('d/m/Y H:i', strtotime($datos['solicitud']->fechahoraoriginal)); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($datos['solicitud']->nuevafechahora && $datos['solicitud']->nuevafechahora !== '0000-00-00 00:00:00'): ?>
                        <div>
                            <p class="text-sm text-gray-500">
                                <?php if ($datos['solicitud']->tipomodificacion === 'modificar'): ?>
                                    Nueva hora propuesta
                                <?php else: ?>
                                    Nueva fecha/hora propuesta
                                <?php endif; ?>
                            </p>
                            <p class="font-medium">
                                <?php if ($datos['solicitud']->tipomodificacion === 'modificar'): ?>
                                    <?php echo date('H:i', strtotime($datos['solicitud']->nuevafechahora)); ?>
                                <?php else: ?>
                                    <?php echo date('d/m/Y H:i', strtotime($datos['solicitud']->nuevafechahora)); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if ($datos['solicitud']->nuevotipofichaje && $datos['solicitud']->tipomodificacion !== 'omitir'): ?>
                        <div>
                            <p class="text-sm text-gray-500">Tipo propuesto</p>
                            <p class="font-medium"><?php echo ucfirst($datos['solicitud']->nuevotipofichaje); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($datos['fichajesJornada'])): ?>
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-3 border-b pb-2">Fichajes de la jornada</h3>
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-3 py-2 text-left">Hora</th>
                                <th class="px-3 py-2 text-left">Tipo</th>
                                <th class="px-3 py-2 text-left">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['fichajesJornada'] as $f): ?>
                                <tr class="border-b border-gray-100 <?php echo $f->id == $datos['solicitud']->idfichajeoriginal ? 'bg-yellow-50' : ''; ?>">
                                    <td class="px-3 py-2"><?php echo date('H:i', strtotime($f->fechahora)); ?></td>
                                    <td class="px-3 py-2">
                                        <?php echo $f->tipofichaje === 'entrada' ? '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Entrada</span>' : '<span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Salida</span>'; ?>
                                    </td>
                                    <td class="px-3 py-2 text-gray-600 text-xs"><?php echo $f->observaciones ?? '-'; echo $f->corregido ? ' <span class="text-yellow-500">(corregido)</span>' : ''; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Resolver solicitud</h3>
                <form method="POST" action="<?php echo RUTA_URL; ?>/AdministracionHorario/resolverSolicitud/<?php echo $datos['solicitud']->id; ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Respuesta del administrador</label>
                        <textarea name="respuestaadmin" rows="3" class="w-full border rounded px-3 py-2 text-sm" placeholder="Motivo de la aprobación o rechazo..." required></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" name="accion" value="aprobar" class="bg-green-600 text-white px-6 py-2 rounded text-sm hover:bg-green-700"
                                onclick="return confirm('¿Aprobar esta solicitud? Se creará/modificará el fichaje correspondiente.')">
                            <i class="fas fa-check mr-1"></i> Aprobar
                        </button>
                        <button type="submit" name="accion" value="rechazar" class="bg-red-600 text-white px-6 py-2 rounded text-sm hover:bg-red-700"
                                onclick="return confirm('¿Rechazar esta solicitud? El fichaje original se mantendrá sin cambios.')">
                            <i class="fas fa-times mr-1"></i> Rechazar
                        </button>
                        <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/solicitudesModificacion" class="bg-gray-500 text-white px-6 py-2 rounded text-sm hover:bg-gray-600">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>