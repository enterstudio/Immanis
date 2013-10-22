<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="article-wrapper">
		<header class="entry-header">
			<span></span>
		</header><!-- .entry-header -->

		<footer class="entry-meta">
			<?php immanis_entry_meta(); ?>

			<?php if (comments_open() && !is_single()): ?>
				<div class="comments-link">
					<?php comments_popup_link('<span class="leave-reply">'.__('Leave a comment', 'immanis').'</span>', __('One comment so far', 'immanis'), __('View all % comments', 'immanis')); ?>
				</div><!-- .comments-link -->
			<?php endif; // comments_open() ?>
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

			<?php if (is_search()): // Only display Excerpts for Search ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
			<?php else: ?>
			<div class="entry-content">
				<?php the_content(__('Read more', 'immanis')); ?>
				<?php immanis_tags(); ?>
				<?php if (is_single()) wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'immanis').'</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
			</div><!-- .entry-content -->
			<?php endif; ?>
		</div>

		<div class="clear"></div>
	</div>
</article><!-- #post -->
