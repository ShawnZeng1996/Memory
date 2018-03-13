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
 * Template Name: 时光轴
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
                    <div class="art-content">
                        <?php if ( has_post_thumbnail() ) {
							the_post_thumbnail();
						}
						the_content();
						memory_archives_list();
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
			<script>
  			//------------------存档页面jQuery伸缩------------------
    		(function(){
        		$('#al_expand_collapse,#archives span.al_mon').css({cursor:"s-resize"});
        		$('#archives span.al_mon').each(function(){
            		var num=$(this).next().children('li').length;
            		var text=$(this).text();
            		$(this).html(text+' ('+num+'篇文章)');
        		});
        		var $al_post_list=$('#archives ul.al_post_list'),
            		$al_post_list_f=$('#archives ul.al_post_list:first');
        			$al_post_list.hide(1,function(){
            		$al_post_list_f.show();
        		});
        		$('#archives span.al_mon').click(function(){
            		$(this).next().slideToggle(400);
            		return false;
        		});
      			var al_expand_collapse_click=0;
        		$('#al_expand_collapse').click(function(){
          			if (al_expand_collapse_click == 0){
              			$al_post_list.show();
              			al_expand_collapse_click++;
            		}else if (al_expand_collapse_click == 1){
              			$al_post_list.hide();
              			al_expand_collapse_click--;
            		}
        		});
    		})();
			</script>
			<?php endif; ?>
			<?php comments_template(); ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>