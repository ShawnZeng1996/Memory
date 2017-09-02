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
 * Template Name: 说说
 * Template Post Type: post, status
 */
get_header();
setPostViews(get_the_ID());
?>
	<div id="main">
        <div id="main-part">
			<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
			<article class="art-shuoshuo">
                <div class="shuoshuo">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
                    <h4><?php the_author(); ?></h4>
                    <p><?php the_content(); ?></p>
                    <span class="shuoshuo-info">
						<i class="fa fa-calendar"></i>&nbsp;<?php the_time('Y-n-j H:i') ?>
						&nbsp;•&nbsp;
			            <i class="fa fa-commenting-o"></i>
			            <?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>	
					</span>	
					<div class="shuoshuo-edit">
						<span>分享至：</span>
						<div class="social-share" data-sites="weibo,qq,qzone,wechat,tencent"></div>
          				<span class="art-info-readmore">
                        	<?php edit_post_link('编辑', '', ''); ?>
                    	</span>
					</div>			
                </div>				
            </article>
			<?php endif; ?>
			<?php comments_template(); ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>