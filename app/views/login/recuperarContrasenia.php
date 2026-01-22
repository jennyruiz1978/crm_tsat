<?php include_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>

<body class="h-screen overflow-hidden flex items-center justify-center" style="background: #edf2f7;">                
    <dlm-olvidocontrasenia  nombre="Enviar" root="<?php echo RUTA_URL; ?>" metodo="/Login/validaEmailParaRecuperarContrasenia" imglogo="<?php echo RUTA_URL; ?>/public/img/logo_telesat.jpg" slogan="<?//php echo SLOGAN ;?>">
    </dlm-olvidocontrasenia>
    
    <?php include_once(RUTA_APP . '/views/includes/footer.php'); ?>

