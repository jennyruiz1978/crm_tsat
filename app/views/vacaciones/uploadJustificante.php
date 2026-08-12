<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold leading-tight">Subir Justificante</h2>
            <a href="<?php echo RUTA_URL; ?>/Vacaciones/misVacaciones" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
            <?php if (isset($datos['vacacion'])): ?>
                <div class="mb-4 p-3 bg-gray-50 rounded">
                    <p class="text-sm text-gray-500">Solicitud #<?php echo $datos['vacacion']->id; ?></p>
                    <p class="font-medium"><?php echo ucfirst($datos['vacacion']->tipovacacion); ?>: <?php echo date('d/m/Y', strtotime($datos['vacacion']->fechainicio)); ?> - <?php echo date('d/m/Y', strtotime($datos['vacacion']->fechafin)); ?></p>
                </div>

                <?php if ($datos['vacacion']->ficherojustificante): ?>
                    <div class="mb-4 p-3 bg-blue-50 rounded">
                        <p class="text-sm text-blue-700"><i class="fas fa-file-alt mr-1"></i> Ya tiene un justificante adjunto.</p>
                        <a href="<?php echo $datos['vacacion']->ficherojustificante; ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                            Ver justificante actual
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form method="POST" action="<?php echo RUTA_URL; ?>/Vacaciones/subirJustificante/<?php echo $datos['vacacion']->id ?? ''; ?>" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Seleccionar archivo</label>
                    <input type="file" name="justificante" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="w-full border rounded px-3 py-2 text-sm" required>
                    <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, JPG, PNG, DOC, DOCX. Máximo 5MB.</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-violeta-oscuro text-white px-6 py-2 rounded text-sm hover:bg-purple-800">
                        <i class="fas fa-upload mr-1"></i> Subir justificante
                    </button>
                    <a href="<?php echo RUTA_URL; ?>/Vacaciones/misVacaciones" class="bg-gray-500 text-white px-6 py-2 rounded text-sm hover:bg-gray-600">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>