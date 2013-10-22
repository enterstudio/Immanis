<?php
get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if (have_posts()): ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php
					if (is_day()):
						printf(__('Daily Archives: %s', 'immanis'), get_the_date());
					elseif (is_month()):
						printf(__('Monthly Archives: %s', 'immanis'), get_the_date(_x('F Y', 'monthly archives date format', 'immanis')));
					elseif (is_year()):
						printf(__('Yearly Archives: %s', 'immanis'), get_the_date(_x('Y', 'yearly archives date format', 'immanis')));
					else:
						_e('Archives', 'immanis');
					endif;
				?></h1>
			</header><!-- .archive-header -->

			<?php /* The loop */ ?>
			<?php while (have_posts()): the_post(); ?>
				<?php get_template_part('content', get_post_format()); ?>
			<?php endwhile; ?>

			<?php immanis_paging_nav(); ?>

		<?php else: ?>
			<?php get_template_part('content', 'none'); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
