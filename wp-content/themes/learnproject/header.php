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
    <div class="slider">
        <?php echo do_shortcode("[espro-slider id=48]"); ?>
    </div>
</header>
<div class="">
    <section class="column-three">
        <div class="container">
            <div class="pure-g">
                <div class="pure-u-sm-1 pure-u-md-1-3">
                    <img src="http://192.168.36.118/wp-content/uploads/2017/03/About.png"/>
                    <p>
                        蓝石致力于企业品牌的建立，帮助企业树立良好的企业形象，打开市场，帮助中小企业发展。蓝石长期从事软件开发、网站建设行业,拥有多年开发设计经验，专为大中小企业提供网站建设服务。从网站策划到网站制作，我们为您提供完美的解决方案。建网站时充分考虑到未来的网络营销、形象产品展示和电子商务功能，为您赢得市场。
                    </p>
                </div>
                <div class="pure-u-sm-1 pure-u-md-1-3">
                    <img src="http://192.168.36.118/wp-content/uploads/2017/03/Develope.png"/>
                    <ul>
                        <li>设计基于WEB标准的网站</li>
                        <li>设计开发信息管理系统</li>
                        <li>网站SEO推广</li>
                        <li>数据库系统运行维护</li>
                        <li>网站结构优化、重构</li>
                        <li>微信开发、代运营</li>
                    </ul>
                </div>
                <div class="pure-u-sm-1 pure-u-md-1-3">
                    <img src="http://192.168.36.118/wp-content/uploads/2017/03/Contact.png"/>
                    <ul>
                        <li>地址：吉林省梨树县梨树镇</li>
                        <li>联系电话:13630999990</li>
                        <li>联系邮箱:457437608@qq.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


