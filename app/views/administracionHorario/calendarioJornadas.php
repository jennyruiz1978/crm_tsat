<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <h2 class="text-2xl font-semibold leading-tight mb-4">Calendario de Jornadas</h2>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <form method="GET" action="<?php echo RUTA_URL; ?>/AdministracionHorario/calendarioJornadas" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
                    <select name="idempleado" class="border rounded px-3 py-2 text-sm">
                        <option value="">Seleccionar empleado...</option>
                        <?php foreach ($datos['empleados'] as $emp): ?>
                            <option value="<?php echo $emp->id; ?>" <?php echo $datos['idEmpleado'] == $emp->id ? 'selected' : ''; ?>>
                                <?php echo $emp->nombre . ' ' . $emp->apellidos; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
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

        <?php if (empty($datos['idEmpleado'])): ?>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-calendar-alt text-4xl mb-3"></i>
                    <p>Seleccione un empleado para ver su calendario de jornadas.</p>
                </div>
            </div>
        <?php else: ?>

        <?php
        $primerDia = mktime(0, 0, 0, $datos['mes'], 1, $datos['anio']);
        $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $datos['mes'], $datos['anio']);
        $diaInicio = date('N', $primerDia);
        $mesesNombre = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
        $nombreMes = $mesesNombre[$datos['mes']];

        $jornadasPorDia = [];
        foreach ($datos['jornadas'] as $j) {
            $dia = (int)date('j', strtotime($j->fecha));
            $jornadasPorDia[$dia] = $j;
        }
        ?>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-center"><?php echo ucfirst($nombreMes) . ' ' . $datos['anio']; ?></h3>

            <div class="grid grid-cols-7 gap-1">
                <?php
                $diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                foreach ($diasSemana as $dia): ?>
                    <div class="text-center text-xs font-semibold text-gray-500 py-2"><?php echo $dia; ?></div>
                <?php endforeach; ?>

                <?php for ($i = 1; $i < $diaInicio; $i++): ?>
                    <div class="bg-gray-50 rounded p-1 min-h-16"></div>
                <?php endfor; ?>

                <?php for ($d = 1; $d <= $diasEnMes; $d++): ?>
                    <?php
                    $fechaActual = $datos['anio'] . '-' . str_pad($datos['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($d, 2, '0', STR_PAD_LEFT);
                    $esHoy = $fechaActual === date('Y-m-d');
                    $jornada = $jornadasPorDia[$d] ?? null;
                    ?>
                    <div class="border rounded p-1 min-h-16 <?php echo $esHoy ? 'border-blue-500 bg-blue-50' : 'border-gray-200'; ?>">
                        <div class="text-xs font-semibold <?php echo $esHoy ? 'text-blue-600' : 'text-gray-700'; ?>"><?php echo $d; ?></div>
                        <?php if ($jornada): ?>
                            <?php
                            $color = 'bg-gray-200 text-gray-600';
                            if ($jornada->estadojornada === 'cerrada') $color = 'bg-green-100 text-green-700';
                            elseif ($jornada->estadojornada === 'abierta') $color = 'bg-blue-100 text-blue-700';
                            elseif ($jornada->estadojornada === 'incompleta') $color = 'bg-yellow-100 text-yellow-700';
                            elseif ($jornada->estadojornada === 'vacaciones') $color = 'bg-purple-100 text-purple-700';
                            elseif ($jornada->estadojornada === 'baja') $color = 'bg-red-100 text-red-700';
                            elseif ($jornada->estadojornada === 'ausencia') $color = 'bg-pink-100 text-pink-700';
                            ?>
                            <div class="<?php echo $color; ?> text-xs rounded px-1 mt-1">
                                <?php echo $jornada->horastotales; ?>h
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>

                <?php
                $ultimoDia = date('N', mktime(0, 0, 0, $datos['mes'], $diasEnMes, $datos['anio']));
                for ($i = $ultimoDia; $i < 7; $i++): ?>
                    <div class="bg-gray-50 rounded p-1 min-h-16"></div>
                <?php endfor; ?>
            </div>

            <div class="flex gap-4 mt-6 justify-center flex-wrap">
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-green-100 rounded border border-green-300"></div><span class="text-xs">Cerrada</span></div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-blue-100 rounded border border-blue-300"></div><span class="text-xs">Abierta</span></div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-yellow-100 rounded border border-yellow-300"></div><span class="text-xs">Incompleta</span></div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-purple-100 rounded border border-purple-300"></div><span class="text-xs">Vacaciones</span></div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-red-100 rounded border border-red-300"></div><span class="text-xs">Baja</span></div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-pink-100 rounded border border-pink-300"></div><span class="text-xs">Ausencia</span></div>
                <div class="flex items-center gap-1"><div class="w-4 h-4 bg-gray-200 rounded border border-gray-300"></div><span class="text-xs">Sin registro</span></div>
            </div>
        </div>
        <?php endif; ?>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>