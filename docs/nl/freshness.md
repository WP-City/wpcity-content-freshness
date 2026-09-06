# Actualiteit bijhouden

Hoe de plugin bepaalt of een bericht actueel, bijna aan de beurt of over tijd is.

## De berekening

Voor elk bijgehouden bericht:

1. Neem het **effectieve interval**. Dat is het eigen interval van het bericht als dat er is, anders de sitestandaard.
2. Neem de **peildatum**. Dat is `wpcity_cf_last_reviewed` als het bericht is beoordeeld, anders de laatste wijzigingsdatum van het bericht.
3. De deadline is de peildatum plus het effectieve interval.
4. De status volgt uit het aantal dagen tot die deadline.

| Dagen over | Kleur | Label |
|------------|-------|-------|
| Meer dan 30 | Groen | Beoordeeld op [datum] |
| 30 of minder | Oranje | Over [n] dagen aan de beurt |
| 0 of minder | Rood | [n] dagen over tijd |

Een bericht met het interval "Niet bijhouden" is grijs en wordt overal overgeslagen.

## Waarom nooit beoordeelde berichten de wijzigingsdatum gebruiken

Zonder peildatum zou elk bericht op de site over tijd zijn op het moment dat je de plugin activeert, en dan is de dashboardwidget op dag één waardeloos. Terugvallen op de wijzigingsdatum betekent dat een pagina die vorige week is bewerkt als actueel telt, en een pagina die drie jaar onaangeroerd is als achterstallig. Dat is het antwoord dat je eigenlijk zocht.

Een bericht als beoordeeld markeren vervangt die terugval door een echte beoordelingsdatum. Vanaf dat moment zet bewerken de actualiteit niet meer terug, en dat is precies de bedoeling: een bewerking is geen beoordeling.

## Markeren als beoordeeld

De knop **Markeer als beoordeeld** schrijft de huidige sitetijd via AJAX naar `wpcity_cf_last_reviewed` en werkt de statusregel ter plekke bij. De aanvraag is beveiligd met een nonce en vereist `edit_post` op dat bericht.

## Sorteren in de lijst

De kolom **Actualiteit content** is sorteerbaar. Sorteren gaat op beoordelingsdatum en houdt berichten die nooit zijn beoordeeld in de lijst, dus een klik op de kop verbergt nooit content voor je.

## De dashboardwidget

De widget telt elk bijgehouden, gepubliceerd bericht dat oranje of rood is, toont de vijf meest urgente en linkt naar de volledige lijst. Berichten op "Niet bijhouden" verschijnen nooit.
