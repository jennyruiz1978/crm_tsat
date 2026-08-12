# Plan de Desarrollo: Control Horario y Vacaciones - CRM Telesat

## 1. Resumen del Requerimiento

Construir un sistema de **registro digital de control horario** conforme al **Real Decreto-ley 8/2019** español, integrado en el CRM actual, más una **gestión básica de vacaciones** como funcionalidad adicional.

### Funcionalidades Principales

1. **Fichaje (entrada/salida)** - Frontend móvil a toda pantalla con botón de entrada y salida
2. **Registro horario** - Fecha/hora del servidor (NO del dispositivo), con geolocalización
3. **Doble entrada** - Control de jornada partida (entrada/salida/entrada/salida)
4. **Historial por empleado** - Vista de sus propios fichajes
5. **Administración** - Listado global, filtros por empleado y fecha
6. **Reportes** - CSV, PDF mensual por empleado, PDF global empresa
7. **Auditoría y trazabilidad** - Logs inmutables, registro IP/user-agent
8. **Corrección de errores** - Solicitud de modificación por empleado → aprobación por admin (nunca eliminación, siempre registro original + corregido)
9. **Vacaciones** - Solicitud/empleado → aprobación-rechazo/admin, upload justificante para bajas
10. **Permisos** - Configuración de qué usuarios deben fichar

---

## 2. Arquitectura Existente (Resumen)

| Elemento | Patrón |
|----------|--------|
| **Routing** | `Core.php` parsea URL → `controller/method/param` |
| **Controladores** | `app/controlers/` (PascalCase), extienden `Controlador` |
| **Modelos** | `app/models/Modelo*.php`, crean `$this->db = new Base` |
| **Vistas** | `app/views/{carpeta}/archivo.php`, PHP plano con Tailwind |
| **DB** | MariaDB 10.4, PDO wrapper en `Base.php`, fetch OBJ |
| **Autenticación** | Sesiones PHP, `$_SESSION`, permisos en `rolesbase.permisos` (JSON) |
| **Permisos** | `controlPermisos()` compara URL contra whitelist de `$_SESSION['controlLinksUsuario']` |
| **Sidebar** | Generado dinámicamente desde `$_SESSION['permisos']` |
| **Roles** | 0=admin, 1=cliente, 2=tecnico |
| **Frontend** | Tailwind CSS, Font Awesome, Select2, Chart.js, JS ES6 modules |

### Convenciones de Nombres

| Elemento | Convención | Ejemplo |
|----------|------------|---------|
| Controladores | PascalCase, español | `ControlHorario`, `Vacaciones` |
| Modelos | Prefijo `Modelo` + PascalCase | `ModeloControlHorario`, `ModeloVacaciones` |
| Vistas | Carpeta por controller, camelCase | `controlHorario/fichar.php` |
| Tablas DB | lowercase, plural, español | `fichajes`, `solicitudesmodificacion` |
| Columnas DB | lowercase, camelCase sin underscore | `idempleado`, `tipofichaje` |

---

## 3. Esquema de Base de Datos

### 3.1 Tabla: `fichajes`

Registro inmutable de cada evento de fichaje.

