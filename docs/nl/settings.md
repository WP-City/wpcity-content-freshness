# Instellingen

Alle instellingen staan onder **Instellingen > Actualiteit content**.

## Berichttypes

Welke berichttypes een meta box voor actualiteit, een lijstkolom en een plek in de dashboardwidget krijgen.

Elk openbaar berichttype op de site staat in de lijst, behalve bijlagen. Berichten en pagina's staan standaard aangevinkt.

Een berichttype uitzetten verbergt de meta box en de kolom, maar de al opgeslagen beoordelingsdata blijven bewaard. Zet je het weer aan, dan staat de historie er nog.

## Standaard beoordelingsinterval

Hoe lang content actueel blijft voordat er opnieuw naar gekeken moet worden: 3 maanden (90 dagen), 6 maanden (180 dagen) of 12 maanden (365 dagen). De standaard is 6 maanden.

Dit is het interval dat geldt voor elk bijgehouden bericht zonder eigen interval. Wil je één bericht een andere cyclus geven, pas dan **Beoordelingsinterval** aan in het zijbalkblok van dat bericht.

## Afwijking per bericht

De dropdown **Beoordelingsinterval** in de zijbalk accepteert:

| Keuze | Opgeslagen waarde | Effect |
|-------|-------------------|--------|
| Standaard gebruiken | `0` | Volgt de sitestandaard, dus een wijziging daar verplaatst dit bericht mee |
| 3 maanden | `90` | Vaste cyclus van 90 dagen |
| 6 maanden | `180` | Vaste cyclus van 180 dagen |
| 12 maanden | `365` | Vaste cyclus van 365 dagen |
| Niet bijhouden | `-1` | Geen status, geen kleur, buiten de dashboardwidget |

## Waar de data staat

| Sleutel | Type | Betekenis |
|---------|------|-----------|
| `wpcity_cf_post_types` | Optie | Array met bijgehouden berichttype-slugs |
| `wpcity_cf_default_interval` | Optie | Standaardinterval van de site in dagen |
| `wpcity_cf_review_interval` | Post meta | Interval per bericht in dagen |
| `wpcity_cf_last_reviewed` | Post meta | MySQL-datetime van de laatste beoordeling |

Bij het verwijderen van de plugin gaan alle vier weg, samen met elke andere sleutel in de `wpcity_cf_`-namespace.
