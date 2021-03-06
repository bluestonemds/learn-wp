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
<?php
if (have_posts()) :
    ?>
    <div class="container">
        <div class="wrapper">
            <div class="pure-g">
                <div class="content">
                    <?php while (have_posts()) : the_post();
                        ?>
                        <div class="pure-u-1 content-area">
                            <a href="<?php echo get_permalink($post->ID) ?>"><h2><?php the_title(); ?></h2></a>
                            <?php the_content("继续阅读..."); ?>
                            <div class="time"><i
                                        class="fa fa-calendar"></i>发布日期:<?php echo date('Y年m月d日', strtotime($post->post_date)); ?>
                            </div>
                        </div>
                        <?php
                    endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;
?>

<?php get_footer(); ?>
