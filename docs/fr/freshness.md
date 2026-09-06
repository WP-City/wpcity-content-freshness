# Suivi de la fraîcheur

Comment l'extension décide qu'un contenu est frais, bientôt dû ou en retard.

## Le calcul

Pour chaque contenu suivi :

1. Prendre l'**intervalle effectif**. C'est l'intervalle propre au contenu s'il en a un, sinon celui du site.
2. Prendre la **date de référence**. C'est `wpcity_cf_last_reviewed` si le contenu a été relu, sinon sa date de dernière modification.
3. L'échéance est la date de référence plus l'intervalle effectif.
4. Le statut découle du nombre de jours restants avant cette échéance.

| Jours restants | Couleur | Libellé |
|----------------|---------|---------|
| Plus de 30 | Vert | Relu le [date] |
| 30 ou moins | Orange | À relire dans [n] jours |
| 0 ou moins | Rouge | En retard de [n] jours |

Un contenu réglé sur "Ne pas suivre" est gris et ignoré partout.

## Pourquoi les contenus jamais relus utilisent la date de modification

Sans date de référence, tous les contenus du site seraient en retard dès l'activation de l'extension, et le widget serait inutile le premier jour. Se rabattre sur la date de modification signifie qu'une page modifiée la semaine dernière est considérée comme fraîche, et qu'une page intouchée depuis trois ans est en retard, ce qui est la réponse que vous cherchiez vraiment.

Marquer un contenu comme relu remplace ce repli par une vraie date de relecture. À partir de là, modifier le contenu ne remet plus sa fraîcheur à zéro, et c'est bien l'intention : une modification n'est pas une relecture.

## Marquer comme relu

Le bouton **Marquer comme relu** écrit l'heure du site dans `wpcity_cf_last_reviewed` via AJAX et met la ligne de statut à jour sur place. La requête est protégée par un nonce et exige la capacité `edit_post` sur ce contenu.

## Trier la liste

La colonne **Fraîcheur du contenu** est triable. Le tri se fait sur la date de relecture et conserve dans la liste les contenus jamais relus, si bien qu'un clic sur l'en-tête ne vous cache jamais de contenu.

## Le widget du tableau de bord

Le widget compte tous les contenus suivis et publiés qui sont orange ou rouges, affiche les cinq plus urgents et renvoie vers la liste complète. Les contenus réglés sur "Ne pas suivre" n'apparaissent jamais.
