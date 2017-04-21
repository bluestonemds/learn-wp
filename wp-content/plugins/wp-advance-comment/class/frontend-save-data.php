<?php
/**
* Save data to the comment table on the database
*/
class wpad_save_data{

	function __construct(){
		add_action( 'wp_ajax_wpad_save_comment' , array( $this , 'wpad_save_comment' ) );
		add_action( 'wp_ajax_nopriv_wpad_save_comment' , array( $this , 'wpad_save_comment' ) );
		//add_action( 'wpad_after_comment_insert' , array( $this , 'notification_publish' ) , 10 , 2 );
		add_action( 'wpad_after_comment_status_change' , array( $this , 'send_mail_after_approve_comment' ) );
		add_action( 'wp_ajax_nopriv_wpad_save_comment_report' , array( $this , 'wpad_save_comment_report' ) );
		add_action( 'wp_ajax_wpad_save_comment_report' , array( $this , 'wpad_save_comment_report' ) );
	} 

	function get_comment_post_id( $comment_id ){

		global $wpdb;

		$results = $wpdb->get_results( "SELECT comment_post_id FROM {$wpdb->prefix}comments 
			WHERE comment_ID = {$comment_id}" , ARRAY_A );

		$title = get_the_title( $results[0]['comment_post_id'] );
		$link = get_permalink( $results[0]['comment_post_id']);

		return array( 
			'title' => $title,
			'url' => $link
		);

	}

	function sent_flagged_mail( $value , $comment_id ){

		$send_email = get_option('wpad_email_flagged_reports');

		if( $send_email != 'enable' ){
			return;
		}

		$args = array();
		$args['headers'] = $this->mail_headers();
		$args['to'] = get_option( 'admin_email' );
		$args['subject'] = __( 'Comment flagged as inappropriate' , 'wpad' );

		$post = $this->get_comment_post_id( $comment_id );

		$comment_object = get_comment( $comment_id , ARRAY_A );

		$message = '<p>Hi Admin,</p><br>
		<p>Someone has flagged a comment on <strong>' . $post['title'] . '</strong>.
		<a href="' . $post['url'] . '">Click here</a> to view the post.</p><br>
		<p style="background-color: #ddd; padding:15px; line-height: 22px;"><strong>Flagged Comment : </strong>' .  $comment_object['comment_content'] . '</p><br>
		<p><strong>Option Selected : </strong> ' . $value['option'] . '</p><br>';

		if( !empty($value['message']) ){
			$message .= '<p><strong>User Message : </strong>' . $value['message'] . '</p><br>';	
		}		

		$message .= '<p><strong>User IP Address : </strong>' . $_SERVER["REMOTE_ADDR"] . '</p>';

		$args['message'] = $message;

		$args = apply_filters( 'wpad_sent_flagged_mail' , $args , $value , $comment_id );

		wp_mail( $args['to'] , $args['subject'] , $args['message'] , $args['headers'] );

	}

	function wpad_save_comment_report(){

		$comment_id = sanitize_text_field( $_POST['comment_id'] );

		if( !empty($_POST) && is_numeric( $comment_id ) ){

			$flagged = get_comment_meta( $comment_id, 'comment_flagged_reports' , true );

			$args = array(
				'ip' => $_SERVER["REMOTE_ADDR"],
				'message' => stripslashes( sanitize_text_field( $_POST['message'] ) ),
				'option' => stripslashes( sanitize_text_field( $_POST['option'] ) ),
			);

			if( empty( $flagged ) ){

				update_comment_meta( $comment_id, 'comment_flagged_reports' , array($args) );

			} else {
				array_push( $flagged , $args );
				update_comment_meta( $comment_id, 'comment_flagged_reports' , $flagged );
			}

			$this->sent_flagged_mail( $args , $comment_id );

			$this->save_no_of_flags( $comment_id );

		}

		die;

	}

	function save_no_of_flags( $comment_id ){

		$no_of_flags = get_comment_meta( $comment_id , 'no_of_flags' , true );	
		
		if( empty($no_of_flags) ){
			update_comment_meta( $comment_id , 'no_of_flags' , 1 );
		} else {
			$flags = $no_of_flags + 1;
			update_comment_meta( $comment_id , 'no_of_flags' , $flags );
		}

		$this->sent_comment_to_spam( $comment_id );

	}

	function sent_comment_to_spam( $comment_id ){

		$wpad_flaging_threshold = get_option('wpad_flaging_threshold');

		if( empty($wpad_flaging_threshold) ){
			return;
		}

		$no_of_flags = get_comment_meta( $comment_id , 'no_of_flags' , true );

		if( $no_of_flags >= $wpad_flaging_threshold ){

			 wp_set_comment_status( $comment_id, 'hold' );

		}

	}

	function get_notification_user_subject( $comment_id ){
		$form_id = get_comment_meta( $comment_id , 'wpad_comment_form_id' , true );
		$data = get_option('wpad_comment_form');
		if( !empty($data) ){
			foreach( $data as $key => $value ){
				if( $key == $form_id ){
					$subject = !empty($data[$form_id]['other']['mail_approved_subject']) ? $data[$form_id]['other']['mail_approved_subject'] : __( 'Comment Approved' , 'wpad' );
					return $subject;
				}
			}
		}
	}

	function get_notification_user_message( $comment_id ){
		$form_id = get_comment_meta( $comment_id , 'wpad_comment_form_id' , true );
		$data = get_option('wpad_comment_form');
		if( !empty($data) ){
			foreach( $data as $key => $value ){
				if( $key == $form_id ){
					$message = !empty($data[$form_id]['other']['confirm_notification_message']) ? $data[$form_id]['other']['confirm_notification_message'] : '<p>Your comment has been approved on the %sitename% ( %siteurl% ).</p><p><strong>Comment Link :</strong> %permalink%</p>';
					return $message;
				}
			}
		}
	}

	function get_mail_from_name_email( $comment_id ){
		$form_id = get_comment_meta( $comment_id , 'wpad_comment_form_id' , true );
		$data = get_option('wpad_comment_form');
		if( !empty($data) ){
			foreach( $data as $key => $value ){
				if( $key == $form_id ){
					$from_name =  sanitize_text_field( $data[$key]['other']['mail_from_name'] );
					$from_email = sanitize_text_field( $data[$key]['other']['mail_from_email'] );
				}
			}
		}
		return array( $from_name , $from_email );
	}

	function send_mail_after_approve_comment( $comment_id ){
		$notification = get_comment_meta( $comment_id , 'email_on_approve' , true );
		$already_sent_mail = get_comment_meta( $comment_id , 'already_sent_mail' , true );
		
		$status = wp_get_comment_status( $comment_id );
		$from_name_email = $this->get_mail_from_name_email( $comment_id );
		if( $status == 'approved' && $notification == 'enable' && $already_sent_mail != 'yes' ){
			$to = get_comment_author_email( $comment_id );
			$content = $this->get_notification_user_message( $comment_id );
			$headers = $this->mail_headers( $from_name_email );
			$message = $this->replace_content_approved_comment( $content , $comment_id );
			$subject = $this->get_notification_user_subject( $comment_id );
			wp_mail( $to, $subject, $message, $headers );
			update_comment_meta( $comment_id , 'already_sent_mail' , 'yes' );
		}
	}
	function get_author_details( $result ){
		$user_id = get_current_user_id();
		$data = array();
		if( !empty($user_id) && $user_id != 0 ){
			$data['user_login'] = get_the_author_meta( 'user_login' , $user_id );
			$data['user_email'] = get_the_author_meta( 'user_email' , $user_id );
			$data['user_id'] = $user_id;
		} else {
			if( is_array( $result['user_name'] ) && $result['user_name']['meta_key'] == 'user_name' ){
				$data['user_login'] = sanitize_text_field( $result['user_name']['meta_value'] );
			}
			if( is_array( $result['user_email'] ) && $result['user_email']['meta_key'] == 'user_email' ){
				$data['user_email'] = sanitize_text_field( $result['user_email']['meta_value'] );
			}
			$data['user_id'] = 0;
		}	
		$data['comment'] = sanitize_text_field( $result['comment']['meta_value'] );
		$data['comment_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		return $data;
	}
	
	function comment_publish_pending( $form_id ){

		$form_id = preg_replace('/\D/', '', $form_id );

		$data = get_option('wpad_comment_form');
		if( !empty($data) ){
			foreach( $data as $key => $value ){
				if( $key == $form_id ){
					if( !empty( $data[$form_id]['other']['comment_automatically_approve'] ) && $data[$form_id]['other']['comment_automatically_approve'] == 'yes' ){
						return '1';
					}
				}
			}
		}
		return '0';
	}

	function check_user_comment( $comment , $user_login , $user_email ){

		$comment = sanitize_text_field( $comment );
		$user_login = sanitize_text_field( $user_login );
		$user_email = sanitize_text_field( $user_email );

		if( empty( $comment ) || empty( $user_login ) || empty( $user_email ) ){
			return true;
		}
		return false;

	}

	function wpad_save_comment(){

		$time = current_time('mysql');
		$user_data = $this->get_author_details( $_POST );

		$comment_check = $this->check_user_comment( $user_data['comment'] , $user_data['user_login'] , $user_data['user_email'] );

		if( $comment_check == true ){
			die;
		}

		$wpad_comment_status = $this->comment_publish_pending( $_POST['form_id'] );
		$data = array(
		    'comment_post_ID' => preg_replace('/\D/', '', $_POST['post_id'] ),
		    'comment_author' => sanitize_text_field( $user_data['user_login'] ) ,
		    'comment_author_email' => sanitize_text_field( $user_data['user_email'] ),
		    'comment_author_url' => 'http://',
		    'comment_content' => sanitize_text_field( $user_data['comment'] ),
		    'comment_type' => '',
		    'comment_parent' => 0,
		    'user_id' => preg_replace('/\D/', '', $user_data['user_id'] ),
		    'comment_author_IP' => sanitize_text_field( $user_data['ip'] ),
		    'comment_agent' => sanitize_text_field( $user_data['comment_agent'] ),
		    'comment_date' => $time,
		    'comment_approved' => $wpad_comment_status,
		);
		$comment_id = wp_insert_comment($data);
		do_action( 'wpad_after_comment_insert' , $wpad_comment_status , $comment_id );
		update_comment_meta( $comment_id, 'wpad_comment_form_id' , sanitize_text_field( $_POST['form_id'] ) );
		$this->save_mail_me_on_approve( $comment_id , $_POST );
		$this->save_comment_meta( $comment_id , $_POST , $_FILES );
		$this->mail_notification( $_POST , $comment_id );
		// Send mail after published
		if( $wpad_comment_status == 1 ){
			$this->send_mail_after_approve_comment( $comment_id );
		}
		die;
	}
	function save_mail_me_on_approve( $comment_id , $value ){
		if( !empty($value['email_me_on_approve']) && $value['email_me_on_approve'] == 'enable' ){
			update_comment_meta( $comment_id, 'email_on_approve' , 'enable' );
		} else {
			update_comment_meta( $comment_id, 'email_on_approve' , 'disable' );
		}
	}
	function wpad_upload_image( $file , $meta_key ){
		$files_temp = file_get_contents( $file[$meta_key]["tmp_name"][0] );
		 $file_image = array(
                'name' => $file[$meta_key]['name'][0],
                'type' => $file[$meta_key]['type'][0],
                'tmp_name' => $file[$meta_key]['tmp_name'][0],
                'error' => $file[$meta_key]['error'][0],
                'size' => $file[$meta_key]['size'][0]
            );
		$upload_overrides = array( 'test_form' => false );
	    $upload = wp_handle_upload( $file_image , $upload_overrides, time() );
	    //resize image - start
	    $image = wp_get_image_editor($upload['file']); // Return an implementation that extends WP_Image_Editor
	    if (!is_wp_error($image)) {
	        $image->resize(300, 300, true); //Here we set width and height to 300, 300 respectively
	        $image->save($upload['file']);
	    }
	    //resize image - end
	     $attachment = array(
	        'guid' => $upload['url'],
	        'post_mime_type' => $upload['type'],
	        'post_title' => preg_replace('/\.[^.]+$/', '', basename( $file[$meta_key]['name'][0] )),
	        'post_content' => '',
	        'post_status' => 'inherit'
	    );
	     // Insert the attachment.
    	$attach_id = wp_insert_attachment($attachment, $upload['file'], 0);
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	    require_once( ABSPATH . 'wp-admin/includes/image.php' );
		// Generate the metadata for the attachment, and update the database record.
	    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
	    wp_update_attachment_metadata($attach_id, $attach_data);
	    return $attach_id;
	}
	function save_user_image( $data , $key , $comment_id , $file ){
		foreach( $data[$key] as $key1 => $value1 ){
			if( $key1 == 'meta_key' ){
				foreach( $data[$key][$key1] as $key2 => $value2 ){
					$meta_key = sanitize_text_field( $value2 );
					$meta_value = $this->wpad_upload_image( $file , $meta_key );
					add_comment_meta( $comment_id, $meta_key , $meta_value );
				}
			}
		}
	}
	function save_metas( $data , $key , $comment_id ){
		foreach( $data[$key] as $key1 => $value1 ){
			if( $key1 == 'meta_key' ){
				foreach( $data[$key][$key1] as $key2 => $value2 ){
					$meta_key = sanitize_text_field( $value2 );
					$meta_value = strip_tags( $data[$key]['meta_value'][$key2] );
					add_comment_meta( $comment_id, $meta_key , $meta_value );
				}
			}
		}
	}
	function save_comment_meta( $comment_id , $data , $file = null ){
		foreach( $data as $key => $value ){
			if( $key == 'text' ){
				$this->save_metas( $data , $key , $comment_id );				
			} elseif( $key == 'textarea' ){
				$this->save_metas( $data , $key , $comment_id );	
			} elseif( $key == 'radio' ){
				$this->save_metas( $data , $key , $comment_id );	
			} elseif( $key == 'checkbox' ){
				$this->save_metas( $data , $key , $comment_id );	
			} elseif( $key == 'select' ){
				$this->save_metas( $data , $key , $comment_id );	
			} elseif( $key == 'email' ){
				$this->save_metas( $data , $key , $comment_id );	
			} elseif( $key == 'url' ){
				$this->save_metas( $data , $key , $comment_id );	
			} elseif( $key == 'multiselect' ){
				$this->save_metas( $data , $key , $comment_id );	
			} elseif( $key == 'user_image' ){
				$this->save_user_image( $data , $key , $comment_id , $file );
			}
		}
	}
	function mail_headers( $from = null ){
		$headers = "MIME-Version: 1.0\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "X-Mailer: PHP's mail() Function\r\n";
        if( !empty($from) ){
        	$headers .= "From: {$from[0]} <{$from[1]}>" . "\r\n";
        }
        return $headers;
	}
	function get_post_id( $comment_id ){
		global $wpdb;
		$results = $wpdb->get_results("SELECT `comment_post_ID` FROM {$wpdb->prefix}comments
			WHERE `comment_id` = $comment_id" , ARRAY_A );
		if(!empty($results)){
			return $results[0]['comment_post_ID'];
		}
	}
	function replace_content_approved_comment( $message , $comment_id ){
		$form_id = get_comment_meta( $comment_id , 'wpad_comment_form_id' , true );
		$post_title = get_the_title( $this->get_post_id( $comment_id ) );
		$author = get_comment_author( $comment_id );
		$author_email = get_comment_author_email( $comment_id );
		$comment = get_comment_text( $comment_id );
		$permalink = get_permalink( $this->get_post_id( $comment_id ) );
		$content = nl2br( $message );
		$content = trim($content);
		$content = str_replace( "%sitename%" , get_bloginfo('name') , $content );
		$content = str_replace( "%siteurl%" , '<a href="' . site_url() . '">' . site_url() . '</a>' , $content );
		$content = str_replace( "%post_title%" , $post_title , $content );
		$content = str_replace( "%comment_author%" , $author , $content );
		$content = str_replace( "%comment_author_email%" , $author_email , $content );
		$content = str_replace( "%comment%" , $comment , $content );
		$content = str_replace( "%permalink%" , '<a href="' . $permalink . '">' . $permalink . '</a>' , $content );
		$content = $this->replace_comment_meta( $content , null , $comment_id , $form_id );
		return $content;
	}
	function replace_content( $message , $object , $comment_id ){
		$post_title = get_the_title( $object['post_id'] );
		$user_id = get_current_user_id();
		if( !empty($user_id) && $user_id != 0 ){
			$author = get_the_author_meta( 'user_login' , $user_id );
			$author_email = get_the_author_meta( 'user_email' , $user_id );
		} else {		
			if( is_array( $object['user_name'] ) && $object['user_name']['meta_key'] == 'user_name' ){
				$author = $object['user_name']['meta_value'];
			}
			if( is_array( $object['user_email'] ) && $object['user_email']['meta_key'] == 'user_email' ){
				$author_email = $object['user_email']['meta_value'];
			}
		}
		$comment = $object['comment']['meta_value'];
		$permalink = get_permalink( $object['post_id'] );
		$content = nl2br( $message );
		$content = trim($content);
		$content = str_replace( "%sitename%" , get_bloginfo('name') , $content );
		$content = str_replace( "%siteurl%" , '<a href="' . site_url() . '">' . site_url() . '</a>' , $content );
		$content = str_replace( "%post_title%" , $post_title , $content );
		$content = str_replace( "%comment_author%" , $author , $content );
		$content = str_replace( "%comment_author_email%" , $author_email , $content );
		$content = str_replace( "%comment%" , $comment , $content );
		$content = str_replace( "%permalink%" , '<a href="' . $permalink . '">' . $permalink . '</a>' , $content );
		$content = $this->replace_comment_meta( $content , $object , $comment_id );
		//$content = strip_tags( $content , "<img><p><ul><ol><li><a><strong><br><i>" );
		return $content;
	}
	function replace_comment_meta( $content , $object = null , $comment_id , $id = null ){
		$comment_option = get_option( 'wpad_comment_form' );
		$form_id =  !empty($object) ? $object['form_id'] : $id;
		foreach( $comment_option[$form_id] as $key => $value ){
			if( $key == 'other' || $value['custom_field'] == 'section_break' ){
				continue;
			} 
			else {
				$custom_field_1 = array( 'radio','select' );
				$custom_field_2 = array( 'multi_select','checkbox' );
				$custom_field_3 = array( 'user_image' );
				if( in_array( $value['custom_field'] , $custom_field_1 ) ){
					$db_value = get_comment_meta( $comment_id , $value['meta_key'] , true );
					if( $value['options'] ){
						foreach( $value['options'] as $key1 => $value1 ){
							if( $value1['option'] == $db_value ){
								$meta_value = sanitize_text_field( $value1['label'] );
							}
						}
					}
				} elseif( in_array( $value['custom_field'] , $custom_field_2 ) ){
					$db_value = explode( ',' , get_comment_meta( $comment_id , $value['meta_key'] , true ) );
					if( !empty( $value['options'] ) ){
						$a = array();
						foreach( $value['options'] as $key2 => $value2 ){
							if( in_array( $value2['option'] , $db_value) ){
								$a[] = sanitize_text_field( $value2['label'] );
							}
						}
					}
					$meta_value = implode(', ' ,$a);
				} elseif( in_array( $value['custom_field'] , $custom_field_3 ) ){
					$image_id = get_comment_meta( $comment_id , $value['meta_key'] , true );
					$image_attributes = wp_get_attachment_image_src( $image_id , 'thumbnail' ); 
					if( !empty($image_attributes) ){
						$meta_value = '<img src="' . $image_attributes[0] . '">';
					}
				} else {
					$meta_value = get_comment_meta( $comment_id , $value['meta_key'] , true );
				}
				$content = str_replace( '%custom_' . $value['meta_key'] . '%' , sanitize_text_field( $meta_value ) , $content );
			}
		}
		return $content;
	}
	function mail_notification( $object , $comment_id ){

		$comment_option = get_option( 'wpad_comment_form' );
		$form_id = sanitize_text_field( $object['form_id'] );

		if( !empty( $comment_option[$form_id]['other']['notification'] ) && $comment_option[$form_id]['other']['notification'] == 'enable' ){
			echo 'success';
			$to = !empty( $comment_option[$form_id]['other']['notification_to'] ) ? $comment_option[$form_id]['other']['notification_to'] : get_option( 'admin_email' );
			$subject = !empty( $comment_option[$form_id]['other']['notification_subject'] ) ? $comment_option[$form_id]['other']['notification_subject'] : 'New Comment';
			$default_message = 'Hi Admin,<br>
			A new comment has been posted in your site %sitename% (%siteurl%).<br>
			Here are the details.
			Post Title : %post_title%
			Comment Author : %comment_author%
			Comment Author Email : %comment_author_email%
			Comment : %comment%
			Comment Link : %permalink%';
			$message = !empty( $comment_option[$form_id]['other']['notification_message'] ) ? $comment_option[$form_id]['other']['notification_message'] : $default_message;
			$content = $this->replace_content( $message , $object , $comment_id );
			$headers = $this->mail_headers();
			wp_mail( $to, $subject, $content , $headers );
		}		
	}
}
$save_data = new wpad_save_data();