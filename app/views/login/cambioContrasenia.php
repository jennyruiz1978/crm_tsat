<?php include_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>

<body class="h-screen overflow-hidden flex items-center justify-center" style="background: #edf2f7;">



    <dlm-cambiocontrasenia nombre="Aceptar" root="<?php echo RUTA_URL; ?>" metodo="<?php echo RUTA_URL; ?>/Login/ejecutarCambioContrasenia" 
    imglogo="<?php echo RUTA_URL; ?>/public/img/logo_telesat.jpg" slogan="<?php //echo SLOGAN ;?>" rutalogin="<?php echo RUTA_URL; ?>/Login" idusuario="<?php echo $datos['id'];?>" 
    validez="<?php echo ((isset($datos['horas']))? $datos['horas']: '') ;?>">  
    </dlm-cambiocontrasenia>


    <?php include_once(RUTA_APP . '/views/includes/footer.php'); ?>

