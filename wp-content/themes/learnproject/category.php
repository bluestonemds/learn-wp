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
<div class="product-list">
    <div class="container">
        <?php
        if (have_posts()) :
            ?>
            <div class="pure-g">
                <?php while (have_posts()) :
                    the_post();
                    ?>
                    <div class="pure-u-1 pure-u-md-1-3 product-thumbnail center">
                        <a href="<?php echo get_permalink($post->ID) ?>"><?php the_post_thumbnail(); ?></a>
                        <div><a href="<?php echo get_permalink($post->ID) ?>"><?php the_title(); ?></a></div>
                    </div>
                <?php endwhile; ?>
                <div class="pure-u-1 pagination">
                    <?php the_posts_pagination(array(
                        "prev_text" => "前一页",
                        "next_text" => "后一页",
                        "screen_reader_text" => " "
                    ));
                    ?>
                </div>
            </div>
            <?php
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
