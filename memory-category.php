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
 * Template Name: 分类
 */
get_header(); 
setPostViews(get_the_ID());
?>
   	<div id="main">
        <div id="main-part">
			<?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
            <article class="art">
				<header class="art-header">
					<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
					<div class="right-box">
						<h3 class="article-author"><?php the_author(); ?>
					 		<i class="fa fa-vcard"></i>
							<span class="page-title">「<?php the_title(); ?>」</span>
						</h3>
						<div class="page-info">
                    		<span class="art-info-view">
			            		<i class="fa fa-eye"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?>
			            	</span>&nbsp;•&nbsp;
			            	<span class="art-info-comment">
			            		<i class="fa fa-comment-o"></i>&nbsp;<?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>
			            	</span>
							<span class="art-info-edit">
							<?php edit_post_link('编辑', '&nbsp;•&nbsp;&nbsp;', ''); ?>
							</span>
							<?php 
								if( current_user_can( 'manage_options') )
									if(d4v(get_permalink()) == 1) 
										$shoulu="•&nbsp;&nbsp;<i class='fa fa-paw'></i>&nbsp;<span>已收录</span>"; 
									else 
										$shoulu="•&nbsp;&nbsp;<i class='fa fa-paw'>&nbsp;</i><span><a target='_blank' href='http://zhanzhang.baidu.com/sitesubmit/index?sitename=".get_permalink()."'>未收录!点此提交</a></span>";  
								echo $shoulu;
							?>
                  		</div>
					</div>
				</header>
				<div class="art-main">
					<div class="art-content art-category">
				<?php
					global $post;
					$reset_post = $post;
					$cat_list =get_categories();
					$cat =get_categories();
					foreach($cat_list as $category) :
						if($category->name !='未分类') {
						echo '<a href="' . sprintf( __( "#%s" ), $category->name ) . '" ' . '>' . $category->name.'</a>';
						}
					endforeach;
					foreach($cat as $category) :
						$args=array(
							'numberposts' => 100,
							//'ignore_sticky_posts'=>1,
							'category__in' => array($category->term_id) 
						);
					$xhdpost = get_posts( $args );
					if($category->name !='未分类') {
						echo '<h3><a href="' . get_category_link( $category->term_id ) . '" name="' . sprintf( __( "%s" ), $category->name ) . '" ' . '>' . $category->name.'</a></h3><ul>';
					}
					foreach ( $xhdpost as $post ) : setup_postdata($post); 
							if( $post->post_type!= 'post' or $category->name =='未分类') {
							continue;
						}?>
						<li><span class="art-category-time"><?php the_time('Y-m-d'); ?></span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php
					endforeach;
					?></ul><?php
					endforeach;
					$post = $reset_post;
					?>
					</div>
					<div class="article-info">
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
				</div>
			</article>
			<?php endif; ?>
			<?php comments_template(); ?>
        </div>	
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>