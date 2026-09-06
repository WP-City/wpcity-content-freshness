# Prise en main

WPCity Content Freshness enregistre la date de la dernière relecture de chaque contenu et montre d'un coup d'oeil quelles pages ont vieilli.

## Installation

1. Envoyez le dossier `wpcity-content-freshness` dans `/wp-content/plugins/`
2. Activez l'extension depuis le menu **Extensions** de WordPress
3. Rendez-vous dans **Réglages > Fraîcheur du contenu** pour choisir les types de contenu à suivre

## Premiers pas

### 1. Choisissez vos types de contenu

Après l'activation, ouvrez **Réglages > Fraîcheur du contenu**. Vous y trouverez tous les types de contenu publics du site. Cochez ceux qui ont besoin d'un cycle de relecture. Les articles et les pages sont activés par défaut.

### 2. Définissez un intervalle de relecture par défaut

Sur le même écran, choisissez la fréquence de relecture : 3, 6 ou 12 mois. Cet intervalle s'applique à tous les contenus suivis, sauf si vous le remplacez sur le contenu lui-même.

### 3. Relisez un contenu

Ouvrez un article ou une page suivie. La boîte **Fraîcheur du contenu** se trouve dans la colonne latérale, sous le bloc Publier, et affiche trois choses :

- **Intervalle de relecture** : utiliser la valeur par défaut du site, choisir 3, 6 ou 12 mois, ou choisir "Ne pas suivre" pour exclure entièrement ce contenu
- **Dernière relecture** : la date à laquelle le contenu a été confirmé à jour pour la dernière fois
- Une ligne de statut colorée qui indique où en est ce contenu

Cliquez sur **Marquer comme relu** et la date est enregistrée immédiatement, sans recharger la page.

### 4. Lisez les couleurs

| Couleur | Signification |
|---------|---------------|
| Vert | Relu récemment, rien à faire |
| Orange | À relire dans les 30 jours |
| Rouge | En retard, l'intervalle est dépassé |
| Gris | Non suivi, vous avez choisi "Ne pas suivre" |

La même pastille apparaît dans la colonne **Fraîcheur du contenu** de chaque liste, à côté du titre. Cliquez sur l'en-tête de colonne pour trier par date de relecture ; les contenus jamais relus restent dans la liste.

### 5. Surveillez le tableau de bord

Le widget **Fraîcheur du contenu** liste les cinq contenus les plus urgents, avec un lien vers la liste complète. Quand rien n'est en retard, il le dit simplement.

## Ce qui compte comme "dernière relecture"

Un contenu qui n'a jamais été marqué comme relu se rabat sur sa propre date de dernière modification. Ainsi une page tout juste rédigée n'apparaît pas en retard le jour où vous installez l'extension, et le compteur veut dire quelque chose dès le premier jour.

## Pour aller plus loin

- [Réglages](settings.md) : chaque option expliquée
- [Suivi de la fraîcheur](freshness.md) : comment le statut est calculé
- [Hooks et filtres](hooks.md) : étendre l'extension par le code
- [FAQ](faq.md) : les questions courantes
