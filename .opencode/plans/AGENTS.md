# AGENTS.md - Guía de Desarrollo CRM Telesat

## Proyecto
CRM Telesat - Sistema de gestión de incidencias para telecomunicaciones con nueva funcionalidad de Control Horario y Vacaciones (RDL 8/2019 español).

## Stack Tecnológico
- **Backend**: PHP 7.x+ (sin Composer, framework MVC custom)
- **Base de datos**: MariaDB 10.4 (puerto 3307), DB: `crm_telesat`
- **Frontend**: Tailwind CSS, JavaScript ES6 modules, Font Awesome, Select2, Chart.js
- **PDF**: HTML2PDF (spipu/html2pdf)
- **Servidor**: XAMPP (Apache + PHP + MySQL)

## Estructura del Proyecto

```
C:\xampp\htdocs\crm_tsat\
├── app/
│   ├── config/configurar.php          # Constantes globales y DB config
│   ├── controlers/                    # NOTA: ortografía "controlers" (sin 'l')
│   ├── helpers/url_helpers.php        # Helper de redirección
│   ├── librerias/                     # Core framework (autoloaded via spl)
│   │   ├── Base.php                   # PDO wrapper
│   │   ├── Controlador.php           # Controlador base
│   │   └── Core.php                   # Router
│   ├── models/Modelo*.php            # Modelos (prefix Modelo)
│   └── views/                         # Vistas PHP plano
│       └── includes/                  # Header, navbar, sidebar, footer
├── public/
│   ├── index.php                      # Entry point
│   ├── css/, js/, img/, librerias/    # Assets
│   └── documentos/                    # Uploads
└── crm_telesat.sql                    # Schema DB
```

## Convenciones de Código

### Nombres
- **Controladores**: PascalCase en español en `app/controlers/` (ej: `ControlHorario.php`, `Vacaciones.php`)
- **Modelos**: Prefijo `Modelo` + PascalCase en `app/models/` (ej: `ModeloControlHorario.php`)
- **Vistas**: Carpeta por controller, camelCase en `app/views/{controller}/` (ej: `controlHorario/fichar.php`)
- **Tablas DB**: lowercase plural español (ej: `fichajes`, `jornadas`, `vacaciones`)
- **Columnas DB**: lowercase camelCase sin guiones (ej: `idempleado`, `tipofichaje`, `fechahora`)

### Patrón MVC
1. **Router** (`Core.php`): Parsea URL como `/Controlador/metodo/param1/param2`
2. **Controlador**: Extiende `Controlador`, carga modelos con `$this->modelo('ModeloX')`, renderiza con `$this->vista('carpeta/archivo', $datos)`
3. **Modelo**: Extiende `ModeloBase`, crea `$this->db = new Base;` en constructor, usa `$this->db->query()`, `$this->db->bind()`, `$this->db->registros()`
4. **Vista**: PHP plano, incluye header/navbar/sidebar/footer, recibe `$datos` como array

### BD - Patrón de Consultas
```php
// En el modelo:
$this->db = new Base;
$this->db->query("SELECT * FROM tabla WHERE campo = :valor");
$this->db->bind(':valor', $valor);
$resultados = $this->db->registros(); // array de objetos
$fila = $this->db->registro();        // un objeto
$id = $this->db->lastInsertId();
```

### Sesiones y Permisos
- Sesiones: `session_start()` en constructor del controlador
- Permisos: `$this->controlPermisos()` verifica URL contra `$_SESSION['controlLinksUsuario']`
- Variables de sesión clave: `$_SESSION['idusuario']`, `$_SESSION['nombrerol']`, `$_SESSION['permisos']`
- Roles: 0=admin, 1=cliente, 2=tecnico, 3=visitante/fichador
- Roles en `rolesbase`: id=1 (admin, rol=0), id=2 (cliente, rol=1), id=3 (tecnico, rol=2), id=4 (visitante, rol=3)
- **Guard de sesión**: Los controladores CHOVA verifican `$_SESSION['idusuario']` antes de `controlPermisos()` para evitar error fatal si la sesión no existe

