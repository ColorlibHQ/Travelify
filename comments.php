<?php
/**
 * The template for displaying Comments.
 *
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
			$travelify_comment_count = get_comments_number();

			// Msgids are kept verbatim from earlier releases so the bundled translations keep matching.
			if ( 1 === (int) $travelify_comment_count ) {
				printf(
					/* translators: 1: number of comments, 2: post title. */
					esc_html__( 'One thought on &ldquo;%2$s&rdquo;', 'travelify' ),
					esc_html( number_format_i18n( $travelify_comment_count ) ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title. */
					esc_html__( '%1$s thoughts on &ldquo;%2$s&rdquo;', 'travelify' ),
					esc_html( number_format_i18n( $travelify_comment_count ) ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'travelify_comment', 'style' => 'ol' ) ); ?>
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav class="comment-navigation" aria-label="<?php esc_attr_e( 'Comment navigation', 'travelify' ); ?>">
			<h2 class="assistive-text section-heading"><?php esc_html_e( 'Comment navigation', 'travelify' ); ?></h2>
			<ul class="default-wp-page clearfix">
				<li class="previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'travelify' ) ); ?></li>
				<li class="next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'travelify' ) ); ?></li>
			</ul>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note.
		elseif ( ! comments_open() && 0 !== (int) get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'travelify' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments .comments-area -->
