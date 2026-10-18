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
| 14 | Configuración horario admin | ✅ Probar activar/debeFichar, horas jornada, tolerancia desde /AdministracionHorario/configuracionHorario |

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
- **Sidebar**: "Control Horario" → "Mi Control Horario", "Vacaciones" → sub-item "Vacaciones/Bajas" dentro de Mi Control Horario (3 roles)
- **Sidebar admin**: "Listado General" movido de Vacaciones a Admin. Horario, renombrado a "Vacaciones y permisos"
- **Vistas**: Títulos actualizados: "Mis Vacaciones / Bajas / Ausencias", "Solicitar Vacaciones / Baja / Permiso", "Solicitudes de vacaciones y permisos", botón "Nueva solicitud"
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
- "Vacaciones/Bajas" es un sub-item de "Mi Control Horario" (3 roles)
- Admin. Horario: añadido "Calendario Jornadas" y renombrado "Vacaciones Global" → "Permisos Global"
- Mi Control Horario: añadido "Mi Calendario" (3 roles)
- **Archivos**: BD `rolesbase.permisos` (roles 1, 3, 4)

### Mejora: Títulos inclusivos en vistas
- `misVacaciones.php`: título → "Mis Vacaciones / Bajas / Ausencias"
- `solicitarVacacion.php`: título → "Solicitar Vacaciones / Baja / Permiso"
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
| DF-1 | Login como admin con `debeFichar=1` → ver "Mi Control Horario" en sidebar | ✅ |
| DF-2 | Login como visitante con `debeFichar=1` → ver "Mi Control Horario" en sidebar | ✅ |
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

- **Problema**: El grupo "Vacaciones" era un grupo separado en el sidebar con solo 2 sub-items ("Solicitar" y "Mis Vacaciones"). El botón "Nueva solicitud" ya existe en la vista `misVacaciones`.
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

### Cambio: Colores de estados pendientes y ausencia en badges y calendarios

- **Problema**: Las clases `bg-orange-100 text-orange-700/800` no existen en el CSS compilado de Tailwind, causando que los badges de estados pendientes aparezcan sin color.
- **Problema**: `ausencia` aprobada usaba `bg-yellow-100 text-yellow-800` (igual que `incompleta`) en vez de `bg-pink-100 text-pink-800` como en el calendario.
- **Problema**: Los calendarios mostraban jornadas con estado `pendiente_*` como si fueran definitivas.
- **Solución**:
  1. Cambiados colores de badges pendientes: `pendiente_vacaciones` → morado claro con borde, `pendiente_baja` → rojo claro con borde, `pendiente_ausencia` → rosa claro con borde.
  2. Cambiado `ausencia` aprobada de `bg-yellow-100 text-yellow-800` a `bg-pink-100 text-pink-800` en todas las vistas.
  3. Los calendarios ya no muestran jornadas con estado `pendiente_*` (se ven como día vacío).
- **Archivos**: `historialEmpleado.php`, `detalleJornada.php`, `listadoGlobal.php`, `detalleEmpleado.php`, `reporteMensualEmpleado.php`, `listadoVacaciones.php`, `misVacaciones.php`, `miCalendario.php`, `calendarioJornadas.php`

### Pendientes añadidos (al final del proyecto)

- **Reportes+**: Añadir columnas de horas esperadas (`jornadatipohoras`) y déficit/exceso (`horastotales - jornadatipohoras`) a los reportes. ✅ Completado (ver abajo)
- **Listado+**: Indicadores de horas extra en listado global de admin. 🔲 Pendiente (al final)

### Test #13: Reportes (CSV, PDF) — ✅ Completado

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

#### Pruebas completadas

| # | Prueba | Estado | Notas |
|---|--------|--------|-------|
| 13.1 | Acceso a selector de reportes | ✅ | Página carga con 3 tarjetas: CSV, Empleado, Global |
| 13.2 | Empleados en select muestra todos los no-cliente activos | ✅ | Comportamiento correcto (ver nota arriba) |
| 13.3 | CSV - todos los empleados | ✅ | |
| 13.4 | CSV - empleado específico | ✅ | |
| 13.5 | CSV - sin datos (rango vacío) | ✅ | |
| 13.6 | Reporte mensual empleado (pantalla) | ✅ | |
| 13.7 | Reporte mensual - estados (vacaciones, baja, ausencia) | ✅ | |
| 13.8 | Reporte mensual empleado (PDF) | ✅ | |
| 13.9 | Reporte global empresa (pantalla) | ✅ | |
| 13.10 | Reporte global - link a detalle empleado | ✅ | |
| 13.11 | Reporte global empresa (PDF) | ✅ | |
| 13.12 | Reporte global - sin datos | ✅ | |

#### Pruebas pendientes

| # | Prueba | Estado |
|---|--------|--------|
| 13.13 | Auditoría CSV | ❌ No necesario — los reportes existentes ya exportan los datos |
| 13.14 | Permisos: visitante no puede acceder a Reportes | ✅ Verificado |
| 13.15 | Permisos: técnico no puede acceder a Reportes | ✅ Verificado |

### Bug CFG-7: Campos vacíos en configuración horario se guardan como 0

- **Problema**: Al editar la config de un empleado y dejar vacíos los campos `jornadatipohoras` o `toleranciaminutos`, se guardaban como `0` en la BD (MySQL convierte string vacío a 0).
- **Solución**: Validación server-side en `AdministracionHorario::configuracionHorario()`. Campos vacíos o no numéricos se reemplazan por defaults: `jornadatipohoras` → 8.00, `toleranciaminutos` → 5, `horarioentrada` → '09:00:00', `horariosalida` → '18:00:00'.
- **Archivos**: `AdministracionHorario.php` (líneas 329-334)