### Vistas - Layout
```php
<?php require_once(RUTA_APP . '/views/includes/header-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/navbar-tailwind.php'); ?>
<?php require_once(RUTA_APP . '/views/includes/sidebar-tailwind.php'); ?>
<!-- Contenido específico de la vista -->
<?php require_once(RUTA_APP . '/views/includes/footer.php'); ?>
```

### Constantes Importantes
- `RUTA_URL` = `http://localhost:8080/crm_tsat` (base URL)
- `RUTA_APP` = path absoluto a `app/`
- `RUTA_PERMISOS` = `/crm_tsat`
- `NOMBRE_SITIO` = `TELESAT`
- `DB_HOST` = `localhost:3307`, `DB_NOMBRE` = `crm_telesat`

## Comandos de Desarrollo

### Verificar PHP
```bash
php -f "ruta/al/archivo.php"
```

### Acceder a MariaDB
```bash
& "C:\xampp\mysql\bin\mysql.exe" -u root -P 3307 crm_telesat
```

### No hay Composer/npm scripts para tests
- No existe framework de tests automatizado
- Testing es manual funcional

## POLITICA DE SEGURIDAD CON LA BASE DE DATOS

### Regla 1: SELECT sin restricciones
Cualquier consulta SELECT puede ejecutarse directamente usando el usuario `root`. No requiere autorización del usuario.

### Regla 2: DELETE - NUNCA ejecutar
Las queries DELETE **nunca** se ejecutan directamente. Si se necesita una eliminación, se informa al usuario y se le entrega la query para que la ejecute manualmente. NUNCA se ejecuta un DELETE sin autorización explícita.

### Regla 3: CREATE, INSERT, UPDATE - Requieren autorización
Las queries de tipo CREATE, INSERT y UPDATE deben ser consultadas al usuario primero. Solo se ejecutan directamente si el usuario responde exactamente: **"Sí, ejecuta directamente en la BD, te autorizo"**. Cualquier otra respuesta significa que no se ejecuta y se entrega la query al usuario.

### Usuarios de Base de Datos
| Usuario | Propósito | Permisos | Uso |
|---------|-----------|----------|-----|
| `root` | Aplicación PHP + consultas opencode | Todos | La app y las consultas usan este usuario |

- **Nota**: El usuario `crm_dev` (solo lectura) perdió sus permisos tras el incidente de corrupción de MariaDB. Se usa `root` para todas las consultas, respetando la política de seguridad (SELECT libre, INSERT/UPDATE/CREATE con autorización, DELETE nunca).

## Reglas Críticas de la Funcionalidad de Control Horario

1. **Hora del SERVIDOR**: Siempre `NOW()` de MySQL, NUNCA timestamp del cliente
2. **Inmutabilidad**: Nunca DELETE físico, solo soft delete con `eliminado=1`
3. **Sin edición manual**: Los empleados solicitan correcciones, los admins las aprueban
4. **Auditoría**: Toda acción se registra en tabla `auditoria` con datos previos/nuevos
5. **Geolocalización**: Capturar lat/long al fichar (si está disponible)
6. **IP y User-Agent**: Registrar en cada fichaje
7. **Cumplimiento RDL 8/2019**: Registros conservados mínimo 4 años

## Estructura de Permisos JSON (`rolesbase.permisos`)

### Formato correcto (con sub-métodos AJAX incluidos)

Los permisos deben incluir **TODOS** los métodos accesibles vía URL, incluidos los AJAX/POST. Si un método no está en la lista, `controlPermisos()` destruye la sesión.

```json
"ControlHorario": ["/ControlHorario", "fas fa-clock", "Control Horario",
  [
    ["/ControlHorario", "Control Horario",
      ["/ControlHorario/fichar","link"],
      ["/ControlHorario/registrarFichaje","link"],
      ["/ControlHorario/obtenerEstadoActual","link"],
      ["/ControlHorario/miHistorial","link"],
      ["/ControlHorario/misSolicitudes","link"],
      ["/ControlHorario/solicitarModificacion","link"]
    ]
  ]
]
```

