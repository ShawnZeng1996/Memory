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
 * Template Name: 文章归档
 * Template Post Type: page
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
								<div class="post-content-real"><?php memory_archives_list();the_content(); ?></div>
                        	</div>
						</div>                           
                    </article>
                </div>
			<?php endif; ?>
			<?php
				comments_template();
			?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer();