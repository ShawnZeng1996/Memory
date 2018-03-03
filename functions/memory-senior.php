<?php
/* 页面伪静态化
function html_page_permalink() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
	}
}
add_action('init', 'html_page_permalink', -1);
*/
/* 使用smtp发送邮件
function mail_smtp( $phpmailer ) {
	$phpmailer->IsSMTP();
	$phpmailer->SMTPAuth = true;//启用SMTPAuth服务
	$phpmailer->Port = 465;//MTP邮件发送端口，这个和下面的对应，如果这里填写25，则下面为空白
	$phpmailer->SMTPSecure ="ssl";//是否验证 ssl，这个和上面的对应，如果不填写，则上面的端口须为25
	$phpmailer->Host = "smtp.qq.com";//邮箱的SMTP服务器地址，如果是QQ的则为：smtp.exmail.qq.com
 	$phpmailer->Username = "admin@shawnzeng.com";//你的邮箱地址
 	$phpmailer->Password = "";//你的邮箱登陆密码
}
add_action('phpmailer_init', 'mail_smtp');
//下面这个很重要，得将发件地址改成和上面smtp邮箱一致才行。
function memory_wp_mail_from( $original_email_address ) {
	return 'admin@shawnzeng.com';
}
add_filter( 'wp_mail_from', 'memory_wp_mail_from' );
*/
// ssl头像
//function get_ssl_avatar($avatar) {
//   $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
//   return $avatar;
//}
//add_filter('get_avatar', 'get_ssl_avatar');
// 默认头像
add_filter( 'avatar_defaults', 'newgravatar' );  
function newgravatar ($avatar_defaults) {  
	if( get_option( 'memory_comment_default' )==null ) {
		$myavatar = get_bloginfo('template_directory') . '/img/60535674_p0_master1200.jpg';
	} else {
		$myavatar = get_option( 'memory_comment_default' );
	}
    $avatar_defaults[$myavatar] = "Memory"; 
    return $avatar_defaults;  
}
// 说说
function create_shuoshuo() {
    $labels = array(
        'name'               => _x( '说说', 'post type 名称' ),
        'singular_name'      => _x( '说说', 'post type 单个 item 时的名称，因为英文有复数' ),
        'add_new'            => _x( '新建说说', '添加新内容的链接名称' ),
        'add_new_item'       => __( '新建一个说说' ),
        'edit_item'          => __( '编辑说说' ),
        'new_item'           => __( '新说说' ),
        'all_items'          => __( '所有说说' ),
        'view_item'          => __( '查看说说' ),
        'search_items'       => __( '搜索说说' ),
        'not_found'          => __( '没有找到有关说说' ),
        'not_found_in_trash' => __( '回收站里面没有相关说说' ),
        'parent_item_colon'  => '',
        'menu_name'          => '说说'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => '写条说说',
        'public'        => true,
    	'menu_position' => 5,
    	'menu_icon'		=> 'dashicons-format-status',
        'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'comments' ),
        'has_archive'   => true
    );
    register_post_type( 'shuoshuo', $args );
}
add_action( 'init', 'create_shuoshuo' );
function Memory_posts_per_page($query){
    if ( (is_home() || is_search()) && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', 'shuoshuo' ) ); //主循环中显示post和product
    return $query;
}
add_action('pre_get_posts','Memory_posts_per_page');
add_action( 'add_meta_boxes', 'Memory_add_shuoshuo_box' );
function Memory_add_shuoshuo_box(){
    add_meta_box( 'Memory_shuoshuo_sticky', '置顶', 'Memory_shuoshuo_sticky', 'shuoshuo', 'side', 'high' );
}
function Memory_shuoshuo_sticky (){ ?>
    <input id="super-sticky" name="sticky" type="checkbox" value="sticky" <?php checked( is_sticky() ); ?> /><label for="super-sticky" class="selectit">置顶本条说说</label>
<?php
}
// 文章浏览量
function getPostViews($postID) {
	$count_key = 'post_views_count';
	$count = get_post_meta($postID, $count_key, true);
	if ($count == '') {
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return "0";
    }
	return $count;
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
// 文章归档
function memory_archives_list() {
	if( !$output = get_option('memory_archives_list') ){
		$output = '<div id="archives"><p style="text-align:right;">[<a id="al_expand_collapse" href="#">全部展开/收缩</a>] (注: 点击月份可以展开)</p>';
		$the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1&post_type=post' ); //update: 加上忽略置顶文章
		$year=0; $mon=0; $i=0; $j=0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$year_tmp = get_the_time('Y');
            $mon_tmp = get_the_time('m');
            $y=$year; $m=$mon;
            if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
            if ($year != $year_tmp && $year > 0) $output .= '</ul>';
            if ($year != $year_tmp) {
                $year = $year_tmp;
                $output .= '<h3 class="al_year">'. $year .' 年</h3><ul class="al_mon_list">'; //输出年份
            }
            if ($mon != $mon_tmp) {
                $mon = $mon_tmp;
                $output .= '<li><span class="al_mon">'.$mon.'月</span><ul class="al_post_list">'; //输出月份
            }
            $output .= '<li>'.'<a href="'. get_permalink() .'">'.get_the_time('d日: ') . get_the_title() .'('. get_comments_number('0', '1', '%') .'条评论)</a></li>'; //输出文章日期和标题
        endwhile;
        wp_reset_postdata();
        $output .= '</ul></li></ul></div>';
        update_option('memory_archives_list', $output);
	}
    echo $output;
}
// 说说归档
function memory_shuoshuo_list() {
	if( !$output = get_option('memory_shuoshuo_list') ){
		$output = '<div id="archives"><p style="text-align:right;">[<a id="al_expand_collapse" href="#">全部展开/收缩</a>] (注: 点击月份可以展开)</p>';
		$the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1&post_type=shuoshuo' ); //update: 加上忽略置顶文章
		$year=0; $mon=0; $i=0; $j=0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$year_tmp = get_the_time('Y');
            $mon_tmp = get_the_time('m');
            $y=$year; $m=$mon;
            if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
            if ($year != $year_tmp && $year > 0) $output .= '</ul>';
            if ($year != $year_tmp) {
                $year = $year_tmp;
                $output .= '<h3 class="al_year">'. $year .' 年</h3><ul class="al_mon_list">'; //输出年份
            }
            if ($mon != $mon_tmp) {
                $mon = $mon_tmp;
                $output .= '<li><span class="al_mon">'.$mon.'月</span><ul class="al_post_list">'; //输出月份
            }
            $output .= '<li>'.'<a href="'. get_permalink() .'">'.get_the_time('d日: ') . get_the_content() .'('. get_comments_number('0', '1', '%') .'条评论)</a></li>'; //输出文章日期和标题
        endwhile;
        wp_reset_postdata();
        $output .= '</ul></li></ul></div>';
        update_option('memory_shuoshuo_list', $output);
	}
    echo $output;
}
function clear_zal_cache() {
    update_option('memory_archives_list', ''); // 清空 memory_archives_list
	update_option('memory_shuoshuo_list', ''); // 清空 memory_shuoshuo_list
}
add_action('save_post', 'clear_zal_cache'); // 新发表文章/修改文章时
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
<p>您好, ' . trim(get_comment($parent_id)->comment_author) . '! 您发表在文章 《' . get_the_title($comment->comment_post_ID) . '》 的评论:</p>
<p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">' . nl2br(strip_tags(get_comment($parent_id)->comment_content)) . '</p>
<p>' . trim($comment->comment_author) . ' 给您的回复如下:</p>
<p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">' . nl2br(strip_tags($comment->comment_content)) . '</p>
<p>您可以点击 <a style="text-decoration:none; color:#5692BC" href="' . htmlspecialchars(get_comment_link($parent_id)) . '">这里查看回复的完整內容</a>，也欢迎再次光临 <a style="text-decoration:none; color:#5692BC"
href="' . home_url() . '">' . $blogname . '</a>。祝您天天开心，欢迎下次访问！谢谢。</p>
<p style="padding-bottom: 15px;">(此邮件由系统自动发出, 请勿回复)</p></div></div></td></tr></tbody></table></div>';
		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
		wp_mail( $to, $subject, $message, $headers );
	}
}
add_action('comment_post', 'comment_mail_notify');
// 评论者样式
function get_author_class($comment_author_email, $user_id){
    global $wpdb;
    $author_count = count($wpdb->get_results(
    "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
    if($author_count>=1 && $author_count<= 10 ) { //数字可自行修改，代表评论次数。
        echo '<span class="vip1 commentator-level">';
		if( get_option('memory_com_vip1')==null ) { echo '潜水'; } else { echo get_option('memory_com_vip1'); }
		echo '</span>';
	} else if($author_count>=11 && $author_count<= 20) {
        echo '<span class="vip2 commentator-level">';
		if( get_option('memory_com_vip2')==null ) { echo '冒泡'; } else { echo get_option('memory_com_vip2'); }
		echo '</span>';
	} else if($author_count>=21 && $author_count<= 40) {
        echo '<span class="vip3 commentator-level">';
		if( get_option('memory_com_vip3')==null ){ echo '吐槽'; } else { echo get_option('memory_com_vip3'); }
		echo '</span>';
    } else if($author_count>=41 && $author_count<= 80) {
        echo '<span class="vip4 commentator-level">';
		if( get_option('memory_com_vip4')==null ){ echo '活跃'; } else { echo get_option('memory_com_vip4'); }
		echo '</span>';
    } else if($author_count>=81 && $author_count<= 160) {
        echo '<span class="vip5 commentator-level">';
		if( get_option('memory_com_vip5')==null ){ echo '话唠'; } else { echo get_option('memory_com_vip5'); }
		echo '</span>';
    } else if($author_count>=161 && $author_count<= 320) {
        echo '<span class="vip6 commentator-level">';
		if( get_option('memory_com_vip6')==null ){ echo '史诗'; } else { echo get_option('memory_com_vip6'); }
		echo '</span>';
    } else if($author_count>=321) {
        echo '<span class="vip7 commentator-level">';
		if( get_option('memory_com_vip7')==null ){ echo '传说'; } else { echo get_option('memory_com_vip7'); }
		echo '</span>';
	}
}
// 添加编辑器按钮
add_action('after_wp_tiny_mce', 'add_button_mce');
function add_button_mce($mce_settings) {
?>
<script type="text/javascript">
QTags.addButton( 'no_des_link', 'no_des_link', '<a class="no-des no-bg" href="链接URL">链接文本</a>', '');
QTags.addButton( 'at', '@link', '<a class="at" href="链接URL">链接文本</a>', '');
QTags.addButton( 'memorycode', 'memory_code', '<pre><span class="pre-title">语言类型</span><code class="hljs 语言类型">请输入您的代码......</code></pre>', '');
QTags.addButton( 'mr','mr','[mr]','');
</script>
<?php
}
// 可视化菜单添加特色功能
add_action('admin_head', 'my_custom_mce_button');
function my_custom_mce_button() {
    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
        return;
    }
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
        add_filter( 'mce_external_plugins', 'my_custom_tinymce_plugin' );
        add_filter( 'mce_buttons', 'my_register_mce_button' );
    }
}
function my_custom_tinymce_plugin( $plugin_array ) {
    $plugin_array['my_mce_button'] = get_template_directory_uri() .'/js/edit.js';
    return $plugin_array;
}
function my_register_mce_button( $buttons ) {
    array_push( $buttons, 'my_mce_button' );
    return $buttons;
}
// 短代码
function memory_line(){
	return '<p class="line"></p>';
}
function memory_pre($atts, $content=null) {
	extract(shortcode_atts(array(
   		'lan' => ''
   	), $atts));	
	return '<pre><span class="pre-title">' . $lan . '</span><code class="hljs ' . $lan . '">' . $content . '</code></pre>';
}
function register_shortcodes(){
   add_shortcode('mr', 'memory_line');
   add_shortcode('mcode', 'memory_pre');
}
add_action( 'init', 'register_shortcodes');
// 百度收录查询/提交
function d4v($url){
	$url='http://www.baidu.com/s?wd='.$url;
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	$rs=curl_exec($curl);
	curl_close($curl);
	if(!strpos($rs,'没有找到')){
		return 1;
	}else{
		return 0;
	}
}
// 移植wp-utf8-excerpt插件至主题，文章摘要不再去格式
if ( !function_exists('mb_strlen') ) {
	function mb_strlen ($text, $encode) {
		if ($encode=='UTF-8') {
			return preg_match_all('%(?:
					  [\x09\x0A\x0D\x20-\x7E]           # ASCII
					| [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
					|  \xE0[\xA0-\xBF][\x80-\xBF]       # excluding overlongs
					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
					|  \xED[\x80-\x9F][\x80-\xBF]       # excluding surrogates
					|  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
					| [\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
					|  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
					)%xs',$text,$out);
		}else{
			return strlen($text);
		}
	}
}
if (!function_exists('mb_substr')) {
    function mb_substr($str, $start, $len = '', $encoding="UTF-8"){
        $limit = strlen($str);
        for ($s = 0; $start > 0;--$start) {
            if ($s >= $limit)
                break;
            if ($str[$s] <= "\x7F")
                ++$s;
            else {
                ++$s;
                while ($str[$s] >= "\x80" && $str[$s] <= "\xBF")
                    ++$s;
            }
        }
        if ($len == '')
            return substr($str, $s);
        else
            for ($e = $s; $len > 0; --$len) {
                if ($e >= $limit)
                    break;
                if ($str[$e] <= "\x7F")
                    ++$e;
                else {
                    ++$e;
                    while ($str[$e] >= "\x80" && $str[$e] <= "\xBF" && $e < $limit)
                        ++$e;
                }
            }
        return substr($str, $s, $e - $s);
    }
}
define ('HOME_EXCERPT_LENGTH', 300);
define ('ARCHIVE_EXCERPT_LENGTH', 150);
define ('ALLOWD_TAG', '<a><b><blockquote><br><cite><dd><del><div><dl><dt><em><h1><h2><h3><h4><h5><h6><i><img><li><ol><p><pre><span><strong><ul>');
define ('READ_MORE_LINK', __( 'Read more', 'wp-utf8-excerpt') );
if (!function_exists('utf8_excerpt')) {
	function utf8_excerpt ($text, $type) {
		global $post;
		switch ($type) {
			case 'content':
				$manual_excerpt = $post->post_excerpt;
				break;			
			case 'excerpt':
				$manual_excerpt = $text;			
				$text = $post->post_content;
				$text = str_replace(']]>', ']]&gt;', $text);
				$text = trim($text);
				break;		
			default:
				break;
		}
		if ( !is_home() && !is_archive() && !is_search() ) {
			return $text;
		}		
		if ( '' !==  $manual_excerpt ) {
			$text = $manual_excerpt;
			$text = utf8_excerpt_readmore ($text);
			return $text;
		}		
		switch ($type) {
			case 'content':
				$more_position = stripos ($text, 'UTF8_EXCERPT_HAS_MORE');
				if ($more_position !== false) {
					$text = substr ($text, 0, -21);
					$text = utf8_excerpt_readmore ($text);
					return $text;
				}
				break;			
			case 'excerpt':
				$more_position = stripos ($text, "<!--more-->");
				if ($more_position !== false) {
					$text = substr ($text, 0, $more_position);
					$text = utf8_excerpt_readmore ($text);
				    	return $text;
				}
				break;			
			default:
				break;
		}		
		$home_excerpt_length = get_option('home_excerpt_length') ? get_option('home_excerpt_length') : HOME_EXCERPT_LENGTH;
		$archive_excerpt_length = get_option('archive_excerpt_length') ? get_option('archive_excerpt_length') : ARCHIVE_EXCERPT_LENGTH;
		$allowd_tag = get_option('allowd_tag') ? get_option('allowd_tag') : ALLOWD_TAG;
		if ( is_home()) {
			$length = $home_excerpt_length;
		} elseif ( is_archive() || is_search() ) {
			$length = $archive_excerpt_length;
		}		
		$strip_short_post = true;		
		if(($length > mb_strlen(strip_tags($text), 'utf-8')) && ($strip_short_post === true) ) {
			$text = strip_tags($text, $allowd_tag); 		
			$text = trim($text);
			$text = utf8_excerpt_readmore ($text);
			return $text;
		}		
		$text = strip_tags($text, $allowd_tag); 		
		$text = trim($text);
		$num = 0;
		$in_tag = false;
		for ($i=0; $num<$length || $in_tag; $i++) {
			if(mb_substr($text, $i, 1) == '<')
				$in_tag = true;
			elseif(mb_substr($text, $i, 1) == '>')
				$in_tag = false;
			elseif(!$in_tag)
				$num++;
		}
		$text = mb_substr ($text,0,$i, 'utf-8');    		
		$text = trim($text);
		$text = utf8_excerpt_readmore ($text);
		return $text;
	}
}
function utf8_excerpt_has_more( $more )
{
	if ( '' !== $more) {
		return 'UTF8_EXCERPT_HAS_MORE';
	} 
}
add_filter( 'the_content_more_link', 'utf8_excerpt_has_more' );
if (!function_exists('utf8_excerpt_readmore')) {
	function utf8_excerpt_readmore ($text) {
		$text .= "......";
		return $text;
	}
}
if (!function_exists('utf8_excerpt_for_excerpt')) {
	function utf8_excerpt_for_excerpt ($text) {
		return utf8_excerpt($text, 'excerpt');
	}
}
add_filter('get_the_excerpt', 'utf8_excerpt_for_excerpt', 9);
// Owo表情
function comment_add_owo($comment_text, $comment = '') {
    if($comment->comment_parent > 0) {
        $comment_text = '<strong><a href="#comment-' . $comment->comment_parent . '" title="' .get_comment_author( $comment->comment_parent ) . '" class="at-no-des">' .get_comment_author( $comment->comment_parent ) . '</a></strong>' . $comment_text;
    }
    $data_OwO = array(
        '@(暗地观察)' => '<img src="/wp-content/themes/Memory/OwO/alu/暗地观察.png" alt="暗地观察" style="vertical-align: middle;">',
        '@(便便)' => '<img src="/wp-content/themes/Memory/OwO/alu/便便.png" alt="便便" style="vertical-align: middle;">',
        '@(不出所料)' => '<img src="/wp-content/themes/Memory/OwO/alu/不出所料.png" alt="不出所料" style="vertical-align: middle;">',
        '@(不高兴)' => '<img src="/wp-content/themes/Memory/OwO/alu/不高兴.png" alt="不高兴" style="vertical-align: middle;">',
        '@(不说话)' => '<img src="/wp-content/themes/Memory/OwO/alu/不说话.png" alt="不说话" style="vertical-align: middle;">',
        '@(抽烟)' => '<img src="/wp-content/themes/Memory/OwO/alu/抽烟.png" alt="抽烟" style="vertical-align: middle;">',
        '@(呲牙)' => '<img src="/wp-content/themes/Memory/OwO/alu/呲牙.png" alt="呲牙" style="vertical-align: middle;">',
        '@(大囧)' => '<img src="/wp-content/themes/Memory/OwO/alu/大囧.png" alt="大囧" style="vertical-align: middle;">',
        '@(得意)' => '<img src="/wp-content/themes/Memory/OwO/alu/得意.png" alt="得意" style="vertical-align: middle;">',
        '@(愤怒)' => '<img src="/wp-content/themes/Memory/OwO/alu/愤怒.png" alt="愤怒" style="vertical-align: middle;">',
        '@(尴尬)' => '<img src="/wp-content/themes/Memory/OwO/alu/尴尬.png" alt="尴尬" style="vertical-align: middle;">',
        '@(高兴)' => '<img src="/wp-content/themes/Memory/OwO/alu/高兴.png" alt="高兴" style="vertical-align: middle;">',
        '@(鼓掌)' => '<img src="/wp-content/themes/Memory/OwO/alu/鼓掌.png" alt="鼓掌" style="vertical-align: middle;">',
        '@(观察)' => '<img src="/wp-content/themes/Memory/OwO/alu/观察.png" alt="观察" style="vertical-align: middle;">',
        '@(害羞)' => '<img src="/wp-content/themes/Memory/OwO/alu/害羞.png" alt="害羞" style="vertical-align: middle;">',
        '@(汗)' => '<img src="/wp-content/themes/Memory/OwO/alu/汗.png" alt="汗" style="vertical-align: middle;">',
        '@(黑线)' => '<img src="/wp-content/themes/Memory/OwO/alu/黑线.png" alt="黑线" style="vertical-align: middle;">',
        '@(欢呼)' => '<img src="/wp-content/themes/Memory/OwO/alu/欢呼.png" alt="欢呼" style="vertical-align: middle;">',
        '@(击掌)' => '<img src="/wp-content/themes/Memory/OwO/alu/击掌.png" alt="击掌" style="vertical-align: middle;">',
        '@(惊喜)' => '<img src="/wp-content/themes/Memory/OwO/alu/惊喜.png" alt="惊喜" style="vertical-align: middle;">',
        '@(看不见)' => '<img src="/wp-content/themes/Memory/OwO/alu/看不见.png" alt="看不见" style="vertical-align: middle;">',
        '@(看热闹)' => '<img src="/wp-content/themes/Memory/OwO/alu/看热闹.png" alt="看热闹" style="vertical-align: middle;">',
        '@(抠鼻)' => '<img src="/wp-content/themes/Memory/OwO/alu/抠鼻.png" alt="抠鼻" style="vertical-align: middle;">',
        '@(口水)' => '<img src="/wp-content/themes/Memory/OwO/alu/口水.png" alt="口水" style="vertical-align: middle;">',
        '@(哭泣)' => '<img src="/wp-content/themes/Memory/OwO/alu/哭泣.png" alt="哭泣" style="vertical-align: middle;">',
        '@(狂汗)' => '<img src="/wp-content/themes/Memory/OwO/alu/狂汗.png" alt="狂汗" style="vertical-align: middle;">',
        '@(蜡烛)' => '<img src="/wp-content/themes/Memory/OwO/alu/蜡烛.png" alt="蜡烛" style="vertical-align: middle;">',
        '@(脸红)' => '<img src="/wp-content/themes/Memory/OwO/alu/脸红.png" alt="脸红" style="vertical-align: middle;">',
        '@(内伤)' => '<img src="/wp-content/themes/Memory/OwO/alu/内伤.png" alt="内伤" style="vertical-align: middle;">',
        '@(喷水)' => '<img src="/wp-content/themes/Memory/OwO/alu/喷水.png" alt="喷水" style="vertical-align: middle;">',
        '@(喷血)' => '<img src="/wp-content/themes/Memory/OwO/alu/喷血.png" alt="喷血" style="vertical-align: middle;">',
        '@(期待)' => '<img src="/wp-content/themes/Memory/OwO/alu/期待.png" alt="期待" style="vertical-align: middle;">',
        '@(亲亲)' => '<img src="/wp-content/themes/Memory/OwO/alu/亲亲.png" alt="亲亲" style="vertical-align: middle;">',
        '@(傻笑)' => '<img src="/wp-content/themes/Memory/OwO/alu/傻笑.png" alt="傻笑" style="vertical-align: middle;">',
        '@(扇耳光)' => '<img src="/wp-content/themes/Memory/OwO/alu/扇耳光.png" alt="扇耳光" style="vertical-align: middle;">',
        '@(深思)' => '<img src="/wp-content/themes/Memory/OwO/alu/深思.png" alt="深思" style="vertical-align: middle;">',
        '@(锁眉)' => '<img src="/wp-content/themes/Memory/OwO/alu/锁眉.png" alt="锁眉" style="vertical-align: middle;">',
        '@(投降)' => '<img src="/wp-content/themes/Memory/OwO/alu/投降.png" alt="投降" style="vertical-align: middle;">',
        '@(吐)' => '<img src="/wp-content/themes/Memory/OwO/alu/吐.png" alt="吐" style="vertical-align: middle;">',
        '@(吐舌)' => '<img src="/wp-content/themes/Memory/OwO/alu/吐舌.png" alt="吐舌" style="vertical-align: middle;">',
        '@(吐血倒地)' => '<img src="/wp-content/themes/Memory/OwO/alu/吐血倒地.png" alt="吐血倒地" style="vertical-align: middle;">',
        '@(无奈)' => '<img src="/wp-content/themes/Memory/OwO/alu/无奈.png" alt="无奈" style="vertical-align: middle;">',
        '@(无所谓)' => '<img src="/wp-content/themes/Memory/OwO/alu/无所谓.png" alt="无所谓" style="vertical-align: middle;">',
        '@(无语)' => '<img src="/wp-content/themes/Memory/OwO/alu/无语.png" alt="无语" style="vertical-align: middle;">',
        '@(喜极而泣)' => '<img src="/wp-content/themes/Memory/OwO/alu/喜极而泣.png" alt="喜极而泣" style="vertical-align: middle;">',
        '@(献花)' => '<img src="/wp-content/themes/Memory/OwO/alu/献花.png" alt="献花" style="vertical-align: middle;">',
        '@(献黄瓜)' => '<img src="/wp-content/themes/Memory/OwO/alu/献黄瓜.png" alt="献黄瓜" style="vertical-align: middle;">',
        '@(想一想)' => '<img src="/wp-content/themes/Memory/OwO/alu/想一想.png" alt="想一想" style="vertical-align: middle;">',
        '@(小怒)' => '<img src="/wp-content/themes/Memory/OwO/alu/小怒.png" alt="小怒" style="vertical-align: middle;">',
        '@(小眼睛)' => '<img src="/wp-content/themes/Memory/OwO/alu/小眼睛.png" alt="小眼睛" style="vertical-align: middle;">',
        '@(邪恶)' => '<img src="/wp-content/themes/Memory/OwO/alu/邪恶.png" alt="邪恶" style="vertical-align: middle;">',
        '@(咽气)' => '<img src="/wp-content/themes/Memory/OwO/alu/咽气.png" alt="咽气" style="vertical-align: middle;">',
        '@(阴暗)' => '<img src="/wp-content/themes/Memory/OwO/alu/阴暗.png" alt="阴暗" style="vertical-align: middle;">',
        '@(赞一个)' => '<img src="/wp-content/themes/Memory/OwO/alu/赞一个.png" alt="赞一个" style="vertical-align: middle;">',
        '@(长草)' => '<img src="/wp-content/themes/Memory/OwO/alu/长草.png" alt="长草" style="vertical-align: middle;">',
        '@(中刀)' => '<img src="/wp-content/themes/Memory/OwO/alu/中刀.png" alt="中刀" style="vertical-align: middle;">',
        '@(中枪)' => '<img src="/wp-content/themes/Memory/OwO/alu/中枪.png" alt="中枪" style="vertical-align: middle;">',
        '@(中指)' => '<img src="/wp-content/themes/Memory/OwO/alu/中指.png" alt="中指" style="vertical-align: middle;">',
        '@(肿包)' => '<img src="/wp-content/themes/Memory/OwO/alu/肿包.png" alt="肿包" style="vertical-align: middle;">',
        '@(皱眉)' => '<img src="/wp-content/themes/Memory/OwO/alu/皱眉.png" alt="皱眉" style="vertical-align: middle;">',
        '@(装大款)' => '<img src="/wp-content/themes/Memory/OwO/alu/装大款.png" alt="装大款" style="vertical-align: middle;">',
        '@(坐等)' => '<img src="/wp-content/themes/Memory/OwO/alu/坐等.png" alt="坐等" style="vertical-align: middle;">',
        '@[啊]' => '<img src="/wp-content/themes/Memory/OwO/paopao/啊.png" alt="啊" style="vertical-align: middle;">',
        '@[爱心]' => '<img src="/wp-content/themes/Memory/OwO/paopao/爱心.png" alt="爱心" style="vertical-align: middle;">',
        '@[鄙视]' => '<img src="/wp-content/themes/Memory/OwO/paopao/鄙视.png" alt="鄙视" style="vertical-align: middle;">',
        '@[便便]' => '<img src="/wp-content/themes/Memory/OwO/paopao/便便.png" alt="便便" style="vertical-align: middle;">',
        '@[不高兴]' => '<img src="/wp-content/themes/Memory/OwO/paopao/不高兴.png" alt="不高兴" style="vertical-align: middle;">',
        '@[彩虹]' => '<img src="/wp-content/themes/Memory/OwO/paopao/彩虹.png" alt="彩虹" style="vertical-align: middle;">',
        '@[茶杯]' => '<img src="/wp-content/themes/Memory/OwO/paopao/茶杯.png" alt="茶杯" style="vertical-align: middle;">',
        '@[吃瓜]' => '<img src="/wp-content/themes/Memory/OwO/paopao/吃瓜.png" alt="吃瓜" style="vertical-align: middle;">',
        '@[吃翔]' => '<img src="/wp-content/themes/Memory/OwO/paopao/吃翔.png" alt="吃翔" style="vertical-align: middle;">',
        '@[大拇指]' => '<img src="/wp-content/themes/Memory/OwO/paopao/大拇指.png" alt="大拇指" style="vertical-align: middle;">',
        '@[蛋糕]' => '<img src="/wp-content/themes/Memory/OwO/paopao/蛋糕.png" alt="蛋糕" style="vertical-align: middle;">',
        '@[嘚瑟]' => '<img src="/wp-content/themes/Memory/OwO/paopao/嘚瑟.png" alt="嘚瑟" style="vertical-align: middle;">',
        '@[灯泡]' => '<img src="/wp-content/themes/Memory/OwO/paopao/灯泡.png" alt="灯泡" style="vertical-align: middle;">',
        '@[乖]' => '<img src="/wp-content/themes/Memory/OwO/paopao/乖.png" alt="乖" style="vertical-align: middle;">',
        '@[哈哈]' => '<img src="/wp-content/themes/Memory/OwO/paopao/哈哈.png" alt="哈哈" style="vertical-align: middle;">',
        '@[汗]' => '<img src="/wp-content/themes/Memory/OwO/paopao/汗.png" alt="汗" style="vertical-align: middle;">',
        '@[呵呵]' => '<img src="/wp-content/themes/Memory/OwO/paopao/呵呵.png" alt="呵呵" style="vertical-align: middle;">',
        '@[黑线]' => '<img src="/wp-content/themes/Memory/OwO/paopao/黑线.png" alt="黑线" style="vertical-align: middle;">',
        '@[红领巾]' => '<img src="/wp-content/themes/Memory/OwO/paopao/红领巾.png" alt="红领巾" style="vertical-align: middle;">',
        '@[呼]' => '<img src="/wp-content/themes/Memory/OwO/paopao/呼.png" alt="呼" style="vertical-align: middle;">',
        '@[花心]' => '<img src="/wp-content/themes/Memory/OwO/paopao/花心.png" alt="花心" style="vertical-align: middle;">',
        '@[滑稽]' => '<img src="/wp-content/themes/Memory/OwO/paopao/滑稽.png" alt="滑稽" style="vertical-align: middle;">',
        '@[惊恐]' => '<img src="/wp-content/themes/Memory/OwO/paopao/惊恐.png" alt="惊恐" style="vertical-align: middle;">',
        '@[惊哭]' => '<img src="/wp-content/themes/Memory/OwO/paopao/惊哭.png" alt="惊哭" style="vertical-align: middle;">',
        '@[惊讶]' => '<img src="/wp-content/themes/Memory/OwO/paopao/惊讶.png" alt="惊讶" style="vertical-align: middle;">',
        '@[开心]' => '<img src="/wp-content/themes/Memory/OwO/paopao/开心.png" alt="开心" style="vertical-align: middle;">',
        '@[酷]' => '<img src="/wp-content/themes/Memory/OwO/paopao/酷.png" alt="酷" style="vertical-align: middle;">',
        '@[狂汗]' => '<img src="/wp-content/themes/Memory/OwO/paopao/狂汗.png" alt="狂汗" style="vertical-align: middle;">',
        '@[蜡烛]' => '<img src="/wp-content/themes/Memory/OwO/paopao/蜡烛.png" alt="蜡烛" style="vertical-align: middle;">',
        '@[懒得理]' => '<img src="/wp-content/themes/Memory/OwO/paopao/懒得理.png" alt="懒得理" style="vertical-align: middle;">',
        '@[泪]' => '<img src="/wp-content/themes/Memory/OwO/paopao/泪.png" alt="泪" style="vertical-align: middle;">',
        '@[冷]' => '<img src="/wp-content/themes/Memory/OwO/paopao/冷.png" alt="冷" style="vertical-align: middle;">',
        '@[礼物]' => '<img src="/wp-content/themes/Memory/OwO/paopao/礼物.png" alt="礼物" style="vertical-align: middle;">',
        '@[玫瑰]' => '<img src="/wp-content/themes/Memory/OwO/paopao/玫瑰.png" alt="玫瑰" style="vertical-align: middle;">',
        '@[勉强]' => '<img src="/wp-content/themes/Memory/OwO/paopao/勉强.png" alt="勉强" style="vertical-align: middle;">',
        '@[你懂的]' => '<img src="/wp-content/themes/Memory/OwO/paopao/你懂的.png" alt="你懂的" style="vertical-align: middle;">',
        '@[怒]' => '<img src="/wp-content/themes/Memory/OwO/paopao/怒.png" alt="怒" style="vertical-align: middle;">',
        '@[喷]' => '<img src="/wp-content/themes/Memory/OwO/paopao/喷.png" alt="喷" style="vertical-align: middle;">',
        '@[钱]' => '<img src="/wp-content/themes/Memory/OwO/paopao/钱.png" alt="钱" style="vertical-align: middle;">',
        '@[钱币]' => '<img src="/wp-content/themes/Memory/OwO/paopao/钱币.png" alt="钱币" style="vertical-align: middle;">',
        '@[弱]' => '<img src="/wp-content/themes/Memory/OwO/paopao/弱.png" alt="弱" style="vertical-align: middle;">',
        '@[三道杠]' => '<img src="/wp-content/themes/Memory/OwO/paopao/三道杠.png" alt="三道杠" style="vertical-align: middle;">',
        '@[沙发]' => '<img src="/wp-content/themes/Memory/OwO/paopao/沙发.png" alt="沙发" style="vertical-align: middle;">',
        '@[生气]' => '<img src="/wp-content/themes/Memory/OwO/paopao/生气.png" alt="生气" style="vertical-align: middle;">',
        '@[胜利]' => '<img src="/wp-content/themes/Memory/OwO/paopao/胜利.png" alt="胜利" style="vertical-align: middle;">',
        '@[手纸]' => '<img src="/wp-content/themes/Memory/OwO/paopao/手纸.png" alt="手纸" style="vertical-align: middle;">',
        '@[睡觉]' => '<img src="/wp-content/themes/Memory/OwO/paopao/睡觉.png" alt="睡觉" style="vertical-align: middle;">',
        '@[酸爽]' => '<img src="/wp-content/themes/Memory/OwO/paopao/酸爽.png" alt="酸爽" style="vertical-align: middle;">',
        '@[太开心]' => '<img src="/wp-content/themes/Memory/OwO/paopao/太开心.png" alt="太开心" style="vertical-align: middle;">',
        '@[太阳]' => '<img src="/wp-content/themes/Memory/OwO/paopao/太阳.png" alt="太阳" style="vertical-align: middle;">',
        '@[吐]' => '<img src="/wp-content/themes/Memory/OwO/paopao/吐.png" alt="吐" style="vertical-align: middle;">',
        '@[吐舌]' => '<img src="/wp-content/themes/Memory/OwO/paopao/吐舌.png" alt="吐舌" style="vertical-align: middle;">',
        '@[挖鼻]' => '<img src="/wp-content/themes/Memory/OwO/paopao/挖鼻.png" alt="挖鼻" style="vertical-align: middle;">',
        '@[委屈]' => '<img src="/wp-content/themes/Memory/OwO/paopao/委屈.png" alt="委屈" style="vertical-align: middle;">',
        '@[捂嘴笑]' => '<img src="/wp-content/themes/Memory/OwO/paopao/捂嘴笑.png" alt="捂嘴笑" style="vertical-align: middle;">',
        '@[犀利]' => '<img src="/wp-content/themes/Memory/OwO/paopao/犀利.png" alt="犀利" style="vertical-align: middle;">',
        '@[香蕉]' => '<img src="/wp-content/themes/Memory/OwO/paopao/香蕉.png" alt="香蕉" style="vertical-align: middle;">',
        '@[小乖]' => '<img src="/wp-content/themes/Memory/OwO/paopao/小乖.png" alt="小乖" style="vertical-align: middle;">',
        '@[小红脸]' => '<img src="/wp-content/themes/Memory/OwO/paopao/小红脸.png" alt="小红脸" style="vertical-align: middle;">',
        '@[笑尿]' => '<img src="/wp-content/themes/Memory/OwO/paopao/笑尿.png" alt="笑尿" style="vertical-align: middle;">',
        '@[笑眼]' => '<img src="/wp-content/themes/Memory/OwO/paopao/笑眼.png" alt="笑眼" style="vertical-align: middle;">',
        '@[心碎]' => '<img src="/wp-content/themes/Memory/OwO/paopao/心碎.png" alt="心碎" style="vertical-align: middle;">',
        '@[星星月亮]' => '<img src="/wp-content/themes/Memory/OwO/paopao/星星月亮.png" alt="星星月亮" style="vertical-align: middle;">',
        '@[呀咩爹]' => '<img src="/wp-content/themes/Memory/OwO/paopao/呀咩爹.png" alt="呀咩爹" style="vertical-align: middle;">',
        '@[药丸]' => '<img src="/wp-content/themes/Memory/OwO/paopao/药丸.png" alt="药丸" style="vertical-align: middle;">',
        '@[咦]' => '<img src="/wp-content/themes/Memory/OwO/paopao/咦.png" alt="咦" style="vertical-align: middle;">',
        '@[疑问]' => '<img src="/wp-content/themes/Memory/OwO/paopao/疑问.png" alt="疑问" style="vertical-align: middle;">',
        '@[阴险]' => '<img src="/wp-content/themes/Memory/OwO/paopao/阴险.png" alt="阴险" style="vertical-align: middle;">',
        '@[音乐]' => '<img src="/wp-content/themes/Memory/OwO/paopao/音乐.png" alt="音乐" style="vertical-align: middle;">',
        '@[真棒]' => '<img src="/wp-content/themes/Memory/OwO/paopao/真棒.png" alt="真棒" style="vertical-align: middle;">',
        '@[nico]' => '<img src="/wp-content/themes/Memory/OwO/paopao/nico.png" alt="nico" style="vertical-align: middle;">',
        '@[OK]' => '<img src="/wp-content/themes/Memory/OwO/paopao/OK.png" alt="OK" style="vertical-align: middle;">',
        '@[what]' => '<img src="/wp-content/themes/Memory/OwO/paopao/what.png" alt="what" style="vertical-align: middle;">'
    );
    return strtr($comment_text,$data_OwO);
}
add_filter( 'comment_text' , 'comment_add_owo', 20, 2);
// 验证码功能
function Memory_protection_math(){
	//获取两个随机数, 范围0~9
	$num1=rand(0,9);
	$num2=rand(0,9);
	//最终网页中的具体内容
	echo "<input type='text' name='sum' class='text-input sum' value='' placeholder='$num1 + $num2 = ?'>"
."<input type='hidden' name='num1' value='$num1'>"
."<input type='hidden' name='num2' value='$num2'>";
}
function Memory_protection_pre($commentdata){
	$sum=$_POST['sum'];//用户提交的计算结果
	switch($sum){
		//得到正确的计算结果则直接跳出
		case $_POST['num1']+$_POST['num2']:break;
		//未填写结果时的错误讯息
		case null:wp_die('错误: 请输入验证码.');break;
		//计算错误时的错误讯息
		default:wp_die('错误: 验证码错误,请重试.');
	}
	return $commentdata;
}
if($comment_data['comment_type']==''){
	add_filter('preprocess_comment','Memory_protection_pre');
}
// 喜欢功能
add_action('wp_ajax_nopriv_memory_like', 'memory_ding');
add_action('wp_ajax_memory_like', 'memory_ding');
function memory_ding(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
    $memory_raters = get_post_meta($id,'memory_ding',true);
    $expire = time() + 99999999;
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
    setcookie('memory_ding_'.$id,$id,$expire,'/',$domain,false);
    if (!$memory_raters || !is_numeric($memory_raters)) {
        update_post_meta($id, 'memory_ding', 1);
    }
    else {
            update_post_meta($id, 'memory_ding', ($memory_raters + 1));
        }
    echo get_post_meta($id,'memory_ding',true);
    }
    die;
}

// Do you like me?
function Memory_doyoulikeme() {
	global $pagenow;   
    //判断是否为激活主题页面   
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){   
		$sql_1="CREATE TABLE IF NOT EXISTS `wp_votes_num` (
			`id` int(10) NOT NULL AUTO_INCREMENT,
			`likes` int(10) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$sql_3="CREATE TABLE IF NOT EXISTS `wp_votes_ip` (
			`id` int(10) NOT NULL AUTO_INCREMENT,
			`ip` varchar(40) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql_1);
		$data['id'] = '1';
		$data['likes'] = '0';
		$wpdb->insert('wp_votes_num', $data);
		dbDelta($sql_3);
    }  	
}
add_action( 'load-themes.php', 'Memory_doyoulikeme' );

