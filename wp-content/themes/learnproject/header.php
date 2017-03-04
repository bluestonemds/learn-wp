<?php
/**
 * The template for displaying the header
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <title><?php bloginfo('name') ?></title>
</head>
<body <?php body_class(); ?>>
<header>
    <h1><?php bloginfo('name') ?></h1>
    <?php wp_nav_menu(array(
            'container_class' => 'pure-menu pure-menu-horizontal',
            'menu_class' => 'pure-menu-list',
        )
    ); ?>
    <div class="slider">
    <?php echo do_shortcode("[espro-slider id=48]"); ?>
        </div>
</header>
<div class="container">
    <div class="pure-g pure-u-1-1">
