# Réglages

Tous les réglages se trouvent dans **Réglages > Fraîcheur du contenu**.

## Types de contenu

Quels types de contenu reçoivent une boîte de fraîcheur, une colonne de liste et une place dans le widget du tableau de bord.

Tous les types de contenu publics du site sont listés, à l'exception des fichiers média. Les articles et les pages sont cochés par défaut.

Désactiver un type de contenu masque la boîte et la colonne, mais conserve les dates de relecture déjà enregistrées. Réactivez-le et l'historique est toujours là.

## Intervalle de relecture par défaut

Combien de temps un contenu reste frais avant de mériter un nouveau regard : 3 mois (90 jours), 6 mois (180 jours) ou 12 mois (365 jours). La valeur par défaut est de 6 mois.

C'est l'intervalle utilisé par tout contenu suivi qui n'a pas le sien. Pour donner un cycle différent à un seul contenu, modifiez **Intervalle de relecture** dans la boîte latérale de ce contenu.

## Réglage par contenu

La liste déroulante **Intervalle de relecture** accepte :

| Choix | Valeur stockée | Effet |
|-------|----------------|-------|
| Utiliser la valeur par défaut | `0` | Suit la valeur du site, donc la modifier déplace aussi ce contenu |
| 3 mois | `90` | Cycle fixe de 90 jours |
| 6 mois | `180` | Cycle fixe de 180 jours |
| 12 mois | `365` | Cycle fixe de 365 jours |
| Ne pas suivre | `-1` | Aucun statut, aucune couleur, exclu du widget |

## Où les données sont stockées

| Clé | Type | Signification |
|-----|------|---------------|
| `wpcity_cf_post_types` | Option | Tableau des types de contenu suivis |
| `wpcity_cf_default_interval` | Option | Intervalle par défaut du site, en jours |
| `wpcity_cf_review_interval` | Post meta | Intervalle propre au contenu, en jours |
| `wpcity_cf_last_reviewed` | Post meta | Date MySQL de la dernière relecture |

La suppression de l'extension retire les quatre, et rien d'autre. L'extension Pro écrit ses propres réglages sous `wpcity_cf_pro_`, qui s'imbrique dans ce préfixe mais appartient à Pro ; ils partent quand vous supprimez Pro.
