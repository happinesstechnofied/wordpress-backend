<?php
/*
  Plugin Name: Report - Simple Report for app data
  Plugin URI: http://orbrixtechnologies.com/
  Description: By using reporting plugin can get the report of app activity to your dashboard.
  Version: 1.0
  Author: Vishal Gandhi
  Author URI: http://orbrixtechnologies.com/
  License: GPLv2+
  Text Domain: ajax-plugin
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

        add_menu_page( 'Report by Orbrix Technologies', 'Reports', 'manage_options', 'report', array(
                          __CLASS__,
                         'OT_page_file_path'
                        ), plugins_url('images/report.png', __FILE__),'2.2.9');
						
		add_submenu_page( 'report', 'Advertise Requests' , 'Advertise Requests', 'manage_options', 'advertise-request', array(
                              __CLASS__,
                             'OT_advertise_file_path'
                            ));
							
		add_submenu_page( 'report', 'Development Requests' , 'Development Requests', 'manage_options', 'development-request', array(
                              __CLASS__,
                             'OT_development_file_path'
                            ));
							
		add_submenu_page( 'report', 'Shared Designs' , 'Shared Designs', 'manage_options', 'shared-designs', array(
                              __CLASS__,
                             'OT_shared_designs_file_path'
                            ));					
							
		add_submenu_page( 'report', 'Reported Designs' , 'Reported Designs', 'manage_options', 'reported-designs', array(
                              __CLASS__,
                             'OT_reported_designs_file_path'
                            ));
							
		add_submenu_page( 'report', 'Recommended Apps' , 'Recommended Apps', 'manage_options', 'recommended-apps', array(
                              __CLASS__,
                             'OT_recommended_apps_file_path'
                            ));
    }

    /*
     * Actions perform on loading of menu pages
     */
    function OT_page_file_path() {
		
		
		
		
		
		
		
		
		
		
		?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default" style="background: white;    margin: 15px;    padding: 15px;    margin-top: 40px;border: solid;">
					<div class="panel-heading" style="border-bottom: solid 2px;    margin-bottom: 15px;">
						<h1>Registerd Mobile Users Report</h1>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="popup-gallery">
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-admin">
											<thead>
												<tr>
													<th>#</th>
													<th>Device Id</th>
													<th>Customer Id</th>
													<th>Device Type</th>
													
													<th>Joinned On</th>
													<th>Country</th>
													<th>email</th>
													<th>Phone</th>
													<th>Full Device Id</th>
												</tr>
											</thead>
											<tbody>
											<?php
												global $wpdb;
												$results = $wpdb->get_results("SELECT * FROM `wpfr_reguser` order by added_date DESC");
												
												
												
												//print_r($results);

												$counter = 1;
												$total_investment = 0;
												foreach($results as $row)
												{
													$id=$row->user_id;
													$user_info = get_userdata($id);
														if($id==0)
														{
															?>
															
														<tr class="odd gradeX row_<?php echo $row->id;?>">
															<td><?php echo $counter;?></td>
															<td><div title="<?php echo $row->device_id;?>"><?php echo substr($row->device_id,0,10)."...";?></div></td>
															<td><?php  echo "Guest" ?></td>
															<td><?php echo ($row->device_type == 1) ? "Android" : "iphone";?></td>
															<td><?php echo date("d M Y",strtotime($row->added_date));?></td>
															<td><?php echo ""; ?></td>
															<td><?php echo ""; ?></td>
															<td><?php echo ""; ?></td>
															<td><?php echo $row->device_id;?></td>
														</tr>
															
															
															<?php
															
														
														
														}
														else
														{
														?>
														<tr class="odd gradeX row_<?php echo $row->id;?>">
															<td><?php echo $counter;?></td>
															<td><div title="<?php echo $row->device_id;?>"><?php echo substr($row->device_id,0,10)."...";?></div></td>
															<td>
															<?php
																$user_info = get_userdata($row->user_id);
																echo "<a href='".get_edit_user_link( $row->user_id )."' target='_blank'>#".$row->user_id." ".$user_info->user_login."</a>";
																?>
															
															</td>
															<td><?php echo ($row->device_type == 1) ? "Android" : "iphone";?></td>
															<td><?php echo date("d M Y",strtotime($row->added_date));?></td>
															<td><?php echo $user_info->billing_country; ?></td>
															<td><?php echo $user_info->billing_email; ?></td>
															<td><?php echo $user_info->billing_phone; ?></td>
															<td><?php echo $row->device_id;?></td>
														</tr>
														<?php
														$counter++;
												}
												}
											?>
											</tbody>
										</table>
						</div>
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<script>
		jQuery(document).ready(function() {
			jQuery('#dataTables-admin').DataTable({
				responsive: true,
				dom: 'Bfrtip',
				buttons: ['copy', 'excel', 'csv']
			});
		});
			
		</script>
	<?php
    }
	
	
	function OT_advertise_file_path(){	
		//Called by Ajax from App - list the contents of the folder so they can be downloaded and stored locally
		function change_user_status_callback() {
			// global $wpdb;
			
			// $id = $_POST['id'] ;
			// $user_status = $_POST['user_status'] ;
			
			// $result = $wpdb->update(
				// $wpdb->prefix.'advertice', 
				// array( 
					// 'status' => $user_status
				// ), 
				// array( 'id' => $id), 
				// array( 
					// '%d'
				// ), 
				// array( '%d' ) 
			// );
			
			// if ($result === false)
			// {
				// echo "fail";
			// }else{
				// echo "success";
			// }
			echo "fail";
			
			wp_die(); 
		}
		
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
		//wp_enqueue_style( 'wp-report-style', plugins_url('css/datatables.css', __FILE__));
		
		
		
		wp_enqueue_script( 'datatables-script', plugins_url( 'js/datatables.js', __FILE__ ), array(), '1.0.0', true );
		//wp_enqueue_script( 'datatables-script', plugins_url( 'js/jquery.dataTables.js', __FILE__ ));
		// wp_enqueue_script( 'datatables-editor', plugins_url( 'js/dataTables.altEditor.free.js', __FILE__ ));
		wp_enqueue_script( 'datatables-buttons', plugins_url( 'js/switchery.min.js', __FILE__ ));
		// wp_enqueue_script( 'datatables-select', plugins_url( 'js/dataTables.select.min.js', __FILE__ ));
		
		wp_localize_script( 'ajax-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
		
		
	}
	
	
	
}

new OT_razorpay_report();

