<!--
┌─┐┬ ┬┌─┐┬ ┬┌┐┌┌─┐┌─┐┌┐┌┌─┐ ┌─┐┌─┐┌┬┐
└─┐├─┤├─┤││││││┌─┘├┤ ││││ ┬ │  │ ││││
└─┘┴ ┴┴ ┴└┴┘┘└┘└─┘└─┘┘└┘└─┘o└─┘└─┘┴ ┴
Author: Shawn
Author URI: https://shawnzeng.com
-->
<html dir="ltr" lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<?php if( is_single() || is_page() ) {
		if( function_exists('get_query_var') ) {
    		$cpage = intval(get_query_var('cpage'));
    		$commentPage = intval(get_query_var('comment-page'));
		}
		if( !empty($cpage) || !empty($commentPage) ) {
			echo '<meta name="robots" content="noindex, nofollow" />';
			echo "\n";
		}
	}
	$description = '';
	$keywords = '';
	if (is_home()) {
		$description = cs_get_option( 'memory_description' );
		$keywords = cs_get_option( 'memory_keywords' );
	} elseif (is_single()) {
		$description1 = memory_meta($post->ID,'_memory_post_options','post_description');
		$description2 = str_replace("\n","",mb_strimwidth(strip_tags($post->post_content), 0, 200, "…", 'utf-8'));
		// 填写自定义字段description时显示自定义字段的内容，否则使用文章内容前200字作为描述
		if($description1 == '' || $description1 == null ) {
			$description = $description2;
		}else{
			$description = $description1 ? $description1 : $description2;
		}
		// 填写自定义字段keywords时显示自定义字段的内容，否则使用文章tags作为关键词
		$keywords = memory_meta($post->ID,'_memory_post_options','post_keywords');
		if($keywords == '') {
			$tags = wp_get_post_tags($post->ID);
			foreach ($tags as $tag) {
				$keywords = $keywords . $tag->name . ", ";
			}
			$keywords = rtrim($keywords, ', ');
		}
	} elseif (is_category()) {
		// 分类的description可以到后台 - 文章 -分类目录，修改分类的描述
		$description = category_description();
		$keywords = single_cat_title('', false);
	} elseif (is_tag()){
		// 标签的description可以到后台 - 文章 - 标签，修改标签的描述
		$description = tag_description();
		$keywords = single_tag_title('', false);
	} elseif (is_page()) {
		$description1_page = memory_meta($post->ID,'_memory_post_options','post_description');
		$description2_page = str_replace("\n","",mb_strimwidth(strip_tags($post->post_content), 0, 200, "…", 'utf-8'));
		// 填写自定义字段description_page时显示自定义字段的内容，否则使用文章内容前200字作为描述
		if($description1_page == '') {
			$description = $description2_page;
		}else{
			$description = $description1_page ? $description1_page : $description2_page;
		}
		// 自定义字段名称为 keywords
		$keywords = memory_meta($post->ID,'_memory_post_options','post_keywords');
		if($keywords == '') {
			$keywords = cs_get_option( 'memory_keywords' );
		}		
	}
	// 去除不必要的空格和HTML标签
	$description = trim(strip_tags($description));
	$keywords = trim(strip_tags($keywords));
	?>
	<meta name="description" content="<?php echo $description; ?>" />
	<meta name="keywords" content="<?php echo $keywords; ?>" />
	<!--[if lt IE 9]><script src="//cdn.bootcss.com/html5shiv/r29/html5.js"></script><![endif]-->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0 - 所有文章" href="<?php echo get_bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0 - 所有评论" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>?ver=<?php echo wp_get_theme()->get('Version'); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/support.css?ver=<?php echo wp_get_theme()->get('Version'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/iconfont.css?ver=<?php echo wp_get_theme()->get('Version'); ?>">
	<!--link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_410989_wuhl0k5ojvf.css" /-->
	<?php wp_head(); ?> 
	<title><?php if ( is_home() ) { bloginfo('name'); echo " - "; bloginfo('description');
        } elseif ( is_category() ) { single_cat_title(); echo " - "; bloginfo('name');
        } elseif (is_single() || is_page() ) { single_post_title();
        } elseif (is_search() ) { echo "搜索结果"; echo " - "; bloginfo('name');
        } elseif (is_404() ) { echo '页面未找到!';
        } else { wp_title('',true); } ?></title>
	<style>
		.memory-certify { color: <?php echo cs_get_option( 'memory_certify_color' ); ?>!important; }
		#foot, #foot a { color: <?php echo cs_get_option( 'memory_footer_color' ); ?>; }
		.memory-item, #header { opacity: <?php echo cs_get_option( 'memory_opacity' ); ?>; }
		<?php 
		$memorybg=cs_get_option( 'memory_background' );
		if( $memorybg!=null ) {
			if( isset($memorybg["image"]) && $memorybg["image"]!='' ) { ?>
		body {
			background-image: url(<?php echo $memorybg["image"]; ?>);
			background-position: <?php echo $memorybg["position"]; ?>;
			background-repeat: <?php echo $memorybg["repeat"]; ?>;
			background-attachment: <?php if($memorybg["attachment"]=='') echo 'scroll'; else echo $memorybg["attachment"]; ?>;
			background-size: <?php echo $memorybg["size"]; ?>;
		}
	<?php } else { ?>
			body { background: <?php echo $memorybg["color"]; ?>; }	
	<?php }
		} 	
		$cardbg=cs_get_option( 'memory_card_background' );
		if( $cardbg!=null ) {
			if( isset($cardbg["image"]) && $cardbg["image"]!='' ) { ?>
		#sidebar .card-bg {
			background-image: url(<?php echo $cardbg["image"]; ?>);
			background-position: <?php echo $cardbg["position"]; ?>;
			background-repeat: <?php echo $cardbg["repeat"]; ?>;
			background-attachment: <?php if($cardbg["attachment"]=='') echo 'scroll'; else echo $cardbg["attachment"]; ?>;
			background-size: <?php echo $cardbg["size"]; ?>;
		}
	<?php } } 
		if(cs_get_option( 'memory_user_css' )!=null) echo cs_get_option( 'memory_user_css' );
		?>
	</style>
