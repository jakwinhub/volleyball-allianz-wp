<?php get_header(); ?>
<main id="main" style="margin-top:64px; padding: 80px 80px 64px;" role="main">
  <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header>
        <div class="section-tag" style="margin-bottom:12px;"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></div>
        <h1 style="font-family:'Familjen Grotesk',sans-serif; font-size:clamp(32px,4vw,56px); font-weight:700; color:var(--blue); margin-bottom:32px; line-height:1.1;">
          <?php the_title(); ?>
        </h1>
      </header>
      <div class="entry-content" style="font-size:16px; line-height:1.75; color:var(--text-muted); ">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
