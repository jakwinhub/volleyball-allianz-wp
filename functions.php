<?php
/**
 * Volleyball Allianz – functions.php
 *
 * Inhalt:
 *  1. Theme-Setup
 *  2. Styles & Scripts
 *  3. Customizer
 *  4. Custom Post Types: Mannschaft, Spiel
 *  5. Custom Taxonomies: Kategorie & Liga
 *  6. Meta Boxes: Mannschafts-Daten
 *  7. Widget-Bereiche
 *  8. CSV-Spielplan auslesen
 *  9. CSV-Import für Spieler / Kader
 *  10. Helper-Funktionen
 *  11. Admin-Seite: Kader importieren / aktualisieren
 *  12. Admin-Menü hinzufügen
 */
if (!defined('ABSPATH')) exit;


/* ─────────────────────────────────────────────
   1. THEME-SETUP
───────────────────────────────────────────── */
function va_theme_setup()
{

    // Übersetzungen laden
    load_theme_textdomain('volleyball-allianz', get_template_directory() . '/languages');

    // Automatischer Feed-Link im <head>
    add_theme_support('automatic-feed-links');

    // <title>-Tag von WordPress verwalten lassen
    add_theme_support('title-tag');

    // Breites Layout für Blöcke
    add_theme_support('align-wide');

    // Featured Images (Beitragsbilder) aktivieren
    add_theme_support('post-thumbnails');

    // HTML5-Markup aktivieren
    add_theme_support('html5', [
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ]);

    // Logo-Unterstützung
    add_theme_support('custom-logo', [
            'height' => 60,
            'width' => 200,
            'flex-width' => true,
            'flex-height' => true,
    ]);

    // Beitragsbild-Größen
    add_image_size('team-banner', 1200, 500, true); // Teamseiten-Header
    add_image_size('player-card', 400, 400, true); // Spieler-Karte (quadratisch)
    add_image_size('hero-hall', 1920, 1080, true); // Hallenaufnahme Hero

    // Menü-Positionen registrieren
    register_nav_menus([
            'primary' => __('Hauptmenü (Navigation)', 'volleyball-allianz'),
            'footer' => __('Footer-Menü', 'volleyball-allianz'),
    ]);
}

add_action('after_setup_theme', 'va_theme_setup');


/* ─────────────────────────────────────────────
   2. STYLES & SCRIPTS
───────────────────────────────────────────── */
function va_enqueue_assets()
{
    $ver = '1.0.1';

    // 1. Google Fonts
    wp_enqueue_style('va-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Familjen+Grotesk:ital,wght@0,400..700;1,400..700&display=swap', [], null);

    // Haupt-Stylesheet
    wp_enqueue_style('va-style', get_stylesheet_uri(), [], $ver);

    // Hero-CSS
    if (is_front_page()) {
        wp_enqueue_style('va-hero', get_template_directory_uri() . '/css/hero.css', ['va-style'], $ver);
    }

    // Team-CSS
    if (is_singular('mannschaft')) {
        wp_enqueue_style('va-team', get_template_directory_uri() . '/css/team.css', ['va-style'], $ver);
    }

    // Header-Stylesheet
    wp_enqueue_style('header-style', get_template_directory_uri() . '/css/header.css', array('va-style'), $ver);

    // Section-Stylesheet
    wp_enqueue_style('section-style', get_template_directory_uri() . '/css/section.css', array('va-style'), $ver);

    // Header-Menu-Stylesheet
    wp_enqueue_style('header-menu-style', get_template_directory_uri() . '/css/header-menu.css', array('header-style'), $ver);

    // Section-Stylesheet
    wp_enqueue_style('footer-style', get_template_directory_uri() . '/css/footer.css', array('va-style'), $ver);

    // Javascript
    wp_enqueue_script('va-main-js', get_template_directory_uri() . '/js/main.js', array(), $ver, true);
}

add_action('wp_enqueue_scripts', 'va_enqueue_assets');


