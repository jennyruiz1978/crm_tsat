# Diagnóstico del envío de correo con info@instalacionestelesat.es

Fecha: 02/10/2026
Sistema: CRM Telesat (XAMPP local, PHP 8.1.25, PHPMailer 6.0.7)

---

## 1. Descripción del problema

El CRM no envía correos cuando está configurado con la cuenta de producción `info@instalacionestelesat.es` (servidor Dinaserver). Con la cuenta de prueba Gmail (`automatizotunegocioinfo@gmail.com`) el envío funciona correctamente con el mismo código, lo que descarta un problema en la aplicación.

## 2. Configuración en juego (`app/config/configurar.php`)

| Constante | Valor (producción, falla) | Valor (test Gmail, funciona) |
|---|---|---|
| `CUENTA_CORREO` | `info@instalacionestelesat.es` | `automatizotunegocioinfo@gmail.com` |
| `PASSWORD_CORREO` | `Correo1@53521` | contraseña de aplicación Gmail |
| `HOST_CORREO` | `instalacionestelesat-es.correoseguro.dinaserver.com` | `smtp.gmail.com` |
| `PUERTO` | 587 | 465 |
| `PROTOCOLO` | `tls` (STARTTLS) | `ssl` (TLS implícito) |

## 3. Archivos modificados para las pruebas de correo

### 3.1 `app/config/configurar.php` (:97-98)

Nueva constante para controlar el nivel de diagnóstico SMTP sin tocar código:

```php
//nivel de debug SMTP en envíos de correo: 0 = sin debug, 3 = transcripción completa (diagnóstico)
define("SMTP_DEBUG_NIVEL", 3);
```

- Con `3` (actual): captura la transcripción completa de la sesión SMTP.
- **Pendiente: bajar a `0` cuando se resuelva el incidente** (el log de fallos sigue activo con `0`).

### 3.2 `app/librerias/enviarEmail.php` — método `enviarEmailDestinatario()` (3 cambios)

1. **Líneas 46-50** — Activación del debug SMTP con captura silenciosa. La transcripción de la
   conversación SMTP se acumula en `$GLOBALS['smtpDebugLog']` mediante un callback
   (`Debugoutput`), sin imprimir nada por pantalla. La app funciona normal para el usuario.

   ```php
   $mail->SMTPDebug = SMTP_DEBUG_NIVEL;
   $GLOBALS['smtpDebugLog'] = '';
   $mail->Debugoutput = function($str, $level) {
       $GLOBALS['smtpDebugLog'] .= $str . "\n";
   };
   ```

2. **Líneas 88-93** — Si el envío falla (`!Send()`), se registra en `app/logs/email_errors.log`
   el error de PHPMailer (`ErrorInfo`) junto con la transcripción completa de la sesión SMTP:

   ```php
   $logMsg = date('Y-m-d H:i:s') . " | ERROR ENVIO EMAIL | ErrorInfo: " . $mail->ErrorInfo
           . " | Debug: " . $GLOBALS['smtpDebugLog'] . "\n";
   file_put_contents(RUTA_APP . '/logs/email_errors.log', $logMsg, FILE_APPEND);
   ```

3. **Líneas 104-109** — También se registran las excepciones (fallos de conexión/TLS que no
   llegan al `Send()`), con el mismo formato, antes del `return $exception->getMessage()`.

### 3.3 Archivos NO modificados (por decisión de alcance)

- `app/librerias/enviarEmail.php` → método `enviarEmailDocumento()` (facturas/PDFs adjuntos):
  queda **sin instrumentar**. Si un envío de factura/parte PDF falla, seguirá siendo silencioso.
  Puede extenderse con el mismo patrón (debug + log en `!Send()` y en `catch`) si se necesita.
- Controladores y vistas: sin cambios. Cualquier envío de la app queda diagnosticado desde la librería.
- `app/logs/`: directorio existente donde se escribe `email_errors.log` (solo se escribe si falla).

## 4. Evidencia técnica recopilada

### 4.1 Transcripción SMTP del fallo (`app/logs/email_errors.log`, 02/10/2026 07:03:57)

```
Connection: opened
SERVER -> CLIENT: 220 hl1263.dinaserver.com SMTP Ready          <- Conexión OK
CLIENT -> SERVER: EHLO localhost
SERVER -> CLIENT: 250-... 250-STARTTLS 250-AUTH PLAIN LOGIN      <- Servidor ofrece STARTTLS y autenticación
CLIENT -> SERVER: STARTTLS
SERVER -> CLIENT: 220 2.0.0 Ready to start TLS                  <- Cifrado TLS OK (NO es un problema de TLS)
CLIENT -> SERVER: EHLO localhost                                  <- Segundo EHLO sobre canal cifrado OK
CLIENT -> SERVER: AUTH LOGIN
SERVER -> CLIENT: 334 VXNlcm5hbWU6                                 <- Servidor ACEPTA el usuario, pide password
CLIENT -> SERVER: <credentials hidden>
SERVER -> CLIENT: 535 5.7.8 Error: authentication failed: (reason unavailable)   <- RECHAZA LA CONTRASEÑA
SMTP Error: Could not authenticate.
CLIENT -> SERVER: QUIT
SERVER -> CLIENT: 221 2.0.0 Bye
Connection: closed
SMTP connect() failed.
```

Nota: el log registró 2 entradas, una por técnico notificado (jennyruizrazuri@gmail.com y
jcarolina141278@hotmail.com) — un intento por destinatario, confirmando que el envío
individual funciona correctamente.

### 4.2 Verificaciones adicionales

