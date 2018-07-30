<?php
function setuser(){
	if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!="")
	{
		$user_id = $_REQUEST['user_id'];
		$user = get_user_by( 'id', $user_id );
		if( $user ) {
			wp_set_current_user( $user_id, $user->user_login );
			wp_set_auth_cookie( $user_id );
			
			

			//$woocommerce->cart->get_cart_from_session();
		}
	}else{
		// $status = 2;
		// $data['status'] = ($status > 1) ? 'failed' : 'success';
		// $errorMsg="Wrong info";
		// $data['message'] = $errorMsg;
		// echo json_encode($data);
		// die;
	}
}

function emailcreater($limit){
	// array of possible top-level domains
	$tlds = array("com", "net", "gov", "org", "edu", "biz", "info");

	// string of possible characters
	$char = "0123456789abcdefghijklmnopqrstuvwxyz";


	// main loop - this gives 1000 addresses
	for ($j = 0; $j < $limit; $j++) {

	  // choose random lengths for the username ($ulen) and the domain ($dlen)
	  $ulen = mt_rand(5, 10);
	  $dlen = mt_rand(3, 4);

	  // reset the address
	  $a = "";

	  // get $ulen random entries from the list of possible characters
	  // these make up the username (to the left of the @)
	  for ($i = 1; $i <= $ulen; $i++) {
		$a .= substr($char, mt_rand(0, strlen($char)), 1);
	  }

	  // wouldn't work so well without this
	  $a .= "@";

	  // now get $dlen entries from the list of possible characters
	  // this is the domain name (to the right of the @, excluding the tld)
	  for ($i = 1; $i <= $dlen; $i++) {
		$a .= substr($char, mt_rand(0, strlen($char)), 1);
	  }

	  // need a dot to separate the domain from the tld
	  $a .= ".";

	  // finally, pick a random top-level domain and stick it on the end
	  $a .= $tlds[mt_rand(0, (sizeof($tlds)-1))];

	  // done - echo the address inside a link
	  //echo $a;

	} 

	// tidy up - finish the paragraph
	return $a;
}


function random_string(){
	$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'); // and any other characters
	shuffle($seed); // probably optional since array_is randomized; this may be redundant
	$rand = '';
	foreach (array_rand($seed, 5) as $k)
	{
		$rand .= $seed[$k];
	}
	return $rand;
}


// takes URL of image and Path for the image as parameter
	function download_image1($image_url, $image_file){
		$fp = fopen ($image_file, 'x');              // open file handle

		$ch = curl_init($image_url);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
		curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      // some large value to allow curl to run for a long time
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
		// curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
		curl_exec($ch);

		curl_close($ch);                              // closing curl handle
		fclose($fp);                                  // closing file handle
	}
	

	
function generateSpecialString($length = 4) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$!';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function extractCommonWords($string){
      $stopWords = array('i','a','about','an','and','are','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','und','the','www');
 
      $string = preg_replace('/\s\s+/i', '', $string); // replace whitespace
      $string = trim($string); // trim the string
      $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too…
      $string = strtolower($string); // make it lowercase
 
      preg_match_all('/\b.*?\b/i', $string, $matchWords);
      $matchWords = $matchWords[0];
 
      foreach ( $matchWords as $key=>$item ) {
          if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3 ) {
              unset($matchWords[$key]);
          }
      }   
      $wordCountArr = array();
      if ( is_array($matchWords) ) {
          foreach ( $matchWords as $key => $val ) {
              $val = strtolower($val);
              if ( isset($wordCountArr[$val]) ) {
                  $wordCountArr[$val]++;
              } else {
                  $wordCountArr[$val] = 1;
              }
          }
      }
      arsort($wordCountArr);
      $wordCountArr = array_slice($wordCountArr, 0, 10);
      return $wordCountArr;
}


