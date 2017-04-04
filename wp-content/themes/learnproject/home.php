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
    <div class="pure-g">
        <div class="pure-u-4-5">
            <?php
            $cat_id = '16';
            $posts_to_show = '10'; // number of posts from the category you want to show on homepage
            //query_posts("cat=$cat_ID&showposts=$posts_to_show");
            $category_posts = new WP_Query("cat=$cat_id&showposts=$posts_to_show");
            var_dump($category_posts);
            if (have_posts()) :
                while (have_posts()) : the_post();
                    ?>
                    <div class="pure-g">
                        <div class="pure-u-1">
                            <?php if (has_excerpt()) : ?>
                                <div class="<?php echo $class; ?>">
                                    <?php the_excerpt(); ?>
                                </div><!-- .<?php echo $class; ?> -->
                            <?php endif; ?>
                            <h3><?php echo $post->post_title; ?></h3>
                            <?php echo $post->post_content; ?>
                        </div>
                    </div>
                    <?php
                endwhile;
            endif;
            ?>
        </div>
        <div class="pure-u-1-5">
            <div class="sidebar">
                <?php dynamic_sidebar(); ?>
            </div>
        </div>
    </div>

    <?php get_footer(); ?>
