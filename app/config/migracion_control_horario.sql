-- ============================================================
-- MIGRACIÓN: Control Horario y Vacaciones - CRM Telesat
-- Fecha: 2026-05-22
-- Cumplimiento: Real Decreto-ley 8/2019 (España)
-- ============================================================

-- Tabla: jornadas
CREATE TABLE IF NOT EXISTS `jornadas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idempleado` int(10) NOT NULL COMMENT 'FK a usuarios.id',
  `fecha` date NOT NULL COMMENT 'Fecha de la jornada',
  `estadojornada` varchar(20) NOT NULL DEFAULT 'abierta' COMMENT 'abierta, cerrada, incompleta, vacaciones, baja',
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

-- ============================================================
-- DATOS INICIALES: Activar fichaje para todos los técnicos (rol=2) y admins (rol=0)
-- ============================================================
INSERT INTO `configuracionhorario` (`idempleado`, `debeFichar`, `jornadatipohoras`, `horarioentrada`, `horariosalida`, `toleranciaminutos`)
SELECT `id`, 1, 8.00, '09:00:00', '18:00:00', 10
FROM `usuarios`
WHERE `rol` IN (0, 2) AND `activo` = 1;

-- ============================================================
-- JSON DE PERMISOS PARA rolesbase (APLICAR MANUALMENTE)
-- ============================================================
-- Añadir al campo `permisos` (JSON) de cada rol los bloques siguientes:
--
-- Para rol=0 (admin), añadir al JSON existente:
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
--
-- Para rol=2 (tecnico), añadir al JSON existente:
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