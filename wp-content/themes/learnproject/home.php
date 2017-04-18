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
    <div class="pure-g">
        <div class="content pure-u-md-4-5 pure-u-sm-1-1">
            <?php
//            $cat_id = '16';
//            $posts_to_show = '10'; // number of posts from the category you want to show on homepage
//            //query_posts("cat=$cat_ID&showposts=$posts_to_show");
//            $category_posts = new WP_Query("cat=$cat_id&showposts=$posts_to_show");
//            var_dump($category_posts);
            if (have_posts()) :
                while (have_posts()) : the_post();
                    ?>
                    <div class="pure-g">
                        <div class="pure-u-1 content-area">
                            <a href="<?php echo get_permalink($post->ID) ?>"><h2><?php the_title(); ?></h2></a>
                            <?php the_content("继续阅读..."); ?>
                            <div class="time"><i class="fa fa-calendar"></i>发布日期:<?php echo date('Y年m月d日',strtotime($post->post_date)); ?></div>
                        </div>
                    </div>
                    <?php
                endwhile;
            endif;
            ?>
        </div>
        <div class="pure-u-md-1-5 pure-u-sm-1-1">
            <div class="sidebar">
                <?php dynamic_sidebar(); ?>
            </div>
        </div>
        </div>

    <?php get_footer(); ?>
