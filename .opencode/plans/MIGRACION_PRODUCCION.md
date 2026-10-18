# Guía de Migración a Producción — Módulo CHOVA (Control Horario y Vacaciones)

## Resumen

Este documento describe todos los pasos necesarios para migrar el módulo CHOVA a un entorno de producción limpio, incluyendo:
1. Creación de tablas
2. Datos iniciales
3. Configuración de permisos
4. Limpieza completa si se necesita rollback

**Estado del despliegue**: ✅ COMPLETADO en producción (septiembre 2026)

---

## 1. Tablas nuevas creadas por CHOVA (7 tablas)

| Tabla | Descripción |
|-------|-------------|
| `jornadas` | Jornadas laborales diarias por empleado |
| `fichajes` | Registros de fichaje (entrada/salida) |
| `solicitudesmodificacion` | Solicitudes de corrección de fichajes |
| `fichajesmodificados` | Fichajes insertados/modificados por solicitudes aprobadas |
| `vacaciones` | Solicitudes de vacaciones, bajas y ausencias |
| `configuracionhorario` | Configuración horaria por empleado (debeFichar, jornada, horarios) |
| `auditoria` | Registro de auditoría de acciones CHOVA |

✅ **Ejecutado en producción**

---

## 2. Script de creación de tablas (ejecutar en producción)

✅ **Ejecutado en producción**. A continuación el SQL definitivo corregido:

```sql
-- ============================================================
-- MIGRACIÓN: Control Horario y Vacaciones - CRM Telesat
-- Cumplimiento: Real Decreto-ley 8/2019 (España)
-- ============================================================

-- Tabla: jornadas
CREATE TABLE IF NOT EXISTS `jornadas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id',
  `fecha` date NOT NULL COMMENT 'Fecha de la jornada',
  `estadojornada` varchar(20) NOT NULL DEFAULT 'abierta' COMMENT 'abierta, cerrada, incompleta, vacaciones, baja, ausencia',
  `horastotales` decimal(5,2) DEFAULT 0.00 COMMENT 'Horas totales calculadas',
  `completada` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=jornada completa con entrada y salida',
  `eliminado` tinyint(1) NOT NULL DEFAULT 0,
  `creadoen` timestamp NOT NULL DEFAULT current_timestamp(),
  `modificadoen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_jornadas_empleado_fecha` (`idempleado`,`fecha`),
  KEY `idx_jornadas_empleado` (`idempleado`),
  KEY `idx_jornadas_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: fichajes
CREATE TABLE IF NOT EXISTS `fichajes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id',
  `tipofichaje` varchar(10) NOT NULL COMMENT 'entrada, salida',
  `fechahora` datetime NOT NULL COMMENT 'Fecha/hora del SERVIDOR',
  `latitud` decimal(10,7) DEFAULT NULL COMMENT 'Geolocalización',
  `longitud` decimal(10,7) DEFAULT NULL COMMENT 'Geolocalización',
  `ipregistro` varchar(50) DEFAULT NULL COMMENT 'IP del dispositivo',
  `useragent` varchar(500) DEFAULT NULL COMMENT 'User-Agent del navegador',
  `observaciones` text DEFAULT NULL,
  `idjornada` int(10) NOT NULL DEFAULT 0 COMMENT 'FK a jornadas.id',
  `eliminado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Soft delete lógico - nunca borrar físicamente',
  `corregido` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=este fichaje fue corregido por una solicitud aprobada',
  `creadoen` timestamp NOT NULL DEFAULT current_timestamp(),
  `modificadoen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_fichajes_empleado` (`idempleado`),
  KEY `idx_fichajes_jornada` (`idjornada`),
  KEY `idx_fichajes_fecha` (`fechahora`),
  KEY `idx_fichajes_empleado_fecha` (`idempleado`, `fechahora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: solicitudesmodificacion
