# PROGRESS.md - Seguimiento de Desarrollo

## Proyecto: Control Horario y Vacaciones - CRM Telesat

---

## Fase 1: Infraestructura y Base de Datos ✅
- [x] Crear script SQL (`app/config/migracion_control_horario.sql`)
- [x] Ejecutar migración en DB crm_telesat *(hecho por el usuario)*
- [x] Actualizar `rolesbase.permisos` para roles 0 y 2 *(hecho por el usuario)*
- [x] Crear `app/models/ModeloControlHorario.php`
- [x] Crear `app/models/ModeloSolicitudesModificacion.php`
- [x] Crear `app/models/ModeloVacaciones.php`
- [x] Crear `app/models/ModeloConfiguracionHorario.php`
- [x] Crear `app/models/ModeloAuditoria.php`
- [x] Crear `app/models/ModeloReportesHorario.php`
- [x] Crear `app/helpers/ControlHorarioHelper.php`

## Fase 2: Fichaje - Core ✅
- [x] Crear `app/controlers/ControlHorario.php`
- [x] Crear `app/views/controlHorario/fichar.php`
- [x] Crear `app/views/controlHorario/historialEmpleado.php`
- [x] Crear `app/views/controlHorario/solicitarModificacion.php`
- [x] Crear `app/views/controlHorario/misSolicitudes.php`
- [x] Método `registrarFichaje()` con NOW() servidor
- [x] Método `obtenerEstadoActual()` AJAX
- [x] Crear `public/js/controlHorarioFichar.js`
- [x] Validaciones: duplicados, coherencia, jornada automática

## Fase 3: Historial y Solicitudes ✅ (integrado en Fase 2)
- [x] Vista `historialEmpleado.php`
- [x] Vista `solicitarModificacion.php`
- [x] Vista `misSolicitudes.php`
- [x] Métodos de solicitudes en ControlHorario
- [x] Lógica de inmutabilidad (original + corregido)

## Fase 4: Administración ✅
- [x] Crear `app/controlers/AdministracionHorario.php`
- [x] Vista `listadoGlobal.php`
- [x] Vista `detalleEmpleado.php`
- [x] Vista `solicitudesModificacion.php`
- [x] Vista `resolverSolicitud.php`
- [x] Vista `configuracionHorario.php`
- [x] Vista `calendarioJornadas.php`
- [ ] Crear `public/js/controlHorarioAdmin.js` (pendiente - se integrará si hace falta)

## Fase 5: Vacaciones ✅
- [x] Crear `app/controlers/Vacaciones.php`
- [x] Vista `solicitarVacacion.php`
- [x] Vista `misVacaciones.php`
- [x] Vista `listadoVacaciones.php`
- [x] Vista `resolverVacacion.php`
- [x] Vista `uploadJustificante.php`
- [ ] Crear `public/js/vacaciones.js` (se integrará si hace falta, JS inline en vistas)

## Fase 6: Reportes ✅
- [x] Crear `app/controlers/ReportesHorario.php`
- [x] Vista `selectorReportes.php`
- [x] Reporte CSV fichajes
- [x] Reporte PDF mensual empleado (vista + plantilla PDF)
- [x] Reporte PDF global empresa (vista + plantilla PDF)
- [x] Vista `reporteMensualEmpleado.php`
- [x] Vista `reporteGlobalEmpresa.php`

## Fase 7: Auditoría y Seguridad ✅
- [x] Tabla auditoria operativa en todos los controladores
- [x] IP y user-agent en cada fichaje
- [x] Log de acciones admin (solicitudes, vacaciones, config, reportes)
- [x] Verificar server-side timestamps (NOW() en MySQL)
- [x] Rate limiting básico (5 segundos entre fichajes)

## Fase 8: Integración y Testing
- [x] Integración con sidebar y permisos (rolesbase permisos)
- [x] Botón "Fichar" en login (modificado Login.php y login.php)
- [x] Redirección rol=3 a ControlHorario/fichar (modificado Login.php)
- [x] JS de controlHorarioFichar incluido en footer.php
- [x] Mini guía admin/usuario (app/documentos/GUIA_CONTROL_HORARIO.md)
- [ ] Testing funcional (en progreso)
- [ ] Pruebas responsive móvil
- [ ] Deployment producción

## Fase 9: Corrección de Bugs ✅

### Bug 1: Permisos AJAX no incluidos en rolesbase.permisos
- **Problema**: Los métodos AJAX/POST (registrarFichaje, obtenerEstadoActual, etc.) no estaban en el JSON de permisos, causando que `controlPermisos()` destruyera la sesión.
- **Solución**: Script PHP (`update_permisos_chova.php`) que añadió todos los métodos internos a los permisos de admin (rol=0) y tecnico (rol=2). Ejecutado vía CLI. Script eliminado tras ejecución.
- **Archivos**: Cambio en BD `rolesbase.permisos` (roles id=1 admin, id=3 tecnico)

### Bug 2: Error fatal al refrescar páginas CHOVA sin sesión
- **Problema**: `controlPermisos()` usa `in_array()` contra `$_SESSION['controlLinksUsuario']` que puede ser null, provocando TypeError fatal.
- **Solución**: Añadido guard de sesión en constructores de todos los controladores CHOVA: `if (!isset($_SESSION['idusuario'])) { redireccionar('/Login'); return; }` antes de `controlPermisos()`.
- **Archivos**: ControlHorario.php, AdministracionHorario.php, Vacaciones.php, ReportesHorario.php

### Bug 3: "Jornada de hoy: Cargando..." permanente en vista fichar
- **Problema**: La vista mostraba "Cargando..." estático, sin cargar datos iniciales (dependía de un segundo AJAX que hacía location.reload).
- **Solución**: Reemplazado "Cargando..." por renderizado PHP de fichajes del día usando `$datos['fichajesHoy']` (nuevo dato pasado desde el controlador).
- **Archivos**: ControlHorario.php (añadido `fichajesHoy` en datos), fichar.php (renderizado PHP), controlHorarioFichar.js (eliminado location.reload, actualización dinámica)

### Bug 4: Login::fichar() usaba variable `$control` no definida
- **Problema**: Línea 120 de Login.php usaba `$control['usuario']->rol ?? $idUsuario->rol` pero `$control` solo existe en el método `acceder()`, no en `fichar()`.
- **Solución**: Reemplazado por `$idUsuario->rol` directamente.
- **Archivos**: Login.php

### Bug 5: Política de seguridad de Base de Datos
- **Creación de usuario `crm_dev`** (solo SELECT) para consultas de desarrollo.
- **Política**: SELECT sin restricciones, DELETE nunca, CREATE/INSERT/UPDATE requieren autorización explícita del usuario.
- **Usuarios BD**: `root` (app PHP), `crm_dev`/`crm_dev_2026_ro` (opencode, solo lectura)

### Incidente: Corrupción de MariaDB por REPAIR TABLE
- **Causa**: Ejecución de `REPAIR TABLE mysql.global_priv` causó corrupción de tablas del sistema (`mysql.db`, `mysql.global_priv`, `mysql.time_zone_name`, `mysql.time_zone_leap_second`).
- **Recuperación**: Reparación manual con `--skip-grant-tables`, recreate de `mysql.db`, creación de usuario `crm_dev` con privilegios SELECT.
- **Lección aprendida**: NUNCA ejecutar REPAIR TABLE en tablas del sistema directamente. Ver política de seguridad BD en AGENTS.md.

---

## Registro de Cambios

