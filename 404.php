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
get_header(); 
?>
	<div id="main">
        <div id="main-part">
            <ul class="posts-list">
                <li>
                    <article class="art">
                        <div class="art-main">
                            <h3 class="art-title">
                                <a href="">404 Not Found</a>
                            </h3>
                            <div class="art-intro">
                                您访问的页面不存在
                            </div>
                        </div>
                    </article>
                </li>
            </ul>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>
        