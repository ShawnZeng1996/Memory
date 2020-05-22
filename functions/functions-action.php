<?php

add_action( 'wp_enqueue_scripts', 'jquery_register' );
function jquery_register() {
	if( !is_admin()){
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery-3.2.1.min.js', false, null , true );
		wp_enqueue_script( 'jquery' );
	}
}

function curPageURL() {
	$pageURL = 'http://';
	$this_page = $_SERVER["REQUEST_URI"];
	if (strpos($this_page , "?") !== false)
		$this_page = reset(explode("?", $this_page));
	$pageURL .= $_SERVER["SERVER_NAME"]  . $this_page;
	return $pageURL;
}

// 简化函数
function memory_meta($ID, $op, $op_name) {
	$temp=get_post_meta($ID,$op,true);
	if ($temp!=null && isset($temp[$op_name])) {
		return $temp[$op_name];
	} else {
		return null;
	}
}

// 前台隐藏工具条
if ( !is_admin() ) {
    add_filter('show_admin_bar', '__return_false');
}

// 移除部分自带小工具
function remove_some_wp_widgets(){
	$unregister_widgets = array(
		'Tag_Cloud',
		'Recent_Comments',
		'Recent_Posts',
		'Links',
		'Search',
		'Meta',		
		'Categories',
		'RSS'
	);
	foreach( $unregister_widgets as $widget )
		unregister_widget( 'WP_Widget_' . $widget );
	foreach( glob( get_template_directory() . '/widgets/widget-with-settings-*.php' ) as $file_path )
		include( $file_path );
}
add_action( 'widgets_init' , 'remove_some_wp_widgets' , 1 );

// 文章浏览量
function restyle_text($number) {
    if($number >= 1000) {
        return round($number/1000,2) . 'k';
    }else{
        return $number;
    }
}
function getPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return restyle_text($count);
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// 删除WordPress私密文章标题前的提示文字
function title_format($content) {
	return '%s';
}
add_filter('private_title_format', 'title_format');
add_filter('protected_title_format', 'title_format');

// 自定义WordPress更改文章密码保护后显示的提示内容
function password_protected_change( $content ) {
    global $post;
    if ( ! empty( $post->post_password ) && stripslashes( @$_COOKIE['wp-postpass_'.COOKIEHASH] ) != $post->post_password ) {
        $output = '
        <form action="' . esc_url( site_url( "wp-login.php?action=postpass", "login_post" ) ) . '" method="post" autocomplete="off"><p class="protected">这是一篇受密码保护的文章，您需要提供访问密码：</p><input name="post_password" class="input" type="password" size="20" /><input type="submit" name="Submit" class="button" value="' . __( "提交", "Memory" ) . '" /></form>
        ';
        return $output;
    } else {
        return $content;
    }
}
add_filter( 'the_password_form', 'password_protected_change' );
//add_filter( 'the_content','password_protected_change' );

// 使WordPress支持post thumbnail
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails', array( 'post', 'shuoshuo' ) );
}

// 喜欢功能
add_action('wp_ajax_nopriv_memory_like', 'memory_like');
add_action('wp_ajax_memory_like', 'memory_like');
function memory_like(){
    global $wpdb,$post;
	$id = $_POST["memory_id"];
	$action = $_POST["memory_action"];
	if ( $action == 'memory_like'){
    $memory_raters = get_post_meta($id,'memory_like',true);
    $expire = time() + 99999999;
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; 
    setcookie('memory_like_'.$id,$id,$expire,'/',$domain,false);
    if (!$memory_raters || !is_numeric($memory_raters)) {
        update_post_meta($id, 'memory_like', 1);
    }
    else {
            update_post_meta($id, 'memory_like', ($memory_raters + 1));
        }
    echo get_post_meta($id,'memory_like',true);
    }
    die;
}

