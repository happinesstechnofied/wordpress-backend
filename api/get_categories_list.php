<?php
require_once('config.php');
//create users
require_once('functions.php');
try {

		setuser();
		//$data =array();
		$data1 =array();

		$exlude_cat = '1,11';
		// if($_REQUEST['customer_id']){
			// $exlude_cat = '1740,478,520,120,683,576,981,526,483,640';
		// }else{
			// $exlude_cat = '1740,478,511,520,120,683,576,981,526,483,640';
		// }
		
		
		$args = array(
			'parent' => 0,
			'taxonomy' => 'category',
			'orderby' => 'name',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => true,
			'title_li'     => '',
			'hide_empty' => 0,
			'exclude' => $exlude_cat,
			'show_option_none' => ''
         );

        $categories = get_categories( $args );
		//print_r($categories); die;

		foreach( $categories as $category ) {
			//print_r($category);

			if($category->category_parent == 0) {

				//echo "<br>";
				$category_id = $category->term_id;
				//echo "<br/>".$category_id;
				$category_name = $category->name;
				//echo " ".$category_name.'<br>';
				$product_count = $category->count;
				//echo " ".$product_count.'<br>';
				
		
				$image = get_field('image',"category_".$category_id);
				
				$size = array('50','50'); // (thumbnail, medium, large, full or custom size)

				if( $image ) {
					$image_url = wp_get_attachment_url($image['id'],$size);
					$image_small = wp_get_attachment_url($image['id'],'category-thumb');
					$image_medium = wp_get_attachment_url($image['id'],'category-midum');
				}else{
					$image_url = "";
					$image_small = "";
					$image_medium = "";
				}
				
				// $thumbnail_id = get_woocommerce_term_meta( $category_id, 'thumbnail_id', true );
				// //echo $thumbnail_id."<br/>";
				
				// $image_url = wp_get_attachment_url( $thumbnail_id );
				// $image_small = wp_get_attachment_image_src( $thumbnail_id, 'category-thumb');
				// $image_small = $image_small[0];
				// $image_medium = wp_get_attachment_image_src( $thumbnail_id, 'category-midum');
				// $image_medium = $image_medium[0];
				
				
				
				//echo "++".$image; die;
				//$image = basename($image_url);  // get image name from image url

				$data =array();

				$args2 = array(
					'taxonomy'     => 'category',
					'child_of'     => 0,
					'parent'       => $category_id,
					'orderby'      => 'name',
					'show_count'   => 0,
					'pad_counts'   => 0,
					'hierarchical' => true,
					'hide_empty'   => 0,
					'title_li'     => '',
					'show_option_none' => ''
			    );
				$sub_cats = get_categories( $args2 );
				//print_r($sub_cats);

					$data['parent_cat_id'] = $category_id;
					$data['parent_cat_name'] = $category_name;
					$data['product_count'] = $product_count;
					if(empty($image_url)){
						$data['image'] = '';
					}else{
						$data['image'] = $image_url;
					}
					$data['image_small'] = $image_small;
					$data['image_medium'] = $image_medium;

				if($sub_cats) {
					
					foreach($sub_cats as $sub_category) {
						//print_r($sub_category); die;
						$subcat_id = $sub_category->term_id;
						//echo  "<br/>".$subcat_id;
						$subcat_name = $sub_category->name;
						//echo  " ".$subcat_name;
						$subcat_prod_count = $sub_category->count;
						//echo  " ".$subcat_prod_count;
						
						$image = get_field('image',"category_".$subcat_id);
				
						$size = array('50','50'); // (thumbnail, medium, large, full or custom size)

						if( $image ) {
							$subcat_image = wp_get_attachment_url( $image['id'] );
							$image_url = wp_get_attachment_url($image['id'],$size);
							$image_small = wp_get_attachment_url($image['id'],'category-thumb');
							$image_medium = wp_get_attachment_url($image['id'],'category-midum');
						}else{
							$image_url = "";
							$image_small = "";
							$image_medium = "";
						}
						
						
						// $thumbnail_id = get_woocommerce_term_meta( $subcat_id, 'thumbnail_id', true );
						// //echo $thumbnail_id."<br/>";
						// $subcat_image = wp_get_attachment_url( $thumbnail_id );
						
						// $image_small = wp_get_attachment_image_src( $thumbnail_id, 'category-thumb');
						// $image_small = $image_small[0];
						// $image_medium = wp_get_attachment_image_src( $thumbnail_id, 'category-midum');
						// $image_medium = $image_medium[0];

						$args3 = array(
							'taxonomy'     => 'category',
							'child_of'     => 0,
							'parent'       => $subcat_id,
							'orderby'      => 'name',
							'show_count'   => 0,
							'pad_counts'   => 0,
							'hierarchical' => true,
							'hide_empty'   => 0,
							'title_li'     => '',
							'show_option_none' => ''
						);
						$subfinal_cats = get_categories( $args3 );
						
						$subcat = array();
						
						$subcat['parent_cat_id'] = $subcat_id;
						$subcat['parent_cat_name'] = $subcat_name;
						$subcat['product_count'] = $subcat_prod_count;
						if(empty($subcat_image)){
							$subcat['image'] = '';
						}else{
							$subcat['image'] = $subcat_image;
						}
						
						$subcat['image_small'] = $image_small;
						$subcat['image_medium'] = $image_medium;
						
						
						
						if($subfinal_cats) {
							

							foreach($subfinal_cats as $subfinal_category) {
								//print_r($subfinal_category); die;
								$subcat_id = $subfinal_category->term_id;
								//echo  "<br/>".$subcat_id;
								$subcat_name = $subfinal_category->name;
								//echo  " ".$subcat_name;
								$subcat_prod_count = $subfinal_category->count;
								//echo  " ".$subcat_prod_count;
								// $thumbnail_id = get_woocommerce_term_meta( $subcat_id, 'thumbnail_id', true );
								// //echo $thumbnail_id."<br/>";
								// $subcat_image = wp_get_attachment_url( $thumbnail_id );
								
								// $image_small = wp_get_attachment_image_src( $thumbnail_id, 'category-thumb');
								// $image_small = $image_small[0];
								// $image_medium = wp_get_attachment_image_src( $thumbnail_id, 'category-midum');
								// $image_medium = $image_medium[0];
								
								
								$image = get_field('image',"category_".$subcat_id);
				
								$size = array('50','50'); // (thumbnail, medium, large, full or custom size)

								if( $image ) {
									$subcat_image = wp_get_attachment_url( $image['id'] );
									$image_url = wp_get_attachment_url($image['id'],$size);
									$image_small = wp_get_attachment_url($image['id'],'category-thumb');
									$image_medium = wp_get_attachment_url($image['id'],'category-midum');
								}else{
									$image_url = "";
									$image_small = "";
									$image_medium = "";
								}
								
								$subcat_final = array();
									
								$subcat_final['parent_cat_id'] = $subcat_id;
								$subcat_final['parent_cat_name'] = $subcat_name;
								$subcat_final['product_count'] = $subcat_prod_count;
								if(empty($subcat_image)){
									$subcat_final['image'] = '';
								}else{
									$subcat_final['image'] = $subcat_image;
								}
								
								$subcat_final['image_small'] = $image_small;
								$subcat_final['image_medium'] = $image_medium;
								
								
								$subcat['subcategory'][] = $subcat_final;
								
								
							}
						}
						
						
						$data['subcategory'][] = $subcat;
					}
					//print_r($data['subcat']);
				}
			}

			$status = 1;
			$data1['status'] = ($status>1)? "fail" : "success";
			$data1['category'][] = $data;
			//print_r($data1); die;
		}
		echo json_encode($data1);
} catch (Exception $e) {
	$status = 2;
	$data['status'] = ($status>1)? "fail" : "success";
	$data['message'] = "Session Expired";
	echo json_encode($data);
	die;
}
