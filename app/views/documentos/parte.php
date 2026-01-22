
    <style type="text/css">

     

        #datosEmpresas,
        #numFactura,
        #contenido {
            border-collapse: collapse;
            width: 100%;
        }

        .pie {
            background-color: #00BCD4;
            margin-top: 20px;
            height: 50px;
        }

        #datosEmpresas td,
        #numFactura td,
        #contenido td {
            border: 1px solid #ddd;
            padding: 4px;
        }

        #numFactura th,
        #datosEmpresas th,
        #contenido th {
            border: 1px solid #ddd;
            padding: 4px;
            background-color: #cce2fe;
        }

        .titulo {
            font-size: 18px;
            font-weight: 1000;
        }

        .contenedor {
            margin-bottom: 20px;
            margin-right: 20px;
            
        }
     

        .izquierda {
            text-align: left;
        }

        .derecha {
            text-align: right;
        }

        td.informa {
            width: 430px;
            text-align: left;
        }

        td.cliente {
            width: 280px;
            text-align: left;
        }

       

        td.subtitulo,
        th.subtitulo {
            width: 150px;
            font-size: 14px;            
        }

        td.subtitulo_cabecera_derecha,
        th.subtitulo_cabecera_derecha {
            width: 150px;
            font-size: 14px;            
            text-align: left;
        }

        td.subtitulo_cabecera_izquierda,
        th.subtitulo_cabecera_izquierda {
            width: 50px;
            font-size: 14px;            
        }

        th.vacio {
            width: 75px;
        }

        .imagen {
            width: 320px;
        }

        th.conceptoCol,
        td.conceptoCol {
            width: 240px;
        }

        th.importeCol,
        td.importeCol {
            width: 65px;
        }
        td.importeColMini,
        th.importeColMini{
            width: 35px;
        }

        th.importeCol{
            text-align: center;
        }

        .rgpd {
            font-size: 10px;
            margin-left: 40px;
            margin-right: 40px;
            color: #606060;
            margin-top: 5px;
            margin-bottom: 60px;        
            text-align: justify;
        }

        .cuerpo{
            top: 80px;
        }

        .subrayado {
            border-bottom: solid 3px blue;
        }

        .principal {
            margin-left: 34px;  
            font-size: 13px;          
        }

        .contLogo {
            margin-left: 70px;                      
        }

        .divisor {
            width: 710px;
            margin-left: 34px;
            border: 2px solid #048bd3;
        }
        .cont_logo{                                    
            width: 180px;
            height: auto;
            position:absolute;
            left: 25px;
            top: 0px;            
        }
        img.logo_documento{
            width: 100%;
        }
        .contTextoslogo {
            margin-left: 35px;  
                                      
        }
        .texto-logo-derecha{
            font-size: 10px;         
            width: 40px;   
        }
        
        .texto-logo-izquierda{
            font-size: 10px;         
            width: 200px;   
        }

        .descripcionInc {            
            margin-right: 40px;
            text-align: justify;
        }

        .cont_img_inc{                                    
            width: 180px;
            height: auto;            
            left: 25px;
            
        }

        .box_img{
            width: 600px;
            margin-left: 33px;   
            margin-top: 20px;
        }

        .all_images{
            margin: 40px;            
        }

        
        .firma_container{
            width: 280px;
            height: 150px;
            border: 1px solid gray;
        }
        
        p.texto_comentario{
            padding: 0px;            
            margin-top: 10px;
            margin-bottom: 4px;
            font-size: 13px;            
        }

        .title_comentarios{
            margin-left: 33px;
        }
        .span_fecha_user{
            font-size: 10px;
            font-style: italic;
        }

        p.par_fichero{
            padding: 0px;            
            margin-top: 10px;
            margin-bottom: 4px;
            font-size: 13px;            
            width: 200px;         
        }

        .imgIncidenciaComentario{            
            margin-left: 33px;           
        }

        .bloque_comentario{
            margin-left: 33px;
            margin-right: 37px;            
            text-align: justify;
        }

    </style>
    
    <!--<page style="font-family: Arial, sans-serif;"  footer='page' backtop="20mm">-->
    <?php

        $idIncidencia = '';
        $title = '';

        if(isset($datos['detalles'])){
            $detalles = $datos['detalles'];

            $idIncidencia = $detalles->id;
            $title = 'Nº '. $idIncidencia;    
            $sucursal = $detalles->id;       
        }

        $imagenes = '';
        if($datos['imagenes'] && count($datos['imagenes'])>0 ){
            $imagenes = $datos['imagenes'];
        }

        $documentos = '';
        if($datos['documentos'] && count($datos['documentos'])>0 ){
            $documentos = $datos['documentos'];
        }         
    ?>
        <!-- <div class="main"> -->

            <div class="cont_logo">
                <img class="logo_documento" src="<?php echo $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']);  ?>/img/logo_telesat.jpg">
            </div>
            

            <table class="contLogo">
                <tr>
                    <td rowspan='11' class='imagen'></td>
                    <td class='titulo' style="text-transform:uppercase;">
                        <b>PARTE DE INCIDENCIA</b>
                    </td>
                    <td class='titulo'></td>
                </tr>
                <?php
                
                echo "
                    <tr>
                        <td class='subtitulo_cabecera_izquierda'><b>  Nº : " . $idIncidencia . "</b></td>
                        <td class='subtitulo_cabecera_derecha'></td> 
                    </tr>";
                    
                ?>
            </table>

            <br><br>

            <table class="contTextoslogo">
                <tr>
                    <td class="texto-logo-derecha">Teléfonos</td>
                    <td>:</td>
                    <td class="texto-logo-izquierda">  <?php echo TELEFONO; ?> </td>
                </tr>       
                <tr>
                    <td class="texto-logo-derecha">Email</td>
                    <td>:</td>
                    <td class="texto-logo-izquierda">  info@instalacionestelesat.es</td>
                </tr>                      
            </table>

            <br>

            <div class="divisor"></div>
            
            <div class="principal">
                <?php
                    
                    echo "                        
                            <div class='contenedor'>          
                            
                                <br>

                                <table id='datosEmpresas'>
                                    
                                    <tbody>
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Fecha registro</th>
                                            <td class='informa'>".$detalles->fecha."</td>
                                            
                                        </tr>
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Solicitante</th>
                                            <td class='informa'>".$detalles->nombreusuario."</td>
                                            
                                        </tr>
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Cliente</th>
                                            <td class='informa'>".$detalles->nombrecliente."</td>
                                           
                                        </tr>
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Sucursal</th>
                                            <td class='informa'>".$detalles->nombresucursal."</td>
                                            
                                        </tr>
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Equipo</th>
                                            <td class='informa'>".$detalles->nombreequipo."</td>
                                            
                                        </tr>  
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Estado</th>
                                            <td class='informa'>".$detalles->nombreestado."</td>
                                        </tr> 
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Técn. Asig.</th>
                                            <td class='informa'>".$detalles->nombrestecnicos."</td>
                                        </tr> 
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Marca</th>
                                            <td class='informa'>".$detalles->marca."</td>
                                        </tr>
                                        
                                        <tr>
                                            <th class='conceptoCol' scope='row'>Serie</th>
                                            <td class='informa'>".$detalles->serie."</td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table>

                            </div>
                            
                            <p style='margin-bottom:0px;'><b>Descripción: </b></p>
                            <p class='descripcionInc'>". $detalles->descripcion . "</p>";
                ?>
            </div>                    

            <br>


            <!--Imágenes y ficheros -->                      
            
                    <p class="title_comentarios"><b>Imágenes y ficheros adjuntos: </b></p>
                    <?php                                               

                        if ($imagenes && $imagenes !='') {                                                       
                                
                                foreach ($imagenes as $key) {                                    

                                    $rutaImagenI = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF'])."/documentos/Incidencias/".$key->nombre;

                                    list($anchoI, $altoI) = getimagesize($rutaImagenI);

                                    $estiloImagenI = '';

                                    if ($anchoI > $altoI) {
                                        $estiloImagenI = 'width: 400px; height: auto;';
                                    } elseif ($anchoI < $altoI) {
                                        $estiloImagenI = 'height: 600px; width: auto;'; 
                                    } else {
                                        $estiloImagenI = 'width: 400px; height: auto;'; 
                                    }      

                                    if (strpos($key->tipo, 'image/') === 0) {
                                        echo "<div class='box_img'><img class='imgIncidencia' style='" . $estiloImagenI . "' src='".$_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF'])."/documentos/Incidencias/".$key->nombre."' /></div>";
                                    } else {                                        
                                        echo "<div class='par_fichero bloque_comentario'><p>" . $key->nombre . "<i class='fas fa-download ml-2'></i></p></div>";
                                    }

                                }                                              
                        }                 

                    ?>

            <br>
                        
            <!--Comentarios-->
            <?php
                if(!empty($datos['comentarios'])){
                    $comentarios = $datos['comentarios'];

                    $ficherosComentarios = $datos['ficherosComentarios'];

                    $tiposImagen = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                    echo'<p class="title_comentarios"><b>Comentarios: </b></p>'; 
                            
                        foreach ($comentarios as $comentario) {                                   
                            
                            echo'
                                
                                    <p class="texto_comentario bloque_comentario">'.$comentario->comentario.'</p>
                                    <span class="bloque_comentario span_fecha_user">'.date('d/m/Y H:i',strtotime($comentario->fechacreacion)).' por '.$comentario->nombreusuario.'</span><p></p>';

                                    if (!empty($ficherosComentarios)) {
                                            foreach ($ficherosComentarios as $fichero) {

                                                if($fichero->idcomentario == $comentario->id){                                                           
                                                    if (in_array($fichero->tipo, $tiposImagen)) {

                                                        $rutaImagen = $_SERVER["DOCUMENT_ROOT"].dirname($_SERVER['PHP_SELF']).'/documentos/TrabajosTerminados/'.$fichero->nombre;

                                                        if (file_exists($rutaImagen)) {

                                                            list($ancho, $alto) = getimagesize($rutaImagen);

                                                            $estiloImagen = '';

                                                            if ($ancho > $alto) {
                                                                $estiloImagen = 'width: 500px; height: auto;';
                                                            } elseif ($ancho < $alto) {
                                                                $estiloImagen = 'height: 700px; width: auto;'; 
                                                            } else {
                                                                $estiloImagen = 'width: 500px; height: auto;'; 
                                                            }                                                        
                                                            
                                                            echo '<img class="imgIncidenciaComentario" style="' . $estiloImagen . '" src="' . $_SERVER["DOCUMENT_ROOT"] . dirname($_SERVER['PHP_SELF']) . '/documentos/TrabajosTerminados/' . $fichero->nombre . '" alt="' . $fichero->nombre . '"/><p></p><p class="par_fichero bloque_comentario">Imagen: '.$fichero->nombre.'</p>';
                                                                
                                                        } else {                                                            
                                                            echo '<p class="par_fichero bloque_comentario">La imagen no existe: ' . $rutaImagen . '</p>';
                                                        }                                                                                                                                                                     
                                                    }else{
                                                        echo'<p class="par_fichero bloque_comentario">Fichero: '.$fichero->nombre.'</p>';
                                                    }                                                   
                                                }

                                            }                                                       
                                    }                                    
                                    
                                }                                                        
                }    
            ?>


            <?php
                if(!empty($detalles->guardada) && $detalles->guardada == 1){
            ?>


            <!--firma-->

            <div class="principal">
                <div class="mr-2">
                    <div class="inline-flex my-3">   
                        <label class="uppercase text-sm xl:text-base text-gray-500 text-light font-semibold lg:mt-1 mr-3"><b>Firma</b></label>
                    </div>
                </div>
                <br>
                <div class="rounded-lg firma_container">                                                             
                    <img id="imagenFirma" class="border-2 border-coolGray-300" src="<?php echo $detalles->firma;?>" />                        
                </div>
            </div>

            <?php
                }
            ?>



        <!-- </div> -->
           