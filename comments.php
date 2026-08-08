<?php
/**
 * Custom RTL-First Comments Template (Shabnam Typography & Premium Style)
 * Displays the comments list and comment submission form elegantly.
 *
 * @package Premium_Persian_Tourism
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area" style="margin-top: 40px; font-family: 'Shabnam', sans-serif; direction: rtl; text-align: right;">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title" style="font-size: 20px; font-weight: bold; color: #2D3748; margin-bottom: 25px; border-bottom: 2px solid #EDF2F7; padding-bottom: 10px;">
			<?php
			$comments_number = get_comments_number();
			// Convert to Persian Digits
			$farsi_digits = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
			$eng_digits   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
			$farsi_count  = str_replace( $eng_digits, $farsi_digits, $comments_number );

			if ( '1' === $comments_number ) {
				echo 'یک دیدگاه ثبت شده است';
			} else {
				echo esc_html( $farsi_count ) . ' دیدگاه برای این مطلب ارسال شده است';
			}
			?>
		</h3>

		<ol class="comment-list" style="list-style: none; padding: 0; margin: 0 0 30px 0;">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 60,
				'callback'    => function( $comment, $args, $depth ) {
					$GLOBALS['comment'] = $comment;
					?>
					<li id="li-comment-<?php comment_ID(); ?>" class="comment-item" style="border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 20px; background: #FFF; list-style-type: none;">
						<article id="comment-<?php comment_ID(); ?>" class="comment-body" style="display: flex; gap: 15px; align-items: flex-start;">

							<!-- Avatar Section -->
							<div class="comment-avatar" style="flex-shrink: 0;">
								<?php echo get_avatar( $comment, 60, '', '', array( 'class' => 'img-circle', 'style' => 'border-radius: 50%; border: 2px solid #E2E8F0;' ) ); ?>
							</div>

							<!-- Comment Content Area -->
							<div class="comment-content-area" style="flex-grow: 1;">
								<div class="comment-metadata" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
									<span class="comment-author" style="font-weight: bold; color: #2B6CB0; font-size: 15px;">
										<?php comment_author_link(); ?>
									</span>
									<span class="comment-date" style="font-size: 12px; color: #718096; direction: rtl;">
										<?php comment_date( 'j F Y' ); ?> در ساعت <?php comment_time( 'H:i' ); ?>
									</span>
								</div>

								<div class="comment-text" style="font-size: 14px; line-height: 1.8; color: #4A5568;">
									<?php comment_text(); ?>
								</div>

								<?php if ( '0' === $comment->comment_approved ) : ?>
									<p class="comment-awaiting-moderation" style="font-size: 12px; color: #DD6B20; font-style: italic; margin-top: 5px;">دیدگاه شما پس از تایید مدیریت نمایش داده خواهد شد.</p>
								<?php endif; ?>

								<div class="comment-reply" style="margin-top: 10px; text-align: left;">
									<?php
									comment_reply_link( array_merge( $args, array(
										'reply_text' => 'پاسخ به این دیدگاه',
										'depth'      => $depth,
										'max_depth'  => $args['max_depth'],
										'before'     => '<span class="reply-badge" style="background: #EDF2F7; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block;">',
										'after'      => '</span>',
									) ) );
									?>
								</div>
							</div>

						</article>
					</li>
					<?php
				}
			) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="comment-navigation" style="display: flex; justify-content: space-between; margin-bottom: 30px;">
				<div class="nav-previous"><?php previous_comments_link( '&larr; دیدگاه‌های قدیمی‌تر' ); ?></div>
				<div class="nav-next"><?php next_comments_link( 'دیدگاه‌های جدیدتر &rarr;' ); ?></div>
			</nav>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments" style="text-align: center; color: #718096; font-size: 14px; background: #F7FAFC; padding: 15px; border-radius: 8px;">امکان ثبت دیدگاه برای این مطلب بسته شده است.</p>
	<?php endif; ?>

	<?php
	// Custom Comment Form Styling for RTL
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );

	comment_form( array(
		'class_form'         => 'comment-form',
		'title_reply'        => '✍️ ارسال دیدگاه و تجربه سفر شما',
		'title_reply_to'     => '✍️ ارسال پاسخ به %s',
		'cancel_reply_link'  => 'انصراف از پاسخ',
		'label_submit'       => 'ثبت و ارسال دیدگاه',
		'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" style="background: #3182CE; color: #FFF; font-weight: bold; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.backgroundColor=\'#2B6CB0\'" onmouseout="this.style.backgroundColor=\'#3182CE\'">%4$s</button>',
		'comment_field'      => '<p class="comment-form-comment" style="margin-bottom: 15px;">
									<label for="comment" style="display: block; margin-bottom: 6px; font-weight: bold;">متن دیدگاه شما: <span style="color: #E53E3E;">*</span></label>
									<textarea id="comment" name="comment" cols="45" rows="5" required style="width: 100%; padding: 12px; border: 1px solid #CBD5E0; border-radius: 8px; font-family: \'Shabnam\', sans-serif;" placeholder="تجربه خود در سفر به این منطقه را با بقیه مسافران به اشتراک بگذارید..."></textarea>
								</p>',
		'fields'             => array(
			'author' => '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
							<p class="comment-form-author">
								<label for="author" style="display: block; margin-bottom: 6px; font-weight: bold;">نام و نام خانوادگی: ' . ( $req ? '<span style="color: #E53E3E;">*</span>' : '' ) . '</label>
								<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' style="width: 100%; padding: 10px; border: 1px solid #CBD5E0; border-radius: 8px; font-family: \'Shabnam\', sans-serif;" placeholder="مثال: رضا محمدی" />
							</p>',
			'email'  => '   <p class="comment-form-email">
								<label for="email" style="display: block; margin-bottom: 6px; font-weight: bold;">آدرس ایمیل: ' . ( $req ? '<span style="color: #E53E3E;">*</span>' : '' ) . '</label>
								<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' style="width: 100%; padding: 10px; border: 1px solid #CBD5E0; border-radius: 8px; font-family: \'Shabnam\', sans-serif; text-align: left; direction: ltr;" placeholder="yourname@gmail.com" />
							</p>
						 </div>',
		),
		'comment_notes_before' => '<p class="comment-notes" style="font-size: 13px; color: #718096; margin-bottom: 15px;">نشانی ایمیل شما منتشر نخواهد شد. بخش‌های موردنیاز علامت‌گذاری شده‌اند <span style="color: #E53E3E;">*</span></p>',
		'comment_notes_after'  => '',
	) );
	?>

</div>