### Bug CFG-8: max de jornadatipohoras cambiado de 12 a 24

- **Cambio**: `<input type="number" name="jornadatipohoras" ... max="12">` → `max="24"` en `configuracionHorario.php`
- **Archivos**: `configuracionHorario.php`

### Corrección BD: Visitante Prueba (id=148)

- `jornadatipohoras` cambiado de 0.00 a 8.00, `toleranciaminutos` cambiado de 0 a 5

### Renombramiento: obtenerEmpleadosQueFichan() → obtenerEmpleadosNoClienteActivos()

- **Motivo**: El nombre `obtenerEmpleadosQueFichan` era misleading. Devuelve todos los usuarios no-cliente activos, no solo los que fichan.
- **Archivos**: `ModeloControlHorario.php` (definición), `AdministracionHorario.php` (2 usos: líneas 40 y 383)

### Feature: Reportes+ — Horas esperadas y diferencia en reportes

- **Reporte Mensual por Empleado** (`reporteMensualEmpleado`):
  - 2 tarjetas nuevas: "Horas esperadas" (`jornadatipohoras × diasTrabajados`) y "Diferencia" (`horasTotales - horasEsperadas`, verde si >= 0, rojo si < 0)
  - 2 columnas nuevas al final de la tabla: "Horas esp." (`jornadatipohoras` por jornada) y "Diferencia" (`horastotales - jornadatipohoras` por jornada)
- **Reporte Global por Empresa** (`reporteGlobalEmpresa`):
  - 2 tarjetas nuevas: "Horas esperadas" (suma por empleado) y "Diferencia" (verde/rojo)
  - 2 columnas nuevas al final de la tabla: "Horas esp." (`jornadatipohoras × diasstrabajados`) y "Diferencia" (`totalhoras - horasEsperadas` por empleado)
- **Valores por defecto** (si no hay config): `jornadatipohoras = 8.00` (COALESCE en las queries)
- **Modelo**: `obtenerResumenMensualEmpleado()` — Añadido JOIN con `configuracionhorario`, campos `j.id, jornadatipohoras, horarioentrada, toleranciaminutos`
- **Modelo**: `obtenerResumenMensualGlobal()` — Añadido JOIN con `configuracionhorario`, campo `jornadatipohoras`
- **Modelo**: Eliminado método `obtenerPrimerFichajePorJornada()` (columna Retraso eliminada)
- **Controlador**: `reporteMensualEmpleado()` — Cálculos de `horasEsperadas`, `diferencia`, eliminado cálculo de retrasos
- **Controlador**: `reporteGlobalEmpresa()` — Cálculos de `horasEsperadasTotal`, `diferenciaTotal`, `horasEsperadas` y `diferencia` por empleado
- **Archivos**: `ModeloReportesHorario.php`, `ReportesHorario.php`, `reporteMensualEmpleado.php`, `reporteGlobalEmpresa.php`

### Cambio: Jornadas pendientes se cuentan como incompletas en reportes

- **Problema**: Las jornadas con estado `pendiente_vacaciones`, `pendiente_baja`, `pendiente_ausencia` no se contaban como incompletas, causando discrepancia entre el número del header y las ✗ en la tabla.
- **Solución**: En `reporteMensualEmpleado()` (controlador), cambiado el `elseif` para que todas las jornadas que no sean completadas/vacaciones/baja/ausencia se cuenten como incompletas (incluyendo las pendientes).
- **Solución**: En `obtenerResumenMensualGlobal()` (modelo), cambiado el CASE de `jornadasincompletas` para incluir los estados `pendiente_*`.
- **Solución**: En `reporteMensualEmpleado.php`, la columna "Completada" ahora muestra ✓ para completadas/vacaciones/baja/ausencia y ✗ para todo lo demás (incluyendo pendientes).
- **Archivos**: `ReportesHorario.php`, `ModeloReportesHorario.php`, `reporteMensualEmpleado.php`

### Pendientes actualizados

| # | Tarea | Estado |
|---|-------|--------|
| CFG-7 | Validación server-side campos vacíos | ✅ Completado |
| CFG-8 | max jornadatipohoras cambiado a 24 | ✅ Completado |
| Renombramiento | obtenerEmpleadosQueFichan → obtenerEmpleadosNoClienteActivos | ✅ Completado |
| Reportes+ | Horas esperadas y diferencia en reportes | ✅ Completado |
| Retraso | Columna de retraso eliminada de reportes | ✅ Completado (eliminada) |
| Jornadas pendientes | Contadas como incompletas en reportes | ✅ Completado |
| Limpieza | Eliminar migrarConfigHorario, migrarResultado.php, permiso temporal | 🔲 Pendiente |
| Listado+ | Indicadores de horas esperadas y diferencia en listado global | ❌ No se implementa |
| TZ-1 | Verificar timezone MySQL vs PHP en producción | 🔲 Pendiente |
| Deploy | Checklist MIGRACION_PRODUCCION.md | 🔲 Pendiente |

### Cambio: Ocultar columna Tolerancia de la vista de configuración

- **Motivo**: La columna de retraso fue eliminada de los reportes, por lo que la tolerancia no se usa en ningún cálculo visible. Se oculta para evitar confusión, pero se mantiene en la BD y en la lógica interna por si se necesita en el futuro.
- **Cambios en `configuracionHorario.php`**:
  1. Ocultada columna `<th>Tolerancia (min)</th>` del thead de la tabla
  2. Ocultada celda `<td>` de tolerancia de cada fila del tbody
  3. Campo de tolerancia del modal cambiado de `<input type="number">` visible con label a `<input type="hidden">` — el JS `editarConfig()` sigue rellenándolo con el valor de la BD, por lo que se preserva al editar otros campos
