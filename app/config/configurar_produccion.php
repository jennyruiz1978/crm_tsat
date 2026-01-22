<?php

// configuracion acceso a base de datos producción


//define('DB_HOST','localhost');

define('DB_HOST','45.154.57.109');
define('DB_USUARIO','crm_telesat');
define('DB_PASSWORD','J2t3c7%o');
define('DB_NOMBRE','crm_telesat');


// Ruta de la aplicacion
define('RUTA_APP', dirname(dirname(__FILE__)));

define('RUTA_URL','https://crm.telesat.infomalaga.es');
// NOMBRE DEL SITIO
define('NOMBRE_SITIO', 'TELESAT');
// RUTA CONTROL PERMISOS
define("RUTA_PERMISOS","");
// COLOR DEL FONDO DEL NAV BAR
define('BG_NAVBAR', 'bg-violeta-oscuro');
// COLOR DEL FONDO DEL SIDE BAR
define('BG_SIDEBAR', 'bg-blue-500');
// COLOR DE FONDO SUBMENU HOVER
define('BG_SUBMENU_HOVER', 'bg-blue-800');
//Ruta para subida de ficheros:
define("DOCUMENTOS_PRIVADOS", RUTA_APP."/documentos/");

//Ruta para subir ficheros en public en produccion
define("DOCS_INCIDENCIAS", $_SERVER['DOCUMENT_ROOT'] . '/public/documentos/Incidencias/');
//Ruta para subir ficheros equipos en public en produccion
define("DOCS_EQUIPOS", $_SERVER['DOCUMENT_ROOT'] . '/public/documentos/Equipos/');
//Ruta para subir ficheros trabajos terminados en public en produccion
define("DOCS_TRABAJOS_TERMINADOS", $_SERVER['DOCUMENT_ROOT'] . '/public/documentos/TrabajosTerminados/');

//slogan del logo
define("SLOGAN", "");



define("NOMBRE_FISCAL_INFOMALAGA", "TECNOLOGIAS APLICADAS TELESAT SL");
define("NIF_INFOMALAGA", "B93289254");
define("DIRECCION_INFOMALAGA", "Avenida Joan Miró, Nº 37");
define("CODIGO_POSTAL_INFOMALAGA", "29620");
define("LOCALIDAD_INFOMALAGA", "Torremolinos");
define("PROVINCIA_INFOMALAGA", "Màlaga ");
define("TELEFONO", "952388790 | 607766741");
define("TELEFONO_FIJO", "952388790");
define("TELEFONO_MOVIL", "607766741");

//cuenta correo para envío
define("CUENTA_CORREO", "info@instalacionestelesat.es");

//cuenta correo administración infomálaga 1
define("CUENTA_CORREOADMINISTRACION1", "");

//cuenta correo administración infomálaga 2
define("CUENTA_CORREOADMINISTRACION2", "");

//password correo
define("PASSWORD_CORREO", "Correo1@53521");

//host correo
define("HOST_CORREO", "instalacionestelesat-es.correoseguro.dinaserver.com");

//puerto config correo
define("PUERTO", 587); //produccion

//protocolo
define("PROTOCOLO", "tls"); //produccion



define("TIPO_IVA_DEFAULT",21);


//Tipos de error
define("ERROR_CREACION", "Se ha producido un error y no se ha creado el registro.");
define("OK_CREACION", "Se ha creado el registro corréctamente.");
define("ERROR_ACTUALIZACION", "Se ha producido un error y no se ha guardado el registro.");
define("OK_ACTUALIZACION", "Se ha actualizado el registro corréctamente");
define("ERROR_ELIMINACION", "No se ha eliminado el registro.");
define("OK_ELIMINACION", "Se ha eliminado el registro.");
define("OK_GUARDADO", "Se han guardado los datos corréctamente.");
define("ERROR_GUARDADO", "Se ha producido un error y no se han guardado los datos.");
define("ERROR_FORM_INCOMPLETO", "No se puede guardar el registro porque faltan datos en el formulario.");
define("ERROR_DOESNT_EXIST", "No hay datos para la consulta");

define("EMPRESA", "TELESAT");

define("GARANTIA", "La Garantía de la instalación es de un mes y del material es de dos años, siempre y cuando no sea manipulada por personal ajeno a nuestra empresa. ESTA FACTURA NO INCLUYE MANO DE OBRAPOSTERIOR ASU INSTALACIÓN");

define("INSCRIPCION", "Tecnologías Aplicadas Telesat S.L. inscrita en el Registro Mercantil Tomo 5209, Libro 4116, Folio 98, Hoja MA-120725, Inscripción 1");