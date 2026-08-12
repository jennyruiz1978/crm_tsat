# Guía de Migración a Producción — Módulo CHOVA (Control Horario y Vacaciones)

## Resumen

Este documento describe todos los pasos necesarios para migrar el módulo CHOVA a un entorno de producción limpio, incluyendo:
1. Creación de tablas
2. Datos iniciales
3. Configuración de permisos
4. Limpieza completa si se necesita rollback

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

---

## 2. Script de creación de tablas (ejecutar en producción)

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
  KEY `idx_jornadas_empleado` (`idempleado`),
  KEY `idx_jornadas_fecha` (`fecha`),
  UNIQUE KEY `idx_jornadas_empleado_fecha` (`idempleado`, `fecha`)
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
  `toleranciaminutos` int(5) DEFAULT 10 COMMENT 'Minutos de tolerancia para retrasos',
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

---

## 3. Datos iniciales — Configuración horario por rol

### Lógica de `debeFichar`

- **Sin registro en `configuracionhorario`**: El usuario NO puede acceder al módulo CHOVA (ControlHorario y Vacaciones). Los menús se ocultan y el acceso por URL redirige a Inicio.
- **Con `debeFichar = 0`**: Mismo comportamiento que sin registro. No puede acceder a CHOVA.
- **Con `debeFichar = 1`**: El usuario SÍ puede acceder a CHOVA. Fichar, historial, solicitudes, vacaciones, etc.

### Flujo de producción

1. Ejecutar el INSERT (o el script de migración) para crear registros con `debeFichar=0`.
2. El administrador decide quiénes deben fichar y los activa desde **Admin. Horario → Configuración**.
3. Los usuarios nuevos creados desde el CRUD de Usuarios tienen su config creada automáticamente con `debeFichar=1` si su rol no es cliente.

### Script INSERT para producción (ejecutar DESPUÉS de crear las tablas)

```sql
-- ============================================================
-- DATOS INICIALES: Crear registros de configuración para usuarios existentes
-- ============================================================
-- Inserta un registro en configuracionhorario para cada usuario ACTIVO
-- que NO sea cliente (rol != 1), con debeFichar = 0 (inactivo).
-- El administrador activará manualmente quiénes deben fichar desde
-- Admin. Horario → Configuración.
-- Los clientes (rol=1) NO fichan y NO necesitan registro.

INSERT INTO `configuracionhorario` (`idempleado`, `debeFichar`, `jornadatipohoras`, `horarioentrada`, `horariosalida`, `toleranciaminutos`)
SELECT `id`, 0, 8.00, '09:00:00', '18:00:00', 10
FROM `usuarios`
WHERE `rol` IN (0, 2, 3) AND `activo` = 1;
```

**Alternativa**: Usar el método `migrarConfigHorario()` del controlador `AdministracionHorario` (ver sección 11).

**NOTA**: Este INSERT solo se ejecuta UNA VEZ en la migración inicial. Después, el controlador `Usuarios.php` gestiona automáticamente la creación/actualización de registros al crear/editar usuarios.

---

## 4. Creación del rol visitante (si no existe)

```sql
-- Verificar si el rol visitante existe antes de insertar
INSERT INTO `rolesbase` (`id`, `rol`, `nombre`, `permisos`)
SELECT 4, 3, 'visitante', '{}'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `rolesbase` WHERE `id` = 4);
```

IMPORTANTE: El campo `permisos` se actualiza en el paso 5 con el JSON completo.

---

## 5. Permisos CHOVA en rolesbase

Los permisos CHOVA deben añadirse al JSON existente en el campo `permisos` de `rolesbase` para los roles admin (id=1), tecnico (id=3) y visitante (id=4). El rol cliente (id=2) no tiene acceso CHOVA.

**OJO**: Estos JSONs deben fusionarse con los permisos EXISTENTES del CRM, no reemplazarlos. La forma más segura es ejecutar el script PHP de actualización.