- **No se modifica**: BD, controlador, modelo ni JS. La tolerancia sigue existiendo con valor por defecto 5 y se guarda correctamente al editar otros campos.

### Cambio: Sidebar labels y títulos de vistas

- **Problema**: Los labels del sidebar "Solicitudes Mod." y "Permisos Global" eran confusos.
- **Solución**:
  1. BD `rolesbase.permisos` (ids 1, 3, 4): "Solicitudes Mod." → "Modificaciones fichajes", "Permisos Global" → "Vacaciones y permisos"
  2. Vista `solicitudesModificacion.php`: Título cambiado a "Solicitudes de Modificación de fichajes"
  3. Vista `listadoVacaciones.php`: Título cambiado a "Solicitudes de vacaciones y permisos"
- **Archivos**: BD `rolesbase.permisos`, `solicitudesModificacion.php`, `listadoVacaciones.php`

### Cambio: PDFs abren en nueva ventana/pestaña

- **Problema**: Los links de generación de PDF redirigían en la misma ventana, perdiendo la vista actual.
- **Solución**:
  1. `reporteMensualEmpleado.php`: Link "Generar PDF" añadido `target="_blank"`
  2. `selectorReportes.php`: Botones "Generar PDF" usan `window.open()` en vez de `window.location.href`
  3. `reporteGlobalEmpresa.php`: Link "Generar PDF" añadido `target="_blank"`
- **Archivos**: `reporteMensualEmpleado.php`, `selectorReportes.php`, `reporteGlobalEmpresa.php`

### Cambio: Reportes PDF con horas esperadas y diferencia

- **Reporte PDF Mensual por Empleado** (`reporteHorarioEmpleado.php`):
  - Añadida fila "Horas esperadas" en la tabla de resumen (fondo morado claro)
  - Añadida fila "Diferencia" en la tabla de resumen (verde si >= 0, rojo si < 0)
  - Variables pasadas desde controlador: `horasEsperadas`, `diferencia`, `jornadasCompletas`, `jornadasIncompletas`

- **Reporte PDF Global Empresa** (`reporteHorarioGlobal.php`):
  - Añadidas filas "Jornadas completas", "Jornadas incompletas", "Horas esperadas", "Diferencia" en tabla de resumen
  - Añadidas columnas "Horas esp." y "Diferencia" en tabla por empleado
  - Variables calculadas en controlador: `horasEsperadas`, `diferencia` por empleado, totales globales

- **Controlador** `ReportesHorario.php`:
  - `reportePDFMensualEmpleado()`: Añadidos cálculos de `horasEsperadas`, `diferencia`, `jornadasCompletas`, `jornadasIncompletas` en `$datosPdf`
  - `reportePDFGlobalEmpresa()`: Añadidos cálculos de `horasEsperadas`, `diferencia`, totales en `$datos`

- **Archivos**: `reporteHorarioEmpleado.php`, `reporteHorarioGlobal.php`, `ReportesHorario.php`

### Pendientes actualizados (post-sesión 2026-08-24)

| # | Tarea | Estado |
|---|-------|--------|
| Limpieza | Eliminar migrarConfigHorario, migrarResultado.php, permiso temporal | 🔲 Pendiente |
| Listado+ | Indicadores de horas esperadas y diferencia en listado global | ❌ No se implementa (decisión del usuario) |
| TZ-1 | Verificar timezone MySQL vs PHP en producción | 🔲 Pendiente |
| Deploy | Checklist MIGRACION_PRODUCCION.md | 🔲 Pendiente |
| EXP-1 | PDF Empleado: reemplazar tabla de fichajes por tabla de jornadas resumidas | ✅ Completado |
| EXP-2 | Exportar Fichajes: añadir formato Excel (.xlsx) y PDF, además de CSV | ✅ Completado |

---

## Plan Final Aprobado — Exportación de Reportes (Sesión 2026-08-24)

### Cambio 1: PDF Empleado → Mostrar tabla de jornadas resumidas (como la pantalla)

**Archivo**: `app/views/documentos/reporteHorarioEmpleado.php`

Reemplazar la tabla de fichajes individuales (líneas 32-64) por una tabla de jornadas con las mismas columnas que la vista en pantalla:

| Columna | Contenido | Estilo |
|---------|-----------|--------|
| Fecha | `d/m/Y` | Texto normal |
| Estado | Badge con color según `estadojornada` (igual que pantalla) | Colores de fondo según estado |
| Horas | `horastotales` | Centrado |
| Horas esp. | `jornadatipohoras` | Centrado |
| Diferencia | `horastotales - jornadatipohoras` | Verde (+) / Rojo (-) |
| Completada | ✓ o ✗ | Centrado |

Los fichajes individuales se eliminan del PDF (ya disponibles en CSV/Excel).

**Controlador** `ReportesHorario.php`: El método `reportePDFMensualEmpleado()` ya pasa `$datos['resumen']` con los datos necesarios. Solo la vista necesita cambios.

### Cambio 2: Exportar Fichajes → CSV + Excel + PDF

#### 2a. Instalar PhpSpreadsheet

Añadir `phpoffice/phpspreadsheet` al `composer.json` existente en `public/composer.json` y ejecutar `composer update` en `public/`.

Esto añadirá phpspreadsheet en `public/vendor/phpoffice/phpspreadsheet/` y actualizará el autoload existente. Al estar dentro de `public/vendor/`, se aísla del resto del proyecto (igual que en piensos_erp).

