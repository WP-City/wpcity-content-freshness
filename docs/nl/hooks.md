# Hooks & filters

Elke hook in WPCity Content Freshness volgt de naamgeving `wpcity_cf_*`.

## Filters

### `wpcity_cf_post_types`

Bepaal welke berichttypes worden bijgehouden.

```php
add_filter( 'wpcity_cf_post_types', function ( array $post_types ): array {
    $post_types[] = 'product';
    return $post_types;
} );
```

**Parameters:**
- `$post_types` *(array)*: berichttype-slugs. Standaard: wat is aangevinkt in de instellingen, met terugval op `['post', 'page']`.

Het filter draait op `init` en op `add_meta_boxes`, nooit tijdens constructie, zodat een plugin die later laadt er nog aan kan toevoegen.

---

### `wpcity_cf_default_interval`

Overschrijf het standaard beoordelingsinterval van de site, in dagen.

```php
add_filter( 'wpcity_cf_default_interval', function ( int $days ): int {
    return 120;
} );
```

**Parameters:**
- `$days` *(int)*: het interval in dagen. Standaard: de waarde uit de instellingen, met terugval op `180`.

---

### `wpcity_cf_allowed_intervals`

De intervallen die een bericht mag krijgen. Waarden buiten deze lijst worden geweigerd bij het opslaan van het bericht.

```php
add_filter( 'wpcity_cf_allowed_intervals', function ( array $allowed ): array {
    $allowed[] = 30;
    return $allowed;
} );
```

**Parameters:**
- `$allowed` *(array)*: toegestane waarden in dagen. Standaard: `[-1, 0, 90, 180, 365]`, waarbij `-1` "niet bijhouden" betekent en `0` "gebruik de sitestandaard".

Een waarde toevoegen zet hem niet in de dropdown; het zorgt ervoor dat een waarde van elders mag binnenkomen, bijvoorbeeld uit een bulkactie of de REST API.

---

### `wpcity_cf_is_stale`

Bepaal zelf of een bericht als over tijd telt.

```php
add_filter( 'wpcity_cf_is_stale', function ( bool $is_stale, int $post_id, int $interval, string $last_reviewed ): bool {
    // Markeer de homepage nooit als over tijd.
    if ( (int) get_option( 'page_on_front' ) === $post_id ) {
        return false;
    }
    return $is_stale;
}, 10, 4 );
```

**Parameters:**
- `$is_stale` *(bool)*: het oordeel van de plugin zelf, true als de deadline is verstreken.
- `$post_id` *(int)*: het bericht dat wordt beoordeeld.
- `$interval` *(int)*: het effectieve interval in dagen.
- `$last_reviewed` *(string)*: de peildatum, in MySQL-datetime formaat.

---

### `wpcity_cf_can_mark_reviewed`

Beperk de knop Markeer als beoordeeld. De Pro-uitbreiding gebruikt dit om een eigen capability af te dwingen.

```php
add_filter( 'wpcity_cf_can_mark_reviewed', function ( bool $can, int $post_id ): bool {
    return current_user_can( 'editor' ) ? $can : false;
}, 10, 2 );
```

**Parameters:**
- `$can` *(bool)*: of de huidige gebruiker dit bericht als beoordeeld mag markeren. Standaard: `true`.
- `$post_id` *(int)*: het betreffende bericht.

Dit komt bovenop de ingebouwde `edit_post`-controle, nooit in plaats daarvan.

---

### `wpcity_cf_metabox_label`

Wijzig de titel van het zijbalkblok.

```php
add_filter( 'wpcity_cf_metabox_label', function ( string $title ): string {
    return 'Beoordelingscyclus';
} );
```

**Parameters:**
- `$title` *(string)*: de titel van de meta box. Standaard: de vertaling van "Content Freshness".

## Actions

### `wpcity_cf_loaded`

Vuurt zodra elk ingredient is geïnitialiseerd. Dit is de veilige plek om je eigen toevoegingen te registreren.

```php
add_action( 'wpcity_cf_loaded', function (): void {
    // De plugin is klaar.
} );
```

---

### `wpcity_cf_activated`

Vuurt aan het einde van de activatie, nadat de standaardopties zijn weggeschreven.

---

### `wpcity_cf_deactivated`

Vuurt aan het einde van de deactivatie. Er wordt op dat moment geen plugindata verwijderd; dat gebeurt bij deinstallatie.

---

### `wpcity_cf_marked_reviewed`

Vuurt direct nadat een bericht als beoordeeld is gemarkeerd.

```php
add_action( 'wpcity_cf_marked_reviewed', function ( int $post_id, int $user_id ): void {
    error_log( "Bericht {$post_id} beoordeeld door gebruiker {$user_id}" );
}, 10, 2 );
```

**Parameters:**
- `$post_id` *(int)*: het beoordeelde bericht.
- `$user_id` *(int)*: de gebruiker die het beoordeelde.

---

### `wpcity_cf_settings_after_postboxes`

Vuurt binnen het instellingenformulier, na het eigen instellingenblok en vóór de verzendknop. Voeg hier je eigen postbox toe en die wordt door hetzelfde formulier opgeslagen.

```php
add_action( 'wpcity_cf_settings_after_postboxes', function (): void {
    echo '<div class="postbox"><h3><span>Mijn sectie</span></h3><div class="inside">...</div></div>';
} );
```

## Post meta

Twee sleutels, allebei gewone post meta, veilig om direct te lezen en te schrijven.

| Sleutel | Type | Betekenis |
|---------|------|-----------|
| `wpcity_cf_review_interval` | int | Interval in dagen. `0` gebruikt de sitestandaard, `-1` schakelt bijhouden uit. |
| `wpcity_cf_last_reviewed` | string | MySQL-datetime van de laatste beoordeling. Afwezig betekent nooit beoordeeld. |
