<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2 mb-4">Control Horario - Fichar</h2>

        <?php if (isset($datos['puedeFichar']) && !$datos['puedeFichar']): ?>
            <?php if (isset($datos['vacacionBloqueo']) && $datos['vacacionBloqueo']): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Fichaje bloqueado</p>
                    <p><?php echo $datos['mensaje']; ?></p>
                </div>
            <?php else: ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Aviso</p>
                    <p><?php echo $datos['mensaje']; ?></p>
                </div>
            <?php endif; ?>
        <?php else: ?>

        <?php if (!empty($datos['jornadasIncompletas'])): ?>
            <?php foreach ($datos['jornadasIncompletas'] as $jincompleta): ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-600 text-yellow-800 p-4 mb-4" role="alert">
                    <p class="font-bold">Atención: Jornada incompleta</p>
                    <p>Su jornada del <strong><?php echo date('d/m/Y', strtotime($jincompleta->fecha)); ?></strong> qued&oacute; sin cierre (falt&oacute; registrar la salida). Para regularizarla, solicite una correcci&oacute;n desde <a href="<?php echo RUTA_URL; ?>/ControlHorario/solicitarModificacion" class="underline font-semibold">Mis Solicitudes</a>.</p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="text-center mb-4">
                    <p class="text-gray-600 text-sm mb-1">Estado actual</p>
                    <div id="estadoIndicador" class="inline-block px-6 py-2 rounded-full text-white text-lg font-bold <?php echo $datos['claseEstado']; ?>">
                        <?php echo $datos['estadoTexto']; ?>
                    </div>
                </div>

                <?php if ($datos['ultimoFichaje']): ?>
                    <div id="ultimoFichajeTexto" class="text-center text-sm text-gray-500 mb-4">
                        Último fichaje: <span id="ultimoFichajeTipo"><?php echo $datos['ultimoFichaje']->tipofichaje; ?></span>
                        - <span id="ultimoFichajeFecha"><?php echo date('d/m/Y H:i', strtotime($datos['ultimoFichaje']->fechahora)); ?></span>
                    </div>
                <?php else: ?>
                    <div id="ultimoFichajeTexto" class="text-center text-sm text-gray-500 mb-4 hidden"></div>
                <?php endif; ?>

                <div class="text-center">
                    <button id="btnFichar"
                        class="w-full py-8 text-2xl font-bold text-white rounded-xl shadow-lg transition-all duration-200 hover:shadow-xl active:scale-95 <?php echo $datos['tipoSiguiente'] === 'entrada' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'; ?>"
                        data-tipo="<?php echo $datos['tipoSiguiente']; ?>">
                        <i class="fas fa-clock mr-2"></i>
                        Fichar <?php echo ucfirst($datos['tipoSiguiente']); ?>
                    </button>
                </div>

                <div id="mensajeFichaje" class="hidden mt-4 p-3 rounded text-center"></div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-3">Jornada de hoy</h3>
                <div id="detalleJornada" class="space-y-2">
                <?php if (!empty($datos['fichajesHoy'])): ?>
                    <?php foreach ($datos['fichajesHoy'] as $f): ?>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 fichaje-row" data-tipo="<?php echo $f->tipofichaje; ?>" data-fechahora="<?php echo $f->fechahora; ?>">
                            <span class="font-medium <?php echo $f->tipofichaje === 'entrada' ? 'text-green-600' : 'text-red-600'; ?>">
                                <i class="fas <?php echo $f->tipofichaje === 'entrada' ? 'fa-sign-in-alt' : 'fa-sign-out-alt'; ?> mr-2"></i>
                                <?php echo ucfirst($f->tipofichaje); ?>
                            </span>
                            <span class="text-gray-600"><?php echo date('H:i', strtotime($f->fechahora)); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <?php if ($datos['jornada'] && $datos['jornada']->horastotales > 0): ?>
                        <div id="totalHoras" class="flex justify-between items-center py-2 mt-2 font-bold text-gray-700 border-t border-gray-200 pt-3">
                            <span>Total horas:</span>
                            <span><?php echo $datos['jornada']->horastotales; ?>h</span>
                        </div>
                    <?php endif; ?>
                <?php elseif ($datos['jornada']): ?>
                    <p class="text-gray-500 text-sm">Sin fichajes registrados aún.</p>
                <?php else: ?>
                    <p class="text-gray-500 text-sm">Aún no ha iniciado la jornada de hoy.</p>
                <?php endif; ?>
                </div>
            </div>
        </div>

        <?php endif; ?>
    </main>
</div>

<input type="hidden" id="urlFichar" value="<?php echo RUTA_URL; ?>/ControlHorario/registrarFichaje">
<input type="hidden" id="urlEstado" value="<?php echo RUTA_URL; ?>/ControlHorario/obtenerEstadoActual">

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>