CREATE TABLE IF NOT EXISTS `solicitudesmodificacion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idfichajeoriginal` int(10) DEFAULT NULL COMMENT 'FK a fichajes.id - NULL si es inserción nueva',
  `idjornadaoriginal` int(10) NOT NULL DEFAULT 0 COMMENT 'FK a jornadas.id',
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id - quien solicita',
  `tipomodificacion` varchar(20) NOT NULL COMMENT 'insertar, modificar, omitir',
  `nuevotipofichaje` varchar(10) DEFAULT NULL COMMENT 'Nuevo tipo si es modificación/inserción',
  `nuevafechahora` datetime DEFAULT NULL COMMENT 'Nueva fecha/hora propuesta',
  `nuevaobservaciones` text DEFAULT NULL,
  `motivo` text NOT NULL COMMENT 'Motivo de la solicitud',
  `estado` varchar(15) NOT NULL DEFAULT 'pendiente' COMMENT 'pendiente, aprobada, rechazada',
  `idadminresuelve` int(10) DEFAULT NULL COMMENT 'FK a usuarios.id - admin que resuelve',
  `fecharesolucion` datetime DEFAULT NULL,
  `respuestaadmin` text DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT 0,
  `creadoen` timestamp NOT NULL DEFAULT current_timestamp(),
  `modificadoen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_solicitudes_empleado` (`idempleado`),
  KEY `idx_solicitudes_fichaje` (`idfichajeoriginal`),
  KEY `idx_solicitudes_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: fichajesmodificados
CREATE TABLE IF NOT EXISTS `fichajesmodificados` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idsolicitud` int(10) NOT NULL COMMENT 'FK a solicitudesmodificacion.id',
  `idfichajeoriginal` int(10) DEFAULT NULL COMMENT 'FK a fichajes.id - NULL si es inserción nueva',
  `idempleado` int(10) NOT NULL,
  `tipofichaje` varchar(10) NOT NULL,
  `fechahora` datetime NOT NULL,
  `observaciones` text DEFAULT NULL,
  `idjornada` int(10) NOT NULL DEFAULT 0,
  `creadoen` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_fichmod_solicitud` (`idsolicitud`),
  KEY `idx_fichmod_original` (`idfichajeoriginal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: vacaciones
CREATE TABLE IF NOT EXISTS `vacaciones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id',
  `tipovacacion` varchar(20) NOT NULL COMMENT 'vacaciones, baja, ausencia',
  `fechainicio` date NOT NULL,
  `fechafin` date NOT NULL,
  `dias` int(5) NOT NULL DEFAULT 1,
  `estado` varchar(15) NOT NULL DEFAULT 'pendiente' COMMENT 'pendiente, aprobada, rechazada',
  `motivo` text DEFAULT NULL COMMENT 'Justificación proporcionada por el empleado',
  `idadminresuelve` int(10) DEFAULT NULL COMMENT 'FK a usuarios.id - admin que resuelve',
  `fecharesolucion` datetime DEFAULT NULL,
  `respuestaadmin` text DEFAULT NULL,
  `ficherojustificante` varchar(500) DEFAULT NULL COMMENT 'Path al fichero de justificante (bajas)',
  `eliminado` tinyint(1) NOT NULL DEFAULT 0,
  `creadoen` timestamp NOT NULL DEFAULT current_timestamp(),
  `modificadoen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vacaciones_empleado` (`idempleado`),
  KEY `idx_vacaciones_estado` (`estado`),
  KEY `idx_vacaciones_fecha` (`fechainicio`, `fechafin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: configuracionhorario