| Fecha | Fase | Archivo | Descripción |
|-------|------|--------|-------------|
| 2026-05-22 | Plan | .opencode/plans/PLAN.md | Plan completo de desarrollo |
| 2026-05-22 | Plan | .opencode/plans/AGENTS.md | Guía de convenciones del proyecto |
| 2026-05-22 | Plan | .opencode/plans/PROGRESS.md | Tracker de progreso |
| 2026-05-22 | F1 | app/config/migracion_control_horario.sql | Script migración con 7 tablas + datos iniciales |
| 2026-05-22 | F1 | app/models/ModeloControlHorario.php | 18 métodos: jornadas, fichajes, cálculo horas |
| 2026-05-22 | F1 | app/models/ModeloSolicitudesModificacion.php | 10 métodos: CRUD solicitudes, aprobar/rechazar |
| 2026-05-22 | F1 | app/models/ModeloVacaciones.php | 10 métodos: CRUD vacaciones, cálculo días |
| 2026-05-22 | F1 | app/models/ModeloConfiguracionHorario.php | 7 métodos: config por empleado, debeFichar |
| 2026-05-22 | F1 | app/models/ModeloAuditoria.php | 5 métodos: registrar y consultar logs |
| 2026-05-22 | F1 | app/models/ModeloReportesHorario.php | 6 métodos: resumen mensual, CSV, anual |
| 2026-05-22 | F1 | app/helpers/ControlHorarioHelper.php | 13 funciones helpers: validación, cálculo, IP |
| 2026-05-22 | F2 | app/controlers/ControlHorario.php | Controlador: fichar, registrarFichaje, historial, solicitudes |
| 2026-05-22 | F2 | app/views/controlHorario/fichar.php | Vista fichaje responsive con botón grande |
| 2026-05-22 | F2 | app/views/controlHorario/historialEmpleado.php | Vista historial con filtro mes/año |
| 2026-05-22 | F2 | app/views/controlHorario/solicitarModificacion.php | Formulario solicitud corrección |
| 2026-05-22 | F2 | app/views/controlHorario/misSolicitudes.php | Listado solicitudes del empleado |
| 2026-05-22 | F2 | public/js/controlHorarioFichar.js | JS: AJAX fichaje, geolocalización, estado |
| 2026-05-22 | F4 | app/controlers/AdministracionHorario.php | Controlador admin: listadoGlobal, detalleEmpleado, solicitudes, resolver, config, calendario |
| 2026-05-22 | F4 | app/views/administracionHorario/listadoGlobal.php | Vista admin con filtros fecha/empleado |
| 2026-05-22 | F4 | app/views/administracionHorario/detalleEmpleado.php | Detalle fichajes por jornada |
| 2026-05-22 | F4 | app/views/administracionHorario/solicitudesModificacion.php | Listado solicitudes pendientes e historial |
| 2026-05-22 | F4 | app/views/administracionHorario/resolverSolicitud.php | Formulario aprobar/rechazar con detalle |
| 2026-05-22 | F4 | app/views/administracionHorario/configuracionHorario.php | Config quién ficha, horas jornada, tolerancia |
| 2026-05-22 | F4 | app/views/administracionHorario/calendarioJornadas.php | Vista calendario mensual con colores por estado |
| 2026-05-23 | F9 | app/controlers/ControlHorario.php | Bug2: guard de sesión + datos fichajesHoy |
| 2026-05-23 | F9 | app/controlers/AdministracionHorario.php | Bug2: guard de sesión |
| 2026-05-23 | F9 | app/controlers/Vacaciones.php | Bug2: guard de sesión |
| 2026-05-23 | F9 | app/controlers/ReportesHorario.php | Bug2: guard de sesión |
| 2026-05-23 | F9 | app/views/controlHorario/fichar.php | Bug3: renderizado PHP de jornadas vs "Cargando..." |
| 2026-05-23 | F9 | public/js/controlHorarioFichar.js | Bug3: eliminado location.reload, actualización dinámica |
| 2026-05-23 | F9 | app/controlers/Login.php | Bug4: $control['usuario']->rol → $idUsuario->rol |
| 2026-05-28 | F10 | app/librerias/Controlador.php | Bug12: controlPermisos() cambia de in_array exacto a match parcial con strpos |
| 2026-05-28 | F10 | app/views/includes/controlPermisos.php | Bug12: mismo cambio que Controlador.php |
| 2026-05-28 | F10 | app/controlers/ControlHorario.php | Bug12: nuevo método detalleJornada() |
| 2026-05-28 | F10 | app/models/ModeloControlHorario.php | Bug12: nuevo método obtenerJornadaPorId() |
| 2026-05-28 | F10 | app/views/controlHorario/detalleJornada.php | Bug12: nueva vista detalle de jornada |
| 2026-05-28 | F10 | app/views/controlHorario/historialEmpleado.php | Bug12: enlace cambiado de \u003ca href\u003e a form POST |
| 2026-05-28 | F10 | BD rolesbase.permisos | Bug12b: reestructurados permisos CHOVA para sidebar (items visibles vs AJAX links) |
| 2026-05-23 | F9 | BD rolesbase.permisos | Bug1: añadidos métodos AJAX/POST a permisos de admin y tecnico |
| 2026-05-23 | F9 | BD rolesbase | Creación de rol visitante (id=4, rol=3) |
| 2026-05-23 | F9 | BD usuario crm_dev | Creación de usuario de solo lectura para desarrollo |
| 2026-05-23 | F9 | .opencode/plans/AGENTS.md | Añadida política de seguridad BD, usuarios, roles, lecciones aprendidas |
| 2026-05-23 | F9 | .opencode/plans/PROGRESS.md | Registro de bugs corregidos e incidente MariaDB |
| 2026-05-29 | F10 | app/views/controlHorario/solicitarModificacion.php | Bug14: data-jornada en options + campo oculto idjornadaoriginal |
| 2026-05-29 | F10 | app/controlers/ControlHorario.php | Bug14: obtener jornada del fichaje si idjornadaoriginal vacío |
| 2026-05-29 | F10 | app/controlers/AdministracionHorario.php | Bug14: aplicarSolicitudAprobada usa idjornada del fichaje, insertarFichajeConFecha, recalcularJornada |
| 2026-05-29 | F10 | app/models/ModeloControlHorario.php | Bug14: nuevos métodos obtenerFichajePorId, insertarFichajeConFecha, actualizarHorasJornada |
| 2026-05-29 | F10 | app/helpers/ControlHorarioHelper.php | Bug14: calcularHorasEntreFichajes excluye fichajes corregidos |
| 2026-05-29 | F10 | app/views/controlHorario/detalleJornada.php | Bug14: fichajes corregidos con bg amarillo, hora tachada, badge "Corregido" |
| 2026-05-30 | F10 | app/controlers/ControlHorario.php | Bug13: cerrarJornada → actualizarHorasJornada al fichar salida |
| 2026-05-30 | F10 | app/controlers/AdministracionHorario.php | Bug13: recalcularJornada solo cierra si jornada es de día anterior |
| 2026-05-30 | F10 | public/js/navbarAcciones.js | Bug15: guarda esAdminOTecnico en llamadas AJAX Incidencias/PresupuestosFacturas |
| 2026-05-30 | F10 | public/js/navbarSidebar/navbarSidebar.js | Bug15: guarda if(profileBtn) para evitar TypeError |
| 2026-05-30 | F10 | BD rolesbase.permisos (id=4) | Bug16: permisos visitante reestructurados con formato correcto |
| 2026-05-29 | F10 | BD fichajes/jornadas/solicitudesmodificacion | Bug14: corrección manual fichaje huérfano ID14, jornada 9 horas recalculadas |
| 2026-05-31 | F10 | BD rolesbase.permisos (id=1,3,4) | Bug17: eliminados [] vacíos de permisos CHOVA |
| 2026-05-31 | F10 | app/views/vacaciones/misVacaciones.php | Bug18: columna Respuesta muestra respuestaadmin |
| 2026-05-31 | F10 | app/models/ModeloVacaciones.php | Bug19: método obtenerVacacionesSolapadas |
| 2026-05-31 | F10 | app/controlers/Vacaciones.php | Bug19: validación solapamiento en solicitar y resolver |
| 2026-05-31 | F10 | app/views/vacaciones/solicitarVacacion.php | Bug19: mensaje error solapamiento + pre-rellenar campos |
| 2026-05-31 | F10 | app/views/vacaciones/resolverVacacion.php | Bug19: mensaje error solapamiento al aprobar |
| 2026-06-05 | F10 | app/controlers/ReportesHorario.php | Bug20: añadidos diasTrabajados, jornadasCompletas, jornadasIncompletas a reportePDFMensualEmpleado + totalJornadasCompletas, totalJornadasIncompletas, totalEmpleados a reportePDFGlobalEmpresa |
| 2026-06-05 | F10 | app/views/administracionHorario/configuracionHorario.php | Bug21: htmlspecialchars en nombreempleado para JS onclick |
| 2026-06-05 | F10 | app/controlers/AdministracionHorario.php | Bug22: validación $_POST['accion'] con in_array + default vacío + redirect |
| 2026-06-05 | F10 | app/controlers/AdministracionHorario.php | Bug23: guard strtotime(null) en aplicarSolicitudAprobada rama insertar |
| 2026-06-05 | F10 | app/controlers/AdministracionHorario.php | Bug24: guard !empty($fichajes) en recalcularJornada |
| 2026-06-05 | F10 | app/views/reportesHorario/selectorReportes.php | Bug25: strftime() reemplazado por array de meses en español |
| 2026-06-05 | F10 | app/views/reportesHorario/reporteGlobalEmpresa.php | Bug25: strftime() reemplazado por array de meses en español |
| 2026-06-05 | F10 | app/controlers/AdministracionHorario.php | Bug26: nuevotipofichaje fallback consistente en modificar |
| 2026-06-05 | F10 | app/controlers/AdministracionHorario.php | Bug27: filtro Todos en listadoGlobal enviaba idempleado=0 en vez de null |
| 2026-06-05 | F10 | app/views/administracionHorario/detalleEmpleado.php | Bug25+mejora: strftime reemplazado, badges vacaciones/baja, mensaje sin fichajes |
| 2026-06-05 | F10 | app/views/administracionHorario/calendarioJornadas.php | Bug25: strftime reemplazado por array meses español |
| 2026-06-07 | F10 | 11 vistas CHOVA (listadoGlobal, solicitudesModificacion, calendarioJornadas, detalleEmpleado, detalleJornada, historialEmpleado, reporteGlobalEmpresa, reporteMensualEmpleado, listadoVacaciones, misVacaciones, resolverSolicitud) | Bug28: reemplazadas clases orange-* por yellow-* (no compiladas en Tailwind CSS) |
| 2026-06-07 | F10 | app/controlers/AdministracionHorario.php | Fix: verificar estado pendiente antes de procesar solicitud (Issue #1) |
| 2026-06-07 | F10 | app/controlers/AdministracionHorario.php | Fix: cargar fichajes de jornada en solicitudes tipo insertar usando idjornadaoriginal (Issue #3) |
| 2026-06-07 | F10 | app/views/administracionHorario/solicitudesModificacion.php | Mejora: columna Detalle añadida en tabla Todas las Solicitudes |
| 2026-06-07 | F10 | app/views/administracionHorario/detalleEmpleado.php | Mejora: fichajes corregidos consistentes con detalleJornada (fondo amarillo, hora tachada, badge Corregido) |
| 2026-06-08 | F10 | app/controlers/Usuarios.php (constructor) | Integración config horario: añadido modelo ModeloConfiguracionHorario |
| 2026-06-08 | F10 | app/controlers/Usuarios.php (crearUsuario) | Integración config horario: llamada a crearConfigHorarioSiCorresponde() en try-catch |
| 2026-06-08 | F10 | app/controlers/Usuarios.php (editarUsuario) | Integración config horario: llamada a actualizarConfigHorarioSegunRol() en try-catch |
| 2026-06-08 | F10 | app/controlers/Usuarios.php (nuevas funciones) | crearConfigHorarioSiCorresponde() y actualizarConfigHorarioSegunRol() |
| 2026-06-09 | F10 | app/controlers/Login.php (acceder + fichar) | Añadido $_SESSION['debeFichar'] al iniciar sesión |
| 2026-06-09 | F10 | app/views/includes/menu-sidebar-desktop.php | Filtro: oculta ControlHorario y Vacaciones si debeFichar=0 |
| 2026-06-09 | F10 | app/views/includes/menu-sidebar-mobile.php | Filtro: oculta ControlHorario y Vacaciones si debeFichar=0 |
| 2026-06-09 | F10 | app/controlers/ControlHorario.php | Guard en constructor: debeFichar=0 → redirige a /Inicio |
| 2026-06-09 | F10 | app/controlers/Vacaciones.php | Guard en constructor: debeFichar=0 → redirige a /Inicio |
| 2026-06-09 | F10 | app/views/usuarios/altaUsuarios/altaUsuarios.php | Añadido rol Visitante (value=3) al select |
| 2026-06-09 | F10 | app/views/usuarios/actualizarUsuarios/actualizarUsuarios.php | Select de rol dinámico: Admin, Cliente, Visitante (+ Técnico si ya lo es) |
| 2026-06-09 | F10 | app/views/administracionHorario/configuracionHorario.php | Añadido rol 3 = Visitante al array de roles |
| 2026-06-09 | F10 | app/controlers/AdministracionHorario.php | Método temporal migrarConfigHorario() para crear registros con debeFichar=0 |
| 2026-06-09 | F10 | app/views/administracionHorario/migrarResultado.php | Vista resultado de migración de configuración horario |

## Fase 10: Testing Funcional y Correcciones ✅ (en progreso)

### Bug 6: Doble petición al fichar (JS incluido 2 veces)
- **Problema**: `controlHorarioFichar.js` se cargaba 2 veces (en `fichar.php` línea 91 y en `footer.php` línea 109), registrando 2 event listeners y causando 2 peticiones por click.
- **Solución**: Eliminado `<script>` duplicado de `fichar.php`. El JS solo se carga desde `footer.php`.
- **Archivos**: `app/views/controlHorario/fichar.php`

### Bug 7: Geolocalización causaba doble envío
- **Problema**: `navigator.geolocation.getCurrentPosition()` dentro del click handler era asíncrono, generando condiciones de carrera.
- **Solución**: Geolocalización precargada al cargar la página y actualizada cada 60s con `setInterval`. Al hacer click, se envían coordenadas cacheadas (o null si no hay).
- **Archivos**: `public/js/controlHorarioFichar.js`

### Bug 8: "Último fichaje" no se actualizaba sin refrescar
- **Problema**: El JS no actualizaba el texto "Último fichaje" tras un fichaje AJAX exitoso.
- **Solución**: Añadidos ids `ultimoFichajeTexto`, `ultimoFichajeTipo`, `ultimoFichajeFecha` en la vista y función `actualizarUltimoFichaje()` en JS.
- **Archivos**: `app/views/controlHorario/fichar.php`, `public/js/controlHorarioFichar.js`

### Bug 9: Jornada incompleta desaparecía al refrescar
- **Problema**: La variable `jornadaIncompleta` solo se seteaba condicionalmente según el tipo de fichaje siguiente. Si ya habías fichado hoy, la alerta del 22/05 desaparecía.
- **Solución**: Nuevo método `buscarJornadasIncompletasAnteriores()` en el modelo que busca jornadas con `estadojornada = 'incompleta'` de días anteriores, independientemente del tipo de fichaje actual. La vista itera sobre `$datos['jornadasIncompletas']`.
- **Archivos**: `app/models/ModeloControlHorario.php`, `app/controlers/ControlHorario.php`, `app/views/controlHorario/fichar.php`

### Bug 10: Total horas no aparecía dinámicamente tras fichar salida
- **Problema**: Tras fichar salida por AJAX, la línea "Total horas" no se mostraba hasta refrescar.
- **Solución**: Añadido `horastotales` al JSON de respuesta del controlador. En JS, `agregarFichajeALista()` crea/actualiza/elimina el div `#totalHoras` según tipo de fichaje (muestra al salida, elimina al entrada). Añadido `id="totalHoras"` en la vista.
- **Archivos**: `app/controlers/ControlHorario.php`, `app/views/controlHorario/fichar.php`, `public/js/controlHorarioFichar.js`

### Bug 11: Protección server-side contra fichajes duplicados
- **Problema**: Solo existía cooldown de sesión (5s) para prevenir fichajes duplicados. Race conditions podían causar duplicados.
- **Solución**: Nuevo método `fichajeRecienteExiste()` en ModeloControlHorario que verifica en BD si existe un fichaje del mismo empleado en los últimos 5 segundos.
- **Archivos**: `app/models/ModeloControlHorario.php`, `app/controlers/ControlHorario.php`

### Bug 12: Enlace a detalleJornada rotos (método no existía) + controlPermisos no soportaba parámetros URL
- **Problema**: La vista `historialEmpleado.php` enlazaba a `/ControlHorario/detalleJornada/{id}` pero el método no existía. Además, `controlPermisos()` usaba `in_array()` exacto, lo que hacía que URLs con parámetros adicionales (`/Path/7`) o query strings (`?mes=6`) fallaran y destruyeran la sesión. Esto afectaba a 6+ métodos CHOVA existentes: `detalleEmpleado`, `resolverSolicitud`, `resolverVacacion`, `subirJustificante`, `reporteMensualEmpleado`, `reportePDFMensualEmpleado`.
- **Solución**:
  1. Modificado `Controlador::controlPermisos()` para usar match parcial (`strpos`) en vez de `in_array()` exacto, y strip del query string. También modificado `app/views/includes/controlPermisos.php` con la misma lógica.
  2. Creado método `detalleJornada()` en `ControlHorario.php` que acepta ID por POST o por URL param.
  3. Creado modelo `obtenerJornadaPorId()` en `ModeloControlHorario.php`.
  4. Creada vista `app/views/controlHorario/detalleJornada.php`.
  5. Modificada vista `historialEmpleado.php` para usar form POST en vez de `<a href>` con ID.
  6. Añadido `/ControlHorario/detalleJornada` a permisos de admin (id=1), tecnico (id=3) y visitante (id=4).
- **Archivos**: `app/librerias/Controlador.php`, `app/views/includes/controlPermisos.php`, `app/controlers/ControlHorario.php`, `app/models/ModeloControlHorario.php`, `app/views/controlHorario/detalleJornada.php`, `app/views/controlHorario/historialEmpleado.php`, BD rolesbase.permisos

### Bug 12b: Permisos CHOVA en sidebar no mostraban items navegables
- **Problema**: Los permisos CHOVA tenían todos los métodos como `["/url","link"]` donde el sidebar filtra `!= "link"`, causando que solo se mostrara "Control Horario" (sub-grupo) sin items como "Mi Historial", "Mis Solicitudes", etc.
- **Solución**: Reestructurado el JSON de permisos de rolesbase para roles admin, técnico y visitante, separando items visibles en el sidebar (con label propio) de los métodos AJAX (con `"link"`).
- **Archivos**: BD rolesbase.permisos (roles id=1, 3, 4)

### Bug 14: `idjornadaoriginal=0` causaba fichajes huérfanos y horas no recalculadas en solicitudes de modificación
- **Problema**: Al crear una solicitud tipo "modificar" u "omitir", `idjornadaoriginal` llegaba como 0 al controlador. Al aprobarse, el fichaje corregido se insertaba con `idjornada=0` (huérfano) y con `fechahora=NOW()` en vez de la hora solicitada. Las horas de la jornada no se recalculaban. En la vista detalleJornada, fichajes corregidos no se distinguían bien visualmente ni se excluían del cálculo.
- **Solución**:
  1. `solicitarModificacion.php` (vista): Añadido `data-jornada` en options del select de fichajes + campo oculto `idjornadaoriginal` que se rellena automática vía JS al seleccionar un fichaje (tipos "modificar" y "omitir").
  2. `ControlHorario.php` (controlador): En `solicitarModificacion()`, si `idjornadaoriginal` viene vacío y hay `idfichajeoriginal`, obtiene la jornada del fichaje usando `obtenerFichajePorId()`.
  3. `AdministracionHorario.php`: En `aplicarSolicitudAprobada()`, los casos "modificar" y "omitir" obtienen `idjornada` del fichaje original si `idjornadaoriginal` es 0. El caso "modificar" usa `insertarFichajeConFecha()` con la fecha solicitada (no `NOW()`). Los 3 casos llaman a `recalcularJornada()`. Nuevo método privado `recalcularJornada()`.
  4. `ModeloControlHorario.php`: Añadidos `obtenerFichajePorId()`, `insertarFichajeConFecha()`, `actualizarHorasJornada()`. `calcularHorasJornada()` excluye fichajes con `corregido=1`.
  5. `ControlHorarioHelper.php`: `calcularHorasEntreFichajes()` excluye fichajes con `corregido=1`.
  6. `detalleJornada.php`: Fichajes corregidos con fondo amarillo, hora tachada, badge "Corregido". Cálculo de duración por pares excluye corregidos.
  7. **Corrección manual en BD**: Fichaje ID 14 (huérfano, `idjornada=0`, `fechahora=NOW()`) → actualizado a `idjornada=9`, `fechahora=2026-05-29 01:49:00`. Jornada 9 horas recalculadas de 0.02h a 2.94h. Solicitud #1 `idjornadaoriginal` actualizado a 9.
- **Archivos**: `solicitarModificacion.php`, `ControlHorario.php`, `AdministracionHorario.php`, `ModeloControlHorario.php`, `ControlHorarioHelper.php`, `detalleJornada.php`, BD fichajes/jornadas/solicitudesmodificacion

### Testing Funcional - Progreso
| # | Prueba | Estado |
|---|--------|--------|
| 1 | Fichaje entrada (1 sola request) | ✅ |
| 2 | Fichaje salida (1 sola request) | ✅ |
| 3 | Jornada incompleta persiste al refrescar | ✅ |
| 4 | "Último fichaje" se actualiza sin refrescar | ✅ |
| 5 | Total horas aparece dinámicamente | ✅ |
| 6 | Doble entrada (entrada/salida/entrada/salida misma jornada) | ✅ (Bug #13 corregido) |
| 7 | Historial propio | ✅ |
| 8 | Solicitar modificación | ✅ |
| 9 | Mis solicitudes | ✅ |
| 10 | Listado global admin | ✅ Verificado (filtros, "Todos", fechas, contadores, detalle) |
| 11 | Resolver solicitud (aprobar/rechazar) | ✅ Verificado (Issue #1, #3, vista detalleEmpleado consistente) |
| 12 | Vacaciones (solicitar, aprobar, rechazar) | ✅ (Bug #18 + #19 corregidos) |
| 13 | Reportes (CSV, PDF) | 🔧 Bugs corregidos, pendiente test manual |
| 14 | Configuración horario admin | 🔧 Bugs corregidos, pendiente test manual |

## Pendiente por Probar

| # | Prueba | Notas |
|---|--------|-------|
| 6 | Doble entrada (entrada/salida/entrada/salida misma jornada) | ✅ Corregido Bug #13, jornada permanece abierta, horas correctas |
| 10 | Listado global admin | ✅ Verificado: filtros, "Todos", fechas, contadores, detalle |
| 11 | Resolver solicitud (aprobar/rechazar) | ✅ Verificado: Issue #1 (estado pendiente), Issue #3 (fichajes jornada en insertar), columna Detalle, vista corregidos consistente |
| V1 | Login visitante redirige a fichar | ✅ visitante@telesat.com / test |
| V2 | Sidebar visitante solo muestra CHOVA y Vacaciones | ✅ Sin Incidencias, Facturas, Dashboard |
| V3 | Visitante puede fichar entrada y salida | ✅ |
| V4 | Visitante puede fichar doble entrada (E/S/E/S) | ✅ Jornada permanece abierta, horas correctas |
| V5 | Visitante NO puede acceder a AdministracionHorario | ✅ Acceso denegado |
| V6 | Visitante NO puede acceder a ReportesHorario | ✅ Acceso denegado |
| V7 | Visitante puede acceder a miHistorial y misSolicitudes | ✅ |
| V8 | Visitante puede solicitar vacaciones | ✅ Bug #19: solapamiento bloqueado |
| V9 | Visitante puede ver misVacaciones + respuesta admin | ✅ Bug #18: respuestaadmin visible |
| V10 | Cierre automático de jornada al día siguiente | ✅ Verificado 07/06/2026 |
| 11 | Admin: resolver solicitudes | ✅ Verificado: Issue #1, #3, columna Detalle, vista corregidos consistente |
| 12 | Vacaciones completo | ✅ (Bug #18 + #19 corregidos) |
| 13 | Reportes (CSV, PDF) | 🔲 Probar descarga CSV y generación PDF desde /ReportesHorario |
| 14 | Configuración horario admin | 🔲 Probar activar/debeFichar, horas jornada, tolerancia desde /AdministracionHorario/configuracionHorario |

### Pruebas Pendientes — Vacaciones (VA)

| # | Prueba | Estado |
|---|--------|--------|
| VA1 | Solicitar vacaciones (tipo: vacaciones) → estado pendiente | ✅ Verificado |
| VA2 | Solicitar baja médica con justificante → se sube archivo | ✅ Validación server-side añadida (Bug #37) |
| VA3 | Solicitar ausencia justificada → se crea solicitud pendiente | ✅ Jornadas "ausencia" creadas (Bug #38) |
| VA4 | Aprobar vacaciones → jornadas marcadas como `vacaciones` | ✅ Verificado - jornadas en morado en calendario |
| VA5 | Aprobar baja médica → jornadas marcadas como `baja` | ✅ Verificado - jornadas en rojo en calendario |
| VA6 | Cancelar baja → jornadas revierten a `abierta` | ✅ Verificado - cancelar baja revierte a azul en calendario |
| VA7 | Solicitar vacaciones con fechas solapadas a aprobada → error de solapamiento | ✅ Verificado (validación de solapamiento funciona) |
| VA8 | Solicitar vacaciones con fechas solapadas a pendiente → error de solapamiento | ✅ Verificado (obtenerVacacionesSolapadas busca pendiente y aprobada) |
| VA9 | Aprobar vacaciones solapadas con otras aprobadas → bloqueo con error | ✅ Verificado - bloquea solapamiento |
| VA10 | Vacaciones que incluyen fin de semana → jornadas creadas para sábado y domingo | ✅ COR-1 verificado |
| VA11 | Vacaciones donde ya existe jornada abierta → reutiliza jornada existente | ✅ Verificado - jornada id=57 reutilizada, no duplicada |
| VA12 | Subir justificante después de crear solicitud de baja | ✅ No aplica (obligatorio antes de crear, Bug #37) |
| VA13 | Mis vacaciones muestra respuesta del admin (toggle expandible) | ✅ Implementado, verificar visualmente |
| VA14 | Cálculo días en formulario de solicitud → muestra "X día(s)" | ✅ Implementado |
| VA15 | Solicitar vacaciones en pasado → se permite (sin validación) | ✅ Verificado - se permite sin error |
| VA16 | Bloqueo fichaje en día con vacaciones aprobadas | ✅ Verificado en COR-2 |
| VA17 | Bloqueo fichaje en día con baja aprobada | ✅ Verificado - bloquea con mensaje |
| VA18 | Bloqueo aprobación si hay fichajes en el rango de fechas | ✅ Verificado en COR-4 |
| VA19 | Cancelar vacaciones aprobadas → estado cancelada, jornadas revertidas | ✅ COR-5 verificado |
| VA20 | Cancelar vacaciones rechazadas → no aparece botón cancelar | ✅ COR-7 verificado |
| VA21 | Vacaciones aprobadas: solo aparece botón Cancelar (no Aprobar/Rechazar) | ✅ COR-7 verificado |

#### Pruebas Nuevas — Validaciones implementadas (2026-06-08)

| # | Prueba | Estado |
|---|--------|--------|
| VA-NEW-1 | Solicitar vacaciones en fechas con fichajes → error bloquea la solicitud | ✅ Verificado |
| VA-NEW-2 | Fichaje bloqueado si vacaciones pendientes en el día | ✅ Verificado |
| VA-NEW-3 | Omitir fichaje → fichaje eliminado (eliminado=1), no corregido | ✅ Verificado |
| VA-NEW-4 | Omitir fichaje → solicitud de vacaciones permitida después (fichaje eliminado no bloquea) | ✅ Verificado |

#### Notas sobre VA2 (Baja médica)

- ✅ Bug resuelto (Bug #37): Justificante médico obligatorio con validación server-side
- ✅ Bug resuelto (Bug #38): Tipo "ausencia" ya crea jornadas correctamente

#### Pruebas — Rol Visitante

| # | Prueba | Estado |
|---|--------|--------|
| V11 | Visitante puede subir justificante de baja | ✅ Verificado - sube archivo correctamente |
| V12 | Visitante ve solo sus datos (no de otros) | ✅ Verificado |
| V13 | Visitante NO puede resolver sus propias vacaciones | ✅ Verificado |
| V14 | Visitante NO puede acceder a Administración Horario | ✅ Verificado |
| V15 | Visitante NO puede acceder a Reportes | ✅ Verificado |
| V16 | Visitante con vacaciones aprobadas NO puede fichar ese día | ✅ Verificado |

#### Pruebas — Selects de solicitarModificacion

| # | Prueba | Estado |
|---|--------|--------|
| SEL-1 | Cargar vista solicitarModificacion → select de jornadas muestra últimos 2 meses | 🔲 |
| SEL-2 | Tipo "Insertar" muestra select de jornada + tipo fichaje + fecha/hora | 🔲 |
| SEL-3 | Tipo "Modificar" muestra select de fichaje + tipo + fecha/hora | 🔲 |
| SEL-4 | Tipo "Omitir" muestra select de fichaje (sin tipo ni fecha/hora) | 🔲 |
| SEL-5 | Seleccionar fichaje en "Modificar" rellena idjornada original automáticamente | 🔲 |

#### Pruebas — Integración Usuarios ↔ Config Horario (manual)

| # | Prueba | Estado |
|---|--------|--------|
| USR-1 | Crear usuario con rol admin (rol=0) → se crea config con `debeFichar=1` | 🔲 |
| USR-2 | Crear usuario con rol cliente (rol=1) → NO se crea config | 🔲 |
| USR-3 | Crear usuario con rol técnico (rol=2) → se crea config con `debeFichar=1` | 🔲 |
| USR-4 | Crear usuario con rol visitante (rol=3) → se crea config con `debeFichar=1` | 🔲 |
| USR-5 | Editar usuario cambiando rol a cliente (rol=1) → config actualiza `debeFichar=0` | 🔲 |
| USR-6 | Editar usuario cambiando rol de cliente a técnico → se crea config con `debeFichar=1` si no existe | 🔲 |
| USR-7 | Editar usuario cambiando rol de cliente a técnico → config existente con `debeFichar=0` se actualiza a `debeFichar=1` | 🔲 |
| USR-8 | Editar usuario con mismo rol no-cliente → config no se modifica | 🔲 |
| USR-9 | Crear usuario no-cliente que ya tiene config → no se duplica | 🔲 |
| USR-10 | Si falla la creación de config, el usuario se crea y el email se envía | 🔲 |
| USR-11 | Verificar en Admin. Horario → Configuración que los nuevos usuarios aparecen | 🔲 |

#### Pruebas Funcionales Previas (pendiente manual)

| # | Prueba | Estado |
|---|--------|--------|
| 13 | Reportes (CSV, PDF) | 🔲 |
| 14 | Configuración horario admin | 🔲 |

#### Prueba de Zonas Horarias — Producción

| # | Prueba | Estado |
|---|--------|--------|
| TZ-1 | `SELECT NOW()` de MySQL coincide con `date('Y-m-d H:i:s')` de PHP en servidor producción | 🔲 |

---

## Cambios 2026-06-08

### Bug #31: Fichaje permitido con vacaciones pendientes (bloqueo solo aprobadas)
- **Problema**: `obtenerVacacionAprobadaPorFecha()` solo bloqueaba fichaje si `estado = 'aprobada'`. Vacaciones en estado `pendiente` no bloqueaban, permitiendo fichar en días con vacaciones solicitadas.
- **Solución**: Nuevo método `obtenerVacacionActivaPorFecha()` en `ModeloVacaciones` que busca `estado IN ('pendiente', 'aprobada')`. Modificados `ControlHorario::fichar()` y `ControlHorario::registrarFichaje()` para usar el nuevo método. Mensaje diferenciado: "pendiente(s)" vs "aprobada(s)".
- **Archivos**: `ModeloVacaciones.php`, `ControlHorario.php`

### Bug #32: Solicitud de vacaciones permitida en fechas con fichajes existentes
- **Problema**: Al solicitar vacaciones para fechas donde ya hay fichajes, la solicitud se creaba correctamente pero al aprobarla fallaba (bloqueo por fichajes en rango). Creaba inconsistencia: jornadas `vacaciones` con fichajes.
- **Solución**: Nueva validación en `Vacaciones::solicitarVacacion()` antes de crear la solicitud. Si hay fichajes en el rango, muestra error y no permite crear la solicitud.
- **Archivos**: `Vacaciones.php`

### Bug #33: Enlaces duplicados en sidebar CHOVA
- **Problema**: Los permisos JSON de roles 1 (admin), 3 (tecnico) y 4 (visitante) tenían como primer subitem de cada grupo CHOVA `["/Controlador", "Nombre"]`, que se renderizaba como enlace visible duplicado (ej: `/ControlHorario` y `/ControlHorario/fichar` llevaban al mismo sitio). Mismo problema con `/Vacaciones`, `/AdministracionHorario`, `/ReportesHorario`.
- **Solución**: Cambiado `[1]` de cada primer subitem de `"Nombre"` a `"link"` en los permisos de los 3 roles. El sidebar solo renderiza como enlace visible los items donde `[1] != "link"`.
- **Archivos**: BD `rolesbase.permisos` (roles id=1, 3, 4)

### Bug #34: miHistorial y detalleEmpleado orden ascendente
- **Problema**: `obtenerJornadasPorEmpleadoRango()` usaba `ORDER BY fecha ASC`, mostrando las jornadas más antiguas primero.
- **Solución**: Cambiado a `ORDER BY fecha DESC` para mostrar las más recientes primero.
- **Archivos**: `ModeloControlHorario.php`

### Bug #35: Select "Empleado" en listadoGlobal no mostraba visitante
- **Problema**: `obtenerEmpleadosQueFichan()` filtraba `u.rol IN (0, 2)` (admin y tecnico), excluyendo al visitante (rol=3).
- **Solución**: Cambiado a `u.rol IN (0, 2, 3)` para incluir visitante.
- **Archivos**: `ModeloControlHorario.php`

### Bug #36: Omitir fichaje dejaba registro corregido visible (inconsistencia con vacaciones)
- **Problema**: Al omitir un fichaje, se marcaba `corregido=1` dejándolo visible (tachado, fondo amarillo) en la jornada. Si la jornada tenía `estadojornada='vacaciones'`, mostraba fichajes corregidos bajo "Vacaciones". Además, `obtenerFichajesPorEmpleadoRangoFechas` no filtraba `corregido`, así que fichajes corregidos bloqueaban solicitudes de vacaciones.
- **Solución**: Nuevo método `eliminarFichaje($id)` en `ModeloControlHorario` que hace `UPDATE fichajes SET eliminado = 1`. En `AdministracionHorario::aplicarSolicitudAprobada()`, caso "omitir", cambiado `marcarFichajeCorregido()` por `eliminarFichaje()`. El caso "modificar" sigue usando `marcarFichajeCorregido()` (sin cambio). Los fichajes eliminados (`eliminado=1`) no aparecen en consultas, vistas, ni bloquean vacaciones.
- **Archivos**: `ModeloControlHorario.php`, `AdministracionHorario.php`

### Notas para reanudar pruebas

**Punto de reanudación**: VA2 - Solicitar baja médica con justificante

**Bugs corregidos esta sesión (2026-06-08)**:

### Bug #37: Justificante médico obligatorio solo visualmente (sin validación server-side)
- **Problema**: En `solicitarVacacion.php`, el JS cambiaba el label a "Justificante médico *" al seleccionar tipo "baja", pero no había validación server-side. Si se enviaba una baja sin justificante, se aceptaba con `ficherojustificante = null`.
- **Solución**: Añadida validación en `Vacaciones::solicitarVacacion()` que verifica si tipo es "baja" y no hay fichero justificante. Si falta, muestra error "El justificante médico es obligatorio para bajas médicas." y recarga la vista con los datos previos. También se añadió `input.required = true/false` dinámico en el JS de la vista.
- **Archivos**: `Vacaciones.php`, `solicitarVacacion.php`

### Bug #38: Tipo "ausencia" NO crea jornadas (falta elseif en solicitarVacacion)
- **Problema**: En `Vacaciones::solicitarVacacion()` líneas 107-111, solo existían `if` para `vacaciones` y `elseif` para `baja`. Al seleccionar "ausencia justificada", se creaba la solicitud pero NO se generaban jornadas con `estadojornada = 'ausencia'`.
- **Solución**: Añadido `elseif ($tipoVacacion === 'ausencia')` que llama nuevo método `crearJornadasAusencia()`. También actualizado `actualizarJornadasVacaciones()` para resolver el estado correcto ("ausencia") al aprobar. Actualizado `eliminarJornadasPendientes()` y `revertirJornadasCanceladas()` para incluir "ausencia". Añadido badge "ausencia" en 6 vistas: `detalleEmpleado`, `historialEmpleado`, `detalleJornada`, `reporteMensualEmpleado`, `listadoGlobal`, `calendarioJornadas`.
- **Archivos**: `Vacaciones.php`, `detalleEmpleado.php`, `historialEmpleado.php`, `detalleJornada.php`, `reporteMensualEmpleado.php`, `listadoGlobal.php`, `calendarioJornadas.php`

**BD visitante (id=148) estado limpio**: Sin fichajes, sin vacaciones, jornada 08/06 en estado `abierta`

**Pruebas completadas esta sesión**:
- VA1-1: Solicitud normal sin conflictos ✅
- VA1-3: Solicitud bloqueada por fichajes en rango ✅
- VA1-4: Bloqueo fichaje con vacaciones pendientes ✅
- VA-NEW-3: Omitir fichaje → eliminado=1 ✅
- Sin inconsistencia vacaciones + fichajes ✅
- **Problema**: En `AdministracionHorario::listadoGlobal()`, `(int)$_GET['idempleado']` convertía el string vacío `""` (valor de "Todos") a `0`, haciendo que la query busque `idempleado = 0` que no existe. El filtro nunca devolvía resultados cuando se seleccionaba "Todos".
- **Solución**: Cambiado a `isset($_GET['idempleado']) && $_GET['idempleado'] !== '' ? (int)$_GET['idempleado'] : null`, igual que ya estaba en `ReportesHorario::generarReportes()`.
- **Archivos**: `app/controlers/AdministracionHorario.php`
- **Fecha**: 2026-06-05

## Cambios 2026-06-08 (Sesión 2)

### Bug #37: Justificante médico obligatorio solo visualmente (sin validación server-side)
- **Problema**: En `solicitarVacacion.php`, el JS cambiaba el label a "Justificante médico *" al seleccionar tipo "baja", pero no había validación server-side. Si se enviaba una baja sin justificante, se aceptaba sin archivo.
- **Solución**: Validación en `Vacaciones::solicitarVacacion()` que verifica si tipo es "baja" y no hay fichero. Si falta, muestra error y recarga con datos previos. JS actualizado para marcar `required=true/false` dinámicamente.
- **Archivos**: `Vacaciones.php`, `solicitarVacacion.php`

### Bug #38: Tipo "ausencia" no crea jornadas (falta elseif en solicitarVacacion)
- **Problema**: Solo existían `if` para "vacaciones" y `elseif` para "baja". Ausencia justificada no creaba jornadas.
- **Solución**: Añadido `elseif ($tipoVacacion === 'ausencia')` + método `crearJornadasAusencia()`. Actualizado `actualizarJornadasVacaciones()`, `eliminarJornadasPendientes()`, `revertirJornadasCanceladas()` para incluir "ausencia". Badge "ausencia" añadido en 6 vistas.
- **Archivos**: `Vacaciones.php`, `detalleEmpleado.php`, `historialEmpleado.php`, `detalleJornada.php`, `reporteMensualEmpleado.php`, `listadoGlobal.php`, `calendarioJornadas.php`

### Mejora: Reorganización sidebar y títulos inclusivos
- **Sidebar**: "Control Horario" → "Mi Control Horario", "Vacaciones" → "Mis Vacaciones" (3 roles)
- **Sidebar admin**: "Listado General" movido de Vacaciones a Admin. Horario, renombrado a "Vacaciones Global"
- **Vistas**: Títulos actualizados: "Mis Vacaciones/Bajas/Ausencias", "Solicitar Vacación/Baja/Ausencia", "Gestión de Vacaciones/Bajas/Ausencias", botón "Vacaciones Global"
- **Permisos BD**: `rolesbase.permisos` actualizados para roles 1, 3 y 4
- **Nuevo método**: `AdministracionHorario::listadoVacaciones()` para acceso desde sidebar admin
- **Archivos**: `misVacaciones.php`, `solicitarVacacion.php`, `listadoVacaciones.php`, `AdministracionHorario.php`, BD `rolesbase.permisos`

### Punto de reanudación
**Pruebas pendientes**:
- VA9: Aprobar vacaciones solapadas con otras aprobadas → error
- VA17: Bloqueo fichaje en día con baja aprobada
- VA11: Vacaciones donde ya existe jornada abierta → reutiliza jornada
- VA12: Subir justificante después de crear solicitud de baja
- V11-V16: Pruebas rol visitante
- SEL-1 a SEL-5: Selects de solicitarModificacion
- 13: Reportes (CSV, PDF)
- 14: Configuración horario admin

## Cambios 2026-06-08 (Sesión 3)

### Bug #39: Clases CSS orange-* no compiladas en Tailwind (ausencia invisible en calendario)
- **Problema**: Las clases `bg-orange-200` y `text-orange-700` no existen en `estilostailwind.css` compilado. La celda de ausencia en el calendario aparecía transparente/invisible.
- **Solución**: Cambiado a `bg-pink-100 text-pink-700` (sí compilado). Aplicado en ambas vistas de calendario.
- **Archivos**: `miCalendario.php`, `calendarioJornadas.php`

### Mejora: Calendario de Jornadas (admin)
- Leyenda: añadido "Ausencia" (rosa) separado de "Incompleta" (amarillo)
- Filtro empleados: quitado "Todos", primer opción "Seleccionar empleado...", solo muestra datos al seleccionar uno
- Controlador: `$idEmpleado` nulo si no se selecciona empleado, no carga jornadas por defecto
- **Archivos**: `calendarioJornadas.php`, `AdministracionHorario.php`

### Mejora: Mi Calendario (nuevo - empleado)
- Nuevo método `ControlHorario::miCalendario()` — muestra calendario del empleado logado (sin selector de empleado)
- Nueva vista `controlHorario/miCalendario.php` — solo filtro mes/año, leyenda completa con Ausencia
- Accesible desde sidebar "Mi Control Horario → Mi Calendario" (3 roles)
- **Archivos**: `ControlHorario.php`, `miCalendario.php`

### Mejora: Sidebar reorganizado (permanente)
- "Control Horario" → "Mi Control Horario" (3 roles)
- "Vacaciones" → "Mis Permisos" como nombre de grupo (3 roles)
- "Mis Vacaciones" → "Mis Permisos" como subitem (3 roles)
- Admin. Horario: añadido "Calendario Jornadas" y renombrado "Vacaciones Global" → "Permisos Global"
- Mi Control Horario: añadido "Mi Calendario" (3 roles)
- **Archivos**: BD `rolesbase.permisos` (roles 1, 3, 4)

### Mejora: Títulos inclusivos en vistas
- `misVacaciones.php`: título → "Mis Permisos: Vacaciones / Bajas / Ausencias"
- `solicitarVacacion.php`: título → "Solicitar Permiso"
- `listadoVacaciones.php`: título → "Gestión de Permisos"
- `listadoGlobal.php`: "Vacaciones pendientes" → "Permisos pendientes"
- Eliminado botón "Vacaciones Global" de `misVacaciones.php` (ya en sidebar admin)

### Bug #40: subirJustificante() causaba recursión infinita y out of memory
- **Problema**: En `Vacaciones::subirJustificante()` línea 274, se llamaba `$this->subirJustificante()` (el propio método público del controlador) en vez de `$this->procesarArchivoJustificante()` (el método privado que procesa el archivo). Esto causaba recursión infinita → `Allowed memory size exhausted`.
- **Solución**: Cambiado `$this->subirJustificante()` por `$this->procesarArchivoJustificante()`.
- **Archivos**: `Vacaciones.php`

### Bug #41: resolverSolicitud muestra "Nueva fecha/hora propuesta: 30/11/-0001 00:00" para solicitudes tipo omitir
- **Problema**: En `resolverSolicitud.php`, los campos "Nueva fecha/hora propuesta" y "Tipo propuesto" se mostraban para solicitudes tipo "omitir", donde `nuevafechahora = 0000-00-00 00:00:00` y `nuevotipofichaje = entrada` son valores por defecto sin sentido.
- **Solución**: Ocultar "Nueva fecha/hora propuesta" cuando el valor es `0000-00-00 00:00:00`, y ocultar "Tipo propuesto" cuando `tipomodificacion === 'omitir'`. Los tipos "insertar" y "modificar" no se ven afectados.
- **Archivos**: `resolverSolicitud.php`

### Pruebas completadas esta sesión (2026-06-08 sesión 4)
- V11: Visitante subir justificante ✅
- V12: Visitante ve solo sus datos ✅
- V13: Visitante NO puede resolver sus propias vacaciones ✅
- V14: Visitante NO puede acceder a Admin. Horario ✅
- V15: Visitante NO puede acceder a Reportes ✅
- V16: Visitante NO puede fichar en día con baja aprobada ✅
- VA11: Vacaciones sobre jornada abierta → reutiliza jornada ✅
- VA15: Solicitar vacaciones en pasado → se permite ✅
- VA-NEW-4: Omitir fichaje → vacaciones permitidas después ✅
- **Problema**: En `Vacaciones::subirJustificante()` línea 274, se llamaba `$this->subirJustificante()` (el propio método público del controlador) en vez de `$this->procesarArchivoJustificante()` (el método privado que procesa el archivo). Esto causaba recursión infinita → `Allowed memory size exhausted`.
- **Solución**: Cambiado `$this->subirJustificante()` por `$this->procesarArchivoJustificante()`.
- **Archivos**: `Vacaciones.php`

### Bug #41: resolverSolicitud muestra campos sin sentido para solicitudes tipo "omitir"
- VA4: Aprobar vacaciones ✅ — jornadas marcadas vacaciones (morado en calendario)
- VA5: Aprobar baja médica ✅ — jornadas marcadas baja (rojo en calendario)
- VA6: Cancelar baja ✅ — jornadas revierten a abierta (azul en calendario)
- VA9: Aprobar vacaciones solapadas ✅ — bloquea con error
- VA12: Subir justificante post-creación ✅ — no aplica (obligatorio antes)
- VA17: Bloqueo fichaje con baja aprobada ✅ — bloquea con mensaje
- Ausencia pendiente/aprobada ✅ — se ve rosa en calendario
- Nota UX: En calendario no se distingue pendiente vs aprobada (mismo color). Estado se consulta en Permisos Global. Decisión: dejar como está.

### Integración Usuarios ↔ Configuración Horario (Puntos 1 y 2) ✅

Implementada la gestión automática de `configuracionhorario` al crear/editar usuarios desde el CRUD de Usuarios. Si algo falla en config horario, el CRUD de usuarios sigue funcionando normalmente (try-catch vacío).

#### Cambios en `Usuarios.php`

1. **Constructor (línea 13)**: Añadido `$this->modeloConfigHorario = $this->modelo('ModeloConfiguracionHorario');`
2. **`crearUsuario()` (líneas 274-277)**: Llamada a `crearConfigHorarioSiCorresponde($ins, (int)$_POST['rol'])` dentro de `try-catch`, antes del envío de email de confirmación.
3. **`editarUsuario()` (líneas 442-445)**: Llamada a `actualizarConfigHorarioSegunRol((int)$_POST['id'], (int)$_POST['rol'])` dentro de `try-catch`.
4. **`crearConfigHorarioSiCorresponde()` (líneas 728-746)**: Función privada nueva.
   - Si `rol == 1` (cliente) → sale sin hacer nada (no debe fichar).
   - Si `rol != 1` y NO tiene config → crea registro con `debeFichar=1`, `jornadatipohoras=8.00`, `horarioentrada='09:00:00'`, `horariosalida='18:00:00'`, `toleranciaminutos=10`.
   - Si `rol != 1` y SÍ tiene config → sale sin hacer nada (ya existe).
5. **`actualizarConfigHorarioSegunRol()` (líneas 748-788)**: Función privada nueva.
   - Si `rol == 1` (cliente) → si tiene config, pone `debeFichar=0` manteniendo otros campos.
   - Si `rol != 1` y NO tiene config → crea registro con `debeFichar=1` y valores por defecto.
   - Si `rol != 1` y SÍ tiene config con `debeFichar == 0` → actualiza a `debeFichar=1` manteniendo otros campos.
   - Si `rol != 1` y tiene config con `debeFichar == 1` → no hace nada (ya está correcto).

#### Registro de cambios

| Fecha | Archivo | Descripción |
|-------|---------|-------------|
| 2026-06-08 | `app/controlers/Usuarios.php` (constructor) | Añadido modelo `ModeloConfiguracionHorario` |
| 2026-06-08 | `app/controlers/Usuarios.php` (crearUsuario) | Llamada a `crearConfigHorarioSiCorresponde()` en try-catch |
| 2026-06-08 | `app/controlers/Usuarios.php` (editarUsuario) | Llamada a `actualizarConfigHorarioSegunRol()` en try-catch |
| 2026-06-08 | `app/controlers/Usuarios.php` (nuevas funciones) | `crearConfigHorarioSiCorresponde()` y `actualizarConfigHorarioSegunRol()` |

#### Pruebas manuales pendientes — Usuarios ↔ Config Horario

| # | Prueba | Estado |
|---|--------|--------|
| USR-1 | Crear usuario con rol admin (rol=0) → se crea config con `debeFichar=1` | 🔲 |
| USR-2 | Crear usuario con rol cliente (rol=1) → NO se crea config (no debe fichar) | 🔲 |
| USR-3 | Crear usuario con rol técnico (rol=2) → se crea config con `debeFichar=1` | 🔲 |
| USR-4 | Crear usuario con rol visitante (rol=3) → se crea config con `debeFichar=1` | 🔲 |
| USR-5 | Editar usuario cambiando rol de técnico a cliente → config actualiza `debeFichar=0` | 🔲 |
| USR-6 | Editar usuario cambiando rol de cliente a técnico → si no tiene config se crea con `debeFichar=1` | 🔲 |
| USR-7 | Editar usuario cambiando rol de cliente a técnico → si ya tiene config con `debeFichar=0` se actualiza a `debeFichar=1` | 🔲 |
| USR-8 | Editar usuario con mismo rol (no cliente) → no se modifica config | 🔲 |
| USR-9 | Crear usuario con rol no-cliente que ya tiene config (caso improbable) → no se duplica config | 🔲 |
| USR-10 | Verificar que si falla la creación de config, el usuario se crea correctamente y el email se envía | 🔲 |
| USR-11 | Verificar en Administración Horario → Configuración que los nuevos usuarios aparecen con config por defecto | 🔲 |

### Feature: Restricción de acceso CHOVA por debeFichar (2026-06-09)

Si un usuario tiene `debeFichar=0` en `configuracionhorario` o no tiene registro, pierde acceso al módulo CHOVA (ControlHorario y Vacaciones).

#### Lógica implementada

1. **Login.php**: Al iniciar sesión, se consulta `empleadoDebeFichar()` y se guarda en `$_SESSION['debeFichar']` (1 o 0).
2. **menu-sidebar-desktop.php y menu-sidebar-mobile.php**: Si `$_SESSION['debeFichar'] == 0`, los bloques "ControlHorario" y "Vacaciones" se ocultan del sidebar.
3. **ControlHorario.php constructor**: Si `$_SESSION['debeFichar'] == 0`, redirige a `/Inicio`.
4. **Vacaciones.php constructor**: Si `$_SESSION['debeFichar'] == 0`, redirige a `/Inicio`.
5. **ModeloConfiguracionHorario::empleadoDebeFichar()**: Devuelve `true` solo si existe registro con `debeFichar=1`. Si no hay registro, devuelve `false`.

#### Archivos modificados

| Fecha | Archivo | Descripción |
|-------|---------|-------------|
| 2026-06-09 | `app/controlers/Login.php` (acceder) | Añadido `$_SESSION['debeFichar']` después de iniciar sesión |
| 2026-06-09 | `app/controlers/Login.php` (fichar) | Añadido `$_SESSION['debeFichar']` después de iniciar sesión |
| 2026-06-09 | `app/views/includes/menu-sidebar-desktop.php` | Filtro: oculta ControlHorario y Vacaciones si `debeFichar=0` |
| 2026-06-09 | `app/views/includes/menu-sidebar-mobile.php` | Filtro: oculta ControlHorario y Vacaciones si `debeFichar=0` |
| 2026-06-09 | `app/controlers/ControlHorario.php` | Guard en constructor: si `debeFichar=0`, redirige a `/Inicio` |
| 2026-06-09 | `app/controlers/Vacaciones.php` | Guard en constructor: si `debeFichar=0`, redirige a `/Inicio` |
| 2026-06-09 | `app/views/usuarios/altaUsuarios/altaUsuarios.php` | Añadido `<option value="3">Visitante</option>` al select de rol |
| 2026-06-09 | `app/views/usuarios/actualizarUsuarios/actualizarUsuarios.php` | Select de rol dinámico: Admin, Cliente, Visitante (+ Técnico si ya lo es) |
| 2026-06-09 | `app/views/administracionHorario/configuracionHorario.php` | Añadido `3 => 'Visitante'` al array de roles |

#### Migración de usuarios existentes

Se creó un método temporal `migrarConfigHorario()` en `AdministracionHorario.php` que inserta registros en `configuracionhorario` para todos los usuarios no-cliente activos que no tengan registro, con `debeFichar=0`. Se añadió un permiso temporal `/AdministracionHorario/migrarConfigHorario` al admin (id=1) en `rolesbase.permisos`.

#### Comportamiento esperado

| debeFichar | Rol | Acceso CHOVA | Acceso CRM |
|-----------|-----|--------------|------------|
| 1 | Admin (0) | Sí (sidebar + URL) | Sí |
| 0 | Admin (0) | No (sidebar oculto, URL redirige) | Sí |
| 1 | Técnico (2) | Sí (sidebar + URL) | Sí |
| 0 | Técnico (2) | No (sidebar oculto, URL redirige) | Sí |
| 1 | Visitante (3) | Sí (sidebar + URL) | No (solo CHOVA) |
| 0 | Visitante (3) | No (atascado en login, sin acceso a nada) | No |
| — | Cliente (1) | No (no tiene permisos CHOVA en rolesbase) | Sí |

**Nota**: Un visitante con `debeFichar=0` queda atascado en el login porque su único destino es `/ControlHorario/fichar` y el guard lo redirige a `/Inicio` que no tiene permisos. Esto es coherente: si no está habilitado para fichar, no tiene nada que hacer en el sistema.

#### Pruebas pendientes — Restricción debeFichar

| # | Prueba | Estado |
|---|--------|--------|
| DF-1 | Login como admin con `debeFichar=1` → ver "Mi Control Horario" y "Mis Permisos" en sidebar | ✅ |
| DF-2 | Login como visitante con `debeFichar=1` → ver "Mi Control Horario" y "Mis Permisos" en sidebar | ✅ |
| DF-3 | Poner `debeFichar=0` al admin, cerrar sesión y logar → CHOVA desaparece del sidebar | ✅ |
| DF-4 | Con `debeFichar=0`, acceder a `/ControlHorario/fichar` por URL → redirige a `/Inicio` | ✅ |
| DF-5 | Con `debeFichar=0`, acceder a `/Vacaciones/solicitarVacacion` por URL → redirige a `/Inicio` | ✅ |
| DF-6 | Con `debeFichar=0` como admin, el resto del CRM funciona normal (Incidencias, Dashboard, etc.) | ✅ |
| DF-7 | Restaurar `debeFichar=1`, cerrar sesión y logar → CHOVA vuelve a aparecer | ✅ |
| DF-8 | Login como visitante con `debeFichar=0` → atascado en login (no tiene acceso a nada) | ✅ |
| DF-9 | Migración: ejecutar `/AdministracionHorario/migrarConfigHorario` → crea registros con `debeFichar=0` | ✅ Ejecutado |
| DF-10 | Configuración Horario muestra rol "Visitante" correcto (antes mostraba "Desconocido") | ✅ |
| DF-11 | Alta de usuarios: select de rol muestra Admin, Cliente y Visitante | ✅ |
| DF-12 | Editar usuario: select de rol muestra Admin, Cliente, Visitante (+ Técnico si ya lo es) | ✅ |

#### Limpieza post-migración pendiente

- [ ] Eliminar método `migrarConfigHorario()` de `AdministracionHorario.php`
- [ ] Eliminar vista `migrarResultado.php` de `app/views/administracionHorario/`
- [ ] Eliminar permiso `/AdministracionHorario/migrarConfigHorario` de `rolesbase.permisos` (rol admin, id=1)

---

## Cambios 2026-08-08

### Fix: Fichajes corregidos en selects y detalle de jornada

1. **ControlHorario.php** — `solicitarModificacion()`: Fichajes con `corregido=1` filtrados del array `$fichajesRecientes`. Ya no aparecen en el select "Fichaje a corregir" (ni para modificar ni para omitir).

2. **solicitarModificacion.php** — Opciones del select de fichajes: Eliminado el texto de observaciones del option. Antes: `"04/08/2026 10:00 - Salida (Fichaje insertado por aprobación de solicitud #5)"`. Ahora: `"04/08/2026 10:00 - Salida"`.

3. **detalleJornada.php** — Añadida columna "Solicitud" que extrae el nº de solicitud del campo `observaciones` (regex `solicitud #N`) y lo muestra como link a `resolverSolicitud/N`. La columna "Observaciones" ahora omite la parte de solicitud (se elimina con regex), mostrando solo el resto del texto.

| Fecha | Archivo | Descripción |
|-------|---------|-------------|
| 2026-08-08 | `app/controlers/ControlHorario.php` | Filtrar fichajes corregidos de `$fichajesRecientes` en `solicitarModificacion()` |
| 2026-08-08 | `app/views/controlHorario/solicitarModificacion.php` | Eliminar observaciones del texto de opciones del select |
| 2026-08-08 | `app/views/controlHorario/detalleJornada.php` | Columna "Solicitud" con link a resolverSolicitud, observaciones limpias |

### Bug #42: crearConfigHorarioSiCorresponde no crea config al crear usuario (Tecnico2 id=149 sin config) — ✅ CERRADO

- **Problema**: Al crear el usuario Tecnico2 (id=149, rol=2, técnico), `crearConfigHorarioSiCorresponde()` no creó el registro en `configuracionhorario`. El `try-catch` vacío en `Usuarios.php:276` oculta cualquier error silenciosamente.
- **Consecuencia**: Tecnico2 no aparece en Administración Horario → Configuración, no tiene `debeFichar`, y no puede acceder a CHOVA.
- **Causa raíz adicional**: `obtenerTodosConfig()` hace `FROM configuracionhorario ch LEFT JOIN usuarios u`, por lo que usuarios SIN config nunca aparecen en la vista de configuración. El admin no puede activarles `debeFichar`.
- **Solución aplicada**:
  1. `obtenerTodosConfig()` cambiado a `FROM usuarios u LEFT JOIN configuracionhorario ch ON u.id = ch.idempleado AND ch.eliminado = 0 WHERE u.rol IN (0,2,3) AND u.activo = 1`, usando COALESCE para valores por defecto. ✅
  2. `crearConfigHorarioSiCorresponde()` eliminada por completo. Criterio: el admin gestiona `debeFichar` manualmente desde Configuración Horario. ✅
  3. `actualizarConfigHorarioSegunRol()` simplificada: solo pone `debeFichar=0` si el usuario cambia a rol cliente (1) y ya tiene config. No crea config, no actualiza `debeFichar` a 1. ✅
  4. Config para usuarios existentes sin registro: el admin los ve en la vista de Configuración (gracias al fix de `obtenerTodosConfig`) y puede crearles config manualmente. ✅
- **Archivos afectados**: `ModeloConfiguracionHorario.php`, `Usuarios.php`, `configuracionHorario.php`

### Usuarios con permisos para fichar (estado actual)

| id | Nombre | Rol | debeFichar |
|----|--------|-----|-----------|
| 1 | Test Pruebas | admin (0) | 1 |
| 113 | Juan Rodriguez | admin (0) | 1 |
| 125 | Roberto Ariani | admin (0) | 1 |
| 111 | Jenny Ruiz Razuri | tecnico (2) | 1 |
| 112 | Técnico Tecnico | tecnico (2) | 1 |
| 114 | juan Rodriguez | tecnico (2) | 1 |
| 126 | Francisco M. López | tecnico (2) | 1 |
| 127-129 | Fran López (x3) | tecnico (2) | **sin config** |
| 130 | Aurelio Núñez Cela | tecnico (2) | 1 |
| 131 | Javier Arrebola | tecnico (2) | 1 |
| 132-134 | dddd, bb, wqqqq | tecnico (2) | **sin config** |
| 148 | Visitante Prueba | visitante (3) | **0** |
| 149 | Tecnico2 probando | tecnico (2) | **sin config** |

### Pruebas SEL completadas

| # | Prueba | Estado |
|---|--------|--------|
| SEL-1 | Cargar vista solicitarModificacion → select muestra fichajes del mes seleccionado | ✅ |
| SEL-2 | Tipo "Insertar" funciona sin select de jornada (auto-determina jornada) | ✅ |
| SEL-3 | Tipo "Modificar" muestra select de fichaje + tipo + hora | ✅ |
| SEL-4 | Tipo "Omitir" muestra select de fichaje (sin tipo ni hora) | ✅ |
| SEL-5 | Seleccionar fichaje rellena idjornada original automáticamente | ✅ |

### Pruebas pendientes — Bloque actual

| # | Prueba | Estado |
|---|--------|--------|
| Bug #42 | `crearConfigHorarioSiCorresponde` eliminada, config se gestiona manualmente por admin | ✅ Cerrado |
| Bug #42a | `obtenerTodosConfig()` no muestra usuarios sin config en vista configuración | ✅ Fix aplicado y verificado |
| USR-1 a USR-11 | Integración Usuarios ↔ Config Horario | ✅ Verificado (ver detalle abajo) |
| DF-1 a DF-12 | Restricción debeFichar | ✅ Completado |
| 13 | Reportes (CSV, PDF) | 🔧 En progreso (13.1 ✅, 13.2 ✅ comportamiento correcto) |
| TZ-1 | Timezone producción | 🔲 |
| Limpieza | Eliminar migrarConfigHorario, migrarResultado.php, permiso temporal | 🔲 |
| Reportes+ | Añadir columnas de horas esperadas, déficit/exceso y retrasos a reportes | 🔲 Pendiente (al final) |
| Listado+ | Indicadores de retrasos y horas extra en listado global admin | 🔲 Pendiente (al final) |

### Bug #42: obtenerTodosConfig no muestra usuarios sin config + crearConfigHorarioSiCorresponde no crea config — ✅ CERRADO

- **Problema original**: Al crear Tecnico2 (rol=2), `crearConfigHorarioSiCorresponde()` no creó el registro en `configuracionhorario`. Además `obtenerTodosConfig()` hacía `FROM configuracionhorario ch LEFT JOIN usuarios u`, excluyendo usuarios sin config de la vista de configuración.
- **Fix aplicado**:
  1. `ModeloConfiguracionHorario::obtenerTodosConfig()`: Cambiado a `FROM usuarios u LEFT JOIN configuracionhorario ch ON u.id = ch.idempleado AND ch.eliminado = 0 WHERE u.rol IN (0, 2, 3) AND u.activo = 1`. Usa COALESCE para valores por defecto.
  2. `ModeloConfiguracionHorario::obtenerEmpleadosActivos()`: Mismo patrón.
  3. `configuracionHorario.php`: Cambiado `$emp->id` por `$emp->idempleado`. Formateados horarioentrada/horariosalida como `H:i` en JS.
- **Criterio modificado (decisión del usuario)**: NO se crea automáticamente el registro en `configuracionhorario` al crear usuarios de ningún rol. El admin gestiona `debeFichar` manualmente desde Configuración Horario.
- **Cambios en Usuarios.php**:
  - Eliminada `crearConfigHorarioSiCorresponde()` por completo.
  - Eliminado el `try-catch` vacío que la llamaba en `crearUsuario()`.
  - `actualizarConfigHorarioSegunRol()` simplificada: solo pone `debeFichar=0` si el usuario cambia a rol cliente (1) y ya tiene config. No crea config, no actualiza `debeFichar` a 1.
- **Archivos**: `ModeloConfiguracionHorario.php`, `configuracionHorario.php`, `Usuarios.php`

| # | Prueba | Estado |
|---|--------|--------|
| USR-1 | Crear admin → no crea config automáticamente | ✅ Correcto (el admin lo gestiona manualmente) |
| USR-2 | Crear cliente → no crea config | ✅ |
| USR-3 | Crear técnico → no crea config automáticamente | ✅ (criterio del usuario) |
| USR-4 | Crear visitante → no crea config automáticamente | ✅ (criterio del usuario) |
| USR-5 | Editar a cliente → pone debeFichar=0 si tiene config | ✅ |
| USR-6 | Editar de cliente a técnico → no crea config, no actualiza debeFichar | ✅ (criterio del usuario) |
| USR-7 | Editar de cliente a técnico con config existente → no actualiza debeFichar a 1 | ✅ (criterio del usuario) |
| USR-8 | Editar con mismo rol → no se modifica config | ✅ |
| USR-9 | Crear no-cliente que ya tiene config → no se duplica | ✅ (ya no aplica) |
| USR-10 | Si falla config, usuario se crea igual | ✅ (ya no aplica) |
| USR-11 | Nuevos usuarios aparecen en Configuración Horario | ✅ Verificado en #14 |

### Cambio: Sidebar — Vacaciones movido dentro de Mi Control Horario

- **Problema**: El grupo "Mis Permisos" (icono sombrilla) era un grupo separado en el sidebar con solo 2 sub-items ("Solicitar" y "Mis Permisos"). El botón "Nueva solicitud" ya existe en la vista `misVacaciones`.
- **Solución**:
  1. Eliminado el grupo "Vacaciones" como entrada principal del sidebar en BD (`rolesbase.permisos` roles id=1, 3, 4).
  2. Añadido `["/Vacaciones/misVacaciones","Vacaciones/Bajas"]` como sub-item de "Mi Control Horario".
  3. Eliminado `["/Vacaciones/solicitarVacacion","Solicitar"]` como item de menú (botón ya existe en misVacaciones).
  4. Links AJAX de Vacaciones movidos al array de links del primer sub-item de ControlHorario.
  5. Filtro `debeFichar` en sidebar cambiado de `in_array($menuKey, ['ControlHorario', 'Vacaciones'])` a `$menuKey === 'ControlHorario'`.
- **Archivos**: BD `rolesbase.permisos`, `menu-sidebar-desktop.php`, `menu-sidebar-mobile.php`

### Cambio: Tolerancia default de 10 a 5 minutos

- **Problema**: La tolerancia por defecto estaba hardcodeada a 10 minutos en 4 sitios del código y en los registros existentes de BD.
- **Solución**: Cambiado default de 10 a 5 en:
  1. `configuracionHorario.php`: valor del input de tolerancia
  2. `ModeloConfiguracionHorario.php`: COALESCE en `obtenerTodosConfig()` y `obtenerEmpleadosActivos()`
  3. `AdministracionHorario.php`: valor default en POST y migración
  4. `Usuarios.php`: valor default en `crearConfigHorarioSiCorresponde()` y `actualizarConfigHorarioSegunRol()`
  5. BD: Actualizados registros existentes de `toleranciaminutos=10` a `5`
- **Archivos**: `configuracionHorario.php`, `ModeloConfiguracionHorario.php`, `AdministracionHorario.php`, `Usuarios.php`, BD `configuracionhorario`

### Criterio: Eliminación de usuarios y datos CHOVA

- **Eliminación de usuarios**: `borrarUsuario()` hace soft delete (`activo = -1`). No hay DELETE físico.
- **Tablas CHOVA**: Nunca usan DELETE físico. Solo soft delete (`eliminado=1`) y correcciones (`corregido=1`). Cumple RDL 8/2019.
- **Sin FK constraints en cascada**: No hay FOREIGN KEY constraints entre tablas CHOVA y `usuarios`. Las relaciones son por convención (columnas `idempleado`).
- **Comportamiento actual por estados**:

| Estado | `activo` | `debeFichar` | Acceso CHOVA | Datos históricos visibles por admin |
|--------|-----------|-------------|-------------|-------------------------------------|
| Usuario activo que ficha | 1 | 1 | Sí (fichar + historial + solicitudes) | Sí |
| Usuario activo sin fichar | 1 | 0 | No (sidebar oculto, URL redirige) | Sí (admin ve sus datos en listado global) |
| Usuario inactivo | -1 | * | No puede logarse | No aparece en filtros (`activo = 1`) |

- **Conclusión**: No se necesitan cambios adicionales. El soft delete + filtro `activo = 1` es suficiente.

### Campos de configuración horario: uso actual y previsto

| Campo | Uso actual | Uso previsto (RDL 8/2019) | Estado |
|-------|------------|--------------------------|--------|
| `debeFichar` | Controla acceso a CHOVA (0 = sin acceso, 1 = con acceso) | Cumplimiento RDL 8/2019 | ✅ Implementado |
| `jornadatipohoras` | Solo informativo en vista Configuración | Comparar horas reales vs esperadas → marcar jornada incompleta por horas | 🔲 Pendiente (al final) |
| `horarioentrada` | Solo informativo en vista Configuración | Detectar retrasos (primer fichaje > entrada + tolerancia) | 🔲 Pendiente (al final) |
| `horariosalida` | Solo informativo en vista Configuración | Detectar salidas anticipadas | 🔲 Pendiente (al final) |
| `toleranciaminutos` | Solo informativo en vista Configuración (default cambiado de 10 a 5) | Margen para retrasos (entrada + tolerancia) | 🔲 Pendiente (al final) |

### Cambio: Tolerancia default de 10 a 5 minutos

- **Problema**: La tolerancia por defecto estaba hardcodeada a 10 minutos en 4 sitios del código y en los registros existentes de BD.
- **Solución**: Cambiado default de 10 a 5 en:
  1. `configuracionHorario.php`: valor del input de tolerancia
  2. `ModeloConfiguracionHorario.php`: COALESCE en `obtenerTodosConfig()` y `obtenerEmpleadosActivos()`
  3. `AdministracionHorario.php`: valor default en POST y migración
  4. `Usuarios.php`: valor default en `crearConfigHorarioSiCorresponde()` (ya eliminada) y `actualizarConfigHorarioSegunRol()`
  5. BD: Actualizados registros existentes de `toleranciaminutos=10` a `5`
- **Archivos**: `configuracionHorario.php`, `ModeloConfiguracionHorario.php`, `AdministracionHorario.php`, `Usuarios.php`, BD `configuracionhorario`

### Pendientes añadidos (al final del proyecto)

- **Reportes+**: Añadir columnas de horas esperadas (`jornadatipohoras`), déficit/exceso (`horastotales - jornadatipohoras`), y retrasos (primer fichaje > `horarioentrada + toleranciaminutos`) a los reportes.
- **Listado+**: Indicadores de retrasos y horas extra en el listado global de admin.

### Test #13: Reportes (CSV, PDF) — En progreso

#### Pruebas completadas

| # | Prueba | Estado | Notas |
|---|--------|--------|-------|
| 13.1 | Acceso a selector de reportes | ✅ | Página carga con 3 tarjetas: CSV, Empleado, Global |
| 13.2 | Empleados en select muestra todos los no-cliente activos | ✅ | Comportamiento correcto (ver nota abajo) |

#### Nota: Comportamiento del select de empleados (13.2)

El método `obtenerEmpleadosQueFichan()` en `ModeloControlHorario.php` devuelve **todos los usuarios no-cliente activos** (`rol IN (0, 2, 3) AND activo = 1`), sin filtrar por `debeFichar`. Se usa en 3 lugares:

1. `ReportesHorario.php:35` — selector de reportes
2. `AdministracionHorario.php:40` — listado global (filtro empleado)
3. `AdministracionHorario.php:398` — calendario de jornadas (filtro empleado)

Este comportamiento es **correcto e intencionado** porque:

| Estado del empleado | `debeFichar` | ¿Tiene datos históricos? | ¿Debe aparecer en Reportes/Admin? |
|---|---|---|---|
| Activo, ficha actualmente | 1 | Sí | Sí |
| Activo, fichó antes pero ya no | 0 | Sí (históricos) | Sí — el admin puede necesitar reportes pasados |
| Activo, nunca ha fichado | 0 o sin config | No | Sí — aparece pero sin datos, no hace daño |
| Inactivo | - | Sí | No — filtrado por `activo = 1` |

**Mejora propuesta (no aplicar aún, ver más tarde):**
- Renombrar `obtenerEmpleadosQueFichan()` → `obtenerEmpleadosNoClienteActivos()` para que el nombre refleje lo que realmente hace. La query y el resultado no cambian, solo el nombre es misleading.
- Actualizar los 3 usos: `AdministracionHorario.php` (líneas 40 y 398), `ReportesHorario.php` (línea 35), `ModeloControlHorario.php` (línea 171).

#### Pruebas pendientes

| # | Prueba | Estado |
|---|--------|--------|
| 13.3 | CSV - todos los empleados | 🔲 |
| 13.4 | CSV - empleado específico | 🔲 |
| 13.5 | CSV - sin datos (rango vacío) | 🔲 |
| 13.6 | Reporte mensual empleado (pantalla) | 🔲 |
| 13.7 | Reporte mensual - estados (vacaciones, baja, ausencia) | 🔲 |
| 13.8 | Reporte mensual empleado (PDF) | 🔲 |
| 13.9 | Reporte global empresa (pantalla) | 🔲 |
| 13.10 | Reporte global - link a detalle empleado | 🔲 |
| 13.11 | Reporte global empresa (PDF) | 🔲 |
| 13.12 | Reporte global - sin datos | 🔲 |
| 13.13 | Auditoría CSV | 🔲 |
| 13.14 | Permisos: visitante no puede acceder | 🔲 |
| 13.15 | Permisos: técnico no puede acceder | 🔲 |