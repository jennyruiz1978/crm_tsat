# Guía de Uso - Control Horario y Vacaciones

## Para el Administrador

### Configuración Inicial

1. **Ejecutar la migración SQL** en MariaDB:
   ```
   mysql -u root -P 3307 crm_telesat < app/config/migracion_control_horario.sql
   ```

2. **Actualizar permisos** en la tabla `rolesbase`:
   - Editar el registro id=1 (admin, rol=0): añadir bloques de ControlHorario, AdministracionHorario, Vacaciones, ReportesHorario al JSON de `permisos`
   - Editar el registro id=3 (tecnico, rol=2): añadir bloques de ControlHorario y Vacaciones
   - El JSON detallado está en los comentarios del archivo `app/config/migracion_control_horario.sql`

3. **Cerrar sesión y volver a entrar** para que los nuevos permisos se carguen en la sesión.

4. **Verificar configuración de fichaje**: Ir a Administración Horario → Configuración y verificar que los empleados que deben fichar tienen `debeFichar = 1`.

### Módulos Disponibles

#### Control Horario (Empleado)
- **Fichar**: Botón grande de entrada/salida. Registra la hora del servidor (no del dispositivo), IP y geolocalización.
- **Mi Historial**: Consulta de fichajes propios por mes/año.
- **Mis Solicitudes**: Solicitar corrección de fichajes olvidados o erróneos.

#### Administración Horario (Admin)
- **Listado Global**: Todos los fichajes con filtros por empleado y fecha.
- **Solicitudes de Modificación**: Aprobar o rechazar solicitudes de corrección de fichajes.
- **Configuración**: Activar/desactivar el fichaje por empleado, configurar horas de jornada, horarios y tolerancia.
- **Calendario de Jornadas**: Vista mensual con colores por estado (abierta, cerrada, incompleta, vacaciones, baja).

#### Vacaciones
- **Solicitar**: Empleado solicita vacaciones, baja médica o ausencia.
- **Mis Vacaciones**: Listado propio del empleado.
- **Listado General (Admin)**: Aprobar/rechazar solicitudes de vacaciones.
- **Subir justificante**: Adjuntar documento PDF/imagen para bajas médicas.

#### Reportes Horario
- **CSV**: Exportar fichajes a archivo CSV con filtros de fecha y empleado.
- **PDF Mensual por Empleado**: Reporte detallado de fichajes de un empleado.
- **PDF Global de Empresa**: Resumen mensual de todos los empleados.

### Flujo de Aprobación de Correcciones

1. El empleado solicita una corrección desde "Mis Solicitudes".
2. El tipo puede ser: **Insertar** (fichaje olvidado), **Modificar** (hora incorrecta), u **Omitir** (fichaje erróneo).
3. El admin ve la solicitud en "Solicitudes de Modificación" con el detalle del fichaje original.
4. Si aprueba: se crea un nuevo fichaje correcto y el original se marca como corregido (nunca se elimina).
5. Si rechaza: el fichaje original se mantiene sin cambios.

---

## Para el Empleado (Técnico/Admin que ficha)

### Cómo Fichar

1. Inicia sesión en el CRM con tus credenciales habituales.
2. En el menú lateral, entra en **Control Horario → Fichar**.
3. Verás el estado actual (Dentro/Fuera) y un botón grande.
4. Pulsa el botón para registrar tu entrada o salida.
5. El sistema registra la **hora del servidor** (no la de tu dispositivo), tu IP y ubicación si está disponible.

### Reglas Importantes

- No puedes fichar dos veces el mismo tipo seguido (entrada tras entrada sin salida entre medio).
- Debes esperar 5 segundos entre fichajes.
- Si olvidaste fichar la salida, solicita una corrección desde "Mis Solicitudes".
- No puedes editar ni eliminar fichajes. Solo puedes solicitar correcciones.
- Si tienes jornadas abiertas de días anteriores, verás una alerta.

### Solicitar Corrección