### Script PHP para actualizar permisos (ejecutar una vez y eliminar)

Crear archivo temporal `app/config/update_permisos_chova_prod.php`:

```php
<?php
// Script temporal para actualizar permisos CHOVA en producción
// EJECUTAR UNA VEZ Y LUEGO ELIMINAR ESTE ARCHIVO

require_once __DIR__ . '/configurar.php';

// Obtener permisos actuales de rolesbase
$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NOMBRE, DB_USER, DB_PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Permisos CHOVA para ADMIN (rol=0, id=1)
$permisosChovaAdmin = [
    "Mi Control Horario" => [
        "/ControlHorario", "fas fa-clock", "Mi Control Horario",
        [
            ["/ControlHorario/fichar", "Fichar", []],
            ["/ControlHorario/registrarFichaje", "link", []],
            ["/ControlHorario/obtenerEstadoActual", "link", []],
            ["/ControlHorario/miHistorial", "Mi Historial", []],
            ["/ControlHorario/misSolicitudes", "Mis Solicitudes", []],
            ["/ControlHorario/solicitarModificacion", "link", []],
            ["/ControlHorario/detalleJornada", "link", []],
            ["/ControlHorario/miCalendario", "Mi Calendario", []]
        ]
    ],
    "AdministracionHorario" => [
        "/AdministracionHorario", "fas fa-user-clock", "Admin. Horario",
        [
            ["/AdministracionHorario/listadoGlobal", "Listado Global", []],
            ["/AdministracionHorario/calendarioJornadas", "Calendario Jornadas", []],
            ["/AdministracionHorario/solicitudesModificacion", "Solicitudes Mod.", []],
            ["/AdministracionHorario/configuracionHorario", "Configuracion", []],
            ["/AdministracionHorario/detalleEmpleado", "link", []],
            ["/AdministracionHorario/resolverSolicitud", "link", []],
            ["/AdministracionHorario/aprobarSolicitud", "link", []],
            ["/AdministracionHorario/rechazarSolicitud", "link", []],
            ["/AdministracionHorario/listadoVacaciones", "Permisos Global", []],
            ["/AdministracionHorario/resolverVacacion", "link", []],
            ["/AdministracionHorario/aprobarVacacion", "link", []],
            ["/AdministracionHorario/rechazarVacacion", "link", []],
            ["/AdministracionHorario/cancelarVacacion", "link", []],
            ["/AdministracionHorario/aplicarSolicitudAprobada", "link", []]
        ]
    ],
    "Mis Permisos" => [
        "/Vacaciones", "fas fa-umbrella-beach", "Mis Permisos",
        [
            ["/Vacaciones/solicitarVacacion", "Solicitar Permiso", []],
            ["/Vacaciones/misVacaciones", "Mis Permisos", []],
            ["/Vacaciones", "link", []],
            ["/Vacaciones/subirJustificante", "link", []]
        ]
    ],
    "ReportesHorario" => [
        "/ReportesHorario", "fas fa-file-alt", "Reportes Horario",
        [
            ["/ReportesHorario/generarReportes", "Generar Reportes", []],
            ["/ReportesHorario/reportePDFMensualEmpleado", "link", []],
            ["/ReportesHorario/reportePDFGlobalEmpresa", "link", []],
            ["/ReportesHorario/reporteCSVFichajes", "link", []],
            ["/ReportesHorario/reporteMensualEmpleado", "link", []],
            ["/ReportesHorario/reporteGlobalEmpresa", "link", []]
        ]
    ]
];

// Permisos CHOVA para TECNICO (rol=2, id=3)
$permisosChovaTecnico = [
    "Mi Control Horario" => [
        "/ControlHorario", "fas fa-clock", "Mi Control Horario",
        [
            ["/ControlHorario/fichar", "Fichar", []],
            ["/ControlHorario/registrarFichaje", "link", []],
            ["/ControlHorario/obtenerEstadoActual", "link", []],
            ["/ControlHorario/miHistorial", "Mi Historial", []],
            ["/ControlHorario/misSolicitudes", "Mis Solicitudes", []],
            ["/ControlHorario/solicitarModificacion", "link", []],
            ["/ControlHorario/detalleJornada", "link", []],
            ["/ControlHorario/miCalendario", "Mi Calendario", []]
        ]
    ],
    "Mis Permisos" => [
        "/Vacaciones", "fas fa-umbrella-beach", "Mis Permisos",
        [
            ["/Vacaciones/solicitarVacacion", "Solicitar Permiso", []],
            ["/Vacaciones/misVacaciones", "Mis Permisos", []],
            ["/Vacaciones", "link", []],
            ["/Vacaciones/subirJustificante", "link", []]
        ]
    ]
];

// Permisos CHOVA para VISITANTE (rol=3, id=4)
$permisosChovaVisitante = [
    "Mi Control Horario" => [
        "/ControlHorario", "fas fa-clock", "Mi Control Horario",
        [
            ["/ControlHorario/fichar", "Fichar", []],
            ["/ControlHorario/registrarFichaje", "link", []],
            ["/ControlHorario/obtenerEstadoActual", "link", []],
            ["/ControlHorario/miHistorial", "Mi Historial", []],
            ["/ControlHorario/misSolicitudes", "Mis Solicitudes", []],
            ["/ControlHorario/solicitarModificacion", "link", []],
            ["/ControlHorario/detalleJornada", "link", []],
            ["/ControlHorario/miCalendario", "Mi Calendario", []]
        ]
    ],
    "Mis Permisos" => [
        "/Vacaciones", "fas fa-umbrella-beach", "Mis Permisos",
        [
            ["/Vacaciones/solicitarVacacion", "Solicitar Permiso", []],
            ["/Vacaciones/misVacaciones", "Mis Permisos", []],
            ["/Vacaciones", "link", []],
            ["/Vacaciones/subirJustificante", "link", []]
        ]
    ]
];

// Función para fusionar permisos CHOVA con permisos existentes
function fusionarPermisosChova($permisosExistentesJson, $permisosChova) {
    $permisos = json_decode($permisosExistentesJson, true);
    if (!is_array($permisos)) {
        $permisos = [];
    }
    foreach ($permisosChova as $key => $value) {
        $permisos[$key] = $value;
    }
    return json_encode($permisos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

// Actualizar admin (id=1)
$stmt = $db->query("SELECT permisos FROM rolesbase WHERE id = 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nuevosPermisos = fusionarPermisosChova($row['permisos'], $permisosChovaAdmin);
$stmt = $db->prepare("UPDATE rolesbase SET permisos = ? WHERE id = 1");
$stmt->execute([$nuevosPermisos]);
echo "Permisos admin actualizados.\n";

// Actualizar tecnico (id=3)
$stmt = $db->query("SELECT permisos FROM rolesbase WHERE id = 3");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nuevosPermisos = fusionarPermisosChova($row['permisos'], $permisosChovaTecnico);
$stmt = $db->prepare("UPDATE rolesbase SET permisos = ? WHERE id = 3");
$stmt->execute([$nuevosPermisos]);
echo "Permisos tecnico actualizados.\n";

// Actualizar visitante (id=4)
$stmt = $db->query("SELECT permisos FROM rolesbase WHERE id = 4");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nuevosPermisos = fusionarPermisosChova($row['permisos'], $permisosChovaVisitante);
$stmt = $db->prepare("UPDATE rolesbase SET permisos = ? WHERE id = 4");
$stmt->execute([$nuevosPermisos]);
echo "Permisos visitante actualizados.\n";

echo "\n¡Permisos CHOVA actualizados correctamente!\n";
echo "ELIMINE ESTE ARCHIVO DESPUÉS DE EJECUTARLO.\n";
```

