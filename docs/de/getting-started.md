# Erste Schritte

WPCity Content Freshness hält fest, wann jeder Inhalt zuletzt geprüft wurde, und zeigt auf einen Blick, welche Seiten veraltet sind.

## Installation

1. Lade den Ordner `wpcity-content-freshness` nach `/wp-content/plugins/` hoch
2. Aktiviere das Plugin über das Menü **Plugins** in WordPress
3. Öffne **Einstellungen > Inhaltsaktualität** und wähle die Inhaltstypen aus, die verfolgt werden sollen

## Die ersten Schritte

### 1. Inhaltstypen auswählen

Öffne nach der Aktivierung **Einstellungen > Inhaltsaktualität**. Dort steht jeder öffentliche Inhaltstyp deiner Website. Hake die an, die einen Prüfzyklus brauchen. Beiträge und Seiten sind standardmäßig aktiv.

### 2. Standard-Prüfintervall festlegen

Wähle auf demselben Bildschirm, wie oft Inhalte erneut angesehen werden sollen: 3, 6 oder 12 Monate. Dieses Intervall gilt für jeden verfolgten Inhalt, sofern du es nicht am Inhalt selbst überschreibst.

### 3. Einen Inhalt prüfen

Öffne einen verfolgten Beitrag oder eine Seite. Die Box **Inhaltsaktualität** steht in der Seitenleiste unter dem Veröffentlichen-Block und zeigt drei Dinge:

- **Prüfintervall**: den Standard verwenden, 3, 6 oder 12 Monate wählen, oder "Nicht verfolgen" auswählen, um diesen Inhalt ganz auszuschließen
- **Zuletzt geprüft**: das Datum, an dem der Inhalt zuletzt als aktuell bestätigt wurde
- Eine farbige Statuszeile, die zeigt, wo dieser Inhalt steht

Klicke auf **Als geprüft markieren** und das Datum wird sofort gespeichert, ohne die Seite neu zu laden.

### 4. Die Farben lesen

| Farbe | Bedeutung |
|-------|-----------|
| Grün | Kürzlich geprüft, nichts zu tun |
| Orange | Innerhalb der nächsten 30 Tage fällig |
| Rot | Überfällig, das Intervall ist verstrichen |
| Grau | Nicht verfolgt, du hast "Nicht verfolgen" gewählt |

Derselbe Punkt erscheint in der Spalte **Inhaltsaktualität** jeder Beitragsliste, neben dem Titel. Klicke auf die Spaltenüberschrift, um nach Prüfdatum zu sortieren; nie geprüfte Beiträge bleiben in der Liste.

### 5. Das Dashboard im Blick behalten

Das Dashboard-Widget **Inhaltsaktualität** listet die fünf dringendsten Beiträge mit einem Link zur vollständigen Liste. Ist nichts überfällig, sagt es genau das.

## Was als "zuletzt geprüft" zählt

Ein Beitrag, der nie als geprüft markiert wurde, greift auf sein eigenes Änderungsdatum zurück. So erscheint eine frisch geschriebene Seite nicht schon am Installationstag als überfällig, und die Zahl bedeutet vom ersten Tag an etwas.

## Weiterführend

- [Einstellungen](settings.md): jede Option erklärt
- [Aktualität verfolgen](freshness.md): wie der Status berechnet wird
- [Hooks und Filter](hooks.md): das Plugin im Code erweitern
- [FAQ](faq.md): häufige Fragen
