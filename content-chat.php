<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="article-wrapper">
		<footer class="entry-meta">
			<?php immanis_entry_meta(); ?>
			<?php edit_post_link(__('Edit', 'immanis'), '<span class="edit-link">', '</span>'); ?>
			<?php immanis_share(); ?>
		</footer><!-- .entry-meta -->

		<div class="entry-wrapper">
			<?php if (is_single()): ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php else: ?>
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permalink to %s', 'immanis'), the_title_attribute('echo=0'))); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
			<?php endif; // is_single() ?>

			<div class="entry-content">
				<?php the_post_format_chat(); ?>
				<?php immanis_tags(); ?>
				<?php if (is_single()) wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'immanis').'</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
			</div><!-- .entry-content -->
		</div>

		<div class="clear"></div>
	</div>
</article><!-- #post -->
