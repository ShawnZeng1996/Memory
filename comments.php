<?php
/**
 * ┌─┐┬ ┬┌─┐┬ ┬┌┐┌┌─┐┌─┐┌┐┌┌─┐ ┌─┐┌─┐┌┬┐
 * └─┐├─┤├─┤││││││┌─┘├┤ ││││ ┬ │  │ ││││
 * └─┘┴ ┴┴ ┴└┴┘┘└┘└─┘└─┘┘└┘└─┘o└─┘└─┘┴ ┴
 *
 * @package WordPress
 * @Theme Memory
 *
 * @author admin@shawnzeng.com
 * @link https://shawnzeng.com
 */
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('请不要直接打开评论模块！');
?>
<div class="memory-item comment-part">
	<a id="go-to-comment" href="#comments"><i class="memory memory-comments"></i></a>
	<header class="memory-item-header"><h3 class="memory-item-title"><i class="memory memory-comments"></i> 评论</h3></header>
	<div class="comment-respond" id="respond">
		<?php if ( !comments_open() ) : elseif ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
			<p class="login-must">你必须 <a href="<?php echo wp_login_url( get_permalink() ); ?>">登录</a> 才能发表评论！</p>
        <?php else  : ?>
		<form name="comment-form" class="comment-form" id="comment-form" method="post" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php">
			<div class="comment-text">
			<?php if ( !is_user_logged_in() ) : ?>
				<div class="commentator">
					<?php if (isset($_COOKIE['comment_author_email_'.COOKIEHASH])) { 
						$comment_author_email = $_COOKIE['comment_author_email_'.COOKIEHASH];
						echo get_avatar($comment_author_email, 48);
					} else { 
						$unique_id = cs_get_option('memory_comment_avatar'); 
						$attachment = wp_get_attachment_image_src( $unique_id, 'full' ); 
						$image_url = ($attachment) ? $attachment[0] : $unique_id; 
					?>
					<img src="<?php print_r($image_url); ?>" class="avatar"/>
					<?php } ?>
				</div>
				<div class="comment-input">
					<input type="text" name="author" value="<?php if (isset($_COOKIE['comment_author_'.COOKIEHASH])) {$comment_author = $_COOKIE['comment_author_'.COOKIEHASH];echo $comment_author;} ?>" class="text-input text-top" id="comment-author" placeholder="昵称 *">
					<input type="text" name="email" value="<?php if (isset($_COOKIE['comment_author_email_'.COOKIEHASH])) {$comment_author_email = $_COOKIE['comment_author_email_'.COOKIEHASH];echo $comment_author_email;} ?>" class="text-input text-top" id="comment-email" placeholder="邮箱 *">
					<input type="text" name="url" value="<?php if (isset($_COOKIE['comment_author_url_'.COOKIEHASH])) {$comment_author_url = $_COOKIE['comment_author_url_'.COOKIEHASH];echo $comment_author_url;} ?>" class="text-input" id="comment-url" placeholder="网址">
					<?php Memory_protection_math(); ?>
				</div>
			<?php else : ?>
				<div class="commentator">
					<?php global $current_user;wp_get_current_user();echo get_avatar( $current_user->user_email, 48); ?>
				</div>
				<div class="comment-login">		
					<p>您已登录: <a class="have-login-name" href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a> <a class="log-out-now no-pjax" href="<?php echo wp_logout_url(get_permalink()); ?>" title="退出登录">退出</a></p>
					<p>您在本站已经留下了<?php global $user_ID; echo get_comments('count=true&user_id='.$user_ID); ?>条评论。</p>
				</div>				
        	<?php endif; ?>
				<div class="comment-s">
					<textarea class="text-input error" id="comment" name="comment" rows="8" cols="45" aria-required="true" placeholder="一言：<?php hitokoto(); ?>"></textarea>
					<div class="OwO no-touch"></div>
					<button type="submit" name="submit" class="comment-submit push-status">发表评论</button>
					<span class="comment-cancel"><?php cancel_comment_reply_link('放弃治疗') ?></span>
				</div>
			</div>
			<?php comment_id_fields(); ?>
			<?php do_action('comment_form', $post->ID); ?>
		</form>                      
        <?php endif; ?>
	</div>
	<div id="comments">
		<?php
		if ( !comments_open() ) {
		?>
		<ol class="memory-comments-area"><p class="center"><i class="memory memory-error"></i> 评论功能已经关闭!</p></ol>
		<?php
		} else if ( !have_comments() ) {
		?>
		<ol class="memory-comments-area"><p class="center no-comment"><i class="memory memory-sofa"></i> 还没有任何评论，你来说两句吧!</p></ol>
		<?php } else { ?>
		<ol class="memory-comments-area">
			<?php wp_list_comments('type=comment&callback=memory_comment'); ?>
		</ol>
		<?php } ?>

		<?php if (get_option('page_comments')) { ?>
		<div id="pagination">
			<div class="memory-comments-page">
				 <?php $comment_pages = paginate_comments_links('prev_text=上一页&next_text=下一页&echo=8'); ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>