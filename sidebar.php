<?php $us_name = cs_get_option( 'memory_bloger_user' );
	$user = get_user_by('login', $us_name);
?>
<div id="sidebar">
    <ul>
		<li class="memory-item">
			<div class="card-bg"></div>
			<div class="card-info">
				<?php if($user) { echo get_avatar( $user->ID, '100' ); } ?>
				<span class="card-info-block card-info-name"><?php echo $user->display_name ?></span>
			</div>
			<div class="catch-me">
					<a data-content="Rss" data-balloon-pos="up" target="_blank" href="<?php echo get_bloginfo('rss2_url'); ?>"><i class="memory memory-rss"></i></a>
					<?php if(cs_get_option( 'memory_qq' )) { ?>
					<a data-content="QQ" data-balloon-pos="up" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo cs_get_option( 'memory_qq' ); ?>&site=qq&menu=yes" rel="nofollow"><i class="memory memory-qq"></i></a>
					<?php } ?>
					<?php if(cs_get_option( 'memory_qqqun' )) { ?>
					<a data-content="QQ群" data-balloon-pos="up" target="_blank" href="<?php echo cs_get_option( 'memory_qqqun' ); ?>" rel="nofollow"><i class="memory memory-qqqun"></i></a>
					<?php } ?>
					<?php if(cs_get_option( 'memory_weibo' )) { ?>
					<a data-content="微博" data-balloon-pos="up" target="_blank" href="<?php echo cs_get_option( 'memory_weibo' ); ?>" rel="nofollow"><i class="memory memory-weibo"></i></a>
					<?php } ?>
					<?php if(cs_get_option( 'memory_github' )) { ?>
					<a data-content="GitHub" data-balloon-pos="up" target="_blank" href="<?php echo cs_get_option( 'memory_github' ); ?>" rel="nofollow"><i class="memory memory-github"></i></a>
					<?php } ?>
					<?php if(cs_get_option( 'memory_email' )) { ?>
					<a data-content="Email" data-balloon-pos="up" target="_blank" href="mailto:<?php echo cs_get_option( 'memory_email' ); ?>" rel="nofollow"><i class="memory memory-email"></i></a>
					<?php } ?>
					<?php if(cs_get_option( 'memory_zhihu' )) { ?>
					<a data-content="知乎" data-balloon-pos="up" target="_blank" href="<?php echo cs_get_option( 'memory_zhihu' ); ?>" rel="nofollow"><i class="memory memory-zhihu"></i></a>
					<?php } ?>
					<?php if(cs_get_option( 'memory_bilibili' )) { ?>
					<a data-content="哔哩哔哩" data-balloon-pos="up" target="_blank" href="<?php echo cs_get_option( 'memory_bilibili' ); ?>" rel="nofollow"><i class="memory memory-bilibili"></i></a>
					<?php } ?>
			</div>
		</li>
    	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-1') ) : ?>
		<li class="memory-item">
            <header class="memory-item-header">
                <h3 class="sidebar-default-icon memory-item-title">我是萌萌哒的侧边栏！</h3>
            </header>
            <div class="textwidget">
            	<p>我是你的第一个侧边栏！快去小工具里面添加组件吧！</p>
            </div>
        </li>
		<?php endif; ?>
    </ul>
</div>