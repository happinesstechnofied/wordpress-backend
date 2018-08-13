<?php
require_once('config.php');
//create users
require_once('functions.php');
require_once(ABSPATH . '/wp-admin/includes/taxonomy.php');
try {
    setuser();

    switch ($_REQUEST['Service']) {
        case "get_sp_service_list":

            $user_id = $_POST['user_id'];

            $args = array( 'post_type' => 'services', 'posts_per_page' => -1 ,'post_status' => array( 'pending', 'publish' ),'author' => $user_id );

            $data=array();
            query_posts($args);

            global $post;

            while (have_posts()) : the_post();

            $productdata = array();

            $postdata = $post;
            //echo "loop have posts";

            // print_r($postdata);

            $post_id=$postdata->ID;

            $productdata['service_status'] = $postdata->post_status;
            $productdata['service_id'] = $post_id;

            //echo $post_id;

            $productdata["title"] = $postdata->post_title;
            $productdata["tag_line"] = get_field('field_5b4492c0a9807', $post_id);

            $categories = wp_get_post_categories($post_id);

            foreach ($categories as $category_num) {
                $category = get_category($category_num);

                if ($category->category_parent == 0) {

                    //echo "<br>";
                    $category_id = $category->term_id;
                    //echo "<br/>".$category_id;
                    $category_name = $category->name;
                    //echo " ".$category_name.'<br>';
                    $product_count = $category->count;
                    //echo " ".$product_count.'<br>';


                    $image = get_field('image', "category_".$category_id);

                    $size = array('50','50'); // (thumbnail, medium, large, full or custom size)

                    if ($image) {
                        $image_url = wp_get_attachment_url($image['id'], $size);
                        $image_small = wp_get_attachment_url($image['id'], 'category-thumb');
                        $image_medium = wp_get_attachment_url($image['id'], 'category-midum');
                    } else {
                        $image_url = "";
                        $image_small = "";
                        $image_medium = "";
                    }

                    $productdata['parent_cat_id'] = $category_id;
                    $productdata['parent_cat_name'] = $category_name;
                    $productdata['parent_product_count'] = $product_count;
                    if (empty($image_url)) {
                        $productdata['parent_image'] = '';
                    } else {
                        $productdata['parent_image'] = $image_url;
                    }
                    $productdata['parent_image_small'] = $image_small;
                    $productdata['parent_image_medium'] = $image_medium;
                }

                if ($category->category_parent > 0) {

                    //echo "<br>";
                    $category_id = $category->term_id;
                    //echo "<br/>".$category_id;
                    $category_name = $category->name;
                    //echo " ".$category_name.'<br>';
                    $product_count = $category->count;
                    //echo " ".$product_count.'<br>';


                    $image = get_field('image', "category_".$category_id);

                    $size = array('50','50'); // (thumbnail, medium, large, full or custom size)

                    if ($image) {
                        $image_url = wp_get_attachment_url($image['id'], $size);
                        $image_small = wp_get_attachment_url($image['id'], 'category-thumb');
                        $image_medium = wp_get_attachment_url($image['id'], 'category-midum');
                    } else {
                        $image_url = "";
                        $image_small = "";
                        $image_medium = "";
                    }

                    $productdata['sub_cat_id'] = $category_id;
                    $productdata['sub_cat_name'] = $category_name;
                    $productdata['sub_product_count'] = $product_count;
                    if (empty($image_url)) {
                        $productdata['sub_image'] = '';
                    } else {
                        $productdata['sub_image'] = $image_url;
                    }
                    $productdata['sub_image_small'] = $image_small;
                    $productdata['sub_image_medium'] = $image_medium;
                }
            }

            $image_array = array();

            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large');

            //print_r($image_data);

            if (!empty($image_data)) {
                //$productdata["image"] = $feat_image = wp_get_attachment_url ( get_post_thumbnail_id ( $post_id ) );
                $data_image["image_id"] = get_post_thumbnail_id($post_id);
                $productdata["image"] = $image_data[0];
                $data_image["image"] = $image_data[0];

                $image_small = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'product-thumb');
                $productdata['image_small'] = $image_small[0];
                $data_image["image_small"] = $image_small[0];

                $image_medium = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'product-midum');
                $productdata['image_medium'] = $image_medium[0];
                $data_image["image_medium"] = $image_medium[0];
            }
            $image_array[] = $data_image;
            $images = get_field('field_5b45c50f675d3', $post_id);




            if ($images):
                foreach ($images as $image):

                //print_r($image);
                    //wp_get_attachment_image( $image['ID'], $size );
                    $data_image = array();
                    $data_image["image_id"] = $image['id'];
                    $data_image["image"] = $image['sizes']['large'];

                    $data_image["image_small"] = $image['sizes']['product-thumb'];
                    $data_image["image_medium"] = $image['sizes']['product-midum'];

                    $image_array[] = $data_image;

                endforeach;
            endif;

            $productdata['gallery_images'] = $image_array;
            $productdata["sort_description"] = $postdata->post_content;

            $group_additional = get_field('field_5b45c5cd675d4', $post_id);

            if ($group_additional):
                if ($group_additional['opening_hour']!=null) {
                    $productdata['opening_hour'] = $group_additional['opening_hour'];
                } else {
                    $productdata['opening_hour'] = "";
                }

                if ($group_additional['closing_hour']!=null) {
                    $productdata['closing_hour'] = $group_additional['closing_hour'];
                } else {
                    $productdata['closing_hour'] = "";
                }

                $productdata['maximum_retail_price'] = $group_additional['maximum_retail_price'];
                $productdata['sale_price'] = $group_additional['sale_price'];

                $productdata['payment_options'] = $group_additional['payment_options'];
                $productdata['recursive_payment'] = "";
                if ($productdata['payment_options'] == "Recursive Payment") {
                    $productdata['recursive_payment'] = $group_additional['recursive_payment'];
                }

            endif;

            $features_details = get_field('field_5b45cc0bb288e', $post_id);
            $features = array();

            if ($features_details):
                foreach ($features_details as $feature) {
                    $sub_feature = array();
                    $sub_feature['feature'] = $feature['feature_with_cost'];
                    $features[] = $sub_feature;
                }
            endif;
            $productdata['features'] = $features;




            $contact_details = get_field('field_5b45ce1f73f74', $post_id);

            if ($contact_details):
                $productdata['contact_name'] = $contact_details['name'];
                $productdata['appartment'] = $contact_details['appartment'];
                $productdata['latitude'] = $contact_details['latitude'];
                $productdata['longitude'] = $contact_details['longitude'];
                $productdata['mobile_no'] = $contact_details['mobile_no'];
                $productdata['email'] = $contact_details['email'];
                $productdata['address'] = $contact_details['address'];
                $productdata['state'] = "";
                $productdata['city'] = $contact_details['city'];
                $productdata['pincode'] = $contact_details['pincode'];

            endif;


            $tags_data = "";
            $tags = get_the_tags($post_id);
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    if ($tags_data == "") {
                        $tags_data = $tag->name;
                    } else {
                        $tags_data .= ",".$tag->name;
                    }
                }
            }
            $productdata['tags'] = $tags_data;
			
			
			$productdata['review_rattings'] = array();
			
			$ratting_counter = 0;
			$total_ratting = 0;
			if( have_rows('user_reviews_and_ratings',$post_id) ):
				while( have_rows('user_reviews_and_ratings',$post_id) ): the_row(); 
				
					$status = get_sub_field('status');
				
					if($status){
						$ratting_review = array();
						$review = get_sub_field('review');
						$rattings = get_sub_field('rattings');
						$date = get_sub_field('date');
						$review_by_user = get_sub_field('review_by_user');
					
						
						$ratting_review['user_id'] = $review_by_user['ID'];
						$ratting_review['user_name'] = $review_by_user['display_name'];
						$ratting_review['user_avatar'] = get_cupp_meta( $review_by_user['ID'], 'thumbnail' );
						$ratting_review['review'] = $review;
						$ratting_review['rattings'] = $rattings;
						$ratting_counter++;
						$total_ratting += $rattings;
						$ratting_review['date'] = $date;
						$ratting_review['status'] = $status;
						
						$productdata['review_rattings'][] = $ratting_review;
					}
				endwhile;
			endif;
			
			if($ratting_counter == 0 ){
				$productdata['total_reviews'] = $ratting_counter;
				$productdata['total_rattings'] = number_format((float)(0), 1, '.', '');
			}else{
				$productdata['total_reviews'] = $ratting_counter;
				$productdata['total_rattings'] = number_format((float)($total_ratting / $ratting_counter), 1, '.', '');
			}
			
			

            $data['services'][] = $productdata;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;

            endwhile;

            // Reset Query
            wp_reset_query();

            if (empty($data)) {
                $data['services'] = array();
            }
            if ($sort == "") {
                //shuffle($data['products']);
            }
            echo json_encode($data);

            die;

        break;
        case "get_subscriber_service_list":

            //$user_id = $_POST['user_id'];

            $args = array( 'post_type' => 'services', 'posts_per_page' => -1 ,'post_status' => array( 'pending', 'publish' ));

            $data=array();
            query_posts($args);

            global $post;

            while (have_posts()) : the_post();

            $productdata = array();

            $postdata = $post;
            //echo "loop have posts";

            // print_r($postdata);

            $post_id=$postdata->ID;

            $productdata['service_status'] = $postdata->post_status;
            $productdata['service_id'] = $post_id;

            //echo $post_id;

            $productdata["title"] = $postdata->post_title;
            $productdata["tag_line"] = get_field('field_5b4492c0a9807', $post_id);

            $categories = wp_get_post_categories($post_id);

            foreach ($categories as $category_num) {
                $category = get_category($category_num);

                if ($category->category_parent == 0) {

                    //echo "<br>";
                    $category_id = $category->term_id;
                    //echo "<br/>".$category_id;
                    $category_name = $category->name;
                    //echo " ".$category_name.'<br>';
                    $product_count = $category->count;
                    //echo " ".$product_count.'<br>';


                    $image = get_field('image', "category_".$category_id);

                    $size = array('50','50'); // (thumbnail, medium, large, full or custom size)

                    if ($image) {
                        $image_url = wp_get_attachment_url($image['id'], $size);
                        $image_small = wp_get_attachment_url($image['id'], 'category-thumb');
                        $image_medium = wp_get_attachment_url($image['id'], 'category-midum');
                    } else {
                        $image_url = "";
                        $image_small = "";
                        $image_medium = "";
                    }

                    $productdata['parent_cat_id'] = $category_id;
                    $productdata['parent_cat_name'] = $category_name;
                    $productdata['parent_product_count'] = $product_count;
                    if (empty($image_url)) {
                        $productdata['parent_image'] = '';
                    } else {
                        $productdata['parent_image'] = $image_url;
                    }
                    $productdata['parent_image_small'] = $image_small;
                    $productdata['parent_image_medium'] = $image_medium;
                }

                if ($category->category_parent > 0) {

                    //echo "<br>";
                    $category_id = $category->term_id;
                    //echo "<br/>".$category_id;
                    $category_name = $category->name;
                    //echo " ".$category_name.'<br>';
                    $product_count = $category->count;
                    //echo " ".$product_count.'<br>';


                    $image = get_field('image', "category_".$category_id);

                    $size = array('50','50'); // (thumbnail, medium, large, full or custom size)

                    if ($image) {
                        $image_url = wp_get_attachment_url($image['id'], $size);
                        $image_small = wp_get_attachment_url($image['id'], 'category-thumb');
                        $image_medium = wp_get_attachment_url($image['id'], 'category-midum');
                    } else {
                        $image_url = "";
                        $image_small = "";
                        $image_medium = "";
                    }

                    $productdata['sub_cat_id'] = $category_id;
                    $productdata['sub_cat_name'] = $category_name;
                    $productdata['sub_product_count'] = $product_count;
                    if (empty($image_url)) {
                        $productdata['sub_image'] = '';
                    } else {
                        $productdata['sub_image'] = $image_url;
                    }
                    $productdata['sub_image_small'] = $image_small;
                    $productdata['sub_image_medium'] = $image_medium;
                }
            }



            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large');

            //print_r($image_data);

            if (!empty($image_data)) {
                //$productdata["image"] = $feat_image = wp_get_attachment_url ( get_post_thumbnail_id ( $post_id ) );
                $productdata["image"] = $image_data[0];

                $image_small = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'product-thumb');
                $productdata['image_small'] = $image_small[0];

                $image_medium = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'product-midum');
                $productdata['image_medium'] = $image_medium[0];
            }

            $images = get_field('field_5b45c50f675d3', $post_id);
            $image_array = array();
            if ($images):
                foreach ($images as $image):

                //print_r($image);
                    //wp_get_attachment_image( $image['ID'], $size );
                    $data_image = array();

                    $data_image["image"] = $image['sizes']['large'];

                    $data_image["image_small"] = $image['sizes']['product-thumb'];
                    $data_image["image_medium"] = $image['sizes']['product-midum'];

                    $image_array[] = $data_image;

                endforeach;
            endif;

            $productdata['gallery_images'] = $image_array;
            $productdata["sort_description"] = $postdata->post_content;

            $group_additional = get_field('field_5b45c5cd675d4', $post_id);

            if ($group_additional):
                if ($group_additional['opening_hour']!=null) {
                    $productdata['opening_hour'] = $group_additional['opening_hour'];
                } else {
                    $productdata['opening_hour'] = "";
                }

                if ($group_additional['closing_hour']!=null) {
                    $productdata['closing_hour'] = $group_additional['closing_hour'];
                } else {
                    $productdata['closing_hour'] = "";
                }

                $productdata['maximum_retail_price'] = $group_additional['maximum_retail_price'];
                $productdata['sale_price'] = $group_additional['sale_price'];

                $productdata['payment_options'] = $group_additional['payment_options'];
                $productdata['recursive_payment'] = "";
                if ($productdata['payment_options'] == "Recursive Payment") {
                    $productdata['recursive_payment'] = $group_additional['recursive_payment'];
                }

            endif;

            $features_details = get_field('field_5b45cc0bb288e', $post_id);
            $features = array();

            if ($features_details):
                foreach ($features_details as $feature) {
                    $sub_feature = array();
                    $sub_feature['feature'] = $feature['feature_with_cost'];
                    $features[] = $sub_feature;
                }
            endif;
            $productdata['features'] = $features;




            $contact_details = get_field('field_5b45ce1f73f74', $post_id);

            if ($contact_details):
                $productdata['contact_name'] = $contact_details['name'];
                $productdata['appartment'] = $contact_details['appartment'];
                $productdata['latitude'] = $contact_details['latitude'];
                $productdata['longitude'] = $contact_details['longitude'];
                $productdata['mobile_no'] = $contact_details['mobile_no'];
                $productdata['email'] = $contact_details['email'];
                $productdata['address'] = $contact_details['address'];
                $productdata['state'] = "";
                $productdata['city'] = $contact_details['city'];
                $productdata['pincode'] = $contact_details['pincode'];

            endif;



            $data['services'][] = $productdata;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;
            // $data['service_id'] = $post_id;

            endwhile;

            shuffle($data['services']);
            // Reset Query
            wp_reset_query();

            if (empty($data)) {
                $data['services'] = array();
            }
            if ($sort == "") {
                //shuffle($data['products']);
            }
            echo json_encode($data);

            die;

        break;


        case "send_otp":
            if (isset($_REQUEST['user_name']) && isset($_REQUEST['mobile_no']) && isset($_REQUEST['email']) && isset($_REQUEST['password']) && isset($_REQUEST['device_id']) && isset($_REQUEST['device_type'])) {
                $user_name = $_REQUEST['user_name'];
                $mobile_no = $_REQUEST['mobile_no'];
                $email = $_REQUEST['email'];
                $password = $_REQUEST['password'];
                // $user_role = $_REQUEST['user_role'];
                $device_id = $_REQUEST['device_id'];
                $device_type = $_REQUEST['device_type'];
                $profile_picture = $_POST['profile_image'];

                if (email_exists($email)) {
                    $user = get_user_by('email', $email);
                    if (in_array("service-provider", $user->roles)) {
                        $status = 2;
                        $data['status'] = ($status > 1) ? 'failed' : 'success';
                        $errorMsg="Email already exists";
                        $data['message'] = $errorMsg;
                        echo json_encode($data);
                        die;
                    }
                }

                //if ( mbile_no_exists($mobile_no) ) {
                if (username_exists($mobile_no)) {
                    $user = get_user_by('login', $mobile_no);
                    if (in_array("service-provider", $user->roles)) {
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

                update_field('field_5b0b579195189', $mobile_no, 'user_'.$user_id);

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
                $url             = get_the_author_meta('cupp_meta', $user_id);
                $upload_url      = get_the_author_meta('cupp_upload_meta', $user_id);
                $upload_edit_url = get_the_author_meta('cupp_upload_edit_meta', $user_id);

                if ($upload_url) {
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

                foreach ($values as $key => $value) {
                    update_user_meta($user_id, $key, $value);
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
                wp_mail($to, $subject, $body, $headers);


                // $smsurl = "http://login.arihantsms.com/vendorsms/pushsms.aspx?user=zota&password=demo12345&msisdn=".$phonenumber."&sid=NVEDIC&msg=Hi,%20your%20nutravedic.com%20one%20time%20PIN%20is%20".$pin.".%20Introductory%20offer:%20Hurry%20get%20mega%20discounts%20on%20all%20nutritional%20supplements%20&%20ayurvedic%20products%20at%20nutravedic.com&fl=0&gwid=2";

                $status = 1;
                $data['status'] = ($status > 1) ? 'failed' : 'success';
                $data['pincode'] = $pincode;
                $data['user_id'] = $user_id;
                echo json_encode($data);
                die;
            } else {
                $status = 2;
                $data['status'] = ($status > 1) ? 'failed' : 'success';
                $data['message'] = "Access denied.";
                echo json_encode($data);
                die;
            }

        break;
        case "resend_otp":
            $user_id = $_REQUEST['user_id'];
            $user = get_user_by('id', $user_id);
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
                        wp_mail($to, $subject, $body, $headers);


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

            $user = get_user_by('id', $user_id);

            $data1['user_id'] = $user_id;
            $data1['user_name'] = $user->first_name;
            $data1['email'] = $user->user_email;

            $data1['profile_picture'] = get_cupp_meta($user->ID, 'thumbnail');
            $data1['address'] = (get_field('address', 'user_'.$user_id))?get_field('address', 'user_'.$user_id):"";
            $data1['city'] = (get_field('city', 'user_'.$user_id))?get_field('city', 'user_'.$user_id):"";
            $data1['pincode'] = (get_field('pincode', 'user_'.$user_id))?get_field('pincode', 'user_'.$user_id):"";
            $data1['mobile_no'] = get_field('mobile_no', 'user_'.$user_id);


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
            $mobile_no = str_replace("+91+91", "+91", $_REQUEST['mobile_no']);
            $profile_picture = $_POST['profile_picture'];
            $address = $_REQUEST['address'];
            $city = $_REQUEST['city'];
            $pincode = $_REQUEST['pincode'];


                if (email_exists($email)) {
                    $user = get_user_by('email', $email);
                    if ($user->ID != $user_id && in_array("service-provider", $user->roles)) {
                        $status = 2;
                        $data['status'] = ($status > 1) ? 'failed' : 'success';
                        $errorMsg="Email already exists";
                        $data['message'] = $errorMsg;
                        echo json_encode($data);
                        die;
                    }
                }

                //if ( mbile_no_exists($mobile_no) ) {
                if (username_exists($mobile_no)) {
                    $user = get_user_by('login', $mobile_no);
                    if ($user->ID != $user_id && in_array("service-provider", $user->roles)) {
                        $status = 2;
                        $data['status'] = ($status > 1) ? 'failed' : 'success';
                        $errorMsg="Mobile no already exists";
                        $data['message'] = $errorMsg;
                        echo json_encode($data);
                        die;
                    }
                }



            update_user_meta($user_id, 'first_name', $user_name);
            update_user_meta($user_id, 'user_email', $email);
            update_user_meta($user_id, 'nickname', $user_name);
            update_user_meta($user_id, 'display_name', $user_name);
            update_field('field_5b0b579195189', $mobile_no, 'user_'.$user_id);
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
                    $url             = get_the_author_meta('cupp_meta', $user_id);
                    $upload_url      = get_the_author_meta('cupp_upload_meta', $user_id);
                    $upload_edit_url = get_the_author_meta('cupp_upload_edit_meta', $user_id);

                    if ($upload_url) {
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

                    foreach ($values as $key => $value) {
                        update_user_meta($user_id, $key, $value);
                    }



            $data1['user_id'] = $user_id;
            $data1['user_name'] = $user->first_name;
            $data1['email'] = $user->user_email;
            $data1['mobile_no'] = get_field('mobile_no', 'user_'.$user_id);
            $data1['profile_picture'] = get_cupp_meta($user->ID, 'thumbnail');
            $data1['address'] = get_field('address', 'user_'.$user_id);
            $data1['city'] = get_field('city', 'user_'.$user_id);
            $data1['pincode'] = get_field('pincode', 'user_'.$user_id);

            $status = 1;
            $data['status'] = ($status > 1) ? 'failed' : 'success';
            $data['message'] = "User created successfully";
            $data['user_data'] = $data1;
            echo json_encode($data);
            die;
        break;
        case "submit_review":
			$post_id = $_REQUEST['service_id'];
			$user_id = $_REQUEST['user_id'];
            $review = $_REQUEST['review'];
            $rattings = $_REQUEST['rattings'];
			
				$row = array(
					'field_5b4f3b5bcf0ed'	=> $review,
					'field_5b4f3c0ecf0ee'	=> $rattings,
					'field_5b4f4011cf0ef'	=> date("Y/m/d H:i:s"),
					'field_5b4f4080cf0f0'	=> $user_id,
					'field_5b4f40b4cf0f1'	=> true,
				);
				add_row( 'field_5b4f3aa8cf0eb', $row, $post_id );
			
			
			$status = 1;
            $data['status'] = ($status > 1) ? 'failed' : 'success';
            $data['message'] = "Review submitted successfully";
            $data['user_data'] = $data1;
            echo json_encode($data);
            die;

		break;
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
