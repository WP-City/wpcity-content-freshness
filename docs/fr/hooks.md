# Hooks & filtres

Tous les hooks de WPCity Content Freshness suivent la convention de nommage `wpcity_cf_*`.

## Filtres

### `wpcity_cf_post_types`

Contrôlez quels types de contenu sont suivis.

```php
add_filter( 'wpcity_cf_post_types', function ( array $post_types ): array {
    $post_types[] = 'product';
    return $post_types;
} );
```

**Paramètres :**
- `$post_types` *(array)* : identifiants des types de contenu. Par défaut : ce qui est coché dans les réglages, avec repli sur `['post', 'page']`.

Le filtre est appliqué sur `init` et sur `add_meta_boxes`, jamais à la construction, pour qu'une extension chargée plus tard puisse encore y ajouter quelque chose.

---

### `wpcity_cf_default_interval`

Remplacez l'intervalle de relecture par défaut du site, en jours.

```php
add_filter( 'wpcity_cf_default_interval', function ( int $days ): int {
    return 120;
} );
```

**Paramètres :**
- `$days` *(int)* : l'intervalle en jours. Par défaut : la valeur des réglages, avec repli sur `180`.

---

### `wpcity_cf_allowed_intervals`

Les intervalles qu'un contenu peut recevoir. Les valeurs hors de cette liste sont refusées à l'enregistrement.

```php
add_filter( 'wpcity_cf_allowed_intervals', function ( array $allowed ): array {
    $allowed[] = 30;
    return $allowed;
} );
```

**Paramètres :**
- `$allowed` *(array)* : valeurs autorisées, en jours. Par défaut : `[-1, 0, 90, 180, 365]`, où `-1` signifie "ne pas suivre" et `0` "utiliser la valeur du site".

Ajouter une valeur ne l'ajoute pas à la liste déroulante ; cela permet à une valeur venue d'ailleurs d'être acceptée, par exemple d'une action groupée ou de l'API REST.

---

### `wpcity_cf_is_stale`

Décidez vous-même si un contenu compte comme en retard.

```php
add_filter( 'wpcity_cf_is_stale', function ( bool $is_stale, int $post_id, int $interval, string $last_reviewed ): bool {
    // Ne jamais marquer la page d'accueil comme en retard.
    if ( (int) get_option( 'page_on_front' ) === $post_id ) {
        return false;
    }
    return $is_stale;
}, 10, 4 );
```

**Paramètres :**
- `$is_stale` *(bool)* : le verdict de l'extension, vrai quand l'échéance est passée.
- `$post_id` *(int)* : le contenu évalué.
- `$interval` *(int)* : l'intervalle effectif, en jours.
- `$last_reviewed` *(string)* : la date de référence, au format datetime MySQL.

---

### `wpcity_cf_can_mark_reviewed`

Conditionnez le bouton Marquer comme relu. L'extension Pro s'en sert pour imposer une capacité dédiée.

```php
add_filter( 'wpcity_cf_can_mark_reviewed', function ( bool $can, int $post_id ): bool {
    return current_user_can( 'editor' ) ? $can : false;
}, 10, 2 );
```

**Paramètres :**
- `$can` *(bool)* : si l'utilisateur courant peut marquer ce contenu comme relu. Par défaut : `true`.
- `$post_id` *(int)* : le contenu concerné.

Cela s'ajoute au contrôle `edit_post` intégré, jamais à sa place.

---

### `wpcity_cf_metabox_label`

Changez le titre de la boîte latérale.

```php
add_filter( 'wpcity_cf_metabox_label', function ( string $title ): string {
    return 'Cycle de relecture';
} );
```

**Paramètres :**
- `$title` *(string)* : le titre de la boîte. Par défaut : la traduction de "Content Freshness".

## Actions

### `wpcity_cf_loaded`

Se déclenche une fois tous les ingrédients initialisés. C'est l'endroit sûr pour enregistrer vos propres ajouts.

```php
add_action( 'wpcity_cf_loaded', function (): void {
    // L'extension est prête.
} );
```

---

### `wpcity_cf_activated`

Se déclenche à la fin de l'activation, après l'écriture des options par défaut.

---

### `wpcity_cf_deactivated`

Se déclenche à la fin de la désactivation. Aucune donnée n'est supprimée à ce moment ; cela se produit à la désinstallation.

---

### `wpcity_cf_marked_reviewed`

Se déclenche juste après qu'un contenu a été marqué comme relu.

```php
add_action( 'wpcity_cf_marked_reviewed', function ( int $post_id, int $user_id ): void {
    error_log( "Contenu {$post_id} relu par l'utilisateur {$user_id}" );
}, 10, 2 );
```

**Paramètres :**
- `$post_id` *(int)* : le contenu relu.
- `$user_id` *(int)* : l'utilisateur qui l'a relu.

---

### `wpcity_cf_settings_after_postboxes`

Se déclenche dans le formulaire de réglages, après la boîte de l'extension et avant le bouton d'envoi. Ajoutez-y votre propre boîte et elle sera enregistrée par le même formulaire.

```php
add_action( 'wpcity_cf_settings_after_postboxes', function (): void {
    echo '<div class="postbox"><h3><span>Ma section</span></h3><div class="inside">...</div></div>';
} );
```

## Post meta

Deux clés, de simples post meta, que vous pouvez lire et écrire directement.

| Clé | Type | Signification |
|-----|------|---------------|
| `wpcity_cf_review_interval` | int | Intervalle en jours. `0` utilise la valeur du site, `-1` désactive le suivi. |
| `wpcity_cf_last_reviewed` | string | Date MySQL de la dernière relecture. Absente signifie jamais relu. |