</head>
<?php flush(); ?>
<body>
	<header id="header">
		<div id="pc-menu">
            <a id="menu-bar" class="memory memory-menu"></a>
            <a id="menu-title" href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a>
			<?php wp_nav_menu( array( 'menu' => '', 'container' => 'nav', 'container_id' => 'menu-main', 'container_class' => 'menu-main', 'echo' => true, 'items_wrap' => '<ul>%3$s</ul>', 'depth' => 2, 'theme_location' => 'top-menu' ) ); ?>
            <a id="menu-login" class="menu-login <?php if ( !is_user_logged_in() ) { echo 'not-login"' . 'href="' . get_option('home') . '/wp-admin"' ; } else { echo 'have-login"'; } ?> >
            <?php if( is_user_logged_in() ){
				global $current_user;
				wp_get_current_user();
				echo get_avatar( $current_user->user_email, 48);
            } elseif ( isset($_COOKIE['comment_author_email_'.COOKIEHASH]) ) {
				$comment_author_email = $_COOKIE['comment_author_email_'.COOKIEHASH];
				echo get_avatar($comment_author_email, 48);
            } else {
            	echo '<i class="memory memory-login"></i>';
            } ?>
            </a>
			<?php if ( is_user_logged_in() ) { ?>
			<div id="personal-menu">
				<ul>
					<li><a href="<?php echo get_option('home'); ?>/wp-admin">后台&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="memory memory-dashboard"></i></a></li>
					<li><a href="<?php echo get_option('home'); ?>/wp-login.php?action=logout">登出&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="memory memory-logout"></i> </a></li>
				</ul>
			</div>
			<?php } ?>
            <form role="search" method="get" id="searchform" action="<?php echo home_url( "/" ); ?>" autocomplete="off">
				<i class="memory memory-search"></i>
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="搜索..."/>
			</form>
        </div>
        <div id="mobile-menu">
            <form role="search" method="get" id="mobile-searchform" action="<?php echo home_url( "/" ); ?>" autocomplete="off">
				<i class="memory memory-search"></i>
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="m_s" placeholder="搜索..."/>
			</form>
            <?php wp_nav_menu( array( 'menu' => '', 'container' => 'nav', 'container_id' => 'mobile-menu-main', 'container_class' => 'menu-main', 'echo' => true, 'items_wrap' => '<ul>%3$s</ul>', 'depth' => 1, 'theme_location' => 'left-menu' ) ); ?>
        </div>
	</header>