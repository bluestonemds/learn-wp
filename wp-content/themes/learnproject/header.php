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

    <div class="pure-menu pure-menu-horizontal lanshi-nav">
        <div class="container">
            <a href="<?php bloginfo('url') ?>" class="pure-menu-heading pure-menu-link"><?php bloginfo('name') ?></a>
            <?php wp_nav_menu(array(
                    "container" => '',
                    'menu_class' => 'pure-menu-list',
                )
            ); ?>
        </div>
    </div>
</header>


