<?php 
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
// codestar后台框架
require_once dirname( __FILE__ ) .'/cs-framework/cs-framework.php';

/* 检查更新
require_once(TEMPLATEPATH . '/theme-update-checker.php'); 
$memory_update_checker = new ThemeUpdateChecker(
	'Memory', //主题名字
	'https://shawnzeng.com/wp-themes/memory-info.json'  //info.json 的访问地址
);*/