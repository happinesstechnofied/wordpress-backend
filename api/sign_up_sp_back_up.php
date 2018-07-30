<?php
require_once('config.php');
//create users
require_once('functions.php');
try {
	
	switch ($_REQUEST['Service'])
	{
		case "upload_image":
		
		$user_id = "3";
		
		$upload_dir = wp_upload_dir();
		$user_dirname = $upload_dir['basedir'].'/profiles/';
		define('UPLOAD_DIR', $user_dirname);
		
		$user_avtar = $_POST['profile_picture'];
			
		$user_avtar = str_replace('data:image/png;base64,', '', $user_avtar);
		$user_avtar = str_replace(' ', '+', $user_avtar);
		$dataimage = base64_decode($user_avtar);
		
		$file = UPLOAD_DIR . $user_id . '.png';
				
					
					if (file_exists($file)) {
						unlink($file);
					}
					
					$success = file_put_contents($file, $dataimage);
					
					//download_image1($user_avtar, UPLOAD_DIR . $user_id . '.png');
					
					$upload_dir = wp_upload_dir();
					$upload_dir_url = $upload_dir['baseurl'] .'/profiles/'.$user_id.'.png';
					
					
					
					// vars
					$url             = get_the_author_meta( 'cupp_meta', $user_id );
					$upload_url      = get_the_author_meta( 'cupp_upload_meta', $user_id );
					$upload_edit_url = get_the_author_meta( 'cupp_upload_edit_meta', $user_id );
					
					if ( $upload_url ) {
						$upload_edit_url = get_site_url() . $upload_edit_url;
					}
					
					$values = array(
						// String value. Empty in this case.
						'cupp_meta'             => $upload_dir_url,

						// File path, e.g., http://3five.dev/wp-content/plugins/custom-user-profile-photo/img/placeholder.gif.
						'cupp_upload_meta'      => "",

						// Edit path, e.g., /wp-admin/post.php?post=32&action=edit&image-editor.
						'cupp_upload_edit_meta' => $upload_edit_url,
					);

					foreach ( $values as $key => $value ) {
						update_user_meta( $user_id, $key, $value );
					}
					
					echo $upload_dir_url;
					die;
		
		break;
		case "send_otp":
			if(isset($_REQUEST['user_name']) && isset($_REQUEST['mobile_no']) && isset($_REQUEST['email']) && isset($_REQUEST['password']) && isset($_REQUEST['device_id']) && isset($_REQUEST['device_type']) )
			{
				$user_name = $_REQUEST['user_name'];
				$mobile_no = $_REQUEST['mobile_no'];
				$email = $_REQUEST['email'];
				$password = $_REQUEST['password'];
				// $user_role = $_REQUEST['user_role'];
				$device_id = $_REQUEST['device_id'];
				$device_type = $_REQUEST['device_type'];
				$profile_picture = $_POST['profile_image'];
				
				if ( email_exists($email) ) {
					$user = get_user_by( 'email', $email );
					if(in_array("service-provider",$user->roles)){
						$status = 2;
						$data['status'] = ($status > 1) ? 'failed' : 'success';
						$errorMsg="Email already exists";
						$data['message'] = $errorMsg;
						echo json_encode($data);
						die;
					}
				}
				
				//if ( mbile_no_exists($mobile_no) ) {
				if ( username_exists( $mobile_no ) ) {
					$user = get_user_by( 'login', $mobile_no );
					if(in_array("service-provider",$user->roles)){
						$status = 2;
						$data['status'] = ($status > 1) ? 'failed' : 'success';
						$errorMsg="Mobile no already exists";
						$data['message'] = $errorMsg;
						echo json_encode($data);
						die;
					}
				}
				
				$default_newuser = array(
						'user_pass' =>  $password,
						'user_login' => $mobile_no,
						'first_name' => $user_name,
						'user_email' => $email,
						'nickname' => $user_name,
						'display_name' => $user_name,
						'role' => 'service-provider'
					);
					
				$user_id = wp_insert_user($default_newuser);
				
				update_field('field_5b0b579195189',$mobile_no,'user_'.$user_id);
				
				update_field('field_5b0d1a816d88a', 'pending', 'user_'.$user_id);
				
				
				
				$upload_dir = wp_upload_dir();
				$user_dirname = $upload_dir['basedir'].'/profiles/';
				define('UPLOAD_DIR', $user_dirname);
				
				$user_avtar = $profile_picture;
					
				$user_avtar = str_replace('data:image/png;base64,', '', $user_avtar);
				$user_avtar = str_replace(' ', '+', $user_avtar);
				$dataimage = base64_decode($user_avtar);
				
				$file = UPLOAD_DIR . $user_id . '.png';
				
					
				if (file_exists($file)) {
					unlink($file);
				}
					
				$success = file_put_contents($file, $dataimage);
				
				$upload_dir_url = $upload_dir['baseurl'] .'/profiles/'.$user_id.'.png';
				
					// vars
					$url             = get_the_author_meta( 'cupp_meta', $user_id );
					$upload_url      = get_the_author_meta( 'cupp_upload_meta', $user_id );
					$upload_edit_url = get_the_author_meta( 'cupp_upload_edit_meta', $user_id );
					
					if ( $upload_url ) {
						$upload_edit_url = get_site_url() . $upload_edit_url;
					}
					
					$values = array(
						// String value. Empty in this case.
						'cupp_meta'             => $upload_dir_url,

						// File path, e.g., http://3five.dev/wp-content/plugins/custom-user-profile-photo/img/placeholder.gif.
						'cupp_upload_meta'      => "",

						// Edit path, e.g., /wp-admin/post.php?post=32&action=edit&image-editor.
						'cupp_upload_edit_meta' => $upload_edit_url,
					);

					foreach ( $values as $key => $value ) {
						update_user_meta( $user_id, $key, $value );
					}
				
				$wpdb->insert(
					$wpdb->prefix.'register_user_devices', 
					array( 
						'user_id' => $user_id,
						'device_id' => $device_id,
						'device_type' => $device_type
					), 
					array( 
						'%d',
						'%s',
						'%d'
					)
				);
			
						$pincode = '1234';
			
						$to = $email;
						$subject = 'Service Provider App - Please verify your account';
						$body = "Hi, $user_name<br>";
						$body .= 'Welcome to Service Provider app,<br>Thank you for signing up with Service Provider app! To activate your account, please enter below One Time Pin for verify your account.';
						$body .= "<br><br><br>$pincode<br><br><br>If you have any problems, please contact us.";
						$body .= '<br>Thanks,';
						$body .= '<br>Service Provider app';


						//$body .= 'Click Here for activate your account : <a href="'.$activation_link.'">Activate Now</a> or cppy & pest below link to new browser tab for activate your account : <br>'.$activation_link;
						$headers[] = 'From: Service Provider app <test@orbrixtechnologies.com>';
						$headers[] = 'Content-Type: text/html; charset=UTF-8';
						wp_mail( $to, $subject, $body, $headers );	

						
				// $smsurl = "http://login.arihantsms.com/vendorsms/pushsms.aspx?user=zota&password=demo12345&msisdn=".$phonenumber."&sid=NVEDIC&msg=Hi,%20your%20nutravedic.com%20one%20time%20PIN%20is%20".$pin.".%20Introductory%20offer:%20Hurry%20get%20mega%20discounts%20on%20all%20nutritional%20supplements%20&%20ayurvedic%20products%20at%20nutravedic.com&fl=0&gwid=2";
						
						$status = 1;
						$data['status'] = ($status > 1) ? 'failed' : 'success';
						$data['pincode'] = $pincode;
						$data['user_id'] = $user_id;
						echo json_encode($data);
						die;
			}else{
				$status = 2;
				$data['status'] = ($status > 1) ? 'failed' : 'success';
				$data['message'] = "Access denied.";
				echo json_encode($data);
				die;
			}
		
		break;
		case "resend_otp":
			$user_id = $_REQUEST['user_id'];
			$user = get_user_by( 'id', $user_id );
			$email = $user->user_email;
			//$email = "vishal.gandhi71.86@gmail.com";
			$nicename = $user->display_name;

			// $email = $_REQUEST['email'];
			// //$mobile_no = $_REQUEST['mobile_no'];
			// $user_name = $_REQUEST['user_name'];
			$pincode = '5678';
			
						$to = $email;
						$subject = 'Service Provider App - Please verify your account';
						$body = "Hi, $nicename<br>";
						$body .= 'Welcome to Service Provider app,<br>Thank you for signing up with Service Provider app! To activate your account, please enter below One Time Pin for verify your account.';
						$body .= "<br><br><br>$pincode<br><br><br>If you have any problems, please contact us.";
						$body .= '<br>Thanks,';
						$body .= '<br>Service Provider app';


						//$body .= 'Click Here for activate your account : <a href="'.$activation_link.'">Activate Now</a> or cppy & pest below link to new browser tab for activate your account : <br>'.$activation_link;
						$headers[] = 'From: Service Provider app <test@orbrixtechnologies.com>';
						$headers[] = 'Content-Type: text/html; charset=UTF-8';
						wp_mail( $to, $subject, $body, $headers );	

						
				// $smsurl = "http://login.arihantsms.com/vendorsms/pushsms.aspx?user=zota&password=demo12345&msisdn=".$phonenumber."&sid=NVEDIC&msg=Hi,%20your%20nutravedic.com%20one%20time%20PIN%20is%20".$pin.".%20Introductory%20offer:%20Hurry%20get%20mega%20discounts%20on%20all%20nutritional%20supplements%20&%20ayurvedic%20products%20at%20nutravedic.com&fl=0&gwid=2";
						
						$status = 1;
						$data['status'] = ($status > 1) ? 'failed' : 'success';
						$data['pincode'] = $pincode;
						echo json_encode($data);
						die;
		break;
		case "create_user_sp":
			$user_id = $_REQUEST['user_id'];

			$datasss = update_field('field_5b0d1a816d88a', 'approved', 'user_'.$user_id);
			
			$user = get_user_by( 'id', $user_id );
			
			$data1['user_id'] = $user_id;
			$data1['user_name'] = $user->first_name;
			$data1['email'] = $user->user_email;
			
			$data1['profile_picture'] = get_cupp_meta( $user->ID, 'thumbnail' );
			$data1['address'] = (get_field('address','user_'.$user_id))?get_field('address','user_'.$user_id):"";
			$data1['city'] = (get_field('city','user_'.$user_id))?get_field('city','user_'.$user_id):"";
			$data1['pincode'] = (get_field('pincode','user_'.$user_id))?get_field('pincode','user_'.$user_id):"";
			$data1['mobile_no'] = get_field('mobile_no','user_'.$user_id);
			
			
			$status = 1;
			$data['status'] = ($status > 1) ? 'failed' : 'success';
			$data['message'] = "User created successfully";
			$data['user_data'] = $data1;
			echo json_encode($data);
			die;
		break;
		case "change_profile":
			$user_id = $_REQUEST['user_id'];
			$user_name = $_REQUEST['user_name'];
			$email = $_REQUEST['email'];
			$mobile_no = str_replace("+91+91","+91",$_REQUEST['mobile_no']);
			$profile_picture = $_POST['profile_picture'];
			$address = $_REQUEST['address'];
			$city = $_REQUEST['city'];
			$pincode = $_REQUEST['pincode']; 
			
			
				if ( email_exists($email) ) {
					$user = get_user_by( 'email', $email );
					if($user->ID != $user_id && in_array("service-provider",$user->roles)){
						$status = 2;
						$data['status'] = ($status > 1) ? 'failed' : 'success';
						$errorMsg="Email already exists";
						$data['message'] = $errorMsg;
						echo json_encode($data);
						die;
					}
				}
				
				//if ( mbile_no_exists($mobile_no) ) {
				if ( username_exists( $mobile_no ) ) {
					$user = get_user_by( 'login', $mobile_no );
					if($user->ID != $user_id && in_array("service-provider",$user->roles)){
						$status = 2;
						$data['status'] = ($status > 1) ? 'failed' : 'success';
						$errorMsg="Mobile no already exists";
						$data['message'] = $errorMsg;
						echo json_encode($data);
						die;
					}
				}
			
			
			
			update_user_meta( $user_id, 'first_name', $user_name);
			update_user_meta( $user_id, 'user_email', $email);
			update_user_meta( $user_id, 'nickname', $user_name);
			update_user_meta( $user_id, 'display_name', $user_name);
			update_field('field_5b0b579195189',$mobile_no,'user_'.$user_id);
			update_field('field_5b0ca7834148b', $address, 'user_'.$user_id);
			update_field('field_5b0ca4064148a', $city, 'user_'.$user_id);
			update_field('field_5b0ca8004148c', $pincode, 'user_'.$user_id);
			
			$upload_dir = wp_upload_dir();
				$user_dirname = $upload_dir['basedir'].'/profiles/';
				define('UPLOAD_DIR', $user_dirname);
				
				$user_avtar = $profile_picture;
					
				$user_avtar = str_replace('data:image/png;base64,', '', $user_avtar);
				$user_avtar = str_replace(' ', '+', $user_avtar);
				$dataimage = base64_decode($user_avtar);
				
				$file = UPLOAD_DIR . $user_id . '.png';
				
					
				if (file_exists($file)) {
					unlink($file);
				}
					
				$success = file_put_contents($file, $dataimage);
				
				$upload_dir_url = $upload_dir['baseurl'] .'/profiles/'.$user_id.'.png';
				
					// vars
					$url             = get_the_author_meta( 'cupp_meta', $user_id );
					$upload_url      = get_the_author_meta( 'cupp_upload_meta', $user_id );
					$upload_edit_url = get_the_author_meta( 'cupp_upload_edit_meta', $user_id );
					
					if ( $upload_url ) {
						$upload_edit_url = get_site_url() . $upload_edit_url;
					}
					
					$values = array(
						// String value. Empty in this case.
						'cupp_meta'             => $upload_dir_url,

						// File path, e.g., http://3five.dev/wp-content/plugins/custom-user-profile-photo/img/placeholder.gif.
						'cupp_upload_meta'      => "",

						// Edit path, e.g., /wp-admin/post.php?post=32&action=edit&image-editor.
						'cupp_upload_edit_meta' => $upload_edit_url,
					);

					foreach ( $values as $key => $value ) {
						update_user_meta( $user_id, $key, $value );
					}
			
			
			
			$data1['user_id'] = $user_id;
			$data1['user_name'] = $user->first_name;
			$data1['email'] = $user->user_email;
			$data1['mobile_no'] = get_field('mobile_no','user_'.$user_id);
			$data1['profile_picture'] = get_cupp_meta( $user->ID, 'thumbnail' );
			$data1['address'] = get_field('address','user_'.$user_id);
			$data1['city'] = get_field('city','user_'.$user_id);
			$data1['pincode'] = get_field('pincode','user_'.$user_id);
			
			$status = 1;
			$data['status'] = ($status > 1) ? 'failed' : 'success';
			$data['message'] = "User created successfully";
			$data['user_data'] = $data1;
			echo json_encode($data);
			die;
		break;
		case "social_login":
		
		default:
		{
			$data['data'] = 'No Service Found';
			$data['message'] = $_REQUEST['Service'];
			echo json_encode($data);
			die;
		}
		break;
	}
} catch (Exception $e) {
	$status = 2;
	$data['status'] = ($status>1)? "fail" : "success";
	$data['message'] = "Check network connection and try again...";
	echo json_encode($data);
	die;
}
