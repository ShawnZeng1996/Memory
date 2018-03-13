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
?>
   	<div id="main">
        <div id="main-part">
			<ul class="posts-list">
				<?php if ( have_posts() ) { ?>
					<?php while ( have_posts() ) { the_post(); global $post; ?>
						<?php if( $post->post_type== 'post' ) { ?>
			                <li>
			                    <article class="art">
									<?php if( is_sticky() ) echo '<div class="set-top"><div><i class="fa fa-thumb-tack"></i></div></div>'; ?>
									<header class="art-header">
									<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
									<div class="right-box">
										<h3 class="article-author"><?php the_author(); ?>
											<i class="fa fa-vcard"></i>
					 						<span class="normal">发布了一篇文章</span>		
										</h3>
										<h3 class="article-title"><a href="<?php the_permalink(); ?>" rel="bookmark">「<?php the_title(); ?>」</a></h3>
										<div class="art-info">
			                                <span class="art-info-date">
			                                    <i class="fa fa-calendar"></i>&nbsp;<?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . '前'; ?>
											</span>&nbsp;•&nbsp;
											<span class="art-info-category">
												<i class="fa fa-archive"></i>&nbsp;<?php the_category( ', ' ); ?>
											</span>&nbsp;•&nbsp;
			                                <span class="art-info-tag"><i class="fa fa-tags"></i>&nbsp;<?php the_tags('', ', ', ''); ?></span>&nbsp;•&nbsp;
											<span class="art-info-view">
			                                    <i class="fa fa-eye"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?>
			                                </span>&nbsp;•&nbsp;
			                                <span class="art-info-comment">
			                                    <i class="fa fa-comment-o"></i>&nbsp;<?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>
			                                </span>
			                            </div>
									</div>
									</header>
									<div class="art-main">			                            
			                            <div class="art-content">
											<?php if ( has_post_thumbnail() ) {
												the_post_thumbnail();
											}
											the_excerpt();
											?>
			                            </div>
			                            <div class="article-info info-index">
											<span class="post-like">
         										<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite<?php if(isset($_COOKIE['memory_ding_'.$post->ID])) echo ' done';?>"><span class="count">
           										<?php if( get_post_meta($post->ID,'memory_ding',true) ){
                    								echo get_post_meta($post->ID,'memory_ding',true);
                 								} else {
                    								echo '0';
                 								}?></span>
        										</a>
 											</span>
			                                <span class="art-info-readmore">
			                                    <a href="<?php the_permalink(); ?>">
			                                        <i class="fa fa-ellipsis-h"></i> 阅读全文
			                                    </a>
			                                </span>
			                            </div>
			                        </div>
			                    </article>
			                </li>
						<?php } ?>
						<?php if( $post->post_type== 'shuoshuo') { ?>
			                <li>
			                    <article class="shuoshuo">
									<?php if( is_sticky() ) echo '<div class="set-top"><div><i class="fa fa-thumb-tack"></i></div></div>'; ?>
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
										</span>
									</div>
									<div class="shuoshuo-content">
			                            <p><?php the_content(); ?></p>
			                        </div>
			                            <div class="shuoshuo-info info-index">
											<span class="post-like">
         										<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite<?php if(isset($_COOKIE['memory_ding_'.$post->ID])) echo ' done';?>"><span class="count">
           										<?php if( get_post_meta($post->ID,'memory_ding',true) ){
                    								echo get_post_meta($post->ID,'memory_ding',true);
                 								} else {
                    								echo '0';
                 								}?></span>
        										</a>
 											</span>
			                                <span class="art-info-readmore">
			                                    <a href="<?php the_permalink(); ?>">
			                                        <i class="fa fa-ellipsis-h"></i> 参与讨论
			                                    </a>
			                                </span>
			                            </div>
			                    </article>
			                </li>
						<?php } ?>
					<?php } ?>
				<?php } ?>
            </ul>
			<?php
				memory_page_navi();
			?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>