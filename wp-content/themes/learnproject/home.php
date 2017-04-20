<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
get_header(); ?>
<div class="container">
    <div class="wrapper">
        <div class="pure-g">
            <div class="content pure-u-1 pure-u-md-4-5">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        ?>
                        <div class="content-area">
                            <a href="<?php echo get_permalink($post->ID) ?>"><h2><?php the_title(); ?></h2></a>
                            <div class="time"><i
                                        class="fa fa-calendar"></i>发布日期:<?php echo date('Y年m月d日', strtotime($post->post_date)); ?>
                            </div>
                            <?php the_content("继续阅读..."); ?>
                        </div>
                        <?php
                    endwhile;
                endif;
                ?>
            </div>
            <div class="sidebar pure-u-1 pure-u-md-1-5">
                <?php dynamic_sidebar(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
