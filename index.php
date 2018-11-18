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
get_header(); ?>
   	<div id="main">
        <div id="main-part">
			<ul class="posts-list">
				<?php if ( have_posts() ) { ?>
					<?php while ( have_posts() ) { the_post(); global $post; ?>
						<?php if( $post->post_type== 'post' ) { ?>
			                <li class="memory-item">
			                    <article class="post post-type">
									<?php if( is_sticky() ) _e('<div class="set-top"><div>置顶</div></div>','Memory'); ?>
									<header class="post-type-header">
										<?php if ( has_post_thumbnail() ) { ?>
											<div class="thumbnail-link" style="background-image:url('<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>');"></div>
										<?php } else { ?>
											<div class="thumbnail-link" style="background-image:url('<?php $unique_id = cs_get_option('memory_post_image'); $attachment = wp_get_attachment_image_src( $unique_id, 'full' ); $image_url = ($attachment) ? $attachment[0] : $unique_id; print_r($image_url);?>')"></div>
										<?php } ?>
										<div class="post-relative">
											<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
											<span class="post-type-author"><?php echo the_author_posts_link(); ?> <i class="memory memory-certify"></i><span class="publish-time"> - <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); _e('前'); ?></span>
											</span>
											<h3 class="post-type-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
										</div>
									</header>
									<div class="post-main post-type-main">
			                            <div class="post-content">
											<div class="post-tag"><?php the_tags('', ' ', ''); ?></div>
											<div class="post-excerpt"><?php Memory_excerpt( 160, '……' ); ?></div>
											<div class="post-category"><?php _e('来自分类：');the_category( ' / ' ); ?></div>
			                            </div>
			                        </div>
			                        <div class="post-footer">
		                                <span class="post-view">
		                                    <i class="memory memory-view"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?>
		                                </span>
		                                <span class="post-comments">
		                                    <i class="memory memory-comment"></i>&nbsp;<?php if ( post_password_required() ) { echo '<a>密码保护</a>'; } else { comments_popup_link('0', '1', '%', '', '评论已关闭'); } ?>
		                                </span>
		                                <span class="post-like">
											<a href="javascript:;" data-action="memory_like" data-id="<?php the_ID(); ?>" class="like<?php if(isset($_COOKIE['memory_like_'.$post->ID])) echo ' have-like';?>">
												<span class="like-count"><?php if( get_post_meta($post->ID,'memory_like',true) ){ echo get_post_meta($post->ID,'memory_like',true); } else { echo '0'; }?></span>
    										</a>
										</span>
		                                <span class="post-readmore">
		                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		                                        <i class="memory memory-more-o"></i> 阅读
		                                    </a>
		                                </span>
			                        </div>
			                    </article>
			                </li>
						<?php } else if( $post->post_type== 'shuoshuo' ) { ?>
			                <li class="memory-item">
			                    <article class="shuoshuo post-type">
									<?php if( is_sticky() ) _e('<div class="set-top"><div>置顶</div></div>','Memory'); ?>
									<header class="post-type-header">
										<div class="post-relative">
											<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
											<span class="post-type-author"><?php echo the_author_posts_link(); ?> <i class="memory memory-certify"></i><span class="publish-time"> - <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); _e('前'); ?></span>
											</span>
											<span class="post-publish">发布了一条说说</span>
										</div>
									</header>
									<div class="post-main post-type-main">
			                            <div class="post-content">
											<div class="post-tag post-content"><?php the_tags('', ' ', ''); the_content(); ?></div>
										</div>
			                        </div>
			                        <div class="post-footer">
		                                <span class="post-view">
		                                    <i class="memory memory-view"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?>
		                                </span>
		                                <span class="post-comments">
		                                    <i class="memory memory-comment"></i>&nbsp;<?php if ( post_password_required() ) { echo '<a>密码保护</a>'; } else { comments_popup_link('0', '1', '%', '', '评论已关闭'); } ?>
		                                </span>
		                                <span class="post-like">
											<a href="javascript:;" data-action="memory_like" data-id="<?php the_ID(); ?>" class="like<?php if(isset($_COOKIE['memory_like_'.$post->ID])) echo ' have-like';?>">
												<span class="like-count"><?php if( get_post_meta($post->ID,'memory_like',true) ){ echo get_post_meta($post->ID,'memory_like',true); } else { echo '0'; }?></span>
    										</a>
										</span>
		                                <span class="post-readmore">
		                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		                                        <i class="memory memory-more-o"></i> 阅读
		                                    </a>
		                                </span>
			                        </div>
			                    </article>
			                </li>
						<?php } else if (  $post->post_type== 'douban' ) { ?>
							<li>
								<article class="art">
									<?php if( is_sticky() ) echo '<div class="set-top"><div>置顶</div></div>'; ?>
									<header class="art-header">
									<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
										<h3 class="article-author"><?php echo the_author_posts_link(); ?>
											<i class="memory memory-certify"></i>	
											<span class="normal">发布了一篇<?php if( strlen(get_post_meta( get_the_ID(), '_douban_id', true )) < 9 ) { echo '影评'; } else { echo '书评'; } ?></span>
										</h3>
										<h3 class="article-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
										<div class="art-info">
			                                <span class="art-info-date">
			                                    <i class="memory memory-calendar"></i>&nbsp;<?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . '前'; ?>
											</span>&nbsp;•&nbsp;
											<span class="art-info-view">
			                                    <i class="memory memory-view"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?>
			                                </span>&nbsp;•&nbsp;
			                                <span class="art-info-comment">
			                                    <i class="memory memory-comment-1"></i>&nbsp;<?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>
			                                </span>
			                            </div>
									</header>
									<div class="art-main">			                            
			                            <?php echo '<div id="db'.get_post_meta( get_the_ID(), '_douban_id', true ).'" db-id="'.get_post_meta( get_the_ID(), '_douban_id', true ).'" db-score="'.get_post_meta( get_the_ID(), '_douban_score', true ).'" class="douban_item post-preview"></div>'; ?>
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
			                                        <i class="memory memory-more-o"></i> 阅读全文
			                                    </a>
			                                </span>
			                            </div>
			                        </div>
			                    </article>
							</li>
						<?php } ?>
					<?php } ?>
				<?php } ?>
            </ul>
			<div id="index-pagination">
				<?php if( get_next_posts_link() ) { ?>
				<?php next_posts_link(__('<div class="page-more">(｡・`ω´･)点我加载更多</div>')); ?>
				<?php } else { echo '<div class="page-more">你已到达了世界的尽头(｡・`ω´･)！</div>'; } ?>  
			</div>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); 