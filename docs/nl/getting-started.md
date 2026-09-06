# Aan de slag

WPCity Content Freshness legt vast wanneer elk stuk content voor het laatst is beoordeeld en laat in één oogopslag zien welke pagina's verouderd zijn.

## Installatie

1. Upload de map `wpcity-content-freshness` naar `/wp-content/plugins/`
2. Activeer de plugin via het menu **Plugins** in WordPress
3. Ga naar **Instellingen > Actualiteit content** om te kiezen welke berichttypes je bijhoudt

## Eerste stappen

### 1. Kies je berichttypes

Open na activatie **Instellingen > Actualiteit content**. Je ziet elk openbaar berichttype op je site. Vink de types aan die een beoordelingscyclus nodig hebben. Berichten en pagina's staan standaard aan.

### 2. Stel een standaard beoordelingsinterval in

Kies op hetzelfde scherm hoe vaak content opnieuw bekeken moet worden: 3, 6 of 12 maanden. Dat interval geldt voor elk bijgehouden bericht, tenzij je het op het bericht zelf overschrijft.

### 3. Beoordeel een bericht

Open een bijgehouden bericht of pagina. Het blok **Actualiteit content** staat in de zijbalk, onder het Publiceren-blok, en toont drie dingen:

- **Beoordelingsinterval**: gebruik de standaard, kies 3, 6 of 12 maanden, of kies "Niet bijhouden" om dit bericht helemaal buiten beschouwing te laten
- **Laatst beoordeeld**: de datum waarop de content voor het laatst als actueel is bevestigd
- Een gekleurde statusregel die laat zien waar dit bericht staat

Klik op **Markeer als beoordeeld** en de datum wordt direct opgeslagen, zonder de pagina te herladen.

### 4. Lees de kleuren

| Kleur | Betekenis |
|-------|-----------|
| Groen | Recent beoordeeld, niets te doen |
| Oranje | Binnen 30 dagen aan de beurt |
| Rood | Over tijd, het interval is verstreken |
| Grijs | Niet bijgehouden, je hebt het interval op "Niet bijhouden" gezet |

Dezelfde stip verschijnt in de kolom **Actualiteit content** in elke berichtenlijst, naast de titel. Klik op de kolomkop om op beoordelingsdatum te sorteren; berichten die nooit zijn beoordeeld blijven gewoon in de lijst staan.

### 5. Houd het dashboard in de gaten

De dashboardwidget **Actualiteit content** toont de vijf meest urgente berichten, met een link naar de volledige lijst. Staat er niets open, dan zegt de widget dat gewoon.

## Wat telt als "laatst beoordeeld"

Een bericht dat nooit als beoordeeld is gemarkeerd valt terug op zijn eigen laatste wijzigingsdatum. Zo verschijnt een pas geschreven pagina niet als achterstallig op de dag dat je de plugin installeert, en zegt het aantal vanaf dag één iets zinnigs.

## Verder lezen

- [Instellingen](settings.md): elke optie uitgelegd
- [Actualiteit bijhouden](freshness.md): hoe de status wordt berekend
- [Hooks en filters](hooks.md): de plugin uitbreiden in code
- [Veelgestelde vragen](faq.md)
