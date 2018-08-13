<?php 
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