### Tabla rolesbase
| id | rol | nombre | Permisos CHOVA |
|----|-----|---------|----------------|
| 1 | 0 | admin | ControlHorario (completo) + AdministracionHorario + Vacaciones + ReportesHorario |
| 2 | 1 | cliente | Sin acceso CHOVA |
| 3 | 2 | tecnico | ControlHorario (fichar/historial/solicitudes) + Vacaciones (solicitar/misVacaciones) |
| 4 | 3 | visitante | ControlHorario (solo fichar) |

### Archivo de actualización de permisos
El script `app/config/update_permisos_chova.php` (ya eliminado por seguridad) fue ejecutado para actualizar los permisos. Para futuras actualizaciones, crear un script similar y eliminarlo tras ejecutarlo.

## Integración Usuarios ↔ Configuración Horario

El controlador `Usuarios.php` (del CRM original) fue modificado para gestionar los registros de `configuracionhorario` al editar usuarios. **Criterio: NO se crea automáticamente el registro al crear usuarios.** El admin gestiona `debeFichar` manualmente desde Configuración Horario.

### Lógica implementada

- **Crear usuario** (`crearUsuario()`): No se crea config automáticamente. El admin lo gestiona manualmente desde Configuración Horario.
- **Editar usuario** (`editarUsuario()`): Si cambia a cliente (rol=1) y ya tiene config → pone `debeFichar=0`. No se crea config si no existe. No se actualiza `debeFichar` a 1.
- **Seguridad**: La llamada a `actualizarConfigHorarioSegunRol()` está dentro de un `try-catch`. Si algo falla en config horario, el CRUD de usuarios sigue funcionando.

### Función privada en Usuarios.php

1. `actualizarConfigHorarioSegunRol($idEmpleado, $rol)`: Si rol=1 y tiene config → pone `debeFichar=0`. No crea config, no actualiza `debeFichar` a 1.

### Modelo utilizado

- `ModeloConfiguracionHorario` con métodos: `obtenerConfigPorEmpleado()`, `crearConfig()`, `actualizarConfig()`, `obtenerTodosConfig()`, `empleadoDebeFichar()`, `obtenerEmpleadosActivos()`.

### Pendiente
- ~~Punto 3: Migración de config para usuarios existentes sin registro en `configuracionhorario`.~~ **RESUELTO**: `obtenerTodosConfig()` ahora muestra usuarios sin config (COALESCE), el admin puede crearles config manualmente. Método temporal `migrarConfigHorario()` y vista `migrarResultado.php` pendientes de eliminar (ver Limpieza post-migración).

## Restricción de acceso CHOVA por debeFichar

Si un usuario tiene `debeFichar=0` en `configuracionhorario` (o no tiene registro), pierde acceso al módulo CHOVA (ControlHorario y Vacaciones).

### Lógica implementada

1. **Login.php**: Al iniciar sesión (`acceder()` y `fichar()`), se consulta `ModeloConfiguracionHorario::empleadoDebeFichar()` y se guarda en `$_SESSION['debeFichar']` (1 o 0).
2. **menu-sidebar-desktop.php y menu-sidebar-mobile.php**: Si `$_SESSION['debeFichar'] == 0`, los bloques "ControlHorario" y "Vacaciones" se ocultan del sidebar (no se renderizan).
3. **ControlHorario.php y Vacaciones.php**: En el constructor, si `$_SESSION['debeFichar'] == 0`, redirigen a `/Inicio`.
4. **ModeloConfiguracionHorario::empleadoDebeFichar()**: Devuelve `true` solo si existe registro en `configuracionhorario` con `debeFichar=1`. Si no hay registro, devuelve `false`. Si hay registro con `debeFichar=0`, devuelve `false`.

### Comportamiento por rol y debeFichar

