<?php
$spieler = get_posts([
    'post_type' => 'spieler',
    'numberposts' => -1,
    'meta_query' => [
        [
            'key' => 'va_team_id',
            'value' => get_the_ID(),
            'compare' => '='
        ]
    ]
]);

if (!$spieler) return;

/* Gruppieren */
$gruppen = [];

foreach ($spieler as $s) {
    $pos = strtolower(trim(get_post_meta($s->ID, 'va_position', true))) ?: 'sonstige';
    $gruppen[$pos][] = $s;
}

/* 🔥 Wunsch-Reihenfolge */
$positions_order = [
    'zuspiel',
    'außen',
    'mitte',
    'universal',
    'libero'
];

/* 🔥 Gruppen sortieren */
uksort($gruppen, function ($a, $b) use ($positions_order) {

    $posA = array_search($a, $positions_order);
    $posB = array_search($b, $positions_order);

    $posA = ($posA === false) ? 999 : $posA;
    $posB = ($posB === false) ? 999 : $posB;

    return $posA <=> $posB;
});

/* 🔥 Spieler innerhalb der Gruppe sortieren (nach Nummer) */
foreach ($gruppen as &$liste) {
    usort($liste, function ($a, $b) {
        $numA = (int)get_post_meta($a->ID, 'va_number', true);
        $numB = (int)get_post_meta($b->ID, 'va_number', true);

        return $numA <=> $numB;
    });
}
unset($liste);
?>

    <div class="block-title">Kader <?php echo date('Y'); ?></div>

<?php foreach ($gruppen as $position => $liste): ?>

    <div class="squad-group">
        <div class="squad-group-title">
            <?php echo esc_html(ucfirst($position)); ?>
        </div>

        <div class="squad-grid">
            <?php foreach ($liste as $p):

                $number = get_post_meta($p->ID, 'va_number', true);
                $captain = get_post_meta($p->ID, 'va_captain', true);
                ?>

                <div class="player-card">

                    <div class="player-img-wrap">
                        <?php
                        if (has_post_thumbnail($p->ID)) {
                            echo get_the_post_thumbnail($p->ID, 'player-card');
                        } else {
                            echo '<img src="' . get_template_directory_uri() . '/images/default-player.png" alt="Spieler">';
                        }
                        ?>
                    </div>

                    <div class="player-num">#<?php echo esc_html($number); ?></div>

                    <div class="player-name">
                        <?php echo esc_html($p->post_title); ?>

                        <?php if ($captain): ?>
                            <span class="player-badge">C</span>
                        <?php endif; ?>
                    </div>

                </div>

            <?php endforeach; ?>
        </div>
    </div>

<?php endforeach; ?>