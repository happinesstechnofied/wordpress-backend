<?php
require_once('config.php');
//create users
require_once('functions.php');
require_once( ABSPATH . '/wp-admin/includes/taxonomy.php');

$tag_data = "New services created by service provider so please verify it from our backend admin-panel and live service.";
$words = extractCommonWords($tag_data);
wp_set_post_tags( '657', implode(',', array_keys($words)), true );
