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
get_header();
setPostViews(get_the_ID());
?>
   	<div id="main">
        <div id="main-part">
			<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
			<article class="shuoshuo">
                <?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
				<div class="right-box">
			    	<h3 class="shuoshuo-author"><?php the_author(); ?>
						<i class="fa fa-vcard"></i>
					 	<span class="normal">发布了一条说说</span>			
					</h3>
					<span class="shuoshuo-publish">
						<span class="shuoshuo-time">
							<i class="fa fa-calendar"></i>&nbsp;<?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . '前'; ?>
						</span>&nbsp;•&nbsp;
						<span class="art-info-view">
			                <i class="fa fa-eye"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?>
			            </span>&nbsp;•&nbsp;
			            <span class="art-info-comment">
			                <i class="fa fa-comment-o"></i>&nbsp;<?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>
			            </span>
						<span class="art-info-edit">
						<?php edit_post_link('编辑', '&nbsp;•&nbsp;&nbsp;', ''); ?>
						</span>
					</span>
				</div>
				<div class="shuoshuo-content">
			    	<p><?php the_content(); ?></p>
				</div>
				<div class="shuoshuo-info">
						<span class="post-like">
         					<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite<?php if(isset($_COOKIE['memory_ding_'.$post->ID])) echo ' done';?>"><span class="count">
           					<?php if( get_post_meta($post->ID,'memory_ding',true) ){
                    			echo get_post_meta($post->ID,'memory_ding',true);
                 			} else {
                    			echo '0';
                 			}?></span>
        					</a>
 						</span>
						<span class="share">
							分享
						</span>
						<span class="dashang">
							打赏
						</span>
					</div>	
					<div class="social-share" data-sites="<?php if( get_option('memory_share')!=null ) echo get_option('memory_share'); ?>"></div>
					<script src="<?php bloginfo('template_url'); ?>/js/jquery.share.min.js"></script>
					<?php memory_donate(); ?>			
            </article>
			<?php endif; ?>
			<?php comments_template(); ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>