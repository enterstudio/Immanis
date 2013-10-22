<?php get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while (have_posts()): the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="article-wrapper">
						<header class="entry-header">
							<?php if (has_post_thumbnail() && !post_password_required()): ?>
							<div class="entry-thumbnail">
								<?php the_post_thumbnail(); ?>
							</div>
							<?php endif; ?>
						</header><!-- .entry-header -->

						<footer class="entry-meta">
							<?php edit_post_link(__('Edit', 'immanis'), '<span class="edit-link">', '</span>'); ?>
						</footer><!-- .entry-meta -->

						<div class="entry-wrapper">
							<h1 class="entry-title"><?php the_title(); ?></h1>

							<div class="entry-content">
								<?php the_content(); ?>
								<?php wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">'.__('Pages:', 'immanis').'</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>')); ?>
							</div><!-- .entry-content -->
						</div>

						<div class="clear"></div>
					</div>
				</article><!-- #post -->
			<?php endwhile; ?>
			<?php comments_template(); ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