**Instrucciones**: 
1. Subir el archivo a `app/config/update_permisos_chova_prod.php`
2. Ejecutar desde CLI: `php app/config/update_permisos_chova_prod.php`
3. **ELIMINAR el archivo inmediatamente después**

---

## 6. Tablas y datos anteriores a CHOVA que fueron modificados

### 6.1. Tabla `rolesbase` (MODIFICADA)

- **Campo `permisos`**: Se añadió el JSON de permisos CHOVA a los roles admin (id=1), tecnico (id=3) y visitante (id=4).
- **Nuevo registro**: Rol visitante (id=4, rol=3, nombre='visitante') creado por CHOVA.
- **No se añadieron columnas nuevas** — solo se modificó el contenido del campo `permisos` existente.

### 6.2. Tabla `usuarios` (NO MODIFICADA en estructura)

- No se añadieron columnas nuevas.
- El controlador `Usuarios.php` fue modificado para gestionar `configuracionhorario` al crear/editar usuarios, pero no se alteró la estructura de la tabla.

### 6.3. Tablas de backup creadas durante el desarrollo

| Tabla | Contenido | Origen |
|-------|-----------|--------|
| `rolesbase_22_05_2026` | Backup original de rolesbase (3 roles: admin, cliente, tecnico) | Pre-CHOVA |
| `rolesbase_28_05_2026` | Backup intermedio (4 roles: admin, cliente, tecnico, visitante, sin permisos CHOVA completos) | Desarrollo CHOVA |

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
--   (usando backup del 22/05/2026, que tiene 3 roles sin permisos CHOVA)
UPDATE rolesbase rb
INNER JOIN rolesbase_22_05_2026 b ON rb.id = b.id
SET rb.permisos = b.permisos;

