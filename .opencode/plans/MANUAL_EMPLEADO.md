# Manual de Usuario — Control Horario y Vacaciones
## Para el Empleado

---

## 1. Fichar Entrada y Salida

Puedes fichar de dos formas:

### 1.1. Desde la página de login

`[IMAGEN: Captura de la página de login mostrando los campos de correo, contraseña y el botón verde "Fichar" debajo del botón "Acceder"]`

1. En la página de inicio de sesión, introduce tu correo y contraseña
2. Pulsa el botón verde **"Fichar"** (con icono de reloj)
3. El sistema te autentica y te lleva directamente a la pantalla de fichaje, donde deberás pulsar el botón para registrar tu entrada o salida

### 1.2. Desde dentro de la aplicación

`[IMAGEN: Captura del menú lateral con la sección "Mi Control Horario" desplegada, mostrando la opción "Fichar"]`

1. En el menú lateral, ve a **Mi Control Horario → Fichar**
2. La página muestra tu estado actual:
   - **"Dentro"** (si has fichado entrada y no has fichado salida)
   - **"Fuera"** (si no has fichado entrada o ya fichaste salida)
3. Pulsa el botón grande central:
   - **Verde "Fichar Entrada"** — si estás fuera
   - **Rojo "Fichar Salida"** — si estás dentro

`[IMAGEN: Pantalla de fichaje mostrando el botón grande verde "Fichar Entrada" y el estado actual]`

4. La hora se registra automáticamente desde el servidor (no se puede manipular)

### 1.3. Jornada partida (doble entrada)

Puedes fichar entrada por la mañana, salida para la pausa del almuerzo, entrada de nuevo y salida al final del día. El sistema calcula las horas totales automáticamente.

`[IMAGEN: Pantalla de fichaje mostrando varios fichajes del día con "Entrada" en verde y "Salida" en rojo, y el total de horas al final]`

### 1.4. Situaciones especiales

- **Si olvidaste fichar la salida**: El sistema marca la jornada como "Incompleta" al día siguiente. Debes solicitar una corrección (ver sección 3).
- **Días de vacaciones o baja aprobada**: El sistema bloquea el fichaje y muestra un aviso en rojo indicando el tipo de permiso.

`[IMAGEN: Alerta roja "Fichaje bloqueado" que aparece cuando intentas fichar en un día de vacaciones/baja]`

---

## 2. Mi Historial

`[IMAGEN: Captura de la vista "Mi Historial" con la lista de jornadas filtradas por mes]`

1. Ve a **Mi Control Horario → Mi Historial**
2. Selecciona el mes y año que quieres consultar
3. Se muestra una tabla con:
   - Fecha de cada jornada
   - Horas trabajadas
   - Estado (Cerrada, Incompleta, Vacaciones, Baja, etc.)
4. Pulsa en cualquier jornada para ver el detalle completo de sus fichajes

`[IMAGEN: Vista de detalle de jornada mostrando la tabla de fichajes con tipo, hora, observaciones y si está corregido]`

---

## 3. Solicitar Corrección de Fichaje

`[IMAGEN: Captura del formulario "Solicitar Modificación" con los campos visibles]`

1. Ve a **Mi Control Horario → Solicitar Mod.** (o pulsa el botón "Solicitar corrección" en el detalle de una jornada)
2. Selecciona el **tipo de modificación**:
   - **Insertar**: Añadir un fichaje que olvidaste registrar
   - **Modificar**: Cambiar la hora o tipo de un fichaje existente
   - **Omitir**: Marcar un fichaje como erróneo (para que se elimine)
3. Según el tipo seleccionado, se muestran campos diferentes:

   | Tipo | Campos que aparecen |
   |------|---------------------|
   | Insertar | Jornada, Tipo (Entrada/Salida), Fecha y hora |
   | Modificar | Fichaje a corregir, Nueva hora, Nuevo tipo |
   | Omitir | Fichaje a corregir |

`[IMAGEN: Captura del formulario con tipo "Modificar" seleccionado, mostrando el desplegable de fichajes a corregir y campo de nueva hora]`

4. Escribe el **motivo de la solicitud** (campo obligatorio)
5. Opcionalmente, añade **observaciones**
6. Pulsa **"Enviar solicitud"**
7. El administrador aprobará o rechazará la solicitud. Puedes consultar el estado en **Mis Solicitudes**

`[IMAGEN: Vista "Mis Solicitudes" mostrando solicitudes con estado pendiente, aprobada o rechazada]`

---

## 4. Mi Calendario

`[IMAGEN: Captura del calendario mensual con las jornadas coloreadas por estado]`

1. Ve a **Mi Control Horario → Mi Calendario**
2. Selecciona el mes y año
3. Se muestra un calendario con cada jornada coloreada según su estado:

   | Color | Estado |
   |-------|--------|
   | Verde | Cerrada (jornada completada) |
   | Azul | Abierta (fichaste entrada pero no salida) |
   | Amarillo | Incompleta |
   | Morado | Vacaciones |
   | Rojo | Baja médica |
   | Rosa | Ausencia justificada |
   | Gris | Sin registro |

`[IMAGEN: Leyenda de colores del calendario que aparece debajo del calendario]`

---

## 5. Solicitar Vacaciones, Baja o Ausencia

`[IMAGEN: Captura del formulario "Solicitar Vacaciones / Baja / Permiso" con los campos del formulario]`

1. Ve a **Mi Control Horario → Vacaciones/Bajas** y pulsa **"Nueva solicitud"**
2. Selecciona el **tipo**:
   - **Vacaciones**
   - **Baja médica**
   - **Ausencia justificada**
3. Selecciona **fecha inicio** y **fecha fin** (ambas obligatorias)
4. El sistema calcula automáticamente los **días** de duración
5. Escribe el **motivo** (obligatorio)
6. **Justificante** (archivo adjunto):
   - Para **baja médica**: el justificante es **obligatorio** (campo marcado con asterisco rojo)
   - Para vacaciones y ausencia: es opcional
   - Formatos aceptados: PDF, JPG, PNG, DOC, DOCX
   - Tamaño máximo: 5 MB

`[IMAGEN: Formulario con tipo "Baja médica" seleccionado, mostrando el campo "Justificante médico" como obligatorio]`

7. Pulsa **"Enviar solicitud"**
8. El administrador aprobará o rechazará tu solicitud

### 5.1. Ver mis vacaciones/bajas/ausencias

`[IMAGEN: Captura de "Mis Vacaciones / Bajas / Ausencias" mostrando la lista de solicitudes con estado]`

1. Ve a **Mi Control Horario → Vacaciones/Bajas**
2. Se muestran todas tus solicitudes con su estado:
   - **Pendientes** (amarillo)
   - **Aprobadas** (verde)
   - **Rechazadas** (rojo)
3. Puedes subir un justificante médico si no lo hiciste al crear la solicitud

### 5.2. Importante

- **No puedes fichar** en días con vacaciones o baja aprobada — el sistema lo bloquea
- Si tus vacaciones se solapan con otra solicitud existente, el sistema mostrará un error
- Solo el administrador puede cancelar vacaciones ya aprobadas. Si necesitas cancelarlas, contacta con el administrador