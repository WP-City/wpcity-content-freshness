# Einstellungen

Alle Einstellungen liegen unter **Einstellungen > Inhaltsaktualität**.

## Inhaltstypen

Welche Inhaltstypen eine Aktualitäts-Box, eine Listenspalte und einen Platz im Dashboard-Widget bekommen.

Jeder öffentliche Inhaltstyp der Website steht in der Liste, außer Medienanhängen. Beiträge und Seiten sind standardmäßig angehakt.

Einen Inhaltstyp abzuschalten blendet Box und Spalte aus, behält aber die bereits gespeicherten Prüfdaten. Schaltest du ihn wieder ein, ist die Historie noch da.

## Standard-Prüfintervall

Wie lange Inhalte aktuell bleiben, bevor sie erneut angesehen werden sollten: 3 Monate (90 Tage), 6 Monate (180 Tage) oder 12 Monate (365 Tage). Voreingestellt sind 6 Monate.

Dieses Intervall gilt für jeden verfolgten Beitrag ohne eigenes Intervall. Um einem einzelnen Beitrag einen anderen Zyklus zu geben, ändere **Prüfintervall** in der Seitenleisten-Box dieses Beitrags.

## Abweichung pro Beitrag

Das Auswahlfeld **Prüfintervall** in der Seitenleiste akzeptiert:

| Auswahl | Gespeicherter Wert | Wirkung |
|---------|--------------------|---------|
| Standard verwenden | `0` | Folgt dem Website-Standard, eine Änderung dort verschiebt diesen Beitrag mit |
| 3 Monate | `90` | Fester Zyklus von 90 Tagen |
| 6 Monate | `180` | Fester Zyklus von 180 Tagen |
| 12 Monate | `365` | Fester Zyklus von 365 Tagen |
| Nicht verfolgen | `-1` | Kein Status, keine Farbe, nicht im Dashboard-Widget |

## Wo die Daten liegen

| Schlüssel | Typ | Bedeutung |
|-----------|-----|-----------|
| `wpcity_cf_post_types` | Option | Array der verfolgten Inhaltstypen |
| `wpcity_cf_default_interval` | Option | Standardintervall der Website in Tagen |
| `wpcity_cf_review_interval` | Post Meta | Intervall des einzelnen Beitrags in Tagen |
| `wpcity_cf_last_reviewed` | Post Meta | MySQL-Datetime der letzten Prüfung |

Beim Löschen des Plugins verschwinden alle vier, und sonst nichts. Die Pro-Erweiterung schreibt ihre eigenen Einstellungen unter `wpcity_cf_pro_`, das in diesem Präfix steckt, aber Pro gehört; sie verschwinden, wenn du Pro löschst.
