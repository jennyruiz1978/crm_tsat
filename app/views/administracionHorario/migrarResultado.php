<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">
    <main class="w-full flex-grow p-4 md:p-6">
        <h2 class="text-2xl font-semibold leading-tight mb-4">Migración: Configuración Horario</h2>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-3 text-green-600">
                <i class="fas fa-check-circle mr-2"></i>Usuarios creados en configuracionhorario (<?php echo count($datos['creados']); ?>)
            </h3>
            <?php if (count($datos['creados']) > 0): ?>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Nombre</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Apellido</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Rol</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">debeFichar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos['creados'] as $u): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm"><?php echo $u->id; ?></td>
                                <td class="px-4 py-2 text-sm"><?php echo $u->nombre; ?></td>
                                <td class="px-4 py-2 text-sm"><?php echo $u->apellidos; ?></td>
                                <td class="px-4 py-2 text-sm"><?php echo $u->rol; ?></td>
                                <td class="px-4 py-2 text-sm text-yellow-600 font-semibold">0 (inactivo)</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500 text-sm">No se crearon nuevos registros.</p>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-3 text-blue-600">
                <i class="fas fa-info-circle mr-2"></i>Usuarios omitidos (ya tenían registro) (<?php echo count($datos['omitidos']); ?>)
            </h3>
            <?php if (count($datos['omitidos']) > 0): ?>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Nombre</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Apellido</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos['omitidos'] as $u): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm"><?php echo $u->id; ?></td>
                                <td class="px-4 py-2 text-sm"><?php echo $u->nombre; ?></td>
                                <td class="px-4 py-2 text-sm"><?php echo $u->apellidos; ?></td>
                                <td class="px-4 py-2 text-sm"><?php echo $u->rol; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500 text-sm">No había registros previos.</p>
            <?php endif; ?>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <p class="font-semibold text-yellow-800"><i class="fas fa-exclamation-triangle mr-2"></i>Acciones post-migración</p>
            <ul class="text-sm text-yellow-700 mt-2 list-disc list-inside">
                <li>Activar <strong>debeFichar</strong> para cada usuario desde <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/configuracionHorario" class="underline text-blue-600">Configuración de Horario</a></li>
                <li>Cerrar sesión y volver a logar para que los cambios en CHOVA surtan efecto</li>
                <li><strong>ELIMINAR</strong> el método <code>migrarConfigHorario()</code> de <code>AdministracionHorario.php</code></li>
                <li><strong>ELIMINAR</strong> la vista <code>migrarResultado.php</code> de <code>app/views/administracionHorario/</code></li>
                <li><strong>ELIMINAR</strong> el permiso <code>/AdministracionHorario/migrarConfigHorario</code> de <code>rolesbase.permisos</code> (rol admin, id=1)</li>
            </ul>
        </div>

        <a href="<?php echo RUTA_URL; ?>/AdministracionHorario/configuracionHorario" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>Ir a Configuración de Horario
        </a>
    </main>
</div>

<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>