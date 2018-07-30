<?php
require_once('config.php');
//create users
require_once('functions.php');
try {
	
	switch ($_REQUEST['Service'])
	{
		case "send_otp":
		
		if(isset($_POST['mobile_no']))
		{
			$username = $_POST['mobile_no'];
			$email = "vishal.gandhi71.86@gmail.com";
			
			$username = sanitize_user($username);
			// if($username);
			$username = "91".$username;
			$password = trim($password);
			$user = get_user_by( 'login', $username );
			
			if ( $user ){
				$user_id = $user->ID;
				if(in_array("service-provider",$user->roles)){
					
					$pincode = '1234';
			
						$to = $email;
						$subject = 'Service Provider App - Please verify your account';
						$body = "Hi, $user_name<br>";
						$body .= 'Please verify your account for change password by using below verification code.';
						$body .= "<br><br><br>$pincode<br><br><br>If you have any problems, please contact us.";
						$body .= '<br>Thanks,';
						$body .= '<br>Service Provider app';


						//$body .= 'Click Here for activate your account : <a href="'.$activation_link.'">Activate Now</a> or cppy & pest below link to new browser tab for activate your account : <br>'.$activation_link;
						$headers[] = 'From: Service Provider app <test@orbrixtechnologies.com>';
						$headers[] = 'Content-Type: text/html; charset=UTF-8';
						wp_mail( $to, $subject, $body, $headers );
					
					
					$status = 1;
						$data['status'] = ($status > 1) ? 'failed' : 'success';
						$data['pincode'] = $pincode;
						$data['user_id'] = $user_id;
						echo json_encode($data);
						die;
					
					
				}else{
					$status = 2;
						$data['status'] = ($status>1)? "fail" : "success";
						$data['message'] = "User not exists.";
						echo json_encode($data);
						die;
				}
				
			}else{
				$status = 2;
				$data['status'] = ($status>1)? "fail" : "success";
				$data['message'] = "Invalid mobile no.";
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
			$email = "vishal.gandhi71.86@gmail.com";
			$nicename = $user->display_name;

			// $email = $_REQUEST['email'];
			// //$mobile_no = $_REQUEST['mobile_no'];
			// $user_name = $_REQUEST['user_name'];
			$pincode = '5678';
			
						$to = $email;
						$subject = 'Service Provider App - Please verify your account';
						$body = "Hi, $nicename<br>";
						$body .= 'Please verify your account for change password by using below verification code.';
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
		case "change_forgot_password":
			if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!="")
				{
					$user_id = $_REQUEST['user_id'];
					// $user = get_user_by( 'id', $user_id );
					// $email = $user->user_email;
					$pass = $_POST['newpassword'];

					// if ( email_exists($email) == true ) {

						// $user_id = email_exists($email);

						wp_set_password( $pass, $user_id );

						$status = 1;
						$data['status'] = ($status > 1) ? 'failed' : 'success';
						$errorMsg="Password changed successfully";
						$data['message'] = $errorMsg;

						// }else{
							// $status = 2;
							// $data['status'] = ($status > 1) ? 'failed' : 'success';
							// $errorMsg="Enter valid email";
							// $data['message'] = $errorMsg;
						// }


				}else{
					$status = 2;
					$data['status'] = ($status > 1) ? 'failed' : 'success';
					$errorMsg="Access Denied!";
					$data['message'] = $errorMsg;
				}

		echo json_encode($data);
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
