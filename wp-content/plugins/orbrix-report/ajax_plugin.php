<?php
define( 'BLOCK_LOAD', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

if(isset($_POST['action']) && $_POST['action'] == "advertise_followup"){
	try{
		
		//echo "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id'] ;
		
		
		//$mylink = $wpdb->get_results( "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id']);
		
		$wpdb->update( 
			'wpfr_advertice',
			array( 
				'followup' => $_POST['followup']
			), 
			array( 'id' => $_POST['row_id'] ), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
		
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "change_advertise_status"){
	try{
		//echo "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id'] ;
		
		
		//$mylink = $wpdb->get_results( "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id']);
		
		$wpdb->update(
			'wpfr_advertice',
			array( 
				'status' => $_POST['user_status']
			), 
			array( 'id' => $_POST['id'] ), 
			array( 
				'%d'
			), 
			array( '%d' ) 
		);
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "change_development_status"){
	try{
		//echo "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id'] ;
		
		
		//$mylink = $wpdb->get_results( "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id']);
		
		$wpdb->update(
			'wpfr_contact_developer',
			array( 
				'status' => $_POST['user_status']
			), 
			array( 'id' => $_POST['id'] ), 
			array( 
				'%d'
			), 
			array( '%d' ) 
		);
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "contect_developer_followup"){
	try{
		
		//echo "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id'] ;
		
		
		//$mylink = $wpdb->get_results( "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id']);
		
		$wpdb->update( 
			'wpfr_contact_developer',
			array( 
				'followup' => $_POST['followup']
			), 
			array( 'id' => $_POST['row_id'] ), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
		
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "change_report_design_status"){
	try{
		//echo "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id'] ;
		
		
		//$mylink = $wpdb->get_results( "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id']);
		
		$wpdb->update(
			'wpfr_report_design',
			array( 
				'status' => $_POST['user_status']
			), 
			array( 'id' => $_POST['id'] ), 
			array( 
				'%d'
			), 
			array( '%d' ) 
		);
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "report_design_followup"){
	try{
		
		//echo "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id'] ;
		
		
		//$mylink = $wpdb->get_results( "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id']);
		
		$wpdb->update( 
			'wpfr_report_design',
			array( 
				'followup' => $_POST['followup']
			), 
			array( 'id' => $_POST['row_id'] ), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
		
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "add_recommended_app"){
	try{
		
		//echo "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id'] ;
		
		
		//$mylink = $wpdb->get_results( "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id']);
		$created_date=date("Y-m-d H:i:s");
		
		$wpdb->insert( 
			'wpfr_recommended_app',
			array( 
				'name'=> $_POST['app_name'],
				'link'=> $_POST['app_link'],
				'image'=> $_POST['app_image'],
				'status'=> $_POST['status'],
				'created_date' => $created_date
			), 
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%s'
			)
		);
		
		if($wpdb->insert_id){
			echo "success";
		}else{
			echo "fail";
		}
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "update_recommended_app"){
	try{
		
		$wpdb->update(
			'wpfr_recommended_app',
			array( 
				'name'=> $_POST['app_name'],
				'link'=> $_POST['app_link'],
				'image'=> $_POST['app_image'],
				//'status'=> $_POST['status'],
			), 
			array( 'id' => $_POST['row_id'] ), 
			array( 
				'%s',
				'%s',
				'%s',
				//'%d',
			), 
			array( '%d' ) 
		);
			echo "success";
		
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "change_recom_app_status"){
	try{
		//echo "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id'] ;
		
		
		//$mylink = $wpdb->get_results( "SELECT * FROM wpfr_advertice WHERE id = ".$_POST['row_id']);
		
		$wpdb->update(
			'wpfr_recommended_app',
			array( 
				'status' => $_POST['user_status']
			), 
			array( 'id' => $_POST['id'] ), 
			array( 
				'%d'
			), 
			array( '%d' ) 
		);
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete_advertise_request"){
	try{
		$id = $_REQUEST['id'];
		
		$wpdb->query('DELETE FROM wpfr_advertice WHERE id = "'.$id.'"');
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete_development_request"){
	try{
		$id = $_REQUEST['id'];
		
		$wpdb->query('DELETE FROM wpfr_contact_developer WHERE id = "'.$id.'"');
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete_reported_design"){
	try{
		$id = $_REQUEST['id'];
		
		$wpdb->query('DELETE FROM wpfr_report_design WHERE id = "'.$id.'"');
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete_recomended_app"){
	try{
		$id = $_REQUEST['id'];
		
		$wpdb->query('DELETE FROM wpfr_recommended_app WHERE id = "'.$id.'"');
		
		echo "success";
	}catch(Exception $ex){
		echo "fail";
	}
}else{
	echo "fail";
}



?>