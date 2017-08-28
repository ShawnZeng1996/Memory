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
						<?php $post_format = memory_get_post_format(); ?>
						<?php if( $post_format != 'status' ) { ?>
			                <li>
			                    <article class="art">
									<?php if ( has_post_thumbnail() ) { ?>
			                        	<span class="round-category">
			                            	<div class="round-category-n"><span class="category-circle"></span><?php the_category( ', ' ); ?></div>
			                        	</span>
			                        	<div class="art-pic">
											<?php the_post_thumbnail(); ?>
			                        	</div>
									<?php } ?>
			                        <div class="art-main">
			                            <h3 class="art-title">
			                                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php if( is_sticky() ) echo '<span>[置顶]</span>';the_title(); ?></a>
			                            </h3>
			                            <div class="art-info">
			                                <span class="art-info-author">
			                                    <i class="fa fa-user"></i>&nbsp;<?php the_author(); ?>
			                                </span>&nbsp;•&nbsp;
			                                <span class="art-info-date">
			                                    <i class="fa fa-calendar"></i>&nbsp;<?php the_time('Y-n-j H:i') ?>
											</span>&nbsp;•&nbsp;
			                                <span class="art-info-tag">
			                                    <i class="fa fa-tags"></i>&nbsp;<?php the_tags('', ', ', ''); ?>
			                                </span>
			                                </span>&nbsp;•&nbsp;
			                                <span class="art-info-view">
			                                    <i class="fa fa-eye"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?>
			                                </span>&nbsp;•&nbsp;
			                                <span class="art-info-comment">
			                                    <i class="fa fa-commenting-o"></i>&nbsp;<?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>
			                                </span>&nbsp;•&nbsp;
											<span class="post-like">
         										<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite<?php if(isset($_COOKIE['bigfa_ding_'.$post->ID])) echo ' done';?>"><span class="count">
           										<?php if( get_post_meta($post->ID,'bigfa_ding',true) ){
                    								echo get_post_meta($post->ID,'bigfa_ding',true);
                 								} else {
                    								echo '0';
                 								}?></span>
        										</a>
 											</span>
			                            </div>
			                            <div class="art-content">
			                                <?php 
												the_excerpt();
											?>
			                            </div>
			                            <div class="text-right">
			                                <span class="art-info-readmore">
			                                    <a href="<?php the_permalink(); ?>">
			                                        阅读全文 <i class="fa fa-angle-double-right"></i>
			                                    </a>
			                                </span>
			                            </div>
			                        </div>
			                    </article>
			                </li>
						<?php } ?>
						<?php if( $post_format == 'status' ) { ?>
			                <li>
			                    <article class="art-shuoshuo">
			                        <div class="shuoshuo">
			                            <?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
			                            <h4><?php the_author(); ?></h4>
			                            <p><?php the_content(); ?></p>
			                            <span class="shuoshuo-info">
			                                <i class="fa fa-calendar"></i>
			                                <?php the_time('Y-n-j H:i'); ?>
			                                &nbsp;•&nbsp;
			                                <i class="fa fa-commenting-o"></i>
			                                <?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>
											&nbsp;•&nbsp;
											<span class="post-like">
         										<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite<?php if(isset($_COOKIE['bigfa_ding_'.$post->ID])) echo ' done';?>"><span class="count">
           										<?php if( get_post_meta($post->ID,'bigfa_ding',true) ){
                    								echo get_post_meta($post->ID,'bigfa_ding',true);
                 								} else {
                    								echo '0';
                 								}?></span>
        										</a>
 											</span>
			                            </span>
			                            <div class="text-right">
			                                <span class="art-info-readmore">
			                                    <a href="<?php the_permalink(); ?>">
			                                        参与讨论 <i class="fa fa-angle-double-right"></i>
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
			<?php
				memory_page_navi();
			?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>