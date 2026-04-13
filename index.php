<?php get_header(); ?>

<main id="main" style="margin-top:64px; ">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <div><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  <?php else : ?>
    <p><?php esc_html_e( 'Es scheint, als wäre Ihr Zuspiel nicht angekommen...', 'volleyball-allianz' ); ?></p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