| debeFichar | Rol | Acceso CHOVA | Acceso CRM |
|-----------|-----|--------------|------------|
| 1 | Admin (0) | Sí | Sí |
| 0 | Admin (0) | No (sidebar oculto, URL redirige) | Sí |
| 1 | Técnico (2) | Sí | Sí |
| 0 | Técnico (2) | No (sidebar oculto, URL redirige) | Sí |
| 1 | Visitante (3) | Sí (solo CHOVA) | No |
| 0 | Visitante (3) | No (atascado en login) | No |
| — | Cliente (1) | No (sin permisos CHOVA) | Sí |

### Nota importante

- `$_SESSION['debeFichar']` se fija al login. Si el admin cambia `debeFichar` para un usuario mientras está logado, el cambio no surte efecto hasta que el usuario cierre sesión y vuelva a logar. Esto es consistente con cómo funcionan los permisos en el CRM.
- Un visitante con `debeFichar=0` queda atascado en el login: su único destino es `/ControlHorario/fichar` y el guard lo redirige a `/Inicio` que no tiene permisos. Esto es coherente con la lógica del sistema.

### Archivos modificados (2026-06-09)

- `app/controlers/Login.php`: `$_SESSION['debeFichar']` en `acceder()` y `fichar()`
- `app/views/includes/menu-sidebar-desktop.php`: Filtro `debeFichar` en foreach
- `app/views/includes/menu-sidebar-mobile.php`: Filtro `debeFichar` en foreach
- `app/controlers/ControlHorario.php`: Guard `debeFichar` en constructor
- `app/controlers/Vacaciones.php`: Guard `debeFichar` en constructor
- `app/views/usuarios/altaUsuarios/altaUsuarios.php`: Opción Visitante en select de rol
- `app/views/usuarios/actualizarUsuarios/actualizarUsuarios.php`: Select de rol dinámico
- `app/views/administracionHorario/configuracionHorario.php`: Rol Visitante en array de roles

## Principios de Diseño

1. **SOLID**: Una función, una responsabilidad. Cada método hace una sola cosa.
2. **Funciones pequeñas**: Prefiere 5 funciones de 10 líneas a 1 de 50. Legibilidad ante brevedad.
3. **Cambio acotado**: No modifiques lógica que funciona. Aísla lo nuevo.
4. **Punto de entrada único**: En el flujo principal, una sola llamada que se pueda comentar si falla.
5. **LEY DE VIDA - NO TOCAR LO EXISTENTE**: NUNCA modifiques código de archivos fuera del alcance de las nuevas funcionalidades, hasta que el usuario diga explícitamente "adelante, aplica". Los archivos existentes del CRM son intocables salvo confirmación expresa.
6. **Siempre propone antes**: Presenta la propuesta primero y espera confirmación del usuario antes de implementar.

## Notas sobre Seguridad Existente

- Las contraseñas están en texto plano en la DB (ya existente, no modificar)
- Varias queries usan interpolación de strings (SQL injection risk existente)
- Para el nuevo código, USAR parametros bind (`:param`) en todas las consultas
- Los controladores CHOVA tienen guard de sesión: `if (!isset($_SESSION['idusuario'])) { redireccionar('/Login'); return; }` antes de `controlPermisos()`
- El bug original de `controlPermisos()` (no verifica si `$_SESSION['controlLinksUsuario']` existe) no se modifica (principio de vida). Los controladores CHOVA protegen contra esto con el guard de sesión.

## Lecciones Aprendidas