/* ─────────────────────────────────────────────
   3. CUSTOMIZER — Einstellungen im Backend
───────────────────────────────────────────── */
function va_customizer_settings($wp_customize)
{

    // Panel: Volleyball Allianz
    $wp_customize->add_panel('va_panel', [
            'title' => __('Volleyball Allianz', 'volleyball-allianz'),
            'priority' => 30,
    ]);

    /* ─── Sektion: Hero ─── */
    $wp_customize->add_section('va_hero', [
            'title' => __('Hero (Startseite)', 'volleyball-allianz'),
            'panel' => 'va_panel',
    ]);

    // rechte Seite Hero
    $wp_customize->add_setting('hero_hall_image', [
            'default' => get_template_directory_uri() . '/images/halle-placeholder.jpg',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_hall_image', [
            'label' => __('Hallenaufnahme (Hero rechts)', 'volleyball-allianz'),
            'description' => __('Empfohlen: min. 960 × 800 px, Querformat', 'volleyball-allianz'),
            'section' => 'va_hero',
    ]));

    // Eyebrow-Text
    $wp_customize->add_setting('hero_eyebrow', [
            'default' => 'TSV Georgii-Allianz · Stuttgart-Vaihingen',
            'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_eyebrow', [
            'label' => __('Eyebrow-Text', 'volleyball-allianz'),
            'section' => 'va_hero',
            'type' => 'text',
    ]);

    // Haupt-Headline
    $wp_customize->add_setting('hero_title', [
            'default' => 'Volleyball in Stuttgart.',
            'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('hero_title', [
            'label' => __('Headline (Zeilenumbruch mit Enter)', 'volleyball-allianz'),
            'section' => 'va_hero',
            'type' => 'textarea',
    ]);

    // Slogan
    $wp_customize->add_setting('hero_slogan', [
            'default' => 'Wir punkten mit Schwabenpower.',
            'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_slogan', [
            'label' => __('Slogan', 'volleyball-allianz'),
            'section' => 'va_hero',
            'type' => 'text',
    ]);

    // Button 1
    $wp_customize->add_setting('hero_btn1_label', ['default' => 'Alle Teams', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('hero_btn1_label', ['label' => 'Button 1 – Beschriftung', 'section' => 'va_hero', 'type' => 'text']);
    $wp_customize->add_setting('hero_btn1_url', ['default' => '#teams', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control('hero_btn1_url', ['label' => 'Button 1 – URL', 'section' => 'va_hero', 'type' => 'text']);

    // Button 2
    $wp_customize->add_setting('hero_btn2_label', ['default' => 'Spielplan ansehen', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('hero_btn2_label', ['label' => 'Button 2 – Beschriftung', 'section' => 'va_hero', 'type' => 'text']);
    $wp_customize->add_setting('hero_btn2_url', ['default' => '/spielplan/', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control('hero_btn2_url', ['label' => 'Button 2 – URL', 'section' => 'va_hero', 'type' => 'text']);

    /* ─── Sektion: Stat-Felder ─── */
    $wp_customize->add_section('va_stats', [
            'title' => __('Kennzahlen (Hero-Karten)', 'volleyball-allianz'),
            'panel' => 'va_panel',
    ]);
    for ($i = 1; $i <= 2; $i++) {
        $wp_customize->add_setting("stat_{$i}_num", ['default' => '', 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control("stat_{$i}_num", ['label' => "Karte {$i} – Zahl/Kürzel", 'section' => 'va_stats', 'type' => 'text']);
        $wp_customize->add_setting("stat_{$i}_label", ['default' => '', 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control("stat_{$i}_label", ['label' => "Karte {$i} – Bezeichnung", 'section' => 'va_stats', 'type' => 'text']);
    }

    /* ─── Sektion: Nächstes Heimspiel ─── */
    $wp_customize->add_section('va_next_game', [
            'title' => __('Nächstes Heimspiel (Hero)', 'volleyball-allianz'),
            'panel' => 'va_panel',
    ]);
    $wp_customize->add_setting('next_game_teams', ['default' => '', 'sanitize_callback' => 'sanitize_textarea_field']);
    $wp_customize->add_control('next_game_teams', ['label' => 'Mannschaften (je Zeile eine)', 'section' => 'va_next_game', 'type' => 'textarea']);
    $wp_customize->add_setting('next_game_meta', ['default' => '', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('next_game_meta', ['label' => 'Details (Datum, Ort, Liga)', 'section' => 'va_next_game', 'type' => 'text']);

    /* ─── Sektion: Footer ─── */
    $wp_customize->add_section('va_footer', [
            'title' => __('Footer', 'volleyball-allianz'),
            'panel' => 'va_panel',
    ]);
    $wp_customize->add_setting('footer_name', ['default' => 'Allianz Volleyball Stuttgart', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('footer_name', ['label' => 'Vereinsname', 'section' => 'va_footer', 'type' => 'text']);
    $wp_customize->add_setting('footer_sub', ['default' => 'TSV Georgii-Allianz e. V. · Stuttgart-Vaihingen', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('footer_sub', ['label' => 'Unterzeile', 'section' => 'va_footer', 'type' => 'text']);
    $wp_customize->add_setting('footer_copy', ['default' => '© 2026 TSV Georgii-Allianz e. V.', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('footer_copy', ['label' => 'Copyright-Zeile', 'section' => 'va_footer', 'type' => 'text']);
}

add_action('customize_register', 'va_customizer_settings');


/* ─────────────────────────────────────────────
   4. CUSTOM POST TYPE: Mannschaft
───────────────────────────────────────────── */
function va_register_post_types()
{
    // CPT: Mannschaft
    register_post_type('mannschaft', [
            'labels' => [
                    'name' => __('Mannschaften', 'volleyball-allianz'),
                    'singular_name' => __('Mannschaft', 'volleyball-allianz'),
                    'add_new_item' => __('Neue Mannschaft anlegen', 'volleyball-allianz'),
                    'edit_item' => __('Mannschaft bearbeiten', 'volleyball-allianz'),
                    'all_items' => __('Alle Mannschaften', 'volleyball-allianz'),
                    'search_items' => __('Mannschaften suchen', 'volleyball-allianz'),
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-groups',
            'menu_position' => 5,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'mannschaften'],
    ]);

    // CPT: Spiel
    register_post_type('spiel', [
            'labels' => [
                    'name' => __('Spiele', 'volleyball-allianz'),
                    'singular_name' => __('Spiel', 'volleyball-allianz'),
                    'add_new_item' => __('Neues Spiel eintragen', 'volleyball-allianz'),
                    'edit_item' => __('Spiel bearbeiten', 'volleyball-allianz'),
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title'],
            'rewrite' => ['slug' => 'spiele'],
    ]);
}

add_action('init', 'va_register_post_types');


/* ─────────────────────────────────────────────
   5. CUSTOM TAXONOMIES: Geschlecht & Liga
───────────────────────────────────────────── */
function va_register_taxonomies()
{

    // Kategorie: Damen / Herren / Jugend
    register_taxonomy('mannschaft_kategorie', 'mannschaft', [
            'labels' => [
                    'name' => __('Kategorien', 'volleyball-allianz'),
                    'singular_name' => __('Kategorie', 'volleyball-allianz'),
                    'all_items' => __('Alle Kategorien', 'volleyball-allianz'),
            ],
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'kategorie'],
    ]);

    // Liga (3. Liga, Regionalliga, …)
    register_taxonomy('liga', 'mannschaft', [
            'labels' => [
                    'name' => __('Ligen', 'volleyball-allianz'),
                    'singular_name' => __('Liga', 'volleyball-allianz'),
            ],
            'hierarchical' => false,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'liga'],
    ]);
}

add_action('init', 'va_register_taxonomies');

/* ─────────────────────────────────────────────
   6. META BOXES: Mannschafts-Daten
───────────────────────────────────────────── */
function va_mannschaft_meta_boxes()
{
    add_meta_box(
            'va_mannschaft_details',
            __('Mannschafts-Details', 'volleyball-allianz'),
            'va_render_mannschaft_meta_box',
            'mannschaft',
            'normal',
            'high'
    );
}

add_action('add_meta_boxes', 'va_mannschaft_meta_boxes');

function va_render_mannschaft_meta_box($post)
{
    wp_nonce_field('va_mannschaft_meta', 'va_mannschaft_nonce');

    $fields = [
            'va_team_typ' => __('Team-Typ (Damen / Herren / Jugend)', 'volleyball-allianz'),
            'va_team_nummer' => __('Team-Nummer (z. B. 1, 2, 3)', 'volleyball-allianz'),

            'va_trainer' => __('Cheftrainer', 'volleyball-allianz'),
            'va_liga' => __('Liga / Spielklasse', 'volleyball-allianz'),
            'va_heimstaette' => __('Heimspielstätte', 'volleyball-allianz'),
            'va_training_zeiten' => __('Trainingszeiten', 'volleyball-allianz'),
            'va_kontakt_email' => __('Kontakt E-Mail', 'volleyball-allianz'),
            'va_tabellen_platz' => __('Aktueller Tabellenplatz', 'volleyball-allianz'),
            'va_bilanz' => __('Bilanz', 'volleyball-allianz'),
            'va_link_tabelle' => __('Link zur Tabelle (extern)', 'volleyball-allianz'),
            'va_link_spielplan' => __('Link zum Spielplan (extern)', 'volleyball-allianz'),
            'va_link_ticker' => __('Link zum Live-Ticker', 'volleyball-allianz'),
            'va_link_instagram' => __('Link zum Instagram-Account', 'volleyball-allianz')
    ];

    echo '<table class="form-table">';
    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, $key, true);
        echo '<tr>';
        echo '<th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
        echo '<td>';

        if ($key === 'va_team_typ') {
            echo '<select id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
            echo '<option value="">-- auswählen --</option>';
            echo '<option value="1"' . selected($value, '1', false) . '>Damen</option>';
            echo '<option value="2"' . selected($value, '2', false) . '>Herren</option>';
            echo '<option value="3"' . selected($value, '3', false) . '>Jugend</option>';
            echo '</select>';

        } elseif ($key === 'va_team_nummer') {
            echo '<input type="number" min="1" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';

        } else {
            echo '<input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text" />';
        }

        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

function va_save_mannschaft_meta($post_id)
{
    if (!isset($_POST['va_mannschaft_nonce'])) return;
    if (!wp_verify_nonce($_POST['va_mannschaft_nonce'], 'va_mannschaft_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
            'va_team_typ', 'va_team_nummer',
            'va_trainer', 'va_liga', 'va_heimstaette', 'va_training_zeiten',
            'va_kontakt_email', 'va_tabellen_platz', 'va_bilanz',
            'va_link_tabelle', 'va_link_spielplan', 'va_link_ticker',
    ];
    foreach ($fields as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
}

add_action('save_post_mannschaft', 'va_save_mannschaft_meta');

/* ─────────────────────────────────────────────
   7. WIDGET-BEREICHE
───────────────────────────────────────────── */
function va_register_sidebars()
{
    register_sidebar([
            'name' => __('Team-Sidebar', 'volleyball-allianz'),
            'id' => 'team-sidebar',
            'description' => __('Wird auf Mannschaftsseiten rechts angezeigt', 'volleyball-allianz'),
            'before_widget' => '<div class="sidebar-box">',
            'after_widget' => '</div>',
            'before_title' => '<div class="sidebar-box-head">',
            'after_title' => '</div>',
    ]);
}

add_action('widgets_init', 'va_register_sidebars');

/* ─────────────────────────────────────────────
   8. CSV-SPIELPLAN-AUSLESEN
───────────────────────────────────────────── */
/**
 * Liest alle zukünftigen Spiele eines Teams aus einer CSV-Datei.
 * Erwartet: /data/spielplan_{team_slug}.csv
 *
 * @param string $team_slug Slug des Teams, z. B. 'damen-1'
 * @return array              Sortierte Liste zukünftiger Spiele
 */
function va_get_team_games_from_csv($team_slug)
{
    $csv_filename = 'spielplan_' . $team_slug . '.csv';
    $csv_path = get_template_directory() . '/data/' . $csv_filename;

    $spiele = [];
    $heute = strtotime('today');

    if (!file_exists($csv_path)) return [];

    if (($handle = fopen($csv_path, "r")) !== FALSE) {

        fgetcsv($handle, 1000, ";");

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

            $data = array_map(function ($value) {
                return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            }, $data);

            if (empty($data[0])) continue;
            $spiel_zeitpunkt = strtotime($data[0]);

            if ($spiel_zeitpunkt < $heute) continue;

            $m1 = $data[4];
            $m2 = $data[6];

            $is_home = (
                    strpos($m1, 'GA Stuttgart') !== false ||
                    strpos($m1, 'G.A. Stuttgart') !== false
            );

            $spiele[] = [
                    'timestamp' => $spiel_zeitpunkt,
                    'datum' => date_i18n('D, d.m.', $spiel_zeitpunkt),
                    'uhrzeit' => date('H:i', $spiel_zeitpunkt),
                    'gegner' => $is_home ? $m2 : $m1,
                    'ort' => str_replace(' (70565 Stuttgart (Vaihingen))', '', $data[13]),
                    'heimspiel' => $is_home
            ];
        }

        fclose($handle);
    }

    usort($spiele, function ($a, $b) {
        return $a['timestamp'] <=> $b['timestamp'];
    });

    return $spiele;
}

/**
 * Gibt das nächste Heimspiel eines Teams zurück.
 *
 * @param string $team_slug Slug des Teams
 * @return array|null             Spiel-Array oder null, wenn keines gefunden
 */
function va_get_next_home_game_from_csv($team_slug)
{
    $spiele = va_get_team_games_from_csv($team_slug);

    foreach ($spiele as $spiel) {
        if ($spiel['heimspiel']) {
            return $spiel;
        }
    }

    return null;
}

/* ─────────────────────────────────────────────
   9. CSV-Import für Spieler / Kader
───────────────────────────────────────────── */

/**
 * Importiert oder aktualisiert Spieler aus CSV.
 * Fügt Default-Bild hinzu, falls kein Thumbnail existiert.
 */
function va_import_kader_csv_file(int $team_id, string $file_path, bool $update_only = false): string
{
    if (!file_exists($file_path)) {
        return "Datei nicht gefunden: $file_path";
    }

    $handle = fopen($file_path, 'r');
    if (!$handle) {
        return "Konnte CSV nicht öffnen: $file_path";
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    fgetcsv($handle, 1000, ';');

    $imported = 0;
    $default_img_path = get_template_directory() . '/images/default-player.png';

    while (($data = fgetcsv($handle, 1000, ';')) !== false) {

        $data = array_map(function ($value) {
            return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
        }, $data);

        $nachname = $data[0] ?? '';
        $vorname = $data[1] ?? '';
        $nummer = $data[7] ?? '';
        $position = $data[10] ?? '';
        $bild_url = $data[12] ?? '';
        $captain = !empty($data[2]) && $data[2] === '1';

        if (empty($vorname) && empty($nachname)) continue;

        $post_title = trim("$vorname $nachname");

        $existing = get_posts([
                'post_type' => 'spieler',
                'meta_key' => 'va_team_id',
                'meta_value' => $team_id,
                'title' => $post_title,
                'numberposts' => 1
        ]);

        if ($existing) {
            $player_id = $existing[0]->ID;
        } else {
            $player_id = wp_insert_post([
                    'post_type' => 'spieler',
                    'post_title' => $post_title,
                    'post_status' => 'publish',
            ]);

            if (is_wp_error($player_id) || !$player_id) continue;

            update_post_meta($player_id, 'va_team_id', $team_id);
        }

        // Daten IMMER aktualisieren
        update_post_meta($player_id, 'va_number', $nummer);
        update_post_meta($player_id, 'va_position', $position);
        update_post_meta($player_id, 'va_captain', $captain ? 1 : 0);

        // Bild setzen
        if (!has_post_thumbnail($player_id)) {

            $source_img = !empty($bild_url) ? $bild_url : $default_img_path;

            if (filter_var($source_img, FILTER_VALIDATE_URL)) {

                $tmp = download_url($source_img);

                if (!is_wp_error($tmp)) {
                    $file_array = [
                            'name' => basename($source_img),
                            'tmp_name' => $tmp
                    ];

                    $thumb_id = media_handle_sideload($file_array, $player_id);

                    if (!is_wp_error($thumb_id)) {
                        set_post_thumbnail($player_id, $thumb_id);
                    }
                }

            } else {

                if (file_exists($source_img)) {
                    $file_array = [
                            'name' => basename($source_img),
                            'tmp_name' => $source_img
                    ];

                    $thumb_id = media_handle_sideload($file_array, $player_id);

                    if (!is_wp_error($thumb_id)) {
                        set_post_thumbnail($player_id, $thumb_id);
                    }
                }
            }
        }

        $imported++;
    }

    fclose($handle);

    return "Import abgeschlossen: $imported Spieler für Team-ID $team_id";
}


/* ─────────────────────────────────────────────
   10. HELPER-FUNKTIONEN
───────────────────────────────────────────── */

/**
 * Gibt ein Meta-Feld (escaped) zurück.
 * Verwendung im Template: <?php echo va_meta( 'va_trainer' ); ?>
 *
 * @param string $key Meta-Key
 * @param int|null $post_id Optional; Standard: aktueller Post
 * @return string
 */
function va_meta($key, $post_id = null)
{
    return esc_html(get_post_meta($post_id ?? get_the_ID(), $key, true));
}


/**
 * Gibt ein Meta-URL-Feld (escaped) zurück.
 *
 * @param string $key Meta-Key
 * @param int|null $post_id Optional; Standard: aktueller Post
 * @return string
 */
function va_meta_url($key, $post_id = null)
{
    return esc_url(get_post_meta($post_id ?? get_the_ID(), $key, true));
}

/**
 * Gibt einen Customizer-Wert (escaped) mit Fallback zurück.
 *
 * @param string $key theme_mod-Schlüssel
 * @param string $default Fallback-Wert
 * @return string
 */
function va_mod($key, $default = '')
{
    return esc_html(get_theme_mod($key, $default));
}

/* ─────────────────────────────────────────────
   11. Admin-Seite: Kader importieren / aktualisieren
───────────────────────────────────────────── */

function va_kader_import_page()
{
    ?>
    <div class="wrap">
        <h1>Kader importieren / aktualisieren</h1>
        <form method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="team_id">Team auswählen</label></th>
                    <td>
                        <select name="team_id" id="team_id" required>
                            <option value="">-- wählen --</option>
                            <?php
                            $teams = get_posts([
                                    'post_type' => 'mannschaft',
                                    'numberposts' => -1,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                            ]);
                            foreach ($teams as $team) {
                                echo '<option value="' . esc_attr($team->ID) . '">' . esc_html($team->post_title) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="kader_csv">CSV-Datei hochladen</label></th>
                    <td><input type="file" name="kader_csv" id="kader_csv" accept=".csv" required></td>
                </tr>
                <tr>
                    <th scope="row">Modus</th>
                    <td>
                        <label><input type="radio" name="update_mode" value="0" checked> Neuer Import
                        </label><br>
                        <label><input type="radio" name="update_mode" value="1"> Update
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button('Import starten'); ?>
        </form>
    </div>
    <?php

    // Verarbeitung beim Absenden
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['team_id']) && !empty($_FILES['kader_csv'])) {
        $team_id = intval($_POST['team_id']);
        $file = $_FILES['kader_csv'];
        $update_mode = !empty($_POST['update_mode']) && $_POST['update_mode'] === '1';

        $tmp_path = $file['tmp_name'];

        if (file_exists($tmp_path)) {
            $result = va_import_kader_csv_file($team_id, $tmp_path, $update_mode);
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($result) . '</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Datei konnte nicht gelesen werden.</p></div>';
        }
    }
}

/* ─────────────────────────────────────────────
   12. Admin-Menü hinzufügen
───────────────────────────────────────────── */
add_action('admin_menu', function () {
    add_submenu_page(
            'edit.php?post_type=mannschaft', // Unter Menü von Mannschaften
            'Kader importieren',             // Seitentitel
            'Kader importieren',             // Menü-Titel
            'manage_options',                // Capability
            'va_kader_import',               // Slug
            'va_kader_import_page'           // Callback
    );
});