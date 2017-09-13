<?php
// 本主题使用 wp_nav_menu() 函数自定义菜单
register_nav_menus();
// 移除网站头部wp-admin条
add_action('get_header', 'remove_admin_login_header');
function remove_admin_login_header() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}
// 分页功能
function memory_page_navi( $args = array() ){
	global $wp_query;
	$args = wp_parse_args( $args, array(
		'before'                       => '<div id="page-nav">',
		'after'                        => '</div>',
		'pages_text'                   => '%CURRENT_PAGE%/%TOTAL_PAGES%',
		'current_text'                 => '%PAGE_NUMBER%',
		'page_text'                    => '%PAGE_NUMBER%',
		'first_text'                   => __( '首页', 'Memory' ),
		'last_text'                    => __( '尾页', 'Memory' ),
		'next_text'                    => __( '&raquo;', 'Memory' ),
		'prev_text'                    => '&laquo;',
		'dotright_text'                => '...',
		'dotleft_text'                 => '...',
		'num_pages'                    => 5,
		'always_show'                  => 0,
		'num_larger_page_numbers'      => 3,
		'larger_page_numbers_multiple' => 10
	) );
	if( $wp_query->max_num_pages <= 1 || is_single() ) return;
	$max_page = $wp_query->max_num_pages;
	$paged = intval( get_query_var( 'paged' ) );
	if( empty( $paged ) || $paged == 0 ) $paged = 1;
	$pages_to_show = intval( $args['num_pages'] );
	$larger_page_to_show = intval( $args['num_larger_page_numbers'] );
	$larger_page_multiple = intval( $args['larger_page_numbers_multiple'] );
	$pages_to_show_minus_1 = $pages_to_show - 1;
	$half_page_start = floor( $pages_to_show_minus_1 / 2 );
	$half_page_end = ceil( $pages_to_show_minus_1 / 2 );
	$start_page = $paged - $half_page_start;
	if( $start_page <= 0 ) $start_page = 1;
	$end_page = $paged + $half_page_end;
	if( ( $end_page - $start_page ) != $pages_to_show_minus_1 ) $end_page = $start_page + $pages_to_show_minus_1;
	if( $end_page > $max_page ){
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}
	if( $start_page <= 0 ) $start_page = 1;
	$larger_per_page = $larger_page_to_show * $larger_page_multiple;
	$larger_start_page_start = ( ( floor( $start_page / 10 ) * 10 ) + $larger_page_multiple ) - $larger_per_page;
	$larger_start_page_end = floor( $start_page / 10 ) * 10 + $larger_page_multiple;
	$larger_end_page_start = floor( $end_page / 10 ) * 10 + $larger_page_multiple;
	$larger_end_page_end = floor( $end_page / 10 ) * 10 + ( $larger_per_page );
	if( $larger_start_page_end - $larger_page_multiple == $start_page ){
		$larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
		$larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
	}
	if( $larger_start_page_start <= 0 ) $larger_start_page_start = $larger_page_multiple;
	if( $larger_start_page_end > $max_page ) $larger_start_page_end = $max_page;
	if( $larger_end_page_end > $max_page ) $larger_end_page_end = $max_page;
	if( $max_page > 1 || intval( $args['always_show'] ) == 1 ){
		$pages_text = str_replace( '%CURRENT_PAGE%', number_format_i18n( $paged ), $args['pages_text'] );
		$pages_text = str_replace( '%TOTAL_PAGES%', number_format_i18n( $max_page ), $pages_text);
		echo $args['before'];
		if( !empty( $pages_text ) ) echo '<span class="page">' . $pages_text . '</span>';
		if( $start_page >= 2 && $pages_to_show < $max_page ){
			$first_page_text = str_replace( '%TOTAL_PAGES%', number_format_i18n( $max_page ), $args['first_text'] );
			echo '<a href="' . esc_url( get_pagenum_link() ) . '" class="first" title="' . $first_page_text . '">' . $first_page_text . '</a>';
		}
		if( $larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page ){
			for( $i = $larger_start_page_start;$i < $larger_start_page_end;$i += $larger_page_multiple ){
				$page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $args['page_text'] );
				echo '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="page-number" title="' . $page_text . '">' . $page_text . '</a>';
			}
		}
		previous_posts_link( $args['prev_text'] );
		for( $i = $start_page;$i <= $end_page;$i++ ){
			if( $i == $paged ){
				$current_page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $args['current_text'] );
				echo '<span class="current page-number">' . $current_page_text . '</span>';
			}else{
				$page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $args['page_text'] );
				echo '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="page-number" title="' . $page_text . '">' . $page_text . '</a>';
			}
		}
		echo '<span class="next-page">';
			next_posts_link( $args['next_text'], $max_page );
		echo '</span>';
	}
	if( $larger_page_to_show > 0 && $larger_end_page_start < $max_page ){
		for( $i = $larger_end_page_start;$i <= $larger_end_page_end;$i += $larger_page_multiple ){
			$page_text = str_replace( '%PAGE_NUMBER%', number_format_i18n( $i ), $args['page_text'] );
			echo '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="page" title="' . $page_text . '">' . $page_text . '</a>';
		}
	}
	if( $end_page < $max_page ){
		$last_page_text = str_replace( '%TOTAL_PAGES%', number_format_i18n( $max_page ), $args['last_text'] );
		echo '<a href="' . esc_url( get_pagenum_link( $max_page ) ) . '" class="last" title="' . $last_page_text . '">' . $last_page_text . '</a>';
	}
	echo $args['after'];
}
// 前台隐藏工具条
if ( !is_admin() ) {
    add_filter('show_admin_bar', '__return_false');
}
// 输出评论
function memory_comment($comment, $args, $depth)
{
   $GLOBALS['comment'] = $comment;
?>
   <li class="art-comment" id="li-comment-<?php comment_ID(); ?>">
		<div class="commentator-avatar">
			<?php if (function_exists('get_avatar') && get_option('show_avatars')) { 
				if (get_comment_author_url()!=null) { ?>
					<a href="<?php echo get_comment_author_url(); ?>" target="_blank">
				<?php } 
				echo get_avatar($comment, 50);
				if (get_comment_author_url()!=null) { ?>
				</a>
				<?php } 
				} 
  				comment_reply_link(array_merge( $args, array('reply_text' => '回复','depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        </div>
        <div class="commentator-comment" id="comment-<?php comment_ID(); ?>">
			<p>
                <span class="commentator-name"><?php printf(__('<strong class="author_name">%s</strong>'), get_comment_author_link()); ?></span>
				<?php if ($comment->user_id == '1' or $comment->comment_author_email == get_the_author_meta('user_email',1)) {
						echo '<span class="vip commentator-level">萌萌哒博主</span>';
					}else{
						echo get_author_class($comment->comment_author_email,$comment->user_id);
					}
				?>
				<span class="comment-time"><?php echo get_comment_time('Y-m-d H:i'); ?></span>
            </p>
            <div class="comment-chat">
                <div class="comment-arrow"></div>
               	<div class="comment-comment">
                <?php if ($comment->comment_approved == '0') : ?><p>你的评论正在审核，稍后会显示出来！</p><?php endif; ?>
				<?php comment_text(); ?><div class="comment-operation"><?php edit_comment_link( __( '编辑', 'Memory' ), '<span class="edit-link">', '</span>' );?></div>
               	</div>
            </div>
      	</div>
<?php }
// RSS 中添加查看全文链接防采集
function feed_read_more($content) {
	return $content . '<p><a rel="bookmark" href="'.get_permalink().'" target="_blank">查看全文</a></p>';
}
add_filter ('the_excerpt_rss', 'feed_read_more');
// 使WordPress支持post thumbnail
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}
if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'customized-post-thumb', 100, 120 );
}
// 上传图片HTTP错误的解决方法
add_filter( 'wp_image_editors', 'change_graphic_lib' );
function change_graphic_lib($array) {
  return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
}
// 阻止站内文章互相Pingback
function memory_noself_ping( &$links ) { 
	$home = get_option( 'home' );
	foreach ( $links as $l => $link )
	if ( 0 === strpos( $link, $home ) )
	unset($links[$l]); 
}
add_action('pre_ping','memory_noself_ping');
// 删除部分自带小工具
function unregister_default_widgets() {
	unregister_widget("WP_Widget_Calendar");
	unregister_widget("WP_Widget_Links");
	unregister_widget("WP_Widget_Meta");
	unregister_widget("WP_Widget_Search");
	unregister_widget("WP_Widget_Categories");
	unregister_widget("WP_Widget_RSS");
}
add_action("widgets_init", "unregister_default_widgets", 11);
// 增强编辑器
function enable_more_buttons($buttons) {
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'anchor';
	$buttons[] = 'cut';
	$buttons[] = 'copy';
	$buttons[] = 'paste';
	$buttons[] = 'undo';
	$buttons[] = 'redo';
	$buttons[] = 'sup';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	return $buttons;
}
add_filter("mce_buttons", "enable_more_buttons");
// 删除左侧工具菜单
function memory_remove_menus() {
	global $menu;
	$restricted = array(__('Tools'));
  	end ($menu);
  	while (prev($menu)){
	    $value = explode(' ',$menu[key($menu)][0]);
	    if(strpos($value[0], '<') === FALSE) {
	      if(in_array($value[0] != NULL ? $value[0]:"" , $restricted)){
	        unset($menu[key($menu)]);
	      }
	    }
	    else {
	      $value2 = explode('<', $value[0]);
	      if(in_array($value2[0] != NULL ? $value2[0]:"" , $restricted)){
	        unset($menu[key($menu)]);
	      }
	    }
  	}
}
if ( is_admin() ) {
  	add_action('admin_menu', 'memory_remove_menus');
}
// 新窗口打开评论者网站
add_filter( "get_comment_author_link", "memory_modifiy_comment_author_anchor" );
function memory_modifiy_comment_author_anchor( $author_link ){
    return str_replace( "<a", "<a target='_blank'", $author_link );
}
// 修复仪表盘头像错位
function fixed_activity_widget_avatar_style(){
  echo '<style type="text/css">
            #activity-widget #the-comment-list .avatar {
            position: absolute;
            top: 13px;
            width: 50px;
            height: 50px;
          }
          </style>';
}
add_action('admin_head', 'fixed_activity_widget_avatar_style' );
// 禁用emoji表情
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );
function disable_emojis_tinymce( $plugins ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
}
// 添加链接菜单
add_filter('pre_option_link_manager_enabled','__return_true');
// 去除头部版本号
remove_action('wp_head', 'wp_generator'); 
// 隐藏面板登陆错误信息
add_filter('login_errors', create_function('$a', "return null;"));
