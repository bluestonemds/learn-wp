<?php


function learningWordPress_resources()
{
    wp_enqueue_style("pure", get_template_directory_uri() . '/assets/css/pure-min.css');
    wp_enqueue_style("grids_responsive", get_template_directory_uri() . '/assets/css/grids-responsive-min.css');
    wp_enqueue_style("awefont", get_template_directory_uri() . '/assets/css/font-awesome.min.css');
    wp_enqueue_style("style", get_stylesheet_uri());
}

add_action("wp_enqueue_scripts", 'learningWordPress_resources');

function add_menuclass($ulclass)
{
    return preg_replace('/<a /', '<a class="pure-menu-link"', $ulclass);
}

add_filter('wp_nav_menu', 'add_menuclass');

add_theme_support('post-thumbnails');

$i = 1;
register_sidebar($args = array(
    'name' => sprintf(__('Sidebar %d'), $i),
    'id' => "sidebar-$i",
    'description' => '',
    'class' => '',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => "</li>\n",
    'before_title' => '<h2 class="widgettitle">',
    'after_title' => "</h2>\n",
));

