# Aktualität verfolgen

Wie das Plugin entscheidet, ob ein Beitrag aktuell, bald fällig oder überfällig ist.

## Die Berechnung

Für jeden verfolgten Beitrag:

1. Nimm das **effektive Intervall**. Das ist das eigene Intervall des Beitrags, sofern vorhanden, sonst der Website-Standard.
2. Nimm das **Bezugsdatum**. Das ist `wpcity_cf_last_reviewed`, wenn der Beitrag geprüft wurde, sonst sein letztes Änderungsdatum.
3. Die Frist ist das Bezugsdatum plus das effektive Intervall.
4. Der Status ergibt sich aus den verbleibenden Tagen bis zu dieser Frist.

| Verbleibende Tage | Farbe | Beschriftung |
|-------------------|-------|--------------|
| Mehr als 30 | Grün | Geprüft am [Datum] |
| 30 oder weniger | Orange | Fällig in [n] Tagen |
| 0 oder weniger | Rot | Seit [n] Tagen überfällig |

Ein Beitrag mit dem Intervall "Nicht verfolgen" ist grau und wird überall übersprungen.

## Warum nie geprüfte Beiträge das Änderungsdatum verwenden

Ohne Bezugsdatum wäre jeder Beitrag der Website in dem Moment überfällig, in dem du das Plugin aktivierst, und das Dashboard-Widget wäre am ersten Tag wertlos. Der Rückgriff auf das Änderungsdatum bedeutet, dass eine letzte Woche bearbeitete Seite als aktuell gilt und eine seit drei Jahren unangetastete Seite als überfällig. Das ist die Antwort, die du eigentlich wolltest.

Einen Beitrag als geprüft zu markieren ersetzt diesen Rückgriff durch ein echtes Prüfdatum. Von da an setzt eine Bearbeitung die Aktualität nicht mehr zurück, und genau darum geht es: eine Bearbeitung ist keine Prüfung.

## Als geprüft markieren

Die Schaltfläche **Als geprüft markieren** schreibt die aktuelle Website-Zeit per AJAX nach `wpcity_cf_last_reviewed` und aktualisiert die Statuszeile an Ort und Stelle. Die Anfrage ist durch einen Nonce geschützt und verlangt `edit_post` für diesen Beitrag.

## Die Liste sortieren

Die Spalte **Inhaltsaktualität** ist sortierbar. Sortiert wird nach Prüfdatum, und nie geprüfte Beiträge bleiben in der Liste, sodass ein Klick auf die Überschrift dir niemals Inhalte verbirgt.

## Das Dashboard-Widget

Das Widget zählt jeden verfolgten, veröffentlichten Beitrag, der orange oder rot ist, zeigt die fünf dringendsten und verlinkt auf die vollständige Liste. Beiträge auf "Nicht verfolgen" erscheinen nie.
