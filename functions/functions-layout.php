<?php

// 本主题使用 wp_nav_menu() 函数自定义菜单
register_nav_menus(array(
	'top-menu' => 'PC端顶部菜单',
	'left-menu' => '手机端下拉菜单',
));

// 侧边栏
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => '侧边栏',
		'id' => 'sidebar-1',
		'before_widget' => '<li class="memory-item">',
		'after_widget'  => '</li>',
		'before_title'  => '<header class="memory-item-header"><h3 class="memory-item-title">',
		'after_title'   => '</h3></header>',
	));
}