// 主循环中显示文章类型
function Memory_posts_per_page($query){
    if ( (is_home() || is_search()) && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', 'shuoshuo', 'douban' ) ); 
    return $query;
}

// 说说
function create_shuoshuo() {
    $labels = array(
		'name'               => _x( '说说', 'Memory' ),
		'singular_name'      => _x( '说说', 'Memory' ),
        'add_new'            => _x( '新建说说', 'Memory' ),
        'add_new_item'       => __( '新建一个说说', 'Memory' ),
        'edit_item'          => __( '编辑说说', 'Memory' ),
        'new_item'           => __( '新说说', 'Memory' ),
        'all_items'          => __( '所有说说', 'Memory' ),
        'view_item'          => __( '查看说说', 'Memory' ),
        'search_items'       => __( '搜索说说', 'Memory' ),
        'not_found'          => __( '没有找到有关说说', 'Memory' ),
        'not_found_in_trash' => __( '回收站里面没有相关说说', 'Memory' ),
        'parent_item_colon'  => '',
        'menu_name'          => '说说'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => '写条说说',
        'public'        => true,
    	'menu_position' => 5,
    	'menu_icon'		=> 'dashicons-format-status',
        'supports'      => array( 'title', 'editor', 'author', 'comments', 'thumbnail', 'tag'),
		'taxonomies'    => array( 'shuoshuo',  'post_tag' ),
        'has_archive'   => true
    );
    register_post_type( 'shuoshuo', $args );
}
add_action( 'init', 'create_shuoshuo' );
add_action( 'add_meta_boxes', 'Memory_add_shuoshuo_box' );
function Memory_add_shuoshuo_box(){
    add_meta_box( 'Memory_shuoshuo_sticky', '置顶', 'Memory_shuoshuo_sticky', 'shuoshuo', 'side', 'high' );
}
function Memory_shuoshuo_sticky (){ ?>
    <input id="super-sticky" name="sticky" type="checkbox" value="sticky" <?php checked( is_sticky() ); ?> /><label for="super-sticky" class="selectit">置顶本条说说</label>
<?php
}
add_action('pre_get_posts','Memory_posts_per_page');

// RSS 中添加查看全文链接防采集
function feed_read_more($content) {
	return $content . '<p><a rel="bookmark" href="'.get_permalink().'" target="_blank">查看全文</a></p>';
}
add_filter ('the_excerpt_rss', 'feed_read_more');

// 阻止站内文章互相Pingback
function Memory_noself_ping( &$links ) { 
	$home = get_option( 'home' );
	foreach ( $links as $l => $link )
	if ( 0 === strpos( $link, $home ) )
	unset($links[$l]); 
}
add_action('pre_ping','Memory_noself_ping');

// 面包屑导航
function memory_breadcrumbs() {
	$delimiter = '›'; // 分隔符
	$before = '<span class="current">'; // 在当前链接前插入
	$after = '</span>'; // 在当前链接后插入
	if ( !is_home() && !is_front_page() || is_paged() ) {
		echo '<span itemscope itemtype="http://schema.org/WebPage" id="crumbs">';
		global $post;
		$homeLink = home_url();
		echo ' <a itemprop="breadcrumb" href="' . $homeLink . '">' . __( '首页' , 'Memory' ) . '</a> ' . $delimiter . ' ';
		if ( is_category() ) { // 分类 存档
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0){
				$cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
			}
			echo $before . '分类：' . single_cat_title('', false) . '' . $after;
		} elseif ( is_day() ) { // 天 存档
			echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif ( is_month() ) { // 月 存档
			echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif ( is_year() ) { // 年 存档
			echo $before . get_the_time('Y') . $after;
		} elseif ( is_single() && !is_attachment() ) { // 文章
			if ( get_post_type() != 'post' ) { // 自定义文章类型
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				echo $before . get_the_title() . $after;
			} else { // 文章 post
				$cat = get_the_category(); $cat = $cat[0];
				$cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
				echo $before . get_the_title() . $after;
			}
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) { // 附件
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && !$post->post_parent ) { // 页面
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) { // 父级页面
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_search() ) { // 搜索结果
			echo $before ;
			printf( __( '"%s"的搜索结果：', 'Memory' ),  get_search_query() );
			echo  $after;
		} elseif ( is_tag() ) { //标签 存档
			echo $before ;
			printf( __( '标签: %s', 'Memory' ), single_tag_title( '', false ) );
			echo  $after;
		} elseif ( is_author() ) { // 作者存档
			global $author;
			$userdata = get_userdata($author);
			echo $before ;
			printf( __( '作者: %s', 'Memory' ),  $userdata->display_name );
			echo  $after;
		} elseif ( is_404() ) { // 404 页面
			echo $before;
			_e( 'Not Found', 'Memory' );
			echo  $after;
		}
		if ( get_query_var('paged') ) { // 分页
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
				echo sprintf( __( '( 第%s页 )', 'Memory' ), get_query_var('paged') );
		}
		echo '</span>';
	}
}

// 文章摘要
function Memory_excerpt( $length, $more = '&hellip;', $echo = true ){
    static $excerpt_length, $excerpt_more;
 
    $current_filter = current_filter();
    if( $current_filter == 'excerpt_length' ) return $excerpt_length;
    if( $current_filter == 'excerpt_more'   ) return $excerpt_more;
 
    $excerpt_length = $length;
    $excerpt_more   = $more;
 
    $callable = __FUNCTION__;
    add_filter( 'excerpt_length', $callable, 18 );
    add_filter( 'excerpt_more',   $callable, 18 );
 
        $excerpt = $echo ? the_excerpt() : get_the_excerpt();
 
    remove_filter( 'excerpt_length', $callable, 18 );
    remove_filter( 'excerpt_more',   $callable, 18 );
 
    unset( $excerpt_length, $excerpt_more );
    return $excerpt;
}

// 特色图片
function wpforce_featured() {
    global $post;
    @$already_has_thumb = has_post_thumbnail($post->ID);
    if (!$already_has_thumb)  {
        @$attached_image = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
        if ($attached_image) {
            foreach ($attached_image as $attachment_id => $attachment) {
                set_post_thumbnail(@$post->ID, @$attachment_id);
            }
        }
    }
}  //end function
add_action('the_post', 'wpforce_featured');
add_action('save_post', 'wpforce_featured');
add_action('draft_to_publish', 'wpforce_featured');
add_action('new_to_publish', 'wpforce_featured');
add_action('pending_to_publish', 'wpforce_featured');
add_action('future_to_publish', 'wpforce_featured');

// 评论等级
function get_author_class($comment_author_email, $user_id){
    global $wpdb;
    $author_count = count($wpdb->get_results(
    "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
	if( $author_count==0 ) echo '<span class="giligili giligili-userlevel-0"></span>';
	else if($author_count>=1 && $author_count<= 5) echo '<span class="userlevel userlevel-1">潜水</span>';
	else if($author_count>=6 && $author_count<= 10) echo '<span class="userlevel userlevel-2">冒泡</span>';
	else if($author_count>=11 && $author_count<= 15) echo '<span class="userlevel userlevel-3">吐槽</span>';
	else if($author_count>=16 && $author_count<= 20) echo '<span class="userlevel userlevel-4">活跃</span>';
	else if($author_count>=21 && $author_count<= 25) echo '<span class="userlevel userlevel-5">话唠</span>';
	else if($author_count>=26 && $author_count<= 30) echo '<span class="userlevel userlevel-5">史诗</span>';
	else if($author_count>=31) echo '<span class="userlevel userlevel-6">传说</span>';
}

// 输出评论
function memory_comment($comment, $args, $depth) {
  	$GLOBALS['comment'] = $comment;
?>
	<li class="comment-item" id="li-comment-<?php comment_ID(); ?>">
		<div class="commentator-avatar">
			<?php if (function_exists('get_avatar') && get_option('show_avatars')) { 
				if (get_comment_author_url()!=null) { ?>
					<a href="<?php echo get_comment_author_url(); ?>" target="_blank">
				<?php } 
				echo get_avatar($comment, 48);
				if (get_comment_author_url()!=null) { ?>
				</a>
				<?php } 
				} 
			?>
      	</div>
		<div class="commentator-comment" id="comment-<?php comment_ID(); ?>"><span class="commentator-name"><?php printf(__('<span class="author-name">%s</span> '), get_comment_author_link()); if ($comment->user_id == '1') { ?><i class="memory memory-certify"></i><?php } ?></span> <?php echo get_author_class($comment->comment_author_email,$comment->user_id); ?>
            <div class="comment-chat">
				<div class="comment-comment">
					<?php if ($comment->comment_approved == '0') : ?><p>你的评论正在审核，稍后会显示出来！</p><?php endif; ?>
					<?php comment_text(); ?>
					<div class="comment-info">
						<span class="comment-time"><?php echo human_time_diff(get_comment_date('U',$comment->comment_ID), current_time('timestamp')) . '前'; ?></span>
                        <?php if ($comment->comment_approved == '1') {
							comment_reply_link(array_merge( $args, array('reply_text' => '回复','depth' => $depth, 'max_depth' => $args['max_depth']))); 
						} ?>
					</div>
               	</div>
	   		</div>
		</div>
<?php } 

// 验证码功能
function Memory_protection_math(){
	//获取两个随机数, 范围0~9
	$num1=rand(0,9);
	$num2=rand(0,9);
	//最终网页中的具体内容
	echo "<input type='text' name='sum' class='text-input sum' id='comment-validate' value='' placeholder='$num1 + $num2 = ?'>"."<input type='hidden' name='num1' value='$num1'>"."<input type='hidden' name='num2' value='$num2'>";
}
function Memory_protection_pre($commentdata){
	$sum=$_POST['sum'];//用户提交的计算结果
	switch($sum){
		//得到正确的计算结果则直接跳出
		case $_POST['num1']+$_POST['num2']:break;
		//未填写结果时的错误讯息
		case null:err('错误: 请输入验证码.');break;
		//计算错误时的错误讯息
		default:err('错误: 请输入正确的验证码.');
	}
	return $commentdata;
}

// ajax评论
add_action('wp_ajax_nopriv_ajax_comment', 'ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'ajax_comment_callback');
function ajax_comment_callback(){
    global $wpdb;
    $comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
    $post = get_post($comment_post_ID);
    $post_author = $post->post_author;
    $sum=$_POST['sum'];//用户提交的计算结果
    switch($sum){
        //得到正确的计算结果则直接跳出
        case $_POST['num1']+$_POST['num2']:break;
        //未填写结果时的错误讯息
        case null:ajax_comment_err('验证码错误: 请输入验证码！');break;
        //计算错误时的错误讯息
        default:ajax_comment_err('验证码错误: 请输入正确的验证码！');
    }
    if ( empty($post->comment_status) ) {
        do_action('comment_id_not_found', $comment_post_ID);
        ajax_comment_err('无效评论！请重新提交！');
    }
    $status = get_post_status($post);
    $status_obj = get_post_status_object($status);
    if ( !comments_open($comment_post_ID) ) {
        do_action('comment_closed', $comment_post_ID);
        ajax_comment_err('抱歉，此页面评论模块已关闭！');
    } elseif ( 'trash' == $status ) {
        do_action('comment_on_trash', $comment_post_ID);
        ajax_comment_err('无效评论！请重新提交！');
    } elseif ( !$status_obj->public && !$status_obj->private ) {
        do_action('comment_on_draft', $comment_post_ID);
        ajax_comment_err('无效评论！请重新提交！');
    } elseif ( post_password_required($comment_post_ID) ) {
        do_action('comment_on_password_protected', $comment_post_ID);
        ajax_comment_err('评论受到密码保护！');
    } else {
        do_action('pre_comment_on_post', $comment_post_ID);
    }
    $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
    $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
    $comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
    $comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
    $edit_id              = ( isset($_POST['edit_id']) ) ? $_POST['edit_id'] : null; // 提取 edit_id
    $user = wp_get_current_user();
    if ( $user->exists() ) {
        if ( empty( $user->display_name ) )
            $user->display_name=$user->user_login;
        $comment_author       = esc_sql($user->display_name);
        $comment_author_email = esc_sql($user->user_email);
        $comment_author_url   = esc_sql($user->user_url);
        $user_ID              = esc_sql($user->ID);
        if ( current_user_can('unfiltered_html') ) {
            if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
                kses_remove_filters();
                kses_init_filters();
            }
        }
    } else {
        if ( get_option('comment_registration') || 'private' == $status )
            ajax_comment_err('抱歉，你必须登录来发表评论！');
    }
    $comment_type = '';
    if ( get_option('require_name_email') && !$user->exists() ) {
        if ( 6 > strlen($comment_author_email) || '' == $comment_author )
            ajax_comment_err( '请填好姓名和邮箱再提交评论！' );
        elseif ( !is_email($comment_author_email))
            ajax_comment_err( '请输入合法的邮箱！' );
    }
    if ( '' == $comment_content )
        ajax_comment_err( '请输入评论内容！' );
    $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
    if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
    $dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
    if ( $wpdb->get_var($dupe) ) {
        ajax_comment_err('检测到重复评论！你是不是已经评论了该内容？');
    }
    if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
        $time_lastcomment = mysql2date('U', $lasttime, false);
        $time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
        $flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
        if ( $flood_die ) {
            ajax_comment_err('评论提交过快，请稍后！');
        }
    }
    $comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
    $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
    if ( $edit_id ) {
        $comment_id = $commentdata['comment_ID'] = $edit_id;
        if( ihacklog_user_can_edit_comment($commentdata,$comment_id) ) {
            wp_update_comment( $commentdata );
        } else {
            ajax_comment_err( '请别干坏事哦~' );
        }
    } else {
        $comment_id = wp_new_comment( $commentdata );
    }
    $comment = get_comment($comment_id);
    do_action('set_comment_cookies', $comment, $user);
    $comment_depth = 1;
    $tmp_c = $comment;
    while($tmp_c->comment_parent != 0){
        $comment_depth++;
        $tmp_c = get_comment($tmp_c->comment_parent);
    }
    $GLOBALS['comment'] = $comment;
    //以下修改成你的评论结构，直接复制评论回调函数内的全部内容过来
?>
    <li class="comment-item" id="li-comment-<?php comment_ID(); ?>">
        <div class="commentator-avatar">
            <?php if (function_exists('get_avatar') && get_option('show_avatars')) { 
                if (get_comment_author_url()!=null) { ?>
                    <a href="<?php echo get_comment_author_url(); ?>" target="_blank">
                <?php } 
                echo get_avatar($comment, 48);
                if (get_comment_author_url()!=null) { ?>
                </a>
                <?php } 
                } 
            ?>
        </div>
        <div class="commentator-comment" id="comment-<?php comment_ID(); ?>"><span class="commentator-name"><?php printf(__('<span class="author-name">%s</span> '), get_comment_author_link()); if ($comment->user_id == '1') { ?><i class="memory memory-certify"></i><?php } ?></span> <?php echo get_author_class($comment->comment_author_email,$comment->user_id); ?>
            <div class="comment-chat">
                <div class="comment-comment">
                    <?php if ($comment->comment_approved == '0') : ?><p>你的评论正在审核，稍后会显示出来！</p><?php endif; ?>
                    <?php comment_text(); ?>
                    <div class="comment-info">
                        <span class="comment-time"><?php echo human_time_diff(get_comment_date('U',$comment->comment_ID), current_time('timestamp')) . '前'; ?></span>
                        <?php if ($comment->comment_approved == '1') {
                            comment_reply_link(array_merge( $args, array('reply_text' => '回复','depth' => $depth, 'max_depth' => $args['max_depth']))); 
                        } ?>
                    </div>
                </div>
            </div>
        </div>
<?php 
    // 回调函数结束
    die();
}
function ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}
function ihacklog_user_can_edit_comment($new_cmt_data,$comment_ID = 0) {
    if(current_user_can('edit_comment', $comment_ID)) {
        return true;
    }
    $comment = get_comment( $comment_ID );
    $old_timestamp = strtotime( $comment->comment_date);
    $new_timestamp = current_time('timestamp');
    // 不用get_comment_author_email($comment_ID) , get_comment_author_IP($comment_ID)
    $rs = $comment->comment_author_email === $new_cmt_data['comment_author_email']
        && $comment->comment_author_IP === $_SERVER['REMOTE_ADDR']
        && $new_timestamp - $old_timestamp < 3600;
    return $rs;
}

// 自定义addComment
function Memory_disable_comment_js(){
    wp_deregister_script( 'comment-reply' );
}
add_action( 'init', 'Memory_disable_comment_js' );

// 一言
require(dirname(dirname(__FILE__)).'/hitokoto.php');

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

// 去除头部版本号
remove_action('wp_head', 'wp_generator'); 
// 隐藏面板登陆错误信息
add_filter('login_errors', function ($a) {
    return null;
});
// ajax头像更新
add_action( 'init', 'ajax_avatar_url' );
function ajax_avatar_url() {
	if( @$_GET['action'] == 'ajax_avatar_get' && 'GET' == $_SERVER['REQUEST_METHOD'] ) {
		$email = $_GET['email'];
		echo get_avatar_url( $email, array( 'size'=>48 ) ); // size 指定头像大小
		die();
	}else { return; }
}

// 文章归档
function memory_archives_list() {
	if( !$output = get_option('memory_archives_list') ){
		$output = '<div id="archives">';
		$the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1&post_type=post' ); 
		$year=0; $mon=0; $i=0; $j=0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$year_tmp = get_the_time('Y');
			$mon_tmp = get_the_time('M');
            $y=$year; $m=$mon;
            if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
            if ($year != $year_tmp && $year > 0) $output .= '</ul>';
            if ($year != $year_tmp) {
                $year = $year_tmp;
                $output .= '<h3 class="al_year">'. $year .' 年</h3><ul class="al_mon_list">'; //输出年份
            }
            if ($mon != $mon_tmp) {
                $mon = $mon_tmp;
                $output .= '<li><span class="al_mon">'.$mon.'</span><ul class="al_post_list">'; //输出月份
            }
            $output .= '<li>'.'<a class="no-des" href="'. get_permalink() .'">'.get_the_time('j日: ') . get_the_title() .'('. get_comments_number('0', '1', '%') .'条评论)</a></li>'; //输出文章日期和标题
        endwhile;
        wp_reset_postdata();
        $output .= '</ul></li></ul></div>';
        update_option('memory_archives_list', $output);
	}
    echo $output;
}
function clear_memory_cache() {
    update_option('memory_archives_list', ''); // 清空 memory_archives_list
}
add_action('save_post', 'clear_memory_cache'); // 新发表文章/修改文章时

// 添加链接菜单
add_filter('pre_option_link_manager_enabled','__return_true');

function comment_add_owo($comment_text, $comment = '') {
    $data_OwO = array(
        '@(便便)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/便便@2x.png" alt="便便" class="OwO-img">',
        '@(暗地观察)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/暗地观察@2x.png" alt="暗地观察" class="OwO-img">',
        '@(不出所料)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/不出所料@2x.png" alt="不出所料" class="OwO-img">',
        '@(不高兴)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/不高兴@2x.png" alt="不高兴" class="OwO-img">',
        '@(不说话)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/不说话@2x.png" alt="不说话" class="OwO-img">',
        '@(抽烟)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/抽烟@2x.png" alt="抽烟" class="OwO-img">',
        '@(呲牙)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/呲牙@2x.png" alt="呲牙" class="OwO-img">',
        '@(大囧)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/大囧@2x.png" alt="大囧" class="OwO-img">',
        '@(得意)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/得意@2x.png" alt="得意" class="OwO-img">',
        '@(愤怒)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/愤怒@2x.png" alt="愤怒" class="OwO-img">',
        '@(尴尬)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/尴尬@2x.png" alt="尴尬" class="OwO-img">',
        '@(高兴)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/高兴@2x.png" alt="高兴" class="OwO-img">',
        '@(鼓掌)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/鼓掌@2x.png" alt="鼓掌" class="OwO-img">',
        '@(观察)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/观察@2x.png" alt="观察" class="OwO-img">',
        '@(害羞)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/害羞@2x.png" alt="害羞" class="OwO-img">',
        '@(汗)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/汗@2x.png" alt="汗" class="OwO-img">',
        '@(黑线)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/黑线@2x.png" alt="黑线" class="OwO-img">',
        '@(欢呼)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/欢呼@2x.png" alt="欢呼" class="OwO-img">',
        '@(击掌)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/击掌@2x.png" alt="击掌" class="OwO-img">',
        '@(惊喜)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/惊喜@2x.png" alt="惊喜" class="OwO-img">',
        '@(看不见)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/看不见@2x.png" alt="看不见" class="OwO-img">',
        '@(看热闹)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/看热闹@2x.png" alt="看热闹" class="OwO-img">',
        '@(抠鼻)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/抠鼻@2x.png" alt="抠鼻" class="OwO-img">',
        '@(口水)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/口水@2x.png" alt="口水" class="OwO-img">',
        '@(哭泣)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/哭泣@2x.png" alt="哭泣" class="OwO-img">',
        '@(狂汗)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/狂汗@2x.png" alt="狂汗" class="OwO-img">',
        '@(蜡烛)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/蜡烛@2x.png" alt="蜡烛" class="OwO-img">',
        '@(脸红)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/脸红@2x.png" alt="脸红" class="OwO-img">',
        '@(内伤)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/内伤@2x.png" alt="内伤" class="OwO-img">',
        '@(喷水)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/喷水@2x.png" alt="喷水" class="OwO-img">',
        '@(喷血)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/喷血@2x.png" alt="喷血" class="OwO-img">',
        '@(期待)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/期待@2x.png" alt="期待" class="OwO-img">',
        '@(亲亲)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/亲亲@2x.png" alt="亲亲" class="OwO-img">',
        '@(傻笑)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/傻笑@2x.png" alt="傻笑" class="OwO-img">',
        '@(扇耳光)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/扇耳光@2x.png" alt="扇耳光" class="OwO-img">',
        '@(深思)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/深思@2x.png" alt="深思" class="OwO-img">',
        '@(锁眉)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/锁眉@2x.png" alt="锁眉" class="OwO-img">',
        '@(投降)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/投降@2x.png" alt="投降" class="OwO-img">',
        '@(吐)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/吐@2x.png" alt="吐" class="OwO-img">',
        '@(吐舌)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/吐舌@2x.png" alt="吐舌" class="OwO-img">',
        '@(吐血倒地)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/吐血倒地@2x.png" alt="吐血倒地" class="OwO-img">',
        '@(无奈)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/无奈@2x.png" alt="无奈" class="OwO-img">',
        '@(无所谓)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/无所谓@2x.png" alt="无所谓" class="OwO-img">',
        '@(无语)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/无语@2x.png" alt="无语" class="OwO-img">',
        '@(喜极而泣)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/喜极而泣@2x.png" alt="喜极而泣" class="OwO-img">',
        '@(献花)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/献花@2x.png" alt="献花" class="OwO-img">',
        '@(献黄瓜)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/献黄瓜@2x.png" alt="献黄瓜" class="OwO-img">',
        '@(想一想)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/想一想@2x.png" alt="想一想" class="OwO-img">',
        '@(小怒)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/小怒@2x.png" alt="小怒" class="OwO-img">',
        '@(小眼睛)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/小眼睛@2x.png" alt="小眼睛" class="OwO-img">',
        '@(邪恶)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/邪恶@2x.png" alt="邪恶" class="OwO-img">',
        '@(咽气)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/咽气@2x.png" alt="咽气" class="OwO-img">',
        '@(阴暗)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/阴暗@2x.png" alt="阴暗" class="OwO-img">',
        '@(赞一个)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/赞一个@2x.png" alt="赞一个" class="OwO-img">',
        '@(长草)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/长草@2x.png" alt="长草" class="OwO-img">',
        '@(中刀)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/中刀@2x.png" alt="中刀" class="OwO-img">',
        '@(中枪)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/中枪@2x.png" alt="中枪" class="OwO-img">',
        '@(中指)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/中指@2x.png" alt="中指" class="OwO-img">',
        '@(肿包)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/肿包@2x.png" alt="肿包" class="OwO-img">',
        '@(皱眉)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/皱眉@2x.png" alt="皱眉" class="OwO-img">',
        '@(装大款)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/装大款@2x.png" alt="装大款" class="OwO-img">',
        '@(坐等)' => '<img src="'.get_bloginfo('template_url').'/emoji/alu/坐等@2x.png" alt="坐等" class="OwO-img">',
        '@[啊]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/啊@2x.png" alt="啊" class="OwO-img">',
        '@[爱心]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/爱心@2x.png" alt="爱心" class="OwO-img">',
        '@[鄙视]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/鄙视@2x.png" alt="鄙视" class="OwO-img">',
        '@[便便]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/便便@2x.png" alt="便便" class="OwO-img">',
        '@[不高兴]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/不高兴@2x.png" alt="不高兴" class="OwO-img">',
        '@[彩虹]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/彩虹@2x.png" alt="彩虹" class="OwO-img">',
        '@[茶杯]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/茶杯@2x.png" alt="茶杯" class="OwO-img">',
        '@[大拇指]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/大拇指@2x.png" alt="大拇指" class="OwO-img">',
        '@[蛋糕]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/蛋糕@2x.png" alt="蛋糕" class="OwO-img">',
        '@[灯泡]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/灯泡@2x.png" alt="灯泡" class="OwO-img">',
        '@[乖]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/乖@2x.png" alt="乖" class="OwO-img">',
        '@[哈哈]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/哈哈@2x.png" alt="哈哈" class="OwO-img">',
        '@[汗]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/汗@2x.png" alt="汗" class="OwO-img">',
        '@[呵呵]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/呵呵@2x.png" alt="呵呵" class="OwO-img">',
        '@[黑线]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/黑线@2x.png" alt="黑线" class="OwO-img">',
        '@[红领巾]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/红领巾@2x.png" alt="红领巾" class="OwO-img">',
        '@[呼]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/呼@2x.png" alt="呼" class="OwO-img">',
        '@[花心]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/花心@2x.png" alt="花心" class="OwO-img">',
        '@[滑稽]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/滑稽@2x.png" alt="滑稽" class="OwO-img">',
        '@[惊哭]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/惊哭@2x.png" alt="惊哭" class="OwO-img">',
        '@[惊讶]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/惊讶@2x.png" alt="惊讶" class="OwO-img">',
        '@[开心]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/开心@2x.png" alt="开心" class="OwO-img">',
        '@[酷]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/酷@2x.png" alt="酷" class="OwO-img">',
        '@[狂汗]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/狂汗@2x.png" alt="狂汗" class="OwO-img">',
        '@[蜡烛]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/蜡烛@2x.png" alt="蜡烛" class="OwO-img">',
        '@[懒得理]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/懒得理@2x.png" alt="懒得理" class="OwO-img">',
        '@[泪]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/泪@2x.png" alt="泪" class="OwO-img">',
        '@[冷]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/冷@2x.png" alt="冷" class="OwO-img">',
        '@[礼物]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/礼物@2x.png" alt="礼物" class="OwO-img">',
        '@[玫瑰]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/玫瑰@2x.png" alt="玫瑰" class="OwO-img">',
        '@[勉强]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/勉强@2x.png" alt="勉强" class="OwO-img">',
        '@[你懂的]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/你懂的@2x.png" alt="你懂的" class="OwO-img">',
        '@[怒]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/怒@2x.png" alt="怒" class="OwO-img">',
        '@[喷]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/喷@2x.png" alt="喷" class="OwO-img">',
        '@[钱]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/钱@2x.png" alt="钱" class="OwO-img">',
        '@[钱币]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/钱币@2x.png" alt="钱币" class="OwO-img">',
        '@[弱]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/弱@2x.png" alt="弱" class="OwO-img">',
        '@[三道杠]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/三道杠@2x.png" alt="三道杠" class="OwO-img">',
        '@[沙发]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/沙发@2x.png" alt="沙发" class="OwO-img">',
        '@[生气]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/生气@2x.png" alt="生气" class="OwO-img">',
        '@[胜利]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/胜利@2x.png" alt="胜利" class="OwO-img">',
        '@[手纸]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/手纸@2x.png" alt="手纸" class="OwO-img">',
        '@[睡觉]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/睡觉@2x.png" alt="睡觉" class="OwO-img">',
        '@[酸爽]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/酸爽@2x.png" alt="酸爽" class="OwO-img">',
        '@[太开心]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/太开心@2x.png" alt="太开心" class="OwO-img">',
        '@[太阳]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/太阳@2x.png" alt="太阳" class="OwO-img">',
        '@[吐]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/吐@2x.png" alt="吐" class="OwO-img">',
        '@[吐舌]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/吐舌@2x.png" alt="吐舌" class="OwO-img">',
        '@[挖鼻]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/挖鼻@2x.png" alt="挖鼻" class="OwO-img">',
        '@[委屈]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/委屈@2x.png" alt="委屈" class="OwO-img">',
        '@[捂嘴笑]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/捂嘴笑@2x.png" alt="捂嘴笑" class="OwO-img">',
        '@[犀利]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/犀利@2x.png" alt="犀利" class="OwO-img">',
        '@[香蕉]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/香蕉@2x.png" alt="香蕉" class="OwO-img">',
        '@[小乖]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/小乖@2x.png" alt="小乖" class="OwO-img">',
        '@[小红脸]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/小红脸@2x.png" alt="小红脸" class="OwO-img">',
        '@[笑尿]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/笑尿@2x.png" alt="笑尿" class="OwO-img">',
        '@[笑眼]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/笑眼@2x.png" alt="笑眼" class="OwO-img">',
        '@[心碎]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/心碎@2x.png" alt="心碎" class="OwO-img">',
        '@[星星月亮]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/星星月亮@2x.png" alt="星星月亮" class="OwO-img">',
        '@[呀咩爹]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/呀咩爹@2x.png" alt="呀咩爹" class="OwO-img">',
        '@[药丸]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/药丸@2x.png" alt="药丸" class="OwO-img">',
        '@[咦]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/咦@2x.png" alt="咦" class="OwO-img">',
        '@[疑问]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/疑问@2x.png" alt="疑问" class="OwO-img">',
        '@[阴险]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/阴险@2x.png" alt="阴险" class="OwO-img">',
        '@[音乐]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/音乐@2x.png" alt="音乐" class="OwO-img">',
        '@[真棒]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/真棒@2x.png" alt="真棒" class="OwO-img">',
        '@[nico]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/nico@2x.png" alt="nico" class="OwO-img">',
        '@[OK]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/OK@2x.png" alt="OK" class="OwO-img">',
        '@[what]' => '<img src="'.get_bloginfo('template_url').'/emoji/paopao/what@2x.png" alt="what" class="OwO-img">'
    );
    return strtr($comment_text,$data_OwO);
}
function comment_add_at($comment_text, $comment = '') {  
	if($comment->comment_parent > 0) {
        $comment_text = '<a href="#comment-' . $comment->comment_parent . '" title="' .get_comment_author( $comment->comment_parent ) . '" class="at">' .get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
    }
	return $comment_text;
}
add_filter( 'comment_text' , 'comment_add_owo', 20, 2);
add_filter( 'comment_text' , 'comment_add_at', 20, 2);
add_filter( 'get_comment_text' , 'comment_add_owo', 20, 2);

// 默认头像
add_filter( 'avatar_defaults', 'newgravatar' );  
function newgravatar ($avatar_defaults) {  
	$unique_id = cs_get_option('memory_comment_avatar'); 
	$attachment = wp_get_attachment_image_src( $unique_id, 'full' ); 
	$image_url = ($attachment) ? $attachment[0] : $unique_id; 
	if( get_option( 'memory_comment_default' )==null ) {
		$myavatar = get_bloginfo('template_directory') . '/img/comment-avatar.png';
	} else {
		$myavatar = $image_url;
	}
	$avatar_defaults[$myavatar] = "Memory自定义头像";
	return $avatar_defaults;  
}

// 评论邮件回复功能
function comment_mail_notify($comment_id) {
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$comment = get_comment($comment_id);
	$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	$spam_confirmed = $comment->comment_approved;
	if (($parent_id != '') && ($spam_confirmed != 'spam')) {
		$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
		$to = trim(get_comment($parent_id)->comment_author_email);
		$subject = '您在 [' . $blogname . '] 中的留言有了新的回复';
		$message = '<div style="color:#555;font:12px/1.5 微软雅黑,Tahoma,Helvetica,Arial,sans-serif;width:650px;margin:50px auto;border-top: none;box-shadow:0 0px 3px #aaaaaa;" ><table border="0" cellspacing="0" cellpadding="0"><tbody><tr valign="top" height="2"><td valign="top"><div style="background-color:white;border-top:2px solid #12ADDB;box-shadow:0 1px 3px #AAAAAA;line-padding:0 15px 12px;width:650px;color:#555555;font-family:微软雅黑, Arial;;font-size:12px;"><h2 style="border-bottom:1px solid #DDD;font-size:14px;font-weight:normal;padding:8px 0 10px 8px;"><span style="color: #12ADDB;font-weight: bold;">&gt; </span>您在 <a style="text-decoration:none; color:#58B5F5;font-weight:600;" href="' . home_url() . '">' . $blogname . '</a> 博客上的留言有回复啦！</h2><div style="padding:0 12px 0 12px;margin-top:18px">
<p>您好, ' . trim(get_comment($parent_id)->comment_author) . '! 您发表在本站 《' . get_the_title($comment->comment_post_ID) . '》 的评论:</p>
<p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">' . nl2br(strip_tags(get_comment($parent_id)->comment_content)) . '</p>
<p>' . trim($comment->comment_author) . ' 给您的回复如下:</p>
<p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">' . nl2br(strip_tags($comment->comment_content)) . '</p>
<p>您可以点击 <a style="text-decoration:none; color:#5692BC" href="' . htmlspecialchars(get_comment_link($parent_id)) . '">这里查看回复的完整內容</a>，也欢迎再次光临 <a style="text-decoration:none; color:#5692BC"
href="' . home_url() . '">' . $blogname . '</a>。祝您天天开心，欢迎下次访问！谢谢。</p>
<p style="padding-bottom: 15px;">(此邮件由系统自动发出, 请勿回复)</p></div></div></td></tr></tbody></table></div>';
		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
		if(cs_get_option( 'memory_comment_reply' )==true) {
			wp_mail( $to, $subject, $message, $headers );	
		}
	}
}
add_action('comment_post', 'comment_mail_notify');

function project_scripts() {	
	wp_enqueue_style( 'custom-icons', get_template_directory_uri() .'/css/iconfont.css', array(), wp_get_theme()->get('Version'), 'all' );
	wp_enqueue_style( 'custom-icons-style', get_template_directory_uri() .'/css/framework.css', array(), wp_get_theme()->get('Version'), 'all' );
}
//add_action( 'wp_enqueue_scripts', 'project_scripts' ); // For front end
add_action( 'admin_enqueue_scripts', 'project_scripts' ); // For admin end

// 激活主题创建页面
function memory_add_page($title,$slug,$page_template=''){
    $allPages = get_pages();//获取所有页面
    $exists = false;
    foreach( $allPages as $page ){
        //通过页面别名来判断页面是否已经存在
        if( strtolower( $page->post_name ) == strtolower( $slug ) ){
            $exists = true;
        }
    }
    if( $exists == false ) {
        $new_page_id = wp_insert_post(
            array(
                'post_title' => $title,
                'post_type'     => 'page',
                'post_name'  => $slug,
                'comment_status' => 'open',
                'ping_status' => 'closed',
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'menu_order' => 0
            )
        );
        //如果插入成功 且设置了模板
        if($new_page_id && $page_template!=''){
            //保存页面模板信息
            update_post_meta($new_page_id, '_wp_page_template',  $page_template);
        }
    }
}
function memory_add_pages() {
    global $pagenow;
    //判断是否为激活主题页面
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
        memory_add_page('归档','post-archives','post-archives.php');//页面标题、别名、页面模板
        memory_add_page('友情链接','friend-link','friend-link.php');
    }
}
add_action( 'load-themes.php', 'memory_add_pages' );