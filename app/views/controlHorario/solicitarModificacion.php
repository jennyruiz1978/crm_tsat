<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2 mb-4">Solicitar Modificación</h2>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <form method="GET" action="<?php echo RUTA_URL; ?>/ControlHorario/solicitarModificacion" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                    <select name="mes" class="border rounded px-3 py-2 text-sm">
                        <?php $meses = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre']; for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo $datos['mes'] == $m ? 'selected' : ''; ?>><?php echo $meses[$m]; ?></option>
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

        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <form method="POST" action="<?php echo RUTA_URL; ?>/ControlHorario/solicitarModificacion">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de modificación</label>
                    <select name="tipomodificacion" id="tipoModificacion" class="w-full border rounded px-3 py-2 text-sm" required>
                        <option value="">Seleccionar...</option>
                        <option value="insertar">Insertar fichaje olvidado</option>
                        <option value="modificar">Modificar hora y/o tipo de un fichaje</option>
                        <option value="omitir">Marcar fichaje como erróneo (eliminar)</option>
                    </select>
                </div>

                <div id="campoFichajeOriginal" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fichaje a corregir</label>
                    <select name="idfichajeoriginal" id="fichajeOriginal" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="" data-jornada="" data-tipo="" data-hora="">Seleccionar fichaje...</option>
                        <?php if (isset($datos['fichajesRecientes'])): ?>
                            <?php foreach ($datos['fichajesRecientes'] as $f): ?>
                                <option value="<?php echo $f->id; ?>" data-jornada="<?php echo $f->idjornada; ?>" data-tipo="<?php echo $f->tipofichaje; ?>" data-hora="<?php echo date('H:i', strtotime($f->fechahora)); ?>">
                                    <?php echo date('d/m/Y H:i', strtotime($f->fechahora)); ?> - <?php echo ucfirst($f->tipofichaje); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <input type="hidden" name="idjornadaoriginal" id="jornadaOriginalHidden" value="">

                <div id="campoJornadaOriginal" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jornada</label>
                    <select name="idjornadaoriginal" id="jornadaOriginal" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">Seleccionar jornada...</option>
                        <?php if (isset($datos['jornadas'])): ?>
                            <?php foreach ($datos['jornadas'] as $j): ?>
                                <option value="<?php echo $j->id; ?>">
                                    <?php echo date('d/m/Y', strtotime($j->fecha)); ?> - <?php echo ucfirst($j->estadojornada); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div id="campoNuevoTipo" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de fichaje</label>
                    <select name="nuevotipofichaje" id="nuevoTipoFichaje" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                    </select>
                </div>

                <div id="campoNuevaFechaHora" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1" id="labelNuevaFechaHora">Nueva fecha y hora</label>
                    <input type="datetime-local" name="nuevafechahora" id="nuevaFechaHora" class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                    <textarea name="nuevaobservaciones" rows="2" class="w-full border rounded px-3 py-2 text-sm" placeholder="Observaciones opcionales sobre la corrección..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la solicitud <span class="text-red-500">*</span></label>
                    <textarea name="motivo" rows="3" class="w-full border rounded px-3 py-2 text-sm" placeholder="Explique el motivo de la corrección solicitada..." required></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-violeta-oscuro text-white px-6 py-2 rounded text-sm hover:bg-purple-800">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar solicitud
                    </button>
                    <a href="<?php echo RUTA_URL; ?>/ControlHorario/misSolicitudes" class="bg-gray-500 text-white px-6 py-2 rounded text-sm hover:bg-gray-600">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
document.getElementById('tipoModificacion').addEventListener('change', function() {
    var tipo = this.value;
    var campoFichaje = document.getElementById('campoFichajeOriginal');
    var campoJornada = document.getElementById('campoJornadaOriginal');
    var campoNuevoTipo = document.getElementById('campoNuevoTipo');
    var campoNuevaFecha = document.getElementById('campoNuevaFechaHora');
    var labelNuevaFecha = document.getElementById('labelNuevaFechaHora');
    var inputNuevaFecha = document.getElementById('nuevaFechaHora');
    var jornadaHidden = document.getElementById('jornadaOriginalHidden');

    campoFichaje.classList.add('hidden');
    campoJornada.classList.add('hidden');
    campoNuevoTipo.classList.add('hidden');
    campoNuevaFecha.classList.add('hidden');
    jornadaHidden.value = '';

    if (tipo === 'insertar') {
        campoNuevoTipo.classList.remove('hidden');
        campoNuevaFecha.classList.remove('hidden');
        labelNuevaFecha.textContent = 'Nueva fecha y hora';
        inputNuevaFecha.type = 'datetime-local';
        inputNuevaFecha.value = '';
        inputNuevaFecha.required = true;
    } else if (tipo === 'modificar') {
        campoFichaje.classList.remove('hidden');
        campoNuevoTipo.classList.remove('hidden');
        campoNuevaFecha.classList.remove('hidden');
        labelNuevaFecha.textContent = 'Nueva hora';
        inputNuevaFecha.type = 'time';
        inputNuevaFecha.required = true;
        actualizarJornadaDesdeFichaje();
        prellenarHoraFichaje();
    } else if (tipo === 'omitir') {
        campoFichaje.classList.remove('hidden');
        inputNuevaFecha.required = false;
        actualizarJornadaDesdeFichaje();
    } else {
        inputNuevaFecha.required = false;
    }
});

document.getElementById('fichajeOriginal').addEventListener('change', function() {
    actualizarJornadaDesdeFichaje();
    prellenarHoraFichaje();
});

function actualizarJornadaDesdeFichaje() {
    var select = document.getElementById('fichajeOriginal');
    var jornadaHidden = document.getElementById('jornadaOriginalHidden');
    var option = select.options[select.selectedIndex];
    if (option && option.value !== '') {
        jornadaHidden.value = option.getAttribute('data-jornada') || '';
    } else {
        jornadaHidden.value = '';
    }
}

function prellenarHoraFichaje() {
    var tipo = document.getElementById('tipoModificacion').value;
    if (tipo !== 'modificar') return;
    var select = document.getElementById('fichajeOriginal');
    var inputHora = document.getElementById('nuevaFechaHora');
    var inputTipo = document.getElementById('nuevoTipoFichaje');
    var option = select.options[select.selectedIndex];
    if (option && option.value !== '') {
        var hora = option.getAttribute('data-hora') || '';
        var tipoFichaje = option.getAttribute('data-tipo') || '';
        inputHora.value = hora;
        if (tipoFichaje === 'entrada') {
            inputTipo.value = 'entrada';
        } else if (tipoFichaje === 'salida') {
            inputTipo.value = 'salida';
        }
    } else {
        inputHora.value = '';
    }
}
</script>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>