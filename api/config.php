<?php
//error_reporting(E_ALL);
ini_set('display_errors', 1);
	define('WP_USE_THEMES', false);
	//require('../wp-blog-header.php');

	require('../wp-config.php');
	$wp->init();
	$wp->parse_request();
	$wp->query_posts();
	$wp->register_globals();