```sql
CREATE TABLE `fichajes` (
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
  `eliminado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Soft delete lógico - nunca borrar物理mente',
  `creadoen` timestamp NOT NULL DEFAULT current_timestamp(),
  `modificadoen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_fichajes_empleado` (`idempleado`),
  KEY `idx_fichajes_jornada` (`idjornada`),
  KEY `idx_fichajes_fecha` (`fechahora`),
  KEY `idx_fichajes_empleado_fecha` (`idempleado`, `fechahora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3.2 Tabla: `jornadas`

Agrupa los fichajes de un día para un empleado. Permite doble entrada.

```sql
CREATE TABLE `jornadas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id',
  `fecha` date NOT NULL COMMENT 'Fecha de la jornada',
  `estadojornada` varchar(20) NOT NULL DEFAULT 'abierta' COMMENT 'abierta, cerrada, ausente, vacaciones, baja',
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
```

### 3.3 Tabla: `solicitudesmodificacion`

Solicitudes de corrección de errores de fichaje (empleado → admin).

```sql
CREATE TABLE `solicitudesmodificacion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idfichajeoriginal` int(10) NOT NULL COMMENT 'FK a fichajes.id - fichaje que se quiere corregir',
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id - quien solicita',
  `tipomodificacion` varchar(20) NOT NULL COMMENT 'insertar, modificar, omitir',
  `nuevotipofichaje` varchar(10) DEFAULT NULL COMMENT 'Nuevo tipo si es modificación',
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
```

### 3.4 Tabla: `fichajesmodificados`

Registro de los fichajes resultantes de una modificación aprobada (inmutabilidad: se conserva original + se crea nuevo).

```sql
CREATE TABLE `fichajesmodificados` (
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
```

### 3.5 Tabla: `vacaciones`

```sql
CREATE TABLE `vacaciones` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id',
  `tipovacacion` varchar(20) NOT NULL COMMENT 'vacaciones, baja, ausencia',
  `fechainicio` date NOT NULL,
  `fechafin` date NOT NULL,
  `dias` int(5) NOT NULL DEFAULT 1,
  `estado` varchar(15) NOT NULL DEFAULT 'pendiente' COMMENT 'pendiente, aprobada, rechazada',
  `motivo` text DEFAULT NULL COMMENT 'Justificación proporcionada por el empleado',
  `idadminresuelve` int(10) DEFAULT NULL,
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
```

### 3.6 Tabla: `configuracionhorario`

Configuración de qué usuarios deben fichar y parámetros de jornada.

```sql
CREATE TABLE `configuracionhorario` (
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
```

### 3.7 Tabla: `auditoria`

Registro de trazabilidad de todas las acciones del sistema.

```sql
CREATE TABLE `auditoria` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idusuario` int(10) NOT NULL COMMENT 'FK a usuarios.id - quién ejecutó la acción',
  `accion` varchar(50) NOT NULL COMMENT 'fichaje_entrada, fichaje_salida, solicitud_mod, aprobacion_mod, etc.',
  `entidad` varchar(50) NOT NULL COMMENT 'fichajes, solicitudesmodificacion, vacaciones, etc.',
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

## 4. Estructura de Archivos a Crear

### 4.1 Controladores (`app/controlers/`)

| Archivo | Propósito |
|---------|-----------|
| `ControlHorario.php` | Controlador principal: fichaje, historial, validaciones, lógica legal |
| `AdministracionHorario.php` | Panel admin: listado global, filtros, aprobación de solicitudes |
| `Vacaciones.php` | Gestión de vacaciones y bajas |
| `ReportesHorario.php` | Generación de reportes CSV y PDF |

### 4.2 Modelos (`app/models/`)

| Archivo | Propósito |
|---------|-----------|
| `ModeloControlHorario.php` | CRUD fichajes, jornadas, lógica de fichaje |
| `ModeloSolicitudesModificacion.php` | CRUD solicitudes de corrección |
| `ModeloVacaciones.php` | CRUD vacaciones/bajas |
| `ModeloConfiguracionHorario.php` | CRUD configuración horario por empleado |
| `ModeloAuditoria.php` | Inserción y consulta de logs de auditoría |
| `ModeloReportesHorario.php` | Queries para reportes CSV/PDF |

### 4.3 Vistas (`app/views/`)

#### `app/views/controlHorario/`

| Archivo | Propósito |
|---------|-----------|
| `fichar.php` | Vista principal de fichaje (mobile-first, botones entrada/salida) |
| `estadoActual.php` | Estado actual del empleado (dentro/fuera, jornada de hoy) |
| `historialEmpleado.php` | Historial de fichajes del empleado autenticado |
| `solicitarModificacion.php` | Formulario de solicitud de corrección |
| `misSolicitudes.php` | Listado de solicitudes del empleado |

#### `app/views/administracionHorario/`

| Archivo | Propósito |
|---------|-----------|
| `listadoGlobal.php` | Listado de todos los fichajes con filtros |
| `detalleEmpleado.php` | Detalle de fichajes de un empleado |
| `solicitudesModificacion.php` | Listado de solicitudes pendientes |
| `resolverSolicitud.php` | Vista para aprobar/rechazar solicitud |
| `configuracionHorario.php` | Configuración de qué empleados fichan |
| `calendarioJornadas.php` | Vista calendario de jornadas |

#### `app/views/vacaciones/`

| Archivo | Propósito |
|---------|-----------|
| `solicitarVacacion.php` | Formulario de solicitud de vacación/baja |
| `misVacaciones.php` | Listado de vacaciones del empleado |
| `listadoVacaciones.php` | Admin: listado de todas las solicitudes |
| `resolverVacacion.php` | Aprobar/rechazar vacación |
| `uploadJustificante.php` | Modal para subir justificante de baja |

#### `app/views/reportesHorario/`

| Archivo | Propósito |
|---------|-----------|
| `reporteMensualEmpleado.php` | PDF mensual por empleado |
| `reporteGlobalEmpresa.php` | PDF global de la empresa |
| `selectorReportes.php` | Formulario para generar reportes |

### 4.4 JavaScript (`public/js/`)

| Archivo | Propósito |
|---------|-----------|
| `controlHorarioFichar.js` | Lógica de fichaje (AJAX, geolocalización, estado) |
| `controlHorarioAdmin.js` | Filtros, tablas dinámicas para admin |
| `vacaciones.js` | Solicitudes y gestión de vacaciones |

### 4.5 Utilidades

| Archivo | Propósito |
|---------|-----------|
| `app/helpers/ControlHorarioHelper.php` | Funciones auxiliares: cálculo de horas, validación de jornada, detección de duplicados |

---

## 5. Lógica de Fichaje (Cumplimiento Legal RDL 8/2019)

### 5.1 Flujo de Fichaje

```
Empleado abre vista "Fichar"
  → Se consulta si tiene jornada abierta hoy
  → Se muestra estado: "Fuera" / "Dentro" + última acción
  → Botón fichar (entrada o salida según estado)
  
Empleado pulsa fichar
  → AJAX POST a ControlHorario/registrarFichaje
  → Server registra:
    - tipofichaje (entrada/salida)
    - fechahora = NOW() del servidor (NUNCA del cliente)
    - latitud/longitud (si geolocalización disponible)
    - ipregistro = $_SERVER['REMOTE_ADDR']
    - useragent = $_SERVER['HTTP_USER_AGENT']
  → Si es "entrada": se crea/abre jornada (estadojornada=abierta)
  → Si es "salida": se cierra jornada (estadojornada=cerrada, completada=1)
  → Se registra en tabla auditoria
  
Doble entrada:
  → Si jornada ya cerrada hoy y se ficha entrada nuevamente
  → Se abre nueva sesión dentro de la misma jornada
  → Se incrementa el contador de pares entrada/salida
```

### 5.2 Reglas de Negocio

1. **Hora del servidor**: `fechahora` siempre con `NOW()` de MySQL, nunca se acepta del cliente
2. **Inmutabilidad**: Nunca se eliminan fichajes (`eliminado=1` soft delete)
3. **Corrección de errores**: Empleado solicita → Admin aprueba → Se crea registro nuevo manteniendo original
4. **Doble entrada**: Permitida dentro de la misma jornada (entrada/salida/entrada/salida)
5. **No duplicados**: No se permite fichar dos veces el mismo tipo consecutivamente (sin salida entre entradas)
6. **Jornada automática**: Si un empleado ficha entrada un nuevo día, se crea automáticamente la jornada
7. **Cierre automático**: Si al día siguiente un empleado no cerró jornada, se marca como "incompleta" requiring admin attention
8. **Registro de auditoría**: Toda acción se loguea en tabla `auditoria`

### 5.3 Tipos de Solicitud de Modificación

- **insertar**: Insertar un fichaje olvidado (ej. olvidó fichar entrada)
- **modificar**: Cambiar hora de un fichaje existente (ej. fichó a las 9:05 pero debía ser 9:00)
- **omitir**: Marcar un fichaje como erróneo sin borrarlo (se crea fichaje corregido)

---

## 6. Permisos y Menús

### 6.1 Actualización de `rolesbase.permisos`

Se debe añadir un nuevo bloque de menú para cada rol:

**Admin (rol=0)**: Acceso completo
```json
"ControlHorario": ["/ControlHorario", "fas fa-clock", "Control Horario",
  [
    ["/ControlHorario/fichar", "Fichar", []],
    ["/ControlHorario/miHistorial", "Mi Historial", []],
    ["/ControlHorario/misSolicitudes", "Mis Solicitudes", []]
  ]
],
"AdministracionHorario": ["/AdministracionHorario", "fas fa-user-clock", "Admin. Horario",
  [
    ["/AdministracionHorario/listadoGlobal", "Listado Global", []],
    ["/AdministracionHorario/solicitudesModificacion", "Solicitudes Mod.", []],
    ["/AdministracionHorario/configuracionHorario", "Configuración", []]
  ]
],
"Vacaciones": ["/Vacaciones", "fas fa-umbrella-beach", "Vacaciones",
  [
    ["/Vacaciones/solicitarVacacion", "Solicitar", []],
    ["/Vacaciones/misVacaciones", "Mis Vacaciones", []],
    ["/Vacaciones/listadoVacaciones", "Listado General", []]
  ]
],
"ReportesHorario": ["/ReportesHorario", "fas fa-file-alt", "Reportes Horario",
  [
    ["/ReportesHorario/generarReportes", "Generar Reportes", []]
  ]
]
```

**Técnico (rol=2)**: Fichaje + sus propios datos + vacaciones
```json
"ControlHorario": ["/ControlHorario", "fas fa-clock", "Control Horario",
  [
    ["/ControlHorario/fichar", "Fichar", []],
    ["/ControlHorario/miHistorial", "Mi Historial", []],
    ["/ControlHorario/misSolicitudes", "Mis Solicitudes", []]
  ]
],
"Vacaciones": ["/Vacaciones", "fas fa-umbrella-beach", "Vacaciones",
  [
    ["/Vacaciones/solicitarVacacion", "Solicitar", []],
    ["/Vacaciones/misVacaciones", "Mis Vacaciones", []]
  ]
]
```

**Cliente (rol=1)**: Sin acceso a control horario (no fichan)

---

## 7. Fases de Desarrollo

### Fase 1: Infraestructura y Base de Datos (Días 1-2)
- [ ] Crear script SQL con todas las tablas nuevas
- [ ] Ejecutar migración en la DB
- [ ] Actualizar `rolesbase.permisos` para los 3 roles
- [ ] Crear `ModeloControlHorario.php`
- [ ] Crear `ModeloSolicitudesModificacion.php`
- [ ] Crear `ModeloVacaciones.php`
- [ ] Crear `ModeloConfiguracionHorario.php`
- [ ] Crear `ModeloAuditoria.php`
- [ ] Crear `ModeloReportesHorario.php`
- [ ] Crear `ControlHorarioHelper.php`

### Fase 2: Fichaje - Core (Días 3-5)
- [ ] Crear `ControlHorario.php` (controlador)
- [ ] Vista `fichar.php` - Mobile-first con botones grandes
- [ ] Método `registrarFichaje()` - Lógica de entrada/salida con hora servidor
- [ ] Método `obtenerEstadoActual()` - AJAX para consultar estado
- [ ] JavaScript `controlHorarioFichar.js` - AJAX + geolocalización
- [ ] Vista `estadoActual.php` - Dashboard de estado de jornada actual
- [ ] Validaciones: no duplicados, coherencia entrada/salida, jornada automática

### Fase 3: Historial y Solicitudes (Días 6-7)
- [ ] Vista `historialEmpleado.php` - Historial propio
- [ ] Vista `solicitarModificacion.php` - Formulario solicitud
- [ ] Vista `misSolicitudes.php` - Listado solicitudes del empleado
- [ ] Métodos en `ControlHorario.php` para solicitudes
- [ ] Lógica de inmutabilidad: original + corregido

### Fase 4: Administración (Días 8-10)
- [ ] Crear `AdministracionHorario.php` (controlador)
- [ ] Vista `listadoGlobal.php` - Tabla con filtros
- [ ] Vista `detalleEmpleado.php` - Detalle por empleado
- [ ] Vista `solicitudesModificacion.php` - Solicitudes pendientes
- [ ] Vista `resolverSolicitud.php` - Aprobar/rechazar
- [ ] Vista `configuracionHorario.php` - Configurar quién ficha
- [ ] Vista `calendarioJornadas.php` - Calendario visual
- [ ] JavaScript `controlHorarioAdmin.js`

### Fase 5: Vacaciones (Días 11-12)
- [ ] Crear `Vacaciones.php` (controlador)
- [ ] Vista `solicitarVacacion.php` - Formulario solicitud
- [ ] Vista `misVacaciones.php` - Listado del empleado
- [ ] Vista `listadoVacaciones.php` - Admin: todas las solicitudes
- [ ] Vista `resolverVacacion.php` - Aprobar/rechazar
- [ ] Vista `uploadJustificante.php` - Subir justificante de baja
- [ ] JavaScript `vacaciones.js`

### Fase 6: Reportes (Días 13-14)
- [ ] Crear `ReportesHorario.php` (controlador)
- [ ] Vista `selectorReportes.php` - Formulario de selección
- [ ] Reporte CSV de fichajes
- [ ] Reporte PDF mensual por empleado (usando HTML2PDF existente)
- [ ] Reporte PDF global empresa
- [ ] Vista `reporteMensualEmpleado.php`
- [ ] Vista `reporteGlobalEmpresa.php`

### Fase 7: Auditoría y Seguridad (Día 15)
- [ ] Tabla `auditoria` operativa en todos los controladores
- [ ] Registro automático de IP y user-agent en cada fichaje
- [ ] Registro de acciones administrativas (aprobaciones, rechazos, modificaciones)
- [ ] Verificar que no se puede manipular fecha/hora desde el cliente
- [ ] Rate limiting básico para prevenir spam de fichajes

### Fase 8: Integración, Testing y Deployment (Días 16-17)
- [ ] Integración con sidebar existente y permisos
- [ ] Testing funcional completo
- [ ] Pruebas deedge cases (doble entrada, olvido de salida, jornada sin cerrar)
- [ ] Pruebas responsive en móvil
- [ ] Ajustes UX
- [ ] Mini guía admin/usuario
- [ ] Deployment en servidor de producción

---

## 8. Notas de Seguridad Críticas

1. **Hora del servidor**: TODOS los fichajes usan `NOW()` de MySQL, nunca confiar en timestamps del cliente
2. **Sin edición manual**: Los empleados NO pueden editar sus fichajes, solo solicitar correcciones
3. **Sin eliminación**: Soft delete (`eliminado=1`) nunca DELETE físico
4. **Auditoría completa**: Cada acción administrativa se loguea con datos previos y nuevos
5. **Protección contra duplicados**: Validación server-side de que no exista fichaje del mismo tipo seguido
6. **Validación de permisos**: Empleado solo ve sus datos; admin ve todo
7. **Registro IP/UA**: Todo fichaje queda con IP y User-Agent
8. **Ley RDL 8/2019**: El registro debe conservarse 4 años mínimo (los registros nunca se borran)

---

## 9. Riesgos y Consideraciones

| Riesgo | Mitigación |
|--------|------------|
| Jornada sin cerrar (olvido de salida) | Sistema detecta al día siguiente y notifica al admin; empleado puede solicitar corrección |
| Pérdida de conexión al fichar | El botón queda deshabilitado tras respuesta exitosa; si falla, no se registra y se reintenta |
| Múltiples pestañas/ventanas | Validación server-side del estado actual antes de registrar |
| Manipulación de hora del dispositivo | Siempre usar `NOW()` del servidor |
| Acceso de cliente (rol=1) a control horario | Permisos excluyen cliente del menú ControlHorario |