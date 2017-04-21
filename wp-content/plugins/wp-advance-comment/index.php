<?php /*

Plugin Name: WP Advanced Comment
Version: 0.14
Author: Ravi Shakya
Description: This plugin allows you to create comment forms and manage your comments more easily and create custom fields for the comments.
Text Domain : wpad
Domain Path: lang

*/

define( 'WPAD_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );

/*
** Load Plugin Textdomain
*/

add_action( 'plugins_loaded', 'wpad_theme_setup');
function wpad_theme_setup(){
	$path = dirname(plugin_basename( __FILE__ )) . '/lang/';
    load_plugin_textdomain( 'wpad', false , $path );
}

/*
** Add Pro version Banner
*/

function pro_version_banner(){ ?>

	<div class="ravis_plugins_wrapper">
		<a href="http://codecanyon.net/item/wp-advanced-comment-pro-/14557575?ref=ravishakya" target="blank" class="wpad_pro_banner">
			<div class="pro_banner">
				<img src="<?php echo WPAD_PLUGIN_DIR . '/images/pro_version.jpg'; ?>">
			</div>
		</a>
		<a href="http://codecanyon.net/item/woocommerce-advanced-product-reviews/16078109?ref=ravishakya" target="blank" class="wpad_pro_banner">
			<div class="pro_banner_woo_advanced_reviews pro_banner">
				<img src="<?php echo WPAD_PLUGIN_DIR . '/images/pro_version_advanced_woo.jpg'; ?>">
			</div>
		</a>
		<a href="http://codecanyon.net/item/add-to-menus/15533312?ref=ravishakya" target="blank" class="wpad_pro_banner">
			<div class="pro_banner_add_to_menu pro_banner">
				<img src="<?php echo WPAD_PLUGIN_DIR . '/images/add_to_menus.jpg'; ?>">
			</div>
		</a>
	</div>
	<?php
}

/*
Include Necessary Classes
*/

include 'class/comment-list.php';
include 'class/enqueue.php';
include 'class/frontend-save-data.php';
include 'class/comment-form-listings.php';
include 'class/edit-comment-form.php';
include 'class/settings.php';
include 'class/comment-reply-form.php';
include 'class/saved-comment-edit.php';
include 'class/add-ons.php';
include 'shortcodes/comment-form.php';
include 'class/remove_default_comment_tag.php';
include 'class/flagged-comment.php';
include 'class/widget.php';
include 'class/admin-notice.php';