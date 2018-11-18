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
setPostViews(get_the_ID()); ?>
   	<div id="main">
        <div id="main-part">
			<?php if(function_exists('memory_breadcrumbs') and cs_get_option( 'memory_breadcrumbs' )==1) { ?>
				<div class="memory-item breadcrumbs">当前位置：
					<?php memory_breadcrumbs();?>
				</div>
			<?php } ?>
			<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
                <div class="memory-item">
                    <article class="post post-type real-post">
						<header class="post-type-header">
							<?php if ( has_post_thumbnail() ) { ?>
							<div class="thumbnail-link" style="background-image:url('<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>');"></div>
							<?php } else { ?>
							<div class="thumbnail-link" style="background-image:url('<?php $unique_id = cs_get_option('memory_post_image'); $attachment = wp_get_attachment_image_src( $unique_id, 'full' ); $image_url = ($attachment) ? $attachment[0] : $unique_id; print_r($image_url);?>')"></div>
							<?php } ?>
							<div class="thumbnail-shadow"><h1 class="post-title"><?php the_title(); ?></h1><div class="post-info"><span class="post-view"><i class="memory memory-view"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?></span>&nbsp;•&nbsp;<span class="post-comments"><i class="memory memory-comment"></i>&nbsp;<?php if ( post_password_required() ) { echo '<a>密码保护</a>'; } else { comments_popup_link('0', '1', '%', '', '评论已关闭'); } ?></span><span class="post-edit"><?php edit_post_link('编辑', '&nbsp;•&nbsp;<i class="memory memory-edit"></i>&nbsp;', ''); ?></span></div></div>
							<div class="post-relative">
								<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
								<span class="post-type-author"><?php echo the_author_posts_link(); ?> <i class="memory memory-certify"></i></span>
								<span class="post-publish"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); _e('前'); ?></span>	
							</div>	
						</header>
						<div class="post-main post-type-main">
                            <div class="post-content">
								<div class="post-tag"><?php the_tags('', ' ', ''); ?></div>
								<div class="post-content-real"><?php the_content(); ?></div>
                        	</div>
							<div class="social-share">
								<a href="javascript:;" class="social-share-icon memory-copy"></a>
							</div>
							<div class="like-pay">
								<span class="post-like"><a href="javascript:;" data-action="memory_like" data-id="<?php the_ID(); ?>" class="like<?php if(isset($_COOKIE['memory_like_'.$post->ID])) echo ' have-like';?>"> <span class="like-count"><?php if( get_post_meta($post->ID,'memory_like',true) ){ echo get_post_meta($post->ID,'memory_like',true); } else { echo '0'; }?></span></a></span>
								<span class="post-pay"><i class="memory memory-dashang"></i> 赏</span>
							</div>
							<div class="pay-box">
								<div class="pay-header">
									<span>请作者吃个鸡腿！</span>
									<i class="memory memory-close"></i>
								</div>
								<div class="pay-body">
									<?php 
									$alipay_image_id = cs_get_option( 'memory_alipay_image' );
									$alipay_attachment = wp_get_attachment_image_src( $alipay_image_id, 'full' );
									$wechat_image_id = cs_get_option( 'memory_wechat_image' );
									$wechat_attachment = wp_get_attachment_image_src( $wechat_image_id, 'full' );
									if( cs_get_option( 'memory_alipay_image' ) && cs_get_option( 'memory_wechat_image' ) ){ ?>
									<h4>扫一扫支付</h3>
									<img class="alipay chosen" src="<?php echo $alipay_attachment[0]; ?>"/>
									<img class="wechatpay" src="<?php echo $wechat_attachment[0]; ?>"/>
									<div class="pay-chose">
										<a class="alibutton chosen"><img src="<?php bloginfo('template_url'); ?>/img/alipay.png"/></a>
										<a class="wechatbutton"><img src="<?php bloginfo('template_url'); ?>/img/wechat.png"/></a>
									</div>											
									<?php } else if ( cs_get_option( 'memory_alipay_image' ) && !cs_get_option( 'memory_wechat_image' ) ) { ?>
									<h4>扫一扫支付</h3>
									<img class="alipay chosen" src="<?php echo $alipay_attachment[0]; ?>"/>											
									<?php } else if ( !cs_get_option( 'memory_alipay_image' ) && cs_get_option( 'memory_wechat_image' ) ) { ?>
									<h4>扫一扫支付</h3>
									<img class="wechatpay chosen" src="<?php echo $wechat_attachment[0]; ?>"/>												
									<?php } else { ?>
									<h4>作者尚未添加打赏二维码！</h3>
									<?php } ?>
								</div>
							</div>
						</div>                          
                    </article>
                </div>
			<?php endif; ?>
			<div class="memory-item nearby-article">
				<div class="nearby-article-left"><?php if (get_previous_post()) { previous_post_link(' %link','‹ 上一篇',false);} else { echo "已经是最后文章啦";} ?></div>    
				<div class="nearby-article-right"><?php if (get_next_post()) { next_post_link(' %link','下一篇 ›',false);} else { echo "已经是最新文章啦";} ?></div>    
			</div>
			<?php
				comments_template();
			?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer();