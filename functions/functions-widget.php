<?php 
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
      <p class="post-info"><span><i class="memory memory-view"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?></span><span><i class="memory memory-comment"></i>&nbsp;<?php if ( post_password_required() ) { echo '<a>密码保护</a>'; } else { comments_popup_link('0', '1', '%', '', '评论已关闭'); } ?></span><span><i class="memory memory-time"></i>&nbsp;<?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . '前'; ?></span></p>   
    </li>
  <?php
  }
endif;

// 最近访客
function Memory_most_active_friends($friends_num = 10) {
    global $wpdb;
    $counts = $wpdb->get_results("SELECT * FROM (SELECT * FROM $wpdb->comments WHERE user_id!='1' AND comment_approved='1' ORDER BY comment_date DESC) AS tempcmt GROUP BY comment_author_email ORDER BY comment_date DESC LIMIT $friends_num");
  $mostactive = '';
    foreach ($counts as $count) {
    $c_url = $count->comment_author_url;
    if ($c_url != '') {
    $mostactive .= '<li class="widget-visitor">' . '<a href="'. $c_url . '" target="_blank" data-content-pos="up" data-content="' . $count->comment_author . '">' . get_avatar($count, 40) . '</a></li>';
    } else {
      $mostactive .= '<li class="widget-visitor">' . '<a class="nopointer" data-content-pos="up" data-content="' . $count->comment_author . '">' . get_avatar($count, 40) . '</a></li>';
    }
    }
    return $mostactive;
}

function Memory_recent_photos($photos_num = 10) {
	$args = array(
		'numberposts' => $photos_num,
		'offset' => 0,
		'category' => 0,
		'orderby' => 'post_date',
		'order' => 'DESC',
		'post_type' => 'post',
		'post_status' => 'publish',
		'suppress_filters' => true );
	$recent_posts = wp_get_recent_posts($args);
	$recent_photos = '';
	foreach( $recent_posts as $recent ){
		if ( has_post_thumbnail($recent["ID"])) {
			$recent_photos .= '<li><a href="' . get_permalink($recent["ID"]) . '" data-content-pos="up" data-content="来自：' . $recent["post_title"] . '">' . get_the_post_thumbnail($recent["ID"], 'thumbnail') . '</a></li> ';
		}
	}
	return $recent_photos;
}