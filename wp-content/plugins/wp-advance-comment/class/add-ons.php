<?php 

class wpad_comment_adons {

	function __construct(){
		add_action( 'admin_menu', array( $this, 'register_wp_addons' ) );
	}

	function register_wp_addons(){

		add_submenu_page( 'wp_advance_comment', 'WP Advanced Comment Addons', __( 'Add-ons' , 'wpad' ), 'delete_others_pages', 'wpad_addons', array($this, 'addons_page'));

	}

	function addons_page(){ ?>
			
		<div class="wrap">
			
			<h2>WP Advanced Comment Add-ons</h2>

			<div class="wpad_addons">
				
				<div class="wpad_alert">

					<p>The following Add-ons are available to increase the functionality of the WP Advance Comment plugin.
					<br>
					Each Add-on can be installed as a separate plugin (receives updates) or included in your theme (does not receive updates).</p>
				</div>

				<ul>
					<li class="wpad_box">
						<a href="#">
							<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/addon2.jpg'; ?>">
						</a>
						<div class="addon_inner">
							<h3>
								<a href="#">WP Advanced Comment Validation</a>
							</h3>
							<p>Advanced Validation for the custom fields from the built in validation or create your own validation method.</p>
						</div>
						<div class="addon_footer">

							<?php 
							if( class_exists('wpad_comment_validation') ){ ?>

								<a class="button" disabled>
								<span class="dashicons dashicons-yes"></span>Installed</a>

								<?php
							} else { ?>

								<a class="button" disabled>Coming Soon</a>

								<?php
							}?>

						</div>
					</li>
					<li class="wpad_box">
						<a href="#">
							<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/addon1.jpg'; ?>">
						</a>
						<div class="addon_inner">
							<h3>
								<a href="#">WooCommerce Advanced Product Reviews</a>
							</h3>
							<p>Integrate WP Advanced Comment Form with the WooCommerce.</p>
						</div>
						<div class="addon_footer">
							
							<?php 
							if( class_exists('wpad_woocommerce') ){ ?>

								<a class="button" disabled>
								<span class="dashicons dashicons-yes"></span>Installed</a>

								<?php
							} else { ?>

								<a class="button button-primary" href="http://codecanyon.net/item/woocommerce-advanced-product-reviews/16078109?ref=ravishakya" target="blank">Buy Now</a>

								<?php
							}?>
							
						</div>
					</li>
					<li class="wpad_box">
						<a href="#">
							<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/addon3.jpg'; ?>">
						</a>
						<div class="addon_inner">
							<h3>
								<a href="#">WP Advanced Comment for Ultimate Member</a>
							</h3>
							<p>Integrate WP Advanced Comment with the Ultimate Member Plugin.</p>
						</div>
						<div class="addon_footer">
							
							<?php 
							if( class_exists( 'wpad_ultimate_member' ) ){ ?>

								<a class="button" disabled>
								<span class="dashicons dashicons-yes"></span>Installed</a>

								<?php
							} else { ?>

								<a class="button button-primary" href="#" target="blank">Get it for free</a>

								<?php
							}?>
							
						</div>
					</li>
				</ul>

			</div>

		</div>

		<?php
	}

}

$wpad_comment_adons = new wpad_comment_adons();