# Hooks & Filters

Every hook in WPCity Content Freshness follows the `wpcity_cf_*` naming convention.

## Filters

### `wpcity_cf_post_types`

Control which post types are tracked.

```php
add_filter( 'wpcity_cf_post_types', function ( array $post_types ): array {
    $post_types[] = 'product';
    return $post_types;
} );
```

**Parameters:**
- `$post_types` *(array)*: post type slugs. Default: whatever is ticked in the settings, falling back to `['post', 'page']`.

The filter is applied on `init` and on `add_meta_boxes`, never at construction time, so a plugin that loads after this one can still add to it.

---

### `wpcity_cf_default_interval`

Override the site default review interval, in days.

```php
add_filter( 'wpcity_cf_default_interval', function ( int $days ): int {
    return 120;
} );
```

**Parameters:**
- `$days` *(int)*: the interval in days. Default: the settings value, falling back to `180`.

---

### `wpcity_cf_allowed_intervals`

The intervals a post may be set to. Values outside this list are rejected when the post is saved.

```php
add_filter( 'wpcity_cf_allowed_intervals', function ( array $allowed ): array {
    $allowed[] = 30;
    return $allowed;
} );
```

**Parameters:**
- `$allowed` *(array)*: allowed values in days. Default: `[-1, 0, 90, 180, 365]`, where `-1` means "don't track" and `0` means "use the site default".

Adding a value here does not add it to the dropdown; it lets a value arrive from somewhere else, such as a bulk action or the REST API.

---

### `wpcity_cf_is_stale`

Decide for yourself whether a post counts as overdue.

```php
add_filter( 'wpcity_cf_is_stale', function ( bool $is_stale, int $post_id, int $interval, string $last_reviewed ): bool {
    // Never mark the front page as overdue.
    if ( (int) get_option( 'page_on_front' ) === $post_id ) {
        return false;
    }
    return $is_stale;
}, 10, 4 );
```

**Parameters:**
- `$is_stale` *(bool)*: the plugin's own verdict, true when the deadline has passed.
- `$post_id` *(int)*: the post being judged.
- `$interval` *(int)*: the effective interval in days.
- `$last_reviewed` *(string)*: the baseline date, MySQL datetime format.

---

### `wpcity_cf_can_mark_reviewed`

Gate the Mark as Reviewed button. The Pro add-on uses this to enforce a custom capability.

```php
add_filter( 'wpcity_cf_can_mark_reviewed', function ( bool $can, int $post_id ): bool {
    return current_user_can( 'editor' ) ? $can : false;
}, 10, 2 );
```

**Parameters:**
- `$can` *(bool)*: whether the current user may mark this post as reviewed. Default: `true`.
- `$post_id` *(int)*: the post in question.

This runs on top of the built-in `edit_post` capability check, never instead of it.

---

### `wpcity_cf_metabox_label`

Change the title of the sidebar meta box.

```php
add_filter( 'wpcity_cf_metabox_label', function ( string $title ): string {
    return 'Review cycle';
} );
```

**Parameters:**
- `$title` *(string)*: the meta box title. Default: the translated "Content Freshness".

## Actions

### `wpcity_cf_loaded`

Fires once every ingredient has been initialised. This is the safe place to register your own additions.

```php
add_action( 'wpcity_cf_loaded', function (): void {
    // The plugin is ready.
} );
```

---

### `wpcity_cf_activated`

Fires at the end of activation, after the default options have been written.

---

### `wpcity_cf_deactivated`

Fires at the end of deactivation. No plugin data is removed at this point; that happens on uninstall.

---

### `wpcity_cf_marked_reviewed`

Fires straight after a post has been marked as reviewed.

```php
add_action( 'wpcity_cf_marked_reviewed', function ( int $post_id, int $user_id ): void {
    error_log( "Post {$post_id} reviewed by user {$user_id}" );
}, 10, 2 );
```

**Parameters:**
- `$post_id` *(int)*: the post that was reviewed.
- `$user_id` *(int)*: the user who reviewed it.

---

### `wpcity_cf_settings_after_postboxes`

Fires inside the settings form, after the plugin's own settings box and before the submit button. Add your own postbox here and it is saved by the same form.

```php
add_action( 'wpcity_cf_settings_after_postboxes', function (): void {
    echo '<div class="postbox"><h3><span>My section</span></h3><div class="inside">...</div></div>';
} );
```

## Post meta

Two keys, both plain post meta, safe to read and write directly.

| Key | Type | Meaning |
|-----|------|---------|
| `wpcity_cf_review_interval` | int | Interval in days. `0` uses the site default, `-1` disables tracking. |
| `wpcity_cf_last_reviewed` | string | MySQL datetime of the last review. Absent means never reviewed. |
