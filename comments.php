<?php
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

	<?php if (have_comments()): ?>
		<h2 class="comments-title">
			<?php
				printf(_nx('1 Comment', '%1$s Comments', get_comments_number(), 'comments title', 'immanis'),
					number_format_i18n(get_comments_number()));
			?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments(array(
					'style' => 'ol',
					'format' => 'html5',
					'short_ping' => true,
					'avatar_size' => 27,
					'callback' => 'immanis_comment',
					'reply_text' => ''
				));
			?>
		</ol><!-- .comment-list -->

		<?php
			// Are there comments to navigate through?
			if (get_comment_pages_count() > 1 && get_option('page_comments')):
		?>
		<nav class="navigation comment-navigation" role="navigation">
			<h1 class="screen-reader-text section-heading"><?php _e('Comment navigation', 'immanis'); ?></h1>
			<div class="nav-previous"><?php previous_comments_link(__('<span class="meta-nav">‹ </span>Older Comments', 'immanis')); ?></div>
			<div class="nav-next"><?php next_comments_link(__('Newer Comments<span class="meta-nav"> ›</span>', 'immanis')); ?></div>
		</nav><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if (!comments_open() && get_comments_number()): ?>
		<p class="no-comments"><?php _e('Comments are closed.', 'immanis'); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(array(
		'format' => 'html5',
		'label_submit' => __('Submit Comment', 'immanis'),
		'title_reply' => 'Leave a Comment',
		'cancel_reply_link' => '&#215; Cancel',
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="Your message"></textarea></p>',
		'fields' => apply_filters('comment_form_default_fields', array(
			'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="'.esc_attr($commenter['comment_author']).'" size="30"'.($req ? ' aria-required="true" placeholder="Your name"' : ' placeholder="Your name"').' /></p>',
			'email' => '<p class="comment-form-email"><input id="email" name="email" type="text" value="'.esc_attr($commenter['comment_author_email']).'" size="30"'.($req ? ' aria-required="true" placeholder="Your email (will not be published)"' : ' placeholder="Your email (will not be published)"').' /></p>',
			'url' => '<p class="comment-form-url"><input id="url" name="url" type="text" value="'.esc_attr($commenter['comment_author_url']).'" size="30" placeholder="Your website" /></p>'
		))
	)); ?>

</div><!-- #comments -->
