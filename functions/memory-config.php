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

// 后台菜单
// memory_add_menus() 为 'admin_menu' 钩子的回调函数
function memory_add_menus() {
	add_menu_page('Memory Settings', 'Memory主题设置', 'manage_options', __FILE__, 'memory_menu');
	add_submenu_page(__FILE__,'Website Recommend','常用网站推荐',8,'memory_submenu_recommend','memory_submenu_recommend');
}

function memory_menu() { ?>
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/config.css?ver=<?php echo wp_get_theme()->get('Version'); ?>">
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-3.2.1.min.js"></script>
	<div class="wrap">
		<h2>	
			<span id="theme-name">Memory V<?php echo wp_get_theme()->get('Version'); ?></span>
			<span id="theme-welcome">欢迎使用Wordpress主题Memory！</span>
		</h2>
		<ul id="config-menu">
			<li class="active" menu-part="1"><a>基础设置</a></li>
			<li menu-part="2"><a>社交信息</a></li>
			<li menu-part="3"><a>评论设置</a></li>
			<li menu-part="4"><a>样式设置</a></li>
			<li menu-part="5"><a>SEO设置</a></li>
		</ul>
    <?php
	if ($_POST['update_options']=='true') {//若提交了表单，则保存变量
		//基础设置
		//基本信息
    	memory_up_or_del('memory_username');//博主昵称
    	memory_up_or_del('memory_useravatar');//博主头像地址
    	memory_up_or_del('memory_mobilebck');//手机侧边背景
    	memory_up_or_del('memory_mobile_qm');//签名
		memory_up_or_del('memory_beian');// 备案
		memory_up_or_del('memory_copyright');// copyright
		//博客建立日期
		memory_up_or_del('memory_setuptime_year');
		memory_up_or_del('memory_setuptime_month');
		memory_up_or_del('memory_setuptime_day');
		// 分享设置
		memory_up('memory_share_qq');
		memory_up('memory_share_weibo');
		memory_up('memory_share_qzone');
		memory_up('memory_share_weixin');
		memory_up('memory_share_qqweibo');
		memory_up('memory_share_douban');
		memory_up('memory_share_linkedin');
		memory_up('memory_share_diandian');
		memory_up('memory_share_facebook');
		memory_up('memory_share_twitter');
		memory_up('memory_share_google');
		$share=array();
		if ( get_option( 'memory_share_qq' )==1 ) array_push($share,"qq");
		if ( get_option( 'memory_share_weibo' )==1 ) array_push($share,"weibo");
		if ( get_option( 'memory_share_qzone' )==1 ) array_push($share,"qzone");
		if ( get_option( 'memory_share_weixin' )==1 ) array_push($share,"wechat");
		if ( get_option( 'memory_share_qqweibo' )==1 ) array_push($share,"tencent");
		if ( get_option( 'memory_share_douban' )==1 ) array_push($share,"douban");
		if ( get_option( 'memory_share_linkedin' )==1 ) array_push($share,"linkedin");
		if ( get_option( 'memory_share_diandian' )==1 ) array_push($share,"diandian");
		if ( get_option( 'memory_share_facebook' )==1 ) array_push($share,"facebook");
		if ( get_option( 'memory_share_twitter' )==1 ) array_push($share,"twitter");
		if ( get_option( 'memory_share_google' )==1 ) array_push($share,"google");
		$memory_share = implode(",",$share);
		update_option('memory_share', $memory_share);
		
		// 打赏设置 
		memory_up_or_del('memory_zhifubao_donate');
		memory_up_or_del('memory_weixin_donate');
		
		// 社交信息
		memory_up_or_del('memory_github');
		memory_up_or_del('memory_weibo');
		memory_up_or_del('memory_zhihu');
		memory_up_or_del('memory_email');
		memory_up_or_del('memory_qqqun');
		memory_up_or_del('memory_qq');

		// 评论设置
		memory_up_or_del('memory_comment_default');		
		memory_up('memory_comment_reply');
		memory_up('memory_touxian');
		memory_up_or_del('memory_com_vip');
		memory_up_or_del('memory_com_vip1');
		memory_up_or_del('memory_com_vip2');
		memory_up_or_del('memory_com_vip3');
		memory_up_or_del('memory_com_vip4');
		memory_up_or_del('memory_com_vip5');
		memory_up_or_del('memory_com_vip6');
		memory_up_or_del('memory_com_vip7');

		// 样式设置
		memory_up('memory_canvas_or_background');
		memory_up_or_del('memory_background');
		memory_up('memory_have_header_picture');
		memory_up_or_del('memory_header_picture');
		memory_up_or_del('memory_foot_color');
		memory_up_or_del('memory_user_style');

		// SEO设置
		memory_up_or_del('memory_description');
		memory_up_or_del('memory_keywords');

		echo '<script>alert("保存成功！");</script>';//保存完毕显示文字提示
	}
    ?>
		<form action="" method="post" id="memory_menu_form">
			<input type="hidden" name="update_options" value="true" />
			<div id="part-1" class="show-part">
				<table class="form-table">
					<tr class="setting-title">
						<th scope="row"><h2>基础信息</h2></th>
					</tr>
	          		<tr style="border-top: 1px solid #d5dfe2;">
	              		<th scope="row"><label for="memory_username">博主昵称:</label></th>
	    				<td>
	                  		<input type="text" class="regular-text" name="memory_username" id="memory_username" value="<?php echo get_option('memory_username'); ?>" />
	                	</td>
	            	</tr>
					<tr>
	              		<th scope="row"><label for="memory_useravatar">博主头像地址:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_useravatar" id="memory_useravatar" value="<?php echo get_option('memory_useravatar'); ?>" />
							<a id="memory_useravatar_upload" class="button" href="#">选择/上传图片</a>
	    				</td>
					</tr>
					<tr>
						<th scope="row"><label for="memory_mobilebck">移动端侧边背景:</label></th>
						<td>
							<input type="text" class="regular-text" name="memory_mobilebck" id="memory_mobilebck" value="<?php echo get_option('memory_mobilebck'); ?>" />
							<a id="memory_mobilebck_upload" class="button" href="#">选择/上传图片</a>
						</td>
					</tr>
					<tr>
	              		<th scope="row"><label for="memory_mobile_qm">签名:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_mobile_qm" id="memory_mobile_qm" value="<?php echo get_option('memory_mobile_qm'); ?>" />
	    				</td>
	    			</tr>
					<tr>
	              		<th scope="row"><label for="memory_beian">备案号:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_beian" id="memory_beian" value="<?php echo get_option('memory_beian'); ?>" />
	    				</td>
	    			</tr>   
	            	<tr>
	              		<th scope="row"><label for="memory_copyright">Copyright:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_copyright" id="memory_copyright" value="<?php echo get_option('memory_copyright'); ?>" />
	    				</td>
	    			</tr>  
	            	<tr>
	              		<th scope="row"><label for="memory_setuptime_year">博客建立日期:</label></th>
	    				<td>
	                		<input type="text" name="memory_setuptime_year" id="memory_setuptime_year" value="<?php echo get_option('memory_setuptime_year'); ?>" size="4" maxlength="4" />年
							<input type="text" name="memory_setuptime_month" id="memory_setuptime_month" value="<?php echo get_option('memory_setuptime_month'); ?>" maxlength="2" />月
							<input type="text" name="memory_setuptime_day" id="memory_setuptime_day" value="<?php echo get_option('memory_setuptime_day'); ?>" maxlength="2" />日
	    				</td>
	    			</tr>
					<tr class="setting-title">
						<th scope="row"><h2>分享设置</h2></th>
					</tr>
					<tr style="border-top: 1px solid #d5dfe2;">
	              		<th scope="row"><label for="memory_share">分享设置:</label></th>
	    				<td>
	                		<input type="checkbox" name="memory_share_qq" id="memory_share_qq" value="1" <?php checked( '1', get_option( 'memory_share_qq' ) ); ?>  />&nbsp;QQ&nbsp;
	                		<input type="checkbox" name="memory_share_weibo" id="memory_share_weibo" value="1" <?php checked( '1', get_option( 'memory_share_weibo' ) ); ?>  />&nbsp;微博&nbsp;
	                		<input type="checkbox" name="memory_share_qzone" id="memory_share_qzone" value="1" <?php checked( '1', get_option( 'memory_share_qzone' ) ); ?>  />&nbsp;QQ空间&nbsp;
	                		<input type="checkbox" name="memory_share_weixin" id="memory_share_weixin" value="1" <?php checked( '1', get_option( 'memory_share_weixin' ) ); ?>  />&nbsp;微信&nbsp;
	                		<input type="checkbox" name="memory_share_qqweibo" id="memory_share_qqweibo" value="1" <?php checked( '1', get_option( 'memory_share_qqweibo' ) ); ?>  />&nbsp;腾讯微博&nbsp;
	                		<input type="checkbox" name="memory_share_douban" id="memory_share_douban" value="1" <?php checked( '1', get_option( 'memory_share_douban' ) ); ?>  />&nbsp;豆瓣&nbsp;
	                		<input type="checkbox" name="memory_share_linkedin" id="memory_share_linkedin" value="1" <?php checked( '1', get_option( 'memory_share_linkedin' ) ); ?>  />&nbsp;LinkedIn&nbsp;
	                		<input type="checkbox" name="memory_share_diandian" id="memory_share_diandian" value="1" <?php checked( '1', get_option( 'memory_share_diandian' ) ); ?>  />&nbsp;点点&nbsp;
	                		<input type="checkbox" name="memory_share_facebook" id="memory_share_facebook" value="1" <?php checked( '1', get_option( 'memory_share_facebook' ) ); ?>  />&nbsp;Facebook&nbsp;
	                		<input type="checkbox" name="memory_share_twitter" id="memory_share_twitter" value="1" <?php checked( '1', get_option( 'memory_share_twitter' ) ); ?>  />&nbsp;Twitter&nbsp;
	                		<input type="checkbox" name="memory_share_google" id="memory_share_google" value="1" <?php checked( '1', get_option( 'memory_share_google' ) ); ?>  />&nbsp;Google&nbsp;
	    				</td>
	    			</tr>
					<tr class="setting-title">
						<th scope="row"><h2>打赏设置</h2></th>
					</tr>
					<tr style="border-top: 1px solid #d5dfe2;">
	              		<th scope="row">
							<label for="memory_zhifubao_donate">支付宝二维码:</label>
						</th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_zhifubao_donate" id="memory_zhifubao_donate" value="<?php echo get_option('memory_zhifubao_donate'); ?>" />
						<a id="memory_zhifubao_donate_upload" class="button" href="#">选择/上传图片</a>
	    				</td>
	    			</tr>
					<tr>
	              		<th scope="row">
							<label for="memory_weixin_donate">微信二维码:</label>
						</th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_weixin_donate" id="memory_weixin_donate" value="<?php echo get_option('memory_weixin_donate'); ?>" />
						<a id="memory_weixin_donate_upload" class="button" href="#">选择/上传图片</a>
	    				</td>
	    			</tr>
				</table>
				<p style="clear:both;"><input type="submit" class="button-primary" name="admin_options" value="保存"/></p>
			</div>
			<div id="part-2">
				<table class="form-table">
					<tr>
	              		<th scope="row"><label for="memory_github">Github主页:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_github" id="memory_github" value="<?php echo get_option('memory_github'); ?>" />
	    				</td>
	    			</tr>  
	    			<tr>
	              		<th scope="row"><label for="memory_weibo">微博主页地址:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_weibo" id="memory_weibo" value="<?php echo get_option('memory_weibo'); ?>" />
	    				</td>
	    			</tr>
	    			<tr>
	              		<th scope="row"><label for="memory_zhihu">知乎主页地址:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_zhihu" id="memory_zhihu" value="<?php echo get_option('memory_zhihu'); ?>" />
	    				</td>
	    			</tr>
	    			<tr>
	              		<th scope="row"><label for="memory_email">电子邮箱:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_email" id="memory_email" value="<?php echo get_option('memory_email'); ?>" />
	    				</td>
	    			</tr>
	    			<tr>
						<th scope="row"><label for="memory_qq">QQ:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_qq" id="memory_qq" value=" <?php echo get_option('memory_qq'); ?>" />
						</td>
	    			</tr>
	    			<tr>
						<th scope="row"><label for="memory_qqqun">QQ群加群链接:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_qqqun" id="memory_qqqun" value=" <?php echo get_option('memory_qqqun'); ?>" />
						</td>
	    			</tr>
				</table>
				<p style="clear:both;"><input type="submit" class="button-primary" name="admin_options" value="保存"/></p>
			</div>
			<div id="part-3">
				<table class="form-table">
					<tr>
	              		<th scope="row">
							<label for="memory_comment_default">评论默认头像:</label>
						</th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_comment_default" id="memory_comment_default" value="<?php echo get_option('memory_comment_default'); ?>" />
						<a id="memory_comment_default_upload" class="button" href="#">选择/上传图片</a>
	    				</td>
	    			</tr>
					<tr>
	              		<th scope="row">评论邮件回复:</th>
	    				<td>
	                		<input type="checkbox" name="memory_comment_reply" id="memory_comment_reply" class="choose" value="1" <?php checked( '1', get_option( 'memory_comment_reply' ) ); ?>  />
							<label for="memory_comment_reply" class="select">
	                			<span class="circle"></span>
	                			<span class="text on"></span>
	                			<span class="text off"></span>
	            			</label>
	    				</td>
	    			</tr>
					<tr class="setting-title">
						<th scope="row"><h2>评论头衔设置</h2></th>
					</tr>
					<tr style="border-top: 1px solid #d5dfe2;">
	              		<th scope="row">使用评论头衔:</th>
	    				<td>
	                		<input type="checkbox" name="memory_touxian" id="memory_touxian" class="choose" value="1" <?php checked( '1', get_option( 'memory_touxian' ) ); ?>  />
							<label for="memory_touxian" class="select">
	                			<span class="circle"></span>
	                			<span class="text on"></span>
	                			<span class="text off"></span>
	            			</label>
	    				</td>
	    			</tr>
					<tr>
						<th scope="row"><label for="memory_com_vip">博主头衔:</label></th>
	    				<td>
							<input type="text" class="regular-text" name="memory_com_vip" id="memory_com_vip" value="<?php echo get_option('memory_com_vip'); ?>" />
	    				</td>
	    			</tr> 
					<tr>
					<tr>
	              		<th scope="row"><label for="memory_com_vip1">等级1名称:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_com_vip1" id="memory_com_vip1" value="<?php echo get_option('memory_com_vip1'); ?>" />
	    				</td>
	    			</tr> 
					<tr>
	              		<th scope="row"><label for="memory_com_vip2">等级2名称:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_com_vip2" id="memory_com_vip2" value="<?php echo get_option('memory_com_vip2'); ?>" />
	    				</td>
	    			</tr> 
					<tr>
	              		<th scope="row"><label for="memory_com_vip3">等级3名称:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_com_vip3" id="memory_com_vip3" value="<?php echo get_option('memory_com_vip3'); ?>" />
	    				</td>
	    			</tr> 
					<tr>
	              		<th scope="row"><label for="memory_com_vip4">等级4名称:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_com_vip4" id="memory_com_vip4" value="<?php echo get_option('memory_com_vip4'); ?>" />
	    				</td>
	    			</tr> 
					<tr>
	              		<th scope="row"><label for="memory_com_vip5">等级5名称:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_com_vip5" id="memory_com_vip5" value="<?php echo get_option('memory_com_vip5'); ?>" />
	    				</td>
	    			</tr> 
					<tr>
	              		<th scope="row"><label for="memory_com_vip6">等级6名称:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_com_vip6" id="memory_com_vip6" value="<?php echo get_option('memory_com_vip6'); ?>" />
	    				</td>
	    			</tr> 
					<tr>
	              		<th scope="row"><label for="memory_com_vip7">等级7名称:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_com_vip7" id="memory_com_vip7" value="<?php echo get_option('memory_com_vip7'); ?>" />
	    				</td>
	    			</tr> 
				</table>
				<p style="clear:both;"><input type="submit" class="button-primary" name="admin_options" value="保存"/></p>
			</div>
			<div id="part-4">
				<table class="form-table">
					<tr>
	              		<th scope="row">
							<input name="memory_canvas_or_background" type="radio" value="0" <?php checked( '0', get_option( 'memory_canvas_or_background' ) ); ?> /><label for="memory_canvas">条带状背景</label><br/>
							<input name="memory_canvas_or_background" type="radio" value="1" <?php checked( '1', get_option( 'memory_canvas_or_background' ) ); ?> /><label for="memory_background">背景图片</label>
						</th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_background" id="memory_background" value="<?php echo get_option('memory_background'); ?>" />
						<a id="memory_background_upload" class="button" href="#">选择/上传图片</a>
	    				</td>
	    			</tr>
					<tr>
	              		<th scope="row">是否使用头部壁纸:</th>
	    				<td>
	                		<input type="checkbox" name="memory_have_header_picture" id="memory_have_header_picture" class="choose" value="1" <?php checked( '1', get_option( 'memory_have_header_picture' ) ); ?>  />
							<label for="memory_have_header_picture" class="select">
	                			<span class="circle"></span>
	                			<span class="text on"></span>
	                			<span class="text off"></span>
	            			</label>
	    				</td>
	    			</tr>
					<tr>
	              		<th scope="row">
							<label for="memory_header_picture">头部壁纸图片:</label>
						</th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_header_picture" id="memory_header_picture" value="<?php echo get_option('memory_header_picture'); ?>" />
							<a id="memory_header_picture_upload" class="button" href="#">选择/上传图片</a>
	    				</td>
	    			</tr>
	            	<tr>
	              		<th scope="row"><label for="memory_foot_color">页脚字体颜色:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_foot_color" id="memory_foot_color" value="<?php echo get_option('memory_foot_color'); ?>" />
	    				</td>
	    			</tr>
	            	<tr>
	              		<th scope="row"><label for="memory_user_style">自定义css:</label></th>
	    				<td>
	                		<textarea name="memory_user_style" id="memory_user_style" rows="10" cols="50" class="large-text code" ><?php echo get_option('memory_user_style'); ?></textarea>
	    				</td>
	    			</tr>
				</table>
				<p style="clear:both;"><input type="submit" class="button-primary" name="admin_options" value="保存"/></p>
			</div>
			<div id="part-5">
				<table class="form-table">
					<tr>
	              		<th scope="row"><label for="memory_description">博客描述:</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_description" id="memory_description" value="<?php echo get_option('memory_description'); ?>" />
	    				</td>
	    			</tr>   
	    			<tr>
	              		<th scope="row"><label for="memory_keywords">博客关键词:(用','分隔)</label></th>
	    				<td>
	                		<input type="text" class="regular-text" name="memory_keywords" id="memory_keywords" value="<?php echo get_option('memory_keywords'); ?>" />
	    				</td>
	    			</tr>
				</table>
				<p style="clear:both;"><input type="submit" class="button-primary" name="admin_options" value="保存"/></p>
			</div>
			<input type="hidden" value="<?php echo get_option('memory_share'); ?>" />
	    </form>
	</div>
<?php
wp_enqueue_media(); //在设置页面需要加载媒体中心
?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/config.js"></script>
<?php
}

function memory_up_or_del($op) {
	update_option($op, $_POST[$op]);
	//若值为空，则删除这行数据
	if( empty($_POST[$op]) ) delete_option($op);
}

function memory_up($op) {
	update_option($op, $_POST[$op]);
}

function memory_submenu_recommend() { ?>
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/config.css">
	<h2>常用网站推荐</h2>
	<p><a href="https://shawnzeng.com" target="_blank">诗与酒:主题作者的博客，没事可以去转转~</a></p>
	<p><a href="http://fontawesome.io/icons/" target="_blank">fontawesome icons:本主题使用的图标</a></p>
	<p><a href="https://tinypng.com" target="_blank">tinypng:优质的图片压缩网站</a></p>
    <p><a href="http://www.css88.com/tool/html-escape/" target="_blank">html转义工具:解决部分html代码无法显示的问题</a></p>
<?php }
// 通过add_action来自动调用memory_add_menus函数
add_action('admin_menu', 'memory_add_menus');

// 颜色选择器
add_action( 'admin_enqueue_scripts', 'wptuts_add_color_picker' );
function wptuts_add_color_picker( $hook ) {
	wp_enqueue_style( 'wp-color-picker' ); 
	wp_enqueue_script( 'wp-color-picker');
}
