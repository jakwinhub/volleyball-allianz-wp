<?php
/**
 * Template Part: Nächste Spiele – Frontpage-Sektion (CSV basiert)
 */

// 👉 Reihenfolge fest definieren
$teams = [
    'damen-1',
    'herren-1',
    'damen-2',
    'herren-2',
];

// 👉 Spiele sammeln (je Team genau 1 Heimspiel)
$spiele = [];

foreach ($teams as $team) {
    $spiel = va_get_next_home_game_from_csv($team);

    if ($spiel) {
        $spiel['team'] = $team;
        $spiele[] = $spiel;
    }
}
?>

<section class="games-section">
    <div class="section-header">
        <div>
            <div class="section-tag"><?php esc_html_e('Spielplan', 'volleyball-allianz'); ?></div>
            <h2 class="section-h2"><?php esc_html_e('Nächste Heimspiele', 'volleyball-allianz'); ?></h2>
        </div>
        <a href="<?php echo esc_url(home_url('/spielplan/')); ?>" class="section-link">
            <?php esc_html_e('Vollständiger Spielplan →', 'volleyball-allianz'); ?>
        </a>
    </div>

    <div class="games-list">

        <?php if (!empty($spiele)) : ?>

            <?php foreach ($spiele as $spiel) : ?>
                <div class="game-item">

                    <div class="game-date">
                        <div class="day"><?php echo esc_html($spiel['datum']); ?></div>
                    </div>

                    <div class="game-tag">
                        <?php echo esc_html(ucwords(str_replace('-', ' ', $spiel['team']))); ?>
                    </div>

                    <div class="game-match">
                        G.A. Stuttgart
                        <span class="vs">vs</span>
                        <?php echo esc_html($spiel['gegner']); ?>
                    </div>

                    <div class="game-place">
                        <?php echo esc_html(explode('(', $spiel['ort'])[0]); ?>
                    </div>

                    <div class="game-time">
                        <?php echo esc_html($spiel['uhrzeit']); ?> Uhr
                    </div>

                </div>
            <?php endforeach; ?>

        <?php else : ?>
            <p>Keine kommenden Heimspiele gefunden.</p>
        <?php endif; ?>

    </div>
</section>