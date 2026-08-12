<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold leading-tight">Mis Solicitudes</h2>
            <a href="<?php echo RUTA_URL; ?>/ControlHorario/solicitarModificacion" class="bg-violeta-oscuro text-white px-4 py-2 rounded text-sm hover:bg-purple-800">
                <i class="fas fa-plus mr-1"></i> Nueva solicitud
            </a>
        </div>

        <?php if (empty($datos['solicitudes'])): ?>
            <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p>No tiene solicitudes de modificación</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-violeta-oscuro text-white text-sm">
                                <th class="px-4 py-3 text-left">Fecha solicitud</th>
                                <th class="px-4 py-3 text-left">Tipo</th>
                                <th class="px-4 py-3 text-left">Detalle</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Respuesta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['solicitudes'] as $solicitud): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                                    <td class="px-4 py-3"><?php echo date('d/m/Y H:i', strtotime($solicitud->creadoen)); ?></td>
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
                                    <td class="px-4 py-3">
                                        <?php if ($solicitud->tipomodificacion === 'insertar'): ?>
                                            Insertar <?php echo ucfirst($solicitud->nuevotipofichaje ?? ''); ?>
                                            el <?php echo $solicitud->nuevafechahora ? date('d/m/Y H:i', strtotime($solicitud->nuevafechahora)) : '-'; ?>
                                        <?php elseif ($solicitud->tipomodificacion === 'modificar'): ?>
                                            Cambiar fichaje del <?php echo $solicitud->fechahoraoriginal ? date('d/m/Y H:i', strtotime($solicitud->fechahoraoriginal)) : '-'; ?>
                                            a <?php echo $solicitud->nuevafechahora ? date('d/m/Y H:i', strtotime($solicitud->nuevafechahora)) : '-'; ?>
                                        <?php elseif ($solicitud->tipomodificacion === 'omitir'): ?>
                                            Marcar como erróneo el fichaje del <?php echo $solicitud->fechahoraoriginal ? date('d/m/Y H:i', strtotime($solicitud->fechahoraoriginal)) : '-'; ?>
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
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        <?php if ($solicitud->respuestaadmin): ?>
                                            <?php echo substr($solicitud->respuestaadmin, 0, 80); ?>
                                            <?php if (strlen($solicitud->respuestaadmin) > 80): ?>...<?php endif; ?>
                                        <?php else: ?>
                                            -
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