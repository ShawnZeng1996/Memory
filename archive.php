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
function curPageURL() {
	$pageURL = 'http://';
	$this_page = $_SERVER["REQUEST_URI"];
	if (strpos($this_page , "?") !== false)
		$this_page = reset(explode("?", $this_page));
	$pageURL .= $_SERVER["SERVER_NAME"]  . $this_page;
	return $pageURL;
} 
?>
   	<div id="main">
        <div id="main-part">
            <ul class="posts-list">
				<li>
					<article class="art">
						<div class="art-main art-archive">
							<h3 class="art-title">
			    				<?php
								// If this is a category archive
								if (is_category()) {
									printf('分类/'.single_cat_title('', false));
								// If this is a tag archive
								} elseif (is_tag()) {
									printf('标签/'.single_tag_title('', false));
								// If this is a daily archive
								} elseif (is_day()) {
									printf('日期存档/'.get_the_time('Y年n月j日'));
								// If this is a monthly archive
								} elseif (is_month()) {
									printf('月份存档/'.get_the_time('Y年n月'));
								// If this is a yearly archive
								} elseif (is_year()) {
									printf('年份存档/'.get_the_time('Y年'));
									// If this is an author archive
								} elseif (is_author()) {
									echo '作者存档';
								// If this is a paged archive
								} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
									echo '博客存档';
								}
								?>
							</h3>
							<?php if (is_category()) { echo '<p class="category-des">' . category_description() . '</p>'; } ?>
							<ul>
								<li><a <?php if ( isset($_GET['order']) && ($_GET['order']=='rand') ) echo 'class="current"'; ?> href="<?php echo curPageURL() . '?' . http_build_query(array_merge($_GET, array('order' => 'rand'))); ?>">随机阅读</a></li>
								<li><a <?php if ( isset($_GET['order']) && ($_GET['order']=='commented') ) echo 'class="current"'; ?> href="<?php echo curPageURL() . '?' . http_build_query(array_merge($_GET, array('order' => 'commented'))); ?>">评论最多</a></li>
								<li><a <?php if ( isset($_GET['order']) && ($_GET['order']=='alpha') ) echo 'class="current"'; ?> href="<?php echo curPageURL() . '?' . http_build_query(array_merge($_GET, array('order' => 'alpha'))); ?>">标题排序</a></li>
							</ul>
						</div>
					</article>
				</li>
				<?php
					global $wp_query;
					if ( isset($_GET['order']) && ($_GET['order']=='rand') )
					{
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						$args=array(
							'orderby' => 'rand',
							'paged' => $paged,
						);
						$arms = array_merge(
							$args,
							$wp_query->query
						);
						query_posts($arms);
					}
					else if ( isset($_GET['order']) && ($_GET['order']=='commented') )
					{
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						$args=array(
							'orderby' => 'comment_count',
							'order' => 'DESC',
							'paged' => $paged,
						);
					    $arms = array_merge(
							$args,
							$wp_query->query
						);
					    query_posts($arms);
					}
					else if ( isset($_GET['order']) && ($_GET['order']=='alpha') )
					{
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						$args=array(
							'orderby' => 'title',
							'order' => 'ASC',
							'paged' => $paged,
						);
					    $arms = array_merge(
							$args,
							$wp_query->query
						);
					    query_posts($arms);
					} if (have_posts()) : while (have_posts()) : the_post(); ?>
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
			                            <div class="art-content">
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
					<?php endwhile; ?>
					<?php else: ?>
					<li>
						<article class="art">
							<div class="art-main">
								<div class="art-content">
			    					<p>抱歉，没有找到相关文章！</p>
								</div>
							</div>
						</article>
					</li>
				<?php endif ?>
            </ul>
			<?php
				memory_page_navi();
			?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>