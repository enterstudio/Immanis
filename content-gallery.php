<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php
$content = get_the_content();
$content = apply_filters( 'the_content', $content );
$content = str_replace( ']]>', ']]&gt;', $content );

$galleries = wpShower::getGalleries();
$attachments = array();
foreach ( $galleries as $gallery )
{
	if ( !isset( $gallery['ids'] ) ) continue;

	$ids = explode( ',', $gallery['ids'] );
	foreach ( $ids as $id )
	{
		$attachments[] = $id;
	}
}
if ( $attachments ) :
?>
	<div class="entry-media-gallery">
		<div class="gallery-wrapper">
			<table>
				<tr>
	<?php foreach ( $attachments as $attachment ) :
		$image = get_post( $attachment );
		$link = $galleries[0]['link'] == 'post' ? true : false;
	?>
					<td data-caption="<?php echo $image->post_excerpt; ?>">
						<?php echo wp_get_attachment_link( $attachment, 'immanis-image', $link ); ?>
					</td>
	<?php endforeach; ?>
				</tr>
			</table>
			<a class="gallery-prev" href="#"></a>
			<a class="gallery-next" href="#"></a>
			<div class="bullets"></div>
		</div>
		<div class="gallery-caption"></div>
	</div><!-- .entry-media -->

<?php endif; ?>

	<div class="article-wrapper">
		<header class="entry-header">
			<span></span>
		</header><!-- .entry-header -->

		<footer class="entry-meta">
			<?php immanis_entry_meta(); ?>

			<?php if ( comments_open() && ! is_single() ) : ?>
			<span class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'immanis' ) . '</span>', __( 'One comment so far', 'immanis' ), __( 'View all % comments', 'immanis' ) ); ?>
			</span><!-- .comments-link -->
			<?php endif; // comments_open() ?>
			<?php edit_post_link( __( 'Edit', 'immanis' ), '<span class="edit-link">', '</span>' ); ?>
			<?php immanis_share(); ?>
		</footer><!-- .entry-meta -->

		<div class="entry-wrapper">
			<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php else : ?>
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'immanis' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
			<?php endif; // is_single() ?>

			<div class="entry-content">
				<?php echo $content; ?>
				<?php if ( is_single() ) : ?>
					<?php immanis_tags(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'immanis' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
				<?php endif; // is_single() ?>
			</div><!-- .entry-content -->
		</div>

		<div class="clear"></div>
	</div>
</article><!-- #post -->

<script type="text/javascript">
jQuery(function() {
	var gallery = new wpshowerGallery('#post-<?php the_ID(); ?>');
});
</script>
