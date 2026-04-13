# Volleyball Allianz – WordPress-Theme

## Installation

1. Den Ordner `volleyball-allianz` nach `wp-content/themes/` hochladen
2. In WordPress-Backend: **Design → Themes** → Theme aktivieren
3. Unter **Design → Customizer → Volleyball Allianz** die Inhalte anpassen

## Wichtigste Einstellungen im Customizer

| Bereich                  | Was du dort einstellst                          |
|--------------------------|-------------------------------------------------|
| Hero → Hallenaufnahme    | Aktuelles Hallenfoto (min. 960×800 px)          |
| Hero → Headline / Slogan | Texte für die linke Hero-Seite                  |
| Kennzahlen               | 2 Felder                                        |
| Nächste Heimspiel        | Mannschaften (Damen 1 und Herren 1) + Datum/Ort |
| Footer                   | Vereinsname & Copyright                         |

## Mannschaften anlegen (Custom Post Type)

Im Backend erscheint ein neuer Menüpunkt **Mannschaften**.

Für jede Mannschaft:

- **Titel**: z.B. „Damen 1“
- **Beitragsinhalt**: Kurzbeschreibung des Teams
- **Mannschafts-Details** (Meta-Box): Trainer, Liga, Halle, Training, E-Mail, Tabellenplatz, Bilanz
- **Kategorie**: Damen / Herren / Jugend (Taxonomy)
- **Reihenfolge**: Zahl für die Sortierung (z. B. 1 = Damen 1 erscheint zuerst)

### Kader eingeben (JSON im Feld `va_spieler`)

Für jede Mannschaft, die einen Kader haben soll:

- CSV Datei erstellen oder aus SAMS / DVV online Portal laden
- CSV Datei über das Backend laden und der jeweiligen Mannschaft zuordnen

### Nächste Spiele (JSON im Feld `va_naechste_spiele`)

Für jede Mannschaft:

- CSV Datei mit allen Spieltagen erstellen oder herunterladen. Der Inhalt muss dem folgenden Format entsprechen:

//TO-DO: Konventionen für CSV festlegen

## Menüs einrichten

**Design → Menüs**:

- Menü „Hauptnavigation“ erstellen → Position „Hauptmenü“ zuweisen
- Menü „Footer“ erstellen → Position ‚Footer-Menü‘ zuweisen

## Dateien-Übersicht

```
volleyball-allianz/
├── style.css              ← Haupt-CSS
├── css/
│   └── footer.css           
│   └── header.css           
│   └── header-menu.css      
│   └── hero.css           
│   └── section.css           
│   └── team.css          
├── js/
│   └── main.js            ← Tab-Filter, Nav-Scroll-Effekt
├── images/
│   └── teams/
|       └── mannschaftsbild_default.png  
│   └── default-player.png  
├── template-parts/
│   ├── next-home-games.php     ← Nächste Heimspiele (Teamseite)
│   ├── next-games.php     ← Nächste Spiele (Frontpage)
│   ├── team-kader.php     ← Spieler-Karte (Teamseite)
│   ├── team-spiele.php    ← Sidebar Spiele (Teamseite)
│   └── team-galerie.php   ← Fotogalerie (Teamseite)
├── functions.php          ← Theme-Setup, Customizer, CPT, Meta-Boxes
├── header.php             ← Navigation
├── footer.php             ← Footer
├── front-page.php         ← Startseite
├── single-mannschaft.php  ← Einheitliche Teamseite
├── page.php               ← Standard-Seiten
└── index.php              ← Fallback
```
