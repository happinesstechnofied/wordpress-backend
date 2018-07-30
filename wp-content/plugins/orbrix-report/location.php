<?php
define( 'BLOCK_LOAD', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/services/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/services/wp-includes/wp-db.php' );
$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

// Fetch all countries list
if(isset($_POST['action']) && $_POST['action'] == "getCountries"){
     try {
		
		
       $result = $wpdb->get_results("SELECT id, name FROM countries");
       if(!$result) {
         throw new exception("Country not found.");
       }
	   
       $res = array();
	   foreach($result as $resultSet){
			$res[$resultSet->id] = $resultSet->name;
       }
       $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$res);
	   // print_r($data);
     } catch (Exception $e) {
       $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
     }
	echo json_encode($data);
	die;
}else if(isset($_POST['action']) && isset($_POST['countryId']) && $_POST['action'] == "getStates" ){
	
	try {
		$countryId = $_POST['countryId'];
	   $result = $wpdb->get_results("SELECT id, name FROM states WHERE country_id=".$countryId);
       if(!$result) {
         throw new exception("State not found.");
       }
       $res = array();
       foreach($result as $resultSet) {
        $res[$resultSet->id] = $resultSet->name;
       }
       $data = array('status'=>'success', 'tp'=>1, 'msg'=>"States fetched successfully.", 'result'=>$res);
     } catch (Exception $e) {
       $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
     }
	
	echo json_encode($data);
	die;
}else if(isset($_POST['action']) && isset($_POST['stateId']) && $_POST['action'] == "getCities" ){
	
	try {
		$stateId = $_POST['stateId'];
	   $result = $wpdb->get_results("SELECT id, name FROM cities WHERE state_id=".$stateId);
       if(!$result) {
         throw new exception("City not found.");
       }
       $res = array();
       foreach($result as $resultSet) {
        $res[$resultSet->id] = $resultSet->name;
       }
       $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Cities fetched successfully.", 'result'=>$res);
     } catch (Exception $e) {
       $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
     }
	
	echo json_encode($data);
	die;
}else{
	
	$data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
	echo json_encode($data);
	die;
}


 // // Fetch all cities list by state id
  // public static function getCities($stateId) {
     // try {
       // $query = "SELECT id, name FROM cities WHERE state_id=".$stateId;
       // $result = dbconfig::run($query);
       // if(!$result) {
         // throw new exception("City not found.");
       // }
       // $res = array();
       // while($resultSet = mysqli_fetch_assoc($result)) {
        // $res[$resultSet['id']] = $resultSet['name'];
       // }
       // $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Cities fetched successfully.", 'result'=>$res);
     // } catch (Exception $e) {
       // $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
     // } finally {
        // return $data;
     // }
   // }   
