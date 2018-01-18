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
 * Template Name: 404
 * Template Post Type: page
 */
get_header(); ?>
   	<div id="main">
        <div id="main-part">
			<ul class="posts-list">
                <li>
                    <article class="art">
                        <div class="art-main">
                    		<div class="art-content">
                            	<h3 class="art-title">
                                	<i class="fa fa-times"></i> 404 Not Found
                            	</h3>
                            	<p>您似乎输入了错误的地址，所访问的页面不存在~要不去其他页面看看？</p>
                            </div>
                        </div>
                    </article>
                </li>
            </ul>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>
        