<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <h2 class="text-2xl font-semibold leading-tight">Mis Permisos: Vacaciones / Bajas / Ausencias</h2>
            <div class="flex gap-2">
                <a href="<?php echo RUTA_URL; ?>/Vacaciones/solicitarVacacion" class="bg-violeta-oscuro text-white px-4 py-2 rounded text-sm hover:bg-purple-800">
                    <i class="fas fa-plus mr-1"></i> Nueva solicitud
                </a>
            </div>
        </div>

        <?php if (empty($datos['vacaciones'])): ?>
            <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-500">
                <i class="fas fa-umbrella-beach text-4xl mb-3"></i>
                <p>No tiene solicitudes de vacaciones</p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-violeta-oscuro text-white text-sm">
                                <th class="px-4 py-3 text-left">Tipo</th>
                                <th class="px-4 py-3 text-left">Fecha inicio</th>
                                <th class="px-4 py-3 text-left">Fecha fin</th>
                                <th class="px-4 py-3 text-center">Días</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Motivo</th>
                                <th class="px-4 py-3 text-left">Resp.</th>
                                <th class="px-4 py-3 text-left">Justificante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos['vacaciones'] as $v): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                                    <td class="px-4 py-3">
                                        <?php
                                        $tipos = [
                                            'vacaciones' => '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Vacaciones</span>',
                                            'baja' => '<span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Baja</span>',
                                            'ausencia' => '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Ausencia</span>'
                                        ];
                                        echo $tipos[$v->tipovacacion] ?? $v->tipovacacion;
                                        ?>
                                    </td>
                                    <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($v->fechainicio)); ?></td>
                                    <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($v->fechafin)); ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo $v->dias; ?></td>
                                    <td class="px-4 py-3">
                                        <?php
                                        $estados = [
                                            'pendiente' => '<span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Pendiente</span>',
                                            'aprobada' => '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Aprobada</span>',
                                            'rechazada' => '<span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded">Rechazada</span>',
                                            'cancelada' => '<span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">Cancelada</span>'
                                        ];
                                        echo $estados[$v->estado] ?? $v->estado;
                                        ?>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        <?php if (!empty($v->motivo)): ?>
                                            <?php if (strlen($v->motivo) > 100): ?>
                                                <?php echo substr($v->motivo, 0, 100); ?>...
                                                <button type="button" class="text-blue-500 hover:text-blue-700 text-xs ml-1" onclick="toggleMotivoMis(<?php echo $v->id; ?>, this)">
                                                    <i class="fas fa-expand-alt mr-1"></i>Ver completo
                                                </button>
                                            <?php else: ?>
                                                <?php echo $v->motivo; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <?php if (!empty($v->respuestaadmin)): ?>
                                            <button type="button" class="text-blue-500 hover:text-blue-700 text-xs" onclick="toggleRespuestaMis(<?php echo $v->id; ?>)">
                                                <i class="fas fa-comment mr-1"></i>Ver
                                            </button>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php if ($v->ficherojustificante): ?>
                                            <a href="<?php echo $v->ficherojustificante; ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs">
                                                <i class="fas fa-file-alt mr-1"></i>Ver
                                            </a>
                                        <?php else: ?>
                                            <?php if ($v->estado === 'pendiente' || $v->tipovacacion === 'baja'): ?>
                                                <a href="<?php echo RUTA_URL; ?>/Vacaciones/subirJustificante/<?php echo $v->id; ?>" class="text-yellow-500 hover:text-yellow-700 text-xs">
                                                    <i class="fas fa-upload mr-1"></i>Subir
                                                </a>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-xs">-</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if (!empty($v->respuestaadmin)): ?>
                                <tr id="respuesta-mis-<?php echo $v->id; ?>" class="hidden bg-gray-50">
                                    <td colspan="8" class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-comment text-blue-500 mr-1"></i> <strong>Respuesta:</strong> <?php echo htmlspecialchars($v->respuestaadmin, ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($v->motivo) && strlen($v->motivo) > 100): ?>
                                <tr id="motivo-mis-<?php echo $v->id; ?>" class="hidden bg-gray-50">
                                    <td colspan="8" class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                                        <i class="fas fa-align-left text-purple-500 mr-1"></i> <strong>Motivo completo:</strong> <?php echo htmlspecialchars($v->motivo, ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>

<script>
function toggleRespuestaMis(id) {
    var row = document.getElementById('respuesta-mis-' + id);
    if (row) {
        row.classList.toggle('hidden');
    }
}

function toggleMotivoMis(id, btn) {
    var row = document.getElementById('motivo-mis-' + id);
    if (row) {
        row.classList.toggle('hidden');
        if (row.classList.contains('hidden')) {
            btn.innerHTML = '<i class="fas fa-expand-alt mr-1"></i>Ver completo';
        } else {
            btn.innerHTML = '<i class="fas fa-compress-alt mr-1"></i>Ocultar';
        }
    }
}
</script>