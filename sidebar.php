<div id="sidebar">
    <ul>
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
        <li class="art">
            <header class="art-widget-header">
                <h3>我是萌萌哒的侧边栏！</h3>
            </header>
            <div class="textwidget">
            	<p>我是你的第一个侧边栏！快去小工具里面添加组件吧！</p>
            </div>
        </li>
	<?php endif; ?>
    </ul>
</div>