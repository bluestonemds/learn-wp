<?php

// Recent Comments Widget
class wpad_widget extends WP_Widget {

	function __construct() {
		
		parent::__construct(
			'wpb_widget', 

			__('WPAD Recent Comments', 'wpad'), 

			array( 'description' => __( 'Your site’s most recent comments.', 'wpad' ), ) 
		);
		
	}

	// Creating widget front-end
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance['title'] );

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title']; 

		$options = array(
			'number' => $instance['no_of_comments'],
			'status' => 'approve'
		);

		$results = get_comments( $options ); 
		$wpad_frontend_comment_form = new wpad_frontend_comment_form();
		$db_option = get_option('wpad_comment_form'); 

		$this->check_template( $results , $wpad_frontend_comment_form , $instance , $db_option );

		echo $args['after_widget'];
	}

	function check_template( $results , $wpad_frontend_comment_form , $instance , $db_option ){

		switch ( $instance['template'] ) {
			case '1':
				$this->comment_style_1( $results , $wpad_frontend_comment_form , $instance , $db_option );
				break;

			case '2':
				$this->comment_style_2( $results , $wpad_frontend_comment_form , $instance , $db_option );
				break;
			
			default:
				$this->comment_style_3( $results , $wpad_frontend_comment_form , $instance , $db_option );
				break;
		}

	}

	function comment_style_3( $results , $wpad_frontend_comment_form , $instance , $db_option ){ ?>

		<ul class="cb-recent-comments-avatar">
			<?php 
			if( !empty($results) ){ 

				foreach( $results as $key => $value ){  
					$form_id = $wpad_frontend_comment_form->get_the_selected_comment_form( $value->comment_post_ID );

					if( empty( $form_id ) ){
						$comment_object = null;
					} else {
						$comment_object = $db_option[$form_id];
					}

					$avatar = $wpad_frontend_comment_form->validate_gravatar( $value->comment_author_email , $comment_object , (array)$value );?>

					<li class="cb-comment-with-avatar">
						<div class="cb-avatar">
							<?php echo $avatar; ?>
						</div>
						<div class="cb-comment">
							
							<?php echo $value->comment_author; ?> on
							
							<a href="<?php echo get_permalink( $value->comment_post_ID ); ?>#wpad_comment_<?php echo $value->comment_ID; ?>"><?php echo get_the_title( $value->comment_post_ID );?></a>
						</div>
					</li>

					<?php
				}

			}
			?>
		</ul>

		<?php
	}

	function comment_style_2( $results , $wpad_frontend_comment_form , $instance , $db_option ){ ?>

		<ul class="widget_ti_latest_comments">

			<?php if( !empty($results) ){ 

				$count = 1;
				foreach( $results as $key => $value ){  

					$form_id = $wpad_frontend_comment_form->get_the_selected_comment_form( $value->comment_post_ID );

					if( empty( $form_id ) || $form_id == 'error' ){
						$comment_object = null;
					} else {
						$comment_object = $db_option[$form_id];
					}

					$avatar = $wpad_frontend_comment_form->validate_gravatar( $value->comment_author_email , $comment_object , (array)$value ); ?>

					<li> 
						<header class="clearfix"> 
							<figure> 
								<a href="<?php echo get_permalink( $value->comment_post_ID ); ?>#wpad_comment_<?php echo $value->comment_ID; ?>"> 
									<?php echo $avatar; ?>
								</a> 
							</figure> 
							<span class="commentnum"> <?php echo $count++; ?> </span> 
							<span class="comment-author"> <?php echo sanitize_text_field( $value->comment_author ); ?>	 </span> 
							<a href="<?php echo get_permalink( $value->comment_post_ID ); ?>#wpad_comment_<?php echo $value->comment_ID; ?>" class="comment-post"> 
								<?php echo get_the_title( $value->comment_post_ID );?>
							</a> 
						</header>
						<div class="comment-text"> 
							<?php echo $this->wpad_truncate( sanitize_text_field( $value->comment_content ) , $instance['length'] ); ?>
						</div>
					</li>
				
				<?php 
				}

			} ?>

		</ul>

		<?php
	}

	function comment_style_1( $results , $wpad_frontend_comment_form , $instance , $db_option ){ ?>

		<ul class="wpad-recent-comments-widget">
			
			<?php 
			if( !empty($results) ){
				
				foreach( $results as $key => $value ){ 
					$form_id = $wpad_frontend_comment_form->get_the_selected_comment_form( $value->comment_post_ID );

					if( empty( $form_id ) || $form_id == 'error' ){
						$comment_object = null;
					} else {
						$comment_object = $db_option[$form_id];
					}

					$avatar = $wpad_frontend_comment_form->validate_gravatar( $value->comment_author_email , $comment_object , (array)$value );
					?>				
					<li class="clearfix">
						
						<div class="wpad-widget-post-thumbnail">
							<?php echo $avatar; ?>
						</div>		
						
						<div class="author-name">
							<p class="entry-title">
								<?php echo $value->comment_author; ?>	
								on <a href="<?php echo get_permalink( $value->comment_post_ID ); ?>#wpad_comment_<?php echo $value->comment_ID; ?>">
									<?php echo get_the_title( $value->comment_post_ID );?>
								</a>						
							</p>
						</div>

						<div class="widget-content">					

							<div class="comment-text">
								
								<a class="wpad_recent_comment_link" href="<?php echo get_permalink( $value->comment_post_ID ); ?>#wpad_comment_<?php echo $value->comment_ID; ?>">			
								<?php echo $this->wpad_truncate( sanitize_text_field( $value->comment_content ) , $instance['length'] ); ?>
								</a>
							</div><!-- .comment-text -->

							<div class="comment-time">
								<?php echo $wpad_frontend_comment_form->timeAgo( $value->comment_date ); ?>						
							</div><!-- .comment-time -->

						</div> <!-- .widget-content -->
					</li>
					<?php 
				} 

			} else {
				_e( 'Sorry, there are no comments to show.' , 'wpad' );
				} ?>

		</ul>

		<?php
	}

	function wpad_truncate($string, $length, $dots = "...") {
	    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
	}
		
	// Widget Backend 
	public function form( $instance ) {
		
		$title = !empty( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'What People Says', 'wpad' ); 
		$no_of_comments = !empty( $instance[ 'no_of_comments' ] ) ? $instance[ 'no_of_comments' ] : 3;
		$length = !empty( $instance[ 'length' ] ) ? $instance[ 'length' ] : 50;
		$template = !empty( $instance[ 'template' ] ) ? $instance[ 'template' ] : 1;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'no_of_comments' ); ?>"><?php _e( 'Number of comments:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'no_of_comments' ); ?>" name="<?php echo $this->get_field_name( 'no_of_comments' ); ?>" type="number" value="<?php echo esc_attr( $no_of_comments ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'length' ); ?>"><?php _e( 'Length of comments:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'length' ); ?>" name="<?php echo $this->get_field_name( 'length' ); ?>" type="number" value="<?php echo esc_attr( $length ); ?>" />
		</p>
		<p>
			<label><?php _e( 'Choose Template:' ); ?></label> <br>
			<label>
				<input class="widefat" name="<?php echo $this->get_field_name( 'template' ); ?>" type="radio" value="1" <?php checked( $template , 1 );?> />
				<?php _e( 'Style 1' ); ?>
			</label>
			<br>
			<label>
				<input class="widefat" name="<?php echo $this->get_field_name( 'template' ); ?>" type="radio" value="2" <?php checked( $template , 2 );?> />
				<?php _e( 'Style 2' ); ?>
			</label>
			<br>
			<label>
				<input class="widefat" name="<?php echo $this->get_field_name( 'template' ); ?>" type="radio" value="3" <?php checked( $template , 3 );?> />
				<?php _e( 'Style 3' ); ?>
			</label>
		</p>
		<?php 
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['no_of_comments'] = ( ! empty( $new_instance['no_of_comments'] ) ) ? strip_tags( $new_instance['no_of_comments'] ) : 3;
		$instance['length'] = ( ! empty( $new_instance['length'] ) ) ? strip_tags( $new_instance['length'] ) : 50;
		$instance['template'] = ( ! empty( $new_instance['template'] ) ) ? strip_tags( $new_instance['template'] ) : 1;
		return $instance;
	}

}

// Register and load the widget
function wpad_load_widget() {
	register_widget( 'wpad_widget' );
}
add_action( 'widgets_init', 'wpad_load_widget' );