-- Eliminar rol visitante (creado por CHOVA)
DELETE FROM rolesbase WHERE id = 4;

-- 3. Eliminar tablas backup
DROP TABLE IF EXISTS rolesbase_22_05_2026;
DROP TABLE IF EXISTS rolesbase_28_05_2026;
```

```powershell
# 4. Eliminar archivos subidos (justificantes)
Remove-Item "C:\xampp\htdocs\crm_tsat\public\documentos\Vacaciones\*" -Recurse -Force
```

---

## 8. Script de LIMPIEZA de datos de prueba (mantener estructura, borrar datos)

Para limpiar solo los datos de prueba pero mantener las tablas y la estructura para empezar limpio en producción:

```sql
-- ============================================================
-- LIMPIEZA DE DATOS: Mantener estructura, borrar datos de prueba
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE fichajesmodificados;
TRUNCATE TABLE solicitudesmodificacion;
TRUNCATE TABLE fichajes;
TRUNCATE TABLE jornadas;
TRUNCATE TABLE vacaciones;
TRUNCATE TABLE auditoria;

SET FOREIGN_KEY_CHECKS = 1;

-- Resetear AUTO_INCREMENT
ALTER TABLE jornadas AUTO_INCREMENT = 1;
ALTER TABLE fichajes AUTO_INCREMENT = 1;
ALTER TABLE solicitudesmodificacion AUTO_INCREMENT = 1;
ALTER TABLE fichajesmodificados AUTO_INCREMENT = 1;
ALTER TABLE vacaciones AUTO_INCREMENT = 1;
ALTER TABLE auditoria AUTO_INCREMENT = 1;

-- NOTA: NO truncar configuracionhorario.
-- Los registros de configuración son necesarios para que los usuarios puedan fichar.
-- Si se reinicializan, ejecutar el INSERT del paso 3.

