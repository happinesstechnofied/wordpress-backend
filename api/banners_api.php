<?php
require_once('config.php');
//create users
require_once('functions.php');
require_once( ABSPATH . '/wp-admin/includes/taxonomy.php');
try {
	setuser();
	switch ($_REQUEST['Service'])
	{
		
		case "get_banners":
		{
			$slider_id = 1;
			$bannerCollection = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."revslider_slides` where slider_id = ".$slider_id ." order by slide_order");
				$banner_array = array();
					foreach ($bannerCollection as $banner)
					{

						//print_r($banner);

						$sting_json = $banner->params;

						$dataobj = json_decode($sting_json);
						
						


						$banner_id = $banner->id;
						$banner_description = "";

						$image_id = $dataobj->image_id;
						
						$image_small = wp_get_attachment_image_src( $image_id, 'product-thumb');
						$image_small = $image_small[0];
						$image_medium = wp_get_attachment_image_src( $image_id, 'product-midum');
						$image_medium = $image_medium[0];
						
						$image = $dataobj->image;
						$link = $dataobj->link;

						array_push($banner_array,  array("id"=>$banner_id, "description"=>$banner_description, "image"=>$image,"imageSmall"=>$image_small,"imageMedium"=>$image_medium,"link"=>$link ));

					}
				if(count($banner_array) > 0)
				{
					$data['status'] = 'true';
					$data['banner'] = $banner_array;

				}
				else
				{
					$data['status'] = 'false';
					$data['message'] = 'No banners found';
				}
			echo json_encode($data);
			die;
		}
		break;
		default:
		{
			$data['status'] = 'false';
			$data['message'] = 'No Service Found';
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
