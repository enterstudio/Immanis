<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="article-wrapper">
		<header class="entry-header">
			<span></span>
		</header><!-- .entry-header -->

		<footer class="entry-meta">
			<?php immanis_entry_date(); ?>

			<?php immanis_entry_meta(); ?>

			<?php edit_post_link(__('Edit', 'immanis'), '<span class="edit-link">', '</span>'); ?>
			<?php immanis_share(); ?>
		</footer><!-- .entry-meta -->

		<div class="entry-wrapper">
			<h1 class="entry-title">
				<a href="<?php echo esc_url(immanis_get_link_url()); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
			</h1>

			<div class="entry-content">
				<?php the_content(__('Read more', 'immanis')); ?>
				<?php immanis_tags(); ?>
				<?php if (is_single()) wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'immanis').'</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
			</div><!-- .entry-content -->
		</div>

		<div class="clear"></div>
	</div>
</article><!-- #post -->