#### 2b. Crear clase `ExportImportExcel.php`

**Archivo nuevo**: `app/librerias/ExportImportExcel.php`

Basada en `C:\xampp\htdocs\piensos_erp\app\librerias\ExportImportExcel.php`, adaptada para:
- `require_once` apuntando a `RUTA_APP . '/../public/vendor/autoload.php'`
- Método estático `exportToExcel($cabeceras, $datos, $nombreArchivo)` — genera `.xlsx` y lo descarga
- Método estático `prepareDataToExportExcel($datos, $nombreListado)` — wrapper que extrae cabeceras automáticamente

#### 2c. Modificar vista `selectorReportes.php`

Reemplazar el card "Exportar Fichajes CSV" por "Exportar Fichajes" con:
- Un **select de formato**: CSV, Excel (.xlsx), PDF
- Los mismos campos (Desde, Hasta, Empleado) que ya existen
- **1 botón "Exportar"** que cambia el `action` del formulario según el formato seleccionado (via JS)

Rutas:
- CSV: `ReportesHorario/exportarCSV` (ya existe)
- Excel: `ReportesHorario/exportarExcel` (nuevo)
- PDF: `ReportesHorario/exportarFichajesPDF` (nuevo)

#### 2d. Añadir métodos en el controlador `ReportesHorario.php`

- `exportarExcel()` — Similar a `exportarCSV()` pero usando `ExportImportExcel::prepareDataToExportExcel()` con los datos de `obtenerFichajesParaCSV()`
- `exportarFichajesPDF()` — Genera PDF de fichajes usando `generarPdf::documentoPDFExportar()` con nueva plantilla `documentos/reporteFichajesExport.php`

#### 2e. Crear plantilla PDF para fichajes exportados

**Archivo nuevo**: `app/views/documentos/reporteFichajesExport.php`

Tabla simple con: Fecha/Hora, Empleado, Tipo, Observaciones (similar al CSV pero en formato PDF con estilos).

### Resumen de archivos a crear/modificar

| # | Archivo | Acción |
|---|---------|--------|
| 1 | `app/views/documentos/reporteHorarioEmpleado.php` | Modificar: reemplazar tabla de fichajes por tabla de jornadas |
| 2 | `public/composer.json` | Modificar: añadir `phpoffice/phpspreadsheet` |
| 3 | `app/librerias/ExportImportExcel.php` | Crear: clase wrapper PhpSpreadsheet |
| 4 | `app/views/reportesHorario/selectorReportes.php` | Modificar: select formato + 1 botón Exportar + JS |
| 5 | `app/controlers/ReportesHorario.php` | Modificar: añadir `exportarExcel()` + `exportarFichajesPDF()` |
| 6 | `app/views/documentos/reporteFichajesExport.php` | Crear: plantilla PDF para fichajes exportados |

### Referencia: Implementación existente en piensos_erp

- Librería PhpSpreadsheet en: `C:\xampp\htdocs\piensos_erp\public\vendor\phpoffice\phpspreadsheet\`
- Clase wrapper en: `C:\xampp\htdocs\piensos_erp\app\librerias\ExportImportExcel.php`
- Usa `require '../public/vendor/autoload.php'` y `\PhpOffice\PhpSpreadsheet\Spreadsheet` / `\PhpOffice\PhpSpreadsheet\Writer\Xlsx`
- Genera archivo en `sys_get_temp_dir()`, envía headers HTTP para descarga, y elimina archivo temporal

---

## Pendientes Globales del Proyecto (resumen)

### Limpieza de código

| # | Tarea | Archivo/Lugar | Estado |
|---|-------|---------------|--------|
| CLEANUP-1 | Eliminar método `migrarConfigHorario()` | `app/controlers/AdministracionHorario.php` | ✅ Completado |
| CLEANUP-2 | Eliminar vista `migrarResultado.php` | `app/views/administracionHorario/migrarResultado.php` | ✅ Completado |
| CLEANUP-3 | Eliminar permiso `/AdministracionHorario/migrarConfigHorario` de rolesbase.permisos (admin id=1) | BD `rolesbase` | 🔲 Pendiente (query preparada, ver abajo) |
| CLEANUP-4 | Eliminar archivos backup/copy de controladores | `app/controlers/Clientes copy.php`, `Clientes_prod_antes_del_23_02_2025.php`, `Clientes1.php`, `Clientes1xxx.php`, `FacturasClienteAntes.php`, `Incidencias_correcc_ficheros.php`, `Incidencias_prod_22_05_2026.php`, `Pruebas.php` | 🔲 Pendiente |
| CLEANUP-5 | Eliminar archivos backup/copy de modelos | `app/models/ModeloClientes copy.php`, `ModeloClientes_prod_antes_del_23_02_2025.php` | 🔲 Pendiente |
| CLEANUP-6 | Eliminar archivos backup/copy de vistas | `app/views/incidencias/verIncidencia copy.php`, `app/views/usuarios/usuarios copy.php` | 🔲 Pendiente |
| CLEANUP-7 | Eliminar archivos backup de librerías | `app/librerias/enviarEmail copy.php` | ✅ Completado |
| CLEANUP-8 | Eliminar vista y controlador de pruebas | `app/controlers/Pruebas.php`, `app/views/pruebas/` (incluido `table.zip` y `table.html`) | 🔲 Pendiente |
| CLEANUP-9 | Eliminar archivo de plantilla backup | `public/documentos/plantillasCorreo/plantillaboton copy.php` | ✅ Completado |
| CLEANUP-10 | Eliminar archivos temporales de la raíz del proyecto | `temp_admin_perms.json`, `temp_admin_perms.txt`, `tmp_perms.txt`, `tmp_permisos.txt`, `test.txt`, `mysql_error.log` | ✅ Completado |
| CLEANUP-11 | Eliminar archivos de configuración backup | `app/config/admin_permisos_backup.txt`, `app/config/admin_permisos_raw.txt`, `app/config/tecnico_permisos_backup.txt` | ✅ Completado |

#### CLEANUP-3: Query para eliminar permiso de BD

```sql
-- Verificar que existe
SELECT id, rol, nombre,
       JSON_CONTAINS(permisos, '["/AdministracionHorario/migrarConfigHorario","link"]') AS tiene_permiso
