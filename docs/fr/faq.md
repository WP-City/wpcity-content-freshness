# FAQ

### Modifier un contenu remet-il sa fraîcheur à zéro ?

Non, et c'est délibéré. Une modification n'est pas une relecture. Seul le bouton **Marquer comme relu**, ou l'écriture directe de `wpcity_cf_last_reviewed`, déplace la date.

La seule exception est un contenu jamais relu. Il n'a aucune date sur laquelle s'appuyer, donc l'extension se rabat sur sa date de dernière modification jusqu'à la première relecture.

### Pourquoi tout est-il vert juste après l'installation ?

Parce que rien n'a encore été relu : chaque contenu est mesuré depuis sa propre date de modification. Un contenu récemment modifié apparaît donc comme frais. Les pages que vous n'avez pas touchées depuis des années sont déjà en orange ou en rouge.

### Puis-je exclure une seule page ?

Oui. Réglez son **Intervalle de relecture** sur "Ne pas suivre". Le statut passe au gris, le contenu disparaît du widget et la colonne affiche un tiret.

### Où sont passés mes contenus quand j'ai trié sur la colonne de fraîcheur ?

Nulle part. Les versions précédentes retiraient de la liste tous les contenus jamais relus dès que vous triiez sur cette colonne. C'est corrigé : le tri conserve désormais la liste complète.

### J'ai changé l'intervalle par défaut et rien ne bouge.

La valeur par défaut ne s'applique qu'aux contenus dont l'intervalle est réglé sur "Utiliser la valeur par défaut". Tout contenu auquel vous avez donné un cycle fixe de 3, 6 ou 12 mois garde ce cycle.

### L'extension envoie-t-elle des données quelque part ?

Non. L'extension gratuite ne fait aucune requête externe. Les webhooks et les résumés par e-mail font partie de l'extension Pro et restent inactifs tant que vous ne les configurez pas.

### Fonctionne-t-elle avec les types de contenu personnalisés ?

Oui, tant que le type de contenu est public. Cochez-le dans **Réglages > Fraîcheur du contenu**, ou ajoutez-le par le code avec le filtre `wpcity_cf_post_types`.

### Qu'advient-il de mes données si je supprime l'extension ?

Les quatre éléments stockés sont supprimés : les deux réglages, ainsi que l'intervalle et la date de relecture de chaque contenu. Rien de plus. Si vous utilisez aussi Pro, ses réglages et sa licence restent en place et partent quand vous supprimez Pro. La désactivation ne supprime rien du tout.

### La date de relecture est-elle en heure du site ou en UTC ?

En heure du site. Elle est écrite avec `current_time( 'mysql' )` et affichée au format de date du site.

### Puis-je marquer plusieurs contenus comme relus d'un coup ?

Pas dans l'extension gratuite. Les actions groupées, l'API REST, le report, les webhooks et les résumés par e-mail font partie de WPCity Content Freshness Pro.
