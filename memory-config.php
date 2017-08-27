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
	add_submenu_page(__FILE__,'Website Recommend','常用网站推荐',8,'memory_submenu1','memory_submenu1');
	add_submenu_page(__FILE__,'Memory Donate','捐助作者',8,'memory_submenu_donate','memory_submenu_donate');
}

function memory_menu() { ?>
	<div class="wrap">
	<h2>Memory</h2>
	<p>欢迎使用Wordpress主题Memory！</p>
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
        </table>
    	<p><input type="submit" class="button-primary" name="admin_options" value="保存"/></p>
    </form>
	</div>
<?php
}

function memory_up_or_del($op) {
	update_option($op, $_POST[$op]);
	//若值为空，则删除这行数据
	if( empty($_POST[$op]) ) delete_option($op);
}

function memory_submenu1() {
    echo '
	<h2>常用网站推荐</h2>
	<p><a href="https://shawnzeng.com" target="_blank">诗与酒:主题作者的博客，没事可以去转转~</a></p>
	<p><a href="https://tinypng.com" target="_blank">tinypng:优质的图片压缩网站</a></p>
    <p><a href="http://fontawesome.io/icons/" target="_blank">fontawesome icons:本主题使用的图标</a></p>
    <p><a href="http://www.css88.com/tool/html-escape/" target="_blank">html转义工具:解决部分html代码无法显示的问题</a></p>
	';  
}
function memory_submenu_donate() {
    echo '
	<h2>捐助作者</h2>
    <p>支持下可怜的吃土少年！</p>
    <img src="https://pictures.shawnzeng.com/zhifubaodonate.png"/>
    <img src="https://pictures.shawnzeng.com/weixindonate.png"/>
	';  
}
// 通过add_action来自动调用memory_add_menus函数
add_action('admin_menu', 'memory_add_menus');