# Manual de Administrador — Control Horario
## Para el Administrador

---

## 1. Configurar quién ficha

`[IMAGEN: Captura de la vista "Configuración de Horario" con la tabla de empleados y sus opciones]`

1. Ve a **Admin. Horario → Configuración**
2. Se muestra una tabla con todos los empleados y sus datos:
   - Nombre
   - Rol
   - Debe Fichar (Sí/No)
   - Horas de jornada
   - Hora de entrada y salida
3. Pulsa **"Editar"** junto al empleado que quieras configurar
4. En el modal que aparece, puedes modificar:

   | Campo | Descripción |
   |-------|-------------|
   | Debe Fichar | Activa/desactiva el acceso al módulo de Control Horario para ese empleado |
   | Horas de jornada | Horas esperadas por jornada (por defecto 8h). Se usa en reportes para calcular la diferencia |
   | Hora de entrada | Horario informativo de entrada |
   | Hora de salida | Horario informativo de salida |

`[IMAGEN: Modal de edición de configuración de horario mostrando los campos editables]`

5. Pulsa **"Guardar"**
6. Los cambios surten efecto al cerrar sesión y volver a entrar

### 1.1. Importante

- Si desactivas **"Debe Fichar"** para un empleado, este dejará de ver el menú de Control Horario y Vacaciones
- Las horas de jornada se usan en los reportes para calcular la diferencia entre horas trabajadas y esperadas

---

## 2. Listado Global de Fichajes

`[IMAGEN: Captura del "Listado Global" con filtros y tabla de empleados]`

1. Ve a **Admin. Horario → Listado Global**
2. Si hay solicitudes de modificación pendientes, aparece un aviso amarillo con enlace directo
3. Si hay vacaciones/permisos pendientes, aparece un aviso morado con enlace directo
4. Filtra por:
   - **Empleado** (desplegable con empleados activos e inactivos)
   - **Fecha inicio** y **fecha fin**
5. La tabla muestra:
   - Empleado
   - Fecha de cada jornada
   - Estado de la jornada (Cerrada, Abierta, Incompleta, Vacaciones, Baja, Ausencia)
   - Horas trabajadas
   - Completada (Sí/No)
6. Pulsa en un empleado para ver su **detalle por jornada**

`[IMAGEN: Vista "Detalle de Empleado" mostrando las jornadas de un empleado específico]`

---

## 3. Calendario de Jornadas

`[IMAGEN: Captura del "Calendario de Jornadas" con un empleado seleccionado y su calendario mensual]`

1. Ve a **Admin. Horario → Calendario Jornadas**
2. Selecciona un **empleado** del desplegable (obligatorio)
3. Selecciona mes y año
4. Se muestra un calendario con las jornadas del empleado coloreadas:

   | Color | Estado |
   |-------|--------|
   | Verde | Cerrada |
   | Azul | Abierta |
   | Amarillo | Incompleta |
   | Morado | Vacaciones |
   | Rojo | Baja |
   | Rosa | Ausencia |
   | Gris | Sin registro |

`[IMAGEN: Leyenda de colores del calendario de jornadas]`

---

## 4. Resolver Solicitudes de Modificación de Fichaje

`[IMAGEN: Captura de "Solicitudes de Modificación" mostrando las solicitudes pendientes y todas las solicitudes]`

1. Ve a **Admin. Horario → Modificaciones fichajes** (o pulsa el aviso amarillo en el Listado Global)
2. Se muestran dos secciones:
   - **Solicitudes Pendientes** (fondo amarillo, con aviso de prioridad)
   - **Todas las Solicitudes** (con estado: pendiente, aprobada, rechazada)
3. Pulsa en una solicitud para ver el detalle

`[IMAGEN: Vista "Resolver Solicitud" mostrando los detalles de la solicitud y los botones de aprobar/rechazar]`

4. En el detalle puedes ver:
   - Datos del empleado
   - Tipo de solicitud (Insertar, Modificar, Omitir)
   - Fichaje original (si aplica)
   - Datos del fichaje solicitado
   - Motivo y observaciones
5. Pulsa **"Aprobar"** o **"Rechazar"**
6. Si rechazas, puedes escribir una **respuesta** opcional para explicar el motivo

### 4.1. Efecto de la aprobación

- **Insertar**: Se crea un nuevo fichaje con los datos solicitados
- **Modificar**: El fichaje original se marca como **"Corregido"** (aparece con fondo amarillo, hora tachada y badge "Corregido"), y se inserta un nuevo fichaje con los datos corregidos
- **Omitir**: El fichaje original se marca como **"Corregido"** y se inserta un fichaje con la corrección

