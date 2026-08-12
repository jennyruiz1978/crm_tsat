<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold leading-tight">Solicitudes de Modificación</h2>
            <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/listadoGlobal" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <?php if (!empty($datos['pendientes'])): ?>
            <h3 class="text-lg font-semibold mb-3 text-yellow-600"><i class="fas fa-exclamation-triangle mr-1"></i> Solicitudes Pendientes</h3>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-yellow-500 text-white text-sm">
                                <th class="px-4 py-3 text-left">Fecha</th>
                                <th class="px-4 py-3 text-left">Empleado</th>
                                <th class="px-4 py-3 text-left">Tipo</th>
                                <th class="px-4 py-3 text-left">Detalle</th>
                                <th class="px-4 py-3 text-left">Motivo</th>
                                <th class="px-4 py-3 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['pendientes'] as $solicitud): ?>
                                <tr class="border-b border-gray-200 hover:bg-yellow-50 text-sm">
                                    <td class="px-4 py-3"><?php echo date('d/m/Y H:i', strtotime($solicitud->creadoen)); ?></td>
                                    <td class="px-4 py-3 font-medium"><?php echo $solicitud->nombreempleado; ?></td>
                                    <td class="px-4 py-3">
                                        <?php
                                        $tipos = [
                                            'insertar' => '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Insertar</span>',
                                            'modificar' => '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Modificar</span>',
                                            'omitir' => '<span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Omitir</span>'
                                        ];
                                        echo $tipos[$solicitud->tipomodificacion] ?? $solicitud->tipomodificacion;
                                        ?>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <?php if ($solicitud->tipomodificacion === 'insertar'): ?>
                                            Insertar <?php echo ucfirst($solicitud->nuevotipofichaje ?? ''); ?><br>
                                            <?php echo $solicitud->nuevafechahora ? date('d/m/Y H:i', strtotime($solicitud->nuevafechahora)) : '-'; ?>
                                        <?php elseif ($solicitud->tipomodificacion === 'modificar'): ?>
                                            De: <?php echo $solicitud->fechahoraoriginal ? date('d/m/Y H:i', strtotime($solicitud->fechahoraoriginal)) : '-'; ?><br>
                                            A: <?php echo $solicitud->nuevafechahora ? date('d/m/Y H:i', strtotime($solicitud->nuevafechahora)) : '-'; ?>
                                        <?php elseif ($solicitud->tipomodificacion === 'omitir'): ?>
                                            Omitir fichaje del <?php echo $solicitud->fechahoraoriginal ? date('d/m/Y H:i', strtotime($solicitud->fechahoraoriginal)) : '-'; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-xs"><?php echo substr($solicitud->motivo, 0, 60); ?><?php echo strlen($solicitud->motivo) > 60 ? '...' : ''; ?></td>
                                    <td class="px-4 py-3">
                                        <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/resolverSolicitud/<?php echo $solicitud->id; ?>" class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 mr-1">
                                            <i class="fas fa-check mr-1"></i>Resolver
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <h3 class="text-lg font-semibold mb-3">Todas las Solicitudes</h3>
        <?php if (empty($datos['todas'])): ?>
            <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p>No hay solicitudes de modificación</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-violeta-oscuro text-white text-sm">
                                <th class="px-4 py-3 text-left">Fecha</th>
                                <th class="px-4 py-3 text-left">Empleado</th>
                                <th class="px-4 py-3 text-left">Tipo</th>
                                <th class="px-4 py-3 text-left">Detalle</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Admin</th>
                                <th class="px-4 py-3 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['todas'] as $solicitud): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                                    <td class="px-4 py-3"><?php echo date('d/m/Y H:i', strtotime($solicitud->creadoen)); ?></td>
                                    <td class="px-4 py-3"><?php echo $solicitud->nombreempleado; ?></td>
                                    <td class="px-4 py-3">
                                        <?php
                                        $tipos = [
                                            'insertar' => '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Insertar</span>',
                                            'modificar' => '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Modificar</span>',
                                            'omitir' => '<span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Omitir</span>'
                                        ];
                                        echo $tipos[$solicitud->tipomodificacion] ?? $solicitud->tipomodificacion;
                                        ?>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <?php if ($solicitud->tipomodificacion === 'insertar'): ?>
                                            Insertar <?php echo ucfirst($solicitud->nuevotipofichaje ?? ''); ?><br>
                                            <?php echo $solicitud->nuevafechahora ? date('d/m/Y H:i', strtotime($solicitud->nuevafechahora)) : '-'; ?>
                                        <?php elseif ($solicitud->tipomodificacion === 'modificar'): ?>
                                            De: <?php echo $solicitud->fechahoraoriginal ? date('d/m/Y H:i', strtotime($solicitud->fechahoraoriginal)) : '-'; ?><br>
                                            A: <?php echo $solicitud->nuevafechahora ? date('d/m/Y H:i', strtotime($solicitud->nuevafechahora)) : '-'; ?>
                                        <?php elseif ($solicitud->tipomodificacion === 'omitir'): ?>
                                            Omitir fichaje del <?php echo $solicitud->fechahoraoriginal ? date('d/m/Y H:i', strtotime($solicitud->fechahoraoriginal)) : '-'; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php
                                        $estados = [
                                            'pendiente' => '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Pendiente</span>',
                                            'aprobada' => '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aprobada</span>',
                                            'rechazada' => '<span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded">Rechazada</span>'
                                        ];
                                        echo $estados[$solicitud->estado] ?? $solicitud->estado;
                                        ?>
                                    </td>
                                    <td class="px-4 py-3 text-xs"><?php echo $solicitud->nombreadmin ?? '-'; ?></td>
                                    <td class="px-4 py-3">
                                        <?php if ($solicitud->estado === 'pendiente'): ?>
                                            <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/resolverSolicitud/<?php echo $solicitud->id; ?>" class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">
                                                <i class="fas fa-gavel mr-1"></i>Resolver
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs">Resuelta</span>
                                        <?php endif; ?>
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