<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <h2 class="text-2xl font-semibold leading-tight mb-4">Configuración de Horario</h2>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-violeta-oscuro text-white text-sm">
                            <th class="px-4 py-3 text-left">Empleado</th>
                            <th class="px-4 py-3 text-left">Rol</th>
                            <th class="px-4 py-3 text-center">Debe Fichar</th>
                            <th class="px-4 py-3 text-center">Horas Jornada</th>
                            <th class="px-4 py-3 text-center">Entrada</th>
                            <th class="px-4 py-3 text-center">Salida</th>
                            <th class="px-4 py-3 text-center">Tolerancia (min)</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos['empleadosConfig'] as $emp): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm" id="fila-<?php echo $emp->idempleado; ?>">
                                <td class="px-4 py-3 font-medium"><?php echo $emp->nombreempleado; ?></td>
                                <td class="px-4 py-3">
                                    <?php
                                    $roles = [0 => 'Admin', 1 => 'Cliente', 2 => 'Técnico', 3 => 'Visitante'];
                                    echo $roles[$emp->rol] ?? 'Desconocido';
                                    ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span id="debeFichar-<?php echo $emp->idempleado; ?>" class="<?php echo $emp->debeFichar ? 'text-green-600' : 'text-red-500'; ?>">
                                        <i class="fas <?php echo $emp->debeFichar ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                                        <?php echo $emp->debeFichar ? 'Sí' : 'No'; ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center" id="horas-<?php echo $emp->idempleado; ?>"><?php echo $emp->jornadatipohoras; ?>h</td>
                                <td class="px-4 py-3 text-center" id="entrada-<?php echo $emp->idempleado; ?>"><?php echo $emp->horarioentrada ? date('H:i', strtotime($emp->horarioentrada)) : '-'; ?></td>
                                <td class="px-4 py-3 text-center" id="salida-<?php echo $emp->idempleado; ?>"><?php echo $emp->horariosalida ? date('H:i', strtotime($emp->horariosalida)) : '-'; ?></td>
                                <td class="px-4 py-3 text-center" id="tolerancia-<?php echo $emp->idempleado; ?>"><?php echo $emp->toleranciaminutos; ?> min</td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="editarConfig(<?php echo $emp->idempleado; ?>, '<?php echo htmlspecialchars($emp->nombreempleado, ENT_QUOTES, 'UTF-8'); ?>', <?php echo (int)$emp->debeFichar; ?>, <?php echo $emp->jornadatipohoras; ?>, '<?php echo date('H:i', strtotime($emp->horarioentrada)); ?>', '<?php echo date('H:i', strtotime($emp->horariosalida)); ?>', <?php echo $emp->toleranciaminutos; ?>)"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="modalEditar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto border w-96 shadow-lg rounded-md bg-white">
                <div class="bg-violeta-oscuro text-white px-4 py-3 rounded-t-md">
                    <h3 class="text-lg font-semibold" id="modalTitulo">Editar Configuración</h3>
                </div>
                <form method="POST" action="<?php echo RUTA_URL; ?>/AdministracionHorario/configuracionHorario">
                    <div class="p-6">
                        <input type="hidden" name="idempleado" id="editIdEmpleado">

                        <div class="mb-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="debeFichar" id="editDebeFichar" value="1" class="w-5 h-5">
                                <span class="text-sm font-medium text-gray-700">Debe fichar</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Horas de jornada</label>
                            <input type="number" name="jornadatipohoras" id="editHoras" step="0.5" min="1" max="12" class="w-full border rounded px-3 py-2 text-sm" value="8">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hora de entrada</label>
                            <input type="time" name="horarioentrada" id="editEntrada" class="w-full border rounded px-3 py-2 text-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hora de salida</label>
                            <input type="time" name="horariosalida" id="editSalida" class="w-full border rounded px-3 py-2 text-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tolerancia (minutos)</label>
                            <input type="number" name="toleranciaminutos" id="editTolerancia" min="0" max="30" class="w-full border rounded px-3 py-2 text-sm" value="5">
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-violeta-oscuro text-white px-6 py-2 rounded text-sm hover:bg-purple-800">
                                <i class="fas fa-save mr-1"></i> Guardar
                            </button>
                            <button type="button" onclick="cerrarModal()" class="bg-gray-500 text-white px-6 py-2 rounded text-sm hover:bg-gray-600">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function editarConfig(id, nombre, debeFichar, horas, entrada, salida, tolerancia) {
    document.getElementById('editIdEmpleado').value = id;
    document.getElementById('modalTitulo').textContent = 'Editar: ' + nombre;
    document.getElementById('editDebeFichar').checked = debeFichar == 1;
    document.getElementById('editHoras').value = horas;
    document.getElementById('editEntrada').value = entrada || '';
    document.getElementById('editSalida').value = salida || '';
    document.getElementById('editTolerancia').value = tolerancia;
    document.getElementById('modalEditar').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalEditar').classList.add('hidden');
}

document.getElementById('modalEditar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>