- **NUNCA ejecutar REPAIR TABLE en tablas del sistema (`mysql.global_priv`, `mysql.db`)**: Puede corromper MariaDB. El `REPAIR TABLE mysql.global_priv` ejecutado directamente causó un crash que requirió recuperación manual.
- **Los permisos JSON deben incluir TODOS los métodos accesibles vía URL**: Los métodos AJAX/POST que no estaban en `rolesbase.permisos` causaban que `controlPermisos()` destruyera la sesión.
- **`controlPermisos()` destruye la sesión si un URL no está en la whitelist**: Por eso es esencial el guard de sesión en los controladores nuevos.
- **Los scripts PHP que modifican permisos deben ejecutarse desde CLI, no desde el navegador**: El framework intercepta todas las URLs y redirige a Login si no hay sesión.
- **Los try-catch vacíos ocultan errores**: El `try-catch` vacío en `Usuarios.php` ocultó un fallo en `crearConfigHorarioSiCorresponde()`. Decisión final: eliminar la función por completo (criterio: el admin gestiona config manualmente).
- **`obtenerTodosConfig()` solo mostraba usuarios CON config**: La query `FROM configuracionhorario ch LEFT JOIN usuarios u` excluye usuarios sin registro. Solución: `FROM usuarios u LEFT JOIN configuracionhorario ch ON u.id = ch.idempleado` con COALESCE para valores por defecto.
- **Fichajes corregidos no deben aparecer en selects de corrección**: Los fichajes con `corregido=1` ya han sido corregidos y no tiene sentido que aparezcan en el select "Fichaje a corregir" de solicitarModificacion. Filtrarlos del array `$fichajesRecientes`.

## Criterios del Proyecto

### Configuración horario: creación manual por el admin
- **NO se crea automáticamente** el registro en `configuracionhorario` al crear usuarios de ningún rol (admin, técnico, visitante).
- **El admin gestiona `debeFichar` manualmente** desde Administración Horario → Configuración.
- **Al editar un usuario a cliente (rol=1)**: si ya tiene config, se pone `debeFichar=0`. No se crea config si no existe.
- **Al editar un usuario a no-cliente**: no se crea config, no se actualiza `debeFichar`. El admin lo gestiona manualmente.

### Eliminación de usuarios y datos CHOVA
- `borrarUsuario()` hace soft delete (`activo = -1`). No DELETE físico.
- Las tablas CHOVA nunca usan DELETE físico. Solo soft delete (`eliminado=1`) y correcciones (`corregido=1`). Cumple RDL 8/2019.
- No hay FK constraints en cascada entre tablas CHOVA y `usuarios`.
- **Usuario activo + `debeFichar=0`**: puede acceder al CRM pero no a CHOVA. El admin sí puede ver su historial en el listado global.
- **Usuario inactivo (`activo=-1`)**: no puede logarse, no aparece en filtros CHOVA (`activo=1`).

### Tolerancia por defecto
- La tolerancia por defecto es **5 minutos** (cambiado de 10).
- Hardcodeado en: `configuracionHorario.php` (input value), `ModeloConfiguracionHorario.php` (COALESCE), `AdministracionHorario.php` (POST default), `Usuarios.php` (actualizarConfigHorarioSegunRol).

### Campos de configuración horario: uso actual
- `debeFichar`: ✅ Controla acceso a CHOVA (0/1)
- `jornadatipohoras`: Solo informativo en vista Configuración. Pendiente implementar comparación con horas reales.
- `horarioentrada`: Solo informativo. Pendiente implementar detección de retrasos.
- `horariosalida`: Solo informativo. Pendiente implementar detección de salidas anticipadas.
- `toleranciaminutos`: Solo informativo. Pendiente implementar en cálculo de retrasos.

## Bugs Abiertos (Pendientes)

### Limpieza post-migración pendiente
- Eliminar método `migrarConfigHorario()` de `AdministracionHorario.php`
- Eliminar vista `migrarResultado.php`
- Eliminar permiso `/AdministracionHorario/migrarConfigHorario` de `rolesbase.permisos`

## Bugs Cerrados

### Bug #42: crearConfigHorarioSiCorresponde no crea config al crear usuario — CERRADO
- **Decisión**: Eliminar `crearConfigHorarioSiCorresponde()` por completo. El admin gestiona `debeFichar` manualmente desde Configuración Horario.
- `obtenerTodosConfig()` corregido para mostrar usuarios sin config (LEFT JOIN desde `usuarios`).
- `actualizarConfigHorarioSegunRol()` simplificada: solo pone `debeFichar=0` si cambia a cliente y ya tiene config.