// 前台评论添加“删除”和“标识为垃圾”链接
function comment_manage_link($id) {
	global $comment, $post;
	$id = $comment->comment_ID;
	if(current_user_can( 'moderate_comments', $post->ID )){
		if ( null === $link ) $link = __('编辑');
		$link = '<a class="comment-edit-link" href="' . get_edit_comment_link( $comment->comment_ID ) . '" title="' . __( '编辑评论' ) . '">' . $link . '</a>';
		$link = $link . '<a href="'.admin_url("comment.php?action=cdc&c=$id").'">删除</a> ';
		$link = $link . '<a href="'.admin_url("comment.php?action=cdc&dt=spam&c=$id").'">标记为垃圾评论</a>';
		$link = $before . $link . $after;
		return $link;
	}
}
add_filter('edit_comment_link', 'comment_manage_link');

// py！
function memory_donate() {
	if( get_option( 'memory_zhifubao_donate' )!=null or get_option( 'memory_weixin_donate' )!=null ) { 
		echo '<div class="erweima">';
		if( get_option( 'memory_zhifubao_donate' )!=null ) {
			echo '<img  class="zhifubaodonate" src="' . get_option( 'memory_zhifubao_donate' ) . '" /> ';
		}
		if( get_option( 'memory_weixin_donate' )!=null ) {
			echo '<img class="weixindonate" src="' . get_option( 'memory_weixin_donate' ) . '" />';
		}
		echo '</div>';
	}
}

//自动创建页面
function memory_add_pages() {   
    global $pagenow;   
    //判断是否为激活主题页面   
    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ){
        memory_add_page('分类','category','memory-category.php'); //页面标题、别名、页面模板  
        memory_add_page('友情链接','friend-link','friend-link.php');
        memory_add_page('时光轴','timeline','archives.php');
    }   
}
