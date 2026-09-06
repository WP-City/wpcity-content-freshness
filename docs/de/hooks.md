# Hooks & Filter

Jeder Hook in WPCity Content Freshness folgt der Namenskonvention `wpcity_cf_*`.

## Filter

### `wpcity_cf_post_types`

Steuere, welche Inhaltstypen verfolgt werden.

```php
add_filter( 'wpcity_cf_post_types', function ( array $post_types ): array {
    $post_types[] = 'product';
    return $post_types;
} );
```

**Parameter:**
- `$post_types` *(array)*: Slugs der Inhaltstypen. Standard: was in den Einstellungen angehakt ist, mit Rückfall auf `['post', 'page']`.

Der Filter läuft auf `init` und auf `add_meta_boxes`, nie beim Konstruieren, damit ein später geladenes Plugin noch etwas beisteuern kann.

---

### `wpcity_cf_default_interval`

Überschreibe das Standard-Prüfintervall der Website, in Tagen.

```php
add_filter( 'wpcity_cf_default_interval', function ( int $days ): int {
    return 120;
} );
```

**Parameter:**
- `$days` *(int)*: das Intervall in Tagen. Standard: der Wert aus den Einstellungen, mit Rückfall auf `180`.

---

### `wpcity_cf_allowed_intervals`

Die Intervalle, die ein Beitrag bekommen darf. Werte außerhalb dieser Liste werden beim Speichern abgelehnt.

```php
add_filter( 'wpcity_cf_allowed_intervals', function ( array $allowed ): array {
    $allowed[] = 30;
    return $allowed;
} );
```

**Parameter:**
- `$allowed` *(array)*: erlaubte Werte in Tagen. Standard: `[-1, 0, 90, 180, 365]`, wobei `-1` "nicht verfolgen" bedeutet und `0` "Website-Standard verwenden".

Einen Wert hinzuzufügen setzt ihn nicht in das Auswahlfeld; es erlaubt, dass ein Wert von anderswo ankommt, etwa aus einer Sammelaktion oder der REST-API.

---

### `wpcity_cf_is_stale`

Entscheide selbst, ob ein Beitrag als überfällig gilt.

```php
add_filter( 'wpcity_cf_is_stale', function ( bool $is_stale, int $post_id, int $interval, string $last_reviewed ): bool {
    // Die Startseite nie als überfällig markieren.
    if ( (int) get_option( 'page_on_front' ) === $post_id ) {
        return false;
    }
    return $is_stale;
}, 10, 4 );
```

**Parameter:**
- `$is_stale` *(bool)*: das Urteil des Plugins, true wenn die Frist verstrichen ist.
- `$post_id` *(int)*: der bewertete Beitrag.
- `$interval` *(int)*: das effektive Intervall in Tagen.
- `$last_reviewed` *(string)*: das Bezugsdatum im MySQL-Datetime-Format.

---

### `wpcity_cf_can_mark_reviewed`

Schränke die Schaltfläche Als geprüft markieren ein. Die Pro-Erweiterung erzwingt darüber eine eigene Capability.

```php
add_filter( 'wpcity_cf_can_mark_reviewed', function ( bool $can, int $post_id ): bool {
    return current_user_can( 'editor' ) ? $can : false;
}, 10, 2 );
```

**Parameter:**
- `$can` *(bool)*: ob die aktuelle Benutzerin oder der aktuelle Benutzer diesen Beitrag als geprüft markieren darf. Standard: `true`.
- `$post_id` *(int)*: der betroffene Beitrag.

Das kommt zusätzlich zur eingebauten `edit_post`-Prüfung, nie an ihrer Stelle.

---

### `wpcity_cf_metabox_label`

Ändere den Titel der Seitenleisten-Box.

```php
add_filter( 'wpcity_cf_metabox_label', function ( string $title ): string {
    return 'Prüfzyklus';
} );
```

**Parameter:**
- `$title` *(string)*: der Titel der Box. Standard: die Übersetzung von "Content Freshness".

## Actions

### `wpcity_cf_loaded`

Wird ausgelöst, sobald alle Ingredients initialisiert sind. Das ist der sichere Ort, um eigene Ergänzungen zu registrieren.

```php
add_action( 'wpcity_cf_loaded', function (): void {
    // Das Plugin ist bereit.
} );
```

---

### `wpcity_cf_activated`

Wird am Ende der Aktivierung ausgelöst, nachdem die Standardoptionen geschrieben wurden.

---

### `wpcity_cf_deactivated`

Wird am Ende der Deaktivierung ausgelöst. Es werden dabei keine Plugindaten entfernt; das passiert bei der Deinstallation.

---

### `wpcity_cf_marked_reviewed`

Wird direkt nach dem Markieren eines Beitrags als geprüft ausgelöst.

```php
add_action( 'wpcity_cf_marked_reviewed', function ( int $post_id, int $user_id ): void {
    error_log( "Beitrag {$post_id} geprüft von Benutzer {$user_id}" );
}, 10, 2 );
```

**Parameter:**
- `$post_id` *(int)*: der geprüfte Beitrag.
- `$user_id` *(int)*: wer ihn geprüft hat.

---

### `wpcity_cf_settings_after_postboxes`

Wird im Einstellungsformular ausgelöst, nach der eigenen Einstellungs-Box und vor der Absenden-Schaltfläche. Füge hier deine eigene Postbox ein und sie wird vom selben Formular gespeichert.

```php
add_action( 'wpcity_cf_settings_after_postboxes', function (): void {
    echo '<div class="postbox"><h3><span>Mein Bereich</span></h3><div class="inside">...</div></div>';
} );
```

## Post Meta

Zwei Schlüssel, beide gewöhnliche Post Meta, die du direkt lesen und schreiben kannst.

| Schlüssel | Typ | Bedeutung |
|-----------|-----|-----------|
| `wpcity_cf_review_interval` | int | Intervall in Tagen. `0` verwendet den Website-Standard, `-1` schaltet das Verfolgen ab. |
| `wpcity_cf_last_reviewed` | string | MySQL-Datetime der letzten Prüfung. Fehlt der Wert, wurde nie geprüft. |
