<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <h2 class="text-2xl font-semibold leading-tight mb-6">Reportes de Control Horario</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4"><i class="fas fa-file-csv text-green-600 mr-2"></i>Exportar Fichajes CSV</h3>
                <p class="text-sm text-gray-600 mb-4">Descarga un archivo CSV con todos los fichajes del periodo seleccionado.</p>

                <form method="GET" action="<?php echo RUTA_URL; ?>/ReportesHorario/exportarCSV" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                            <input type="date" name="fechainicio" value="<?php echo date('Y-m-01'); ?>" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                            <input type="date" name="fechafin" value="<?php echo date('Y-m-t'); ?>" class="w-full border rounded px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
                        <select name="idempleado" class="w-full border rounded px-3 py-2 text-sm">
                            <option value="">Todos</option>
                            <?php
                            $activos = [];
                            $inactivos = [];
                            foreach ($datos['empleados'] as $emp):
                                if ($emp->activo == 1) {
                                    $activos[] = $emp;
                                } else {
                                    $inactivos[] = $emp;
                                }
                            endforeach;
                            ?>
                            <?php if (!empty($activos)): ?>
                            <optgroup label="Activos">
                                <?php foreach ($activos as $emp): ?>
                                    <option value="<?php echo $emp->id; ?>"><?php echo $emp->nombreempleado; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($inactivos)): ?>
                            <optgroup label="Inactivos">
                                <?php foreach ($inactivos as $emp): ?>
                                    <option value="<?php echo $emp->id; ?>"><?php echo $emp->nombreempleado; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded text-sm hover:bg-green-700 w-full">
                        <i class="fas fa-download mr-1"></i> Descargar CSV
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4"><i class="fas fa-user text-blue-600 mr-2"></i>Reporte Mensual por Empleado</h3>
                <p class="text-sm text-gray-600 mb-4">Ver detalle de fichajes de un empleado en pantalla o generar PDF.</p>

                <form id="formEmpleado" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                            <select name="mes" class="w-full border rounded px-3 py-2 text-sm">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>" <?php echo $datos['mes'] == $m ? 'selected' : ''; ?>>
                                        <?php $meses = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre']; echo $meses[$m]; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                            <select name="anio" class="w-full border rounded px-3 py-2 text-sm">
                                <?php for ($y = date('Y'); $y >= date('Y') - 2; $y--): ?>
                                    <option value="<?php echo $y; ?>" <?php echo $datos['anio'] == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
                        <select name="idempleado" id="selectEmpleado" class="w-full border rounded px-3 py-2 text-sm" required>
                            <option value="">Seleccionar empleado...</option>
                            <?php if (!empty($activos)): ?>
                            <optgroup label="Activos">
                                <?php foreach ($activos as $emp): ?>
                                    <option value="<?php echo $emp->id; ?>"><?php echo $emp->nombreempleado; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($inactivos)): ?>
                            <optgroup label="Inactivos">
                                <?php foreach ($inactivos as $emp): ?>
                                    <option value="<?php echo $emp->id; ?>"><?php echo $emp->nombreempleado; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="verReporteEmpleado()" class="bg-violeta-oscuro text-white px-4 py-2 rounded text-sm hover:bg-purple-800 flex-1">
                            <i class="fas fa-eye mr-1"></i> Ver en pantalla
                        </button>
                        <button type="button" onclick="generarPDFEmpleado()" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 flex-1">
                            <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4"><i class="fas fa-building text-purple-600 mr-2"></i>Reporte Global de Empresa</h3>
                <p class="text-sm text-gray-600 mb-4">Resumen de todos los empleados del periodo seleccionado.</p>

                <form id="formGlobal" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                            <select name="mes" class="w-full border rounded px-3 py-2 text-sm">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>" <?php echo $datos['mes'] == $m ? 'selected' : ''; ?>>
                                        <?php $meses = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre']; echo $meses[$m]; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                            <select name="anio" class="w-full border rounded px-3 py-2 text-sm">
                                <?php for ($y = date('Y'); $y >= date('Y') - 2; $y--): ?>
                                    <option value="<?php echo $y; ?>" <?php echo $datos['anio'] == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="verReporteGlobal()" class="bg-violeta-oscuro text-white px-4 py-2 rounded text-sm hover:bg-purple-800 flex-1">
                            <i class="fas fa-eye mr-1"></i> Ver en pantalla
                        </button>
                        <button type="button" onclick="generarPDFGlobal()" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 flex-1">
                            <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>
</div>

<script>
function verReporteEmpleado() {
    var id = document.getElementById('selectEmpleado').value;
    if (!id) { alert('Seleccione un empleado'); return; }
    var mes = document.querySelector('#formEmpleado select[name=mes]').value;
    var anio = document.querySelector('#formEmpleado select[name=anio]').value;
    window.location.href = '<?php echo RUTA_URL; ?>/ReportesHorario/reporteMensualEmpleado/' + id + '?mes=' + mes + '&anio=' + anio;
}

function generarPDFEmpleado() {
    var id = document.getElementById('selectEmpleado').value;
    if (!id) { alert('Seleccione un empleado'); return; }
    var mes = document.querySelector('#formEmpleado select[name=mes]').value;
    var anio = document.querySelector('#formEmpleado select[name=anio]').value;
    window.location.href = '<?php echo RUTA_URL; ?>/ReportesHorario/reportePDFMensualEmpleado/' + id + '?mes=' + mes + '&anio=' + anio;
}

function verReporteGlobal() {
    var mes = document.querySelector('#formGlobal select[name=mes]').value;
    var anio = document.querySelector('#formGlobal select[name=anio]').value;
    window.location.href = '<?php echo RUTA_URL; ?>/ReportesHorario/reporteGlobalEmpresa?mes=' + mes + '&anio=' + anio;
}

function generarPDFGlobal() {
    var mes = document.querySelector('#formGlobal select[name=mes]').value;
    var anio = document.querySelector('#formGlobal select[name=anio]').value;
    window.location.href = '<?php echo RUTA_URL; ?>/ReportesHorario/reportePDFGlobalEmpresa?mes=' + mes + '&anio=' + anio;
}
</script>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>