<?php
require_once('config.php');
//create users
require_once('functions.php');
require_once( ABSPATH . '/wp-admin/includes/taxonomy.php');
try {
	
	setuser();
	
	switch ($_REQUEST['Service'])
	{
		case "create_service":
		
			$user_id = $_POST['user_id'];
			
			$tag_data = "";
			
			$images = array();
			
			//$image_counter = 0;
			$image_id = $_POST['image_add'];
			// do{
				// $images[] = $_POST['image_'.$image_counter];
				// $image_counter++;
			// }while(isset($_POST['image_'.$image_counter]));
			
			$features = array();
			$feature_counter = 0;
			do{
				$features[] = $_POST['feature_'.$feature_counter];
				$feature_counter++;
			}while(isset($_POST['feature_'.$feature_counter]));
			
			$service_title = $_POST['service_title'];
			$tag_data .= $service_title;
			$tag_line = $_POST['tag_line'];
			$tag_data .= " ".$tag_line;
			//categories not display proper
			$main_category = $_POST['main_category'];
			$sub_category = $_POST['sub_category'];	

			$tag_data .= " ".$main_category." ".$sub_category;			
			
			if($main_category == 0){
				
				$main_category_name = $_POST['main_category_name'];
				$cat_defaults = array(
				  'cat_name' => $main_category_name,
				  'category_description' => '',
				  'category_nicename' => '',
				  'category_parent' => '11',
				  'taxonomy' => 'category' );
				  
				  $main_category = wp_insert_category($cat_defaults);
				  
			}
			if($sub_category == 0){
				
				$sub_category_name = $_POST['sub_category_name'];
				$cat_defaults = array(
				  'cat_name' => $sub_category_name,
				  'category_description' => 'Main Category : '.$main_category,
				  'category_nicename' => '',
				  'category_parent' => $main_category,
				  'taxonomy' => 'category' );
				  
				  $sub_category = wp_insert_category($cat_defaults);
			}
			
			
			
			$sort_description = $_POST['sort_description'];
			$tag_data .= " ".$sort_description;
			$opening_hours = $_POST['opening_hours'];
			$closing_hours = $_POST['closing_hours'];
			$mrp_price = $_POST['mrp_price'];
			$sale_price = $_POST['sale_price'];
			$payment_option = $_POST['payment_option'];
			$payment_type = $_POST['payment_type'];
			
			
			$vendor_name = $_POST['vendor_name'];
			$vendor_apartment = $_POST['vendor_apartment'];
			$tag_data .= " ".$vendor_apartment;
			$vendor_latitude = $_POST['vendor_latitude'];
			$vendor_longitude = $_POST['vendor_longitude'];
			$vendor_mobile_no = $_POST['vendor_mobile_no'];
			$vendor_email = $_POST['vendor_email'];
			$vendor_address = $_POST['vendor_address'];
			$tag_data .= " ".$vendor_address;
			$vendor_city = $_POST['vendor_city'];
			$tag_data .= " ".$vendor_city;
			$vendor_pincode = $_POST['vendor_pincode'];
			
			
			$new_post = array(
				'post_title' => $service_title,
				'post_content' => $sort_description,
				'post_status' => 'pending',
				'post_date' => date('Y-m-d H:i:s'),
				'post_author' => $user_id,
				'post_type' => 'services',
				'post_category' => array($main_category,$sub_category)
			);
			
			$post_id = wp_insert_post($new_post);			
			update_field('field_5b4492c0a9807', $tag_line, $post_id);
			

			$slug = wp_unique_post_slug( sanitize_title( $service_title.' '.$post_id ,$post_id), $post_id, 'pending', 'services', 0 );
			
			
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$upload_dir = wp_upload_dir();
			$user_dirname = $upload_dir['basedir'].'/temp_images/';
			define('UPLOAD_DIR', $user_dirname);
			
			$image_counter = 0;	
			$attachments_array = array();
			
			$image_ids = explode(",",$image_id);
			
			foreach($image_ids as $attach_id){
				if($image_counter == 0){
					set_post_thumbnail( $post_id, $attach_id );
				}else{
					$attachments_array[] = $attach_id;
				}
				$image_counter++;
			}
			
			// foreach($images as $image){
				// $user_avtar = $image;
				// $user_avtar = str_replace('data:image/png;base64,', '', $user_avtar);
				// $user_avtar = str_replace(' ', '+', $user_avtar);
				// $dataimage = base64_decode($user_avtar);
				
				
				// $file = UPLOAD_DIR . $slug.'_'.$image_counter.'.png';
				// if (file_exists($file)) {
						// unlink($file);
					// }
				// $filename = $slug.'_'.$image_counter.'.png';

				// if(wp_mkdir_p($upload_dir['path']))
					// $file = $upload_dir['path'] . '/' . $filename;
				// else
					// $file = $upload_dir['basedir'] . '/' . $filename;
				
				// file_put_contents($file, $dataimage);
				// $wp_filetype = wp_check_filetype($filename, null );
				// $attachment = array(
					// 'post_mime_type' => $wp_filetype['type'],
					// 'post_title' => sanitize_file_name($filename),
					// 'post_content' => '',
					// 'post_status' => 'inherit'
				// );
				// $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
				
				// $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				// wp_update_attachment_metadata( $attach_id, $attach_data );
				// if($image_counter == 0){
					// set_post_thumbnail( $post_id, $attach_id );
				// }else{
					// $attachments_array[] = $attach_id;
				// }
				// $image_counter++;
			// }
			
			if(!empty($attachments_array)){
				update_field( 'field_5b45c50f675d3', $attachments_array , $post_id );
			}
			
			//code for remove immages user uploaded by mistake
			$image_id = $_POST['image_remove'];
			$image_ids = explode(",",$image_id);

			foreach($image_ids as $attach_id){
				wp_delete_attachment( $attach_id, false );
			}
			
			foreach($features as $feature){
				$row = array(
					'field_5b45cc3eb288f'	=> $feature,
				);
				add_row( 'field_5b45cc0bb288e', $row, $post_id );
			}
			
			//update group Contact Details field in service
			$row = array(
				'field_5b45c688675d5'	=> $opening_hours,
				'field_5b45c6ca675d6'	=> $closing_hours,
				'field_5b45c899bd6f4'	=> $mrp_price,
				'field_5b45c8c2bd6f5'	=> $sale_price,
				'field_5b45c91cbd6f7'	=> $payment_option,
			);
			
			if($payment_option == "Recursive Payment"){
				$row['field_5b45ca7422821'] = $payment_type;
			}
			update_field( 'field_5b45c5cd675d4', $row, $post_id );
			
			
			$field_name = "field_53bdd058c84a8";
			$value = array("address" => $address, "lat" => $lat, "lng" => $lng, "zoom" => $zoom);
			update_field($field_name, $value, $this_ID);
			
			//update group Contact Details field in service
			$row = array(
				'field_5b45ce3d73f75'	=> $vendor_name,
				'field_5b45ce5673f76'	=> $vendor_apartment,
				'field_5b482299a9fdb'	=> $vendor_latitude,
				'field_5b4822aba9fdc'	=> $vendor_longitude,
				'field_5b45d29c73f78'	=> $vendor_mobile_no,
				'field_5b45d2f773f79'	=> $vendor_email,
				'field_5b45d32a73f7a'	=> $vendor_address,
				'field_5b4951d5ef235'	=> $vendor_city,
				'field_5b45d45473f7d'	=> $vendor_pincode,
				'field_5b45d1eb73f77'   => array("address" => "(".$vendor_address.", ".$vendor_city."-".$vendor_pincode.")", "lat" => $vendor_latitude, "lng" => $vendor_longitude, "zoom" => 14),
				
			);
			update_field( 'field_5b45ce1f73f74', $row, $post_id );
			
			$tags = $_POST['tags'];
			$words = extractCommonWords($tags);
			
			wp_set_post_tags( $post_id, implode(',', array_keys($words)) );
			$words = extractCommonWords($tag_data);
			wp_set_post_tags( $post_id, implode(',', array_keys($words)), true );
						
						//send mails to admins for verify newly created services
						$args = array(
							'role'         => 'administrator',
						); 
						$blogusers = get_users( $args );
						
						foreach ( $blogusers as $user ) {
							$email = $user->user_email;
							$username = $user->display_name;
							$to = $email;
							$subject = 'Service Provider App - Please verify new created service';
							$body = "Hello $username,<br>";
							$body .= 'New services created by service provider so please verify it from our backend admin-panel and live service.';
							$body .= "<br><br><br>Back-end access details : <br><a href='".get_edit_post_link($post_id)."'>click here </a><br>Username : admin<br>Password : Services@123";
							$body .= '<br>Thanks,';
							$body .= '<br>Service Provider app';


							//$body .= 'Click Here for activate your account : <a href="'.$activation_link.'">Activate Now</a> or cppy & pest below link to new browser tab for activate your account : <br>'.$activation_link;
							$headers[] = 'From: Service Provider app <test@orbrixtechnologies.com>';
							$headers[] = 'Content-Type: text/html; charset=UTF-8';
							wp_mail( $to, $subject, $body, $headers );
						}
						
						
			$status = 1;
			$data['status'] = ($status > 1) ? 'failed' : 'success';
			$data['message'] = "Service created successfully";
			echo json_encode($data);
			die;
		break;
		case "upload_images":
		
			$user_id = $_POST['user_id'];
			
			$productdata = array();
			$images = array();
			
			$image_counter = 0;
			do{
				$images[] = $_POST['image_'.$image_counter];
				$image_counter++;
			}while(isset($_POST['image_'.$image_counter]));
			
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$upload_dir = wp_upload_dir();
			$user_dirname = $upload_dir['basedir'].'/temp_images/';
			define('UPLOAD_DIR', $user_dirname);
			
			$image_counter = 0;	
			$attachments_array = array();
			
			foreach($images as $image){
				$t=time();
				$slug = $t."_".$user_id;
				$user_avtar = $image;
				$user_avtar = str_replace('data:image/png;base64,', '', $user_avtar);
				$user_avtar = str_replace(' ', '+', $user_avtar);
				$dataimage = base64_decode($user_avtar);
				
				
				$file = UPLOAD_DIR . $slug.'_'.$image_counter.'.png';
				if (file_exists($file)) {
						unlink($file);
					}
				$filename = $slug.'_'.$image_counter.'.png';

				if(wp_mkdir_p($upload_dir['path']))
					$file = $upload_dir['path'] . '/' . $filename;
				else
					$file = $upload_dir['basedir'] . '/' . $filename;
				
				file_put_contents($file, $dataimage);
				$wp_filetype = wp_check_filetype($filename, null );
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => sanitize_file_name($filename),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $file );
				
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				$attachments_array[] = $attach_id;
				$image_counter++;
			}
			
			
			
			$image_array = array();			
			foreach($attachments_array as $attach_id){

				$image_data = wp_get_attachment_image_src($attach_id, 'large');

				if (!empty($image_data)) {
					$data_image["image_id"] = $attach_id;
					$data_image["image"] = $image_data[0];

					$image_small = wp_get_attachment_image_src($attach_id, 'product-thumb');
					$data_image["image_small"] = $image_small[0];

					$image_medium = wp_get_attachment_image_src($attach_id, 'product-midum');
					$data_image["image_medium"] = $image_medium[0];
				}
				$image_array[] = $data_image;
			}
			
			$productdata['gallery_images'] = $image_array;
			
			echo json_encode($productdata);
            die;
			
		break;
		case "update_service":
		
			$user_id = $_POST['user_id'];
			$post_id = $_POST['service_id'];
			
			$tag_data = "";
			
			$images = array();
			
			//$image_counter = 0;
			$image_id = $_POST['image_add'];
			// do{
				// $images[] = $_POST['image_'.$image_counter];
				// $image_counter++;
			// }while(isset($_POST['image_'.$image_counter]));
			
			$features = array();
			$feature_counter = 0;
			do{
				$features[] = $_POST['feature_'.$feature_counter];
				$feature_counter++;
			}while(isset($_POST['feature_'.$feature_counter]));
			
			$service_title = $_POST['service_title'];
			$tag_data .= $service_title;
			$tag_line = $_POST['tag_line'];
			$tag_data .= " ".$tag_line;
			//categories not display proper
			$main_category = $_POST['main_category'];
			$sub_category = $_POST['sub_category'];	

			$tag_data .= " ".$main_category." ".$sub_category;			
			
			if($main_category == 0){
				
				$main_category_name = $_POST['main_category_name'];
				$cat_defaults = array(
				  'cat_name' => $main_category_name,
				  'category_description' => '',
				  'category_nicename' => '',
				  'category_parent' => '11',
				  'taxonomy' => 'category' );
				  
				  $main_category = wp_insert_category($cat_defaults);
				  
			}
			if($sub_category == 0){
				
				$sub_category_name = $_POST['sub_category_name'];
				$cat_defaults = array(
				  'cat_name' => $sub_category_name,
				  'category_description' => 'Main Category : '.$main_category,
				  'category_nicename' => '',
				  'category_parent' => $main_category,
				  'taxonomy' => 'category' );
				  
				  $sub_category = wp_insert_category($cat_defaults);
			}
			
			
			
			$sort_description = $_POST['sort_description'];
			$tag_data .= " ".$sort_description;
			$opening_hours = $_POST['opening_hours'];
			$closing_hours = $_POST['closing_hours'];
			$mrp_price = $_POST['mrp_price'];
			$sale_price = $_POST['sale_price'];
			$payment_option = $_POST['payment_option'];
			$payment_type = $_POST['payment_type'];
			
			
			$vendor_name = $_POST['vendor_name'];
			$vendor_apartment = $_POST['vendor_apartment'];
			$tag_data .= " ".$vendor_apartment;
			$vendor_latitude = $_POST['vendor_latitude'];
			$vendor_longitude = $_POST['vendor_longitude'];
			$vendor_mobile_no = $_POST['vendor_mobile_no'];
			$vendor_email = $_POST['vendor_email'];
			$vendor_address = $_POST['vendor_address'];
			$tag_data .= " ".$vendor_address;
			$vendor_city = $_POST['vendor_city'];
			$tag_data .= " ".$vendor_city;
			$vendor_pincode = $_POST['vendor_pincode'];
			
			
			$my_post = array(
				'ID' => $post_id,
				'post_title' => $service_title,
				'post_content' => $sort_description,
				'post_author' => $user_id,
				'post_type' => 'services',
				'post_category' => array($main_category,$sub_category)
			);
			
			wp_update_post( $my_post );
			
			update_field('field_5b4492c0a9807', $tag_line, $post_id);
			

			$slug = wp_unique_post_slug( sanitize_title( $service_title.' '.$post_id ,$post_id), $post_id, 'pending', 'services', 0 );
			
			
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$upload_dir = wp_upload_dir();
			$user_dirname = $upload_dir['basedir'].'/temp_images/';
			define('UPLOAD_DIR', $user_dirname);
			
			$image_counter = 0;	
			$attachments_array = array();
			
			$image_ids = explode(",",$image_id);
			
			foreach($image_ids as $attach_id){
				if($image_counter == 0){
					set_post_thumbnail( $post_id, $attach_id );
				}else{
					$attachments_array[] = $attach_id;
				}
				$image_counter++;
			}
			
			// foreach($images as $image){
				// $user_avtar = $image;
				// $user_avtar = str_replace('data:image/png;base64,', '', $user_avtar);
				// $user_avtar = str_replace(' ', '+', $user_avtar);
				// $dataimage = base64_decode($user_avtar);
				
				
				// $file = UPLOAD_DIR . $slug.'_'.$image_counter.'.png';
				// if (file_exists($file)) {
						// unlink($file);
					// }
				// $filename = $slug.'_'.$image_counter.'.png';

				// if(wp_mkdir_p($upload_dir['path']))
					// $file = $upload_dir['path'] . '/' . $filename;
				// else
					// $file = $upload_dir['basedir'] . '/' . $filename;
				
				// file_put_contents($file, $dataimage);
				// $wp_filetype = wp_check_filetype($filename, null );
				// $attachment = array(
					// 'post_mime_type' => $wp_filetype['type'],
					// 'post_title' => sanitize_file_name($filename),
					// 'post_content' => '',
					// 'post_status' => 'inherit'
				// );
				// $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
				
				// $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				// wp_update_attachment_metadata( $attach_id, $attach_data );
				// if($image_counter == 0){
					// set_post_thumbnail( $post_id, $attach_id );
				// }else{
					// $attachments_array[] = $attach_id;
				// }
				// $image_counter++;
			// }
			
			if(!empty($attachments_array)){
				update_field( 'field_5b45c50f675d3', $attachments_array , $post_id );
			}
			
			//code for remove immages user uploaded by mistake
			$image_id = $_POST['image_remove'];
			$image_ids = explode(",",$image_id);

			foreach($image_ids as $attach_id){
				wp_delete_attachment( $attach_id, false );
			}
			
			foreach($features as $feature){
				$row = array(
					'field_5b45cc3eb288f'	=> $feature,
				);
				add_row( 'field_5b45cc0bb288e', $row, $post_id );
			}
			
			//update group Contact Details field in service
			$row = array(
				'field_5b45c688675d5'	=> $opening_hours,
				'field_5b45c6ca675d6'	=> $closing_hours,
				'field_5b45c899bd6f4'	=> $mrp_price,
				'field_5b45c8c2bd6f5'	=> $sale_price,
				'field_5b45c91cbd6f7'	=> $payment_option,
			);
			
			if($payment_option == "Recursive Payment"){
				$row['field_5b45ca7422821'] = $payment_type;
			}
			update_field( 'field_5b45c5cd675d4', $row, $post_id );
			
			
			$field_name = "field_53bdd058c84a8";
			$value = array("address" => $address, "lat" => $lat, "lng" => $lng, "zoom" => $zoom);
			update_field($field_name, $value, $this_ID);
			
			//update group Contact Details field in service
			$row = array(
				'field_5b45ce3d73f75'	=> $vendor_name,
				'field_5b45ce5673f76'	=> $vendor_apartment,
				'field_5b482299a9fdb'	=> $vendor_latitude,
				'field_5b4822aba9fdc'	=> $vendor_longitude,
				'field_5b45d29c73f78'	=> $vendor_mobile_no,
				'field_5b45d2f773f79'	=> $vendor_email,
				'field_5b45d32a73f7a'	=> $vendor_address,
				'field_5b4951d5ef235'	=> $vendor_city,
				'field_5b45d45473f7d'	=> $vendor_pincode,
				'field_5b45d1eb73f77'   => array("address" => "(".$vendor_address.", ".$vendor_city."-".$vendor_pincode.")", "lat" => $vendor_latitude, "lng" => $vendor_longitude, "zoom" => 14),
				
			);
			update_field( 'field_5b45ce1f73f74', $row, $post_id );
			
			$tags = $_POST['tags'];
			$words = extractCommonWords($tags);
			
			wp_set_post_tags( $post_id, implode(',', array_keys($words)) );
			$words = extractCommonWords($tag_data);
			wp_set_post_tags( $post_id, implode(',', array_keys($words)), true );
						
						//send mails to admins for verify newly created services
						$args = array(
							'role'         => 'administrator',
						); 
						$blogusers = get_users( $args );
						
						foreach ( $blogusers as $user ) {
							$email = $user->user_email;
							$username = $user->display_name;
							$to = $email;
							$subject = 'Service Provider App - Please verify updated data of service';
							$body = "Hello $username,<br>";
							$body .= 'Service data updated by service provider so please verify it from our backend admin-panel and approve service.';
							$body .= "<br><br><br>Back-end access details : <br><a href='".get_edit_post_link($post_id)."'>click here </a><br><br>Or<br><br>Copy below link to your browser:<br>".get_edit_post_link($post_id)."<br>Username : admin<br>Password : Services@123";
							$body .= '<br>Thanks,';
							$body .= '<br>Service Provider app';


							//$body .= 'Click Here for activate your account : <a href="'.$activation_link.'">Activate Now</a> or cppy & pest below link to new browser tab for activate your account : <br>'.$activation_link;
							$headers[] = 'From: Service Provider app <test@orbrixtechnologies.com>';
							$headers[] = 'Content-Type: text/html; charset=UTF-8';
							wp_mail( $to, $subject, $body, $headers );
						}
						
						
			$status = 1;
			$data['status'] = ($status > 1) ? 'failed' : 'success';
			$data['message'] = "Service created successfully";
			echo json_encode($data);
			die;
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
