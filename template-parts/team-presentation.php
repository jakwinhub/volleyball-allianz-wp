<div class="block-title">Mannschaft <?php echo date('Y'); ?></div>

<?php if (has_post_thumbnail()): ?>
    <div class="team-photo">
        <?php the_post_thumbnail('full', ['class' => 'team-photo-img']); ?>
    </div>
<?php else: ?>
    <div class="team-photo">
        <img src="<?php echo get_template_directory_uri(); ?>/images/teams/mannschaftsbild_default.png" alt="Mannschaftsfoto">
    </div>
<?php endif; ?>