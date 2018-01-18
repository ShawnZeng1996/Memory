<?php
// 添加侧边栏
function Memory_widgets_init(){
	if ( function_exists('register_sidebar') ) {
    	register_sidebar(array(
			'name'          => __( '萌萌哒侧边栏', 'theme_text_domain' ),
        	'before_widget' => '<li class="art">',
        	'after_widget' => '</li>',
        	'before_title' => '<header class="art-widget-header"><h3 class="sidebar-default-icon">',
        	'after_title' => '</h3></header>',
    	));	
	} 
	foreach( glob( get_template_directory() . '/widgets/widget-*.php' ) as $file_path )
		include( $file_path );
	$unregister_widgets = array(
		'Tag_Cloud',
		'Recent_Comments',
		'Recent_Posts',
		'Search'
	);
	foreach( $unregister_widgets as $widget )
		unregister_widget( 'WP_Widget_' . $widget );
}
add_action( 'widgets_init', 'Memory_widgets_init' );
if( function_exists( 'register_sidebar_widget' ) ) {   
    register_sidebar_widget('Do you like me?','Memory_like');   
}  
function Memory_like() { include(TEMPLATEPATH . '/widgets/doyoulikeme.php'); } 

if ( !function_exists( 'Memory_sidebar_posts_list' ) ) :
	/**
	 * 边栏文章列表
	 */
	function Memory_sidebar_posts_list( $query_args ){
      	$query_args['post_type']='post';
		$query = new WP_Query( $query_args );
		if( $query->have_posts() ):
			echo '<ul class="sidebar-posts-list">';
				while( $query->have_posts() ):
					$query->the_post();
					Memory_sidebar_posts_list_loop();
				endwhile;
				wp_reset_postdata();
			echo '</ul>';
		else:
	?>
			<div class="empty-sidebar-posts-list">
				<p><?php _e( '这里什么都没有，你也许可以使用搜索功能找到你需要的内容：' ); ?></p>
				<?php get_search_form(); ?>
			</div>
	<?php
		endif;
	}
endif;
if ( !function_exists( 'Memory_sidebar_posts_list_loop' ) ) :
	/**
	 * 边栏文章列表样式
	 */
	function Memory_sidebar_posts_list_loop(){
	?>
		<li>
				<?php the_title( '<p class="post-title"><a href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">「', '」</a></p>' ); ?>
				<p class="post-info"><i class="fa fa-eye"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?>&nbsp;•&nbsp;<i class="fa fa-commenting-o"></i>&nbsp;<?php comments_popup_link('0', '1', '%', '', '评论已关闭'); ?>&nbsp;•&nbsp;<i class="fa fa-calendar"></i>&nbsp;<?php the_time('Y-n-j H:i') ?></p>		
		</li>
	<?php
	}
endif;