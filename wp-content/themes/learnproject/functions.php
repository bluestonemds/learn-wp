<?php


function learningWordPress_resources(){
	wp_enqueue_style("style",get_stylesheet_uri());
    wp_enqueue_style("grids_responsive",get_template_directory_uri().'/assets/css/grids-responsive-min.css');
}

add_action("wp_enqueue_scripts",'learningWordPress_resources');