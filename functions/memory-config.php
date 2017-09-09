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
	add_submenu_page(__FILE__,'Memory Donate','捐助作者',8,'memory_submenu_donate','memory_submenu_donate');
}

function memory_menu() { ?>
	<style>
	.form-table th {padding: 10px 10px 10px 0;}
	.form-table td {padding: 10px 10px;}
	.regular-text {width: 20em;}
	#memory_setuptime_year,#memory_setuptime_month,#memory_setuptime_day {width: 5em;display: inline-block;}
	</style>
	<div class="wrap">
	<h2>Memory<span style="font-size:12px;padding-left:2em;">欢迎使用Wordpress主题Memory！<a href="https://jq.qq.com/?_wv=1027&k=44nyJOX">点此进群不孤单！</a></span></h2>
    <?php
    if ($_POST['update_options']=='true') {//若提交了表单，则保存变量
    	memory_up_or_del('memory_username');
    	memory_up_or_del('memory_useravatar');
    	memory_up_or_del('memory_description');
    	memory_up_or_del('memory_keywords');
    	memory_up_or_del('memory_mobile_qm');
		memory_up_or_del('memory_github');
    	memory_up_or_del('memory_QQ');
    	memory_up_or_del('memory_weibo');
    	memory_up_or_del('memory_zhihu');
    	memory_up_or_del('memory_beian');
      	memory_up_or_del('memory_copyright');
      	memory_up_or_del('memory_setuptime_year');
      	memory_up_or_del('memory_setuptime_month');
      	memory_up_or_del('memory_setuptime_day');	
		memory_up_or_del('memory_sql_dbn');
		memory_up_or_del('memory_sql_dbu');
		memory_up_or_del('memory_sql_dbp');
		update_option('memory_have_header_picture', $_POST['memory_have_header_picture']);
		memory_up_or_del('memory_header_picture');
		memory_up_or_del('memory_foot_color');
		update_option('memory_canvas_or_background', $_POST['memory_canvas_or_background']);
		memory_up_or_del('memory_background');
		echo '<div><p>保存成功!</p></div>';//保存完毕显示文字提示
	}
    ?>
	<form action="" method="post" id="memory_menu_form">
   		<input type="hidden" name="update_options" value="true" />
      	<table class="form-table">
          	<tr>
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
              	<th scope="row">
					<input name="memory_canvas_or_background" type="radio" value="0" <?php checked( '0', get_option( 'memory_canvas_or_background' ) ); ?> /><label for="memory_canvas">条带状背景</label>
					<input name="memory_canvas_or_background" type="radio" value="1" <?php checked( '1', get_option( 'memory_canvas_or_background' ) ); ?> /><label for="memory_background">背景图片</label>
				</th>
    			<td>
                	<input type="text" class="regular-text" name="memory_background" id="memory_background" value="<?php echo get_option('memory_background'); ?>" />
					<a id="memory_background_upload" class="button" href="#">选择/上传图片</a>
    			</td>
    		</tr>  
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
    		<tr>
              	<th scope="row"><label for="memory_mobile_qm">手机端签名:</label></th>
    			<td>
                	<input type="text" class="regular-text" name="memory_mobile_qm" id="memory_mobile_qm" value="<?php echo get_option('memory_mobile_qm'); ?>" />
    			</td>
    		</tr>
			<tr>
              	<th scope="row"><label for="memory_github">Github主页:</label></th>
    			<td>
                	<input type="text" class="regular-text" name="memory_github" id="memory_github" value="<?php echo get_option('memory_github'); ?>" />
    			</td>
    		</tr>
    		<tr>
              	<th scope="row"><label for="memory_QQ">QQ:</label></th>
    			<td>
                	<input type="text" class="regular-text" name="memory_QQ" id="memory_QQ" value="<?php echo get_option('memory_QQ'); ?>" />
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
              	<th scope="row"><label for="memory_beian">备案号:</label></th>
    			<td>
                	<input type="text" class="regular-text" name="memory_beian" id="memory_beian" value="<?php echo get_option('memory_beian'); ?>" />
    			</td>
    		</tr>   
            <tr>
              	<th scope="row"><label for="memory_copyright">copyright:(如2017、2016-2017等)</label></th>
    			<td>
                	<input type="text" class="regular-text" name="memory_copyright" id="memory_copyright" value="<?php echo get_option('memory_copyright'); ?>" />
    			</td>
    		</tr>  
            <tr>
              	<th scope="row"><label for="memory_setuptime_year">博客建立日期:</label></th>
    			<td>
                	<input type="text" name="memory_setuptime_year" id="memory_setuptime_year" value="<?php echo get_option('memory_setuptime_year'); ?>" />年
					<input type="text" name="memory_setuptime_month" id="memory_setuptime_month" value="<?php echo get_option('memory_setuptime_month'); ?>" />月
					<input type="text" name="memory_setuptime_day" id="memory_setuptime_day" value="<?php echo get_option('memory_setuptime_day'); ?>" />日
    			</td>
    		</tr> 
            <tr>
              	<th scope="row"><label for="memory_sql_dbn">数据库名:</label></th>
    			<td>
                	<input type="text" class="regular-text" name="memory_sql_dbn" id="memory_sql_dbn" value="<?php echo get_option('memory_sql_dbn'); ?>" />
    			</td>
    		</tr>
            <tr>
              	<th scope="row"><label for="memory_sql_dbu">数据库用户名:</label></th>
    			<td>
                	<input type="text" class="regular-text" name="memory_sql_dbu" id="memory_sql_dbu" value="<?php echo get_option('memory_sql_dbu'); ?>" />
    			</td>
    		</tr>  
            <tr>
              	<th scope="row"><label for="memory_sql_dbp">数据库密码:</label></th>
    			<td>
                	<input type="text" class="regular-text" name="memory_sql_dbp" id="memory_sql_dbp" value="<?php echo get_option('memory_sql_dbp'); ?>" />
    			</td>
    		</tr>
            <tr>
              	<th scope="row">
					<input type="checkbox" name="memory_have_header_picture" id="memory_have_header_picture" value="1" <?php checked( '1', get_option( 'memory_have_header_picture' ) ); ?>  />
					<label for="memory_header_picture">头部壁纸:</label>
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
        </table>
    	<p><input type="submit" class="button-primary" name="admin_options" value="保存"/></p>
    </form>
	</div>
<?php
wp_enqueue_media(); //在设置页面需要加载媒体中心
?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-2.1.3.min.js"></script>
<script>   
jQuery(document).ready(function($){
    $('#memory_useravatar_upload').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#memory_useravatar').val(image_url);
        });
    });
    $('#memory_header_picture_upload').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#memory_header_picture').val(image_url);
        });
    });
	$('#memory_background_upload').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#memory_background').val(image_url);
        });
    });
});
</script> 
<?php
}

