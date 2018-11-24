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
?>
		<div id="foot">
			<a id="back-to-top"><i class="memory memory-top"></i></a>
			<p>版权所有 © <?php if( cs_get_option( 'memory_copyright' )!=null ) echo cs_get_option( 'memory_copyright' ); ?> <a href="<?php echo get_option( 'siteurl' ); ?>"><?php bloginfo('name'); ?></a> <?php if( cs_get_option( 'memory_record' )!=null ) { ?> | <a href="http://www.miitbeian.gov.cn"><?php echo cs_get_option( 'memory_record' ); ?></a> <?php } ?><br/>Theme <a class="theme" href="https://shawnzeng.com/wordpress-theme-memory.html" target="_blank">Memory</a> By <a href="https://shawnzeng.com" target="_blank">Shawn</a> With <i class="memory memory-heart throb"></i> | All Rights Reserved<br/><span class="my-face">(●'◡'●)ﾉ</span>本博客已萌萌哒运行了<span id="span_dt_dt"></span></p>
		</div>
		<div id="layout-shadow"></div>
<?php wp_enqueue_script( 'func', get_template_directory_uri() . '/js/func.js', false, wp_get_theme()->get('Version'), array('jquery') );
		wp_enqueue_script( 'support', get_template_directory_uri() . '/js/support.js', false, wp_get_theme()->get('Version'), array('jquery') );
		wp_enqueue_script( 'app', get_template_directory_uri() . '/js/app.js', false, wp_get_theme()->get('Version'), array('jquery','func','support') );
		if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
		wp_localize_script( 'app', 'memoryConfig', array(
			'siteUrl' => get_stylesheet_directory_uri(),
			'siteStartTime' => cs_get_option( 'memory_start_time' ),
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'commentEditAgain' => cs_get_option( 'memory_comment_edit' ),
		)); ?>
	<?php wp_footer(); if ( cs_get_option( 'memory_user_js' )!=null ) echo '<script>' . cs_get_option( 'memory_user_js' ) . '</script>';?>
</body>
</html>