CREATE TABLE IF NOT EXISTS `configuracionhorario` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id',
  `debeFichar` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Debe fichar, 0=No ficha',
  `jornadatipohoras` decimal(4,2) DEFAULT 8.00 COMMENT 'Horas de jornada estándar',
  `horarioentrada` time DEFAULT NULL COMMENT 'Hora teórica de entrada',
  `horariosalida` time DEFAULT NULL COMMENT 'Hora teórica de salida',
  `toleranciaminutos` int(5) DEFAULT 5 COMMENT 'Minutos de tolerancia para retrasos',
  `eliminado` tinyint(1) NOT NULL DEFAULT 0,
  `creadoen` timestamp NOT NULL DEFAULT current_timestamp(),
  `modificadoen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_config_empleado` (`idempleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla: auditoria
CREATE TABLE IF NOT EXISTS `auditoria` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idusuario` int(10) NOT NULL COMMENT 'FK a usuarios.id - quién ejecutó la acción',
  `accion` varchar(50) NOT NULL COMMENT 'fichaje_entrada, fichaje_salida, solicitud_mod, aprobacion_mod, rechazo_mod, aprobacion_vacacion, rechazo_vacacion, etc.',
  `entidad` varchar(50) NOT NULL COMMENT 'fichajes, solicitudesmodificacion, vacaciones, jornadas, etc.',
  `identidad` int(10) DEFAULT NULL COMMENT 'ID del registro afectado',
  `datosprevios` longtext DEFAULT NULL COMMENT 'JSON con estado anterior',
  `datosnuevos` longtext DEFAULT NULL COMMENT 'JSON con estado nuevo',
  `ip` varchar(50) DEFAULT NULL,
  `useragent` varchar(500) DEFAULT NULL,
  `creadoen` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_auditoria_usuario` (`idusuario`),
  KEY `idx_auditoria_accion` (`accion`),
  KEY `idx_auditoria_fecha` (`creadoen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Notas sobre el SQL respecto a versiones anteriores

| Cambio | Versión anterior | Versión corregida | Motivo |
|--------|-----------------|-------------------|--------|
| `jornadas.estadojornada` COMMENT | `'abierta, cerrada, incompleta, vacaciones, baja'` | `'abierta, cerrada, incompleta, vacaciones, baja, ausencia'` | Se añadió el estado "ausencia" |
| `configuracionhorario.toleranciaminutos` DEFAULT | `10` | `5` | Se cambió de 10 a 5 minutos durante el desarrollo |
| `solicitudesmodificacion.idjornadaoriginal` | No existía | Añadida como `int(10) NOT NULL DEFAULT 0` | Necesaria para corregir fichajes huérfanos |
| `fichajes.corregido` | No existía | Añadida como `tinyint(1) NOT NULL DEFAULT 0` | Marca fichajes corregidos por solicitudes aprobadas |

---

## 3. Datos iniciales — Configuración horario

### Lógica de `debeFichar`

- **Sin registro en `configuracionhorario`**: El usuario NO puede acceder al módulo CHOVA (ControlHorario y Vacaciones). Los menús se ocultan y el acceso por URL redirige a Inicio.
- **Con `debeFichar = 0`**: Mismo comportamiento que sin registro. No puede acceder a CHOVA.
- **Con `debeFichar = 1`**: El usuario SÍ puede acceder a CHOVA. Fichar, historial, solicitudes, vacaciones, etc.

### Flujo de producción

1. El administrador decide quiénes deben fichar y los activa manualmente desde **Admin. Horario → Configuración**.
2. No se crea automáticamente el registro al crear usuarios. El admin gestiona `debeFichar` manualmente.
3. Al editar un usuario cambiando su rol a cliente (rol=1) y si ya tiene config, se pone `debeFichar=0`.

**NOTA**: No se ejecuta un INSERT masivo. Los registros se crean manualmente desde la UI de Configuración Horario, donde los usuarios sin config ya aparecen con valores por defecto (COALESCE).

---

## 4. Creación del rol visitante (si no existe)

✅ **Ejecutado en producción**:

```sql
INSERT INTO `rolesbase` (`id`, `rol`, `nombre`, `permisos`)
SELECT 4, 3, 'visitante', '{}'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `rolesbase` WHERE `id = 4`);
```

---

## 5. Permisos CHOVA en rolesbase

✅ **Ejecutado en producción** mediante copia de la tabla `rolesbase` desde la BD local (`crm_telesat`) a producción (`crm_telesat_prod`) usando HeidiSQL.

### Resultado verificado

| Rol | Antes (prod) | Después (prod) |
|-----|-------------|----------------|
| Admin (id=1) | 4 bloques (Dashboard, Incidencias, Facturas, Ajustes) | 7 bloques (+ ControlHorario, AdministracionHorario, ReportesHorario) |
| Cliente (id=2) | 2 bloques (Dashboard, Incidencias) | 2 bloques (sin cambios) |
| Técnico (id=3) | 2 bloques (Incidencias, Ajustes) | 3 bloques (+ ControlHorario) |
| Visitante (id=4) | `{}` (vacío) | 1 bloque (ControlHorario) |

Los bloques no-CHOVA (Dashboard, Incidencias, Facturas, Ajustes) son idénticos entre local y producción (verificado con MD5). Solo se añadieron los bloques CHOVA.

### Estructura de permisos CHOVA (referencia)

**Admin (id=1)**: ControlHorario + AdministracionHorario + ReportesHorario
**Técnico (id=3)**: ControlHorario
**Visitante (id=4)**: ControlHorario

La estructura de permisos CHOVA en la BD tiene las siguientes particularidades:
- **Vacaciones está anidado dentro de ControlHorario** como sub-item (no es un bloque separado)
- **AdministracionHorario** tiene sub-items anidados con links AJAX
- **ReportesHorario** tiene sub-items anidados con links AJAX

---

## 6. Tablas y datos anteriores a CHOVA que fueron modificados

### 6.1. Tabla `rolesbase` (MODIFICADA)

- **Campo `permisos`**: Se fusionaron los permisos CHOVA con los permisos existentes del CRM.
- **Nuevo registro**: Rol visitante (id=4, rol=3, nombre='visitante').
- **No se añadieron columnas nuevas** — solo se modificó el contenido del campo `permisos` existente.

### 6.2. Tabla `usuarios` (NO MODIFICADA en estructura)

- No se añadieron columnas nuevas.
- El controlador `Usuarios.php` fue modificado para gestionar `configuracionhorario` al editar usuarios, pero no se alteró la estructura de la tabla.

---

## 7. Script de limpieza completa (ROLLBACK)

Si es necesario eliminar TODO rastro de CHOVA y restaurar la BD al estado anterior:

```sql
-- ============================================================
-- ROLLBACK COMPLETO: Eliminar todo rastro de CHOVA
-- ADVERTENCIA: Esto elimina TODOS los datos de fichajes, jornadas,
-- vacaciones, solicitudes, configuración y auditoría.
-- ============================================================

-- 1. Eliminar datos de tablas CHOVA
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE fichajesmodificados;
TRUNCATE TABLE solicitudesmodificacion;
TRUNCATE TABLE fichajes;
TRUNCATE TABLE jornadas;
TRUNCATE TABLE vacaciones;
TRUNCATE TABLE configuracionhorario;
TRUNCATE TABLE auditoria;

SET FOREIGN_KEY_CHECKS = 1;

-- Resetear AUTO_INCREMENT
ALTER TABLE jornadas AUTO_INCREMENT = 1;
ALTER TABLE fichajes AUTO_INCREMENT = 1;
ALTER TABLE solicitudesmodificacion AUTO_INCREMENT = 1;
ALTER TABLE fichajesmodificados AUTO_INCREMENT = 1;
ALTER TABLE vacaciones AUTO_INCREMENT = 1;
ALTER TABLE configuracionhorario AUTO_INCREMENT = 1;
ALTER TABLE auditoria AUTO_INCREMENT = 1;

-- 2. Restaurar rolesbase al estado anterior a CHOVA
-- Usar backup de rolesbase anterior a CHOVA si existe

-- 3. Eliminar rol visitante (creado por CHOVA)
DELETE FROM rolesbase WHERE id = 4;

-- 4. Eliminar tablas CHOVA (solo si se quiere eliminar completamente)
-- DROP TABLE IF EXISTS fichajesmodificados;
-- DROP TABLE IF EXISTS solicitudesmodificacion;
-- DROP TABLE IF EXISTS fichajes;
-- DROP TABLE IF EXISTS jornadas;
-- DROP TABLE IF EXISTS vacaciones;
-- DROP TABLE IF EXISTS configuracionhorario;
-- DROP TABLE IF EXISTS auditoria;
```

---

## 8. Script de LIMPIEZA de datos de prueba (mantener estructura, borrar datos)

Para limpiar solo los datos de prueba pero mantener las tablas y la estructura para empezar limpio en producción:

```sql
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE fichajesmodificados;
TRUNCATE TABLE solicitudesmodificacion;
TRUNCATE TABLE fichajes;
TRUNCATE TABLE jornadas;
TRUNCATE TABLE vacaciones;
TRUNCATE TABLE auditoria;

SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE jornadas AUTO_INCREMENT = 1;
ALTER TABLE fichajes AUTO_INCREMENT = 1;
ALTER TABLE solicitudesmodificacion AUTO_INCREMENT = 1;
ALTER TABLE fichajesmodificados AUTO_INCREMENT = 1;
ALTER TABLE vacaciones AUTO_INCREMENT = 1;
ALTER TABLE auditoria AUTO_INCREMENT = 1;

-- NOTA: NO truncar configuracionhorario.
-- Los registros de configuración son necesarios para que los usuarios puedan fichar.
-- El admin los crea manualmente desde Admin. Horario → Configuración.
```

---

## 9. Checklist de migración a producción

- [x] Ejecutar script de creación de tablas (paso 2)
- [x] Crear rol visitante si no existe (paso 4)
- [ ] ~~Ejecutar INSERT de datos iniciales en configuracionhorario~~ → **Descartado**: el admin crea los configs manualmente desde la UI
- [x] Copiar tabla rolesbase desde local a producción (permisos CHOVA fusionados) (paso 5)
- [x] Activar `debeFichar` para usuarios que deban fichar desde Admin. Horario → Configuración
- [x] Subir archivos PHP nuevos y modificados por FTP
- [x] Crear directorio `public/documentos/Vacaciones/`
- [x] Desbloquear acceso (MANTENIMIENTO = false en Login.php)
- [ ] Verificar zona horaria de MySQL: `SELECT NOW();` debe coincidir con `date('Y-m-d H:i:s')` de PHP
- [ ] Verificar que `configurar.php` usa las credenciales correctas de BD de producción
- [ ] Probar fichaje de entrada y salida
- [ ] Probar solicitar vacaciones
- [ ] Probar login como visitante
- [ ] Probar administración horario como admin

### Archivos subidos a producción

#### Archivos NUEVOS

| # | Archivo |
|---|---------|
| 1 | `app/controlers/ControlHorario.php` |
| 2 | `app/controlers/AdministracionHorario.php` |
| 3 | `app/controlers/Vacaciones.php` |
| 4 | `app/controlers/ReportesHorario.php` |
| 5 | `app/models/ModeloControlHorario.php` |
| 6 | `app/models/ModeloSolicitudesModificacion.php` |
| 7 | `app/models/ModeloVacaciones.php` |
| 8 | `app/models/ModeloConfiguracionHorario.php` |
| 9 | `app/models/ModeloAuditoria.php` |
| 10 | `app/models/ModeloReportesHorario.php` |
| 11 | `app/helpers/ControlHorarioHelper.php` |
| 12 | `app/librerias/ExportImportExcel.php` |
| 13 | `public/js/controlHorarioFichar.js` |
| 14-19 | `app/views/controlHorario/*.php` (6 archivos) |
| 20-25 | `app/views/administracionHorario/*.php` (6 archivos) |
| 26-30 | `app/views/vacaciones/*.php` (5 archivos) |
| 31-33 | `app/views/reportesHorario/*.php` (3 archivos) |
| 34 | `app/views/documentos/reporteFichajesExport.php` |
| 35 | Directorio `public/vendor/phpoffice/` (PhpSpreadsheet) |

#### Archivos MODIFICADOS

| # | Archivo | Qué cambió |
|---|---------|------------|
| 36 | `app/controlers/Login.php` | `$_SESSION['debeFichar']` + MANTENIMIENTO |
| 37 | `app/controlers/Usuarios.php` | `actualizarConfigHorarioSegunRol()` |
| 38 | `app/librerias/Controlador.php` | `controlPermisos()` con match parcial (strpos) |
| 39 | `app/views/includes/controlPermisos.php` | Mismo cambio que Controlador.php |
| 40 | `app/views/includes/menu-sidebar-desktop.php` | Filtro `debeFichar`, sidebar reorganizado |
| 41 | `app/views/includes/menu-sidebar-mobile.php` | Filtro `debeFichar`, sidebar reorganizado |
| 42 | `app/views/includes/footer.php` | JS de controlHorarioFichar |
| 43 | `app/views/includes/sidebar-tailwind.php` | Cambios en sidebar |
| 44 | `app/views/usuarios/altaUsuarios/altaUsuarios.php` | Rol Visitante en select |
| 45 | `app/views/usuarios/actualizarUsuarios/actualizarUsuarios.php` | Select de rol dinámico |
| 46 | `app/views/documentos/reporteHorarioEmpleado.php` | Tabla de jornadas resumidas |
| 47 | `app/views/documentos/reporteHorarioGlobal.php` | Portrait, horas esp./diferencia |
| 48 | `public/js/navbarAcciones.js` | Guard isAdminOTecnico |
| 49 | `public/js/navbarSidebar/navbarSidebar.js` | Guard if(profileBtn) |
| 50 | `public/css/styles.css` | Fix icono lupa (currentColor) |
| 51 | `public/composer.json` | Añadido phpoffice/phpspreadsheet |
| 52 | `public/composer.lock` | Actualizado |
| 53 | `public/vendor/autoload.php` | Actualizado |
| 54 | `public/vendor/composer/` | Actualizado |

---

## 10. Notas importantes para producción

1. **Zona horaria**: Verificar que MySQL y PHP usan la misma zona horaria. Ejecutar `SELECT NOW()` y comparar con `date('Y-m-d H:i:s')` de PHP en el servidor de producción.

2. **Directorio de justificantes**: Crear `public/documentos/Vacaciones/` con permisos de escritura para el servidor web.

3. **Usuarios existentes sin config**: No se crea automáticamente el registro en `configuracionhorario`. El admin gestiona `debeFichar` manualmente desde Administración Horario → Configuración. Los usuarios sin config aparecen en la vista con valores por defecto (COALESCE).

4. **Soft delete**: Las tablas CHOVA usan `eliminado=1` para borrado lógico. NUNCA hacer DELETE físico en estas tablas.

5. **Auditoría**: La tabla `auditoria` registra toda acción CHOVA. En producción, considerar una política de retención (el RDL 8/2019 exige mínimo 4 años).

6. **Rol visitante**: El rol visitante (id=4, rol=3) es nuevo. Debe existir en `rolesbase` antes de que ningún usuario con `rol=3` pueda usar el sistema.

7. **Integración Usuarios ↔ Config Horario**: Al crear un usuario, NO se crea automáticamente su config. Al editar un usuario cambiando su rol a cliente (rol=1), si ya tiene config, se pone `debeFichar=0`. El admin gestiona el resto manualmente.

8. **Restricción de acceso CHOVA por `debeFichar`**: Si un usuario tiene `debeFichar=0` en `configuracionhorario`, o no tiene registro, no puede acceder al módulo CHOVA. Los menús se ocultan del sidebar y el acceso por URL redirige a `/Inicio`. Esto se controla mediante `$_SESSION['debeFichar']` que se establece al login. Los módulos de administración (AdministracionHorario, ReportesHorario) no se ven afectados por esta restricción.

9. **Formularios de Usuarios**: El alta de usuarios incluye la opción "Visitante" (rol=3) en el select de rol. La edición muestra Admin, Cliente y Visitante (mantiene Técnico si el usuario ya lo es). La vista de Configuración Horario muestra correctamente "Visitante" en la columna Rol.

10. **Mantenimiento**: El archivo `Login.php` contiene `define('MANTENIMIENTO', false);` en la línea 3. Para bloquear el acceso, cambiar a `true`. Para desbloquear, cambiar a `false`.

---

## 11. Migración de usuarios existentes — ELIMINADO

**Sección eliminada**. El método `migrarConfigHorario()`, la vista `migrarResultado.php` y el permiso `/AdministracionHorario/migrarConfigHorario` ya no existen en el código.

El admin crea los registros de `configuracionhorario` manualmente desde **Admin. Horario → Configuración** para cada usuario que deba fichar. Los usuarios sin config aparecen en la vista con valores por defecto gracias a COALESCE.

### Valores por defecto en la vista de Configuración Horario (sin registro en BD)

| Campo | Valor por defecto (COALESCE) |
|-------|-------------------------------|
| `debeFichar` | 0 (no ficha) |
| `jornadatipohoras` | 8.00 |
| `horarioentrada` | 09:00:00 |
| `horariosalida` | 18:00:00 |
| `toleranciaminutos` | 5 |

---

## Limpieza pendiente en local (archivos backup)

Los siguientes archivos existen en local pero NO deben subirse a producción:

| Archivo | Motivo |
|---------|--------|
| `app/controlers/Clientes copy.php` | Backup |
| `app/controlers/Clientes1.php` | Backup |
| `app/controlers/Clientes1xxx.php` | Backup |
| `app/controlers/Clientes_prod_antes_del_23_02_2025.php` | Backup |
| `app/controlers/FacturasClienteAntes.php` | Backup |
| `app/controlers/Incidencias_correcc_ficheros.php` | Backup |
| `app/controlers/Incidencias_prod_22_05_2026.php` | Backup |
| `app/controlers/PresupuestosFacturas_prod.php` | Backup |
| `app/controlers/Pruebas.php` | Controlador de pruebas |
| `app/models/ModeloClientes copy.php` | Backup |
| `app/models/ModeloClientes_prod_antes_del_23_02_2025.php` | Backup |
| `app/models/ModeloFacturasClienteAntes.php` | Backup |
| `app/views/incidencias/verIncidencia copy.php` | Backup |
| `app/views/usuarios/usuarios copy.php` | Backup |
| `app/views/pruebas/` | Directorio de pruebas |