FROM rolesbase WHERE id = 1;

-- Eliminar
UPDATE rolesbase 
SET permisos = JSON_REMOVE(
    permisos, 
    JSON_UNQUOTE(JSON_SEARCH(permisos, 'one', '/AdministracionHorario/migrarConfigHorario'))
)
WHERE id = 1 
AND JSON_CONTAINS(permisos, '["/AdministracionHorario/migrarConfigHorario","link"]');

-- Verificar que se eliminó
SELECT id, rol, nombre,
       JSON_CONTAINS(permisos, '["/AdministracionHorario/migrarConfigHorario","link"]') AS tiene_permiso
FROM rolesbase WHERE id = 1;
```

### Funcionalidades pendientes de implementar

| # | Tarea | Descripción | Estado |
|---|-------|-------------|--------|
| EXP-1 | PDF Empleado: tabla de jornadas resumidas | Reemplazar tabla de fichajes individuales por tabla de jornadas (Fecha, Estado, Horas, Horas esp., Diferencia, Completada) en `reporteHorarioEmpleado.php` | ✅ Completado |
| EXP-2 | Exportar Fichajes: CSV + Excel + PDF | Añadir PhpSpreadsheet, crear `ExportImportExcel.php`, modificar `selectorReportes.php`, añadir `exportarExcel()` y `exportarFichajesPDF()` en `ReportesHorario.php`, crear `reporteFichajesExport.php` | ✅ Completado |
| EXP-REV | Revisar columnas del Exportar Fichajes | Decisión: mantener las 4 columnas actuales (Fecha/Hora, Empleado, Tipo, Observaciones) sin cambios | ✅ Cerrado |

### Funcionalidades diferidas (no implementadas por decisión)

| # | Tarea | Descripción | Estado | Notas |
|---|-------|-------------|--------|-------|
| DIFF-1 | Detección de llegadas tarde | Campo `horarioentrada` existe en BD pero no se usa para cálculos. Detectar primer fichaje > entrada + tolerancia | 🔲 Diferida | Campo oculto en `configuracionHorario.php` como hidden input |
| DIFF-2 | Detección de salidas anticipadas | Campo `horariosalida` existe en BD pero no se usa para cálculos | 🔲 Diferida | Campo oculto en `configuracionHorario.php` como hidden input |
| DIFF-3 | Cálculo de retrasos con tolerancia | Campo `toleranciaminutos` existe (default 5 min) pero no se usa | 🔲 Diferida | Campo oculto en `configuracionHorario.php` como hidden input. Columna "Retraso" eliminada de reportes |
| DIFF-4 | Indicadores de horas esperadas y diferencia en listado global admin | Mostrar horas esp. y diferencia en `listadoGlobal.php` | ❌ No se implementa | Decisión del usuario |

### Despliegue

| # | Tarea | Descripción | Estado |
|---|-------|-------------|--------|
| DEPLOY-1 | Seguir checklist de MIGRACION_PRODUCCION.md | Checklist completo de migración a producción | ✅ Completado |

### Seguridad

| # | Tarea | Descripción | Severidad | Estado |
|---|-------|-------------|-----------|--------|
| SEC-1 | Contraseña BD vacía en producción | `DB_PASSWORD = ''` en `configurar.php` (root sin contraseña) | Alta | 🔲 Pendiente |
| SEC-2 | Credenciales email en texto plano | `PASSWORD_CORREO` visible en `configurar.php` | Media | 🔲 Pendiente |
| SEC-3 | Sin .gitignore para archivos sensibles | Archivos como `temp_admin_perms.json`, SQL dumps, `mysql_error.log` accesibles | Media | 🔲 Pendiente (archivos temporales eliminados en CLEANUP-10, pero .gitignore aún necesario) |
| SEC-4 | SQL injection en código original del CRM | Varias queries usan interpolación de strings en vez de parámetros bind | Media | 🔲 Pendiente (código existente, no tocar sin confirmación) |
| SEC-5 | Contraseñas en texto plano en la BD | Las contraseñas de usuarios no están hasheadas | Media | 🔲 Pendiente (código existente, no tocar sin confirmación) |

### Verificación de timezone

| # | Tarea | Descripción | Estado |
|---|-------|-------------|--------|
| TZ-1 | Verificar que `SELECT NOW()` de MySQL coincide con `date('Y-m-d H:i:s')` de PHP en servidor de producción | Crítico para el cierre de jornadas. Ver MIGRACION_PRODUCCION.md sección 10.1 | 🔲 Pendiente |

---

## Cambios 2026-08-25

### EXP-1: PDF Empleado — Tabla de jornadas resumidas — ✅ Completado

- **Problema**: El PDF mensual por empleado mostraba una tabla de fichajes individuales (Fecha, Hora, Tipo, Corregido, Observaciones), que ya está disponible en CSV/Excel.
- **Solución**: Reemplazada la tabla de fichajes individuales por una tabla de jornadas resumidas con las mismas columnas que la vista en pantalla: Fecha, Estado (badge con color), Horas, Horas esp., Diferencia, Completada.
- **Archivos**: `app/views/documentos/reporteHorarioEmpleado.php`

### Cambio: PDF Global de landscape a portrait

- **Problema**: El reporte PDF global se generaba en orientación horizontal (landscape), diferente del reporte mensual por empleado que es vertical.
- **Solución**: Cambiado a orientación vertical (portrait). Ajustados font-sizes de cabeceras y celdas de 8px a 10px, restaurados nombres completos de cabeceras abreviadas.
- **Archivos**: `app/controlers/ReportesHorario.php` (`'L'` → `'P'`), `app/views/documentos/reporteHorarioGlobal.php` (eliminado `orientation="landscape"`, ajustados font-sizes y padding)

### Cambio: Font-sizes en PDFs

- **reporteHorarioEmpleado.php**: Cabeceras 10px → 11px, celdas 10px → 12px, badge estado 9px → 10px
- **reporteHorarioGlobal.php**: Cabeceras 8px → 10px, celdas 8px → 10px, padding 4px 5px → 5px 6px

### EXP-2: Exportar Fichajes → CSV + Excel + PDF — ✅ Completado

#### Paso 1: Instalar PhpSpreadsheet
- Ejecutado `composer require phpoffice/phpspreadsheet:^1.28 --ignore-platform-req=ext-gd` en `public/`
- Añadidas dependencias: phpspreadsheet 1.28.0, psr/simple-cache, psr/http-message, psr/http-factory, psr/http-client, markbaker/complex, markbaker/matrix, maennchen/zipstream-php, ezyang/htmlpurifier, myclabs/php-enum
- Autoload actualizado correctamente
- **Archivos**: `public/composer.json`, `public/composer.lock`, `public/vendor/` (nuevos paquetes)

#### Paso 2: Crear `ExportImportExcel.php`
- Nueva clase `ExportImportExcel` en `app/librerias/ExportImportExcel.php`
- Método estático `exportarFichajesExcel($cabeceras, $filas, $nombreArchivo)`:
  - Crea `Spreadsheet`, escribe cabeceras en fila 1 (negrita), datos en filas 2+
  - Auto-size de columnas
  - Guarda en `sys_get_temp_dir()`, envía headers HTTP para descarga `.xlsx`, elimina archivo temporal
  - Validación de datos vacíos con alert JS
- **Archivo**: `app/librerias/ExportImportExcel.php`

#### Paso 3: Modificar `selectorReportes.php`
- Reemplazado card "Exportar Fichajes CSV" por "Exportar Fichajes" con:
  - Select de formato: CSV, Excel (.xlsx), PDF
  - Mismos campos (Desde, Hasta, Empleado)
  - 1 botón "Exportar" que cambia el action según formato via JS (`exportarFichajes()`)
- Rutas: CSV → `ReportesHorario/exportarCSV` (ya existe), Excel → `ReportesHorario/exportarExcel` (nuevo), PDF → `ReportesHorario/exportarFichajesPDF` (nuevo)
- **Archivo**: `app/views/reportesHorario/selectorReportes.php`

#### Paso 4: Añadir métodos en `ReportesHorario.php`
- **`exportarExcel()`**: Recibe `fechainicio`, `fechafin`, `idempleado` por GET. Obtiene datos con `obtenerFichajesParaCSV()`, registra auditoría, llama `ExportImportExcel::exportarFichajesExcel()` con cabeceras y filas
- **`exportarFichajesPDF()`**: Recibe `fechainicio`, `fechafin`, `idempleado` por GET. Obtiene datos con `obtenerFichajesParaCSV()`, registra auditoría, llama `generarPdf::documentoPDFExportar()` con nueva plantilla `reporteFichajesExport.php`
- **Archivo**: `app/controlers/ReportesHorario.php`

#### Paso 5: Crear `reporteFichajesExport.php`
- Plantilla PDF para fichajes exportados con:
  - Título: "Listado de Fichajes" + rango de fechas + nombre de empleado (o "Todos los empleados")
  - Tabla: Fecha/Hora, Empleado, Tipo, Observaciones
  - Filas alternadas blanco/gris, font-size 10-11px (consistente con otros PDFs)
  - Pie: fecha de generación + referencia RDL 8/2019
- **Archivo**: `app/views/documentos/reporteFichajesExport.php`

#### Paso 6: Permisos en BD
- Añadidos `/ReportesHorario/exportarExcel` y `/ReportesHorario/exportarFichajesPDF` como permisos tipo `"link"` en `rolesbase.permisos` para admin (id=1)
- Script temporal ejecutado y eliminado

### Resumen de archivos creados/modificados (EXP-2)

| # | Archivo | Acción |
|---|---------|--------|
| 1 | `public/composer.json` | Modificado: añadido `phpoffice/phpspreadsheet` |
| 2 | `public/composer.lock` | Modificado: nuevas dependencias |
| 3 | `public/vendor/` | Modificado: nuevos paquetes instalados por composer |
| 4 | `app/librerias/ExportImportExcel.php` | Creado: clase wrapper PhpSpreadsheet |
| 5 | `app/views/reportesHorario/selectorReportes.php` | Modificado: select formato + 1 botón Exportar + JS |
| 6 | `app/controlers/ReportesHorario.php` | Modificado: añadidos `exportarExcel()` + `exportarFichajesPDF()` |
| 7 | `app/views/documentos/reporteFichajesExport.php` | Creado: plantilla PDF para fichajes exportados |
| 8 | BD `rolesbase.permisos` (id=1) | Modificado: añadidos permisos exportarExcel y exportarFichajesPDF |

### Revisión pendiente: Diferencias entre los 3 reportes de exportación

| Aspecto | Exportar Fichajes (CSV/Excel/PDF) | Reporte Mensual por Empleado (pantalla + PDF) | Reporte Global de Empresa (pantalla + PDF) |
|---------|-----------------------------------|------------------------------------------------|----------------------------------------------|
| **Datos** | Fichajes individuales | Jornadas resumidas por día | Resumen por empleado |
| **Ámbito** | Rango de fechas libre (desde/hasta) | 1 mes, 1 empleado | 1 mes, todos los empleados |
| **Filas** | 1 fila = 1 fichaje (entrada o salida) | 1 fila = 1 jornada | 1 fila = 1 empleado |
| **Columnas** | Fecha/Hora, Empleado, Tipo, Observaciones | Fecha, Estado, Horas, Horas esp., Diferencia, Completada | Empleado, Días trabajados, Horas totales, Completadas, Incompletas, Horas esp., Diferencia |
| **Empleado** | Todos o uno específico | Solo uno | Todos |
| **Periodo** | Cualquier rango de fechas | Un mes específico | Un mes específico |
| **Nivel de detalle** | Máximo (cada fichaje) | Medio (resumen diario) | Mínimo (resumen mensual por empleado) |

**Pendiente de revisar**: Verificar si el usuario quiere añadir más columnas al Exportar Fichajes (por ejemplo: estado de jornada, corregido, id de jornada) o si el formato actual es suficiente.

---

## Cambios 2026-09-03

### Bug CSS: Icono lupa no visible en botones violeta

- **Problema**: En los botones `bg-violeta-oscuro text-white` de las vistas CHOVA, el icono SVG de la lupa (`fa-search`) no se veía porque tenía `color: #048bd3` (mismo azul que el fondo del botón). Solo era visible en hover porque el fondo cambiaba a `purple-800`.
- **Solución**: Cambiado `color: #048bd3` a `color: currentColor` en `public/css/styles.css` línea 232. Ahora el icono hereda el color del padre: blanco en botones con `text-white`, y el color de texto habitual en otros contextos.
- **Vistas afectadas**: `miCalendario.php`, `historialEmpleado.php`, `solicitarModificacion.php`, `calendarioJornadas.php`, `detalleEmpleado.php`, `listadoGlobal.php`
- **Archivos**: `public/css/styles.css`

