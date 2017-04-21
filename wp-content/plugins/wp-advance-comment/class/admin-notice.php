<?php
class wpad_admin_notices {

	function __construct(){

		add_action( 'admin_notices', array( $this , 'rate_plugin' ) ); 
		register_activation_hook( 'wp-advance-comment/index.php' , array( $this , 'on_activation' ) );
		add_action( 'wp_ajax_ignore_rate_message' , array( $this , 'ignore_rate_message' ) );

	}

	public static function on_activation(){

		$data = array(
			'date' => date( 'Y-m-d' )
		);

		update_option( 'rating_reminder' , $data );
	}

	function ignore_rate_message(){

		if( sanitize_text_field( $_POST['hide'] ) != 'yes' ){
			die;
		}

		$data = array(
			'date' => date( 'Y-m-d' )
		);

		update_option( 'rating_reminder' , $data );
		die;

	}

	function date_diff( $data ){

		$date1 = $data['date'];
		$date2 = date("Y-m-d");

		$interval = strtotime( $date2 ) - (strtotime( $date1 ));

		return floor($interval/3600/24);

	}

	function rate_plugin(){ 

		$data = get_option( 'rating_reminder' ); 

		$diff_days = $this->date_diff( $data ); 

		// If more than 1 week show the notice
		if( $diff_days < 30 ){
			return;
		} ?>

		<div class="wpad_rate_plugin">

			<div class="wpad_notice_logo">
				<img src="<?php echo WPAD_PLUGIN_DIR . 'images/small_logo.jpg'; ?>">
			</div>
			<div class="wpad_notice_message">
				<p>It seems like you are using WP Advanced Comment plugin for a while. If you have some time please give us 5 star rating. We have spent countless hours making this plugin. Help us to make it better and biggger. <br>Thank you.</p>
			</div>
			<div class="wpad_notice_buttons">
				<a class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/wp-advance-comment?rate=5#postform" target="blank">Rate it</a>
				<a class="button wpad_leave_it" href="javascript:void(0)">
					Leave it
					<img src="<?php echo includes_url( 'images/spinner.gif' ); ?>" style="display:none">
				</a>
			</div>

		</div>	

		<script>

			jQuery(document).on( 'click' , '.wpad_leave_it' , function(){

				jQuery.ajax({
					url : "<?php echo admin_url( 'admin-ajax.php' ); ?>",
			 		type : 'POST',
			 		data : {
			 			action : 'ignore_rate_message',
			 			hide : 'yes'
			 		},
			 		beforeSend : function(){
			 			jQuery('.wpad_leave_it img').show();
			 		}, 
			 		success : function(){
			 			jQuery('.wpad_rate_plugin').remove();
			 		}
				});

			});

		</script>

		<style>
			.wpad_rate_plugin .wpad_notice_logo{
				width: 8%;
				float: left;
				text-align: center;
			}
			.wpad_rate_plugin {
			    background: #fff none repeat scroll 0 0;
			    border-bottom: 3px solid #cc006f;
			    box-shadow: -1px -1px 6px #aaa;
			    display: inline-block;
			    margin-top: 20px;
			    padding: 13px;
			    width: 96%;
			}
			.wpad_notice_message p {
			    font-size: 13px;
			    font-weight: 300;
			    margin: 0;
			    padding: 0 10px;
			}
			.wpad_notice_message {
			    float: left;
			    width: 80%;
			    
			}
			.wpad_notice_logo img {
			    border-radius: 21%;
			    width: 69%;
			}
			.wpad_notice_buttons {
			    float: right;
			}
			.wpad_notice_buttons a {
			    margin-bottom: 5px !important;
			    width: 100%;
			    text-align: center;
			}
			.wpad_leave_it img {
			    margin-top: 4px;
			    padding: 0 0 0 12px;
			    position: absolute;
			    width: 18px;
			}
		</style>

		<?php
	}

}

$wpad_admin_notices = new wpad_admin_notices();