1. Ve a **Control Horario → Mis Solicitudes**.
2. Pulsa "Nueva solicitud".
3. Selecciona el tipo:
   - **Insertar**: Para un fichaje que olvidaste hacer.
   - **Modificar**: Para cambiar la hora de un fichaje existente.
   - **Omitir**: Para marcar un fichaje como erróneo (se mantiene el registro original).
4. Escribe el motivo y envía.
5. El admin revisará y aprobará/rechazará tu solicitud.

### Solicitar Vacaciones

1. Ve a **Vacaciones → Solicitar**.
2. Selecciona el tipo (Vacaciones, Baja médica, Ausencia justificada).
3. Indica las fechas de inicio y fin.
4. Si es baja médica, puedes adjuntar un justificante.
5. El admin aprobará o rechazará la solicitud.

---

## Ciclo de Vida de una Jornada

### Estados de Jornada

| Estado | Significado |
|---|---|
| `abierta` | Jornada en curso. Se crea con este estado al fichar la primera entrada del día. |
| `cerrada` | Jornada completada correctamente (con entrada y salida). Se cierra automáticamente al día siguiente. |
| `incompleta` | Jornada donde solo se fichó la entrada (sin salida). Se marca automáticamente al día siguiente. Requiere corrección del empleado. |

### Transiciones

```
Fichar entrada (día X)
    → Jornada creada con estado "abierta"

Fichar salida (día X)
    → Se actualizan las horas, la jornada sigue "abierta"

Al día siguiente (día X+1), al acceder a la página de Fichar:
    → Si la jornada tiene entrada + salida → se cierra ("cerrada", completada=1)
    → Si la jornada tiene solo entrada → se marca "incompleta" (completada=0)
```

### Cierre Automático

El cierre automático de jornadas de días anteriores ocurre en **dos momentos**:

1. **Al cargar la página de Fichar** (`/ControlHorario/fichar`): antes de renderizar la vista, el sistema busca jornadas `abierta` de días anteriores y las cierra o marca incompletas.
2. **Al registrar un fichaje** (AJAX, `/ControlHorario/registrarFichaje`): antes de insertar el nuevo fichaje, se ejecuta el mismo proceso de cierre.

No es necesario pulsar el botón de fichar para que se cierre una jornada anterior. Solo con entrar a la página de Fichar ya se procesan las jornadas pendientes.

### Jornadas Incompletas

Cuando una jornada se marca como `incompleta`:

- El empleado ve un aviso amarillo en la página de Fichar indicando qué jornada quedó sin cierre.
- El empleado debe solicitar una corrección desde **Mis Solicitudes** para regularizarla.
- El administrador también puede ver las jornadas incompletas en el Calendario de Jornadas (color diferenciado).

---

## Cumplimiento Legal (RDL 8/2019)

- Todos los registros de fichaje son **inmutables**: nunca se eliminan físicamente.
- Las correcciones se realizan creando un registro nuevo, manteniendo el original.
- Toda acción administrativa se registra en la tabla de **auditoría**.
- Los registros se conservan sin límite de tiempo (mínimo 4 años según la ley).
- La hora registrada es siempre la del **servidor**, no la del dispositivo del empleado.
- Se registra IP y User-Agent en cada fichaje para trazabilidad.

### Pendiente: Verificar zonas horarias en producción

Antes de desplegar en producción, ejecutar estas dos consultas y verificar que la fecha/hora coincide:

```sql
SELECT NOW();        -- Hora de MySQL/MariaDB
```

```sql
SELECT @@global.time_zone, @@session.time_zone;  -- Zona horaria de MySQL
```

Y desde PHP:

```php
echo date('Y-m-d H:i:s');  // Hora de PHP
echo date_default_timezone_get();  // Zona horaria de PHP
```

Si `NOW()` de MySQL y `date()` de PHP devuelven horas diferentes, el sistema de cierre automático de jornadas podría comportarse incorrectamente (cerrar jornadas del día actual o no cerrar las del día anterior).