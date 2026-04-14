<?php
/**
 * Template Part: Nächste Spiele (Sidebar)
 */
$team_post = get_post(get_the_ID());
$team_id = $team_post->ID;

$spiele = va_get_team_games($team_id);

if ( empty( $spiele ) ) return;
?>

<div class="sidebar-box">
    <div class="sidebar-box-head">
        <?php esc_html_e( 'Nächste Spiele', 'volleyball-allianz' ); ?>
    </div>

    <?php foreach ( array_slice( $spiele, 0, 3 ) as $spiel ) : ?>
        <div class="sidebar-game">

            <div class="sidebar-game-date">
                <?php echo esc_html( $spiel['datum'] ); ?>
            </div>

            <div class="sidebar-game-match">
        <span class="team-meta-label" style="color: var(--text-muted); font-size: 10px;">
          <?php echo $spiel['heimspiel'] ? 'HEIM' : 'AUSWÄRTS'; ?>
        </span><br>

                <strong>vs <?php echo esc_html( $spiel['gegner'] ); ?></strong>
            </div>

            <div class="sidebar-game-info">
                <?php echo esc_html( $spiel['uhrzeit'] ); ?> Uhr ·
                <?php echo esc_html( $spiel['ort'] ); ?>
            </div>

        </div>
    <?php endforeach; ?>

    <?php if ( va_meta_url( 'va_link_spielplan' ) ) : ?>
        <a href="<?php echo va_meta_url( 'va_link_spielplan' ); ?>" class="sidebar-link-row" target="_blank">
            Zum Spielplan <span>↗</span>
        </a>
    <?php endif; ?>

</div>