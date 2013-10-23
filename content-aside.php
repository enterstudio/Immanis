<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="article-wrapper">
		<footer class="entry-meta">
			<?php immanis_entry_meta(); ?>
			<?php edit_post_link(__('Edit', 'immanis'), '<span class="edit-link">', '</span>'); ?>
			<?php immanis_share(); ?>
		</footer><!-- .entry-meta -->

		<div class="entry-wrapper">
			<div class="entry-content">
				<?php the_content(__('Read more', 'immanis')); ?>
				<?php immanis_tags(); ?>
				<?php if (is_single()) wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'immanis').'</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
			</div><!-- .entry-content -->
		</div>
	</div>
</article><!-- #post -->
