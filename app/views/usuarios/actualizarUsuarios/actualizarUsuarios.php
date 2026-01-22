<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php');  ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">

    <main class="w-full flex-grow p-6">        
        <!-- ****** AQUI DENTRO EL CONTENIDO DE CADA PAGINA ****** -->
       
        <div class="flex items-center justify-center  mt-8">
            <div class="grid bg-white rounded-lg shadow-xl w-11/12">
                <div class="mt-6 ml-6">
                    <h2 class="text-2xl font-semibold leading-tight flex-1 mr-2">Editar Usuario</h2>    
                    <span id="msgValidaCliente" class="font-bold font-bold text-pink-600"></span>                                         
                </div>
                <form id="formAltaUsuario" method="POST" action="<?php echo RUTA_URL; ?>/Usuarios/editarUsuario">
                    <input type="hidden" id="id" value="<?php echo $datos['usuario']->id;?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-5 md:gap-8 mt-5 mx-7">
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Nombre</label>
                            <input type="text" name="nombre" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Nombre" value="<?php print($datos['usuario']->nombre); ?>" required/>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Apellidos</label>
                            <input type="text" name="apellidos" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="Apellidos" value="<?php print($datos['usuario']->apellidos); ?>" required/>
                        </div>
                    
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Mail</label>
                            <input name="correo" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="email" value="<?php print($datos['usuario']->correo); ?>" required/>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Contrase&ntilde;a</label>
                            <input type="password" name="contra" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" type="text" placeholder="contrase&ntilde;a" value="<?php print($datos['usuario']->contra); ?>" required/>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 md:gap-8 mt-5 mx-7">
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Rol</label>
                            <select name="rol" id="rol" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                            <?php
                               
                                    echo"
                                    <option value='".$datos['usuario']->rol."'>".ucwords($datos['nombreRol'])."</option>";                                
                            ?>
                            </select>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Estado</label>
                            <select name="estado" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                            <?php 
                                switch ($datos['usuario']->estado) {
                                    case 0: ?>
                                        <option value="<?php echo $datos['usuario']->estado;  ?>" selected>Inactivo</option>
                                        <option value="1">Activo</option>
                                        
                                    <?php  break;
                                    case 1: ?>
                                        <option value="0">Inactivo</option>
                                        <option value="<?php echo $datos['usuario']->estado;  ?>" selected>Activo</option>
                                        
                                    <?php   break;
                                }
                            ?>
                            </select>
                            <input class="hidden" name="id" value="<?php print($datos['usuario']->id); ?>" />
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="inline-flex items-center">
                                <?php
                                    $checked = '';
                                    if ($datos['usuario']->recibemails == 0) {
                                        $checked = 'checked';
                                    }
                                ?>
                                <input <?php echo  $checked;?> type="checkbox" class="form-checkbox h-4 w-4" name="recibemails">
                                <span class="ml-3 uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Bloquear envío emails</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1">
                            <?php
                                $disabled = '';
                                if ($datos['usuario']->rol ==0) {
                                    $disabled = 'disabled';
                                }
                            ?>
                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Cliente</label>
                            <select name="idcliente" id="idcliente" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 
                            focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" <?php print($disabled);?>>
                                <?php
                                    if ($datos['usuario']->rol == 1) {
                                        echo"<option value='".$datos['usuario']->idcliente."'>".$datos['nombreCliente']."</option>";
                                    }
                                    
                                ?>
                            </select>
                        </div>
                    </div>

                    <?php if ($datos['usuario']->rol ==1) { ?>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 md:gap-8 mt-6 mx-7" id="contenedorSoloClientes">

                            <div class="grid grid-cols-1" id="contenedorClienteTipo" style="height: fit-content;">                    
                                <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Seleccionar cliente tipo</label>
                                <div class="flex flex-col items-left justify-center">
                                    <label class="inline-flex items-center ml-6 my-2">
                                        <input type="radio" class="form-radio text-indigo-600 h-6 w-6 radioType" name="accountType" <?php echo (($datos['usuario']->clientetipo == 'administrador')? 'checked':'');?> value="administrador">
                                        <span class="ml-2">Administrador</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6 my-2">
                                        <input type="radio" class="form-radio text-indigo-600 h-6 w-6 radioType" name="accountType" <?php echo (($datos['usuario']->clientetipo == 'supervisor')? 'checked':'');?> value="supervisor">
                                        <span class="ml-2">Supervisor</span>
                                        <label class="inline-flex items-center ml-2">
                                            <input type="checkbox" class="form-checkbox h-6 w-6 check" id="checkVerTodos" name="checkVerTodos" disabled>
                                            <span class="ml-3 text-lg">Ver todo</span>
                                        </label>
                                    </label>
                                    <label class="inline-flex items-center ml-6 my-2">
                                        <input type="radio" class="form-radio text-indigo-600 h-6 w-6 radioType" name="accountType" <?php echo (($datos['usuario']->clientetipo == 'usuario')? 'checked':'');?> value="usuario">
                                        <span class="ml-2">Usuario</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1" style="height: fit-content;" >
                                <div class="grid grid-cols-1 my-1" id="contenedorClienteSucursales">
                                    <?php
                                        if ($datos['usuario']->clientetipo == 'administrador') {
                                            echo'
                                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold">Este usuario podrá actuar sobre todos las sucursales y equipos</label>';
                                        }else if($datos['usuario']->clientetipo == 'supervisor' || $datos['usuario']->clientetipo == 'usuario'){
                                            echo'
                                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold" >Seleccionar sucursal</label>
                                            <select name="idSucursalCli" id="idSucursalCli" class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" >
                                            <option disabled selected>Seleccionar</option>';
                                            
                                            if (isset($datos['sucursales']) && count($datos['sucursales']) >0 ) {
                                                foreach ($datos['sucursales'] as $sucursal) {
                                                    echo'
                                                    <option value="'.$sucursal->id.'" >'.$sucursal->nombre.'</option>';
                                                }                                            
                                            }
                                            echo'</select>';
                                        }
                                    ?>
                                </div>
                                <div class="grid grid-cols-1 my-1" id="contenedorClienteEquipos">
                                <?php
                                        if($datos['usuario']->clientetipo == 'supervisor' || $datos['usuario']->clientetipo == 'usuario'){
                                            echo'
                                            <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold" >Seleccionar equipos</label>
                                            <select class="py-2 px-3 rounded-lg border border-gray-300 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                                            <option disabled selected>Seleccionar</option>                                         
                                            </select>
                                            ';
                                        }
                                    ?>
                                </div>                                              
                            </div>

                            <div class="grid grid-cols-1" id="contenedorEquiposAsignados">
                                <?php
                                        if($datos['usuario']->clientetipo == 'supervisor' || $datos['usuario']->clientetipo == 'usuario'){
                                ?>
                                     <section class="container mx-auto p-2">
                                        <label class="uppercase md:text-sm text-xs text-gray-500 text-light font-semibold" >Equipos asignados</label>
                                        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                                            <div class="w-full overflow-x-auto">
                                            <table class="w-full" id="tablaListadoEquiposAsignados">
                                                <thead>
                                                <tr class="text-sm font-semibold tracking-wide text-left text-gray-900 bg-gray-100 border-b border-gray-600">
                                                    <th class="px-2 py-2">Nº</th>
                                                    <th class="px-2 py-2">Sucursal</th>
                                                    <th class="px-2 py-2">Equipos</th>
                                                    <th class="px-2 py-2">Eliminar</th>
                                                </tr>
                                                </thead>
                                                <tbody class="bg-white">
                                    <?php
                                        if (isset($datos['equipos']) && count($datos['equipos']) >0) {
                                            foreach ($datos['equipos'] as $equipo) {
                                                echo"
                                                <tr class='text-sm text-gray-700'>
                                                    <td style='width: 8%;' class='px-2 py-2'><input style='width: 90%;' value='".$equipo->id."' name='idEquipoSelected[]'></td>
                                                    <td class='px-2 py-2'>".$equipo->nombresucursal."</td>
                                                    <td class='px-2 py-2'>".$equipo->nombreequipo."</td>
                                                    <td style='width: 8%;' class='px-2 py-2'><a href='' class='eliminarEquipo'><i class='fas fa-user-minus' style='color:red;'></i></a></td>
                                                <tr>
                                                ";
                                            }
                                        }
                                    
                                    ?>                                     
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </section>
                                            
                                <?php
                                        }
                                ?>
                            </div>

                        </div>

                    <?php } ?>
                    
                    <div class='flex items-center justify-center  md:gap-8 gap-4 pt-5 pb-5'>
                        <a href="<?php echo RUTA_URL; ?>/Usuarios" class='w-auto bg-gray-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>Cancelar</a>
                        <button type="submit" class='w-auto bg-violeta-oscuro hover:bg-blue-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>Guardar</button>
                    </div>

                </form>
            </div>
        </div>

        <!-- ****** FIN DEL CONTENIDO DE CADA PAGINA ****** -->
    </main>
</div>

</div>

</main>
<!--Esta etiqueta Main es el fin del sidebar -->



<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>