<?php
/*
  Plugin Name: Appartments - Custom plugin for handel appratmentsdata
  Plugin URI: http://orbrixtechnologies.com/
  Description: By using reporting plugin can get the report of app activity to your dashboard.
  Version: 1.0
  Author: Vishal Gandhi
  Author URI: http://orbrixtechnologies.com/
  License: GPLv2+
  Text Domain: orbrix-report
*/

class OT_razorpay_report{

  // Constructor
    function __construct() {

        add_action( 'admin_menu', array( $this, 'OT_add_menu' ));
        register_activation_hook( __FILE__, array( $this, 'OT_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'OT_uninstall' ) );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'OT_styles') );

		
    }

    /*
      * Actions perform at loading of admin menu
      */
    function OT_add_menu() {

        add_menu_page( 'Appratments', 'Appratments', 'manage_options', 'apprtments', array(
                          __CLASS__,
                         'OT_page_file_path'
                        ), plugins_url('images/apprtments.png', __FILE__),'2.2.9');
						
		// add_submenu_page( 'report', 'Recommended Apps' , 'Recommended Apps', 'manage_options', 'recommended-apps', array(
                              // __CLASS__,
                             // 'OT_recommended_apps_file_path'
                            // ));
						
		// add_submenu_page( 'report', 'Advertise Requests' , 'Advertise Requests', 'manage_options', 'advertise-request', array(
                              // __CLASS__,
                             // 'OT_advertise_file_path'
                            // ));
							
		// add_submenu_page( 'report', 'Development Requests' , 'Development Requests', 'manage_options', 'development-request', array(
                              // __CLASS__,
                             // 'OT_development_file_path'
                            // ));
							
		// add_submenu_page( 'report', 'Shared Designs' , 'Shared Designs', 'manage_options', 'shared-designs', array(
                              // __CLASS__,
                             // 'OT_shared_designs_file_path'
                            // ));					
							
		// add_submenu_page( 'report', 'Reported Designs' , 'Reported Designs', 'manage_options', 'reported-designs', array(
                              // __CLASS__,
                             // 'OT_reported_designs_file_path'
                            // ));
							
		
							
		// add_submenu_page( 'report', 'Downloaded Designs' , 'Downloaded Designs', 'manage_options', 'downloaded-designs', array(
                              // __CLASS__,
                             // 'OT_downloaded_designs_file_path'
                            // ));
							
		// add_submenu_page( 'report', 'Order Report' , 'Order Report', 'manage_options', 'order-report', array(
                              // __CLASS__,
                             // 'OT_order_report_file_path'
                            // ));		
							
    }

    /*
     * Actions perform on loading of menu pages
     */
    function OT_page_file_path() {
		include('apprtments-lists.php');
    }
	
	
	function OT_advertise_file_path(){	
		// //Called by Ajax from App - list the contents of the folder so they can be downloaded and stored locally
		// function change_user_status_callback() {
			// // global $wpdb;
			
			// // $id = $_POST['id'] ;
			// // $user_status = $_POST['user_status'] ;
			
			// // $result = $wpdb->update(
				// // $wpdb->prefix.'advertice', 
				// // array( 
					// // 'status' => $user_status
				// // ), 
				// // array( 'id' => $id), 
				// // array( 
					// // '%d'
				// // ), 
				// // array( '%d' ) 
			// // );
			
			// // if ($result === false)
			// // {
				// // echo "fail";
			// // }else{
				// // echo "success";
			// // }
			// echo "fail";
			
			// // Always exit when doing Ajax
			// exit();
		// }
		
		include('advertise-report.php');
	}
	
	function OT_development_file_path(){	
		include('development-report.php');
	}
	
	function OT_shared_designs_file_path(){	
		include('shared-designs.php');
	}
	
	function OT_reported_designs_file_path(){	
		include('reported-designs.php');
	}
	
	function OT_recommended_apps_file_path(){	
		include('recommended-apps.php');
	}
	
	function OT_downloaded_designs_file_path(){	
		include('download-designs.php');
	}
	
	function OT_order_report_file_path(){
		include('order-report.php');
	}
	



    /*
     * Actions perform on activation of plugin
     */
    function OT_install() {

		add_action('wp_head', 'myplugin_ajaxurl');

		function myplugin_ajaxurl() {

		   echo '<script type="text/javascript">
				   var ajaxurl = "' . admin_url('admin-ajax.php') . '";
				 </script>';
		}

    }

    /*
     * Actions perform on de-activation of plugin
     */
    function OT_uninstall() {



    }
	
	
	/**
	 * Styling: loading stylesheets for the plugin.
	 */
	public function OT_styles( $page ) {

		wp_enqueue_style( 'wp-datatables-style', plugins_url('css/jquery.dataTables.min.css', __FILE__));
		//wp_enqueue_style( 'wp-responsive-style', plugins_url('css/dataTables.responsive.css', __FILE__));
		wp_enqueue_style( 'wp-responsive-style', plugins_url('css/responsive.dataTables.min.css', __FILE__));
		wp_enqueue_style( 'wp-switchery-style', plugins_url('css/switchery.min.css', __FILE__));
		wp_enqueue_style( 'wp-bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__));
		//wp_enqueue_style( 'wp-report-style', plugins_url('css/datatables.css', __FILE__));
		
		
		
		wp_enqueue_script( 'datatables-script', plugins_url( 'js/datatables.js', __FILE__ ), array(), '1.0.0', true );
		//wp_enqueue_script( 'datatables-script', plugins_url( 'js/jquery.dataTables.js', __FILE__ ));
		// wp_enqueue_script( 'datatables-editor', plugins_url( 'js/dataTables.altEditor.free.js', __FILE__ ));
		wp_enqueue_script( 'datatables-buttons', plugins_url( 'js/switchery.min.js', __FILE__ ));
		wp_enqueue_script( 'bootsrtap-js', plugins_url( 'js/bootstrap.min.js', __FILE__ ));
		// wp_enqueue_script( 'datatables-select', plugins_url( 'js/dataTables.select.min.js', __FILE__ ));
		
		//wp_enqueue_script( 'location-js', plugins_url( 'js/location.js', __FILE__ ));
		
		wp_localize_script( 'ajax-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
		
		
	}
	
	
	
}

new OT_razorpay_report();


/*<style>
.dashicons-before img{
	width:18px;
}
</style>*/
