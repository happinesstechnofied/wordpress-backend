<?php
//error_reporting(E_ALL);
ini_set('display_errors', 1);
	define('WP_USE_THEMES', false);
	//require('../wp-blog-header.php');

	require('../../../wp-config.php');
	$wp->init();
	$wp->parse_request();
	$wp->query_posts();
	$wp->register_globals();

//create users
require_once('functions.php');
try {

//if from user from social then cumplsary password is Admin@123
	if(isset($_POST['email']) && isset($_POST['full_name']) && isset($_POST['password']) && isset($_POST['device_id']) && isset($_POST['device_type']) )
	{
		global $wpdb;
		
		$user_email = $_POST['email'];
		$full_name = $_POST['full_name'];
		$password = $_POST['password'];
		$device_id = $_POST['device_id'];
		$device_type = $_POST['device_type'];
		
		$mobile_no = "";
		if(isset($_POST['mobile_no']) && $_POST['mobile_no'] != ""){
			$mobile_no = $_POST['mobile_no'];
		}
		
		$upload_dir = wp_upload_dir();
		$user_dirname = $upload_dir['basedir'].'/profiles/';
		define('UPLOAD_DIR', $user_dirname);
		
		$user_avtar = $_POST['user_avtar'];
			
		$user_avtar = str_replace('data:image/png;base64,', '', $user_avtar);
		$user_avtar = str_replace(' ', '+', $user_avtar);
		$dataimage = base64_decode($user_avtar);
		
		//$fullnamestring = exclude(" ",$fullname);
		if(isset($_POST['social']) && $_POST['social'] == 1){
				$user_avtar = $_POST['user_avtar'];
				$status = 1;
				$data['status'] = ($status>1)? "fail" : "success";
				
				
				if( email_exists($user_email) == false ){
					
					$default_newuser = array(
						'user_pass' =>  $password,
						'user_login' => $user_email,
						'user_email' => $user_email,
						'nickname' => $full_name,
						'display_name' => $full_name,
						'role' => 'subscriber'
					);
					
					$user_id = wp_insert_user($default_newuser);
					
					update_usermeta( $user_id, 'phone', $mobile_no );

					$wpdb->insert( 
						$wpdb->prefix.'reguser', 
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
					
					$file = UPLOAD_DIR . $user_id . '.png';
				
					//$success = file_put_contents($file, $dataimage);
					if (file_exists($file)) {
						unlink($file);
					}
					
					
					download_image1($user_avtar, UPLOAD_DIR . $user_id . '.png');
					
					//$data['message'] = "Successfully registered with cicx";
					
					$user_id = email_exists($user_email);
					$user = get_user_by( 'login', $user_email );
					
					$userdata = array();
					$userdata['user_id'] = $user_id;
					$userdata['full_name'] = $user->display_name;
					$userdata['email'] = $user->user_email;
					
					$upload_dir = wp_upload_dir();
					$upload_dir_url = $upload_dir['baseurl'] .'/profiles/'.$user_id.'.png';
					
					$userdata['user_avtar'] = $upload_dir_url;
					$userdata['cricx_coins'] = mycred_get_users_cred( $user_id );
				
					
					$wpdb->update( 
							$wpdb->prefix.'reguser',
							array( 
								'device_id' => $device_id,
								'device_type' => $device_type
							), 
							array( 'user_id' => $user_id ), 
							array(
								'%s',
								'%d'
							), 
							array( '%d' ) 
						);
					
					$data['message'] = $userdata;
					
				}else{
					
					$user_avtar = $_POST['user_avtar'];
					
					$user_id = email_exists($user_email);
					$user = get_user_by( 'login', $user_email );
					
					$userdata = array();
					$userdata['user_id'] = $user_id;
					$userdata['full_name'] = $user->display_name;
					$userdata['email'] = $user->user_email;
					
					if (file_exists(UPLOAD_DIR . $user_id . '.png')) {
						unlink(UPLOAD_DIR . $user_id . '.png');
					}
					
					download_image1($user_avtar, UPLOAD_DIR . $user_id . '.png');
					
					$upload_dir = wp_upload_dir();
					$upload_dir_url = $upload_dir['baseurl'] .'/profiles/'.$user_id.'.png';
					
					$userdata['user_avtar'] = $upload_dir_url;
					$userdata['cricx_coins'] = mycred_get_users_cred( $user_id );
				
				
					
					$results = $wpdb->get_results('select * from '.$wpdb->prefix.'reguser WHERE device_id ='.$device_id);
				
					if(count($results) > 0){
						$wpdb->update( 
							$wpdb->prefix.'reguser',  
							array( 
								'user_id' => $user_id,
								'device_type' => $device_type
							), 
							array( 'device_id' => $device_id ), 
							array(
								'%d',
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
								'device_type' => $device_type
							), 
							array( 
								'%d',
								'%s',
								'%d'
							)
						);
					}
					
					$data['message'] = $userdata;
					
				}				
				
				echo json_encode($data);
				die;
				
			}else{
				if ( email_exists($user_email) == false && !empty($password) ) {
			//$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
				
				
					$default_newuser = array(
						'user_pass' =>  $password,
						'user_login' => $user_email,
						'user_email' => $user_email,
						'nickname' => $full_name,
						'display_name' => $full_name,
						'role' => 'pending'
					);
					
					
					$user_id = wp_insert_user($default_newuser);
					
					
					$wpdb->insert( 
						$wpdb->prefix.'reguser', 
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
					
					$file = UPLOAD_DIR . $user_id . '.png';
					
					$success = file_put_contents($file, $dataimage);
					
					
					if ( $user_id && !is_wp_error( $user_id ) ) {
						$code = sha1( $user_id . time() );
						$activation_link = add_query_arg( array( 'key' => $code, 'user' => $user_id ), get_permalink( 4 ));
						add_user_meta( $user_id, 'has_to_be_activated', $code, true );
						
							$message = '<table width="100%" cellpadding="0" cellspacing="0">
								<tbody><tr>
									<td valign="top" align="left">

										<table align="center" cellpadding="0" cellspacing="0">
										<tbody><tr>
											<td>

												<table width="700" align="center" cellpadding="0" cellspacing="0" class="" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);transition:0.3s">
												<tbody><tr>
													<td style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition:0.3s">
														
														<table bgcolor="#2d2d2e" width="700" cellpadding="0" cellspacing="0" class="m_-260622093734470207m_-2335270447192522997wMax">
														<tbody><tr>
															<td align="center" style="height:345px;background:url(http://orbrixtechnologies.com/cricx/wp-content/uploads/mailtemplates/Banner.png) top/auto 100%;background-position:top center;background-repeat:no-repeat;" class="">
															</td>
														</tr>
														</tbody></table>		

														<table bgcolor="#ffffff" width="700" cellpadding="0" cellspacing="0" class="m_-260622093734470207m_-2335270447192522997wMax">
														<tbody><tr>
															<td height="760" valign="top" style="padding-top:50px;padding-bottom:30px;padding-left:20px;padding-right:20px" class="m_-260622093734470207m_-2335270447192522997autoHeight">
																<table width="550" align="center" cellpadding="0" cellspacing="0" class="m_-260622093734470207m_-2335270447192522997wMax">
																<tbody><tr>
																	<td style="font-family:Roboto Slab,Arial,Helvetica,sans-serif;font-size:20px;color:#333333;line-height:30px;padding-bottom:35px">											 
																		Welcome to CricxCafe! <br><br>
																		CricxCafe is the world’s largest Fantasy Cricket game of skill! Fantasy Cricket is all about using your cricket knowledge and skill to create your own table on upcoming league matches team within a budget of available coins in virtual money.!<br><br>
																		Benefits of verifying email address<br><br>
																		1. You get 100 Coins instantly into your account.<br>
																		2. Never miss out on important notifications regarding your account with cricxcafe.<br>
																		3. In case you forget your password, you can retrieve your password anytime.<br><br>
																		Follow this link to get started:
																	</td>
																</tr>
																<tr>
																	<td>
																		<table bgcolor="#3599e7" align="center" cellpadding="0" cellspacing="0">
																		<tbody><tr>
																			<td align="center" style="font-family:Roboto Slab,Arial,Helvetica,sans-serif;font-size:20px;color:#ffffff">
																				<a href="'.$activation_link.'" style="color:#ffffff;text-decoration:none;display:block;border-top:8px solid #3599e7;border-bottom:8px solid #3599e7;border-left:20px solid #3599e7;border-right:20px solid #3599e7" target="_blank">Activate my Account</a>
																			</td>
																		</tr>
																		</tbody></table>
																	</td>
																</tr>
																<tr>
																	<td style="font-family:Roboto Slab,Arial,Helvetica,sans-serif;font-size:20px;color:#333333;line-height:30px;padding-top:35px;padding-bottom:15px;word-break:break-all">
																		If you cannot see the link, please click here:<br> <a href="'.$activation_link.'" style="color:#3599e7;text-decoration:underline" target="_blank" >'.$activation_link.'</a><br><br>
																		If you have any questions please contact <a href="mailto:support@orbrixtechnologies.com" style="color:#3599e7;text-decoration:underline" target="_blank">support@orbrixtechnologies.com</a> and a team member will answer you shortly.<br><br>
																		Glad to see you on board,<br><br>
																		Best Regards,<br>
																		<span style="color:#545454">The CricxCafe Team</span><br><br>
																		Note : If you didn\'t create an account using this email address, please ignore this email.
																	</td>
																</tr>
																</tbody></table>
															</td>
														</tr>
														</tbody></table>

														
														<table style="background: url(http://orbrixtechnologies.com/cricx/wp-content/uploads/mailtemplates/Banner2.png);" width="700"  height="377" cellpadding="0" cellspacing="0" class="m_-260622093734470207m_-2335270447192522997wMax">
														<tbody><tr>
															<td >
																<table width="700" cellpadding="0" cellspacing="0" class="m_-260622093734470207m_-2335270447192522997wMax">
																<tbody><tr>
																	<td valign="top" style="padding-left:10px;padding-right:10px">
																		<table width="570" align="center" cellpadding="0" cellspacing="0" class="m_-260622093734470207m_-2335270447192522997wMax">
																		<tbody><tr>
																			<td align="left" style="padding-left:10px;padding-right:10px">
																				  <a href="https://www.facebook.com/Orbrix" style="text-decoration:none" target="_blank" ><img src="http://orbrixtechnologies.com/cricx/wp-content/uploads/mailtemplates/facebook.png" width="70" height="70" alt="Facebook" border="0" style="display:block" class="m_-260622093734470207m_-2335270447192522997scale CToWUd"></a>
																			</td>
																			<td align="center" style="padding-left:10px;padding-right:10px">
																				  <a href="https://plus.google.com/u/1/114220872352422628579" style="text-decoration:none" target="_blank"><img src="http://orbrixtechnologies.com/cricx/wp-content/uploads/mailtemplates/gpluse.png" width="70" height="70" alt="Google Plus" border="0" style="display:block" class="m_-260622093734470207m_-2335270447192522997scale CToWUd"></a>
																			</td>
																			<td align="center" style="padding-left:10px;padding-right:10px">
																				  <a href="https://www.linkedin.com/in/Orbrix" style="text-decoration:none" target="_blank" ><img src="http://orbrixtechnologies.com/cricx/wp-content/uploads/mailtemplates/linkedin.png" width="70" height="70" alt="LinkedIn" border="0" style="display:block" class="m_-260622093734470207m_-2335270447192522997scale CToWUd"></a>
																			</td>
																			<td align="right" style="padding-left:10px;padding-right:10px">
																				  <a href="skype:orbrixtechnologies" style="text-decoration:none" target="_blank" ><img src="http://orbrixtechnologies.com/cricx/wp-content/uploads/mailtemplates/skype.png" width="70" height="70" alt="Twitter" border="0" style="display:block" class="m_-260622093734470207m_-2335270447192522997scale CToWUd"></a>
																			</td>
																		</tr>
																		</tbody></table>
																	</td>
																</tr>
																</tbody></table>
																
															</td>
														</tr>
														</tbody></table>

													</td>
												</tr>
												</tbody></table>

											</td>
										</tr>
										</tbody></table>

									</td>
								</tr>
								</tbody>
							</table>';

							
							$to = $user_email;
							$subject = "Activate your Cricx Cafe Account";
							$headers[]= 'Content-Type: text/html; charset=UTF-8';
							$headers[]= 'Bcc: vishal.gandhi71.86@gmail.com';

							wp_mail( $to, $subject, $message, $headers);
						
						
						
						
							
							$registrationIds = array();
							$registrationIds[] = $device_id;
							$link_image = "";
							$message = "Your account created successfully. Please check your email for account activation.";
							push_android($registrationIds,$link_image,$message);
						
						
						$status = 1;
						$data['status'] = ($status>1)? "fail" : "success";
						$data['message'] = "Please check mail for activation account";
						echo json_encode($data);
						die;
					}
				} else {
			
					$status = 2;
					$data['status'] = ($status>1)? "fail" : "success";
					$data['message'] = "User already exists.";
					echo json_encode($data);
					die;
				}
			}
		
	}else{
		$status = 2;
		$data['status'] = ($status>1)? "fail" : "success";
		$data['message'] = "Access denied";
		echo json_encode($data);
		die;
	}
} catch (Exception $e) {
	$status = 2;
	$data['status'] = ($status>1)? "fail" : "success";
	$data['message'] = "Session Expired";
	echo json_encode($data);
	die;
}