| Verificación | Resultado |
|---|---|
| Conectividad TCP al servidor (puertos 587 y 465) | Correcta en ambos (IP 82.98.171.96) |
| Resolución DNS: MX del dominio (`mail.instalacionestelesat.es`) vs host configurado | Ambos apuntan a la misma IP (82.98.171.96) — hablamos con el servidor correcto |
| Credenciales en `configurar.php` | Caracteres exactos verificados: `Correo1@53521` — ASCII puro, sin espacios ni caracteres ocultos |
| Mismo código con cuenta Gmail | Funciona perfectamente -> la librería y el código del CRM están bien |

## 5. Conclusión del diagnóstico

**La conexión, el cifrado TLS y el protocolo funcionan correctamente. El servidor de Dinaserver
acepta el usuario pero rechaza la contraseña** con el error `535 5.7.8 authentication failed`.
El problema está en el **lado del proveedor de correo (Dinaserver)**, no en el CRM.

Dado que el cliente confirma que las credenciales son correctas, la explicación más probable es
que **la contraseña sea válida para el webmail (IMAP) pero la cuenta no tenga habilitada la
autenticación SMTP para clientes externos**, o que exista una **restricción de relay por IP**
(el envío se prueba desde una conexión local, no desde el servidor de hosting).

## 6. Posibles causas (lado servidor) y cómo verificarlas

| # | Causa | Verificación |
|---|---|---|
| 1 | SMTP AUTH no habilitado para la cuenta (contraseña válida solo para webmail/IMAP) | Preguntar a Dinaserver: "¿Está habilitado SMTP AUTH para info@instalacionestelesat.es?" |
| 2 | Restricción de relay: el servidor solo acepta SMTP desde IPs autorizadas | Preguntar si hay bloqueo de relay por IP; probar el CRM desde el hosting de producción |
| 3 | Cuenta requiere activar "clientes externos" en el panel | Revisar el panel correoseguro de Dinaserver |
| 4 | La cuenta exige otro formato de usuario (solo `info` en vez del email completo) | Probar con `Username = info` (cambio de 1 línea) |
| 5 | Contraseña copiada con error (carácter `@` similar a otro) | Re-escribir manualmente o resetear desde el panel |

## 7. Respuesta para el cliente

**Asunto: Diagnóstico del envío de correos del CRM con la cuenta info@instalacionestelesat.es**

Hemos completado el diagnóstico técnico del problema de envío de correos desde el CRM. Le explicamos la situación:

**Por qué no se envían los correos:**

El CRM se conecta correctamente al servidor de correo de su proveedor (Dinaserver): la conexión se establece, el cifrado de seguridad (TLS) funciona, y el servidor acepta el nombre de usuario. Sin embargo, en el momento de validar la contraseña, el servidor de correo **la rechaza** con el error `535 5.7.8 authentication failed`. Es decir, el servidor de correo está rechazando las credenciales de la cuenta para el envío por SMTP.

Hemos verificado que no es un problema del CRM: con otra cuenta de correo de prueba (Gmail) el sistema envía perfectamente, la configuración del CRM contiene las credenciales exactamente como nos fueron facilitadas, y la comunicación con el servidor llega hasta el paso de validación de contraseña.

**Qué significa esto:**

Aunque las credenciales sean correctas para consultar el correo por webmail, muchos proveedores (en este caso Dinaserver/correoseguro) mantienen **activado por separado** el envío por SMTP desde aplicaciones externas. Las causas más habituales son: la cuenta no tiene habilitado el "envío SMTP desde clientes externos", o el proveedor restringe el envío a conexiones autorizadas por política antispam.

**Qué se puede hacer:**

1. **Contactar con el soporte de Dinaserver** con este mensaje (técnico, para que lo atiendan directamente): *"Al conectar por SMTP a instalacionestelesat-es.correoseguro.dinaserver.com:587 con STARTTLS, el usuario info@instalacionestelesat.es es aceptado pero la autenticación devuelve '535 5.7.8 Error: authentication failed'. Conexión y STARTTLS funcionan correctamente. ¿Está habilitado SMTP AUTH para esta cuenta? ¿Existe restricción de relay por IP? ¿Las credenciales SMTP coinciden con las de webmail?"*
2. **Verificar en el panel de control del correo** (correoseguro de Dinaserver) si hay una opción de "permitir envío desde clientes externos" o similar y activarla.
3. **Probar el envío desde el CRM instalado en el hosting de producción** (si existía antes y funcionaba, la restricción sería solo para conexiones externas como nuestro entorno local).
4. **Mientras tanto**, el CRM puede seguir enviando correos con la cuenta de prueba ya configurada (Gmail) sin ningún cambio adicional, de forma que la operativa no se detenga.

En cuanto el proveedor confirme que el SMTP de la cuenta está habilitado (o facilite las credenciales SMTP correctas), la puesta en marcha de la cuenta info@instalacionestelesat.es es inmediata — solo requiere actualizar la contraseña/host en la configuración, sin desarrollo adicional.

## 8. Acciones pendientes

| # | Acción | Responsable |
|---|---|---|
| 1 | Consultar a Dinaserver (mensaje técnico de la sección 7, punto 1) | Cliente / Telesat |
| 2 | Resetear o confirmar credenciales SMTP de la cuenta | Cliente / Telesat |
| 3 | Actualizar `PASSWORD_CORREO` en `configurar.php:86` con las credenciales válidas | Desarrollo |
| 4 | Re-test de envío desde la UI (crear incidencia) y verificar correo recibido | Desarrollo |
| 5 | Tras resolver: bajar `SMTP_DEBUG_NIVEL` de `3` a `0` en `configurar.php:97` | Desarrollo |
| 6 | Opcional: instrumentar `enviarEmailDocumento()` (facturas/PDFs) con el mismo patrón | Desarrollo |