<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>

<div class="w-full overflow-x-hidden border-t flex flex-col">

    <main class="w-full flex-grow p-6">


    <?php require_once(RUTA_APP . '/views/usuarios/componentes/tabla-usuarios.php'); ?>

    <?php require_once(RUTA_APP . '/views/usuarios/componentes/modalActualizarUsuario.php'); ?>
    <?php require_once(RUTA_APP . '/views/usuarios/componentes/modalPermisosUsuario.php'); ?>

        <!-- ****** FIN DEL CONTENIDO DE CADA PAGINA ****** -->
        </main>
</div>

</div>

</main>
<!--Esta etiqueta Main es el fin del sidebar -->
<script>
    // INICIO ACTUALIZAR DATOS DE USUARIO 
    /*     var actualizar = document.getElementById('actualizar');
        var btnActualizar = document.getElementsByClassName('btnActualizar');
        //rrecorremos todos los iconos de actualizar para que carguen el modal
        for(var i = 0;i < btnActualizar.length; i++){
            btnActualizar[i].addEventListener("click",function(e){
            e.preventDefault();
            actualizar.classList.remove('hidden');
            actualizar.classList.add('flex');
         }); // fin del addEventListener
        } // fin del if
        
        // cerramos el modal con la x de la parte superior derecha
        let cerrarActualizar = document.getElementById('cerrarActualizar');
        cerrarVentana(cerrarActualizar,actualizar);
        // cerramos el modal con el boton cancelar del modal
        let btnCancelar = document.getElementById('btn-cancelar-actualizar');
           cerrarVentana(btnCancelar,actualizar);
        // funcion para cerrar la ventana y reutilizar
        function cerrarVentana(boton,actualizar) {
            boton.addEventListener('click',function(){
            actualizar.classList.remove('flex');
            actualizar.classList.add('hidden');
            });
        }; // fin funcion cerrarVentana
    // FIN ACTUALIZAR DATOS DE USUARIO

    // INICIO ACTUALIZAR PERMISOS DE USUARIO 
        var permisos = document.getElementById('permisos');
        var btnPermisos = document.getElementsByClassName('btnPermisos');
        //rrecorremos todos los iconos de actualizar para que carguen el modal
        for(var i = 0;i < btnPermisos.length; i++){
            btnPermisos[i].addEventListener("click",function(e){
            e.preventDefault();
            permisos.classList.remove('hidden');
            permisos.classList.add('flex');
         }); // fin del addEventListener
        } // fin del if
        
        // cerramos el modal con la x de la parte superior derecha
        let cerrarPermisos = document.getElementById('cerrarPermisos');
            cerrarVentana(cerrarPermisos,permisos);
        // cerramos el modal con el boton cancelar del modal
        let btnCancelarPermisos = document.getElementById('btn-cancelar-permisos');
           cerrarVentana(btnCancelarPermisos,permisos);
    // FIN ACTUALIZAR PERMISOS DE USUARIO
     */
	</script>


<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>


<!-- </body>
</body>
</html> -->
