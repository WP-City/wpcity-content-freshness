# Hooks y filtros

Todos los hooks de WPCity Content Freshness siguen la convención de nombres `wpcity_cf_*`.

## Filtros

### `wpcity_cf_post_types`

Controla qué tipos de contenido se siguen.

```php
add_filter( 'wpcity_cf_post_types', function ( array $post_types ): array {
    $post_types[] = 'product';
    return $post_types;
} );
```

**Parámetros:**
- `$post_types` *(array)*: slugs de tipos de contenido. Por defecto: lo que esté marcado en los ajustes, con respaldo en `['post', 'page']`.

El filtro se aplica en `init` y en `add_meta_boxes`, nunca durante la construcción, para que un plugin que cargue más tarde todavía pueda añadir algo.

---

### `wpcity_cf_default_interval`

Sustituye el intervalo de revisión predeterminado del sitio, en días.

```php
add_filter( 'wpcity_cf_default_interval', function ( int $days ): int {
    return 120;
} );
```

**Parámetros:**
- `$days` *(int)*: el intervalo en días. Por defecto: el valor de los ajustes, con respaldo en `180`.

---

### `wpcity_cf_allowed_intervals`

Los intervalos que puede recibir una entrada. Los valores fuera de esta lista se rechazan al guardar.

```php
add_filter( 'wpcity_cf_allowed_intervals', function ( array $allowed ): array {
    $allowed[] = 30;
    return $allowed;
} );
```

**Parámetros:**
- `$allowed` *(array)*: valores permitidos, en días. Por defecto: `[-1, 0, 90, 180, 365]`, donde `-1` significa "no hacer seguimiento" y `0` "usar el valor del sitio".

Añadir un valor no lo añade al desplegable; permite que llegue un valor desde otro sitio, por ejemplo de una acción en lote o de la API REST.

---

### `wpcity_cf_is_stale`

Decide por tu cuenta si una entrada cuenta como atrasada.

```php
add_filter( 'wpcity_cf_is_stale', function ( bool $is_stale, int $post_id, int $interval, string $last_reviewed ): bool {
    // No marcar nunca la portada como atrasada.
    if ( (int) get_option( 'page_on_front' ) === $post_id ) {
        return false;
    }
    return $is_stale;
}, 10, 4 );
```

**Parámetros:**
- `$is_stale` *(bool)*: el veredicto del propio plugin, true cuando ha pasado la fecha límite.
- `$post_id` *(int)*: la entrada evaluada.
- `$interval` *(int)*: el intervalo efectivo, en días.
- `$last_reviewed` *(string)*: la fecha de referencia, en formato datetime de MySQL.

---

### `wpcity_cf_can_mark_reviewed`

Condiciona el botón Marcar como revisado. La extensión Pro lo usa para exigir una capacidad propia.

```php
add_filter( 'wpcity_cf_can_mark_reviewed', function ( bool $can, int $post_id ): bool {
    return current_user_can( 'editor' ) ? $can : false;
}, 10, 2 );
```

**Parámetros:**
- `$can` *(bool)*: si el usuario actual puede marcar esta entrada como revisada. Por defecto: `true`.
- `$post_id` *(int)*: la entrada en cuestión.

Esto se suma a la comprobación integrada de `edit_post`, nunca la sustituye.

---

### `wpcity_cf_metabox_label`

Cambia el título de la caja lateral.

```php
add_filter( 'wpcity_cf_metabox_label', function ( string $title ): string {
    return 'Ciclo de revisión';
} );
```

**Parámetros:**
- `$title` *(string)*: el título de la caja. Por defecto: la traducción de "Content Freshness".

## Acciones

### `wpcity_cf_loaded`

Se dispara cuando todos los ingredientes están inicializados. Es el sitio seguro para registrar tus propios añadidos.

```php
add_action( 'wpcity_cf_loaded', function (): void {
    // El plugin está listo.
} );
```

---

### `wpcity_cf_activated`

Se dispara al final de la activación, después de escribir las opciones predeterminadas.

---

### `wpcity_cf_deactivated`

Se dispara al final de la desactivación. En ese momento no se borra ningún dato del plugin; eso ocurre al desinstalarlo.

---

### `wpcity_cf_marked_reviewed`

Se dispara justo después de marcar una entrada como revisada.

```php
add_action( 'wpcity_cf_marked_reviewed', function ( int $post_id, int $user_id ): void {
    error_log( "Entrada {$post_id} revisada por el usuario {$user_id}" );
}, 10, 2 );
```

**Parámetros:**
- `$post_id` *(int)*: la entrada revisada.
- `$user_id` *(int)*: quién la revisó.

---

### `wpcity_cf_settings_after_postboxes`

Se dispara dentro del formulario de ajustes, después de la caja propia del plugin y antes del botón de envío. Añade ahí tu propia caja y se guardará con el mismo formulario.

```php
add_action( 'wpcity_cf_settings_after_postboxes', function (): void {
    echo '<div class="postbox"><h3><span>Mi sección</span></h3><div class="inside">...</div></div>';
} );
```

## Post meta

Dos claves, ambas post meta normales, que puedes leer y escribir directamente.

| Clave | Tipo | Significado |
|-------|------|-------------|
| `wpcity_cf_review_interval` | int | Intervalo en días. `0` usa el valor del sitio, `-1` desactiva el seguimiento. |
| `wpcity_cf_last_reviewed` | string | Fecha MySQL de la última revisión. Si falta, nunca se ha revisado. |
