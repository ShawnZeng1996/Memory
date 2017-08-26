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
<div class="comment-part">
	<div class="comment-respond" id="respond">
		<h3 id="reply-title" class="comment-title comment-reply-title">
	         <i class="fa fa-comments-o fa-fw"></i>&nbsp;来一发
			 <small><?php cancel_comment_reply_link('放弃治疗') ?></small>
		</h3>
		<?php
			if ( !comments_open() ) :
			// If registration required and not logged in.
			elseif ( get_option('comment_registration') && !is_user_logged_in() ) :
		?>
		<p>你必须 <a href="<?php echo wp_login_url( get_permalink() ); ?>">登录</a> 才能发表评论！</p>
		<?php else  : ?>
		<form name="comment-form" class="comment-form" id="comment-form" method="post" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php">
			<textarea class="text-input" id="comment-content" name="comment" rows="8" cols="45" aria-required="true" placeholder="|´・ω・)ノ你想和我说什么呀poi~"></textarea>
			<div class="OwO"></div>
	          	<div id="comment-info">
				<?php if ( !is_user_logged_in() ) : ?>
	               	<input type="text" name="author" value="" class="text-input" id="comment-author" placeholder="昵称 *">
	               	<input type="text" name="email" value="" class="text-input" id="comment-email" placeholder="邮箱 *">
	              	<input type="text" name="url" value="" class="text-input" id="comment-url" placeholder="网址">
	          	<?php else : ?>
					<p>您已登录:<a class="have-login-name" href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a class="log-out-now" href="<?php echo wp_logout_url(get_permalink()); ?>" title="退出登录"><i class="fa fa-sign-out"></i> 退出</a></p>
				<?php endif; ?>
				</div>
	   		<div class="form-submit">
	           	<input type="submit" name="submit" class="comment-submit" value="点击发射朝鲜火箭！">
	       	</div>
			<?php comment_id_fields(); ?>
	    	<?php do_action('comment_form', $post->ID); ?>
	  	</form>
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/OwO.min.js"></script>
		<script>
        	var OwO_demo = new OwO({
            	logo: 'OωO表情',
            	container: document.getElementsByClassName('OwO')[0],
            	target: document.getElementsByClassName('text-input')[0],
            	api: '/wp-content/themes/Memory/OwO/OwO.min.json',
            	position: 'down',
            	width: '100%',
            	maxHeight: '250px'
        	});
    	</script>
		<?php endif; ?>
	</div>    
	<div id="comments">
		<h3 class="comment-title" id="comments-area">
	       	<i class="fa fa-hand-lizard-o fa-rotate-270 fa-fw"></i>&nbsp;活捉<?php comments_popup_link('0', '1', '%', '', '<a>0</a>'); ?>条
	   	</h3>
	   	<ol class="art-comments">
		<?php
	    	if ( !comments_open() ) {
		?>
			<p class="center">评论功能已经关闭!</p>
		<?php
	    	} else if ( !have_comments() ) {
		?>
			<p class="center">还没有任何评论，你来说两句吧!</p>
		<?php
	    	} else {
	       		wp_list_comments('type=comment&callback=memory_comment');
	    	}
		?>
	  	</ol>
		<?php
	 		if (get_option('page_comments')) {
				$comment_pages = paginate_comments_links('echo=0');		// 获取评论分页的 HTML
				if ($comment_pages) {		// 如果评论分页的 HTML 不为空, 显示上一页和下一页的链接
		?>
			<div class="commentnavi">
				<?php paginate_comments_links('prev_text=上一页&next_text=下一页');?>
			</div>
		<?php   } 
          	} ?>
	</div>
</div>