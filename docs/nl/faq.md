# Veelgestelde vragen

### Zet het bewerken van een bericht de actualiteit terug?

Nee, en dat is met opzet. Een bewerking is geen beoordeling. Alleen de knop **Markeer als beoordeeld**, of zelf `wpcity_cf_last_reviewed` schrijven, verzet de datum.

De enige uitzondering is een bericht dat nog nooit is beoordeeld. Dat heeft geen datum om mee te rekenen, dus valt de plugin terug op de laatste wijzigingsdatum tot je het voor het eerst beoordeelt.

### Waarom staat alles op groen vlak na installatie?

Omdat er nog niets is beoordeeld, wordt elk bericht gemeten vanaf zijn eigen laatste wijzigingsdatum. Recent bewerkte content telt dus als actueel. Pagina's waar je jaren niet aan gezeten hebt staan meteen op oranje of rood.

### Kan ik één pagina buiten beschouwing laten?

Ja. Zet het **Beoordelingsinterval** op "Niet bijhouden". De status wordt grijs, het bericht verdwijnt uit de dashboardwidget en de kolom toont een streepje.

### Waar bleven mijn berichten toen ik op de actualiteitskolom sorteerde?

Nergens. Oudere versies lieten elk nooit beoordeeld bericht uit de lijst vallen zodra je op die kolom sorteerde. Dat is opgelost: sorteren houdt de volledige lijst intact.

### Ik heb het standaardinterval gewijzigd en er gebeurt niets.

De standaard geldt alleen voor berichten waarvan het eigen interval op "Standaard gebruiken" staat. Elk bericht waar je een vaste cyclus van 3, 6 of 12 maanden aan gaf, houdt die cyclus.

### Stuurt de plugin iets naar buiten?

Nee. De gratis plugin doet geen enkele externe aanvraag. Webhooks en e-maildigests horen bij de Pro-uitbreiding en staan uit tot je ze instelt.

### Werkt het met custom post types?

Ja, zolang het berichttype openbaar is. Vink het aan onder **Instellingen > Actualiteit content**, of voeg het in code toe via het filter `wpcity_cf_post_types`.

### Wat gebeurt er met mijn data als ik de plugin verwijder?

De vier dingen die de plugin opslaat worden verwijderd: de twee instellingen, en het beoordelingsinterval en de beoordelingsdatum op elk bericht. Verder niets. Draai je ook Pro, dan blijven diens instellingen en licentie staan en gaan die weg als je Pro verwijdert. Deactiveren verwijdert helemaal niets.

### Staat de beoordelingsdatum in sitetijd of UTC?

Sitetijd. Hij wordt geschreven met `current_time( 'mysql' )` en getoond in het datumformaat van de site.

### Kan ik meerdere berichten tegelijk als beoordeeld markeren?

Niet in de gratis plugin. Bulkacties, een REST API, uitstellen, webhooks en e-maildigests horen bij WPCity Content Freshness Pro.
