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
    <?php
    $cat_id = '16';
    $posts_to_show = '10'; // number of posts from the category you want to show on homepage
    //query_posts("cat=$cat_ID&showposts=$posts_to_show");
    $category_posts = new WP_Query("cat=$cat_id&showposts=$posts_to_show");
    if (have_posts()) :
        ?>
        <div class="pure-g">
            <div class="pure-u-1">
                <h3><?php echo $post->post_title; ?></h3>
                <?php echo $post->post_content; ?>
            </div>
        </div>
        <?php
    endif;
    ?>
</div>

<?php get_footer(); ?>
