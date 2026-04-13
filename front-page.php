<?php
/**
 * Template Name: Startseite
 * Wird automatisch als Frontpage geladen wenn "Statische Startseite" eingestellt ist.
 */
get_header();
?>

    <main id="main" class="site-main">

        <section class="hero" aria-label="<?php esc_attr_e('Willkommen', 'volleyball-allianz'); ?>">

            <!-- Linke Seite -->
            <div class="hero-left">
                <p class="hero-eyebrow"><?php echo va_mod('hero_eyebrow', 'TSV Georgii-Allianz · Stuttgart-Vaihingen'); ?></p>

                <?php
                $raw_title = get_theme_mod('hero_title');
                $lines = array_filter(array_map('trim', explode("\n", $raw_title)));
                ?>
                <h1 class="hero-title">
                    <?php foreach ($lines as $i => $line) : ?>
                        <?php if ($i === 1) : ?>
                            <em><?php echo esc_html($line); ?></em><br>
                        <?php else : ?>
                            <?php echo esc_html($line); ?><br>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </h1>

                <p class="hero-slogan"><?php echo va_mod('hero_slogan', 'Wir punkten mit Schwabenpower.'); ?></p>

                <div class="hero-actions">
                    <a href="<?php echo esc_url(get_theme_mod('hero_btn1_url', '#teams')); ?>" class="btn-primary">
                        <?php echo va_mod('hero_btn1_label', 'Alle Teams'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_theme_mod('hero_btn2_url', '/spielplan/')); ?>" class="btn-ghost">
                        <?php echo va_mod('hero_btn2_label', 'Spielplan ansehen'); ?>
                    </a>
                </div>
            </div>

            <!-- Rechte Seite -->
            <div class="hero-right">
                <!-- Hintergrundbild (im Customizer einstellbar) -->
                <div class="hero-right-bg"
                     style="background-image: url('<?php echo esc_url(get_theme_mod('hero_hall_image', get_template_directory_uri())); ?>');"></div>
                <div class="hero-right-overlay" aria-hidden="true"></div>

                <!-- Inhalt: Karten über dem Bild -->
                <div class="hero-right-content">

                    <!-- Stat-Karten (2 Felder, im Customizer pflegbar) -->
                    <div class="hero-stat-cards">
                        <?php for ($i = 1; $i <= 2; $i++) :
                            $num = get_theme_mod("stat_{$i}_num");
                            $label = get_theme_mod("stat_{$i}_label");
                            ?>
                            <div class="hero-stat">
                                <div class="hero-stat-num"><?php echo esc_html($num); ?></div>
                                <div class="hero-stat-label"><?php echo esc_html($label); ?></div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Nächstes Spiel Damen 1 - Herren 1 -->
                    <div class="hero-next-game-cards">
                        <?php
                        $teams = ['damen-1', 'herren-1'];
                        $heute = strtotime('today');

                        foreach ($teams as $slug) :

                            $csv_path = get_template_directory() . '/data/spielplan_' . $slug . '.csv';
                            $spiele = [];

                            if (file_exists($csv_path) && ($handle = fopen($csv_path, "r")) !== FALSE) {
                                fgetcsv($handle, 1000, ";"); // Header überspringen

                                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                                    if (empty($data[0])) continue;

                                    $data = array_map(function ($field) {
                                        return mb_convert_encoding($field, 'UTF-8', 'UTF-8, ISO-8859-1');
                                    }, $data);

                                    $spiel_zeitpunkt = strtotime($data[0]);
                                    if ($spiel_zeitpunkt < $heute) continue;

                                    $m1_name = trim($data[4] ?? '');
                                    $m1_verein = trim($data[5] ?? '');
                                    $m2_name = trim($data[6] ?? '');
                                    $m2_verein = trim($data[7] ?? '');
                                    $liga = trim($data[16] ?? '');
                                    $ort = trim($data[13] ?? '');
                                    $gastgeber = trim($data[11] ?? ''); // Spalte L

                                    // Prüfen, ob TSV G.A. Stuttgart beteiligt ist
                                    $verein_ga = 'TSV G.A. Stuttgart';
                                    $ist_beteiligter = (
                                        stripos($m1_verein, $verein_ga) !== false ||
                                        stripos($m2_verein, $verein_ga) !== false
                                    );
                                    if (!$ist_beteiligter) continue;

                                    // Prüfen, ob Heimspiel anhand des Gastgebervereins
                                    $is_home = stripos($gastgeber, $verein_ga) !== false;

                                    // Gegner bestimmen
                                    if (stripos($m1_verein, $verein_ga) !== false) {
                                        $gegner = $m2_name;
                                    } else {
                                        $gegner = $m1_name;
                                    }

                                    $spiele[] = [
                                        'timestamp' => $spiel_zeitpunkt,
                                        'datum' => date_i18n('D d.m.', $spiel_zeitpunkt),
                                        'uhrzeit' => date('H:i', $spiel_zeitpunkt),
                                        'gegner' => $gegner,
                                        'ort' => str_replace(' (70565 Stuttgart (Vaihingen))', '', $ort),
                                        'heimspiel' => $is_home,
                                        'liga' => $liga
                                    ];
                                }

                                fclose($handle);
                            }

                            // Chronologisch sortieren
                            usort($spiele, function ($a, $b) {
                                return $a['timestamp'] <=> $b['timestamp'];
                            });

                            $next_game = $spiele[0] ?? null;
                            ?>

                            <div class="hero-next-game">
                                <?php if ($next_game) : ?>
                                    <div class="hero-next-label">
                                        <span style="font-size: 12px; color: var(--accent)">
                                        <?php echo esc_html(ucfirst(str_replace('-', ' ', $slug))); ?></div>
                                    <div class="hero-next-teams">
                                        <span style="font-size: 22px; color: var(--white);">
                                        <strong> Stuttgart</strong>
                                        <strong>vs. <?php echo esc_html($next_game['gegner']); ?></strong>
                                    </div>
                                    <div class="hero-next-meta">
                                        <span style="font-size: 12px; color: var(--accent)">
                                        <?php echo esc_html($next_game['datum']); ?> <?php echo esc_html($next_game['uhrzeit']); ?>
                                        Uhr · <?php echo esc_html($next_game['ort']); ?>
                                        · <?php echo esc_html($next_game['liga']); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="hero-no-game">
                                        <?php echo __('Für diese Mannschaft stehen aktuell keine Spiele an.', 'volleyball-allianz'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        <?php endforeach; ?>
                    </div>

                </div>

        </section>

        <div id="teams">
            <div class="section-header">
                <div>
                    <div class="section-tag"><?php esc_html_e('Mannschaften', 'volleyball-allianz'); ?></div>
                    <h2 class="section-h2"><?php esc_html_e('Unsere Teams', 'volleyball-allianz'); ?></h2>
                </div>
            </div>

            <div class="teams-wrapper">
                <!-- Tab-Filter -->
                <div class="teams-tabs" role="tablist">
                    <button class="tab-btn" data-filter="damen"
                            role="tab"><?php esc_html_e('Damen', 'volleyball-allianz'); ?></button>
                    <button class="tab-btn" data-filter="herren"
                            role="tab"><?php esc_html_e('Herren', 'volleyball-allianz'); ?></button>
                    <button class="tab-btn" data-filter="jugend"
                            role="tab"><?php esc_html_e('Jugend', 'volleyball-allianz'); ?></button>
                </div>

                <div class="teams-grid">
                    <?php
                    $teams_query = new WP_Query([
                        'post_type' => 'mannschaft',
                        'posts_per_page' => -1,
                        'meta_query' => [
                            'relation' => 'AND',
                            'typ_clause' => [
                                'key' => 'va_team_typ',
                                'type' => 'NUMERIC'
                            ],
                            'nummer_clause' => [
                                'key' => 'va_team_nummer',
                                'type' => 'NUMERIC'
                            ]
                        ],
                        'orderby' => [
                            'typ_clause' => 'ASC',
                            'nummer_clause' => 'ASC'
                        ]
                    ]);

                    while ($teams_query->have_posts()) :
                        $teams_query->the_post();
                        $is_highlight = (get_post_meta(get_the_ID(), 'va_highlight', true) == '1');
                        $liga = va_meta('va_liga');
                        $kategorien = get_the_terms(get_the_ID(), 'mannschaft_kategorie');
                        $kat_slug = $kategorien ? strtolower(trim($kategorien[0]->slug)) : '';
                        $kat_name = $kategorien ? $kategorien[0]->name : '';
                        ?>
                        <a href="<?php the_permalink(); ?>"
                           class="team-card <?php echo $is_highlight ? 'highlight' : ''; ?>"
                           data-kategorie="<?php echo esc_attr($kat_slug); ?>">

                            <div class="team-card-name"><?php the_title(); ?></div>
                            <div class="team-card-league"><?php echo esc_html($liga); ?></div>
                            <div class="team-card-footer">
                                <?php esc_html_e('Zur Mannschaft', 'volleyball-allianz'); ?>
                                <span class="team-card-arrow" aria-hidden="true">→</span>
                            </div>
                        </a>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>


        <?php get_template_part('template-parts/next-home-games'); ?>

        <!-- Sponsoren -->
        <section class="Sponsoren">
            <div class="section-header" style="padding-left:0; padding-right:0; margin: 0 80px;">
                <div>
                    <div class="section-tag"><?php esc_html_e('Sponsoren', 'volleyball-allianz'); ?></div>
                    <h2 class="section-h2"><?php esc_html_e('Sponsoren', 'volleyball-allianz'); ?></h2>
                </div>
                <?php
                $sponsoren_link = get_term_link('sponsoren', 'mannschaft_kategorie');
                ?>
                <a href="<?php echo esc_url(!is_wp_error($sponsoren_link) ? $sponsoren_link : '#'); ?>"
                   class="section-link">
                    <?php esc_html_e('Mehr von unseren Sponsoren →', 'volleyball-allianz'); ?>
                </a>
            </div>

            <div class="sponsor-track">
                <img src="<?php echo get_template_directory_uri(); ?>/images/sponsor1.png" alt="">
                <img src="<?php echo get_template_directory_uri(); ?>/images/sponsor2.png" alt="">
                <img src="<?php echo get_template_directory_uri(); ?>/images/sponsor3.png" alt="">
            </div>
        </section>
    </main>

<?php get_footer(); ?>