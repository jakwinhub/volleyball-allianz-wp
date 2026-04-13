<?php
/**
 * Template Part: Teamfoto-Galerie
 * Zeigt die Bildergalerie falls Bilder an den Post angehängt sind.
 */

$images = get_attached_media( 'image', get_the_ID() );
if ( empty( $images ) ) return;
?>

<div class="block-title"><?php esc_html_e( 'Fotos', 'volleyball-allianz' ); ?></div>
<div class="team-galerie">
  <?php foreach ( $images as $img ) : ?>
  <a href="<?php echo esc_url( wp_get_attachment_url( $img->ID ) ); ?>" class="galerie-item">
    <?php echo wp_get_attachment_image( $img->ID, 'player-card', false, [ 'class' => 'galerie-img', 'loading' => 'lazy' ] ); ?>
  </a>
  <?php endforeach; ?>
</div>