### EXP-REV: Revisión de columnas del Exportar Fichajes — ✅ Cerrado

- **Decisión**: Mantener las 4 columnas actuales (Fecha/Hora, Empleado, Tipo, Observaciones) sin añadir más. El formato actual es suficiente.

### CLEANUP-1: Eliminar método `migrarConfigHorario()` — ✅ Completado

- Eliminado el método temporal `migrarConfigHorario()` de `app/controlers/AdministracionHorario.php`
- Este método ya se ejecutó una vez y era innecesario. Su acceso por URL era una superficie de ataque innecesaria.

### CLEANUP-2: Eliminar vista `migrarResultado.php` — ✅ Completado

- Eliminada `app/views/administracionHorario/migrarResultado.php`
- Vista asociada al método `migrarConfigHorario()`, ya sin uso.

### CLEANUP-7: Eliminar backup de librería — ✅ Completado

- Eliminado `app/librerias/enviarEmail copy.php`

### CLEANUP-9: Eliminar plantilla backup — ✅ Completado

- Eliminado `public/documentos/plantillasCorreo/plantillaboton copy.php`

### CLEANUP-10: Eliminar archivos temporales de la raíz — ✅ Completado

- Eliminados: `temp_admin_perms.json`, `temp_admin_perms.txt`, `tmp_perms.txt`, `tmp_permisos.txt`, `test.txt`, `mysql_error.log`

