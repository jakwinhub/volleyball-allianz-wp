<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header" role="banner">

  <!-- Logo / Branding -->
  <a class="site-branding" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
    <?php if ( has_custom_logo() ) : ?>
      <?php the_custom_logo(); ?>
    <?php else : ?>
      <div class="site-logo-mark" aria-hidden="true">AV</div>
      <div class="site-name-wrap">
        <span class="site-name"><?php bloginfo( 'name' ); ?></span>
        <span class="site-tagline"><?php bloginfo( 'description' ); ?></span>
      </div>
    <?php endif; ?>
  </a>

    <!-- Mobile Menu Toggle -->
    <button id="menu-toggle" aria-label="Menü umschalten">☰</button>


    <!-- Hauptnavigation -->
  <nav id="site-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Hauptmenü', 'volleyball-allianz' ); ?>">
    <?php
    wp_nav_menu( [
      'theme_location' => 'primary',
      'menu_id'        => 'primary-menu',
      'container'      => false,
      'fallback_cb'    => false,
    ] );
    ?>
  </nav>

  <!-- CTA-Button -->
  <a class="nav-cta" href="<?php echo esc_url( get_permalink( get_page_by_path( 'mitmachen' ) ) ); ?>">
    <?php esc_html_e( 'Mitglied werden', 'volleyball-allianz' ); ?>
  </a>

</header>