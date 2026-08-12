<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <h2 class="text-2xl font-semibold leading-tight mb-4">Solicitar Permiso</h2>

        <?php if (isset($datos['errorSolapamiento'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-triangle mr-1"></i> <?php echo $datos['errorSolapamiento']; ?>
            </div>
        <?php endif; ?>

        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <form method="POST" action="<?php echo RUTA_URL; ?>/Vacaciones/solicitarVacacion" enctype="multipart/form-data">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipovacacion" id="tipoVacacion" class="w-full border rounded px-3 py-2 text-sm" required>
                        <option value="">Seleccionar...</option>
                        <option value="vacaciones" <?php echo (isset($datos['tipovacacionPost']) && $datos['tipovacacionPost'] === 'vacaciones') ? 'selected' : ''; ?>>Vacaciones</option>
                        <option value="baja" <?php echo (isset($datos['tipovacacionPost']) && $datos['tipovacacionPost'] === 'baja') ? 'selected' : ''; ?>>Baja médica</option>
                        <option value="ausencia" <?php echo (isset($datos['tipovacacionPost']) && $datos['tipovacacionPost'] === 'ausencia') ? 'selected' : ''; ?>>Ausencia justificada</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio <span class="text-red-500">*</span></label>
                        <input type="date" name="fechainicio" id="fechaInicio" class="w-full border rounded px-3 py-2 text-sm" value="<?php echo $datos['fechainicioPost'] ?? ''; ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha fin <span class="text-red-500">*</span></label>
                        <input type="date" name="fechafin" id="fechaFin" class="w-full border rounded px-3 py-2 text-sm" value="<?php echo $datos['fechafinPost'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Días</label>
                    <p id="diasCalculados" class="border rounded px-3 py-2 text-sm bg-gray-50 text-gray-600">Seleccione las fechas</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo <span class="text-red-500">*</span></label>
                    <textarea name="motivo" rows="3" class="w-full border rounded px-3 py-2 text-sm" placeholder="Indique el motivo de su solicitud..." required><?php echo $datos['motivoPost'] ?? ''; ?></textarea>
                </div>

                <div class="mb-4" id="campoJustificante">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Justificante (opcional)</label>
                    <input type="file" name="justificante" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">PDF, imagen o documento. Máximo 5MB.</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-violeta-oscuro text-white px-6 py-2 rounded text-sm hover:bg-purple-800">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar solicitud
                    </button>
                    <a href="<?php echo RUTA_URL; ?>/Vacaciones/misVacaciones" class="bg-gray-500 text-white px-6 py-2 rounded text-sm hover:bg-gray-600">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
document.getElementById('fechaInicio').addEventListener('change', calcularDias);
document.getElementById('fechaFin').addEventListener('change', calcularDias);
document.getElementById('tipoVacacion').addEventListener('change', function() {
    var campo = document.getElementById('campoJustificante');
    var input = campo.querySelector('input[type="file"]');
    if (this.value === 'baja') {
        campo.querySelector('label').innerHTML = 'Justificante médico <span class="text-red-500">*</span>';
        input.required = true;
    } else {
        campo.querySelector('label').innerHTML = 'Justificante (opcional)';
        input.required = false;
        input.setCustomValidity('');
    }
});

function calcularDias() {
    var inicio = document.getElementById('fechaInicio').value;
    var fin = document.getElementById('fechaFin').value;
    var diasEl = document.getElementById('diasCalculados');

    if (!inicio || !fin) {
        diasEl.textContent = 'Seleccione las fechas';
        return;
    }

    var fechaInicio = new Date(inicio);
    var fechaFin = new Date(fin);

    if (fechaFin < fechaInicio) {
        diasEl.textContent = 'La fecha fin debe ser posterior a la fecha inicio';
        return;
    }

    var totalDias = Math.round((fechaFin - fechaInicio) / (1000 * 60 * 60 * 24)) + 1;
    diasEl.textContent = totalDias + (totalDias === 1 ? ' día' : ' días');
}
</script>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>