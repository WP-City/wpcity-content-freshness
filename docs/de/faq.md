# FAQ

### Setzt das Bearbeiten eines Beitrags seine Aktualität zurück?

Nein, und das ist Absicht. Eine Bearbeitung ist keine Prüfung. Nur die Schaltfläche **Als geprüft markieren** oder das direkte Schreiben von `wpcity_cf_last_reviewed` verschiebt das Datum.

Die einzige Ausnahme ist ein Beitrag, der noch nie geprüft wurde. Er hat kein Datum, mit dem sich rechnen ließe, also greift das Plugin bis zur ersten Prüfung auf sein Änderungsdatum zurück.

### Warum ist direkt nach der Installation alles grün?

Weil noch nichts geprüft wurde und jeder Beitrag ab seinem eigenen Änderungsdatum gemessen wird. Kürzlich bearbeitete Inhalte gelten daher als aktuell. Seiten, die du seit Jahren nicht angefasst hast, stehen sofort auf Orange oder Rot.

### Kann ich eine einzelne Seite ausnehmen?

Ja. Setze ihr **Prüfintervall** auf "Nicht verfolgen". Der Status wird grau, der Beitrag fällt aus dem Dashboard-Widget und die Spalte zeigt einen Strich.

### Wo waren meine Beiträge, als ich nach der Aktualitätsspalte sortiert habe?

Nirgends. Ältere Versionen ließen jeden nie geprüften Beitrag aus der Liste fallen, sobald du nach dieser Spalte sortiert hast. Das ist behoben: Sortieren behält jetzt die vollständige Liste.

### Ich habe das Standardintervall geändert und nichts passiert.

Der Standard gilt nur für Beiträge, deren eigenes Intervall auf "Standard verwenden" steht. Jeder Beitrag, dem du einen festen Zyklus von 3, 6 oder 12 Monaten gegeben hast, behält diesen Zyklus.

### Sendet das Plugin irgendetwas nach außen?

Nein. Das kostenlose Plugin stellt überhaupt keine externen Anfragen. Webhooks und E-Mail-Zusammenfassungen gehören zur Pro-Erweiterung und bleiben aus, bis du sie einrichtest.

### Funktioniert es mit eigenen Inhaltstypen?

Ja, solange der Inhaltstyp öffentlich ist. Hake ihn unter **Einstellungen > Inhaltsaktualität** an oder füge ihn im Code über den Filter `wpcity_cf_post_types` hinzu.

### Was passiert mit meinen Daten, wenn ich das Plugin lösche?

Alles im Namensraum `wpcity_cf_` wird entfernt: die Einstellungen, die Prüfdaten jedes Beitrags, die Benutzer-Meta, die Transients und die geplanten Aufgaben. Das Deaktivieren entfernt nichts.

### Wird das Prüfdatum in Website-Zeit oder UTC gespeichert?

In Website-Zeit. Es wird mit `current_time( 'mysql' )` geschrieben und im Datumsformat der Website angezeigt.

### Kann ich mehrere Beiträge auf einmal als geprüft markieren?

Nicht im kostenlosen Plugin. Sammelaktionen, eine REST-API, das Zurückstellen, Webhooks und E-Mail-Zusammenfassungen gehören zu WPCity Content Freshness Pro.
