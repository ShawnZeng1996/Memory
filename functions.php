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
// 任何添加于主题目录functions文件夹内的php文件都被调用到这里
define('functions', TEMPLATEPATH.'/functions');
IncludeAll( functions );
function IncludeAll($dir){
    $dir = realpath($dir);
    if($dir){
        $files = scandir($dir);
        sort($files);
        foreach($files as $file){
            if($file == '.' || $file == '..'){
                continue;
            }elseif(preg_match('/.php$/i', $file)){
                include_once $dir.'/'.$file;
            }
        }
    }
}

// 添加自定义的Description和Keywords字段面板
$new_meta_boxes = array(
	"description" => array(
		"name" => "_description",
		"std" => "",
		"title" => "网页描述:"
	),
	"keywords" => array(
		"name" => "_keywords",
		"std" => "",
		"title" => "关键字:"
	)
);
function new_meta_boxes() {
	global $post, $new_meta_boxes;
	foreach($new_meta_boxes as $meta_box) {
    	$meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);
		if($meta_box_value == "")
    		$meta_box_value = $meta_box['std'];
		// 自定义字段标题
    	echo'<h3>'.$meta_box['title'].'</h3>';
		// 自定义字段输入框
    	echo '<textarea cols="60" rows="3" style="width:100%" name="'.$meta_box['name'].'_value">'.$meta_box_value.'</textarea><br />';
	}
	echo '<input type="hidden" name="memory_metaboxes_nonce" id="memory_metaboxes_nonce" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
}
function create_meta_box() {
	if ( function_exists('add_meta_box') ) {
		add_meta_box( 'new-meta-boxes', '自定义文章描述和关键词', 'new_meta_boxes', 'post', 'normal', 'high' );
	}
	add_meta_box( 'new-meta-boxes', '自定义页面描述和关键词', 'new_meta_boxes', 'page', 'normal', 'high' );
}
function save_postdata( $post_id ) {
	global $new_meta_boxes;
	if ( !wp_verify_nonce( $_POST['memory_metaboxes_nonce'], plugin_basename(__FILE__) ))
    	return;
	if ( !current_user_can( 'edit_posts', $post_id ))
    	return;
	foreach($new_meta_boxes as $meta_box) {
    	$data = $_POST[$meta_box['name'].'_value'];
		if($data == "")
			delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
    	else
		update_post_meta($post_id, $meta_box['name'].'_value', $data);
	}
}
add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata');