### CLEANUP-11: Eliminar archivos config backup — ✅ Completado

- Eliminados: `app/config/admin_permisos_backup.txt`, `app/config/admin_permisos_raw.txt`, `app/config/tecnico_permisos_backup.txt`

### CLEANUP-6 (parcial): Eliminar archivos backup de vistas

- Eliminados: `app/controlers/ControlHorario.php.backup`, `app/views/controlHorario/solicitarModificacion.php.backup`, `app/views/incidencias/verHistorialTiempos copy.php`
- Pendientes: `app/views/incidencias/verIncidencia copy.php`, `app/views/usuarios/usuarios copy.php`

---

## Cambios 2026-10-14 — Correo: instrumentación SMTP y fix teléfono

### Instrumentación de envío de correos (diagnóstico Dinaserver) — ✅ Desplegado en producción

- **Problema**: El CRM no envía correos con `info@instalacionestelesat.es` (Dinaserver); con Gmail de prueba funciona. Fallos silenciosos, sin log ni rastro.
- **Solución**:
  1. `app/config/configurar.php`: constante `SMTP_DEBUG_NIVEL` (0 = sin debug, 3 = transcripción SMTP completa)
  2. `app/librerias/enviarEmail.php` (`enviarEmailDestinatario`): `SMTPDebug` + callback `Debugoutput` que captura la transcripción en `$GLOBALS['smtpDebugLog']` (nada en pantalla); registro en `app/logs/email_errors.log` al fallar `Send()` o en excepción. `enviarEmailDocumento()` (facturas/PDFs) queda SIN instrumentar
  3. `app/controlers/Incidencias.php`: fix de destinatarios acumulados en `enviarEmailAcuseDeReciboCliente()` y `enviarEmailALosTecnicosAsignadosAlCliente()` (antes cada email incluía a los destinatarios anteriores, con enlaces personalizados cruzados)
