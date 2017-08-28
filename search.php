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
				<li>
					<article class="art">
						<div class="art-main">
							<h3 class="art-title">
			    				<a href="#">搜索结果</a>
							</h3>
						</div>
					</article>
				</li>
				<?php if ( have_posts() ) { ?>
					<?php while ( have_posts() ) { the_post(); global $post; ?>
						<?php $post_format = memory_get_post_format(); ?>
						<?php if( $post_format != 'status' ) { ?>
			                <li>
			                    <article class="art">
			                        <div class="art-main">
			                            <h3 class="art-title">
			                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
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
			                                </span>
			                            </div>
			                            <div class="art-intro">
			                                <?php the_excerpt(); ?>
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
			                    <article class="art">
			                        <div class="shuoshuo">
			                            <?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
			                            <h4><?php the_author(); ?></h4>
			                            <p><?php the_excerpt(); ?></p>
			                            <span class="shuoshuo-info">
			                                <i class="fa fa-clock-o"></i>
			                                2017-8-3
			                                &nbsp;•&nbsp;
			                                <i class="fa fa-commenting-o"></i>
			                                <?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>
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
				<?php } else { ?>
					<li>
						<article class="art">
							<div class="art-main">
								<div class="art-intro">
			    					<p>抱歉，没有找到相关文章！</p>
								</div>
							</div>
						</article>
					</li>
				<?php } ?>
            </ul>
			<?php
				memory_page_navi();
			?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>