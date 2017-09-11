<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf("留言（".get_comments_number()."条）");
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 42,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php the_comments_navigation(); ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( '本文留言功能已关闭。', 'twentysixteen' ); ?></p>
	<?php endif; ?>

	<?php
		comment_form( array(
			'fields' => array(
				'author' => '<p class="comment-form-author">' . '<label for="author">您的大名:<span class="required">*</span></label> ' .
					'<input id="author" name="author" type="text" value="" size="30" maxlength="245" /></p>',
				'email'  => '<p class="comment-form-email"><label for="email">邮件地址:<span class="required">*</span></label> ' .
					'<input id="email" name="email" type="text" value="" size="30" maxlength="100" aria-describedby="email-notes"  /></p>',
			),
            'class_form' => 'pure-form',
			'title_reply' => '发表留言',
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h2>',
			'label_submit' => '发表留言',
		) );
	?>

</div><!-- .comments-area -->
