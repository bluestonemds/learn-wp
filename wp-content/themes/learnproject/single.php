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
<article class="container">
    <div class="wrapper">
        <?php
        if (have_posts()) :
            ?>
            <div class="pure-g">
                <div class="content">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="pure-u-1">
                            <a href="<?php echo get_permalink() ?>"><?php the_title('<h2 class="entry-title">', '</h2>'); ?></a>
                            <div class="publish-date">
                                发布日期:<?php echo the_time('Y年m月d日 h:m:s'); ?></div>
                            <div class="content">
                                <?php the_content();
                                wp_link_pages();
                                ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <div class="pure-u-1">
                        <?php comments_template(); ?>
                    </div>
                </div>
            </div>
            <?php
        endif;
        ?>
    </div>
</article>

<?php get_footer(); ?>
