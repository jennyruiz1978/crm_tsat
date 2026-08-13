<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <?php
            $tiposTitulo = ['vacaciones' => 'Vacaciones', 'baja' => 'Baja Médica', 'ausencia' => 'Ausencia Justificada'];
            $tipoTitulo = $tiposTitulo[$datos['vacacion']->tipovacacion] ?? 'Solicitud';
            ?>
            <h2 class="text-2xl font-semibold leading-tight">Resolver Solicitud de <?php echo $tipoTitulo; ?> #<?php echo $datos['vacacion']->id; ?></h2>
            <a href="<?php echo RUTA_URL; ?>/Vacaciones/listadoVacaciones" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <div class="max-w-3xl mx-auto">

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Datos de la Solicitud</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Empleado</p>
                        <p class="font-medium"><?php echo $datos['vacacion']->nombreempleado; ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tipo</p>
                        <p class="font-medium">
                            <?php
                            $tipos = ['vacaciones' => 'Vacaciones', 'baja' => 'Baja médica', 'ausencia' => 'Ausencia justificada'];
                            echo $tipos[$datos['vacacion']->tipovacacion] ?? $datos['vacacion']->tipovacacion;
                            ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fecha inicio</p>
                        <p class="font-medium"><?php echo date('d/m/Y', strtotime($datos['vacacion']->fechainicio)); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fecha fin</p>
                        <p class="font-medium"><?php echo date('d/m/Y', strtotime($datos['vacacion']->fechafin)); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Días</p>
                        <p class="font-medium"><?php echo $datos['vacacion']->dias; ?> días</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fecha de solicitud</p>
                        <p class="font-medium"><?php echo date('d/m/Y H:i', strtotime($datos['vacacion']->creadoen)); ?></p>
                    </div>
                    <?php if ($datos['vacacion']->motivo): ?>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Motivo</p>
                            <p class="font-medium bg-gray-50 p-3 rounded"><?php echo $datos['vacacion']->motivo; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($datos['vacacion']->ficherojustificante): ?>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Justificante</p>
                            <a href="<?php echo $datos['vacacion']->ficherojustificante; ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-alt mr-1"></i>Ver justificante
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Resolver solicitud</h3>

                <?php if (isset($datos['errorSolapamiento'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-triangle mr-1"></i> <?php echo $datos['errorSolapamiento']; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo RUTA_URL; ?>/Vacaciones/resolverVacacion/<?php echo $datos['vacacion']->id; ?>">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Respuesta del administrador</label>
                        <textarea name="respuestaadmin" rows="3" class="w-full border rounded px-3 py-2 text-sm" placeholder="Motivo de la aprobación, rechazo o cancelación..."></textarea>
                    </div>

                    <div class="flex gap-3">
                        <?php if ($datos['vacacion']->estado === 'pendiente'): ?>
                            <button type="submit" name="accion" value="aprobar" class="bg-green-600 text-white px-6 py-2 rounded text-sm hover:bg-green-700"
                                    onclick="return confirm('¿Aprobar esta solicitud? Se marcarán los días en el calendario del empleado.')">
                                <i class="fas fa-check mr-1"></i> Aprobar
                            </button>
                            <button type="submit" name="accion" value="rechazar" class="bg-red-600 text-white px-6 py-2 rounded text-sm hover:bg-red-700"
                                    onclick="return confirm('¿Rechazar esta solicitud?')">
                                <i class="fas fa-times mr-1"></i> Rechazar
                            </button>
                        <?php elseif ($datos['vacacion']->estado === 'aprobada'): ?>
                            <?php
                            $tiposCancelar = [
                                'vacaciones' => ['texto' => 'Cancelar vacaciones', 'confirm' => '¿Cancelar estas vacaciones aprobadas? Se revertirán los días marcados.'],
                                'baja' => ['texto' => 'Cancelar baja', 'confirm' => '¿Cancelar esta baja aprobada? Se revertirán los días marcados.'],
                                'ausencia' => ['texto' => 'Cancelar ausencia', 'confirm' => '¿Cancelar esta ausencia aprobada? Se revertirán los días marcados.']
                            ];
                            $cancelarInfo = $tiposCancelar[$datos['vacacion']->tipovacacion] ?? $tiposCancelar['vacaciones'];
                            ?>
                            <button type="submit" name="accion" value="cancelar" class="bg-yellow-600 text-white px-6 py-2 rounded text-sm hover:bg-yellow-700"
                                    onclick="return confirm('<?php echo $cancelarInfo['confirm']; ?>')">
                                <i class="fas fa-ban mr-1"></i> <?php echo $cancelarInfo['texto']; ?>
                            </button>
                        <?php endif; ?>
                        <a href="<?php echo RUTA_URL; ?>/Vacaciones/listadoVacaciones" class="bg-gray-500 text-white px-6 py-2 rounded text-sm hover:bg-gray-600">
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>