function memory_up_or_del($op) {
	update_option($op, $_POST[$op]);
	//若值为空，则删除这行数据
	if( empty($_POST[$op]) ) delete_option($op);
}

function memory_submenu_recommend() {
    echo '
	<style>
	p>a{
		text-decoration: none;
		color: #000;
		padding: 5px;
		margin: 5px 0;
	}
	</style>
	<h2>常用网站推荐</h2>
	<p><a href="https://shawnzeng.com" target="_blank">诗与酒:主题作者的博客，没事可以去转转~</a></p>
	<p><a href="https://tinypng.com" target="_blank">tinypng:优质的图片压缩网站</a></p>
    <p><a href="http://fontawesome.io/icons/" target="_blank">fontawesome icons:本主题使用的图标</a></p>
    <p><a href="http://www.css88.com/tool/html-escape/" target="_blank">html转义工具:解决部分html代码无法显示的问题</a></p>
    <p><a href="https://www.flaticon.com/packs/font-awesome/8" target="_blank">flaticon-font-awesome:font-awesome图标下载</a></p>
	';  
}
function memory_submenu_donate() { ?>
	<h2>捐助作者</h2>
    <p>支持下可怜的吃土少年！</p>
    <img src="<?php bloginfo('template_url'); ?>/img/zhifubaodonate.png" width="100px" height="100px" />
    <img src="<?php bloginfo('template_url'); ?>/img/weixindonate.png" width="100px" height="100px" />
<?php
}
// 通过add_action来自动调用memory_add_menus函数
add_action('admin_menu', 'memory_add_menus');