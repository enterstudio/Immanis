<?php
the_post();

/**
 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
 */
$attachments = array_values( get_children( array(
	'post_parent' => $post->post_parent,
	'post_status' => 'inherit',
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'order' => 'ASC',
	'orderby' => 'menu_order ID'
) ) );

foreach ( $attachments as $k => $attachment ) :
if ( $attachment->ID == $post->ID )
	break;
endforeach;

$k++;
// If there is more than 1 attachment in a gallery
if ( count( $attachments ) > 1 ) :
	if ( isset( $attachments[ $k ] ) ) :
		// get the URL of the next image attachment
		$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
	else :
		// or get the URL of the first image attachment
		$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	endif;
else :
	// or, if there's only 1 image, get the URL of the image
	$next_attachment_url = wp_get_attachment_url();
endif;

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
				<div class="article-wrapper">
					<div class="entry-meta">
						<?php
							$published_text  = __( '<span class="attachment-meta">Published on <time class="entry-date" datetime="%1$s">%2$s</time> in <a href="%3$s" title="Return to %4$s" rel="gallery">%5$s</a></span>', 'immanis' );
							$post_title = get_the_title( $post->post_parent );
							if ( empty( $post_title ) || 0 == $post->post_parent )
								$published_text  = '<span class="attachment-meta"><time class="entry-date" datetime="%1$s">%2$s</time></span>';

							printf( $published_text,
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() ),
								esc_url( get_permalink( $post->post_parent ) ),
								esc_attr( strip_tags( $post_title ) ),
								$post_title
							);

							$metadata = wp_get_attachment_metadata();
							printf( '<span class="attachment-meta full-size-link"><a href="%1$s" title="%2$s">%3$s (%4$s &times; %5$s)</a></span>',
								esc_url( wp_get_attachment_url() ),
								esc_attr__( 'Link to full-size image', 'immanis' ),
								__( 'Full resolution', 'immanis' ),
								$metadata['width'],
								$metadata['height']
							);

							edit_post_link( __( 'Edit', 'immanis' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-meta -->

					<div class="entry-wrapper">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						
						<div class="entry-content">
							<nav id="image-navigation" class="navigation image-navigation" role="navigation">
								<span class="nav-previous"><?php previous_image_link( false, __( '<span class="meta-nav">‹ </span>Previous', 'immanis' ) ); ?></span>
								<span class="nav-next"><?php next_image_link( false, __( 'Next<span class="meta-nav"> ›</span>', 'immanis' ) ); ?></span>
							</nav><!-- #image-navigation -->

							<div class="entry-attachment">
								<div class="attachment">
									<a href="<?php echo esc_url( $next_attachment_url ); ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php
									$attachment_size = apply_filters( 'immanis_attachment_size', array( 724, 724 ) );
									echo wp_get_attachment_image( $post->ID, $attachment_size );
									?></a>

									<?php if ( ! empty( $post->post_excerpt ) ) : ?>
									<div class="entry-caption">
										<?php the_excerpt(); ?>
									</div>
									<?php endif; ?>
								</div><!-- .attachment -->

							</div><!-- .entry-attachment -->

							<?php if ( ! empty( $post->post_content ) ) : ?>
							<div class="entry-description">
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'immanis' ), 'after' => '</div>' ) ); ?>
							</div><!-- .entry-description -->
							<?php endif; ?>

						</div><!-- .entry-content -->
					</div>

					<div class="clear"></div>
				</div>
			</article><!-- #post -->

			<?php comments_template(); ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>