-- Si se quiere reinicializar configuracionhorario también:
-- TRUNCATE TABLE configuracionhorario;
-- ALTER TABLE configuracionhorario AUTO_INCREMENT = 1;
-- Y luego ejecutar el INSERT del paso 3.
```

```powershell
# Eliminar justificantes subidos
Remove-Item "C:\xampp\htdocs\crm_tsat\public\documentos\Vacaciones\*" -Recurse -Force
```

---

## 9. Checklist de migración a producción

- [ ] Ejecutar script de creación de tablas (paso 2)
- [ ] Crear rol visitante si no existe (paso 4)
- [ ] Ejecutar INSERT de datos iniciales en configuracionhorario (paso 3) O usar método migrarConfigHorario (sección 11)
- [ ] Subir y ejecutar script PHP de permisos CHOVA (paso 5) Y permiso de migración (sección 11)
- [ ] Ejecutar migración de usuarios existentes desde Admin. Horario → migrarConfigHorario (sección 11)
- [ ] Activar debeFichar para usuarios que deban fichar desde Admin. Horario → Configuración
- [ ] Eliminar archivos temporales de migración (método, vista, permiso, script PHP)
- [ ] Eliminar script PHP de permisos después de ejecutar
- [ ] Crear directorio `public/documentos/Vacaciones/` si no existe
- [ ] Verificar que los archivos PHP nuevos están en el servidor:
  - [ ] `app/controlers/ControlHorario.php`
  - [ ] `app/controlers/AdministracionHorario.php`
  - [ ] `app/controlers/Vacaciones.php`
  - [ ] `app/controlers/ReportesHorario.php`
  - [ ] `app/controlers/Usuarios.php` (modificado)
  - [ ] `app/controlers/Login.php` (modificado — añade `$_SESSION['debeFichar']`)
  - [ ] `app/views/includes/menu-sidebar-desktop.php` (modificado — filtra CHOVA si debeFichar=0)
  - [ ] `app/views/includes/menu-sidebar-mobile.php` (modificado — filtra CHOVA si debeFichar=0)
  - [ ] `app/views/usuarios/altaUsuarios/altaUsuarios.php` (modificado — añadido rol Visitante)
  - [ ] `app/views/usuarios/actualizarUsuarios/actualizarUsuarios.php` (modificado — opciones de rol)
  - [ ] `app/views/administracionHorario/configuracionHorario.php` (modificado — rol Visitante)
  - [ ] `app/models/ModeloControlHorario.php`
  - [ ] `app/models/ModeloSolicitudesModificacion.php`
  - [ ] `app/models/ModeloVacaciones.php`
  - [ ] `app/models/ModeloConfiguracionHorario.php`
  - [ ] `app/models/ModeloAuditoria.php`
  - [ ] `app/models/ModeloReportesHorario.php`
  - [ ] `app/helpers/ControlHorarioHelper.php`
  - [ ] `app/librerias/Controlador.php` (modificado)
  - [ ] `app/views/includes/controlPermisos.php` (modificado)
  - [ ] `app/views/includes/sidebar-tailwind.php` (modificado)
  - [ ] `app/views/includes/footer.php` (modificado)
  - [ ] `public/js/controlHorarioFichar.js`
  - [ ] `public/js/navbarAcciones.js` (modificado)
  - [ ] `public/js/navbarSidebar/navbarSidebar.js` (modificado)
  - [ ] Todas las vistas en `app/views/controlHorario/`
  - [ ] Todas las vistas en `app/views/administracionHorario/`
  - [ ] Todas las vistas en `app/views/vacaciones/`
  - [ ] Todas las vistas en `app/views/reportesHorario/`
- [ ] Verificar zona horaria de MySQL: `SELECT NOW();` debe coincidir con `date('Y-m-d H:i:s')` de PHP
- [ ] Verificar que `configurar.php` usa el usuario `root` (con permisos completos)
- [ ] Probar fichaje de entrada y salida
- [ ] Probar solicitar vacaciones
- [ ] Probar login como visitante
- [ ] Probar administración horario como admin

---

## 10. Notas importantes para producción

1. **Zona horaria**: Verificar que MySQL y PHP usan la misma zona horaria. Ejecutar `SELECT NOW()` y comparar con `date('Y-m-d H:i:s')` de PHP en el servidor de producción.

2. **Directorio de justificantes**: Crear `public/documentos/Vacaciones/` con permisos de escritura para el servidor web.

3. **Usuarios existentes sin config**: Si hay usuarios activos con rol != 1 que ya existían antes de la migración, el INSERT del paso 3 cubre todos. Los nuevos usuarios creados después serán gestionados automáticamente por `Usuarios.php`.

4. **Soft delete**: Las tablas CHOVA usan `eliminado=1` para borrado lógico. NUNCA hacer DELETE físico en estas tablas.

5. **Auditoría**: La tabla `auditoria` registra toda acción CHOVA. En producción, considerar una política de retención (el RDL 8/2019 exige mínimo 4 años).

6. **Rol visitante**: El rol visitante (id=4, rol=3) es nuevo. Debe existir en `rolesbase` antes de que ningún usuario con `rol=3` pueda usar el sistema.

7. **Integración Usuarios ↔ Config Horario**: Al crear un usuario no-cliente, se crea automáticamente su config. Al editar un usuario cambiando su rol, se ajusta `debeFichar`. Esto está protegido con `try-catch` para que no afecte al CRUD de usuarios si algo falla.

8. **Restricción de acceso CHOVA por `debeFichar`**: Si un usuario tiene `debeFichar=0` en `configuracionhorario`, o no tiene registro en esa tabla, no puede acceder al módulo CHOVA (ControlHorario y Vacaciones). Los menús se ocultan del sidebar y el acceso por URL redirige a `/Inicio`. Esto se controla mediante `$_SESSION['debeFichar']` que se establece al login. Los módulos de administración (AdministracionHorario, ReportesHorario) no se ven afectados por esta restricción.

9. **Formularios de Usuarios**: El alta de usuarios ahora incluye la opción "Visitante" (rol=3) en el select de rol. La edición de usuario muestra Admin, Cliente y Visitante (mantiene Técnico si el usuario ya lo es). La vista de Configuración Horario ahora muestra correctamente "Visitante" en la columna Rol.

---

## 11. Migración de usuarios existentes a configuracionhorario

En producción, es probable que existan usuarios (admin, técnicos, visitantes) que NO tengan registro en `configuracionhorario`. Este script crea los registros necesarios con `debeFichar=0` (inactivo) para que luego el administrador decida quiénes deben fichar.

### Script PHP temporal: `add_permiso_migracion.php`

Crear archivo `add_permiso_migracion.php` en la raíz del proyecto:

```php
<?php
// Script temporal para añadir permiso de migrarConfigHorario al admin (rol id=1)
// EJECUTAR UNA VEZ Y LUEGO ELIMINAR ESTE ARCHIVO

