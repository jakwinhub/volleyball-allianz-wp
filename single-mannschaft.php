<?php
/**
 * Einheitliches Template für alle Mannschaftsseiten (Custom Post Type: mannschaft)
 */
get_header();
?>

<main id="main" role="main">

    <!-- TEAM HEADER -->
    <div class="team-page-header">
        <div class="team-page-header-ring" aria-hidden="true"></div>

        <!-- Backwards-Link -->
        <a class="team-page-back" href="<?php echo esc_url(get_post_type_archive_link('mannschaft')); ?>">
            ← <?php esc_html_e('Zurück zur Übersicht', 'volleyball-allianz'); ?>
        </a>

        <!-- Category-Badge -->
        <?php
        $kategorien = get_the_terms(get_the_ID(), 'mannschaft_kategorie');
        $ligen = get_the_terms(get_the_ID(), 'liga');
        $kat_name = $kategorien && !is_wp_error($kategorien) ? $kategorien[0]->name : '';
        $liga_name = $ligen && !is_wp_error($ligen) ? $ligen[0]->name : va_meta('va_liga');
        $team_nummer = va_meta('va_team_nummer');
        ?>

        <!-- Badge -->
        <div class="team-page-cat">
            <?php echo esc_html($kat_name); ?>
            <?php if ($team_nummer) echo ' · ' . esc_html($team_nummer); ?>
        </div>

        <!-- Team-name -->
        <h1 class="team-page-name">
            <?php if ($liga_name) echo '' . esc_html($liga_name); ?>
        </h1>

        <!-- Meta-fields -->
        <div class="team-page-meta">
            <?php
            $meta_felder = [
                    'va_trainer' => __('Cheftrainer', 'volleyball-allianz'),
                    'va_heimstaette' => __('Heimspielstätte', 'volleyball-allianz'),
                    'va_training_zeiten' => __('Training', 'volleyball-allianz'),
                    'va_kontakt_email' => __('Kontakt', 'volleyball-allianz'),
            ];
            foreach ($meta_felder as $key => $label) :
                $value = va_meta($key);
                if (!$value) continue;
                ?>
                <div class="team-meta-item">
                    <div class="team-meta-label"><?php echo esc_html($label); ?></div>
                    <div class="team-meta-value"><?php echo esc_html($value); ?></div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>


    <!-- Content -->
    <div class="team-page-body">

        <!-- Main -->
        <div class="team-page-main">

            <?php get_template_part('template-parts/team-presentation'); ?>

            <!-- Team description (Editor) -->
            <?php if (has_excerpt() || get_the_content()) : ?>
                <div class="team-description">
                    <?php if (has_excerpt()) : the_excerpt(); else : the_content(); endif; ?>
                </div>
            <?php endif; ?>

            <!-- Squad -->
            <?php get_template_part('template-parts/team-kader'); ?>

            <!-- Photos -->
            <?php get_template_part('template-parts/team-galerie'); ?>

        </div>


        <!-- Sidebar -->
        <aside class="team-page-sidebar" role="complementary">

            <!-- Saison info (set manually) -->
            <?php
            $platz = va_meta('va_tabellen_platz');
            $bilanz = va_meta('va_bilanz');
            if ($platz || $bilanz || $liga_name) :
                ?>
                <div class="sidebar-box">
                    <div class="sidebar-box-head">
                        <?php echo esc_html(date('Y') . '/' . (date('y') + 1)); ?> &mdash; Saison
                    </div>
                    <?php if ($liga_name) : ?>
                        <div class="sidebar-row">
                            <span class="sidebar-row-label"><?php esc_html_e('Liga', 'volleyball-allianz'); ?></span>
                            <span class="sidebar-row-value"><?php echo esc_html($liga_name); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($platz) : ?>
                        <div class="sidebar-row">
                            <span class="sidebar-row-label"><?php esc_html_e('Tabellenplatz', 'volleyball-allianz'); ?></span>
                            <span class="sidebar-row-value highlight"><?php echo esc_html($platz); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($bilanz) : ?>
                        <div class="sidebar-row">
                            <span class="sidebar-row-label"><?php esc_html_e('Bilanz', 'volleyball-allianz'); ?></span>
                            <span class="sidebar-row-value"><?php echo esc_html($bilanz); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Next games -->
            <?php get_template_part('template-parts/team-spiele'); ?>

            <!-- External links -->
            <?php
            $links = [
                    'va_link_tabelle' => __('Tabelle', 'volleyball-allianz'),
                    'va_link_spielplan' => __('Gesamtspielplan', 'volleyball-allianz'),
                    'va_link_ticker' => __('Live-Ticker', 'volleyball-allianz'),
                    'va_link_instagram' => __('Instagram', 'volleyball-allianz')
            ];
            $has_links = false;
            foreach ($links as $key => $_) {
                if (va_meta_url($key)) {
                    $has_links = true;
                    break;
                }
            }
            if ($has_links) :
                ?>
                <div class="sidebar-box">
                    <div class="sidebar-box-head"><?php esc_html_e('Links', 'volleyball-allianz'); ?></div>
                    <?php foreach ($links as $key => $label) :
                        $url = va_meta_url($key);
                        if (!$url) continue;
                        ?>
                        <a href="<?php echo esc_url($url); ?>" class="sidebar-link-row" target="_blank" rel="noopener">
                            <?php echo esc_html($label); ?>
                            <span aria-hidden="true">↗</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- WordPress-Widget-Bereich (optional) -->
            <?php if (is_active_sidebar('team-sidebar')) : ?>
                <?php dynamic_sidebar('team-sidebar'); ?>
            <?php endif; ?>

        </aside>

    </div>

</main>

<?php get_footer(); ?>
