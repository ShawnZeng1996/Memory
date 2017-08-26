<!--
┌─┐┬ ┬┌─┐┬ ┬┌┐┌┌─┐┌─┐┌┐┌┌─┐ ┌─┐┌─┐┌┬┐
└─┐├─┤├─┤││││││┌─┘├┤ ││││ ┬ │  │ ││││
└─┘┴ ┴┴ ┴└┴┘┘└┘└─┘└─┘┘└┘└─┘o└─┘└─┘┴ ┴
Author: Shawn
Author URI: https://shawnzeng.com
-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
	  <?php
	$description = '';
	$keywords = '';
	if (is_home()) {
   		// 将以下引号中的内容改成你的主页description
   		$description = get_option( 'memory_description' );
   		// 将以下引号中的内容改成你的主页keywords
   		$keywords = get_option( 'memory_keywords' );
	}
	elseif (is_single()) {
   		$description1 = get_post_meta($post->ID, "description", true);
   		$description2 = str_replace("\n","",mb_strimwidth(strip_tags($post->post_content), 0, 200, "…", 'utf-8'));
		// 填写自定义字段description时显示自定义字段的内容，否则使用文章内容前200字作为描述
		if($description1 == '') {
			$description = $description2;
		}else{
			$description = $description1 ? $description1 : $description2;
		}
		// 填写自定义字段keywords时显示自定义字段的内容，否则使用文章tags作为关键词
   		$keywords = get_post_meta($post->ID, "keywords", true);
   		if($keywords == '') {
      		$tags = wp_get_post_tags($post->ID);    
      		foreach ($tags as $tag ) {        
         		$keywords = $keywords . $tag->name . ", ";    
      		}
      		$keywords = rtrim($keywords, ', ');
   		}
	}
	elseif (is_category()) {
   		// 分类的description可以到后台 - 文章 -分类目录，修改分类的描述
   		$description = category_description();
   		$keywords = single_cat_title('', false);
	}
	elseif (is_tag()){
   		// 标签的description可以到后台 - 文章 - 标签，修改标签的描述
   		$description = tag_description();
   		$keywords = single_tag_title('', false);
	}
	elseif (is_page()) {
		$description1_page = get_post_meta($post->ID, "description", true);
   		$description2_page = str_replace("\n","",mb_strimwidth(strip_tags($post->post_content), 0, 200, "…", 'utf-8'));
		// 填写自定义字段description_page时显示自定义字段的内容，否则使用文章内容前200字作为描述
		if($description1_page == '') {
			$description = $description2_page;
		}else{
			$description = $description1_page ? $description1_page : $description2_page;
		}
		// 自定义字段名称为 keywords
		$keywords = get_post_meta($post->ID, "keywords_value", true);
		if($keywords == '') {
      		$keywords = get_option( 'memory_keywords' );
   		}
		
	}
	// 去除不必要的空格和HTML标签
	$description = trim(strip_tags($description));
	$keywords = trim(strip_tags($keywords));
	?>

	<meta name="description" content="<?php echo $description; ?>" />
	<meta name="keywords" content="<?php echo $keywords; ?>" />
    <!--[if lt IE 9]><script src="//cdn.bootcss.com/html5shiv/r29/html5.js"></script><![endif]-->
	<title><?php if ( is_home() ) {
		bloginfo('name'); echo " - "; bloginfo('description');
	} elseif ( is_category() ) {
		single_cat_title(); echo " - "; bloginfo('name');
	} elseif (is_single() || is_page() ) {
		single_post_title();
	} elseif (is_search() ) {
		echo "搜索结果"; echo " - "; bloginfo('name');
	} elseif (is_404() ) {
		echo '页面未找到!';
	} else {
		wp_title('',true);
	} ?></title>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-2.1.3.min.js"></script>
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<link id="favicon" href="<?php bloginfo('template_url'); ?>/img/icon.ico" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<link rel="stylesheet" href="//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="//cdn.bootcss.com/balloon-css/0.4.0/balloon.min.css" />
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/share.min.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/OwO.min.css">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0 - 所有文章" href="<?php echo get_bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0 - 所有评论" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<?php wp_head(); ?>
</head>

<body>
    <canvas id="evanyou"></canvas>
    <div class="cover"></div>
    <header id="header">
        <div id="menu">
            <a id="menu-bar">
                <i class="fa fa-bars fa-2x"></i>
            </a>
			<a id="menu-search">
                <i class="fa fa-search fa-2x"></i>
            </a>
			<?php get_search_form(); ?>
            <div id="menu-logo">
                <h1 id="logo">
                    <a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>
                </h1>
            </div>

            <?php
    			wp_nav_menu( array( 'menu' => '', 'container' => 'nav', 'container_id' => 'menu-main', 'container_class' => 'menu-main', 'echo' => true, 'fallback_cb' => 'wp_page_menu', 'items_wrap' => '<ul>%3$s</ul>', 'depth' => 2 ) );
			?>

        </div>
    </header>
	<header id="mobile-menu">
        <div class="mobile-menu-img">
            <img src="<?php echo get_option( 'memory_useravatar' ); ?>">
            <h1 class="mobile-menu-title"><?php echo get_option( 'memory_username' ); ?></h1>
            <h2 class="mobile-menu-description"><?php echo get_option( 'memory_mobile_qm' ); ?></h2>
        </div>
        <div class="mobile-menu-main">
            <div class="mobile-menu-container">
                <?php 
    				wp_nav_menu( array( 'menu' => '', 'container' => false, 'menu_class' => '', 'menu_id' => 'menu-main', 'echo' => true, 'fallback_cb' => 'wp_page_menu', 'items_wrap' => '<ul>%3$s</ul>', 'depth' => 1 ) );
				?>
            </div>
            <div class="mobile-menu-social">
                <ul>
					<li><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo get_option( 'memory_QQ' ); ?>&site=qq&menu=yes" target="_blank"><i class="fa fa-qq fa-fw"></i></a></li>
                    <li><a href="<?php echo get_option( 'memory_weibo' ); ?>"><i class="fa fa-weibo fa-fw"></i></a></li>
                    <li><a href="<?php echo get_option( 'memory_zhihu' ); ?>"><i class="fa" style="font-weight: 500;">知</i></a></li>
                </ul>
            </div>
        </div>
        <div class="mobile-menu-plur"></div>
		<div class="mobile-shade"></div>
    </header>
	<?php flush(); ?>
    