<?php
require_once('config.php');
//create users
require_once('functions.php');
try {
	
	switch ($_REQUEST['Service'])
	{
		case "login":
		
		if(isset($_POST['mobile_no']) && isset($_POST['password']) && isset($_POST['device_id']) && isset($_POST['device_type']))
		{
			
			$username = $_POST['mobile_no'];
			$password = $_POST['password'];
			$device_id = $_POST['device_id'];
			$device_type = $_POST['device_type'];
			
			$username = sanitize_user($username);
			$username = str_replace("+","",$username);
			$username = $username;
			$password = trim($password);
			
			$user = get_user_by( 'login', $username );
			
			if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID) ){
				
				$user_id = $user->ID;
				if(in_array("service-provider",$user->roles)){
				}else{
					$status = 2;
						$data['status'] = ($status>1)? "fail" : "success";
						$data['message'] = "User not exists.";
						echo json_encode($data);
						die;
				}
				if ( get_field('status','user_'.$user_id) != "approved" ) {
					if(get_field('status','user_'.$user_id)== "pending" ){
						$status = 2;
						$data['status'] = ($status>1)? "fail" : "success";
						$data['message'] = "User is not activated.";
						echo json_encode($data);
						die;
					}else{
						$status = 2;
						$data['status'] = ($status>1)? "fail" : "success";
						$data['message'] = "User blocked by authority.";
						echo json_encode($data);
						die;
					}
					
					
				}

				$user_id = $user->ID;
				
				//echo $user_id;
				
				// $user_info = get_userdata($user_id);
					// //print_r($user_info);
					
					
					$userdata = array();
					$userdata['user_id'] = $user_id;
					$userdata['user_name'] = $user->first_name;
					$userdata['email'] = $user->user_email;
					$userdata['mobile_no'] = get_field('mobile_no','user_'.$user_id);
					$userdata['profile_picture'] = get_cupp_meta( $user->ID, 'thumbnail' );
					$userdata['address'] = get_field('address','user_'.$user_id);
					$userdata['city'] = get_field('city','user_'.$user_id);
					$userdata['pincode'] = get_field('pincode','user_'.$user_id);
					
					
				
				
					// $results = $wpdb->get_results('select * from '.$wpdb->prefix.'reguser WHERE user_id ='.$user_id);
					
					// if(count($results) > 0){
						// $wpdb->update( 
							// $wpdb->prefix.'reguser',  
							// array( 
								// 'device_id' => $device_id,
								// 'device_type' => $device_type
							// ), 
							// array( 'user_id' => $user_id ), 
							// array(
								// '%s',
								// '%d'
							// ), 
							// array( '%d' ) 
						// );	
					
					// }else{
						// $wpdb->insert( 
							// $wpdb->prefix.'reguser', 
							// array( 
								// 'user_id' => $user_id,
								// 'device_id' => $device_id,
								// 'device_type' => $device_type
							// ), 
							// array( 
								// '%d',
								// '%s',
								// '%d'
							// )
						// );
					// }
				
					
					$status = 1;
					$data['status'] = ($status > 1) ? 'failed' : 'success';
					$data['message'] = "Sign in sucessfully";
					$data['user_data'] = $userdata;
					echo json_encode($data);
					die;			
				
			}else{
				$status = 2;
				$data['status'] = ($status>1)? "fail" : "success";
				$data['message'] = "Invalid mobile no or password.";
				echo json_encode($data);
				die;
			}
			
		}else{
			$status = 2;
			$data['status'] = ($status>1)? "fail" : "success";
			$data['message'] = "Please provide valid details";
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
		
			
		
		
			$username = $_POST['mobile_no'];
			$password = 'user@123';
			$device_id = $_POST['device_id'];
			$device_type = $_POST['device_type'];

			

			$user_email = $_POST['email'];
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$device_id = $_POST['deviceid'];
			$device_type = $_POST['devicetype'];

			if ( email_exists($user_email) == false ) {

				$user_id = wp_create_user( $user_email, $password, $user_email );
				
				
									$userdata =	array(
										'ID'         => $user_id,
										'first_name' => $first_name,
										'last_name'  => $last_name,
										//'phone'      => $mobile_number,
										//'user_url'    =>  $website,
										'role' => 'regular'
										//'url '  => $website
										//'nickname' => $nickname
										//'display_name' => $display_name
									);
				
									wp_update_user($userdata);
				
				
									add_user_meta( $user_id, 'billing_first_name', $first_name);
									add_user_meta( $user_id, 'billing_last_name', $last_name);
									add_user_meta( $user_id, 'billing_email', $user_email);

				$status = 1;
				$data['status'] = ($status>1)? "fail" : "success";
				$data['id'] = $user_id;
				$data['user_email'] = $user_email;
				$data['first_name'] = $first_name;
				$data['last_name'] = $last_name;

			}else {

				$status = 1;
				$data['status'] = ($status>1)? "fail" : "success";

				$user = get_user_by( 'email', $user_email );
				$data['id'] = $user->ID;
				$data['user_email'] = $user->user_email;
				$data['first_name'] = get_user_meta($user->ID,'billing_first_name',true);
				$data['last_name'] = get_user_meta($user->ID,'billing_last_name',true);

			}


			global $wpdb; 
			
			$results = $wpdb->get_results("SELECT * from ".$wpdb->prefix."reguser where device_id='".$device_id."' and device_type=".$device_type);
				
			//echo "SELECT * from ".$wpdb->prefix."reguser where device_id='".$device_id."' and device_type=".$device_type;
				
			if( count($results) > 0){
				$wpdb->update( 
					$wpdb->prefix.'reguser',
					array( 
						'user_id' => $user_id
					), 
					array( 'device_id' => $device_id ), 
					array( 
						'%d'
					), 
					array( '%s' ) 
				);
			}else{
				$wpdb->insert( 
					$wpdb->prefix.'reguser',
					array( 
						'user_id' => $user_id,
						'device_id' => $device_id,
						'device_type' => $device_type,
					), 
					array( 
						'%d',
						'%s',
						'%s'
					)
				);
			}
			

			echo json_encode($data);
		
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