- **Verificación**: archivos local y copia producción idénticos (`fc`), `php -l` OK. Prueba real en producción: alta de técnico → AVISO visible + transcripción completa en `app/logs/email_errors.log`
- **Documentación**: `docs/diagnostico_correo_telesat.md` (solo local)

### Fix: teléfono vacío causaba 500 en alta/edición de técnico — ✅ Desplegado en producción

- **Problema**: `usuarios.telefono` es INT y MySQL de producción va en modo estricto. Teléfono vacío (o con espacios/guiones) → `PDOException 1366` no capturada → HTTP 500 en `/Tecnicos/registrarTecnico`. En local no se reproducía (XAMPP no estricto convierte `''` → `0`).
- **Solución**: en `ModeloTecnicos.php` (`insertarNuevaTecnico` y `actualizarDatosTecnico`): `$telefono = (int) preg_replace('/[^0-9]/', '', (string) $datos['telefono']);` — vacío/letras → 0, números formateados → solo dígitos.
- **Archivos**: `app/models/ModeloTecnicos.php` (local + copia producción, idénticos verificados)

### PENDIENTE: Respuesta de Dinaserver (SMTP AUTH rechaza credenciales) — 🔲

- **Estado actual**: SMTP_DEBUG_NIVEL = 3 en producción (temporal). El envío con Dinaserver falla; el CRM funciona con Gmail de prueba.
- **Evidencia** (2026-10-14 16:23, `email_errors.log` producción): conexión OK, STARTTLS OK, servidor acepta usuario pero rechaza contraseña (`535 5.7.8 authentication failed`). Mismo resultado desde local y desde el propio hosting → **descartada restricción de relay por IP**; el fallo es del lado del proveedor.
- **Consulta enviada al proveedor**: ¿SMTP AUTH habilitado para la cuenta? ¿Credenciales SMTP = webmail? ¿Usuario en otro formato (solo `info`)?
- **Al recibir respuesta**:
  - [ ] Actualizar `PASSWORD_CORREO`/credenciales en `configurar.php` de producción
  - [ ] Bajar `SMTP_DEBUG_NIVEL` de `3` a `0` en producción (el log de errores sigue activo igualmente)
  - [ ] Re-test: alta de técnico → correo recibido sin AVISO
- **Opcional mientras tanto**: probar `Username = info` (requiere separar usuario SMTP del remitente en `enviarEmail.php`). La instrumentación de `enviarEmailDocumento()` ya está completada (ver auditoría abajo). Resto del tema correo: cerrado, pendiente únicamente el aviso de Dinaserver.

### Auditoría de envíos de correo (2026-10-14, post-fix duplicidad) — hallazgos y plan aprobado

**Auditoría completa de todos los puntos de envío** (`enviarEmailDestinatario` + `enviarEmailDocumento`):

**Duplicidad (acumulación de `$emailsDestino`) — veredicto:**
- ✅ Corregidos y vivos: `Incidencias.php:726` y `:771` (acuse de recibo + técnicos asignados)
- ✅ Sanos (declaración dentro del bucle o sin bucle): notificación admins (`Incidencias.php:646`, `:685`; `PresupuestosFacturas.php:154`, `:217`), valoración (`:1814`, single), reasignar (`:1902`), rechazo (`:2943`)
- ✅ `enviarEmailDocumento()` NO afecta: sus 2 llamadores vivos (`FacturasCliente.php:1321` facturas, `Incidencias.php:3427` partes) usan `$_POST['inputEmailSelected']` (multi-select, un solo envío a todos los seleccionados, por diseño)
- 🔴 Con el bug pero CÓDIGO MUERTO (sin llamadores, verificado en app/ + public/):
  - `Incidencias.php:1709` `enviarEmailClienteConFinalizacionIncidencia()` — el "email de finalización al cliente" nunca se envía (método huérfano)
  - `FacturasCliente.php:1207` `enviarEmailFactura1()` — huérfano (el JS usa `enviarEmailFactura`, otro método)

**Plan — ✅ EJECUTADO y desplegado en producción (2026-10-14, sesión 2):**
1. [x] Fix truthiness `if ($envio)` → `if ($envio === 1)` en `FacturasCliente.php:1323` e `Incidencias.php:3430` (`enviarEmailDocumentoPdf`): en excepción la librería devuelve string no vacío → mostraba "Correo enviado" y registraba en `emails_clientes_facturas` aunque falló
2. [x] Instrumentar `enviarEmailDocumento()` en `app/librerias/enviarEmail.php`: `SMTPDebug = SMTP_DEBUG_NIVEL` + callback `Debugoutput` + logs con etiqueta propia `"ERROR/EXCEPCION ENVIO EMAIL DOCUMENTO"` en `email_errors.log` (antes un fallo de factura/parte no dejaba rastro)
3. [x] Fix truthiness `if ($envioEmail)` → `=== 1` en `Login.php:219` (recuperación de contraseña reporta éxito en excepciones)
4. [x] Decisión sobre los 2 métodos huérfanos con bug de acumulación: **dejarlos sin tocar** (decisión del usuario, 14/10/2026 — quedan documentados como código muerto arriba)

Aplicado en local + copia producción. Verificado: `php -l` 8/8 sin errores; `fc` pares — controladores idénticos entre copias, `enviarEmail.php` difiere solo en comentarios legacy preexistentes (bloques `/*echo...*/` de la copia prod, respetados). Subido al hosting por el usuario. Prueba pendiente de hacer: envío de factura/parte con Dinaserver caído → entrada "ERROR ENVIO EMAIL DOCUMENTO" en `app/logs/email_errors.log`.