require_once __DIR__ . '/app/config/configurar.php';

try {
    $pdo = new PDO("mysql:host=localhost:3307;dbname=crm_telesat", DB_USUARIO, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT permisos FROM rolesbase WHERE id = 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $permisos = json_decode($row['permisos'], true);

    if (!$permisos) {
        die("Error: No se pudieron decodificar los permisos del admin.\n");
    }

    $added = false;
    if (isset($permisos['AdministracionHorario']) && is_array($permisos['AdministracionHorario'])) {
        if (isset($permisos['AdministracionHorario'][3]) && is_array($permisos['AdministracionHorario'][3])) {
            $permisos['AdministracionHorario'][3][] = ["/AdministracionHorario/migrarConfigHorario", "link"];
            $added = true;
        }
    }

    if (!$added) {
        die("Error: No se encontró el bloque AdministracionHorario en los permisos del admin.\n");
    }

    $nuevosPermisos = json_encode($permisos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $stmt = $pdo->prepare("UPDATE rolesbase SET permisos = :permisos WHERE id = 1");
    $stmt->execute([':permisos' => $nuevosPermisos]);

    echo "Permiso /AdministracionHorario/migrarConfigHorario añadido correctamente al admin (id=1).\n";
    echo "ELIMINE ESTE ARCHIVO INMEDIATAMENTE.\n";

} catch (PDOException $e) {
    die("Error de BD: " . $e->getMessage() . "\n");
}
```

### Pasos para ejecutar la migración

1. **Subir archivos al servidor**:
   - `add_permiso_migracion.php` en la raíz del proyecto
   - `app/views/administracionHorario/migrarResultado.php` (vista del resultado)

2. **Añadir el método temporal** en `app/controlers/AdministracionHorario.php`:

```php
public function migrarConfigHorario()
{
    $this->db = new Base;
    $this->db->query("SELECT id, nombre, apellidos, rol FROM usuarios WHERE rol != 1 AND activo = 1 ORDER BY id");
    $usuarios = $this->db->registros();

    $creados = [];
    $omitidos = [];

    foreach ($usuarios as $usuario) {
        $existente = $this->ModelConfigHorario->obtenerConfigPorEmpleado($usuario->id);
        if ($existente) {
            $omitidos[] = $usuario;
        } else {
            $datosConfig = [
                'idempleado' => $usuario->id,
                'debeFichar' => 0,
                'jornadatipohoras' => 8.00,
                'horarioentrada' => '09:00:00',
                'horariosalida' => '18:00:00',
                'toleranciaminutos' => 10
            ];
            $this->ModelConfigHorario->crearConfig($datosConfig);
            $creados[] = $usuario;
        }
    }

    $datos = [
        'creados' => $creados,
        'omitidos' => $omitidos
    ];
    $this->vista('administracionHorario/migrarResultado', $datos);
}
```

3. **Ejecutar el script de permisos** desde CLI:
   ```
   php add_permiso_migracion.php
   ```
   Esto añade `/AdministracionHorario/migrarConfigHorario` a los permisos del admin.

4. **Cerrar sesión y volver a logar** como admin (para refrescar permisos en sesión).

5. **Ejecutar la migración** desde el navegador:
   ```
   http://localhost:8080/crm_tsat/AdministracionHorario/migrarConfigHorario
   ```
   Se mostrará una página con los usuarios creados y los omitidos.

6. **Activar debeFichar** para cada usuario que deba fichar desde Configuración de Horario.

7. **ELIMINAR todo lo temporal**:
   - Eliminar `add_permiso_migracion.php` de la raíz del proyecto
   - Eliminar el método `migrarConfigHorario()` de `AdministracionHorario.php`
   - Eliminar la vista `migrarResultado.php` de `app/views/administracionHorario/`
   - Eliminar el permiso `/AdministracionHorario/migrarConfigHorario` de `rolesbase.permisos` del admin (id=1):
     ```sql
     -- Ejecutar tras eliminar el método y la vista:
     -- Consultar permisos del admin, buscar y eliminar la entrada ["migrarConfigHorario","link"]
     -- o ejecutar script similar que quite esa entrada del JSON
     ```

### Valores por defecto del INSERT

| Campo | Valor | Motivo |
|-------|-------|--------|
| `debeFichar` | **0** | Inactivo por defecto. El admin activa manualmente quiénes deben fichar. |
| `jornadatipohoras` | 8.00 | Jornada estándar de 8 horas |
| `horarioentrada` | 09:00:00 | Hora de entrada estándar |
| `horariosalida` | 18:00:00 | Hora de salida estándar |
| `toleranciaminutos` | 10 | 10 minutos de tolerancia |

### Nota importante

Los usuarios creados DESPUÉS de esta migración se gestionan automáticamente por `Usuarios.php`: al crear un usuario no-cliente se crea el registro con `debeFichar=1`, y al editar el rol se ajusta `debeFichar`. Este script solo es necesario para usuarios que ya existían antes de la integración.