---

## 5. Gestionar Vacaciones, Bajas y Ausencias

`[IMAGEN: Captura de "Solicitudes de vacaciones y permisos" mostrando las solicitudes pendientes y todas las solicitudes]`

1. Ve a **Admin. Horario → Vacaciones y permisos** (o pulsa el aviso morado en el Listado Global)
2. Se muestran dos secciones:
   - **Solicitudes Pendientes** (amarillo)
   - **Todas las Solicitudes** (con estado: pendiente, aprobada, rechazada)
3. Pulsa en una solicitud para ver el detalle

`[IMAGEN: Vista "Resolver Solicitud de Vacaciones/Baja/Ausencia" con los botones de aprobar/rechazar]`

4. En el detalle puedes ver:
   - Tipo (Vacaciones, Baja médica, Ausencia justificada)
   - Empleado
   - Fecha inicio y fin
   - Número de días
   - Motivo
   - Justificante (si se subió)
5. Pulsa **"Aprobar"** o **"Rechazar"** con respuesta opcional

### 5.1. Efecto de la aprobación

- Las **vacaciones aprobadas** bloquean el fichaje del empleado en esos días
- Las **bajas aprobadas** bloquean el fichaje y marcan la jornada como "Baja"
- Las **ausencias aprobadas** bloquean el fichaje y marcan la jornada como "Ausencia"

### 5.2. Cancelar vacaciones, bajas o ausencias aprobadas

`[IMAGEN: Vista de detalle de una solicitud aprobada mostrando el botón amarillo "Cancelar vacaciones/baja/ausencia"]`

Si una solicitud ya está aprobada y necesita cancelarse:

1. Ve a **Admin. Horario → Vacaciones y permisos**
2. Busca la solicitud aprobada en la sección **Todas las Solicitudes**
3. Pulsa en ella para ver el detalle
4. Aparecerá un botón amarillo según el tipo:
   - **"Cancelar vacaciones"** (si es vacaciones)
   - **"Cancelar baja"** (si es baja médica)
   - **"Cancelar ausencia"** (si es ausencia justificada)
5. Al pulsar, el sistema solicitará confirmación
6. La cancelación revierte los días marcados y desbloquea el fichaje para esos días

**Nota**: Solo el administrador puede cancelar solicitudes aprobadas. Los empleados no pueden cancelar sus propias solicitudes una vez aprobadas.

---

## 6. Reportes de Control Horario

`[IMAGEN: Captura de la vista "Generar Reportes" con las tres secciones: Exportar Fichajes, Reporte Mensual y Reporte Global]`

1. Ve a **Reportes Horario → Generar Reportes**
2. Tres secciones disponibles:

### 6.1. Exportar Fichajes

`[IMAGEN: Sección "Exportar Fichajes" con campos de fecha, empleado y formato]`

- Selecciona **fecha inicio** y **fecha fin**
- Opcionalmente selecciona un **empleado** (o "Todos")
- Selecciona el **formato**:
  - **CSV**: Archivo de texto con valores separados por punto y coma
  - **Excel (.xlsx)**: Hoja de cálculo Excel
  - **PDF**: Documento PDF con tabla de fichajes
- Pulsa **"Exportar"**

### 6.2. Reporte Mensual por Empleado

`[IMAGEN: Sección "Reporte Mensual por Empleado" con campos de mes, año y selector de empleado]`

- Selecciona **mes**, **año** y **empleado**
- Pulsa **"Ver en pantalla"** para ver el reporte en pantalla
- Pulsa **"Generar PDF"** para descargar el reporte en PDF

`[IMAGEN: Captura del reporte mensual en pantalla mostrando las jornadas del empleado]`

### 6.3. Reporte Global de Empresa

`[IMAGEN: Sección "Reporte Global de Empresa" con campos de mes y año]`

- Selecciona **mes** y **año**
- Pulsa **"Ver en pantalla"** para ver el resumen de todos los empleados
- Pulsa **"Generar PDF"** para descargar el reporte en PDF

---

## 7. Restricciones importantes

- **No se pueden borrar fichajes**: Solo corrección vía solicitudes
- **No se pueden editar fichajes directamente**: Solo el administrador aprueba correcciones
- **La hora es siempre del servidor**: No se puede manipular desde el navegador
- **Todo se registra en auditoría**: Cada acción queda trazada
- **Si un empleado tiene "Debe Fichar" desactivado**: No verá el menú de Control Horario ni Vacaciones