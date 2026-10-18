# Fix tablaClass: el ordenamiento persistía en sessionStorage (incidencias en orden inverso)

Fecha: 15/10/2026
Estado: ✅ **CERRADO** — fix aplicado en local y en copia de producción el mismo día
Sistema: CRM Telesat (crm_tsat)
Origen: funcionalidad de persistencia de estado en `sessionStorage` añadida el 14/10/2026 (sin documentar)

---

## 1. Descripción del problema

Desde la mañana del 15/10/2026 los usuarios reportan que el listado de incidencias aparece con las de agosto primero y las del día (15 de octubre) al final, en lugar del orden por defecto `inc.estado ASC, inc.creacion DESC` (más nuevas primero).

En sessionStorage quedaba guardado:

```json
{"busqueda": "{}", "filas": 30, "pagina": 0, "sortCriteria": [{"campo": "Creación", "dir": "ASC"}]}
```

## 2. Causa raíz

- El 14/10/2026 se añadió a `public/js/tablaClass/tablaClass.js` la persistencia de estado de las tablas: `guardarEstado()` / `restaurarEstado()` con clave `tablaClass_estado_{ruta}[_{id}][_{anio}]`, guardando `{busqueda, filas, pagina, sortCriteria}`.
- `sortCriteria` (el orden elegido por el usuario) quedaba persistido: **un solo clic** en la cabecera "Creación" (ciclo DEFAULT→ASC) se guardaba y sobrevivía a la navegación por el CRM, e incluso al cierre del navegador con restauración de sesión.
- Al reentrar, `restaurarEstado()` recuperaba `sortCriteria` y `rendertabla()` lo usaba en lugar del orden por defecto → backend `ORDER BY creacion ASC` (agosto primero, hoy al final).
- Alcance: global, ~20 vistas que usan tablaClass.js. `tablaClassSinBuscador.js` NO estaba afectado (no incluye persistencia).

## 3. Solución aplicada (decisión: no persistir `sortCriteria`)

Dos líneas eliminadas en `guardarEstado()` y `restaurarEstado()`:

```diff
 guardarEstado() {
     try {
         const estado = {
             busqueda: this.busqueda,
             filas: this.filas,
             pagina: this.pagina
-            ,sortCriteria: this.sortCriteria
         };
```

```diff
         if (!isNaN(parseInt(estado.pagina))) this.pagina = parseInt(estado.pagina);
-        if (Array.isArray(estado.sortCriteria)) this.sortCriteria = estado.sortCriteria;
         this._restaurado = true;
```

Comportamiento resultante:

- El ordenamiento vive solo **en memoria**: sigue funcionando mientras se pagina o busca dentro de la página; se resetea al orden por defecto en cada entrada/recarga (iconos de cabecera a DEFAULT).
- `busqueda`, `filas` y `pagina` **siguen persistiendo** (útil al volver desde una ficha al listado).
- Self-healing intacto: si la página restaurada llega vacía, se vuelve a la página 0.
- Limpieza automática: los estados viejos con `sortCriteria` se ignoran al restaurar y se sobrescriben en el primer render — sin migración manual.

## 4. Archivos modificados

| Copia | Archivo |
|---|---|
| Local | `C:\xampp\htdocs\crm_tsat\public\js\tablaClass\tablaClass.js` (guardarEstado :83-92, restaurarEstado :94-105) |
| Producción | `C:\Users\JENNY\Downloads\crm_tsat_produccion\public\js\tablaClass\tablaClass.js` |

Verificación: `node --check` OK en ambos y `fc /B` confirma archivos idénticos byte a byte.

## 5. Notas de despliegue

- Los `import` de tablaClass.js no llevan parámetro de cache-busting → los navegadores pueden servir la versión vieja en caché; Ctrl+F5 al verificar.
- Los usuarios con el orden "atascado" recuperan el orden por defecto en su primera recarga tras el despliegue.

## 6. Tareas derivadas

- 🔲 **crm_inma**: port de la persistencia de estado de tablaClass con el fix ya incluido (nunca persistir `sortCriteria`). Registrado como pendiente en `C:\xampp\htdocs\crm_inma\.opencode\plans\PROGRESS.md`.