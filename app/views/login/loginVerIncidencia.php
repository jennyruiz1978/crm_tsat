<?php include_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>

<body class="h-screen overflow-hidden flex items-center justify-center" style="background: #edf2f7;">


    <dlm-loginverincidencia nombre="Aceptar" root="<?php echo RUTA_URL; ?>" 
    imglogo="<?php echo RUTA_URL; ?>/public/img/logo_telesat.jpg" slogan="<?php //echo SLOGAN ;?>" 
    idusuario="<?php echo $datos['idUsuario'];?>" idincidencia="<?php echo $datos['idIncidencia'];?>" email="<?php echo $datos['email'];?>">  
    </dlm-loginverincidencia>
    

    <?php include_once(RUTA_APP . '/views/includes/footer.php'); ?>

