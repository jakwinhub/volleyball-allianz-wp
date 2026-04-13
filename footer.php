    <footer id="site-footer" role="contentinfo">

  <div class="footer-left">
    <div class="footer-logo-text">
      <?php echo va_mod( 'footer_name', get_bloginfo( 'name' ) ); ?>
    </div>
    <div class="footer-logo-sub">
      <?php echo va_mod( 'footer_sub', 'TSV Georgii-Allianz e. V. · Stuttgart-Vaihingen' ); ?>
    </div>
  </div>

  <!-- Footer-Navigation -->
  <?php if ( has_nav_menu( 'footer' ) ) : ?>
    <nav class="footer-links" aria-label="<?php esc_attr_e( 'Footer-Navigation', 'volleyball-allianz' ); ?>">
      <?php
      wp_nav_menu( [
        'theme_location' => 'footer',
        'container'      => false,
        'depth'          => 1,
        'fallback_cb'    => false,
        'items_wrap'     => '%3$s',  // kein <ul>, Links direkt
        'walker'         => new VA_Flat_Menu_Walker(),
      ] );
      ?>
    </nav>
  <?php else : ?>
    <div class="footer-links">
      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'impressum' ) ) ); ?>">Impressum</a>
      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'datenschutz' ) ) ); ?>">Datenschutz</a>
      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'kontakt' ) ) ); ?>">Kontakt</a>
      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'satzung' ) ) ); ?>">Satzung</a>
    </div>
  <?php endif; ?>

  <div class="footer-copy">
    <?php echo va_mod( 'footer_copy', '© ' . date( 'Y' ) . ' TSV Georgii-Allianz e. V.' ); ?>
  </div>

</footer>

<?php wp_footer(); ?>